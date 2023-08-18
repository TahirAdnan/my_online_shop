<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

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

                $ext = pathinfo($tempImage->name, PATHINFO_EXTENSION);
                $newName = $categories->id . '.' . $ext;
                $spath = public_path('temp/') . $tempImage->name;

                $dpath = public_path('uploads/category/');
                !is_dir($dpath) && mkdir($dpath, 0777, true);

                $fileNameWithPath = $dpath . $newName;
                if (File::copy($spath, $fileNameWithPath)) {
                    $categories->image = $newName;
                    $categories->save();
                }
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
