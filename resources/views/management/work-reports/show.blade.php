<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>出勤詳細 | Genba Log</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100">
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto max-w-5xl px-4 py-4">
            <a
                href="{{ route('management.dashboard') }}"
                class="text-sm font-semibold text-blue-600 hover:text-blue-800"
            >
                ← ダッシュボードへ戻る
            </a>

            <h1 class="mt-2 text-xl font-bold text-slate-900">
                出勤詳細
            </h1>
        </div>
    </header>

    <main class="mx-auto max-w-5xl px-4 py-8">
        <div class="grid gap-6 lg:grid-cols-2">
            <section class="rounded-2xl bg-white p-6 shadow-sm">
                <h2 class="text-lg font-bold text-slate-900">
                    基本情報
                </h2>

                <dl class="mt-5 divide-y divide-slate-100">
                    <div class="flex justify-between gap-4 py-3">
                        <dt class="text-sm text-slate-500">
                            日付
                        </dt>

                        <dd class="text-right font-medium text-slate-900">
                            {{ $workReport->dailyAttendance->work_date->format('Y年n月j日') }}
                        </dd>
                    </div>

                    <div class="flex justify-between gap-4 py-3">
                        <dt class="text-sm text-slate-500">
                            作業員
                        </dt>

                        <dd class="text-right font-medium text-slate-900">
                            {{ $workReport->dailyAttendance->worker->name }}
                        </dd>
                    </div>

                    <div class="flex justify-between gap-4 py-3">
                        <dt class="text-sm text-slate-500">
                            現場
                        </dt>

                        <dd class="text-right font-medium text-slate-900">
                            {{ $workReport->site->name }}
                        </dd>
                    </div>

                    <div class="flex justify-between gap-4 py-3">
                        <dt class="text-sm text-slate-500">
                            人工
                        </dt>

                        <dd class="text-right font-medium text-slate-900">
                            {{ number_format(
                                (float) $workReport->labor_units,
                                1
                            ) }}人工
                        </dd>
                    </div>

                    <div class="flex justify-between gap-4 py-3">
                        <dt class="text-sm text-slate-500">
                            勤務区分
                        </dt>

                        <dd class="text-right font-medium text-slate-900">
                            {{ $workReport->work_shift->value === 'night'
                                ? '夜勤'
                                : '通常' }}
                        </dd>
                    </div>

                    <div class="flex justify-between gap-4 py-3">
                        <dt class="text-sm text-slate-500">
                            役割
                        </dt>

                        <dd class="text-right font-medium text-slate-900">
                            @switch($workReport->work_role->value)
                                @case('support')
                                    応援
                                    @break

                                @case('foreman')
                                    職長
                                    @break

                                @default
                                    一般
                            @endswitch
                        </dd>
                    </div>

                    <div class="flex justify-between gap-4 py-3">
                        <dt class="text-sm text-slate-500">
                            残業
                        </dt>

                        <dd class="text-right font-medium text-slate-900">
                            {{ number_format(
                                (float) $workReport->overtime_hours,
                                1
                            ) }}時間
                        </dd>
                    </div>

                    <div class="py-3">
                        <dt class="text-sm text-slate-500">
                            備考
                        </dt>

                        <dd class="mt-2 whitespace-pre-wrap text-sm text-slate-900">
                            {{ $workReport->notes ?: 'なし' }}
                        </dd>
                    </div>
                </dl>
            </section>

            <section class="rounded-2xl bg-white p-6 shadow-sm">
                <h2 class="text-lg font-bold text-slate-900">
                    金額
                </h2>

                <dl class="mt-5 divide-y divide-slate-100">
                    <div class="flex justify-between gap-4 py-3">
                        <dt class="text-sm text-slate-500">
                            適用日額
                        </dt>

                        <dd class="font-medium text-slate-900">
                            {{ number_format(
                                $workReport->daily_rate_snapshot
                            ) }}円
                        </dd>
                    </div>

                    <div class="flex justify-between gap-4 py-3">
                        <dt class="text-sm text-slate-500">
                            基本人件費
                        </dt>

                        <dd class="font-medium text-slate-900">
                            {{ number_format(
                                $workReport->base_labor_cost
                            ) }}円
                        </dd>
                    </div>

                    <div class="flex justify-between gap-4 py-3">
                        <dt class="text-sm text-slate-500">
                            残業代
                        </dt>

                        <dd class="font-medium text-slate-900">
                            {{ number_format(
                                $workReport->overtime_cost
                            ) }}円
                        </dd>
                    </div>

                    <div class="flex justify-between gap-4 py-3">
                        <dt class="text-sm text-slate-500">
                            高速代
                        </dt>

                        <dd class="font-medium text-slate-900">
                            {{ number_format(
                                $workReport->highway_cost
                            ) }}円
                        </dd>
                    </div>

                    <div class="flex justify-between gap-4 py-3">
                        <dt class="text-sm text-slate-500">
                            駐車場代
                        </dt>

                        <dd class="font-medium text-slate-900">
                            {{ number_format(
                                $workReport->parking_cost
                            ) }}円
                        </dd>
                    </div>

                    <div class="flex justify-between gap-4 py-3">
                        <dt class="text-sm text-slate-500">
                            その他経費
                        </dt>

                        <dd class="font-medium text-slate-900">
                            {{ number_format(
                                $workReport->other_cost
                            ) }}円
                        </dd>
                    </div>

                    @if ($workReport->other_cost > 0)
                        <div class="py-3">
                            <dt class="text-sm text-slate-500">
                                その他経費の内容
                            </dt>

                            <dd class="mt-2 text-sm text-slate-900">
                                {{ $workReport->other_cost_note }}
                            </dd>
                        </div>
                    @endif

                    <div class="flex justify-between gap-4 py-4">
                        <dt class="font-bold text-slate-900">
                            合計原価
                        </dt>

                        <dd class="text-xl font-bold text-blue-700">
                            {{ number_format(
                                $workReport->total_cost
                            ) }}円
                        </dd>
                    </div>
                </dl>
            </section>
        </div>

        <section class="mt-6 rounded-2xl bg-white p-6 shadow-sm">
            <h2 class="text-lg font-bold text-slate-900">
                現場写真・位置情報
            </h2>

            @if ($workReport->photo)
                <div class="mt-5 grid gap-6 lg:grid-cols-2">
                    <div>
                        <img
                            src="{{ asset(
                                'storage/'.$workReport->photo->file_path
                            ) }}"
                            alt="現場写真"
                            class="max-h-[600px] w-full rounded-xl bg-slate-100 object-contain"
                        >
                    </div>

                    <div>
                        <dl class="divide-y divide-slate-100">
                            <div class="py-3">
                                <dt class="text-sm text-slate-500">
                                    ファイル名
                                </dt>

                                <dd class="mt-1 break-all text-sm font-medium text-slate-900">
                                    {{ $workReport->photo->original_name }}
                                </dd>
                            </div>

                            <div class="flex justify-between gap-4 py-3">
                                <dt class="text-sm text-slate-500">
                                    アップロード日時
                                </dt>

                                <dd class="text-right text-sm font-medium text-slate-900">
                                    {{ $workReport->photo->uploaded_at->format('Y/m/d H:i') }}
                                </dd>
                            </div>

                            <div class="py-3">
                                <dt class="text-sm text-slate-500">
                                    位置情報
                                </dt>

                                @if (
                                    $workReport->photo->latitude !== null
                                    && $workReport->photo->longitude !== null
                                )
                                    <dd class="mt-2 text-sm text-slate-900">
                                        緯度：
                                        {{ $workReport->photo->latitude }}
                                    </dd>

                                    <dd class="mt-1 text-sm text-slate-900">
                                        経度：
                                        {{ $workReport->photo->longitude }}
                                    </dd>

                                    @if (
                                        $workReport->photo->location_accuracy
                                        !== null
                                    )
                                        <dd class="mt-1 text-sm text-slate-600">
                                            精度：
                                            約{{ number_format(
                                                (float) $workReport->photo->location_accuracy
                                            ) }}m
                                        </dd>
                                    @endif

                                    <a
                                        href="https://www.google.com/maps?q={{ $workReport->photo->latitude }},{{ $workReport->photo->longitude }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="mt-4 inline-flex rounded-lg bg-blue-600 px-4 py-3 font-semibold text-white hover:bg-blue-700"
                                    >
                                        地図で確認
                                    </a>
                                @else
                                    <dd class="mt-2 text-sm text-slate-500">
                                        位置情報は取得されていません。
                                    </dd>
                                @endif
                            </div>
                        </dl>
                    </div>
                </div>
            @else
                <p class="mt-4 text-slate-500">
                    写真は登録されていません。
                </p>
            @endif
        </section>
    </main>
</body>
</html>
