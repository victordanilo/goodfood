<?php

namespace App\Http\Controllers;

use DB;
use Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Retorna lista de configurações do sistema.
     *
     * @return mixed
     */
    public function index()
    {
        return Setting::all();
    }

    /**
     * Define configurações do sistema.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function set(Request $request)
    {
        $request->validate([
            'settings' => 'required',
            'settings.*.key' => 'required|string|regex:/^[A-Za-z._]+$/|min:2|max:255',
            'settings.*.value' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $settings = $request->input('settings');
            foreach ($settings as $setting) {
                $setting['value'] = $setting['value'] ?? '';
                Setting::set($setting['key'], $setting['value']);
            }

            Setting::save();
            DB::commit();

            return response()->json(['message' => __('save_success')], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
