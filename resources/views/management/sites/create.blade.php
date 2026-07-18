<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1">

    <title>現場追加 | Genba Log</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100">
    <main class="mx-auto max-w-2xl px-4 py-8">
        <a
            href="{{ route('management.sites.index') }}"
            class="text-sm font-semibold text-blue-600">
            ← 現場一覧へ戻る
        </a>

        <section class="mt-4 rounded-2xl bg-white p-6 shadow-sm">
            <h1 class="text-2xl font-bold text-slate-900">
                現場を追加
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
                action="{{ route('management.sites.store') }}"
                class="mt-6">
                @csrf

                @include('management.sites._form', [
                'site' => null,
                ])

                <button
                    type="submit"
                    class="mt-8 w-full rounded-lg bg-blue-600 px-4 py-4 text-lg font-semibold text-white">
                    登録する
                </button>
            </form>
        </section>
    </main>
</body>

</html>