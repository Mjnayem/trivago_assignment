<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function login(LoginRequest $request){

        $response = Config::get('global.response_format');
        $input = $request->all();


        if($request->validator->fails()){
            $response['message'] = "Validation Error!";
            return response()->json($response, 422);
        }

        $credentials = array(
            'email'=>$input['email'],
            'password'=> $input['password'],
        );


        // check auth attempt
        if(!Auth::attempt($credentials)) {

            $response['message'] = 'Invalid username or password.';
            return response()->json($response, 401);

        }

        // get user
        $user = $request->user();

        /**
         * Tokenizer
         */
        $tokenResult = $user->createToken('Personal Access Token');

        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);

        //store token
        $token->save();


        $response = [
            'status'=>'success',
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_in' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),

        ];
        return response()->json($response, 200);

    }
}
