<?php
// app/Http/Controllers/Admin/UserController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('registrations')->latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::with(['registrations', 'country'])->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        // Validation and store logic
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'status' => 'nullable|in:active,disabled',
        ]);

        $oldStatus = $user->status;
        if ($request->filled('status')) {
            $user->status = $request->status;
            $user->save();

            ActivityLog::record(
                'ADMIN_USER_STATUS_UPDATED',
                "User account for {$user->full_name} ({$user->email}) status updated to {$user->status}.",
                ['user_id' => $user->id, 'old_status' => $oldStatus, 'new_status' => $user->status]
            );
        }

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    public function toggleStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $oldStatus = $user->status ?? User::STATUS_ACTIVE;
        $newStatus = ($oldStatus === User::STATUS_ACTIVE) ? User::STATUS_DISABLED : User::STATUS_ACTIVE;

        $user->status = $newStatus;
        $user->save();

        if ($newStatus === User::STATUS_DISABLED) {
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('sessions')) {
                    \Illuminate\Support\Facades\DB::table('sessions')
                        ->where('user_id', $user->id)
                        ->delete();
                }
            } catch (\Throwable $e) {}
        }

        $actionText = ($newStatus === User::STATUS_DISABLED) ? 'disabled' : 'enabled';

        ActivityLog::record(
            'ADMIN_USER_STATUS_UPDATED',
            "User account for {$user->full_name} ({$user->email}) was {$actionText} by admin.",
            ['user_id' => $user->id, 'old_status' => $oldStatus, 'new_status' => $newStatus]
        );

        return redirect()->back()->with('success', "User account has been {$actionText} successfully.");
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully.');
    }
}
