<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
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

    public function shop(Request $request){
        $categories = Category::where('status', '1')->with('sub_categories')->get();
        $brands = Brand::orderBy('id', 'DESC')->where('status', '1')->get();
        $products = Product::orderBy('id', 'DESC')->with('product_images')->where('status', '1')->paginate(11);
        if ($request->get('keyword') != "") {
            $products = $products->where('title', 'like', '%' . $request->keyword . '%');
        }
        $data['categories'] = $categories;
        $data['brands'] = $brands;
        $data['products'] = $products;
        return view('front.shop', $data);
    }
}
