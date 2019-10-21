<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;


class AuthController extends Controller
{


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }


    public function logout(Request $request)
    {
        try {
            $response = Config::get('global.response_format');

            // revoke token
            $request->user()->token()->revoke();
            $response['status'] = 'success';
            $response['message'] = 'Successfully logged out.';

        } catch (\Throwable $e) {
            $response['message'] = $e->getMessage();
        }

        return response()->json($response, 200);
    }
}
