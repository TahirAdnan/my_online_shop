<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;



class CategoryController extends Controller
{
    public function index(){
        echo "Index page";
    }

    public function create(){
        return view('admin.category.create');
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:categories',
        ]);
        
        if($validator->passes()){
            $categories = new Category();
            $categories->name = $request->name;
            $categories->slug = $request->slug;
            $categories->status = $request->status;
            $categories->save();


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
