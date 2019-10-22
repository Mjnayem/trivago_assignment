<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddItemRequest;
use App\Http\Requests\LoginRequest;
use App\Item;
use App\Location;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function addItemUser(AddItemRequest $request){
        try {
        
            $response = Config::get('global.response_format');
            $input = $request->all();
        
            /*Form Validation*/
            if($request->validator->fails()){
                $response['message'] = "Validation Error!";
                return response()->json($response, 422);
            }
            $user = new User();
            $userExist = $user->where('id',$input['hotelier_id'])->first();
            if(!$userExist){
                $response['message'] = "Hotelier id does not exist!";
                return response()->json($response, 200);
            }
            //begin transaction
            DB::beginTransaction();
            $locations = new Location();
            
            $locations->city = $input['city'];
            $locations->state = $input['state'];
            $locations->country = $input['country'];
            $locations->zip_code = $input['zip_code'];
            $locations->address = $input['address'];
            $locations->save();
            $locationId = $locations->id;
            
            
            
            $item = new Item();
            $item->name = $input['name'];
            $item->rating = $input['rating'];
            $item->category = $input['category'];
            $item->location_id = $locationId;
            $item->hotelier_id = $input['hotelier_id'];
            $item->image_url = $input['image_url'];
            $item->reputation = $input['reputation'];
            $item->price = $input['price'];
            $item->availability = $input['availability'];
            
            if($item->save()) {
                DB::commit();
                $response['status'] = 'success';
                $response['message'] = 'Successfully Item Saved!';
            } else {
                DB::rollback();
                $response['message'] = 'Unable to save item info!';
            }
        } catch (\Throwable $e) {
            DB::rollback();
            $response['message'] = $e->getMessage();
        }
    
        return response()->json($response, 200);
    

    }
    
    protected function validateItemsConditions($input){
//        if($input[''])
    
    }

}
