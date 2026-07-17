<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>今日の入力 | Genba Log</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100">
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-xl items-center justify-between px-4 py-4">
            <div>
                <p class="text-sm font-semibold text-blue-600">
                    Genba Log
                </p>

                <h1 class="text-lg font-bold text-slate-900">
                    {{ $worker->name }}
                </h1>
            </div>

            <form
                method="POST"
                action="{{ route('worker.logout') }}"
            >
                @csrf

                <button
                    type="submit"
                    class="rounded-lg border border-slate-300 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
                >
                    ログアウト
                </button>
            </form>
        </div>
    </header>

    <main class="mx-auto max-w-xl px-4 py-6">
        @if (session('success'))
            <div class="mb-4 rounded-xl bg-green-50 p-4 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-xl bg-red-50 p-4 text-red-800">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <section class="rounded-2xl bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">
                今日の日付
            </p>

            <p class="mt-1 text-xl font-bold text-slate-900">
                {{ today()->format('Y年n月j日') }}
            </p>

            @if ($todayAttendance)
                @if ($todayAttendance->status === \App\Enums\AttendanceStatus::Off)
                    <div class="mt-6 rounded-xl bg-slate-100 p-4">
                        <p class="font-semibold text-slate-800">
                            本日は休みとして登録されています
                        </p>

                        <p class="mt-2 text-sm text-slate-600">
                            入力日時：
                            {{ $todayAttendance->submitted_at?->format('H:i') }}
                        </p>
                    </div>

                    <form
                        method="POST"
                        action="{{ route('worker.attendance.destroy-today') }}"
                        class="mt-4"
                    >
                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="w-full rounded-lg border border-red-300 px-4 py-3 font-semibold text-red-700 hover:bg-red-50"
                            onclick="return confirm('休みの登録を取り消しますか？')"
                        >
                            休みの登録を取り消す
                        </button>
                    </form>
                @else
                    <div class="mt-6 rounded-xl bg-green-50 p-4">
                        <p class="font-semibold text-green-800">
                            本日の入力は完了しています
                        </p>

                        @forelse ($todayAttendance->workReports as $report)
                            <div class="mt-3 border-t border-green-200 pt-3">
                                <p class="font-medium text-green-900">
                                    {{ $report->site->name }}
                                </p>

                                <p class="mt-1 text-sm text-green-700">
                                    {{ number_format((float) $report->labor_units, 1) }}人工
                                </p>

                                <p class="mt-1 text-sm text-green-700">
                                    区分：
                                    {{ $report->work_shift->value }}
                                    /
                                    {{ $report->work_role->value }}
                                </p>

                                @if ((float) $report->overtime_hours > 0)
                                    <p class="mt-1 text-sm text-green-700">
                                        残業：
                                        {{ number_format((float) $report->overtime_hours, 1) }}時間
                                    </p>
                                @endif
                            </div>
                        @empty
                            <p class="mt-3 text-sm text-green-700">
                                出勤明細はまだ登録されていません。
                            </p>
                        @endforelse
                    </div>
                @endif
            @else
                <div class="mt-6 space-y-3">
                    <p class="text-slate-700">
                        本日の出面はまだ入力されていません。
                    </p>

                    <a
                        href="{{ route('worker.work-reports.create') }}"
                        class="block w-full rounded-lg bg-blue-600 px-4 py-4 text-center text-lg font-semibold text-white hover:bg-blue-700"
                    >
                        出勤を入力
                    </a>

                    <form
                        method="POST"
                        action="{{ route('worker.attendance.off') }}"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="w-full rounded-lg border border-slate-300 px-4 py-4 text-lg font-semibold text-slate-700 hover:bg-slate-50"
                            onclick="return confirm('今日は休みとして登録しますか？')"
                        >
                            今日は休み
                        </button>
                    </form>
                </div>
            @endif
        </section>
    </main>
</body>
</html>
