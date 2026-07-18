<div class="space-y-6">
    <div>
        <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">
            現場名
        </label>

        <input id="name" name="name" type="text" maxlength="200" required value="{{ old('name', $site?->name) }}"
            class="w-full rounded-lg border border-slate-300 px-3 py-3">
    </div>

    <div>
        <label for="short_name" class="mb-2 block text-sm font-semibold text-slate-700">
            略称
            <span class="font-normal text-slate-500">任意</span>
        </label>

        <input id="short_name" name="short_name" type="text" maxlength="100"
            value="{{ old('short_name', $site?->short_name) }}"
            class="w-full rounded-lg border border-slate-300 px-3 py-3">
    </div>

    <div>
        <label for="contract_amount" class="mb-2 block text-sm font-semibold text-slate-700">
            現場の金額
            <span class="font-normal text-slate-500">税抜・任意</span>
        </label>

        <div class="relative">
            <input id="contract_amount" name="contract_amount" type="number" min="0" step="1" inputmode="numeric"
                value="{{ old('contract_amount', $site?->contract_amount) }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-3 pr-10">

            <span class="absolute right-3 top-3 text-slate-500">
                円
            </span>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="starts_on" class="mb-2 block text-sm font-semibold text-slate-700">
                開始日
            </label>

            <input id="starts_on" name="starts_on" type="date" value="{{ old(
                    'starts_on',
                    $site?->starts_on?->format('Y-m-d')
                ) }}" class="w-full rounded-lg border border-slate-300 px-3 py-3">
        </div>

        <div>
            <label for="planned_ends_on" class="mb-2 block text-sm font-semibold text-slate-700">
                終了予定日
            </label>

            <input id="planned_ends_on" name="planned_ends_on" type="date" value="{{ old(
                    'planned_ends_on',
                    $site?->planned_ends_on?->format('Y-m-d')
                ) }}" class="w-full rounded-lg border border-slate-300 px-3 py-3">
        </div>
    </div>

    <div>
        <label for="notes" class="mb-2 block text-sm font-semibold text-slate-700">
            備考
            <span class="font-normal text-slate-500">任意</span>
        </label>

        <textarea id="notes" name="notes" rows="4" maxlength="2000"
            class="w-full rounded-lg border border-slate-300 px-3 py-3">{{ old('notes', $site?->notes) }}</textarea>
    </div>
</div>