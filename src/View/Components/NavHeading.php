<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;

class NavHeading extends Component
{
    public function __construct(public string $heading, public ?string $subheading = null)
    {
    }

    public function render()
    {
        return <<<'blade'
            <div {{ $attributes->class('nav-heading') }}>
                <h3>{{ $heading }}</h3>
                @if($subheading)
                    <p class="nav-heading__subheading">{{ $subheading }}</p>
                @endif
            </div>
        blade;
    }
}
