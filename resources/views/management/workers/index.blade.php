<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1">

    <title>作業員管理 | Genba Log</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100">
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4">
            <div>
                <a
                    href="{{ route('management.dashboard') }}"
                    class="text-sm font-semibold text-blue-600">
                    ← ダッシュボード
                </a>

                <h1 class="mt-1 text-xl font-bold text-slate-900">
                    作業員管理
                </h1>
            </div>

            @if (auth()->user()->isAdmin())
            <a
                href="{{ route('management.workers.create') }}"
                class="rounded-lg bg-blue-600 px-4 py-3 font-semibold text-white">
                作業員を追加
            </a>
            @endif
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8">
        @if (session('success'))
        <div class="mb-5 rounded-xl bg-green-50 p-4 text-green-800">
            {{ session('success') }}
        </div>
        @endif

        <section class="overflow-hidden rounded-2xl bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500">
                                作業員
                            </th>

                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500">
                                状態
                            </th>

                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500">
                                社員コード
                            </th>

                            <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500">
                                現在日額
                            </th>

                            <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500">
                                操作
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @foreach ($workers as $worker)
                        @php
                        $currentRate = $worker->rates
                        ->first(function ($rate) {
                        return $rate->effective_from <= today()
                            && (
                            $rate->effective_to === null
                            || $rate->effective_to >= today()
                            );
                            });
                            @endphp

                            <tr>
                                <td class="px-4 py-4">
                                    <p class="font-semibold text-slate-900">
                                        {{ $worker->name }}
                                    </p>

                                    @if ($worker->left_on)
                                    <p class="mt-1 text-sm text-slate-500">
                                        退職日：
                                        {{ $worker->left_on->format('Y/m/d') }}
                                    </p>
                                    @endif
                                </td>

                                <td class="whitespace-nowrap px-4 py-4">
                                    @if ($worker->is_active)
                                    <span class="rounded-full bg-green-100 px-3 py-1 text-sm font-semibold text-green-800">
                                        在籍
                                    </span>
                                    @else
                                    <span class="rounded-full bg-slate-200 px-3 py-1 text-sm font-semibold text-slate-700">
                                        退職
                                    </span>
                                    @endif
                                </td>

                                <td class="px-4 py-4 text-sm text-slate-700">
                                    {{ $worker->employee_code ?: '—' }}
                                </td>

                                <td class="whitespace-nowrap px-4 py-4 text-right font-medium text-slate-900">
                                    @if ($currentRate)
                                    {{ number_format($currentRate->daily_rate) }}円
                                    @else
                                    未設定
                                    @endif
                                </td>

                                <td class="whitespace-nowrap px-4 py-4 text-right">
                                    @if (auth()->user()->isAdmin())
                                    <a
                                        href="{{ route(
                                                'management.workers.edit',
                                                $worker
                                            ) }}"
                                        class="rounded-lg border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-700">
                                        編集
                                    </a>
                                    @else
                                    <span class="text-sm text-slate-400">
                                        閲覧のみ
                                    </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <div class="mt-6">
            {{ $workers->links() }}
        </div>
    </main>
</body>

</html>