<?php

namespace App\Http\Controllers\Management;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Worker;
use App\Models\WorkerRate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Support\Carbon;

class WorkerController extends Controller
{
    public function index(): View
    {
        $workers = Worker::query()
            ->with([
                'rates' => fn($query) => $query
                    ->orderByDesc('effective_from'),
            ])
            ->orderByDesc('is_active')
            ->orderBy('display_order')
            ->orderBy('name')
            ->paginate(20);

        return view('management.workers.index', [
            'workers' => $workers,
        ]);
    }

    public function create(): View
    {
        $this->authorizeAdmin();

        return view('management.workers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();

        $validated = $this->validateWorker($request);

        DB::transaction(function () use ($validated): void {
            $worker = Worker::query()->create([
                'employee_code' => $validated['employee_code'] ?? null,
                'name' => $validated['name'],
                'pin_hash' => Hash::make($validated['pin']),
                'display_order' => $validated['display_order'],
                'joined_on' => $validated['joined_on'] ?? null,
                'left_on' => null,
                'is_active' => true,
            ]);

            WorkerRate::query()->create([
                'worker_id' => $worker->id,
                'daily_rate' => $validated['daily_rate'],
                'effective_from' => $validated['rate_effective_from'],
                'effective_to' => null,
            ]);
        });

        return redirect()
            ->route('management.workers.index')
            ->with('success', '作業員を登録しました。');
    }

    public function edit(Worker $worker): View
    {
        $this->authorizeAdmin();

        $worker->load([
            'rates' => fn($query) => $query
                ->orderByDesc('effective_from'),
        ]);

        return view('management.workers.edit', [
            'worker' => $worker,
        ]);
    }

    public function update(
        Request $request,
        Worker $worker,
    ): RedirectResponse {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'employee_code' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('workers', 'employee_code')
                    ->ignore($worker->id),
            ],

            'name' => [
                'required',
                'string',
                'max:100',
            ],

            'display_order' => [
                'required',
                'integer',
                'min:0',
                'max:9999',
            ],

            'joined_on' => [
                'nullable',
                'date',
            ],
        ], [
            'name.required' => '氏名を入力してください。',
            'employee_code.unique' => '同じ社員コードが登録されています。',
        ]);

        $worker->update($validated);

        return redirect()
            ->route('management.workers.index')
            ->with('success', '作業員情報を更新しました。');
    }

    public function updatePin(
        Request $request,
        Worker $worker,
    ): RedirectResponse {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'pin' => [
                'required',
                'digits:4',
                'confirmed',
            ],
        ], [
            'pin.required' => '新しいPINを入力してください。',
            'pin.digits' => 'PINは4桁の数字で入力してください。',
            'pin.confirmed' => '確認用PINが一致しません。',
        ]);

        $worker->update([
            'pin_hash' => Hash::make($validated['pin']),
        ]);

        return redirect()
            ->route('management.workers.edit', $worker)
            ->with('success', 'PINを変更しました。');
    }

    public function updateRate(
        Request $request,
        Worker $worker,
    ): RedirectResponse {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'daily_rate' => [
                'required',
                'integer',
                'min:0',
                'max:1000000',
            ],

            'effective_from' => [
                'required',
                'date',
            ],
        ], [
            'daily_rate.required' => '日額を入力してください。',
            'effective_from.required' => '適用開始日を入力してください。',
        ]);

        DB::transaction(function () use ($worker, $validated): void {
            $effectiveFrom = $validated['effective_from'];

            $previousRate = WorkerRate::query()
                ->where('worker_id', $worker->id)
                ->whereDate('effective_from', '<', $effectiveFrom)
                ->where(function ($query) use ($effectiveFrom): void {
                    $query
                        ->whereNull('effective_to')
                        ->orWhereDate('effective_to', '>=', $effectiveFrom);
                })
                ->orderByDesc('effective_from')
                ->first();

            if ($previousRate) {
                $previousRate->update([
                    'effective_to' => Carbon::parse($effectiveFrom)
                        ->subDay()
                        ->toDateString(),
                ]);
            }

            WorkerRate::query()->updateOrCreate(
                [
                    'worker_id' => $worker->id,
                    'effective_from' => $effectiveFrom,
                ],
                [
                    'daily_rate' => $validated['daily_rate'],
                    'effective_to' => null,
                ],
            );
        });

        return redirect()
            ->route('management.workers.edit', $worker)
            ->with('success', '日額を変更しました。');
    }

    public function retire(
        Request $request,
        Worker $worker,
    ): RedirectResponse {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'left_on' => [
                'required',
                'date',
                Rule::when(
                    $worker->joined_on !== null,
                    ['after_or_equal:' . $worker->joined_on->format('Y-m-d')]
                ),
            ],
        ], [
            'left_on.required' => '退職日を入力してください。',
            'left_on.after_or_equal' => '退職日は入社日以降にしてください。',
        ]);

        $worker->update([
            'left_on' => $validated['left_on'],
            'is_active' => false,
        ]);

        return redirect()
            ->route('management.workers.index')
            ->with('success', '作業員を退職扱いにしました。');
    }

    public function reactivate(Worker $worker): RedirectResponse
    {
        $this->authorizeAdmin();

        $worker->update([
            'left_on' => null,
            'is_active' => true,
        ]);

        return redirect()
            ->route('management.workers.index')
            ->with('success', '作業員を再開しました。');
    }

    private function validateWorker(Request $request): array
    {
        return $request->validate([
            'employee_code' => [
                'nullable',
                'string',
                'max:50',
                'unique:workers,employee_code',
            ],

            'name' => [
                'required',
                'string',
                'max:100',
            ],

            'pin' => [
                'required',
                'digits:4',
                'confirmed',
            ],

            'display_order' => [
                'required',
                'integer',
                'min:0',
                'max:9999',
            ],

            'joined_on' => [
                'nullable',
                'date',
            ],

            'daily_rate' => [
                'required',
                'integer',
                'min:0',
                'max:1000000',
            ],

            'rate_effective_from' => [
                'required',
                'date',
            ],
        ]);
    }

    private function authorizeAdmin(): void
    {
        abort_unless(
            auth()->user()?->role === UserRole::Admin,
            403,
            'この操作は管理者のみ実行できます。',
        );
    }
}
