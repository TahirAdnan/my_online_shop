<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\New_;

class TempImageController extends Controller
{
    // Store a category function
    public function create(Request $request)
    {
        // get original extension of image and make name
        if (!empty($request->image)) {
            $ext  = $request->image->getClientOriginalExtension();
            $filename = time().'.'.$ext;

            // create directory if not avaliable
            $path = public_path('temp/');
            !is_dir($path) && mkdir($path, 0777, true);

            // Move image in directory and save name in db
            if($request->image->move($path, $filename)){
                $tempImage = new TempImage();
                $tempImage->name = $filename;
                $tempImage->save();

                return response()->json([
                    'status' => true,
                    'image_id' => $tempImage->id,
                    'message' => 'Image uploaded successfully'
                ]);                
            } else {
                return response()->json([
                    'status' => false,
                    'error' => 'Image not saved'
                ]);
            }


            // die(public_path());
        }




    }
}
