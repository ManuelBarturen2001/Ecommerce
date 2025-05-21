<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\ChildCategoryDataTable;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\HomePageSetting;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Str;

class ChildCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ChildCategoryDataTable $dataTable)
    {
        return $dataTable->render('admin.child-category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.child-category.create', compact('categories'));
    }

    /**
     * Get sub categories
     */
    public function getSubCategories(Request $request)
    {
        $subCategories = SubCategory::where('category_id', $request->id)->where('status', 1)->get();
        return $subCategories;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category' => ['required'],
            'sub_category' => ['required'],
            // 'name' => ['required', 'max:200', 'unique:child_categories,name'],
            'name' => [
                'required',
                'max:200',
                Rule::unique('child_categories', 'name')->where(function ($query) use ($request) {
                    return $query->where('category_id', $request->category)
                                ->where('sub_category_id', $request->sub_category);
                }),
            ],
            'status' => ['required']
        ]);

        $childCategory = new ChildCategory();

        $childCategory->category_id = $request->category;
        $childCategory->sub_category_id = $request->sub_category;
        $childCategory->name = $request->name;
        $childCategory->slug = Str::slug($request->name);
        $childCategory->status = $request->status;
        $childCategory->save();

        toastr('Created Successfully!', 'success');

        return redirect()->route('admin.child-category.index');
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
        $childCategory = ChildCategory::findOrFail($id);
        $subCategories = SubCategory::where('category_id', $childCategory->category_id)->get();

        return view('admin.child-category.edit', compact('categories', 'childCategory', 'subCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'category' => ['required'],
            'sub_category' => ['required'],
            // 'name' => ['required', 'max:200', 'unique:child_categories,name,'.$id],
            'name' => [
                'required',
                'max:200',
                Rule::unique('child_categories', 'name')->ignore($id)->where(function ($query) use ($request) {
                    return $query->where('category_id', $request->category)
                                ->where('sub_category_id', $request->sub_category);
                }),
            ],
            'status' => ['required']
        ]);

        $childCategory = ChildCategory::findOrFail($id);

        $childCategory->category_id = $request->category;
        $childCategory->sub_category_id = $request->sub_category;
        $childCategory->name = $request->name;
        $childCategory->slug = Str::slug($request->name);
        $childCategory->status = $request->status;
        $childCategory->save();

        toastr('Update Successfully!', 'success');

        return redirect()->route('admin.child-category.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $childCategory = ChildCategory::findOrFail($id);
        if(Product::where('child_category_id', $childCategory->id)->count() > 0){
            return response(['status' => 'error', 'message' => 'This item contain relation can\'t delete it.']);
        }
        $homeSettings = HomePageSetting::all();

        foreach($homeSettings as $item){
            $array = json_decode($item->value, true);
            $collection = collect($array);
            if($collection->contains('child_category', $childCategory->id)){
                return response(['status' => 'error', 'message' => 'This item contain relation can\'t delete it.']);
            }
        }

        $childCategory->delete();

        return response(['status' => 'success', 'message' => 'Deleted Successfully!']);
    }

    public function changeStatus(Request $request)
    {
        $childCategory = ChildCategory::findOrFail($request->id);
        $newStatus = $request->status == 'true' ? 1 : 0;
        $childCategory->status = $newStatus;
        $childCategory->save();
        
        // Si la childcategory se está desactivando (status = 0)
        if ($newStatus == 0) {
            // Desactivar todos los productos de esta childcategory
            Product::where('child_category_id', $childCategory->id)
                ->update(['status' => 0]);
        }
        // Si la childcategory se está activando (status = 1)
        else {
            // Activar todos los productos de esta childcategory
            Product::where('child_category_id', $childCategory->id)
                ->update(['status' => 1]);
            
            // Verificar si la subcategoría padre está activa
            $parentSubCategory = SubCategory::find($childCategory->sub_category_id);
            if ($parentSubCategory && $parentSubCategory->status == 0) {
                // Activar la subcategoría padre
                $parentSubCategory->status = 1;
                $parentSubCategory->save();
                
                // Verificar si la categoría principal está activa
                $parentCategory = Category::find($parentSubCategory->category_id);
                if ($parentCategory && $parentCategory->status == 0) {
                    // Activar la categoría principal
                    $parentCategory->status = 1;
                    $parentCategory->save();
                }
            }
        }
        
        return response(['message' => 'Status has been updated!']);
    }
}
