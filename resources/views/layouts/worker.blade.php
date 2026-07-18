<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1">

    <title>@yield('title', '作業員画面') | Genba Log</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="min-h-screen">
        <header class="border-b border-slate-200 bg-white/95 backdrop-blur">
            <div class="mx-auto flex max-w-xl items-center justify-between px-4 py-4">
                <a
                    href="{{ route('worker.home') }}"
                    class="flex items-center gap-3">
                    <span class="genba-logo-mark">
                        G
                    </span>

                    <span>
                        <span class="block font-bold text-slate-950">
                            Genba Log
                        </span>

                        <span class="block text-xs text-slate-500">
                            今日の出面入力
                        </span>
                    </span>
                </a>

                @if (session()->has('worker_id'))
                <form
                    method="POST"
                    action="{{ route('worker.logout') }}">
                    @csrf

                    <button
                        type="submit"
                        class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-semibold text-slate-600">
                        ログアウト
                    </button>
                </form>
                @endif
            </div>
        </header>

        <main class="mx-auto max-w-xl px-4 py-6">
            @if (session('success'))
            <div class="mb-4 rounded-xl border border-green-200 bg-green-50 p-4 font-medium text-green-800">
                {{ session('success') }}
            </div>
            @endif

            @if ($errors->any())
            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-red-800">
                @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
                @endforeach
            </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>

</html>