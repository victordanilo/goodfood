<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Hash;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * Criar usuário.
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     */
    public function signup(Request $request)
    {
        $providerName = Auth::getGuardCurrent()['provider'];
        $request->validate([
            'name' => 'required|string',
            'email' => "required|string|email|unique:{$providerName}",
            'password' => 'required|string|confirmed',
        ]);

        $user = Auth::getModelFromProvider();
        $user->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json([
            'message' => __('auth.signup_success'),
        ], 201);
    }

    /**
     * Loga usuário e cria token.
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean',
        ]);

        $user = Auth::getModelFromProvider();
        $user = $user::where($request->only('email'))->first();

        if (! $user) {
            return response()->json([
                'message' => __('auth.account_not_exist'),
            ], 422);
        }

        if (! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => __('auth.login_failed'),
            ], 401);
        }

        $guardName = Auth::getDefaultDriver();
        $userToken = $user->createToken($guardName, [$guardName]);
        $token = $userToken->token;

        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(4);
        }

        $token->save();

        return response()->json([
            'user' => $user,
            'access_token' => $userToken->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($userToken->token->expires_at)->toDateTimeString(),
        ]);
    }

    /**
     * Desloga usuário (Revoga o token).
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => __('auth.logout_success'),
        ]);
    }

    /**
     * Retorna usuário autenticado.
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
