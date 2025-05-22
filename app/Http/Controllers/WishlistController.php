<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->wishlists()->with(['images', 'colors', 'sizes', 'features', 'specifications', 'reviews'])->get();
    }



    public function toggle(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        $user = $request->user();
        $user->wishlists()->toggle($request->product_id);

        return response()->json(['message' => 'toggled to wishlist']);
    }


    //    public function destroy(Request $request, $product_id)
    // {
    //     $request->user()->wishlist()->detach($product_id);

    //     return response()->json(['message' => 'Removed from wishlist']);
    // }
}
