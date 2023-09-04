<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $brands = Brand::latest();
        if (!empty($request->get('keyword'))) {
            $brands = $brands->where('name', 'like', '%' . $request->get('keyword') . '%');
        }
        $brands =  $brands->paginate(10);
        return view('admin.brand.list', compact('brands'));
    }

    public function create()
    {
        return view('admin.brand.create');
    }

        // Store a Brand function
        public function store(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'slug' => 'required|unique:brands',
            ]);
    
            if ($validator->passes()) {
                $brands = new Brand();
                $brands->name = $request->name;
                $brands->slug = $request->slug;
                $brands->status = $request->status;
                $brands->save();

                session()->flash('success', 'Brand added successfully');
                return response()->json([
                    'status' => true,
                    'message' => 'Brand created successfully'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors(),
                ]);
            }
        }
    
        // Edit Brand function
        public function edit($brandId, Request $request)
        {
            $brand = Brand::find($brandId);
            return view('admin.brand.edit', [
                'brand' => $brand,
            ]);
        }
    
        // update a Brand function
        public function update(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'slug' => 'required|unique:brands,slug,$brand->id,id',
            ]);
    
            if ($validator->passes()) {
                $brands = Brand::find($request->id);
                $brands->name = $request->name;
                $brands->slug = $request->slug;
                $brands->status = $request->status;
                $brands->save();

                session()->flash('success', 'Brand updated successfully');
                return response()->json([
                    'status' => true,
                    'message' => 'Brand updated successfully'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors(),
                ]);
            }
        }
    
        // Delete a Brand
        public function delete($brandId, Request $request)
        {
            $brand = Brand::find($brandId);
    
            // Delete brand
            $brand->delete();
    
            session()->flash('success', 'Brand deleted successfully');
            return response()->json([
                'status' => true,
                'message' => 'Brand deleted successfullly',
            ]);
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
