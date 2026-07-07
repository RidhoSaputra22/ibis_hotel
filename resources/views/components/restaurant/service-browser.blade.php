@props([
    'categories' => [],
    'items' => [],
])

<aside class="border-b border-slate-200 bg-[#eef5f6] p-3 xl:border-b-0 xl:border-r">
    <label class="field-label" for="serviceSearch">Search Service</label>
    <div class="relative">
        <input id="serviceSearch" type="search" class="field-control pr-8" placeholder="Cari service">
        <i data-lucide="search" class="pointer-events-none absolute right-2 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400"></i>
    </div>

    <div class="mt-3 space-y-2">
        @foreach ($categories as $index => $category)
            <button type="button" data-category="{{ $category }}" class="category-button flex min-h-12 w-full items-center justify-center rounded-sm border border-[#a9c3cb] bg-[#e3f0f3] px-2 text-center text-[10px] font-bold leading-tight text-[#467383] shadow-[inset_0_1px_0_rgba(255,255,255,.9)] transition hover:border-[#62a7c2] hover:bg-[#d3ebf2] hover:text-[#216987] {{ $index === 0 ? 'ring-2 ring-[#3a98c4] ring-offset-1' : '' }}">{{ $category }}</button>
        @endforeach
    </div>
</aside>

<section class="bg-[#f9fcfd] p-3">
    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 pb-2">
        <div>
            <p class="text-[10px] font-semibold text-slate-500">Service</p>
            <h2 id="selectedCategoryTitle" class="text-sm font-bold text-slate-700">{{ $categories[0] ?? 'Service' }}</h2>
        </div>
        <button type="button" id="clearSearchButton" class="text-[10px] font-semibold text-sky-700 hover:underline">Reset Filter</button>
    </div>

    <div id="serviceGrid" class="mt-3 grid grid-cols-2 gap-2 sm:grid-cols-3">
        @foreach ($items as $item)
            <button type="button" data-service-name="{{ $item['name'] }}" data-service-price="{{ $item['price'] }}" data-service-code="{{ $item['service_code'] ?? '' }}" data-service-category="{{ $item['category'] }}" class="service-card min-h-[90px] rounded-sm border border-[#bfd0d5] bg-[#eaf3f5] p-3 text-left shadow-[inset_0_1px_0_rgba(255,255,255,.9)] transition hover:-translate-y-0.5 hover:border-[#65a9c2] hover:bg-[#d7ecf2] focus:outline-none focus:ring-4 focus:ring-sky-100">
                <span class="block text-[10px] font-bold leading-snug text-[#3f6c7e]">{{ $item['name'] }}</span>
                <span class="mt-3 block text-[10px] font-semibold text-[#267093]">Rp{{ number_format($item['price'], 0, ',', '.') }}</span>
            </button>
        @endforeach
    </div>

    <p id="noServiceMessage" class="hidden py-12 text-center text-sm text-slate-400">Service tidak ditemukan pada kategori ini.</p>
</section>
