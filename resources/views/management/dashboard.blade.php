<!DOCTYPE html>
<html lang="ja">

    <head>
        <meta charset="UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>管理画面 | Genba Log</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="min-h-screen bg-slate-100">
        <header class="border-b border-slate-200 bg-white">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4">
                <div>
                    <p class="text-sm font-semibold text-blue-600">
                        Genba Log
                    </p>

                    <h1 class="text-xl font-bold text-slate-900">
                        管理画面
                    </h1>
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-sm font-medium text-slate-900">
                            {{ auth()->user()->name }}
                        </p>

                        <p class="text-xs text-slate-500">
                            {{ auth()->user()->role->value }}
                        </p>
                    </div>

                    <form method="POST" action="{{ route('management.logout') }}">
                        @csrf

                        <button type="submit"
                            class="rounded-lg border border-slate-300 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                            ログアウト
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-8">
            <div>
                <p class="text-sm text-slate-500">
                    {{ today()->format('Y年n月j日') }}
                </p>

                <h2 class="mt-1 text-2xl font-bold text-slate-900">
                    今日の状況
                </h2>
            </div>

            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
                <article class="rounded-xl bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">
                        稼働中の作業員
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-900">
                        {{ $activeWorkerCount }}
                        <span class="text-base font-normal">人</span>
                    </p>
                </article>

                <article class="rounded-xl bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">
                        出勤済み
                    </p>

                    <p class="mt-2 text-3xl font-bold text-green-700">
                        {{ $workedCount }}
                        <span class="text-base font-normal">人</span>
                    </p>
                </article>

                <article class="rounded-xl bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">
                        休み
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-700">
                        {{ $offCount }}
                        <span class="text-base font-normal">人</span>
                    </p>
                </article>

                <article class="rounded-xl bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">
                        未入力
                    </p>

                    <p class="mt-2 text-3xl font-bold text-red-700">
                        {{ $missingCount }}
                        <span class="text-base font-normal">人</span>
                    </p>
                </article>

                <article class="rounded-xl bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">
                        稼働中の現場
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-900">
                        {{ $activeSiteCount }}
                        <span class="text-base font-normal">件</span>
                    </p>
                </article>
            </div>

            <section class="mt-8 overflow-hidden rounded-2xl bg-white shadow-sm">
                <div class="border-b border-slate-200 px-5 py-4">
                    <h3 class="text-lg font-bold text-slate-900">
                        作業員別一覧
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    作業員
                                </th>

                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    状態
                                </th>

                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    現場
                                </th>

                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    人工
                                </th>

                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    勤務
                                </th>

                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    写真
                                </th>

                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    詳細
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach ($todayRows as $row)
                            <tr>
                                <td class="whitespace-nowrap px-4 py-4">
                                    <p class="font-medium text-slate-900">
                                        {{ $row['worker']->name }}
                                    </p>
                                </td>

                                <td class="whitespace-nowrap px-4 py-4">
                                    @if ($row['status'] === 'worked')
                                    <span
                                        class="inline-flex rounded-full bg-green-100 px-3 py-1 text-sm font-semibold text-green-800">
                                        出勤済み
                                    </span>
                                    @elseif ($row['status'] === 'off')
                                    <span
                                        class="inline-flex rounded-full bg-slate-200 px-3 py-1 text-sm font-semibold text-slate-700">
                                        休み
                                    </span>
                                    @else
                                    <span
                                        class="inline-flex rounded-full bg-red-100 px-3 py-1 text-sm font-semibold text-red-800">
                                        未入力
                                    </span>
                                    @endif
                                </td>

                                <td class="px-4 py-4 text-sm text-slate-700">
                                    {{ $row['report']?->site?->name ?? '—' }}
                                </td>

                                <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-700">
                                    @if ($row['report'])
                                    {{ number_format(
                                            (float) $row['report']->labor_units,
                                            1
                                        ) }}人工
                                    @else
                                    —
                                    @endif
                                </td>

                                <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-700">
                                    @if ($row['report'])
                                    {{ $row['report']->work_shift->value === 'night'
                                            ? '夜勤'
                                            : '通常' }}
                                    @else
                                    —
                                    @endif
                                </td>

                                <td class="whitespace-nowrap px-4 py-4 text-sm">
                                    @if ($row['report']?->photo)
                                    <span class="font-medium text-green-700">
                                        あり
                                    </span>
                                    @else
                                    <span class="text-slate-400">
                                        なし
                                    </span>
                                    @endif
                                </td>

                                <td class="whitespace-nowrap px-4 py-4 text-sm">
                                    @if ($row['report'])
                                    <a href="{{ route(
                                                'management.work-reports.show',
                                                $row['report']
                                            ) }}" class="font-semibold text-blue-600 hover:text-blue-800">
                                        確認する
                                    </a>
                                    @else
                                    <span class="text-slate-400">
                                        —
                                    </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </body>

</html>