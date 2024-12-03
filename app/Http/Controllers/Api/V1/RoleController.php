<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Services\RoleService;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\RoleAlreadyExists;

class RoleController extends Controller
{
    public function __construct(
        private RoleService $roleService
    ){}

    public function createRole(StoreRoleRequest $req) {
        try {
            $data = $req->all();

            $role = $this->roleService->createRole($data);
            
            return response()->json(['data' => $role]);
        } catch (RoleAlreadyExists $e) {
            return response()->json(['error' => 'Vai trò đã tồn tại'], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getAllRole(Request $req) {
        try {
            $perPage = $req->input('per_page', 10);

            $roles = $this->roleService->getAllRole($perPage);
            
            return response()->json(['data' => $roles]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getRole($id) {
        try {
            $role = $this->roleService->getRole($id);
            
            return response()->json(['data' => $role]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateRole(StoreRoleRequest $req, $id) {
        try {
            $data = $req->all();

            $role = $this->roleService->updateRole($id, $data);
            
            return response()->json(['data' => $role]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteRole($id) {
        try {
            $role = $this->roleService->deleteRole($id);
            
            return response()->json(['data' => $role]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function assignPermission(Request $req, $id) {
        try {
            $data = $req->all();

            $permissions = $this->roleService->assignPermission($id, $data);
    
            return response()->json(['data' => $permissions]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getPermissionOfRole($id) {
        try {
            $permissions = $this->roleService->getPermissionOfRole($id);
    
            return response()->json(['data' => $permissions]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
