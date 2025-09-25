<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;

class NavLogoLink extends Component
{
    public function __construct(
        public string $href,
        public ?string $logo = null,
        public ?string $alt = null,
    ) {
        $this->logo ??= asset('img/mile-high-club-logo.png');
        $this->alt ??= config('waterhole.forum.name');
    }

    public function render()
    {
        return $this->view('waterhole::components.nav-logo-link');
    }
}
