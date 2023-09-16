<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubCategory;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SubCategoryController extends Controller
{
    public function index(Request $request)
    {

    //   Eloquent ORM Query 
        // $sub_categories = SubCategory::latest()
        //                             ->select('sub_categories.*', 'categories.name as categoryName')
        //                             ->leftJoin("categories", "categories.id", "=", "sub_categories.category_id");  
    
    //  Query Builder query but (Include Facades\DB;)
        $sub_categories = DB::table('sub_categories')
        ->latest()
        ->select('sub_categories.*', 'categories.name as categoryName')
        ->leftJoin("categories", "categories.id", "=", "sub_categories.category_id");  

        if (!empty($request->get('keyword'))) {
            $sub_categories = $sub_categories->where('name', 'like', '%' . $request->get('keyword') . '%');
        }
        $sub_categories =  $sub_categories->paginate(10);
        return view('admin.sub_category.list', compact('sub_categories'));
    }

    public function create()
    {
        $categories = Category::get();
        return view('admin.sub_category.create', compact('categories'));
    }

    // Store a category function
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'category_id' => 'required',
            'slug' => 'required|unique:categories',
        ]);

        if ($validator->passes()) {
            $sub_categories = new SubCategory();
            $sub_categories->name = $request->name;
            $sub_categories->slug = $request->slug;
            $sub_categories->category_id = $request->category_id;
            $sub_categories->status = $request->status;
            $sub_categories->save();
        
            session()->flash('success', 'Sub Category added successfully');
            return response()->json([
                'status' => true,
                'message' => 'Sub Category created successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    // Edit Sub Category function
    public function edit($categoryId, Request $request)
    {
        $categories = Category::get();
        $subCategory = SubCategory::find($categoryId);
        return view('admin.sub_category.edit', [
            'sub_catogries' => $subCategory,
            'categories' => $categories
        ]);
    }

    // update a sub category function
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'category_id' => 'required',
            'slug' => 'required|unique:categories,slug,'.$request->id.',id',
        ]);

        if ($validator->passes()) {
            $sub_categories = SubCategory::find($request->id);
            $sub_categories->name = $request->name;
            $sub_categories->slug = $request->slug;
            $sub_categories->category_id = $request->category_id;
            $sub_categories->status = $request->status;
            $sub_categories->save();

            session()->flash('success', 'Sub Category updated successfully');
            return response()->json([
                'status' => true,
                'message' => 'Sub Category updated successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }  
    }  

    public function getSubCategory(Request $request)
    {
        if (!empty($request->category_id)) {
            $sub_categories = SubCategory::where('category_id', $request->category_id)
                ->orderBy("name", "ASC")
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

    // Delete a category
    public function delete($categoryId, Request $request)
    {
        // Delete category
        $sub_category = SubCategory::find($categoryId);
        $sub_category->delete();

        session()->flash('success', 'Sub Category deleted successfully');
        return response()->json([
            'status' => true,
            'message' => 'Sub Category deleted successfullly',
        ]);
    }
}
