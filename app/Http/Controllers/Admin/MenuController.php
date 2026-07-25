<?php
// app/Http/Controllers/Admin/MenuController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('allChildren')->whereNull('parent_id')->orderBy('sort_order')->get();
        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        $parentMenus = Menu::whereNull('parent_id')->get();
        return view('admin.menus.create', compact('parentMenus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'roles' => 'required|array',
            'sort_order' => 'integer'
        ]);

        Menu::create($request->all());

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu created successfully.');
    }

    // Add edit, update, delete methods as needed
}
