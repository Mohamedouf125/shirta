<?php

namespace App\Http\Controllers\API;

use App\Models\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ApiBrandController extends Controller
{
    public function index()
    {
        $brands = Seller::get();
        return BrandResource::collection($brands);
    }
    public function add_seller(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'email' => ['required', 'email'],
            'phone' => ['required'],
            'desc' => ['required'],
            'img' => ['required', 'image'],
            'backgroundImage' => ['required', 'image'],

        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all(),
            ]);
        }
        $image = $request->file('img');
        $image_path = $image->store('images');

        $backgroundImage = $request->file('backgroundImage');
        $backgroundImage_path = $backgroundImage->store('images');

        $add = Seller::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'desc' => $request->desc,
            'img' => $image_path,
            'backgroundImage' => $backgroundImage_path,
        ]);
        if ($add) {
            return response()->json([
                'status' => true,
                'message' => "Seller successfully added"
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Failed to add seller',
        ]);
    }
    public function delete_seller($id)
    {
        $seller = Seller::where('id', $id)->first();
        if ($seller) {
            $delete = $seller->delete();
            if ($delete) {
                return response()->json([
                    'status' => true,
                    'message' => "Seller successfully delete and his product delete"
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => "Seller failed delete"
                ]);
            }
        }
    }

    public function update_seller(Request $request, $sellerId)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'email' => ['required','email'],
            'phone' => ['required'],
            'desc' => ['required'],
            'img' => ['image'],
            'backgroundImage' => ['image'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all(),
            ]);
        }

        $seller = Seller::find($sellerId);

        if (!$seller) {
            return response()->json([
                'status' => false,
                'message' => 'Seller not found',
            ]);
        }

        $image = $request->file('img');
        $backgroundImage = $request->file('backgroundImage');

        // Delete existing images, if new images are provided
        if ($image) {
            Storage::delete($seller->img);
        }

        if ($backgroundImage) {
            Storage::delete($seller->backgroundImage);
        }

        if ($image) {
            $image_path = $image->store('images');
            $seller->img = $image_path;
        }

        if ($backgroundImage) {
            $backgroundImage_path = $backgroundImage->store('images');
            $seller->backgroundImage = $backgroundImage_path;
        }

        $seller->name = $request->name;
        $seller->email = $request->email;
        $seller->phone = $request->phone;
        $seller->desc = $request->desc;
        $seller->save();

        return response()->json([
            'status' => true,
            'message' => 'Seller updated successfully',
        ]);
    }
}
