<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin']);
    }

    /**
     * Retorna lista de funções.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return Role::with('permissions')->get();
    }

    /**
     * Cria função.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\jsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles',
            'guard_name' => 'string',
        ]);

        $guardName = Auth::getDefaultDriver();
        $role = Role::create([
            'name' => $request->input('name'),
            'guard_name' => $request->input('guard_name', $guardName),
        ]);
        if ($role) {
            return response()->json([
                'role' => $role,
                'message' => __('common.created_success'),
            ], 201);
        }

        return response()->json(['message' => __('common.created_fail')], 422);
    }

    /**
     * Atualiza função.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Spatie\Permission\Models\Role  $role
     * @return \Illuminate\Http\jsonResponse
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles',
        ]);

        if ($role->update(['name' => $request->name])) {
            return response()->json(['message' => __('common.updated_success')], 200);
        }

        return response()->json(['message' => __('common.updated_fail')], 422);
    }

    /**
     * Remove função.
     *
     * @param  \Spatie\Permission\Models\Role  $role
     * @return \Illuminate\Http\jsonResponse
     */
    public function destroy(Role $role)
    {
        if ($role->delete()) {
            return response()->json(['message' => __('common.deleted_success')], 200);
        }

        return response()->json(['message' => __('common.deleted_fail')], 422);
    }

    /**
     * Retorna lista de todas as permissões atribuídas à função.
     *
     * @param \Spatie\Permission\Models\Role  $role
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function permissions(Role $role)
    {
        return $role->permissions;
    }

    /**
     * Sincroniza permissões à função.
     *
     * @param \Illuminate\Http\Request  $request
     * @param \Spatie\Permission\Models\Role  $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function syncPermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions.*' => 'exists:permissions,id',
        ]);

        if ($role->syncPermissions($request->input('permissions'))) {
            return response()->json(['message' => __('common.save_success')], 200);
        }

        return response()->json(['message' => __('common.save_fail')], 422);
    }
}
