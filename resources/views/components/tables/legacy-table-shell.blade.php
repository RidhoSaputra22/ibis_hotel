@props([
    'wrapperClass' => 'overflow-auto rounded-sm border border-[#b9cbd1] bg-white',
    'tableClass' => 'w-full border-collapse text-left text-[10px] text-[#526b74]',
])

<div class="{{ $wrapperClass }}">
    <table {{ $attributes->merge(['class' => $tableClass]) }}>
        {{ $slot }}
    </table>
</div>
