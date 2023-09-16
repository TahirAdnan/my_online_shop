<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image as Image;

class ProductImageController extends Controller
{
    public function updateProductImage($product_id, Request $request)
    {
        // get original extension of image and make name
        if (!empty($request->image)) {
            $products_image = new ProductImage();
            $products_image->product_id = $product_id;
            $products_image->image = '';
            $products_image->save();

            $path = public_path('uploads/product/');
            !is_dir($path) && mkdir($path, 0777, true);
            $ext = $request->image->getClientOriginalExtension();
            $filename = $product_id . '-' . $products_image->id . '-' . time() . '.' . $ext;
            if ($request->image->move($path, $filename)) {
                $products_image->image = $filename;
                $products_image->save();

                $thumbnailPath = public_path('uploads/product/thumb/');
                !is_dir($thumbnailPath) && mkdir($thumbnailPath, 0777, true);
                $img = Image::make($path . $filename);
                $img->fit(300, 275);
                $img->save($thumbnailPath . $filename);

                return response()->json([
                    'status' => true,
                    'image_id' => $products_image->id,
                    'image_path' => asset('uploads/product/thumb/'.$filename),
                    'message' => 'Image uploaded successfully'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'error' => 'Image not saved'
                ]);
            }
        }
    }

    // Delete a product
    public function productImageDelete(Request $request)
    {
        $productImage = ProductImage::find($request->id);

        // Delete images
        File::delete(public_path('uploads/product/thumb/' . $productImage->image));
        File::delete(public_path('uploads/product/' . $productImage->image));

        // Delete product
        $productImage->delete();

        session()->flash('success', 'Image deleted successfully');
        return response()->json([
            'status' => true,
            'message' => 'Image deleted successfullly',
        ]);
    }
}
