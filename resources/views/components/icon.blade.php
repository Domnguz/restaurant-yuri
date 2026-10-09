@props(['name' => 'plate'])
@php
    $paths = [
        'home' => 'M3 10 12 3l9 7M5 9v12h5v-7h4v7h5V9',
        'orders' => 'M6 3h12v18l-3-2-3 2-3-2-3 2V3ZM9 8h6M9 12h6M9 16h3',
        'plate' => 'M3 12h18M5 12a7 7 0 0 1 14 0M12 3v2M3 16h18M6 20h12',
        'categories' => 'M3 3h7v7H3ZM14 3h7v7h-7ZM3 14h7v7H3ZM14 14h7v7h-7Z',
        'table' => 'M4 11h16M5 11v10M19 11v10M8 11V5h8v6M8 16h8',
        'user' => 'M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0ZM4 21v-2a8 8 0 0 1 16 0v2',
        'logout' => 'M9 3H4v18h5M14 8l5 4-5 4M8 12h11',
        'arrow' => 'M4 12h16M14 6l6 6-6 6',
        'plus' => 'M12 5v14M5 12h14',
        'search' => 'M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0ZM16 16l6 6',
        'menu' => 'M4 6h16M4 12h16M4 18h16',
        'close' => 'm6 6 12 12M6 18 18 6',
        'money' => 'M3 5h18v14H3ZM15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM6 8h.01M18 16h.01',
        'chart' => 'M3 3v18h18M7 15l4-5 4 3 6-8',
        'clock' => 'M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM12 7v5l3 2',
        'drink' => 'M5 7h14l-2 14H7ZM8 7V3h8M12 7v10',
        'check' => 'm5 12 4 4L19 6',
        'bag' => 'M5 7h14l2 14H3ZM8 7V6a4 4 0 0 1 8 0v1',
    ];
@endphp
<svg {{ $attributes->class(['ui-icon']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="{{ $paths[$name] ?? $paths['plate'] }}"/></svg>
