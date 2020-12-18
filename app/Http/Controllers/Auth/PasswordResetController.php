<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Carbon\Carbon;
use App\PasswordReset;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;

class PasswordResetController extends Controller
{
    /**
     * Cria token para redefinir senha.
     *
     * @param  [string] email
     * @return [string] message
     */
    public function create(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|email',
            ]);

            $user = Auth::getModelFromProvider();
            $user = $user::where('email', $request->email)->first();

            if (! $user) {
                return response()->json([
                    'message' => __('passwords.user'),
                ], 404);
            }

            $passwordReset = PasswordReset::updateOrCreate(['email' => $user->email], [
                'email' => $user->email,
                'token' => str_random(60),
            ]);

            if ($user && $passwordReset) {
                $scope = Auth::getDefaultDriver();
                $user->notify(new PasswordResetRequest($passwordReset->token, $scope));
            }

            return response()->json([
                'message' => __('passwords.sent'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => __('passwords.sent_fail'),
            ]);
        }
    }

    /**
     * Procura token de redefinição de senha.
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function find($token)
    {
        $passwordReset = PasswordReset::where('token', $token)->first();

        if (! $passwordReset) {
            return response()->json([
                'message' => __('passwords.token'),
            ], 404);
        }

        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();

            return response()->json([
                'message' => __('passwords.token'),
            ], 404);
        }

        return response()->json($passwordReset);
    }

    /**
     * Redefinir senha.
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     * @return [json] user object
     */
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|confirmed',
            'token' => 'required|string',
        ]);

        $passwordReset = PasswordReset::where([
            ['token', $request->token],
            ['email', $request->email],
        ])->first();

        if (! $passwordReset) {
            return response()->json([
                'message' => __('passwords.token'),
            ], 404);
        }

        $user = Auth::getModelFromProvider();
        $user = $user::where('email', $passwordReset->email)->first();

        if (! $user) {
            return response()->json([
                'message' => __('passwords.user'),
            ], 404);
        }

        $user->password = bcrypt($request->password);
        $user->save();

        $passwordReset->delete();

        $user->notify(new PasswordResetSuccess($passwordReset));

        return response()->json($user);
    }
}
