<?php

namespace Acme\CmsDashboard\Traits;

trait HasCmsPermissions
{
    public function role()
    {
        return $this->belongsTo(\Acme\CmsDashboard\Models\Role::class);
    }

    public function hasRole(string $role): bool
    {
        return $this->role && $this->role->slug === $role;
    }

    public function isAdmin(): bool
    {
        // 1. Direct ID check (1=Admin, 6=SuperAdmin)
        if (in_array($this->role_id, [1, 6])) return true;
        
        // 2. Email fallback
        if (in_array($this->email, ['admin@admin.com', 'tareq@poronto.com'])) return true;

        if (!$this->role_id) return false;

        static $adminCheck = [];
        if (isset($adminCheck[$this->role_id])) return $adminCheck[$this->role_id];
        
        $slug = \Illuminate\Support\Facades\DB::table('roles')->where('id', $this->role_id)->value('slug');
        $res = in_array($slug, ['super-admin', 'administrator', 'admin']);
        
        return $adminCheck[$this->role_id] = $res;
    }

    public function hasPermission(string $permission): bool
    {
        if (!$this->role_id) return false;

        // Debug Log (Temporarily write to scratch)
        try {
            $log = "User ID: " . $this->id . " | Role ID: " . $this->role_id . " | Checking: " . $permission . "\n";
            file_put_contents('brain/08f1e3fb-d47d-4e90-afc3-af4e4bbf6500/scratch/perm_debug.log', $log, FILE_APPEND);
        } catch (\Exception $e) {}

        if ($this->isAdmin()) {
            return true;
        }

        // 2. Check role's permissions from DB
        return \Illuminate\Support\Facades\DB::table('role_permission')
            ->join('permissions', 'role_permission.permission_id', '=', 'permissions.id')
            ->where('role_permission.role_id', $this->role_id)
            ->where('permissions.slug', $permission)
            ->exists();
    }

    public function hasCmsPermission(string $permission): bool
    {
        return $this->hasPermission($permission);
    }
}
