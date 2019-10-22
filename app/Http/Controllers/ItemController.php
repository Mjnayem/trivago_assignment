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
//        $this->middleware('auth:api');
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

            if(!$this->validateItemsName($input['name'])){
                $response['message'] = "Name can not contain keywords [Free, Offer, Book, Website]";
                return response()->json($response, 400);
            }

            $user = new User();
            $userID = Auth::user()->id;
            $userExist = $user->where('id',$userID)->first();
            if(!$userExist){
                $response['message'] = "Hotelier id does not exist!";
                return response()->json($response, 400);
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
            $item->hotelier_id = $userID;
            $item->image = $input['image_url'];
            $item->reputation = $input['reputation'];
            $item->ReputationBadge = $this->defineReputationBadge($input['reputation']);
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

    public function updateItem(AddItemRequest $request,$itemId){
        try {

            $response = Config::get('global.response_format');
            $input = $request->all();

            /*Form Validation*/
            if($request->validator->fails()){
                $response['message'] = "Validation Error!";
                return response()->json($response, 422);
            }
            $item = new Item();
            $itemData = $item->find($itemId);
            if(!$itemData){
                $response['message'] = "Item Not found!";
                return response()->json($response, 400);
            }
            $locations = new Location();
            $locationsData = $locations->find($itemData->location_id);

            if(!$locationsData){
                $response['message'] = "Location Not found!";
                return response()->json($response, 400);
            }

            if(!$this->validateItemsName($input['name'])){
                $response['message'] = "Name can not contain keywords [Free, Offer, Book, Website]";
                return response()->json($response, 400);
            }
            $user = new User();
            $userID = Auth::user()->id;
            $userExist = $user->where('id',$userID)->first();
            if(!$userExist){
                $response['message'] = "Hotelier id does not exist!";
                return response()->json($response, 400);
            }
            //begin transaction
            DB::beginTransaction();

            $locationsData->city = $input['city'];
            $locationsData->state = $input['state'];
            $locationsData->country = $input['country'];
            $locationsData->zip_code = $input['zip_code'];
            $locationsData->address = $input['address'];
            $locationsData->save();

            $itemData->name = $input['name'];
            $itemData->rating = $input['rating'];
            $itemData->category = $input['category'];
            $itemData->hotelier_id = $userID;
            $itemData->image = $input['image_url'];
            $itemData->reputation = $input['reputation'];
            $itemData->ReputationBadge = $this->defineReputationBadge($input['reputation']);
            $itemData->price = $input['price'];
            $itemData->availability = $input['availability'];

            if($itemData->save()) {
                DB::commit();
                $response['status'] = 'success';
                $response['message'] = 'Successfully Item Updated!';
            } else {
                DB::rollback();
                $response['message'] = 'Unable to update item info!';
            }
        } catch (\Throwable $e) {
            DB::rollback();
            $response['message'] = $e->getMessage();
        }

        return response()->json($response, 200);
    }

    public function deleteItem($itemId){

        try {
            $response = Config::get('global.response_format');

            $item = new Item();
            $itemData = $item->find($itemId);
            if(!$itemData){
                $response['message'] = "Item Not found!";
                return response()->json($response, 400);
            }
            //begin transaction
            DB::beginTransaction();

            $locations = new Location();
           /* First i have to delete the parent row then child because of foreign key*/
            if($itemData->delete()) {
                $locations->find($itemData->location_id)->delete();
                DB::commit();
                $response['status'] = 'success';
                $response['message'] = 'Successfully Item Deleted!';
            } else {
                DB::rollback();
                $response['message'] = 'Unable to Delete item!';
            }
        } catch (\Throwable $e) {
            DB::rollback();
            $response['message'] = $e->getMessage();
        }

        return response()->json($response, 200);
    }

    public function getSingleItem($itemId){
        try {

            $response = Config::get('global.response_format');
            $userID = Auth::user()->id;
            $items = new Item();
            $data = $items->with('location')->find($itemId);

            if(!$data) {
                $response['message'] = 'Item not found!';
                return response()->json($response, 200);
            }

            if($userID !=$data->hotelier_id){
                $response['message'] = 'The item You looking for is not your item!';
                return response()->json($response, 200);
            }

            $response['data'] = $data;
            $response['status'] = 'success';
            $response['message'] = 'Item found!';
        } catch (\Throwable $e) {
            DB::rollback();
            $response['message'] = $e->getMessage();
        }

        return response()->json($response, 200);
    }

    public function getItemByUser($userId){
        try {

            $response = Config::get('global.response_format');
            $items = new Item();
            $data = $items->where('hotelier_id',$userId)->with('location')->get();

            if(!$data) {
                $response['message'] = 'Data not found!';
                return response()->json($response, 400);
            } else {
                $response['data'] = $data;
                $response['status'] = 'success';
                $response['message'] = 'Data found!';
            }
        } catch (\Throwable $e) {
            DB::rollback();
            $response['message'] = $e->getMessage();
        }

        return response()->json($response, 200);
    }

    public function bookItem($itemId){
        try {

            $response = Config::get('global.response_format');

            $item = new Item();
            $itemData = $item->find($itemId);
            if(!$itemData){
                $response['message'] = "Item Not found!";
                return response()->json($response, 422);
            }

            $availability = intval($itemData->availability);
            if($availability <= 0){
                $response['message'] = "Item availability zero! ";
                return response()->json($response, 400);
            }
            $availability-=1;

            //begin transaction
            DB::beginTransaction();

            $itemData->availability = $availability;

            if($itemData->save()) {
                DB::commit();
                $response['status'] = 'success';
                $response['message'] = 'Successfully Item Booked!';
            } else {
                DB::rollback();
                $response['message'] = 'Unable to Book item!';
            }
        } catch (\Throwable $e) {
            DB::rollback();
            $response['message'] = $e->getMessage();
        }

        return response()->json($response, 200);
    }

    protected function validateItemsName($name){
        $lowerFormattedName = strtolower($name);
        if (preg_match("/^((?!(free|offer|website|book)).)*$/",$lowerFormattedName)) {
            return true;
        }else{
            return false;
        }
    }

    protected function defineReputationBadge($amount){
        if($amount <= 500){
            return 'red';
        }elseif ($amount <= 799){
            return 'yellow';
        }else{
            return 'green';
        }
    }

}
