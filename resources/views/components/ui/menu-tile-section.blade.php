@props([
    'title',
    'items' => [],
    'color' => '#11a9ed',
    'gridClass' => 'grid-cols-2',
    'requiresCashierLogin' => false,
    'loginModal' => 'cashierLoginModal',
])

<section>
    <h2 class="mb-2 text-xs font-bold text-slate-300">{{ $title }}</h2>
    <div class="grid gap-1.5 {{ $gridClass }}">
        @foreach ($items as $item)
            <x-ui.menu-tile
                :label="data_get($item, 'label')"
                :icon="data_get($item, 'icon', 'square')"
                :color="data_get($item, 'color', $color)"
                :modal="data_get($item, 'modal')"
                :href="data_get($item, 'href')"
                :requires-cashier-login="data_get($item, 'requiresCashierLogin', $requiresCashierLogin)"
                :login-modal="data_get($item, 'loginModal', $loginModal)"
            />
        @endforeach
    </div>
</section>
