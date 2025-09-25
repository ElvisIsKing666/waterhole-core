<?php

namespace Waterhole\View\Components;

use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Waterhole\Models\Channel;
use Waterhole\Models\Page;
use Waterhole\Models\Structure;
use Waterhole\Models\StructureHeading;
use Waterhole\Models\StructureLink;

use function Waterhole\is_absolute_url;

class IndexNav extends Component
{
    public Collection $nav;

    public function __construct()
    {
        $structure = Structure::query()
            ->where('is_listed', true)
            ->with('content')
            ->orderBy('position')
            ->get()
            ->filter(fn(Structure $node) => $node->content);

        $this->nav = collect([
            // Add logo as first navigation item
            new NavLogoLink(href: route('waterhole.home')),
            ...$structure
                ->map(function (Structure $node) {
                    if ($node->content instanceof StructureHeading) {
                        return new NavHeading($node->content->name ?: '', $node->content->subheading);
                    } elseif ($node->content instanceof Channel) {
                        $navLink = new NavLink(
                            label: $node->content->name,
                            icon: $node->content->icon,
                            href: $node->content->url,
                        );
                        
                        // Add description as data attribute if channel has description
                        if ($node->content->description) {
                            $strippedDescription = strip_tags($node->content->description);
                            if (trim($strippedDescription)) {
                                $navLink = $navLink->withAttributes(['data-description' => $strippedDescription]);
                            }
                        }
                        
                        return $navLink;
                    } elseif ($node->content instanceof StructureLink) {
                        return (new NavLink(
                            label: $node->content->name,
                            icon: $node->content->icon,
                            href: $node->content->href,
                        ))->withAttributes(
                            is_absolute_url($node->content->href) ? ['target' => '_blank'] : [],
                        );
                    } elseif ($node->content instanceof Page) {
                        return new NavLink(
                            label: $node->content->name,
                            icon: $node->content->icon,
                            href: $node->content->url,
                        );
                    }

                    return null;
                })
                ->filter(),
        ]);

        // Filter out headings with no items after them
        $this->nav = $this->nav->filter(function ($item, $i) {
            if ($item instanceof NavHeading) {
                return isset($this->nav[$i + 1]) && !($this->nav[$i + 1] instanceof NavHeading);
            }

            return true;
        });

        $this->nav->push(new IndexFooter());
    }

    public function render()
    {
        return $this->view('waterhole::components.index-nav');
    }
}
