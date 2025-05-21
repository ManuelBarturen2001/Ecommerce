<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\CategoryDataTable;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\ChildCategory;
use Illuminate\Http\Request;
use Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CategoryDataTable $dataTable)
    {
        return $dataTable->render('admin.category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'icon' => ['required', 'not_in:empty'],
            'name' => ['required', 'max:200', 'unique:categories,name'],
            'status' => ['required']
        ]);

        $category = new Category();

        $category->icon = $request->icon;
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->status = $request->status;
        $category->save();

        toastr('Created Successfully!', 'success');

        return redirect()->route('admin.category.index');
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
        $category = Category::findOrFail($id);
        return view('admin.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'icon' => ['required', 'not_in:empty'],
            'name' => ['required', 'max:200', 'unique:categories,name,'.$id],
            'status' => ['required']
        ]);

        $category = Category::findOrFail($id);

        $category->icon = $request->icon;
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->status = $request->status;
        $category->save();

        toastr('Updated Successfully!', 'success');

        return redirect()->route('admin.category.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $subCategory = SubCategory::where('category_id', $category->id)->count();
        if($subCategory > 0){
            return response(['status' => 'error', 'message' => 'This items contain, sub items for delete this you have to delete the sub items first!']);
        }
        $category->delete();

        return response(['status' => 'success', 'Deleted Successfully!']);
    }


    public function changeStatus(Request $request)
    {
        $category = Category::findOrFail($request->id);
        $newStatus = $request->status == 'true' ? 1 : 0;
        $category->status = $newStatus;
        $category->save();
        
        // Obtener IDs de subcategorías relacionadas
        $subCategoryIds = SubCategory::where('category_id', $category->id)
            ->pluck('id')
            ->toArray();
            
        // Si la categoría se está desactivando (status = 0)
        if ($newStatus == 0) {
            // Desactivar todas las subcategorías relacionadas
            SubCategory::where('category_id', $category->id)
                ->update(['status' => 0]);
                
            // Desactivar todas las childcategories relacionadas
            if (!empty($subCategoryIds)) {
                ChildCategory::whereIn('sub_category_id', $subCategoryIds)
                    ->update(['status' => 0]);
            }
            
            // Desactivar todos los productos de esta categoría
            Product::where('category_id', $category->id)
                ->update(['status' => 0]);
        } 
        // Si la categoría se está activando (status = 1)
        else {
            // Activar todas las subcategorías relacionadas
            SubCategory::where('category_id', $category->id)
                ->update(['status' => 1]);
                
            // Activar todas las childcategories relacionadas
            if (!empty($subCategoryIds)) {
                ChildCategory::whereIn('sub_category_id', $subCategoryIds)
                    ->update(['status' => 1]);
            }
            
            // Activar todos los productos de esta categoría
            Product::where('category_id', $category->id)
                ->update(['status' => 1]);
        }
        
        return response(['message' => 'Status has been updated!']);
    }
}
