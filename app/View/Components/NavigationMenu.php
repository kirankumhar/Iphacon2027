<?php
// app/View/Components/NavigationMenu.php

namespace App\View\Components;

use Illuminate\View\Component;

class NavigationMenu extends Component
{
    public $menus;
    public $level;

    public function __construct($menus, $level = 0)
    {
        $this->menus = $menus;
        $this->level = $level;
    }

    public function render()
    {
        return view('components.navigation-menu');
    }
}
