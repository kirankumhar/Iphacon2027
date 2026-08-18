<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        $admins = AdminUser::paginate(15);
        return view('admin.superadmin.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.superadmin.admins.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username'  => 'required|string|unique:admin_users,username',
            'email'     => 'required|email|unique:admin_users,email',
            'password'  => 'required|string|min:6',
            'full_name' => 'nullable|string',
            'role'      => 'required|string',
        ]);

        AdminUser::create([
            'username'      => $request->username,
            'email'         => $request->email,
            'password_hash' => Hash::make($request->password),
            'full_name'     => $request->full_name,
            'role'          => $request->role,
            'is_active'     => true,
        ]);

        return redirect()->route('admin.admins.index')->with('success', 'Admin user created successfully.');
    }

    public function edit($id)
    {
        $admin = AdminUser::findOrFail($id);
        return view('admin.superadmin.admins.edit', compact('admin'));
    }

    public function update(Request $request, $id)
    {
        $admin = AdminUser::findOrFail($id);

        $request->validate([
            'username'  => 'required|string|unique:admin_users,username,' . $id,
            'email'     => 'required|email|unique:admin_users,email,' . $id,
            'full_name' => 'nullable|string',
            'role'      => 'required|string',
        ]);

        $admin->username = $request->username;
        $admin->email = $request->email;
        $admin->full_name = $request->full_name;
        $admin->role = $request->role;

        if ($request->filled('password')) {
            $admin->password_hash = Hash::make($request->password);
        }

        $admin->save();

        return redirect()->route('admin.admins.index')->with('success', 'Admin user updated successfully.');
    }

    public function destroy($id)
    {
        $admin = AdminUser::findOrFail($id);
        $admin->delete();

        return redirect()->route('admin.admins.index')->with('success', 'Admin user deleted successfully.');
    }

    public function activityLog()
    {
        $activities = ActivityLog::latest()->get();
        return view('admin.superadmin.activity-log.index', compact('activities'));
    }
}
