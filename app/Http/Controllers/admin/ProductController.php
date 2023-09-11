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

            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);

                // Image extension + Image path + select already saved image path
                $ext = pathinfo($tempImage->name, PATHINFO_EXTENSION);
                $newName = $products->id . '.' . $ext;
                $spath = public_path('temp/') . $tempImage->name;

                // Destination directory check
                $dpath = public_path('uploads/product/');
                !is_dir($dpath) && mkdir($dpath, 0777, true);

                // Copy image from temp dir to uploads/category
                $fileNameWithPath = $dpath . $newName;
                File::copy($spath, $fileNameWithPath);

                // Save image name
                $products_image = new ProductImage();
                $products_image->image = $newName;
                $products_image->product_id = $products->id;
                $products_image->save();

                // Thumbnail directory creation check
                $thumbnailPath = public_path('uploads/product/thumb/');
                !is_dir($thumbnailPath) && mkdir($thumbnailPath, 0777, true);

                // Generate image thumbnail
                $dpath = $thumbnailPath . $newName;
                $img = Image::make($spath);
                // $img->resize('450', '450');
                $img->fit(250, 250, function ($constraint) {
                    $constraint->upsize();
                });
                $img->save($dpath);
                
                // Delete Old images
                File::delete(public_path('temp/' . $tempImage->name));
            }

            session()->flash('success', 'Product added successfully');
            return response()->json([
                'status' => true,
                'message' => 'Category created successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }
}
