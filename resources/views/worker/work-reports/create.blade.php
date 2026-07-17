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
                enctype="multipart/form-data"
                class="space-y-7"
                id="work-report-form"
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
                            @php
                                $value = number_format($hours, 1, '.', '');
                            @endphp

                            <option
                                value="{{ $value }}"
                                @selected(old('overtime_hours', '0.0') === $value)
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

                <div class="border-t border-slate-200 pt-6">
                    <h2 class="text-base font-bold text-slate-900">
                        現場写真
                        <span class="font-normal text-slate-500">
                            任意・1枚
                        </span>
                    </h2>

                    <p class="mt-1 text-sm text-slate-600">
                        写真を選ぶと、可能であれば現在地も取得します。
                    </p>

                    <div class="mt-4">
                        <label
                            for="photo"
                            class="block cursor-pointer rounded-xl border-2 border-dashed border-slate-300 px-4 py-8 text-center hover:bg-slate-50"
                        >
                            <span class="block text-base font-semibold text-slate-800">
                                現場写真を撮る・選ぶ
                            </span>

                            <span
                                id="photo-name"
                                class="mt-2 block text-sm text-slate-500"
                            >
                                写真はまだ選択されていません
                            </span>
                        </label>

                        <input
                            id="photo"
                            name="photo"
                            type="file"
                            accept="image/jpeg,image/png,image/webp,image/heic"
                            capture="environment"
                            class="sr-only"
                        >

                        @error('photo')
                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div
                        id="location-status"
                        class="mt-3 rounded-lg bg-slate-100 p-3 text-sm text-slate-600"
                    >
                        写真を選ぶと位置情報を取得します。
                    </div>

                    <input
                        id="latitude"
                        name="latitude"
                        type="hidden"
                        value="{{ old('latitude') }}"
                    >

                    <input
                        id="longitude"
                        name="longitude"
                        type="hidden"
                        value="{{ old('longitude') }}"
                    >

                    <input
                        id="location_accuracy"
                        name="location_accuracy"
                        type="hidden"
                        value="{{ old('location_accuracy') }}"
                    >

                    <input
                        id="location_captured_at"
                        name="location_captured_at"
                        type="hidden"
                        value="{{ old('location_captured_at') }}"
                    >
                </div>

                <button
                    type="submit"
                    id="submit-button"
                    class="w-full rounded-lg bg-blue-600 px-4 py-4 text-lg font-semibold text-white hover:bg-blue-700 disabled:cursor-not-allowed disabled:bg-slate-400"
                >
                    この内容で送信
                </button>
            </form>
        </section>
    </main>

    <script>
        const form = document.getElementById('work-report-form');
        const submitButton = document.getElementById('submit-button');

        const photoInput = document.getElementById('photo');
        const photoName = document.getElementById('photo-name');
        const locationStatus = document.getElementById('location-status');

        const latitudeInput = document.getElementById('latitude');
        const longitudeInput = document.getElementById('longitude');
        const accuracyInput = document.getElementById('location_accuracy');
        const capturedAtInput = document.getElementById(
            'location_captured_at'
        );

        photoInput.addEventListener('change', () => {
            const file = photoInput.files[0];

            if (! file) {
                photoName.textContent = '写真はまだ選択されていません';
                locationStatus.textContent =
                    '写真を選ぶと位置情報を取得します。';

                latitudeInput.value = '';
                longitudeInput.value = '';
                accuracyInput.value = '';
                capturedAtInput.value = '';

                return;
            }

            photoName.textContent = file.name;

            if (! navigator.geolocation) {
                locationStatus.textContent =
                    'この端末では位置情報を取得できません。写真のみ送信します。';

                return;
            }

            locationStatus.textContent = '位置情報を取得しています…';

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    latitudeInput.value =
                        position.coords.latitude.toString();

                    longitudeInput.value =
                        position.coords.longitude.toString();

                    accuracyInput.value =
                        position.coords.accuracy.toString();

                    capturedAtInput.value =
                        new Date(position.timestamp).toISOString();

                    locationStatus.textContent =
                        `位置情報を取得しました（誤差 約${Math.round(
                            position.coords.accuracy
                        )}m）`;
                },
                () => {
                    latitudeInput.value = '';
                    longitudeInput.value = '';
                    accuracyInput.value = '';
                    capturedAtInput.value = '';

                    locationStatus.textContent =
                        '位置情報を取得できませんでした。写真のみ送信します。';
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0,
                }
            );
        });

        form.addEventListener('submit', () => {
            submitButton.disabled = true;
            submitButton.textContent = '送信中…';
        });
    </script>
</body>
</html>
