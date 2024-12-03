<?php

namespace App\Services;

use Illuminate\Auth\Access\AuthorizationException;
use Spatie\Permission\Models\Role;
use Tymon\JWTAuth\Facades\JWTAuth;

class RoleService
{
    public function createRole($data) {
        $user = JWTAuth::parseToken()->authenticate();

        if(!$user || !$user->hasRole('admin')) {
            throw new AuthorizationException('Unauthorized');
        }

        return Role::create($data);
    }

    public function getAllRole($perPage) {
        $user = JWTAuth::parseToken()->authenticate();

        if(!$user || !$user->hasRole('admin')) {
            throw new AuthorizationException('Unauthorized');
        }

        return Role::paginate($perPage);
    }

    public function getRole($id) {
        $user = JWTAuth::parseToken()->authenticate();

        if(!$user || !$user->hasRole('admin')) {
            throw new AuthorizationException('Unauthorized');
        }

        return Role::findOrFail($id);
    }

    public function updateRole($id, $data) {
        $user = JWTAuth::parseToken()->authenticate();

        if(!$user || !$user->hasRole('admin')) {
            throw new AuthorizationException('Unauthorized');
        }

        $role = Role::findOrFail($id);
        $role->name = $data['name'];
        $role->save();

        return $role;
    }

    public function deleteRole($id) {
        $user = JWTAuth::parseToken()->authenticate();

        if(!$user || !$user->hasRole('admin')) {
            throw new AuthorizationException('Unauthorized');
        }

        $role = Role::findOrFail($id);
        return $role->delete();
    }
}
