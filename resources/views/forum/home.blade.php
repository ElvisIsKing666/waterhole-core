<x-waterhole::layout>
    <x-slot name="head">
        <link
            rel="alternate"
            type="application/rss+xml"
            href="{{ route('waterhole.rss.posts') }}"
        />
    </x-slot>

    {{-- Custom full-width layout with IndexNav as main content --}}
    <div class="section container">
        <div class="index-nav-full-width">
            {{-- Display the IndexNav component as the main content --}}
            <x-waterhole::index-nav />
        </div>
    </div>
</x-waterhole::layout>

<style>
/* Custom styles for full-width navbar layout */
.index-nav-full-width {
    width: 100%;
    max-width: none;
}

/* Override the collapsible-nav to display as a full-width grid */
.index-nav-full-width .collapsible-nav {
    display: block !important; /* Override the popup behavior */
}

.index-nav-full-width .drawer {
    display: block !important; /* Show the drawer content directly */
    position: static !important;
    transform: none !important;
    box-shadow: none !important;
    border: none !important;
    background: transparent !important;
    padding: 0 !important;
    margin: 0 !important;
    width: 100% !important;
    height: auto !important;
    max-height: none !important;
    overflow: visible !important;
}

/* Style the navigation as a grid layout */
.index-nav-full-width .nav {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: var(--space-md);
    padding: var(--space-xl) 0;
}

/* Style individual nav items */
.index-nav-full-width .nav-link {
    display: flex;
    align-items: center;
    gap: var(--space-xs);
    padding: var(--space-md);
    background: var(--palette-surface);
    border: 1px solid var(--palette-stroke);
    border-radius: var(--radius-md);
    text-decoration: none;
    color: var(--palette-text);
    transition: all 0.2s ease;
    min-height: 3rem;
    font-weight: 500;
}

.index-nav-full-width .nav-link:hover {
    background: var(--palette-fill);
    border-color: var(--palette-accent);
    color: var(--palette-accent);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px var(--palette-fill-soft);
}

.index-nav-full-width .nav-link.is-active {
    background: var(--palette-accent);
    color: var(--palette-accent-contrast);
    border-color: var(--palette-accent);
}

/* Style nav headings */
.index-nav-full-width .nav-heading {
    grid-column: 1 / -1;
    text-align: center;
    font-weight: 600;
    color: var(--palette-muted);
    margin: var(--space-lg) 0 var(--space-sm) 0;
    font-size: var(--text-sm);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 1px solid var(--palette-stroke);
    padding-bottom: var(--space-sm);
}

/* Style the icon in nav links */
.index-nav-full-width .nav-link .icon {
    width: 1.25rem;
    height: 1.25rem;
    flex-shrink: 0;
}

/* Style the label */
.index-nav-full-width .nav-link .label {
    flex: 1;
    text-align: center;
}

/* Responsive design */
@media (max-width: 768px) {
    .index-nav-full-width .nav {
        grid-template-columns: 1fr;
        gap: var(--space-sm);
    }
    
    .index-nav-full-width .nav-link {
        justify-content: center;
        text-align: center;
    }
}

/* Footer styling */
.index-nav-full-width .index-footer {
    grid-column: 1 / -1;
    text-align: center;
    margin-top: var(--space-xl);
    padding-top: var(--space-lg);
    border-top: 1px solid var(--palette-stroke);
    color: var(--palette-muted);
    font-size: var(--text-sm);
}

/* Hide the popup button since we're showing the nav directly */
.index-nav-full-width .btn {
    display: none !important;
}
</style>
