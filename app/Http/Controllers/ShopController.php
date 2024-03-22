<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function shop(Request $request, $cat_slug = null, $sub_cat_slug = null){
        $categories = Category::where('status', '1')->with('sub_categories')->get();
        $brands = Brand::orderBy('id', 'DESC')->where('status', '1')->get();
        $products = Product::where('status', '1');
        $categoryId = null;
        $subCategoryId = null;
        $brandsArray = [];

        // echo $request->get('brand'); die;
        if(!empty($request->get('brand'))){
            $brandsArray = explode(',',$request->get('brand'));
            $products = $products->whereIn('brand_id',$brandsArray);
        }

        if (!empty($cat_slug)) {            
            $category = $categories->where('slug', $cat_slug)->first();
            $products = $products->where('category_id', $category->id);
            $categoryId = $category->id;
        }

        if (!empty($sub_cat_slug)) {            
            $sub_category = SubCategory::where('slug', $sub_cat_slug)->first();            
            $products = $products->where('sub_category_id', $sub_category->id);
            $subCategoryId = $sub_category->id;
        }

        $products = $products->orderBy('id', 'DESC')->paginate(5);
        // dd($products);
        $data['categories'] = $categories;
        $data['brands'] = $brands;
        $data['products'] = $products;
        $data['categoryId'] = $categoryId;
        $data['subCategoryId'] = $subCategoryId;
        $data['brandsArray'] = $brandsArray;
        return view('front.shop', $data);
    }
}