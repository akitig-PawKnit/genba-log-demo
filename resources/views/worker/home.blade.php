@extends('layouts.worker')

@section('title', '今日の入力')

@section('content')
<section class="mb-5">
    <p class="text-sm font-semibold text-blue-600">
        {{ today()->format('Y年n月j日') }}
    </p>

    <h1 class="mt-1 text-2xl font-bold text-slate-950">
        {{ $worker->name }}さん
    </h1>

    <p class="mt-1 text-sm text-slate-500">
        今日の出面を入力してください。
    </p>
</section>

@if ($todayAttendance)
@if ($todayAttendance->status === \App\Enums\AttendanceStatus::Off)
<section class="app-card overflow-hidden">
    <div class="border-b border-slate-200 bg-slate-50 px-5 py-5">
        <span class="status-off inline-flex rounded-full px-3 py-1 text-sm font-bold">
            休み
        </span>

        <h2 class="mt-4 text-xl font-bold text-slate-950">
            本日は休みとして登録されています
        </h2>

        <p class="mt-2 text-sm text-slate-500">
            登録時刻：
            {{ $todayAttendance->submitted_at?->format('H:i') ?? '—' }}
        </p>
    </div>

    <div class="p-5">
        <form method="POST" action="{{ route('worker.attendance.destroy-today') }}">
            @csrf
            @method('DELETE')

            <button type="submit" class="app-button-danger w-full" onclick="return confirm('休みの登録を取り消しますか？')">
                休みの登録を取り消す
            </button>
        </form>
    </div>
</section>
@else
<section class="app-card overflow-hidden">
    <div class="border-b border-green-200 bg-green-50 px-5 py-5">
        <span class="status-worked inline-flex rounded-full px-3 py-1 text-sm font-bold">
            入力完了
        </span>

        <h2 class="mt-4 text-xl font-bold text-green-950">
            本日の入力は完了しています
        </h2>
    </div>

    <div class="divide-y divide-slate-100">
        @forelse ($todayAttendance->workReports as $report)
        <article class="p-5">
            <p class="text-sm font-semibold text-slate-500">
                現場
            </p>

            <h3 class="mt-1 text-lg font-bold text-slate-950">
                {{ $report->site->name }}
            </h3>

            <div class="mt-5 grid grid-cols-2 gap-3">
                <div class="rounded-xl bg-slate-50 p-4">
                    <p class="text-xs font-semibold text-slate-500">
                        人工
                    </p>

                    <p class="mt-1 text-lg font-bold text-slate-900">
                        {{ number_format(
                                            (float) $report->labor_units,
                                            1
                                        ) }}人工
                    </p>
                </div>

                <div class="rounded-xl bg-slate-50 p-4">
                    <p class="text-xs font-semibold text-slate-500">
                        勤務区分
                    </p>

                    <p class="mt-1 text-lg font-bold text-slate-900">
                        {{ $report->work_shift->value === 'night'
                                            ? '夜勤'
                                            : '通常' }}
                    </p>
                </div>

                <div class="rounded-xl bg-slate-50 p-4">
                    <p class="text-xs font-semibold text-slate-500">
                        役割
                    </p>

                    <p class="mt-1 text-lg font-bold text-slate-900">
                        @switch($report->work_role->value)
                        @case('support')
                        応援
                        @break

                        @case('foreman')
                        職長
                        @break

                        @default
                        一般
                        @endswitch
                    </p>
                </div>

                <div class="rounded-xl bg-slate-50 p-4">
                    <p class="text-xs font-semibold text-slate-500">
                        残業
                    </p>

                    <p class="mt-1 text-lg font-bold text-slate-900">
                        {{ number_format(
                                            (float) $report->overtime_hours,
                                            1
                                        ) }}時間
                    </p>
                </div>
            </div>

            <div class="mt-4 rounded-xl border border-slate-200 p-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-500">
                        現場写真
                    </span>

                    <span class="{{ $report->photo
                                        ? 'font-bold text-green-700'
                                        : 'text-slate-400' }}">
                        {{ $report->photo ? '送信済み' : 'なし' }}
                    </span>
                </div>
            </div>
        </article>
        @empty
        <div class="p-5 text-sm text-slate-500">
            出勤明細が見つかりません。
        </div>
        @endforelse
    </div>
</section>
@endif
@else
<section class="app-card overflow-hidden">
    <div class="border-b border-slate-200 bg-white px-5 py-5">
        <span class="status-missing inline-flex rounded-full px-3 py-1 text-sm font-bold">
            未入力
        </span>

        <h2 class="mt-4 text-xl font-bold text-slate-950">
            本日の出面はまだ入力されていません
        </h2>

        <p class="mt-2 text-sm leading-6 text-slate-500">
            出勤した場合は「出勤を入力」、休みの場合は「今日は休み」を押してください。
        </p>
    </div>

    <div class="space-y-3 p-5">
        <a href="{{ route('worker.work-reports.create') }}" class="app-button-primary w-full py-4 text-lg">
            出勤を入力
        </a>

        <form method="POST" action="{{ route('worker.attendance.off') }}">
            @csrf

            <button type="submit" class="app-button-secondary w-full py-4 text-lg"
                onclick="return confirm('今日は休みとして登録しますか？')">
                今日は休み
            </button>
        </form>
    </div>
</section>

<section class="mt-5 rounded-2xl border border-blue-100 bg-blue-50 p-5">
    <p class="font-bold text-blue-950">
        入力は1分ほどで完了します
    </p>

    <p class="mt-2 text-sm leading-6 text-blue-800">
        現場を選び、人工・勤務区分・経費などを入力して送信してください。
    </p>
</section>
@endif
@endsection