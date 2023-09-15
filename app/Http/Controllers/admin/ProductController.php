<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\TempImage;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image as Image;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::latest()->with('product_images')->paginate(10);
        if (!empty($request)) {
            $products = $products->where('title', 'like', '%' . $request->keyword . '%');
        }
        return view('admin.product.list', compact('products'));
    }

    public function create(){
        $categories = Category::orderBy("name","ASC")->get();
        $brands = Brand::orderBy("name","ASC")->get();
        $data['categories'] = $categories;
        $data['brands'] = $brands;
        return view('admin.product.create', $data);
    }

    public function getSubCategory(Request $request){
        if(!empty($request->category_id)){
            $sub_categories = SubCategory::where('category_id', $request->category_id)
            ->orderBy("name","ASC")
            ->get();

            return response()->json([
                'status' => true,
                'sub_categories' => $sub_categories,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'sub_categories' => [],
            ]);
        }                                        
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'slug' => 'required|unique:products',
            'price' => 'required|numeric',
            'sku' => 'required',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
        ]);

        if ($validator->passes()) {
            $products = new Product();
            $products->title = $request->title;
            $products->slug = $request->slug;
            $products->status = $request->status;
            $products->description = $request->description;
            $products->price = $request->price;
            $products->compare_price = $request->compare_price;
            $products->category_id  = $request->category ;
            $products->sub_category_id  = $request->sub_category ;
            $products->brand_id  = $request->brand;
            $products->is_featured = $request->is_featured;
            $products->sku = $request->sku;
            $products->barcode = $request->barcode;
            $products->track_qty = $request->track_qty;
            $products->qty = $request->qty;
            $products->save();

            if (!empty($request->image_array)) {
                foreach($request->image_array as $temp_image_id){

                    //Get temp image make new name and path
                    $tempImage = TempImage::find($temp_image_id);
                    $ext = pathinfo($tempImage->name, PATHINFO_EXTENSION);
                    $newName = $products->id .'-'. $temp_image_id .'-'. time() . '.' . $ext;
                    $spath = public_path('temp/') . $tempImage->name;

                    // Save image name in DB
                    $products_image = new ProductImage();
                    $products_image->image = $newName;
                    $products_image->product_id = $products->id;
                    $products_image->save();

                    // Image Destination directory creation check and image storage
                    $dpath = public_path('uploads/product/');
                    !is_dir($dpath) && mkdir($dpath, 0777, true);
                    $image = Image::make($spath);
                    $image->resize(1400, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $image->save($dpath.$newName);

                    // Thumbnail directory creation check and thumbnail storage
                    $thumbnailPath = public_path('uploads/product/thumb/');
                    !is_dir($thumbnailPath) && mkdir($thumbnailPath, 0777, true);
                    $img = Image::make($spath);
                    $img->fit(300, 275);
                    $img->save($thumbnailPath . $newName);
                    
                    // Delete Old images
                    File::delete(public_path('temp/'));
                    File::delete(public_path('temp/thumb/'));
                }
            }

            session()->flash('success', 'Product added successfully');
            return response()->json([
                'status' => true,
                'message' => 'Product created successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    // Edit product function
    public function edit($productId, Request $request)
    {
        $product = ProductImage::latest()
                            ->leftJoin('products','product_images.product_id','=','products.id')
                            ->select('products.*','product_images.image','product_images.id as image_id')
                            ->where('products.id', $productId)                            
                            ->get(); 

        $categories = Category::get();
        $brands = Brand::get();
        $subCategory = SubCategory::where('category_id',$product[0]['category_id'])->get();
                                                      
// echo '<pre>';
// var_dump($product[0]); 
// die;                            
         
        
        return view('admin.product.edit', [
            'product' => $product,
            'categories' => $categories,
            'sub_catogries' => $subCategory,
            'brands' => $brands,
        ]);
    }

    // update a product function
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'slug' => 'required|unique:products',
            'price' => 'required|numeric',
            'sku' => 'required',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
        ]);

        if ($validator->passes()) {
            $products = Product::find($request->id);
            $products->title = $request->title;
            $products->slug = $request->slug;
            $products->status = $request->status;
            $products->description = $request->description;
            $products->price = $request->price;
            $products->compare_price = $request->compare_price;
            $products->category_id  = $request->category ;
            $products->sub_category_id  = $request->sub_category ;
            $products->brand_id  = $request->brand;
            $products->is_featured = $request->is_featured;
            $products->sku = $request->sku;
            $products->barcode = $request->barcode;
            $products->track_qty = $request->track_qty;
            $products->qty = $request->qty;
            $products->save();

            if (!empty($request->image_array)) {
                foreach($request->image_array as $temp_image_id){

                    //Get temp image make new name and path
                    $tempImage = TempImage::find($temp_image_id);
                    $ext = pathinfo($tempImage->name, PATHINFO_EXTENSION);
                    $newName = $products->id .'-'. $temp_image_id .'-'. time() . '.' . $ext;
                    $spath = public_path('temp/') . $tempImage->name;

                    // Save image name in DB
                    $products_image = new ProductImage();
                    $products_image->image = $newName;
                    $products_image->product_id = $products->id;
                    $products_image->save();

                    // Image Destination directory creation check and image storage
                    $dpath = public_path('uploads/product/');
                    !is_dir($dpath) && mkdir($dpath, 0777, true);
                    $image = Image::make($spath);
                    $image->resize(1400, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $image->save($dpath.$newName);

                    // Thumbnail directory creation check and thumbnail storage
                    $thumbnailPath = public_path('uploads/product/thumb/');
                    !is_dir($thumbnailPath) && mkdir($thumbnailPath, 0777, true);
                    $img = Image::make($spath);
                    $img->fit(300, 275);
                    $img->save($thumbnailPath . $newName);
                    
                    // Delete Old images
                    File::delete(public_path('temp/' . $tempImage->name));
                    File::delete(public_path('temp/thumb/' . $tempImage->name));
                }
            }

            session()->flash('success', 'Product updated successfully');
            return response()->json([
                'status' => true,
                'message' => 'Product updated successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    // Delete a product
    public function delete($productId, Request $request)
    {
        $product = Product::find($productId);
        
        // Delete images
        File::delete(public_path('uploads/product/thumb/' . $product->image));
        File::delete(public_path('uploads/product/' . $product->image));

        // Delete product
        $product->delete();

        session()->flash('success', 'Product deleted successfully');
        return response()->json([
            'status' => true,
            'message' => 'Product deleted successfullly',
        ]);
    }    
}
