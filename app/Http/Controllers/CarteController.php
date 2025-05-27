<?php

namespace App\Http\Controllers;

use App\Models\Carte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarteController extends Controller
{
    public function index()
    {
        $cartItems = Carte::with('product')->where('user_id', Auth::id())->get();
        return response()->json($cartItems);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $userId = Auth::id();

        $cartItem = Carte::where('user_id', $userId)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            $cartItem = Carte::create([
                'user_id' => $userId,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }

        return response()->json([
            'message' => 'Produit ajouté au panier',
            'item' => $cartItem
        ]);
    }


    public function destroy($product_id)
    {
        $deleted = Carte::where('user_id', Auth::id())
            ->where('product_id', $product_id)
            ->delete();

        return response()->json(['message' => $deleted ? 'Produit supprimé' : 'Produit non trouvé']);
    }

    public function clear() {
          Carte::where('user_id' , Auth::id())->delete() ; 
          return response()->json(['message' => 'panier vidé']) ; 
    }
}
