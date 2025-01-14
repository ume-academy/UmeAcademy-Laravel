<?php

namespace App\Services;

use Illuminate\Auth\Access\AuthorizationException;
use Spatie\Permission\Models\Role;
use Tymon\JWTAuth\Facades\JWTAuth;

class RoleService
{
    public function createRole($data) {
        return Role::create($data);
    }

    public function getAllRole($perPage) {
        return Role::paginate($perPage);
    }

    public function getRole($id) {
        return Role::findOrFail($id);
    }

    public function updateRole($id, $data) {
        $role = Role::findOrFail($id);
        $role->name = $data['name'];
        $role->save();

        return $role;
    }

    public function deleteRole($id) {
        $role = Role::findOrFail($id);
        return $role->delete();
    }

    public function assignPermission($id, $data) {
        $role = Role::findById($id);

        return $role->syncPermissions($data);
    }

    public function getPermissionOfRole($id) {
        $role = Role::findById($id);

        return $role->permissions;
    }
}
