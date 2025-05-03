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
     */
    public function index()
    {
        $products=Product::with(['images', 'colors', 'sizes', 'features','specifications','reviews'])->get();
        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'slogo' => 'nullable|string',
            'brand' => 'required|string',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
            'image' => 'required|string',
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
    
        $product = Product::create([
            'name' => $data['name'],
            'slogo' => $data['slogo'] ?? null,
            'brand' => $data['brand'],
            'quantity' => $data['quantity'],
            'price' => $data['price'],
            'image' =>$data['image'],
            'oldPrice' => $data['oldPrice'] ?? null,
            'rating' => $data['rating'] ?? 0,
            'reviewCount' => $data['reviewCount'] ?? 0,
            'sold' => $data['sold'] ?? 0,
            'categorie' => $data['categorie'],
            'subCategory' => $data['subCategorie'],
            'details' => $data['details'],
        ]);
    
        foreach ($data['images'] ?? [] as $img) {
            Image::create([
                'product_id' => $product->id,
                'path' => reset($img) // "image1" => "path", "image2" => "path", etc.
            ]);
        }
    
        foreach ($data['features'] ?? [] as $feature) {
            Feature::create([
                'product_id' => $product->id,
                'feature' => $feature
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
    
        return response()->json(['message' => 'Product created successfully', 'product' => $product], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
