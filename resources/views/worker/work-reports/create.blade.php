<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>出勤入力 | Genba Log</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100">
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto max-w-xl px-4 py-4">
            <a
                href="{{ route('worker.home') }}"
                class="text-sm font-medium text-blue-600"
            >
                ← 戻る
            </a>

            <h1 class="mt-2 text-xl font-bold text-slate-900">
                今日の出勤入力
            </h1>

            <p class="mt-1 text-sm text-slate-600">
                {{ $worker->name }}
                ・
                {{ today()->format('Y年n月j日') }}
            </p>
        </div>
    </header>

    <main class="mx-auto max-w-xl px-4 py-6">
        @if ($errors->any())
            <div class="mb-4 rounded-xl bg-red-50 p-4 text-red-800">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <section class="rounded-2xl bg-white p-5 shadow-sm">
            <form
                method="POST"
                action="{{ route('worker.work-reports.store') }}"
                class="space-y-7"
            >
                @csrf

                <div>
                    <label
                        for="site_id"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        現場
                    </label>

                    <select
                        id="site_id"
                        name="site_id"
                        required
                        class="w-full rounded-lg border border-slate-300 px-3 py-4 text-base"
                    >
                        <option value="">
                            現場を選択してください
                        </option>

                        @foreach ($sites as $site)
                            <option
                                value="{{ $site->id }}"
                                @selected(old('site_id') == $site->id)
                            >
                                {{ $site->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <p class="mb-2 text-sm font-semibold text-slate-700">
                        人工
                    </p>

                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input
                                type="radio"
                                name="labor_units"
                                value="1.0"
                                class="peer sr-only"
                                @checked(old('labor_units', '1.0') === '1.0')
                            >

                            <span class="block rounded-lg border border-slate-300 px-4 py-4 text-center font-semibold peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-700">
                                1人工
                            </span>
                        </label>

                        <label class="cursor-pointer">
                            <input
                                type="radio"
                                name="labor_units"
                                value="0.5"
                                class="peer sr-only"
                                @checked(old('labor_units') === '0.5')
                            >

                            <span class="block rounded-lg border border-slate-300 px-4 py-4 text-center font-semibold peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-700">
                                0.5人工
                            </span>
                        </label>
                    </div>
                </div>

                <div>
                    <p class="mb-2 text-sm font-semibold text-slate-700">
                        勤務区分
                    </p>

                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input
                                type="radio"
                                name="work_shift"
                                value="day"
                                class="peer sr-only"
                                @checked(old('work_shift', 'day') === 'day')
                            >

                            <span class="block rounded-lg border border-slate-300 px-4 py-4 text-center font-semibold peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-700">
                                通常
                            </span>
                        </label>

                        <label class="cursor-pointer">
                            <input
                                type="radio"
                                name="work_shift"
                                value="night"
                                class="peer sr-only"
                                @checked(old('work_shift') === 'night')
                            >

                            <span class="block rounded-lg border border-slate-300 px-4 py-4 text-center font-semibold peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-700">
                                夜勤
                            </span>
                        </label>
                    </div>
                </div>

                <div>
                    <p class="mb-2 text-sm font-semibold text-slate-700">
                        役割
                    </p>

                    <div class="grid grid-cols-3 gap-2">
                        @foreach ([
                            'regular' => '一般',
                            'support' => '応援',
                            'foreman' => '職長',
                        ] as $value => $label)
                            <label class="cursor-pointer">
                                <input
                                    type="radio"
                                    name="work_role"
                                    value="{{ $value }}"
                                    class="peer sr-only"
                                    @checked(old('work_role', 'regular') === $value)
                                >

                                <span class="block rounded-lg border border-slate-300 px-2 py-4 text-center font-semibold peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-700">
                                    {{ $label }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label
                        for="overtime_hours"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        残業時間
                    </label>

                    <select
                        id="overtime_hours"
                        name="overtime_hours"
                        class="w-full rounded-lg border border-slate-300 px-3 py-4 text-base"
                    >
                        @for ($hours = 0; $hours <= 12; $hours += 0.5)
                            <option
                                value="{{ number_format($hours, 1, '.', '') }}"
                                @selected(
                                    old(
                                        'overtime_hours',
                                        '0.0'
                                    ) === number_format($hours, 1, '.', '')
                                )
                            >
                                {{ number_format($hours, 1) }}時間
                            </option>
                        @endfor
                    </select>

                    <p class="mt-2 text-xs text-slate-500">
                        通常は1時間1,000円、夜勤は1時間1,250円で計算します。
                    </p>
                </div>

                <div class="border-t border-slate-200 pt-6">
                    <h2 class="text-base font-bold text-slate-900">
                        経費
                    </h2>

                    <div class="mt-4 space-y-4">
                        <div>
                            <label
                                for="highway_cost"
                                class="mb-2 block text-sm font-medium text-slate-700"
                            >
                                高速代
                            </label>

                            <div class="relative">
                                <input
                                    id="highway_cost"
                                    name="highway_cost"
                                    type="number"
                                    min="0"
                                    step="1"
                                    inputmode="numeric"
                                    value="{{ old('highway_cost', 0) }}"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-3 pr-10"
                                >

                                <span class="absolute right-3 top-3 text-slate-500">
                                    円
                                </span>
                            </div>
                        </div>

                        <div>
                            <label
                                for="parking_cost"
                                class="mb-2 block text-sm font-medium text-slate-700"
                            >
                                駐車場代
                            </label>

                            <div class="relative">
                                <input
                                    id="parking_cost"
                                    name="parking_cost"
                                    type="number"
                                    min="0"
                                    step="1"
                                    inputmode="numeric"
                                    value="{{ old('parking_cost', 0) }}"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-3 pr-10"
                                >

                                <span class="absolute right-3 top-3 text-slate-500">
                                    円
                                </span>
                            </div>
                        </div>

                        <div>
                            <label
                                for="other_cost"
                                class="mb-2 block text-sm font-medium text-slate-700"
                            >
                                その他経費
                            </label>

                            <div class="relative">
                                <input
                                    id="other_cost"
                                    name="other_cost"
                                    type="number"
                                    min="0"
                                    step="1"
                                    inputmode="numeric"
                                    value="{{ old('other_cost', 0) }}"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-3 pr-10"
                                >

                                <span class="absolute right-3 top-3 text-slate-500">
                                    円
                                </span>
                            </div>
                        </div>

                        <div>
                            <label
                                for="other_cost_note"
                                class="mb-2 block text-sm font-medium text-slate-700"
                            >
                                その他経費の内容
                            </label>

                            <input
                                id="other_cost_note"
                                name="other_cost_note"
                                type="text"
                                maxlength="255"
                                value="{{ old('other_cost_note') }}"
                                placeholder="例：資材購入"
                                class="w-full rounded-lg border border-slate-300 px-3 py-3"
                            >
                        </div>
                    </div>
                </div>

                <div>
                    <label
                        for="notes"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        備考
                        <span class="font-normal text-slate-500">
                            任意
                        </span>
                    </label>

                    <textarea
                        id="notes"
                        name="notes"
                        rows="3"
                        maxlength="1000"
                        class="w-full rounded-lg border border-slate-300 px-3 py-3"
                    >{{ old('notes') }}</textarea>
                </div>

                <button
                    type="submit"
                    class="w-full rounded-lg bg-blue-600 px-4 py-4 text-lg font-semibold text-white hover:bg-blue-700"
                    onclick="this.disabled = true; this.form.submit();"
                >
                    この内容で送信
                </button>
            </form>
        </section>
    </main>
</body>
</html>
