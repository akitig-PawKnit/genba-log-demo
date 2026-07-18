<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Genba Log | 現場・出面管理</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <main class="flex min-h-screen items-center justify-center px-4 py-10">
        <div class="w-full max-w-4xl">
            <header class="text-center">
                <div class="mx-auto flex justify-center">
                    <span class="genba-logo-mark h-16 w-16 text-2xl">
                        G
                    </span>
                </div>

                <p class="mt-5 text-sm font-bold tracking-wider text-blue-600">
                    現場・出面管理システム
                </p>

                <h1 class="mt-2 text-4xl font-bold tracking-tight text-slate-950 sm:text-5xl">
                    Genba Log
                </h1>

                <p class="mx-auto mt-4 max-w-xl text-base leading-7 text-slate-600">
                    毎日の出面入力と、現場・作業員・人件費の管理を、
                    ひとつの画面にまとめます。
                </p>
            </header>

            <section class="mt-10 grid gap-5 md:grid-cols-2">
                <a href="{{ route('worker.login') }}"
                    class="app-card group block p-6 transition hover:-translate-y-1 hover:border-blue-300 hover:shadow-lg">
                    <div class="flex items-start justify-between gap-4">
                        <span
                            class="grid h-14 w-14 shrink-0 place-items-center rounded-2xl bg-blue-100 text-xl font-black text-blue-700">
                            作
                        </span>

                        <span
                            class="text-2xl text-slate-300 transition group-hover:translate-x-1 group-hover:text-blue-600">
                            →
                        </span>
                    </div>

                    <h2 class="mt-6 text-2xl font-bold text-slate-950">
                        作業員の方
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        名前と4桁PINでログインし、今日の現場・人工・残業・経費などを入力します。
                    </p>

                    <span class="app-button-primary mt-6 w-full">
                        出面を入力する
                    </span>
                </a>

                <a href="{{ route('management.login') }}"
                    class="app-card group block p-6 transition hover:-translate-y-1 hover:border-slate-400 hover:shadow-lg">
                    <div class="flex items-start justify-between gap-4">
                        <span
                            class="grid h-14 w-14 shrink-0 place-items-center rounded-2xl bg-slate-900 text-xl font-black text-white">
                            管
                        </span>

                        <span
                            class="text-2xl text-slate-300 transition group-hover:translate-x-1 group-hover:text-slate-700">
                            →
                        </span>
                    </div>

                    <h2 class="mt-6 text-2xl font-bold text-slate-950">
                        管理者・閲覧者の方
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        今日の入力状況や、現場・作業員・写真・人件費などを確認します。
                    </p>

                    <span class="app-button-secondary mt-6 w-full">
                        管理画面を開く
                    </span>
                </a>
            </section>

            <footer class="mt-10 text-center">
                <p class="text-xs text-slate-400">
                    Genba Log Demo
                </p>

                <p class="mt-1 text-xs text-slate-400">
                    Powered by PawKnit
                </p>
            </footer>
        </div>
    </main>
</body>

</html>