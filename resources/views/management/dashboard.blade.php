<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>管理画面 | Genba Log</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100">
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4">
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

                <form
                    method="POST"
                    action="{{ route('management.logout') }}"
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
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-4 py-8">
        <h2 class="text-2xl font-bold text-slate-900">
            ダッシュボード
        </h2>

        <div class="mt-6 grid gap-4 sm:grid-cols-2">
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
                    稼働中の現場
                </p>

                <p class="mt-2 text-3xl font-bold text-slate-900">
                    {{ $activeSiteCount }}
                    <span class="text-base font-normal">件</span>
                </p>
            </article>
        </div>
    </main>
</body>
</html>
