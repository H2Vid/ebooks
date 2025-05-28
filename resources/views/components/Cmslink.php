<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CmsLink extends Component
{
    public string $route;
    public string $icon;

    public function __construct(string $route, string $icon = '')
    {
        $this->route = $route;
        $this->icon  = $icon;
    }

    public function render()
    {
        return view('components.cms-link');
    }
}
