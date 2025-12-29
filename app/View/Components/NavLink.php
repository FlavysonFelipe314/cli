<?php

namespace App\View\Components;

use Illuminate\View\Component;

class NavLink extends Component
{
    public $route;
    public $icon;
    public $text;
    public $isLocked;

    public function __construct($route, $icon, $text, $isLocked = false)
    {
        $this->route = $route;
        $this->icon = $icon;
        $this->text = $text;
        $this->isLocked = $isLocked;
    }

    public function render()
    {
        return view('components.nav-link');
    }
}

