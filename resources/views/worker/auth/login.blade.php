<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>作業員ログイン | Genba Log</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100">
    <main class="flex min-h-screen items-center justify-center px-4 py-8">
        <section class="w-full max-w-md rounded-2xl bg-white p-6 shadow-sm">
            <div class="mb-8">
                <p class="text-sm font-semibold text-blue-600">
                    Genba Log
                </p>

                <h1 class="mt-2 text-2xl font-bold text-slate-900">
                    作業員ログイン
                </h1>

                <p class="mt-2 text-sm text-slate-600">
                    名前を選び、4桁のPINを入力してください。
                </p>
            </div>

            <form
                method="POST"
                action="{{ route('worker.login.store') }}"
                class="space-y-5"
            >
                @csrf

                <div>
                    <label
                        for="worker_id"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        名前
                    </label>

                    <select
                        id="worker_id"
                        name="worker_id"
                        required
                        class="w-full rounded-lg border border-slate-300 px-3 py-3 text-base"
                    >
                        <option value="">
                            選択してください
                        </option>

                        @foreach ($workers as $worker)
                            <option
                                value="{{ $worker->id }}"
                                @selected(old('worker_id') == $worker->id)
                            >
                                {{ $worker->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('worker_id')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label
                        for="pin"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        4桁PIN
                    </label>

                    <input
                        id="pin"
                        name="pin"
                        type="password"
                        inputmode="numeric"
                        pattern="[0-9]{4}"
                        maxlength="4"
                        autocomplete="current-password"
                        required
                        class="w-full rounded-lg border border-slate-300 px-3 py-3 text-center text-2xl tracking-[0.5em]"
                    >

                    @error('pin')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="w-full rounded-lg bg-blue-600 px-4 py-4 text-lg font-semibold text-white hover:bg-blue-700"
                >
                    ログイン
                </button>
            </form>
        </section>
    </main>
</body>
</html>
