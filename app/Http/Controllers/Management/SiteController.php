<?php

namespace App\Http\Controllers\Management;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Site;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SiteController extends Controller
{
    public function index(): View
    {
        $sites = Site::query()
            ->orderByDesc('is_active')
            ->orderByDesc('starts_on')
            ->orderBy('name')
            ->paginate(20);

        return view('management.sites.index', [
            'sites' => $sites,
        ]);
    }

    public function create(): View
    {
        $this->authorizeAdmin();

        return view('management.sites.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();

        $validated = $this->validateSite($request);

        Site::query()->create([
            ...$validated,
            'is_active' => true,
            'ended_on' => null,
        ]);

        return redirect()
            ->route('management.sites.index')
            ->with('success', '現場を登録しました。');
    }

    public function edit(Site $site): View
    {
        $this->authorizeAdmin();

        return view('management.sites.edit', [
            'site' => $site,
        ]);
    }

    public function update(Request $request, Site $site): RedirectResponse
    {
        $this->authorizeAdmin();

        $validated = $this->validateSite($request, $site);

        $site->update($validated);

        return redirect()
            ->route('management.sites.index')
            ->with('success', '現場情報を更新しました。');
    }

    public function close(Request $request, Site $site): RedirectResponse
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'ended_on' => [
                'required',
                'date',
                Rule::when(
                    $site->starts_on !== null,
                    ['after_or_equal:' . $site->starts_on->format('Y-m-d')]
                ),
            ],
        ], [
            'ended_on.required' => '終了日を入力してください。',
            'ended_on.after_or_equal' => '終了日は開始日以降にしてください。',
        ]);

        $site->update([
            'ended_on' => $validated['ended_on'],
            'is_active' => false,
        ]);

        return redirect()
            ->route('management.sites.index')
            ->with('success', '現場を終了しました。');
    }

    public function reopen(Site $site): RedirectResponse
    {
        $this->authorizeAdmin();

        $site->update([
            'ended_on' => null,
            'is_active' => true,
        ]);

        return redirect()
            ->route('management.sites.index')
            ->with('success', '現場を再開しました。');
    }

    private function validateSite(
        Request $request,
        ?Site $site = null,
    ): array {
        return $request->validate([
            'name' => [
                'required',
                'string',
                'max:200',
                Rule::unique('sites', 'name')->ignore($site?->id),
            ],

            'short_name' => [
                'nullable',
                'string',
                'max:100',
            ],

            'contract_amount' => [
                'nullable',
                'integer',
                'min:0',
                'max:999999999999',
            ],

            'starts_on' => [
                'nullable',
                'date',
            ],

            'planned_ends_on' => [
                'nullable',
                'date',
                'after_or_equal:starts_on',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ], [
            'name.required' => '現場名を入力してください。',
            'name.unique' => '同じ現場名がすでに登録されています。',
            'contract_amount.integer' => '現場の金額は整数で入力してください。',
            'planned_ends_on.after_or_equal' => '終了予定日は開始日以降にしてください。',
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
