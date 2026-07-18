<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1">

    <title>@yield('title', '管理画面') | Genba Log</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="app-shell lg:flex">
        <aside class="border-b border-slate-200 bg-slate-950 text-white lg:fixed lg:inset-y-0 lg:w-64 lg:border-b-0 lg:border-r lg:border-slate-800">
            <div class="flex items-center justify-between px-5 py-5 lg:block">
                <a
                    href="{{ route('management.dashboard') }}"
                    class="flex items-center gap-3">
                    <span class="genba-logo-mark">
                        G
                    </span>

                    <span>
                        <span class="block text-lg font-bold">
                            Genba Log
                        </span>

                        <span class="block text-xs text-slate-400">
                            現場・出面管理
                        </span>
                    </span>
                </a>
            </div>

            <nav class="flex gap-2 overflow-x-auto px-4 pb-4 lg:block lg:space-y-1 lg:overflow-visible lg:pb-0">
                <a
                    href="{{ route('management.dashboard') }}"
                    class="block whitespace-nowrap rounded-xl px-4 py-3 text-sm font-semibold transition
                        {{ request()->routeIs('management.dashboard')
                            ? 'bg-blue-600 text-white'
                            : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    今日の状況
                </a>

                <a
                    href="{{ route('management.sites.index') }}"
                    class="block whitespace-nowrap rounded-xl px-4 py-3 text-sm font-semibold transition
                        {{ request()->routeIs('management.sites.*')
                            ? 'bg-blue-600 text-white'
                            : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    現場管理
                </a>

                <a
                    href="{{ route('management.workers.index') }}"
                    class="block whitespace-nowrap rounded-xl px-4 py-3 text-sm font-semibold transition
                        {{ request()->routeIs('management.workers.*')
                            ? 'bg-blue-600 text-white'
                            : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    作業員管理
                </a>
            </nav>

            <div class="hidden px-5 py-5 lg:absolute lg:inset-x-0 lg:bottom-0 lg:block">
                <div class="border-t border-slate-800 pt-5">
                    <p class="text-sm font-semibold">
                        {{ auth()->user()->name }}
                    </p>

                    <p class="mt-1 text-xs text-slate-400">
                        {{ auth()->user()->isAdmin()
                            ? '管理者'
                            : '閲覧者' }}
                    </p>

                    <form
                        method="POST"
                        action="{{ route('management.logout') }}"
                        class="mt-4">
                        @csrf

                        <button
                            type="submit"
                            class="w-full rounded-lg border border-slate-700 px-3 py-2 text-sm font-semibold text-slate-300 hover:bg-slate-800">
                            ログアウト
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <div class="min-w-0 flex-1 lg:ml-64">
            <header class="border-b border-slate-200 bg-white/90 backdrop-blur">
                <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6">
                    <p class="text-sm font-semibold text-blue-600">
                        @yield('eyebrow', 'Genba Log')
                    </p>

                    <div class="mt-1 flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-bold text-slate-950">
                                @yield('heading')
                            </h1>

                            @hasSection('description')
                            <p class="mt-1 text-sm text-slate-500">
                                @yield('description')
                            </p>
                            @endif
                        </div>

                        <div>
                            @yield('header-actions')
                        </div>
                    </div>
                </div>
            </header>

            <main class="mx-auto max-w-7xl px-4 py-7 sm:px-6">
                @if (session('success'))
                <div class="mb-5 rounded-xl border border-green-200 bg-green-50 p-4 text-green-800">
                    {{ session('success') }}
                </div>
                @endif

                @if ($errors->any())
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-red-800">
                    @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                    @endforeach
                </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>