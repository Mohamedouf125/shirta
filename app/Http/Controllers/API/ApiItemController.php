<?php

namespace App\Http\Controllers\API;

use App\Models\item;
use App\Models\User;
use App\Models\Seller;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ItemResource;
use Illuminate\Support\Facades\Validator;

class ApiItemController extends Controller
{
    public function index()
    {
        $items = item::get();
        return ItemResource::collection($items);
    }
    public function add_favor(Request $request, $id)
    {
        $user = User::where("access_token", $request->header("Authorization"))->first();
        $favourita = $user->favor_items()->attach($id);
        return ItemResource::collection($favourita);
    }
    public function item($id)
    {
        $item = item::findOrFail($id);
        return new ItemResource($item);
    }
    public function remove_favor(Request $request, $id)
    {
        $user = User::where("access_token", $request->header("Authorization"))->first();
        $favourita = $user->favor_items()->where('item_id', $id)->pivot->delete();
        return ItemResource::collection($favourita);
    }
    // $user = User::where('access_token', $request->header('Authorization'))->first();
    // $seller = Seller::where()->first();
    public function add_product(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'images' => ['required', 'array'],
            'images.*' => ['image'],
            'price' => ['required'],
            'no_products' => ['required'],
            'desc' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all(),
            ]);
        }

        $create = Item::create([
            'name' => $request->name,
            'seller_id' => 2,
            'price' => $request->price,
            'NoItems' => $request->no_products,
            'desc' => $request->desc,
        ]);

        if ($create) {
            foreach ($request->file('images') as $image) {
                $image_path = $image->store('images');
                Upload::create([
                    'name' => $image->getClientOriginalName(),
                    'product_id' => $create->id,
                    'destination' => $image_path
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Product created successfully'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Failed to create product'
        ]);
    }

    public function remove_product($id)
    {
        $item = item::findOrFail($id);
        if ($item) {
            return response()->json([
                'status' => true,
                'message' => 'product removed successfully'
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'failed to remove product'
        ]);
    }
    public function edit_product($id, Request $request)
    {
        $item = Item::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'images' => ['required', 'array'],
            'price' => ['required'],
            'no_produtcs' => ['required'],
            'desc' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all(),
            ]);
        }

        $edit = $item->update([
            'name' => $request->name,
            'price' => $request->price,
            'NoItems' => $request->no_produts,
            'desc' => $request->desc,
        ]);

        if ($edit) {
            // Delete existing images for the product
            $item->uploads()->delete();

            foreach ($request->file('images') as $image) {
                $image_path = $image->store('images');
                $item->uploads()->create([
                    'name' => $image->getClientOriginalName(),
                    'destination' => $image_path
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Product edited successfully'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Failed to edit product'
        ]);
    }




    // public function addCart(){

    // }
}
