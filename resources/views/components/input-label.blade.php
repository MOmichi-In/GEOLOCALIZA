@props(['value'])

<label {{ $attributes->merge(['class' => 'bg-white text-black border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm background-color-white']) }}>
    {{ $value ?? $slot }}
</label>
