<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use App\Models\Worker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        if ($request->session()->has('worker_id')) {
            return redirect()->route('worker.home');
        }

        return view('worker.auth.login', [
            'workers' => Worker::query()
                ->where('is_active', true)
                ->orderBy('display_order')
                ->orderBy('name')
                ->get(['id', 'name']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'worker_id' => ['required', 'integer'],
            'pin' => ['required', 'digits:4'],
        ]);

        $worker = Worker::query()
            ->whereKey($validated['worker_id'])
            ->where('is_active', true)
            ->first();

        if (! $worker || ! Hash::check($validated['pin'], $worker->pin_hash)) {
            return back()
                ->withErrors([
                    'pin' => '名前またはPINが正しくありません。',
                ])
                ->onlyInput('worker_id');
        }

        $request->session()->regenerate();

        $request->session()->put([
            'worker_id' => $worker->id,
            'worker_name' => $worker->name,
        ]);

        $worker->forceFill([
            'last_login_at' => now(),
        ])->save();

        return redirect()->route('worker.home');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->session()->forget([
            'worker_id',
            'worker_name',
        ]);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('worker.login');
    }
}
