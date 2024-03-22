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

        // Brands array 
        if(!empty($request->get('brand'))){
            $brandsArray = explode(',',$request->get('brand'));
            $products = $products->whereIn('brand_id',$brandsArray);
        }
        
        // Price range
        if($request->get('price_max') != '' && $request->get('price_min') != ''){
            if($request->get('price_max') == 1000){
                $products = $products->whereBetween('price', [intval($request->get('price_min')), 1000000]);
            } else {
                $products = $products->whereBetween('price', [intval($request->get('price_min')),intval($request->get('price_max'))]);
            }
        }

        if($request->get('sort') != ''){
            if($request->get('sort') == 'latest'){
                $products = $products->orderBy('id', 'DESC');
            } else if($request->get('sort') == 'price_desc'){
                $products = $products->orderBy('price', 'DESC');
            } else {
                $products = $products->orderBy('price', 'ASC');
            }
        } else {
            $products = $products->orderBy('id', 'DESC');
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

        $products = $products->orderBy('id', 'DESC')->paginate(6);
        // dd($products);
        $data['categories'] = $categories;
        $data['brands'] = $brands;
        $data['products'] = $products;
        $data['categoryId'] = $categoryId;
        $data['subCategoryId'] = $subCategoryId;
        $data['brandsArray'] = $brandsArray;
        $data['priceMin'] = intval($request->get('price_min'));
        $data['priceMax'] = (intval($request->get('price_max')) == 0) ? 1000 : $request->get('price_max');
        $data['sort'] = $request->get('sort');
        return view('front.shop', $data);
    }
}