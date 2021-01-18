<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin']);
    }

    /**
     * Retorna lista de permissão.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return Permission::all();
    }

    /**
     * Cria permissão.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\jsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions',
            'guard_name' => 'string',
        ]);

        $guardName = Auth::getDefaultDriver();
        $permission = Permission::create([
            'name' => $request->input('name'),
            'guard_name' => $request->input('guard_name', $guardName),
        ]);
        if ($permission) {
            return response()->json([
                'permission' => $permission,
                'message' => __('common.created_success'),
            ], 201);
        }

        return response()->json(['message' => __('common.created_fail')], 422);
    }

    /**
     * Atualiza permissão.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Spatie\Permission\Models\Permission  $permission
     * @return \Illuminate\Http\jsonResponse
     */
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|unique:permissions',
        ]);

        if ($permission->update(['name' => $request->name])) {
            return response()->json(['message' => __('common.updated_success')], 200);
        }

        return response()->json(['message' => __('common.updated_fail')], 422);
    }

    /**
     * Remove Permissão.
     *
     * @param  \Spatie\Permission\Models\Permission  $permission
     * @return \Illuminate\Http\jsonResponse
     */
    public function destroy(Permission $permission)
    {
        if ($permission->delete()) {
            return response()->json(['message' => __('common.deleted_success')], 200);
        }

        return response()->json(['message' => __('common.deleted_fail')], 422);
    }
}
