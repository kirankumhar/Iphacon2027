<?php
// app/View/Composers/MenuComposer.php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Services\MenuService;
use Illuminate\Support\Facades\Auth;

class MenuComposer
{
    protected $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    public function compose(View $view)
    {
        $menus = collect();

        if (Auth::check()) {
            $user = Auth::user();
            $menus = $this->menuService->getUserMenus($user);
        } elseif (Auth::guard('admin')->check()) {
            $user = Auth::guard('admin')->user();
            $menus = $this->menuService->getUserMenus($user);
        }

        $view->with('navigationMenus', $menus);
    }
}
