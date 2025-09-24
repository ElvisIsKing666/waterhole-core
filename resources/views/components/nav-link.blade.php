<a {{
    $attributes
        ->merge(['href' => $href ?: ($route ? route($route) : null)])
        ->class([$attributes->has('class') ? '' : 'nav-link', 'is-active' => $isActive])
    }}>
    <div class="nav-link-content">
        @icon($icon)
        <span class="label">{{ $label }}</span>
        {{ $slot ?? null }}
        @isset($badge)
            <span class="badge {{ $badgeClass }}">{{ $badge }}</span>
        @endisset
    </div>
    @if($attributes->has('data-description'))
        <div class="description">{{ $attributes->get('data-description') }}</div>
    @endif
</a>