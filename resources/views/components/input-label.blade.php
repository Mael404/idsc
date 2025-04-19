@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-lg text-black text-left']) }}>
    {{ $value ?? $slot }}
</label>
