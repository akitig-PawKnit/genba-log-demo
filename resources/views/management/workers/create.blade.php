<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1">

    <title>作業員追加 | Genba Log</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100">
    <main class="mx-auto max-w-2xl px-4 py-8">
        <a
            href="{{ route('management.workers.index') }}"
            class="text-sm font-semibold text-blue-600">
            ← 作業員一覧へ戻る
        </a>

        <section class="mt-4 rounded-2xl bg-white p-6 shadow-sm">
            <h1 class="text-2xl font-bold text-slate-900">
                作業員を追加
            </h1>

            @if ($errors->any())
            <div class="mt-5 rounded-xl bg-red-50 p-4 text-red-800">
                @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
                @endforeach
            </div>
            @endif

            <form
                method="POST"
                action="{{ route('management.workers.store') }}"
                class="mt-6 space-y-5">
                @csrf

                <div>
                    <label class="mb-2 block text-sm font-semibold">
                        氏名
                    </label>

                    <input
                        name="name"
                        type="text"
                        required
                        value="{{ old('name') }}"
                        class="w-full rounded-lg border border-slate-300 px-3 py-3">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold">
                        社員コード
                    </label>

                    <input
                        name="employee_code"
                        type="text"
                        value="{{ old('employee_code') }}"
                        class="w-full rounded-lg border border-slate-300 px-3 py-3">
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold">
                            4桁PIN
                        </label>

                        <input
                            name="pin"
                            type="password"
                            inputmode="numeric"
                            maxlength="4"
                            required
                            class="w-full rounded-lg border border-slate-300 px-3 py-3">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold">
                            PIN確認
                        </label>

                        <input
                            name="pin_confirmation"
                            type="password"
                            inputmode="numeric"
                            maxlength="4"
                            required
                            class="w-full rounded-lg border border-slate-300 px-3 py-3">
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold">
                        日額
                    </label>

                    <input
                        name="daily_rate"
                        type="number"
                        min="0"
                        required
                        value="{{ old('daily_rate') }}"
                        class="w-full rounded-lg border border-slate-300 px-3 py-3">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold">
                        日額適用開始日
                    </label>

                    <input
                        name="rate_effective_from"
                        type="date"
                        required
                        value="{{ old(
                            'rate_effective_from',
                            today()->toDateString()
                        ) }}"
                        class="w-full rounded-lg border border-slate-300 px-3 py-3">
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold">
                            入社日
                        </label>

                        <input
                            name="joined_on"
                            type="date"
                            value="{{ old('joined_on') }}"
                            class="w-full rounded-lg border border-slate-300 px-3 py-3">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold">
                            表示順
                        </label>

                        <input
                            name="display_order"
                            type="number"
                            min="0"
                            value="{{ old('display_order', 0) }}"
                            class="w-full rounded-lg border border-slate-300 px-3 py-3">
                    </div>
                </div>

                <button
                    type="submit"
                    class="w-full rounded-lg bg-blue-600 px-4 py-4 text-lg font-semibold text-white">
                    登録する
                </button>
            </form>
        </section>
    </main>
</body>

</html>