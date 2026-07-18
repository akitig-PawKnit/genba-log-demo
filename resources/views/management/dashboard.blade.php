@extends('layouts.management')

@section('title', '今日の状況')
@section('eyebrow', today()->format('Y年n月j日'))
@section('heading', '今日の状況')
@section('description', '作業員の出勤・休み・未入力を確認できます。')

@section('header-actions')
<div class="flex flex-wrap gap-2">
    <a href="{{ route('management.sites.index') }}" class="app-button-secondary">
        現場管理
    </a>

    <a href="{{ route('management.workers.index') }}" class="app-button-primary">
        作業員管理
    </a>
</div>
@endsection

@section('content')
<section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
    <article class="app-card p-5">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm font-semibold text-slate-500">
                    稼働中の作業員
                </p>

                <p class="mt-3 text-3xl font-bold text-slate-950">
                    {{ $activeWorkerCount }}
                    <span class="text-base font-medium text-slate-500">
                        人
                    </span>
                </p>
            </div>

            <span class="grid h-10 w-10 place-items-center rounded-xl bg-blue-50 text-lg text-blue-700">
                人
            </span>
        </div>
    </article>

    <article class="app-card p-5">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm font-semibold text-slate-500">
                    出勤済み
                </p>

                <p class="mt-3 text-3xl font-bold text-green-700">
                    {{ $workedCount }}
                    <span class="text-base font-medium text-slate-500">
                        人
                    </span>
                </p>
            </div>

            <span class="grid h-10 w-10 place-items-center rounded-xl bg-green-50 font-bold text-green-700">
                ✓
            </span>
        </div>
    </article>

    <article class="app-card p-5">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm font-semibold text-slate-500">
                    休み
                </p>

                <p class="mt-3 text-3xl font-bold text-slate-700">
                    {{ $offCount }}
                    <span class="text-base font-medium text-slate-500">
                        人
                    </span>
                </p>
            </div>

            <span class="grid h-10 w-10 place-items-center rounded-xl bg-slate-100 font-bold text-slate-600">
                休
            </span>
        </div>
    </article>

    <article class="app-card p-5">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm font-semibold text-slate-500">
                    未入力
                </p>

                <p class="mt-3 text-3xl font-bold text-red-700">
                    {{ $missingCount }}
                    <span class="text-base font-medium text-slate-500">
                        人
                    </span>
                </p>
            </div>

            <span class="grid h-10 w-10 place-items-center rounded-xl bg-red-50 font-bold text-red-700">
                !
            </span>
        </div>
    </article>

    <article class="app-card p-5">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm font-semibold text-slate-500">
                    稼働中の現場
                </p>

                <p class="mt-3 text-3xl font-bold text-slate-950">
                    {{ $activeSiteCount }}
                    <span class="text-base font-medium text-slate-500">
                        件
                    </span>
                </p>
            </div>

            <span class="grid h-10 w-10 place-items-center rounded-xl bg-amber-50 font-bold text-amber-700">
                現
            </span>
        </div>
    </article>
</section>

<section class="app-card mt-6 overflow-hidden">
    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-5 py-5">
        <div>
            <h2 class="text-lg font-bold text-slate-950">
                作業員別一覧
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                本日の入力状況を作業員ごとに表示しています。
            </p>
        </div>

        <div class="flex gap-2 text-xs font-semibold">
            <span class="status-worked rounded-full px-3 py-1">
                出勤済み
            </span>

            <span class="status-off rounded-full px-3 py-1">
                休み
            </span>

            <span class="status-missing rounded-full px-3 py-1">
                未入力
            </span>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="border-b border-slate-200 bg-slate-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-bold tracking-wide text-slate-500">
                        作業員
                    </th>

                    <th class="px-5 py-3 text-left text-xs font-bold tracking-wide text-slate-500">
                        状態
                    </th>

                    <th class="px-5 py-3 text-left text-xs font-bold tracking-wide text-slate-500">
                        現場
                    </th>

                    <th class="px-5 py-3 text-left text-xs font-bold tracking-wide text-slate-500">
                        人工
                    </th>

                    <th class="px-5 py-3 text-left text-xs font-bold tracking-wide text-slate-500">
                        勤務
                    </th>

                    <th class="px-5 py-3 text-left text-xs font-bold tracking-wide text-slate-500">
                        写真
                    </th>

                    <th class="px-5 py-3 text-right text-xs font-bold tracking-wide text-slate-500">
                        詳細
                    </th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
                @foreach ($todayRows as $row)
                <tr class="transition hover:bg-slate-50">
                    <td class="whitespace-nowrap px-5 py-4">
                        <p class="font-bold text-slate-900">
                            {{ $row['worker']->name }}
                        </p>

                        @if ($row['worker']->employee_code)
                        <p class="mt-1 text-xs text-slate-400">
                            {{ $row['worker']->employee_code }}
                        </p>
                        @endif
                    </td>

                    <td class="whitespace-nowrap px-5 py-4">
                        @if ($row['status'] === 'worked')
                        <span class="status-worked inline-flex rounded-full px-3 py-1 text-sm font-bold">
                            出勤済み
                        </span>
                        @elseif ($row['status'] === 'off')
                        <span class="status-off inline-flex rounded-full px-3 py-1 text-sm font-bold">
                            休み
                        </span>
                        @else
                        <span class="status-missing inline-flex rounded-full px-3 py-1 text-sm font-bold">
                            未入力
                        </span>
                        @endif
                    </td>

                    <td class="min-w-56 px-5 py-4 text-sm text-slate-700">
                        {{ $row['report']?->site?->name ?? '—' }}
                    </td>

                    <td class="whitespace-nowrap px-5 py-4 text-sm font-medium text-slate-700">
                        @if ($row['report'])
                        {{ number_format(
                                        (float) $row['report']->labor_units,
                                        1
                                    ) }}人工
                        @else
                        —
                        @endif
                    </td>

                    <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-700">
                        @if ($row['report'])
                        {{ $row['report']->work_shift->value === 'night'
                                        ? '夜勤'
                                        : '通常' }}
                        @else
                        —
                        @endif
                    </td>

                    <td class="whitespace-nowrap px-5 py-4 text-sm">
                        @if ($row['report']?->photo)
                        <span class="font-bold text-green-700">
                            あり
                        </span>
                        @else
                        <span class="text-slate-400">
                            なし
                        </span>
                        @endif
                    </td>

                    <td class="whitespace-nowrap px-5 py-4 text-right">
                        @if ($row['report'])
                        <a href="{{ route(
                                            'management.work-reports.show',
                                            $row['report']
                                        ) }}"
                            class="inline-flex rounded-lg bg-blue-50 px-3 py-2 text-sm font-bold text-blue-700 hover:bg-blue-100">
                            確認する
                        </a>
                        @else
                        <span class="text-slate-300">
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
@endsection