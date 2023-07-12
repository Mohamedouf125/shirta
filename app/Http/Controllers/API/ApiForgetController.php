<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\forgetResponseMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\ForgetResources;
use Illuminate\Support\Facades\Validator;

class ApiForgetController extends Controller
{
    public function forgot(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 409);
        }
        $user = User::where('email', $request->email)->first();
        if ($user != null) {
            $receiverMail = $request->email;
            $otp = random_int(100000, 999999);
            $image = asset('photo/1.jpg');
            $user->update([
                'otp' => $otp,
            ]);
            Mail::to($receiverMail)->send(new forgetResponseMail($otp,$image));
            return response()->json([
                'status' => true,
                "data" => new ForgetResources($user)
            ]);
        }else {
            return response()->json([
                'status' => false,
                "message" => 'your email is not coorect'
            ]);
        }
    }

    public function otp(Request $request)
    {
        $user = User::where('otp', $request->header('Authorization'))->first();

        if ($user !== Null) {

            return response()->json([
                'status' => true,
            ]);
        } else {
            return response()->json([
                'status' => false,
            ], 409);
        }
    }
    public function reset(Request $request)
    {

        $user = User::where('otp', $request->header('Authorization'))->first();
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'min:8'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ]);
        }

        $update = $user->update([
            'password' => Hash::make($request->password)
        ]);
        if ($update) {
            $user->update([
                'otp' => null,
            ]);
            return response()->json([
                'status' => true,
                'message' => "Password update successfully",
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Password update faild",
            ]);
        }
    }
}
