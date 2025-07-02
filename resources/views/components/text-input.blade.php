@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-if-green focus:ring-if-green rounded-md shadow-sm']) !!}>