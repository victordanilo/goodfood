<?php

namespace App\Http\Controllers;

use GoogleReCaptchaV3;
use Illuminate\Http\Request;

class RecaptchaController extends Controller
{
    public function validateRecaptcha(Request $request)
    {
        $request->validate([
           'token' => 'required',
           'action' => 'required',
        ]);

        $validate = GoogleReCaptchaV3::setAction($request->input('action'))
            ->verifyResponse($request->input('token'), $request->getClientIp());

        if ($validate->isSuccess()) {
            return response()->json(['validate' => $validate->isSuccess()], 200);
        }

        return response()->json(['validate' => $validate->isSuccess()], 422);
    }
}
