<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Logger;

class CategoryController extends Controller
{



    public function saveCategory(Request $request)
    {
        // echo '<pre>';print_r($_POST);exit;
        $category_id = $request->input('category_id');

        $validation_rules = ['name' => 'required|string|max:50'];

        $validator = Validator::make($request->all(),$validation_rules);

        // Stop if validation fails
        if ($validator->fails()) {
            // return $this->return_output('error', 'error', $validator, 'back', '422');
            return response()->json([
                'status' => false,
                'message' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($category_id) {
            $category = Category::find($category_id);
            $success_message = 'Category updated successfully';
        } else {
            // Logger('no id');
            $category = new Category();
            $success_message = 'Category added successfully';

        }

        $category->name = $request->input('name');
        $category->icon_class = $request->input('icon_class');

        $category->is_active = $request->input('is_active');
        $category->save();


        $categories = Category::get();

        return response()->json([
            'status' => true,
            'message' => $success_message,
            'categories' => $categories,
        ], 200);
    }


    public function deleteCategory(Request $request)
    {
        $category_id = $request->input('category_id');
        if($category_id){
            Category::destroy($category_id);
        }

        $categories = Category::get();

        return response()->json([
            'status' => true,
            'message' => 'Deleted Successfully',
            'categories' => $categories,
        ], 200);

    }


    public function categories()
    {
        return Category::get();
    }

    public function active_categories()
    {
        return Category::where('is_active', 1)->get();
    }
}
