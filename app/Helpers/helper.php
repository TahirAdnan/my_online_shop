<?php

use App\Models\Category;

function getCategories(){
    $data = Category::orderBy('id', 'DESC')
    ->with('sub_categories')
    ->where('status', '1')
    ->where('show_home', 'Yes')
    ->get();
// dd($data);
    return $data;
}

