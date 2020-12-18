<?php

namespace App\Http\Controllers;

use App\User;
use App\Helpers\Upload;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    /**
     * Retorna lista de usuários.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return User::with('roles')->get();
    }

    /**
     * Cria usuário.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\jsonResponse
     */
    public function store(StoreUserRequest $request)
    {
        $this->authorize('create', User::class);

        $datas = $request->validated();
        if (! empty($request->file('avatar'))) {
            $datas['avatar'] = Upload::img($request->file('avatar'), 'user/avatar/');
        }

        $user = User::create($datas);
        if ($user) {
            return response()->json([
                'user' => $user,
                'message' => __('common.created_success'),
            ], 201);
        }

        return response()->json(['message' => __('common.created_fail')], 422);
    }

    /**
     * Exibe dados do usuário.
     *
     * @param  \App\User $user
     * @return \App\User
     */
    public function show(User $user)
    {
        $user->roles;

        return $user;
    }

    /**
     * Atualizar usuário.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\jsonResponse
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);

        $datas = $request->validated();
        if (! empty($request->file('avatar'))) {
            $datas['avatar'] = Upload::img($request->file('avatar'), 'user/avatar/');
        }

        if ($user->update($datas)) {
            return response()->json([
                'updates' => $datas,
                'message' => __('common.updated_success'),
            ], 200);
        }

        return response()->json(['message' => __('common.updated_fail')], 422);
    }

    /**
     * Remove usuário.
     *
     * @param  int  $id
     * @return \Illuminate\Http\jsonResponse
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        if ($user->delete()) {
            return response()->json(['message' => __('common.deleted_success')], 200);
        }

        return response()->json(['message' => __('common.deleted_fail')], 422);
    }

    /**
     * Define função de usuário.
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function setRole(Request $request, User $user)
    {
        $this->authorize('setRole', $user);

        $request->validate([
            'role' => 'required|int',
        ]);

        $role = Role::find($request->input('role'));
        if (! $role) {
            return response()->json(['message' => __('common.role_not_exist')], 422);
        }

        $user->roles()->detach();
        $user->assignRole($role);
        if ($user->hasRole($role)) {
            return response()->json(['message' => __('common.role_defined_success')], 200);
        }

        return response()->json(['message' => __('common.role_defined_fail')], 422);
    }
}
