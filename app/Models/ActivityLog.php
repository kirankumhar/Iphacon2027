<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'activity_logs';

    protected $fillable = [
        'user_id',
        'admin_id',
        'user_type',
        'subject_name',
        'action',
        'description',
        'ip_address',
        'user_agent',
        'properties',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(AdminUser::class, 'admin_id');
    }

    /**
     * Helper to log system activity
     */
    public static function record(string $action, string $description, array $properties = [], $userOrAdmin = null): self
    {
        $userId = null;
        $adminId = null;
        $userType = null;
        $subjectName = null;

        if ($userOrAdmin instanceof User) {
            $userId = $userOrAdmin->id;
            $userType = 'User';
            $subjectName = $userOrAdmin->full_name;
        } elseif ($userOrAdmin instanceof AdminUser) {
            $adminId = $userOrAdmin->id;
            $userType = 'Admin';
            $subjectName = $userOrAdmin->full_name ?? $userOrAdmin->username;
        } else {
            if (Auth::guard('admin')->check()) {
                $admin = Auth::guard('admin')->user();
                $adminId = $admin->id;
                $userType = 'Admin';
                $subjectName = $admin->full_name ?? $admin->username;
            } elseif (Auth::check()) {
                $user = Auth::user();
                $userId = $user->id;
                $userType = 'User';
                $subjectName = $user->full_name;
            }
        }

        // Write to Laravel log file
        \Illuminate\Support\Facades\Log::info("Activity [{$action}]: {$description}", [
            'user_id'      => $userId,
            'admin_id'     => $adminId,
            'user_type'    => $userType,
            'subject_name' => $subjectName,
            'ip'           => request()->ip(),
            'properties'   => $properties,
        ]);

        return static::create([
            'user_id'      => $userId,
            'admin_id'     => $adminId,
            'user_type'    => $userType,
            'subject_name' => $subjectName,
            'action'       => strtoupper($action),
            'description'  => $description,
            'ip_address'   => request()->ip(),
            'user_agent'   => request()->userAgent(),
            'properties'   => $properties,
        ]);
    }
}
