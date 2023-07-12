<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResources;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ApiAuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'unique:users', 'email'],
            'password' => ['required', 'string', 'min:8'],
            'role'=>['required']
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ]);
        }
        $new_account = User::create([
            'user_name' => null,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id'=>$request->role

        ]);
        if ($new_account) {
            return response()->json([
                'message' => "you are successfully registration",

            ]);
        } else {
            return response()->json([
                'message' => "you are failed registration "
            ]);
        }
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required', 'string', ' min:5']
        ]);
        if ($validator->fails()) {

            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ]);
        }
        $user = User::where('email', $request->email)->first();


        if ($user !== null) {
            $password_Correct = Hash::check($request->password, $user->password);
            if ($password_Correct) {
                $access_token = Str::random(64);

                $user->update([
                    'access_token' => $access_token,

                ]);
                return response()->json([
                    'status' => true,
                    'message' => "You have been login successfully",
                    "data" => new UserResources($user)
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Password Not Correct',
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'email not correct',
            ]);
        }
    }
    public function update_size(Request $request)
    {
        $user = User::where('access_token', $request->header('Authorization'))->first();
        $validator = Validator::make($request->all(), [
            'height' => ['required'],
            'weight' => ['required'],
            'foot_size' => ['required']
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ]);
        }
        $update = $user->update([
            'height' => $request->height,
            'weight' => $request->weight,
            'foot_size' => $request->foot_size,
        ]);
        if ($update) {
            return response()->json([
                'status' => true,
                'message' => "update succssfully"
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "update failed"
            ]);
        }
    }
    public function update(Request $request)
    {
        $user = User::where('access_token', $request->header('Authorization'))->first();
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'unique:users', 'email'],
            'height' => ['required'],
            'weight' => ['required'],
            'foot_size' => ['required'],
            // 'password' => ['required', 'string', 'min:8'],
            'img' => ['nullable','image']
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ]);
        }
        if($request->hasFile('img')){
            Storage::delete('img');
            $extension=$request->file('img')->extension();
            $path_img = $request->file('img')->storeAs('users',$user->id . "." .$extension);
            $update = $user->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'height' => $request->height,
                'weight' => $request->weight,
                'foot_size' => $request->foot_size,
                'img'=>$path_img,
                // 'password' => Hash::make($request->password),
            ]);
            if($update){
                return response()->json([
                    "status" => true,
                    "message" => "update successfully"
                ]);
            }else{
                return response()->json([
                    "status" => false,
                    "message" => "update failed"
                ]);
            }


        }else{
            $update = $user->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'height' => $request->height,
                'weight' => $request->weight,
                'foot_size' => $request->foot_size,
                // 'password' => Hash::make($request->password),
            ]);
            if($update){
                return response()->json([
                    "status" => true,
                    "message" => "update successfully"
                ]);
            }else{
                return response()->json([
                    "status" => false,
                    "message" => "update failed"
                ]);
            }
        }
    }
    public function getProfile(Request $request)
    {
        $user = User::where('access_token', $request->header('Authorization'))->first();
        return response()->json([
            'status' => true,
            "data" => new ProfileResource($user)
        ]);

    }
}
