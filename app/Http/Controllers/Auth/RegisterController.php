<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }


    public function register(RegisterRequest $request) {

        try {

            $response = Config::get('global.response_format');
            $input = $request->all();

            /*Form Validation*/
            if($request->validator->fails()){
                $response['message'] = "Validation Error!";
                return response()->json($response, 422);
            }

            $exist = User::where('email', $input['email'])->first();
            if($exist) {
                $response['message'] = 'This email address has already taken.';
                return response()->json($response, 200);
            }

            $model = new User();

            $model->name = $input['name'];

            $model->email = $input['email'];
            $model->password = Hash::make($input['password']);

            //begin transaction
            DB::beginTransaction();
            if($model->save()) {
                DB::commit();
                $response['status'] = 'success';
                $response['message'] = 'Successfully Registration Completed!';
            } else {
                DB::rollback();
                $response['message'] = 'Unable to save user info!';
            }
        } catch (\Throwable $e) {
            $response['message'] = $e->getMessage();
        }

        return response()->json($response, 200);

    }

}
