<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\Feature;
use App\Models\Image;
use App\Models\Product;
use App\Models\Review;
use App\Models\Size;
use App\Models\Specification;
use Illuminate\Http\Request;
use Symfony\Component\CssSelector\Node\Specificity;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
    //  */
    // public function index()
    // {
    //     $products = Product::with(['images', 'colors', 'sizes', 'features', 'specifications', 'reviews'])->get();
    //     return response()->json($products);
    // }

    public function index()
    {
        $products = Product::with(['images', 'colors', 'sizes', 'features', 'specifications', 'reviews'])->get();

        // تعديل المسارات ديال الصور
        $products->each(function ($product) {
            $product->images->each(function ($image) {
                $image->path = asset('storage/' . $image->path);
            });

            $product->reviews->each(function ($review) {
                $review->avatar = asset('storage/' . $review->avatar);
            });
        });

        return response()->json($products);
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'slogo' => 'nullable|string',
            'brand' => 'required|string',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
            'oldPrice' => 'nullable|numeric',
            'rating' => 'nullable|numeric',
            'reviewCount' => 'nullable|integer',
            'sold' => 'nullable|integer',
            'categorie' => 'required|string',
            'subCategorie' => 'required|string',
            'details' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg',
            'features' => 'array',
            'specifications' => 'array',
            'reviews' => 'array',
            'colors' => 'array',
            'sizes' => 'array',
        ]);


        $product = Product::create([
            'name' => $data['name'],
            'slogo' => $data['slogo'] ?? null,
            'brand' => $data['brand'],
            'quantity' => $data['quantity'],
            'price' => $data['price'],
            'oldPrice' => $data['oldPrice'] ?? null,
            'rating' => $data['rating'] ?? 0,
            'reviewCount' => $data['reviewCount'] ?? 0,
            'sold' => $data['sold'] ?? 0,
            'categorie' => $data['categorie'],
            'subCategorie' => $data['subCategorie'],
            'details' => $data['details'],
        ]);


        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imgFile) {
                $path = $imgFile->store('products', 'public'); // تخزين فـ storage/app/public/products
                Image::create([
                    'product_id' => $product->id,
                    'path' => $path
                ]);
            }
        }


        foreach ($data['specifications'] ?? [] as $spec) {
            Specification::create([
                'product_id' => $product->id,
                'name' => $spec['name'],
                'value' => $spec['value']
            ]);
        }


        foreach ($data['reviews'] ?? [] as $review) {
            Review::create([
                'product_id' => $product->id,
                'user' => $review['user'],
                'avatar' => $review['avatar'],
                'rating' => $review['rating'],
                'date' => $review['date'],
                'comment' => $review['comment']
            ]);
        }


        foreach ($data['colors'] ?? [] as $color) {
            Color::create([
                'product_id' => $product->id,
                'color' => $color
            ]);
        }


        foreach ($data['sizes'] ?? [] as $size) {
            Size::create([
                'product_id' => $product->id,
                'size' => $size
            ]);
        }

        return response()->json(['message' => 'Product created successfully', 'product' => $product], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load(['images', 'colors', 'sizes', 'features', 'specifications', 'reviews']);
        return response()->json($product);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'slogo' => 'nullable|string',
            'brand' => 'required|string',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
            'oldPrice' => 'nullable|numeric',
            'rating' => 'nullable|numeric',
            'reviewCount' => 'nullable|integer',
            'sold' => 'nullable|integer',
            'categorie' => 'required|string',
            'subCategorie' => 'required|string',
            'details' => 'required|string',
            'images' => 'array',
            'features' => 'array',
            'specifications' => 'array',
            'reviews' => 'array',
            'colors' => 'array',
            'sizes' => 'array'
        ]);

        // Update product fields
        $product->update([
            'name' => $data['name'],
            'slogo' => $data['slogo'] ?? null,
            'brand' => $data['brand'],
            'quantity' => $data['quantity'],
            'price' => $data['price'],
            'oldPrice' => $data['oldPrice'] ?? null,
            'rating' => $data['rating'] ?? 0,
            'reviewCount' => $data['reviewCount'] ?? 0,
            'sold' => $data['sold'] ?? 0,
            'categorie' => $data['categorie'],
            'subCategorie' => $data['subCategorie'],
            'details' => $data['details'],
        ]);

        // Optional: delete old related data before re-adding
        $product->images()->delete();
        $product->specifications()->delete();
        $product->reviews()->delete();
        $product->colors()->delete();
        $product->sizes()->delete();

        // Re-add related data
        foreach ($data['images'] ?? [] as $img) {
            Image::create([
                'product_id' => $product->id,
                'path' => reset($img)
            ]);
        }

        foreach ($data['specifications'] ?? [] as $spec) {
            Specification::create([
                'product_id' => $product->id,
                'name' => $spec['name'],
                'value' => $spec['value']
            ]);
        }

        foreach ($data['reviews'] ?? [] as $review) {
            Review::create([
                'product_id' => $product->id,
                'user' => $review['user'],
                'avatar' => $review['avatar'],
                'rating' => $review['rating'],
                'date' => $review['date'],
                'comment' => $review['comment']
            ]);
        }

        foreach ($data['colors'] ?? [] as $color) {
            Color::create([
                'product_id' => $product->id,
                'color' => $color
            ]);
        }

        foreach ($data['sizes'] ?? [] as $size) {
            Size::create([
                'product_id' => $product->id,
                'size' => $size
            ]);
        }

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product->load(['images', 'specifications', 'reviews', 'colors', 'sizes'])
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {

        $product->images()->delete();
        $product->specifications()->delete();
        $product->reviews()->delete();
        $product->colors()->delete();
        $product->sizes()->delete();

        $product->delete();
        return response()->json(['message' => 'deleted', 'id' => $product->id]);
    }
}
