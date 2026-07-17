<?php

namespace App\Http\Middleware;

use App\Models\Worker;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureWorkerAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        $workerId = $request->session()->get('worker_id');

        if (! $workerId) {
            return redirect()->route('worker.login');
        }

        $workerExists = Worker::query()
            ->whereKey($workerId)
            ->where('is_active', true)
            ->exists();

        if (! $workerExists) {
            $request->session()->forget([
                'worker_id',
                'worker_name',
            ]);

            return redirect()
                ->route('worker.login')
                ->withErrors([
                    'worker_id' => 'この作業員アカウントは現在利用できません。',
                ]);
        }

        return $next($request);
    }
}
