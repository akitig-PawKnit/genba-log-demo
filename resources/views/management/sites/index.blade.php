<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1">

    <title>現場管理 | Genba Log</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100">
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4">
            <div>
                <a
                    href="{{ route('management.dashboard') }}"
                    class="text-sm font-semibold text-blue-600">
                    ← ダッシュボード
                </a>

                <h1 class="mt-1 text-xl font-bold text-slate-900">
                    現場管理
                </h1>
            </div>

            @if (auth()->user()->isAdmin())
            <a
                href="{{ route('management.sites.create') }}"
                class="rounded-lg bg-blue-600 px-4 py-3 font-semibold text-white hover:bg-blue-700">
                現場を追加
            </a>
            @endif
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8">
        @if (session('success'))
        <div class="mb-5 rounded-xl bg-green-50 p-4 text-green-800">
            {{ session('success') }}
        </div>
        @endif

        <section class="overflow-hidden rounded-2xl bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500">
                                現場名
                            </th>

                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500">
                                状態
                            </th>

                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500">
                                期間
                            </th>

                            <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500">
                                現場金額
                            </th>

                            <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500">
                                操作
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse ($sites as $site)
                        <tr>
                            <td class="px-4 py-4">
                                <p class="font-semibold text-slate-900">
                                    {{ $site->name }}
                                </p>

                                @if ($site->short_name)
                                <p class="mt-1 text-sm text-slate-500">
                                    {{ $site->short_name }}
                                </p>
                                @endif
                            </td>

                            <td class="whitespace-nowrap px-4 py-4">
                                @if ($site->is_active)
                                <span class="rounded-full bg-green-100 px-3 py-1 text-sm font-semibold text-green-800">
                                    稼働中
                                </span>
                                @else
                                <span class="rounded-full bg-slate-200 px-3 py-1 text-sm font-semibold text-slate-700">
                                    終了
                                </span>
                                @endif
                            </td>

                            <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-700">
                                {{ $site->starts_on?->format('Y/m/d') ?? '未設定' }}

                                <span class="mx-1">〜</span>

                                {{ $site->ended_on?->format('Y/m/d')
                                        ?? $site->planned_ends_on?->format('Y/m/d')
                                        ?? '未定' }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-4 text-right text-sm font-medium text-slate-900">
                                @if ($site->contract_amount !== null)
                                {{ number_format($site->contract_amount) }}円
                                @else
                                未設定
                                @endif
                            </td>

                            <td class="whitespace-nowrap px-4 py-4 text-right">
                                @if (auth()->user()->isAdmin())
                                <div class="flex justify-end gap-2">
                                    <a
                                        href="{{ route(
                                                    'management.sites.edit',
                                                    $site
                                                ) }}"
                                        class="rounded-lg border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-700">
                                        編集
                                    </a>

                                    @if ($site->is_active)
                                    <form
                                        method="POST"
                                        action="{{ route(
                                                        'management.sites.close',
                                                        $site
                                                    ) }}"
                                        onsubmit="return confirm('この現場を終了しますか？')">
                                        @csrf
                                        @method('PATCH')

                                        <input
                                            type="hidden"
                                            name="ended_on"
                                            value="{{ today()->toDateString() }}">

                                        <button
                                            type="submit"
                                            class="rounded-lg border border-red-300 px-3 py-2 text-sm font-semibold text-red-700">
                                            終了
                                        </button>
                                    </form>
                                    @else
                                    <form
                                        method="POST"
                                        action="{{ route(
                                                        'management.sites.reopen',
                                                        $site
                                                    ) }}">
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="rounded-lg border border-green-300 px-3 py-2 text-sm font-semibold text-green-700">
                                            再開
                                        </button>
                                    </form>
                                    @endif
                                </div>
                                @else
                                <span class="text-sm text-slate-400">
                                    閲覧のみ
                                </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td
                                colspan="5"
                                class="px-4 py-12 text-center text-slate-500">
                                現場はまだ登録されていません。
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <div class="mt-6">
            {{ $sites->links() }}
        </div>
    </main>
</body>

</html>