<?php

declare(strict_types=1);

use Illuminate\Support\Facades\View;
use Illuminate\View\ComponentAttributeBag;
use Waterhole\View\Components\NavLink;

it('should render title attribute in nav-link template', function () {
    // Create a NavLink component with title attribute
    $navLink = new NavLink(
        label: 'Test Channel',
        icon: 'tabler-message-circle',
        href: '/channels/test'
    );
    
    // Add title attribute using withAttributes
    $navLinkWithTitle = $navLink->withAttributes(['title' => 'This is a test channel with HTML tags']);
    
    // Render the actual nav-link template
    $html = View::make('waterhole::components.nav-link', [
        'label' => $navLinkWithTitle->label,
        'icon' => $navLinkWithTitle->icon,
        'href' => $navLinkWithTitle->href,
        'isActive' => $navLinkWithTitle->isActive,
        'attributes' => new ComponentAttributeBag(['title' => 'This is a test channel with HTML tags']),
    ])->render();
    
    // Assert the title attribute is present in the rendered HTML
    expect($html)->toContain('title="This is a test channel with HTML tags"');
    expect($html)->toContain('Test Channel');
    expect($html)->toContain('nav-link');
});

it('should render nav-link without title attribute when not provided', function () {
    // Create a NavLink component without title attribute
    $navLink = new NavLink(
        label: 'No Tooltip Channel',
        icon: 'tabler-message-circle',
        href: '/channels/no-tooltip'
    );
    
    // Render the actual nav-link template
    $html = View::make('waterhole::components.nav-link', [
        'label' => $navLink->label,
        'icon' => $navLink->icon,
        'href' => $navLink->href,
        'isActive' => $navLink->isActive,
        'attributes' => new ComponentAttributeBag(),
    ])->render();
    
    // Assert no title attribute is present
    expect($html)->not->toContain('title=');
    expect($html)->toContain('No Tooltip Channel');
    expect($html)->toContain('nav-link');
});

it('should handle HTML-escaped content in title attributes', function () {
    // Create a NavLink component with special characters in title
    $navLink = new NavLink(
        label: 'Special Channel',
        icon: 'tabler-star',
        href: '/channels/special'
    );
    
    // Render the actual nav-link template with special characters
    $html = View::make('waterhole::components.nav-link', [
        'label' => $navLink->label,
        'icon' => $navLink->icon,
        'href' => $navLink->href,
        'isActive' => $navLink->isActive,
        'attributes' => new ComponentAttributeBag(['title' => 'Description with "quotes" & ampersands']),
    ])->render();
    
    // Assert the title attribute is present (ComponentAttributeBag handles escaping)
    expect($html)->toContain('title="Description with \"quotes\" & ampersands"');
    expect($html)->toContain('Special Channel');
});

it('should render active state with title attribute', function () {
    // Create a NavLink component that's active
    $navLink = new NavLink(
        label: 'Active Channel',
        icon: 'tabler-check',
        href: '/channels/active',
        active: true
    );
    
    // Render the actual nav-link template
    $html = View::make('waterhole::components.nav-link', [
        'label' => $navLink->label,
        'icon' => $navLink->icon,
        'href' => $navLink->href,
        'isActive' => $navLink->isActive,
        'attributes' => new ComponentAttributeBag(['title' => 'This is an active channel']),
    ])->render();
    
    // Assert both title and active class are present
    expect($html)->toContain('title="This is an active channel"');
    expect($html)->toContain('is-active');
    expect($html)->toContain('Active Channel');
});