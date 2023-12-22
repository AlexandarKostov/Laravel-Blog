@props(['textColor', 'bgColor'])

@php
    $textColor = match ($textColor) {
       'gray' => 'text-gray-800',
       'blue' => 'text-blue-800',
       'green' => 'text-green-800',
       'red' => 'text-red-800',
       'orange' => 'text-orange-800',
       'yellow' => 'text-yellow-800',
       default => 'text-black-100',
    };

    $bgColor = match ($bgColor) {
       'gray' => 'bg-gray-800',
       'blue' => 'bg-blue-100',
       'green' => 'bg-green-100',
       'red' => 'bg-red-100',
       'orange' => 'bg-orange-100',
       'yellow' => 'bg-yellow-100',
       default => 'bg-gray-100',
    };
@endphp

<button {{ $attributes }} class="{{$textColor}} {{$bgColor}} rounded-xl px-3 py-1 text-base">
    {{ $slot }}</button>
