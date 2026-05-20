@props(['disabled' => false])
@props(['placeholder' => ''])
@props(['value' => ''])

<textarea {{ $disabled ? 'disabled' : '' }} 
    {!! $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) !!}
    rows="4" placeholder="{{ $placeholder }}">{{ $value }}</textarea>
