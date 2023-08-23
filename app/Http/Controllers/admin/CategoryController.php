<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image as Image;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::latest();
        if (!empty($request)) {
            $categories = $categories->where('name', 'like', '%' . $request->keyword . '%');
        }
        $categories =  $categories->paginate(10);
        return view('admin.category.list', compact('categories'));
    }

    public function create()
    {
        return view('admin.category.create');
    }

    // Store a category function
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories',
        ]);

        if ($validator->passes()) {
            $categories = new Category();
            $categories->name = $request->name;
            $categories->slug = $request->slug;
            $categories->status = $request->status;
            $categories->save();

            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);

                // Image extension + Image path + select already saved image path
                $ext = pathinfo($tempImage->name, PATHINFO_EXTENSION);
                $newName = $categories->id . '.' . $ext;
                $spath = public_path('temp/') . $tempImage->name;

                // Destination directory check
                $dpath = public_path('uploads/category/');
                !is_dir($dpath) && mkdir($dpath, 0777, true);

                // Copy image from temp dir to uploads/category
                $fileNameWithPath = $dpath . $newName;
                File::copy($spath, $fileNameWithPath);

                // Save image name
                $categories->image = $newName;
                $categories->save();

                // Thumbnail directory creation check
                $thumbnailPath = public_path('uploads/category/thumb/');
                !is_dir($thumbnailPath) && mkdir($thumbnailPath, 0777, true);

                // Generate image thumbnail
                $dpath = $thumbnailPath . $newName;
                Image::make($spath)->resize('450', '450')->save($dpath);
            }

            session()->flash('success', 'Category added successfully');
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

    public function edit($categoryId, Request $request)
    {
        $Category = Category::find($categoryId);
        return view('admin.category.edit', [
            'category' => $Category,
        ]);
        // return view('admin.category.edit', compact('request'));
    }

    // Slug generation
    public function getSlug(Request $request)
    {
        $slug = '';
        if (!empty($request->title)) {
            $slug = Str::slug($request->title);
        }

        return response()->json([
            'status' => true,
            'slug' => $slug,
        ]);
    }
}
