@props(['disabled' => false])
<input @disabled($disabled) {{ $attributes->merge(['class' => 'search-input focus:border-orange-600 focus:ring-orange-600/20']) }}>
