<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\SubCategoryDataTable;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\SubCategory;
use App\Models\Product;
use Illuminate\Http\Request;
use Str;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SubCategoryDataTable $dataTable)
    {
        return $dataTable->render('admin.sub-category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.sub-category.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category' => ['required'],
            'name' => ['required', 'max:200', 'unique:sub_categories,name'],
            'status' => ['required']
        ]);

        $subCategory = new SubCategory();

        $subCategory->category_id = $request->category;
        $subCategory->name = $request->name;
        $subCategory->slug = Str::slug($request->name);
        $subCategory->status = $request->status;
        $subCategory->save();

        toastr('Created Successfully!', 'success');

        return redirect()->route('admin.sub-category.index');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $categories = Category::all();
        $subCategory = SubCategory::findOrFail($id);
        return view('admin.sub-category.edit', compact('subCategory', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'category' => ['required'],
            'name' => ['required', 'max:200', 'unique:sub_categories,name,'.$id],
            'status' => ['required']
        ]);

        $subCategory = SubCategory::findOrFail($id);

        $subCategory->category_id = $request->category;
        $subCategory->name = $request->name;
        $subCategory->slug = Str::slug($request->name);
        $subCategory->status = $request->status;
        $subCategory->save();

        toastr('Updated Successfully!', 'success');

        return redirect()->route('admin.sub-category.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $subCategory = SubCategory::findOrFail($id);
        $childCategory = ChildCategory::where('sub_category_id', $subCategory->id)->count();

        if($childCategory > 0){
            return response(['status' => 'error', 'message' => 'This items contain, sub items for delete this you have to delete the sub items first!']);
        }
        $subCategory->delete();

        return response(['status' => 'success', 'message' => 'Deleted Successfully!']);
    }

    public function changeStatus(Request $request)
    {
        $subCategory = SubCategory::findOrFail($request->id);
        $newStatus = $request->status == 'true' ? 1 : 0;
        $subCategory->status = $newStatus;
        $subCategory->save();
        
        // Si la subcategoría se está desactivando (status = 0)
        if ($newStatus == 0) {
            // Desactivar todas las childcategories relacionadas
            ChildCategory::where('sub_category_id', $subCategory->id)
                ->update(['status' => 0]);
                
            // Desactivar todos los productos de esta subcategoría
            Product::where('sub_category_id', $subCategory->id)
                ->update(['status' => 0]);
        }
        // Si la subcategoría se está activando (status = 1)
        else {
            // Activar todas las childcategories relacionadas
            ChildCategory::where('sub_category_id', $subCategory->id)
                ->update(['status' => 1]);
                
            // Activar todos los productos de esta subcategoría
            Product::where('sub_category_id', $subCategory->id)
                ->update(['status' => 1]);
            
            // Verificar si la categoría padre está activa
            $parentCategory = Category::find($subCategory->category_id);
            if ($parentCategory && $parentCategory->status == 0) {
                // Activar la categoría padre
                $parentCategory->status = 1;
                $parentCategory->save();
            }
        }
        
        return response(['message' => 'Status has been updated!']);
    }
}
