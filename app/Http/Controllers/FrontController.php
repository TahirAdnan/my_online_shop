<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index(){
        $featuredProducts = Product::take(8)
        ->with('product_images')
        ->where('is_featured', 'Yes')
        ->where('status', '1')
        ->get();

        $latestProducts = Product::orderBy('id', 'DESC')
        ->take(8)
        ->with('product_images')
        ->where('status', '1')
        ->get();

        $data['featuredProducts'] = $featuredProducts;
        $data['latestProducts'] = $latestProducts;
        // dd($data);
        return view('front.home', $data);
    }
}
