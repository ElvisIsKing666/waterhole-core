<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Models\Channel;
use Waterhole\View\Components\Concerns\HasFeed;

class PostFeedNoChannel extends Component
{
    use HasFeed;

    public function __construct(
        public $feed,
        public ?Channel $channel = null,
        public bool $showLastVisit = true,
    ) {
    }

    public function render()
    {
        return $this->view('waterhole::components.post-feed-no-channel');
    }
}
