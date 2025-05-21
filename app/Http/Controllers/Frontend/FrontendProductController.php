<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Adverisement;
use App\Models\Brand;
use App\Models\Gender;
use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class FrontendProductController extends Controller
{
    public function productsIndex(Request $request)
    {   
        // Inicializar variables para filtros activos
        $activeFilters = [
            'category' => null,
            'subcategory' => null,
            'childcategory' => null,
            'gender' => null,
            'brand' => null,
            'range' => null,
        ];
        
        if ($request->has('category')) {
            $categorySlugs = (array) $request->category;
            $categoryIds = Category::whereIn('slug', $categorySlugs)->pluck('id');
            $activeFilters['category'] = $categorySlugs;
    
            $products = Product::withAvg('reviews', 'rating')->withCount('reviews')
                ->with(['variants', 'category', 'productImageGalleries'])
                ->where(['status' => 1, 'is_approved' => 1])
    
                // Filtro de categorías múltiples
                ->when($request->has('category'), function ($query) use ($request) {
                    $slugs = (array) $request->category;
                    $ids = Category::whereIn('slug', $slugs)->pluck('id');
                    $query->whereIn('category_id', $ids);
                })
                // Filtro de subcategorías múltiples
                ->when($request->has('subcategory'), function ($query) use ($request) {
                    $slugs = (array) $request->subcategory;
                    $ids = SubCategory::whereIn('slug', $slugs)->pluck('id');
                    $query->whereIn('sub_category_id', $ids);
                })
                // Filtro de marcas múltiples
                ->when($request->has('brand'), function ($query) use ($request) {
                    $slugs = (array) $request->brand;
                    $ids = Brand::whereIn('slug', $slugs)->pluck('id');
                    $query->whereIn('brand_id', $ids);
                })
                // Filtro de generos
                ->when($request->has('gender'), function ($query) use ($request) {
                    $slugs = (array) $request->gender;
                    $ids = Gender::whereIn('slug', $slugs)->pluck('id');
                    $query->whereIn('gender_id', $ids);
                })
                // Rango de precios
                ->when($request->has('range'), function ($query) use ($request) {
                    $price = explode(';', $request->range);
                    if (count($price) === 2 && is_numeric($price[0]) && is_numeric($price[1])) {
                        $query->whereBetween('price', [floatval($price[0]), floatval($price[1])]);
                    }
                })

                // Tipo
                ->when($request->has('type'), function ($query) use ($request) {
                    $type = $request->type;
                    if (in_array($type, ['new_arrival', 'featured_product', 'top_product', 'best_product'])) {
                        $query->where($type, 1);
                    }
                })
    
                ->paginate(24);
    
        } elseif ($request->has('subcategory')) {
            // Esta parte ya está implementada, pero necesitamos agregar el filtro de subcategorías múltiples
            $subcategorySlugs = (array) $request->subcategory;
            $subcategoryIds = SubCategory::whereIn('slug', $subcategorySlugs)->pluck('id');
            $activeFilters['subcategory'] = $subcategorySlugs;
            
            $products = Product::withAvg('reviews', 'rating')->withCount('reviews')
                ->with(['variants', 'category', 'productImageGalleries'])
                ->where(['status' => 1, 'is_approved' => 1])
                ->whereIn('sub_category_id', $subcategoryIds)
                ->when($request->has('brand'), function ($query) use ($request) {
                    $slugs = (array) $request->brand;
                    $ids = Brand::whereIn('slug', $slugs)->pluck('id');
                    $query->whereIn('brand_id', $ids);
                })
                ->when($request->has('gender'), function ($query) use ($request) {
                    $slugs = (array) $request->gender;
                    $ids = Gender::whereIn('slug', $slugs)->pluck('id');
                    $query->whereIn('gender_id', $ids);
                })
                ->when($request->has('range'), function ($query) use ($request) {
                    $price = explode(';', $request->range);
                    if (count($price) === 2 && is_numeric($price[0]) && is_numeric($price[1])) {
                        $query->whereBetween('price', [floatval($price[0]), floatval($price[1])]);
                    }
                })
                ->paginate(24);
    
        }  elseif ($request->has('childcategory')) {
            $childcategorySlugs = (array) $request->childcategory;
            $childcategoryIds = ChildCategory::whereIn('slug', $childcategorySlugs)->pluck('id');
            $activeFilters['childcategory'] = $childcategorySlugs;
            
            $products = Product::withAvg('reviews', 'rating')->withCount('reviews')
                ->with(['variants', 'category', 'productImageGalleries'])
                ->where(['status' => 1, 'is_approved' => 1])
                ->whereIn('child_category_id', $childcategoryIds)
                ->when($request->has('brand'), function ($query) use ($request) {
                    $slugs = (array) $request->brand;
                    $ids = Brand::whereIn('slug', $slugs)->pluck('id');
                    $query->whereIn('brand_id', $ids);
                })
                ->when($request->has('gender'), function ($query) use ($request) {
                    $slugs = (array) $request->gender;
                    $ids = Gender::whereIn('slug', $slugs)->pluck('id');
                    $query->whereIn('gender_id', $ids);
                })
                ->when($request->has('range'), function ($query) use ($request) {
                    $price = explode(';', $request->range);
                    if (count($price) === 2 && is_numeric($price[0]) && is_numeric($price[1])) {
                        $query->whereBetween('price', [floatval($price[0]), floatval($price[1])]);
                    }
                })
                ->paginate(24);

        

        } elseif ($request->has('search')) {
            $products = Product::withAvg('reviews', 'rating')->withCount('reviews')
                ->with(['variants', 'category', 'productImageGalleries'])
                ->where(['status' => 1, 'is_approved' => 1])
                ->where(function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('long_description', 'like', '%' . $request->search . '%')
                        ->orWhereHas('category', function ($query) use ($request) {
                            $query->where('name', 'like', '%' . $request->search . '%')
                                ->orWhere('long_description', 'like', '%' . $request->search . '%');
                        });
                })
                ->when($request->has('brand'), function ($query) use ($request) {
                    $slugs = (array) $request->brand;
                    $ids = Brand::whereIn('slug', $slugs)->pluck('id');
                    $query->whereIn('brand_id', $ids);
                })
                ->when($request->has('gender'), function ($query) use ($request) {
                    $slugs = (array) $request->gender;
                    $ids = Gender::whereIn('slug', $slugs)->pluck('id');
                    $query->whereIn('gender_id', $ids);
                })
                ->when($request->has('range'), function ($query) use ($request) {
                    $price = explode(';', $request->range);
                    if (count($price) === 2 && is_numeric($price[0]) && is_numeric($price[1])) {
                        $query->whereBetween('price', [floatval($price[0]), floatval($price[1])]);
                    }
                })
                ->paginate(24);

        } else {
            $products = Product::withAvg('reviews', 'rating')->withCount('reviews')
                ->with(['variants', 'category', 'productImageGalleries'])
                ->where(['status' => 1, 'is_approved' => 1])
                ->when($request->has('brand'), function ($query) use ($request) {
                    $slugs = (array) $request->brand;
                    $ids = Brand::whereIn('slug', $slugs)->pluck('id');
                    $query->whereIn('brand_id', $ids);
                })
                ->when($request->has('gender'), function ($query) use ($request) {
                    $slugs = (array) $request->gender;
                    $ids = Gender::whereIn('slug', $slugs)->pluck('id');
                    $query->whereIn('gender_id', $ids);
                })
                ->when($request->has('subcategory'), function ($query) use ($request) {
                    $slugs = (array) $request->subcategory;
                    $ids = SubCategory::whereIn('slug', $slugs)->pluck('id');
                    $query->whereIn('sub_category_id', $ids);
                })
                ->when($request->has('range'), function ($query) use ($request) {
                    $price = explode(';', $request->range);
                    if (count($price) === 2 && is_numeric($price[0]) && is_numeric($price[1])) {
                        $query->whereBetween('price', [floatval($price[0]), floatval($price[1])]);
                    }
                })
                ->orderBy('id', 'DESC')
                ->paginate(24);
                
            // Capturar filtros activos de la URL
            if ($request->has('brand')) {
                $activeFilters['brand'] = (array) $request->brand;
            }
            if ($request->has('gender')) {
                $activeFilters['gender'] = (array) $request->gender;
            }
            if ($request->has('range')) {
                $activeFilters['range'] = $request->range;
            }
        }

        $categories = Category::where(['status' => 1])->get();
        $brands = Brand::where(['status' => 1])->get();
        $genders = Gender::where(['status'=>1])->get();
        $subcategories = SubCategory::where(['status' => 1])->get();
        $productpage_banner_section = Adverisement::where('key', 'productpage_banner_section')->first();
        $productpage_banner_section = json_decode($productpage_banner_section?->value);

        return view('frontend.pages.product', compact(
            'products', 
            'categories', 
            'brands',
            'genders', 
            'productpage_banner_section', 
            'subcategories',
            'activeFilters'
        ));
    }

    /** Show product detail page */
    public function showProduct(string $slug)
    {
        $product = Product::with(['vendor', 'category', 'productImageGalleries', 'variants', 'brand', 'gender'])
            ->where('slug', $slug)
            ->where('status', 1)
            ->first();

        $reviews = ProductReview::where('product_id', $product->id)
            ->where('status', 1)
            ->paginate(24);

        return view('frontend.pages.product-detail', compact('product', 'reviews'));
    }

    public function chageListView(Request $request){
        Session::put('product_list_style', $request->style);
        return response()->json(['success' => true]);
    }
    
    /**
     * Maneja las URLs semánticas para categorías
     */
    public function byCategory(Request $request, $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        // Variables para filtros activos
        $activeFilters = [
            'category' => [$slug],
            'subcategory' => null,
            'childcategory' => null,
            'gender' => null,
            'brand' => null,
            'range' => null,
        ];
        
        // Actualizar filtros activos desde request
        if ($request->has('brand')) {
            $activeFilters['brand'] = (array) $request->brand;
        }
        if ($request->has('gender')) {
            $activeFilters['gender'] = (array) $request->gender;
        }
        if ($request->has('subcategory')) {
            $activeFilters['subcategory'] = (array) $request->subcategory;
        }
        if ($request->has('range')) {
            $activeFilters['range'] = $request->range;
        }
        
        // Obtener productos con filtro de categoría
        $products = Product::withAvg('reviews', 'rating')->withCount('reviews')
            ->with(['variants', 'category', 'productImageGalleries'])
            ->where([
                'category_id' => $category->id,
                'status' => 1,
                'is_approved' => 1
            ])
            // Aplicar filtro de subcategorías
            ->when($request->has('subcategory'), function ($query) use ($request) {
                $slugs = (array) $request->subcategory;
                $ids = SubCategory::whereIn('slug', $slugs)->pluck('id');
                $query->whereIn('sub_category_id', $ids);
            })
            // Aplicar filtro de marcas
            ->when($request->has('brand'), function ($query) use ($request) {
                $slugs = (array) $request->brand;
                $ids = Brand::whereIn('slug', $slugs)->pluck('id');
                $query->whereIn('brand_id', $ids);
            })
            // Aplicar filtro de géneros
            ->when($request->has('gender'), function ($query) use ($request) {
                $slugs = (array) $request->gender;
                $ids = Gender::whereIn('slug', $slugs)->pluck('id');
                $query->whereIn('gender_id', $ids);
            })
            // Aplicar filtro de rango de precios
            ->when($request->has('range'), function ($query) use ($request) {
                $price = explode(';', $request->range);
                if (count($price) === 2 && is_numeric($price[0]) && is_numeric($price[1])) {
                    $query->whereBetween('price', [floatval($price[0]), floatval($price[1])]);
                }
            })
            ->orderBy('id', 'DESC')
            ->paginate(24);
        
        $categories = Category::where(['status' => 1])->get();
        $brands = Brand::where(['status' => 1])->get();
        $genders = Gender::where(['status'=>1])->get();
        
        // Cargar sólo subcategorías relacionadas con esta categoría
        $subcategories = SubCategory::where(['status' => 1, 'category_id' => $category->id])->get();
        $relatedProducts = $this->getRelatedProducts($products, 'category', $category);
        $productpage_banner_section = Adverisement::where('key', 'productpage_banner_section')->first();
        $productpage_banner_section = json_decode($productpage_banner_section?->value);
        
        // Pasar el título de la página y el tipo de sección actual
        $pageTitle = $category->name;
        $currentSection = 'category';
        
        return view('frontend.pages.product', compact(
            'products', 
            'categories', 
            'brands', 
            'genders', 
            'productpage_banner_section', 
            'subcategories', 
            'pageTitle', 
            'currentSection',
            'activeFilters',
            'category',
            'relatedProducts'
        ));
    }
    
    /**
     * Maneja las URLs semánticas para subcategorías
     */
    public function bySubCategory(Request $request, $slug)
    {
        $subcategory = SubCategory::where('slug', $slug)->firstOrFail();
        
        // Variables para filtros activos
        $activeFilters = [
            'category' => null,
            'subcategory' => [$slug],
            'childcategory' => null,
            'gender' => null,
            'brand' => null,
            'range' => null,
        ];
        
        // Actualizar filtros activos desde request
        if ($request->has('brand')) {
            $activeFilters['brand'] = (array) $request->brand;
        }
        if ($request->has('gender')) {
            $activeFilters['gender'] = (array) $request->gender;
        }
        if ($request->has('category')) {
            $activeFilters['category'] = (array) $request->category;
        }
        if ($request->has('range')) {
            $activeFilters['range'] = $request->range;
        }
        
        // Obtener productos con filtro de subcategoría
        $products = Product::withAvg('reviews', 'rating')->withCount('reviews')
            ->with(['variants', 'category', 'productImageGalleries'])
            ->where([
                'sub_category_id' => $subcategory->id,
                'status' => 1,
                'is_approved' => 1
            ])
            // Aplicar filtro de categorías
            ->when($request->has('category'), function ($query) use ($request) {
                $slugs = (array) $request->category;
                $ids = Category::whereIn('slug', $slugs)->pluck('id');
                $query->whereIn('category_id', $ids);
            })
            // Aplicar filtro de marcas
            ->when($request->has('brand'), function ($query) use ($request) {
                $slugs = (array) $request->brand;
                $ids = Brand::whereIn('slug', $slugs)->pluck('id');
                $query->whereIn('brand_id', $ids);
            })
            // Aplicar filtro de géneros
            ->when($request->has('gender'), function ($query) use ($request) {
                $slugs = (array) $request->gender;
                $ids = Gender::whereIn('slug', $slugs)->pluck('id');
                $query->whereIn('gender_id', $ids);
            })
            // Aplicar filtro de rango de precios
            ->when($request->has('range'), function ($query) use ($request) {
                $price = explode(';', $request->range);
                if (count($price) === 2 && is_numeric($price[0]) && is_numeric($price[1])) {
                    $query->whereBetween('price', [floatval($price[0]), floatval($price[1])]);
                }
            })
            ->orderBy('id', 'DESC')
            ->paginate(24);
        
        $categories = Category::where(['status' => 1])->get();
        $brands = Brand::where(['status' => 1])->get();
        $genders = Gender::where(['status'=>1])->get();
        
        // Cargar todas las subcategorías para el menú pero marcar la actual
        $subcategories = SubCategory::where(['status' => 1])->get();
        $relatedProducts = $this->getRelatedProducts($products, 'subcategoryr', $subcategory);
        $productpage_banner_section = Adverisement::where('key', 'productpage_banner_section')->first();
        $productpage_banner_section = json_decode($productpage_banner_section?->value);
        
        // Pasar el título de la página y el tipo de sección actual
        $pageTitle = $subcategory->name;
        $currentSection = 'subcategory';
        
        return view('frontend.pages.product', compact(
            'products', 
            'categories', 
            'brands', 
            'genders', 
            'productpage_banner_section', 
            'subcategories', 
            'pageTitle', 
            'currentSection',
            'activeFilters',
            'subcategory',
            'relatedProducts'
        ));
    }
    
    /**
     * Maneja las URLs semánticas para categorías hijo
     */
    public function byChildCategory(Request $request,$slug)
    {
        $childcategory = ChildCategory::where('slug', $slug)->firstOrFail();
        
        // Variables para filtros activos
        $activeFilters = [
            'category' => null,
            'subcategory' => null,
            'childcategory' => [$slug],
            'gender' => null,
            'brand' => null,
            'range' => null,
        ];
        
        // Actualizar filtros activos desde request
        if ($request->has('brand')) {
            $activeFilters['brand'] = (array) $request->brand;
        }
        if ($request->has('gender')) {
            $activeFilters['gender'] = (array) $request->gender;
        }
        if ($request->has('category')) {
            $activeFilters['category'] = (array) $request->category;
        }
        if ($request->has('subcategory')) {
            $activeFilters['subcategory'] = (array) $request->subcategory;
        }
        if ($request->has('range')) {
            $activeFilters['range'] = $request->range;
        }
        
        // Obtener productos con filtro de childcategory
        $products = Product::withAvg('reviews', 'rating')->withCount('reviews')
            ->with(['variants', 'category', 'productImageGalleries'])
            ->where([
                'child_category_id' => $childcategory->id,
                'status' => 1,
                'is_approved' => 1
            ])
            // Aplicar filtro de categorías
            ->when($request->has('category'), function ($query) use ($request) {
                $slugs = (array) $request->category;
                $ids = Category::whereIn('slug', $slugs)->pluck('id');
                $query->whereIn('category_id', $ids);
            })
            // Aplicar filtro de subcategorías
            ->when($request->has('subcategory'), function ($query) use ($request) {
                $slugs = (array) $request->subcategory;
                $ids = SubCategory::whereIn('slug', $slugs)->pluck('id');
                $query->whereIn('sub_category_id', $ids);
            })
            // Aplicar filtro de marcas
            ->when($request->has('brand'), function ($query) use ($request) {
                $slugs = (array) $request->brand;
                $ids = Brand::whereIn('slug', $slugs)->pluck('id');
                $query->whereIn('brand_id', $ids);
            })
            // Aplicar filtro de géneros
            ->when($request->has('gender'), function ($query) use ($request) {
                $slugs = (array) $request->gender;
                $ids = Gender::whereIn('slug', $slugs)->pluck('id');
                $query->whereIn('gender_id', $ids);
            })
            // Aplicar filtro de rango de precios
            ->when($request->has('range'), function ($query) use ($request) {
                $price = explode(';', $request->range);
                if (count($price) === 2 && is_numeric($price[0]) && is_numeric($price[1])) {
                    $query->whereBetween('price', [floatval($price[0]), floatval($price[1])]);
                }
            })
            ->orderBy('id', 'DESC')
            ->paginate(24);
        
        $categories = Category::where(['status' => 1])->get();
        $brands = Brand::where(['status' => 1])->get();
        $genders = Gender::where(['status'=>1])->get();
        $subcategories = SubCategory::where(['status' => 1])->get();
        $relatedProducts = $this->getRelatedProducts($products, 'childcategory', $childcategory);
        $productpage_banner_section = Adverisement::where('key', 'productpage_banner_section')->first();
        $productpage_banner_section = json_decode($productpage_banner_section?->value);
        
        // Pasar el título de la página y el tipo de sección actual
        $pageTitle = $childcategory->name;
        $currentSection = 'childcategory';
        
        return view('frontend.pages.product', compact(
            'products', 
            'categories', 
            'brands', 
            'genders', 
            'productpage_banner_section', 
            'subcategories', 
            'pageTitle', 
            'currentSection',
            'activeFilters',
            'childcategory',
            'relatedProducts'
        ));
    }
    
    /**
     * Maneja las URLs semánticas para géneros
     */
    public function byGender(Request $request, $slug)
    {
        $gender = Gender::where('slug', $slug)->firstOrFail();
        
        // Variables para filtros activos
        $activeFilters = [
            'category' => null,
            'subcategory' => null,
            'childcategory' => null,
            'gender' => [$slug],
            'brand' => null,
            'range' => null,
        ];
        
        // Actualizar filtros activos desde request
        if ($request->has('brand')) {
            $activeFilters['brand'] = (array) $request->brand;
        }
        if ($request->has('category')) {
            $activeFilters['category'] = (array) $request->category;
        }
        if ($request->has('subcategory')) {
            $activeFilters['subcategory'] = (array) $request->subcategory;
        }
        if ($request->has('range')) {
            $activeFilters['range'] = $request->range;
        }
        
        // Obtener productos con filtro de gender
        $products = Product::withAvg('reviews', 'rating')->withCount('reviews')
            ->with(['variants', 'category', 'productImageGalleries'])
            ->where([
                'gender_id' => $gender->id,
                'status' => 1,
                'is_approved' => 1
            ])
            // Aplicar filtro de categorías
            ->when($request->has('category'), function ($query) use ($request) {
                $slugs = (array) $request->category;
                $ids = Category::whereIn('slug', $slugs)->pluck('id');
                $query->whereIn('category_id', $ids);
            })
            // Aplicar filtro de subcategorías
            ->when($request->has('subcategory'), function ($query) use ($request) {
                $slugs = (array) $request->subcategory;
                $ids = SubCategory::whereIn('slug', $slugs)->pluck('id');
                $query->whereIn('sub_category_id', $ids);
            })
            // Aplicar filtro de marcas
            ->when($request->has('brand'), function ($query) use ($request) {
                $slugs = (array) $request->brand;
                $ids = Brand::whereIn('slug', $slugs)->pluck('id');
                $query->whereIn('brand_id', $ids);
            })
            // Aplicar filtro de rango de precios
            ->when($request->has('range'), function ($query) use ($request) {
                $price = explode(';', $request->range);
                if (count($price) === 2 && is_numeric($price[0]) && is_numeric($price[1])) {
                    $query->whereBetween('price', [floatval($price[0]), floatval($price[1])]);
                }
            })
            ->orderBy('id', 'DESC')
            ->paginate(24);
            $relatedProducts = $this->getRelatedProducts($products, 'gender', $gender);
        
        $categories = Category::where(['status' => 1])->get();
        $brands = Brand::where(['status' => 1])->get();
        $genders = Gender::where(['status'=>1])->get();
        $subcategories = SubCategory::where(['status' => 1])->get();
        
        $productpage_banner_section = Adverisement::where('key', 'productpage_banner_section')->first();
        $productpage_banner_section = json_decode($productpage_banner_section?->value);
        
        // Pasar el título de la página y el tipo de sección actual
        $pageTitle = $gender->name;
        $currentSection = 'gender';
        
        return view('frontend.pages.product', compact(
            'products', 
            'categories', 
            'brands', 
            'genders', 
            'productpage_banner_section', 
            'subcategories', 
            'pageTitle', 
            'currentSection',
            'activeFilters',
            'gender',
            'relatedProducts'
        ));
    }
    
    /**
     * Maneja las URLs semánticas para marcas
     */
    public function byBrand(Request $request,$slug)
    {
        $brand = Brand::where('slug', $slug)->firstOrFail();
        
        // Variables para filtros activos
        $activeFilters = ['category' => null,'subcategory' => null,'childcategory' => null,'gender' => null,'brand' => [$slug],'range' => null,];
        
        // Actualizar filtros activos desde request
        if ($request->has('gender')) {
            $activeFilters['gender'] = (array) $request->gender;
        }
        if ($request->has('category')) {
            $activeFilters['category'] = (array) $request->category;
        }
        if ($request->has('subcategory')) {
            $activeFilters['subcategory'] = (array) $request->subcategory;
        }
        if ($request->has('range')) {
            $activeFilters['range'] = $request->range;
        }
        
        // Obtener productos con filtro de brand
        $products = Product::withAvg('reviews', 'rating')->withCount('reviews')
            ->with(['variants', 'category', 'productImageGalleries'])
            ->where([
                'brand_id' => $brand->id,
                'status' => 1,
                'is_approved' => 1
            ])
            // Aplicar filtro de categorías
            ->when($request->has('category'), function ($query) use ($request) {
                $slugs = (array) $request->category;
                $ids = Category::whereIn('slug', $slugs)->pluck('id');
                $query->whereIn('category_id', $ids);
            })
            // Aplicar filtro de subcategorías
            ->when($request->has('subcategory'), function ($query) use ($request) {
                $slugs = (array) $request->subcategory;
                $ids = SubCategory::whereIn('slug', $slugs)->pluck('id');
                $query->whereIn('sub_category_id', $ids);
            })
            // Aplicar filtro de géneros
            ->when($request->has('gender'), function ($query) use ($request) {
                $slugs = (array) $request->gender;
                $ids = Gender::whereIn('slug', $slugs)->pluck('id');
                $query->whereIn('gender_id', $ids);
            })
            // Aplicar filtro de rango de precios
            ->when($request->has('range'), function ($query) use ($request) {
                $price = explode(';', $request->range);
                if (count($price) === 2 && is_numeric($price[0]) && is_numeric($price[1])) {
                    $query->whereBetween('price', [floatval($price[0]), floatval($price[1])]);
                }
            })
            ->orderBy('id', 'DESC')
            ->paginate(24);
        
        $categories = Category::where(['status' => 1])->get();
        $brands = Brand::where(['status' => 1])->get();
        $genders = Gender::where(['status'=>1])->get();
        $subcategories = SubCategory::where(['status' => 1])->get();
        $relatedProducts = $this->getRelatedProducts($products, 'brand', $brand);
        $productpage_banner_section = Adverisement::where('key', 'productpage_banner_section')->first();
        $productpage_banner_section = json_decode($productpage_banner_section?->value);
        
        // Pasar el título de la página y el tipo de sección actual
        $pageTitle = $brand->name;
        $currentSection = 'brand';
        
        return view('frontend.pages.product', compact('products', 'categories', 'brands', 'genders', 'productpage_banner_section', 'subcategories', 'pageTitle', 'currentSection','activeFilters','brand','relatedProducts'));
    }

    /**
     * Maneja las vistas de productos especiales (nuevos, destacados, etc.)
     */
    public function specialProducts(Request $request, $type)
    {
        // Validar tipo de producto especial
        $validTypes = ['new_arrival', 'featured_product', 'top_product', 'best_product'];
        if (!in_array($type, $validTypes)) {
            abort(404);
        }
        
        // Variables para filtros activos
        $activeFilters = [
            'category' => null,
            'subcategory' => null,
            'childcategory' => null,
            'gender' => null,
            'brand' => null,
            'range' => null,
        ];
        
        // Actualizar filtros activos desde request
        if ($request->has('brand')) {
            $activeFilters['brand'] = (array) $request->brand;
        }
        if ($request->has('gender')) {
            $activeFilters['gender'] = (array) $request->gender;
        }
        if ($request->has('category')) {
            $activeFilters['category'] = (array) $request->category;
        }
        if ($request->has('subcategory')) {
            $activeFilters['subcategory'] = (array) $request->subcategory;
        }
        if ($request->has('range')) {
            $activeFilters['range'] = $request->range;
        }
        
        // Obtener productos con filtro de tipo especial
        $products = Product::withAvg('reviews', 'rating')->withCount('reviews')
            ->with(['variants', 'category', 'productImageGalleries'])
            ->where([
                'status' => 1,
                'is_approved' => 1,
                $type => 1  // Filtrar por tipo de producto especial
            ])
            // Aplicar filtro de categorías
            ->when($request->has('category'), function ($query) use ($request) {
                $slugs = (array) $request->category;
                $ids = Category::whereIn('slug', $slugs)->pluck('id');
                $query->whereIn('category_id', $ids);
            })
            // Aplicar filtro de subcategorías
            ->when($request->has('subcategory'), function ($query) use ($request) {
                $slugs = (array) $request->subcategory;
                $ids = SubCategory::whereIn('slug', $slugs)->pluck('id');
                $query->whereIn('sub_category_id', $ids);
            })
            // Aplicar filtro de marcas
            ->when($request->has('brand'), function ($query) use ($request) {
                $slugs = (array) $request->brand;
                $ids = Brand::whereIn('slug', $slugs)->pluck('id');
                $query->whereIn('brand_id', $ids);
            })
            // Aplicar filtro de géneros
            ->when($request->has('gender'), function ($query) use ($request) {
                $slugs = (array) $request->gender;
                $ids = Gender::whereIn('slug', $slugs)->pluck('id');
                $query->whereIn('gender_id', $ids);
            })
            // Aplicar filtro de rango de precios
            ->when($request->has('range'), function ($query) use ($request) {
                $price = explode(';', $request->range);
                if (count($price) === 2 && is_numeric($price[0]) && is_numeric($price[1])) {
                    $query->whereBetween('price', [floatval($price[0]), floatval($price[1])]);
                }
            })
            ->orderBy('id', 'DESC')
            ->paginate(24);
        
        $categories = Category::where(['status' => 1])->get();
        $brands = Brand::where(['status' => 1])->get();
        $genders = Gender::where(['status'=>1])->get();
        $subcategories = SubCategory::where(['status' => 1])->get();
        
        $productpage_banner_section = Adverisement::where('key', 'productpage_banner_section')->first();
        $productpage_banner_section = json_decode($productpage_banner_section?->value);
        
        // Definir títulos según el tipo
        $titles = [
            'new_arrival' => 'Nuevos Productos',
            'featured_product' => 'Productos Destacados',
            'top_product' => 'Productos Top',
            'best_product' => 'Mejores Productos'
        ];
        
        $pageTitle = $titles[$type];
        $currentSection = 'special';
        
        return view('frontend.pages.product', compact(
            'products', 
            'categories', 
            'brands', 
            'genders', 
            'productpage_banner_section', 
            'subcategories', 
            'pageTitle', 
            'currentSection',
            'activeFilters',
            'type'
        ));
    }

    /**
     * Método para manejar búsquedas avanzadas de productos
     */
    public function advancedSearch(Request $request)
    {
        // Variables para filtros activos
        $activeFilters = [
            'category' => null,
            'subcategory' => null,
            'childcategory' => null,
            'gender' => null,
            'brand' => null,
            'range' => null,
        ];
        
        // Actualizar filtros activos desde request
        if ($request->has('brand')) {
            $activeFilters['brand'] = (array) $request->brand;
        }
        if ($request->has('gender')) {
            $activeFilters['gender'] = (array) $request->gender;
        }
        if ($request->has('category')) {
            $activeFilters['category'] = (array) $request->category;
        }
        if ($request->has('subcategory')) {
            $activeFilters['subcategory'] = (array) $request->subcategory;
        }
        if ($request->has('range')) {
            $activeFilters['range'] = $request->range;
        }
        
        // Construir la consulta base
        $query = Product::withAvg('reviews', 'rating')->withCount('reviews')
            ->with(['variants', 'category', 'productImageGalleries'])
            ->where(['status' => 1, 'is_approved' => 1]);
        
        // Aplicar búsqueda por término
        if ($request->has('search')) {
            $query->where(function ($subquery) use ($request) {
                $subquery->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('long_description', 'like', '%' . $request->search . '%')
                    ->orWhere('short_description', 'like', '%' . $request->search . '%')
                    ->orWhereHas('category', function ($catQuery) use ($request) {
                        $catQuery->where('name', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('subcategory', function ($subcatQuery) use ($request) {
                        $subcatQuery->where('name', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('brand', function ($brandQuery) use ($request) {
                        $brandQuery->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }
        
        // Aplicar filtro de categorías
        if ($request->has('category')) {
            $slugs = (array) $request->category;
            $ids = Category::whereIn('slug', $slugs)->pluck('id');
            $query->whereIn('category_id', $ids);
        }
        
        // Aplicar filtro de subcategorías
        if ($request->has('subcategory')) {
            $slugs = (array) $request->subcategory;
            $ids = SubCategory::whereIn('slug', $slugs)->pluck('id');
            $query->whereIn('sub_category_id', $ids);
        }
        
        // Aplicar filtro de categorías hijo
        if ($request->has('childcategory')) {
            $slugs = (array) $request->childcategory;
            $ids = ChildCategory::whereIn('slug', $slugs)->pluck('id');
            $query->whereIn('child_category_id', $ids);
        }
        
        // Aplicar filtro de marcas
        if ($request->has('brand')) {
            $slugs = (array) $request->brand;
            $ids = Brand::whereIn('slug', $slugs)->pluck('id');
            $query->whereIn('brand_id', $ids);
        }
        
        // Aplicar filtro de géneros
        if ($request->has('gender')) {
            $slugs = (array) $request->gender;
            $ids = Gender::whereIn('slug', $slugs)->pluck('id');
            $query->whereIn('gender_id', $ids);
        }
        
        // Aplicar filtro de rango de precios
        if ($request->has('range')) {
            $price = explode(';', $request->range);
            if (count($price) === 2 && is_numeric($price[0]) && is_numeric($price[1])) {
                $query->whereBetween('price', [floatval($price[0]), floatval($price[1])]);
            }
        }
        
        // Ordenar productos
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'ASC');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'DESC');
                    break;
                case 'newest':
                    $query->orderBy('id', 'DESC');
                    break;
                case 'popularity':
                    $query->orderBy('reviews_count', 'DESC');
                    break;
                case 'rating':
                    $query->orderBy('reviews_avg_rating', 'DESC');
                    break;
                default:
                    $query->orderBy('id', 'DESC');
            }
        } else {
            $query->orderBy('id', 'DESC');
        }
        
        // Ejecutar la consulta
        $products = $query->paginate(24);
        
        $categories = Category::where(['status' => 1])->get();
        $brands = Brand::where(['status' => 1])->get();
        $genders = Gender::where(['status'=>1])->get();
        $subcategories = SubCategory::where(['status' => 1])->get();
        
        $productpage_banner_section = Adverisement::where('key', 'productpage_banner_section')->first();
        $productpage_banner_section = json_decode($productpage_banner_section?->value);
        
        // Pasar el título de la página y el tipo de sección actual
        $pageTitle = $request->has('search') ? 'Resultados para: ' . $request->search : 'Búsqueda avanzada';
        $currentSection = 'search';
        
        return view('frontend.pages.product', compact(
            'products', 
            'categories', 
            'brands', 
            'genders', 
            'productpage_banner_section', 
            'subcategories', 
            'pageTitle', 
            'currentSection',
            'activeFilters'
        ));
    }
    
    /**
     * Maneja la vista de ofertas y descuentos
     */
    public function discountProducts(Request $request)
    {
        // Variables para filtros activos
        $activeFilters = [
            'category' => null,
            'subcategory' => null,
            'childcategory' => null,
            'gender' => null,
            'brand' => null,
            'range' => null,
        ];
        
        // Actualizar filtros activos desde request
        if ($request->has('brand')) {
            $activeFilters['brand'] = (array) $request->brand;
        }
        if ($request->has('gender')) {
            $activeFilters['gender'] = (array) $request->gender;
        }
        if ($request->has('category')) {
            $activeFilters['category'] = (array) $request->category;
        }
        if ($request->has('subcategory')) {
            $activeFilters['subcategory'] = (array) $request->subcategory;
        }
        if ($request->has('range')) {
            $activeFilters['range'] = $request->range;
        }
        
        // Obtener productos con descuento
        $products = Product::withAvg('reviews', 'rating')->withCount('reviews')
            ->with(['variants', 'category', 'productImageGalleries'])
            ->where([
                'status' => 1,
                'is_approved' => 1,
            ])
            ->where('offer_price', '>', 0) // Solo productos con descuento
            ->where('offer_price', '<', \DB::raw('price')) // El precio de oferta debe ser menor que el precio regular
            
            // Aplicar filtro de categorías
            ->when($request->has('category'), function ($query) use ($request) {
                $slugs = (array) $request->category;
                $ids = Category::whereIn('slug', $slugs)->pluck('id');
                $query->whereIn('category_id', $ids);
            })
            // Aplicar filtro de subcategorías
            ->when($request->has('subcategory'), function ($query) use ($request) {
                $slugs = (array) $request->subcategory;
                $ids = SubCategory::whereIn('slug', $slugs)->pluck('id');
                $query->whereIn('sub_category_id', $ids);
            })
            // Aplicar filtro de marcas
            ->when($request->has('brand'), function ($query) use ($request) {
                $slugs = (array) $request->brand;
                $ids = Brand::whereIn('slug', $slugs)->pluck('id');
                $query->whereIn('brand_id', $ids);
            })
            // Aplicar filtro de géneros
            ->when($request->has('gender'), function ($query) use ($request) {
                $slugs = (array) $request->gender;
                $ids = Gender::whereIn('slug', $slugs)->pluck('id');
                $query->whereIn('gender_id', $ids);
            })
            // Aplicar filtro de rango de precios
            ->when($request->has('range'), function ($query) use ($request) {
                $price = explode(';', $request->range);
                if (count($price) === 2 && is_numeric($price[0]) && is_numeric($price[1])) {
                    $query->whereBetween('offer_price', [floatval($price[0]), floatval($price[1])]);
                }
            })
            // Ordenar por porcentaje de descuento por defecto
            ->orderByRaw('((price - offer_price) / price) DESC')
            ->paginate(24);
        
        $categories = Category::where(['status' => 1])->get();
        $brands = Brand::where(['status' => 1])->get();
        $genders = Gender::where(['status'=>1])->get();
        $subcategories = SubCategory::where(['status' => 1])->get();
        
        $productpage_banner_section = Adverisement::where('key', 'productpage_banner_section')->first();
        $productpage_banner_section = json_decode($productpage_banner_section?->value);
        
        $pageTitle = 'Ofertas y Descuentos';
        $currentSection = 'discount';
        
        return view('frontend.pages.product', compact(
            'products', 
            'categories', 
            'brands', 
            'genders', 
            'productpage_banner_section', 
            'subcategories', 
            'pageTitle', 
            'currentSection',
            'activeFilters'
        ));
    }

    private function getRelatedProducts($currentProducts, $currentSection = null, $sectionValue = null, $limit = 8)
    {
        // Evitar mostrar productos que ya están en la lista principal
        $excludeIds = $currentProducts->pluck('id')->toArray();
        
        $query = Product::where('status', 1)
                        ->where('is_approved', 1)
                        ->whereNotIn('id', $excludeIds)
                        ->with(['category', 'productImageGalleries'])
                        ->withAvg('reviews', 'rating')
                        ->withCount('reviews');
        
        // Filtrar según la sección actual
        if ($currentSection && $sectionValue) {
            switch ($currentSection) {
                case 'category':
                    $query->where('category_id', $sectionValue->id);
                    break;
                case 'subcategory':
                    $query->where('sub_category_id', $sectionValue->id);
                    break;
                case 'childcategory':
                    $query->where('child_category_id', $sectionValue->id);
                    break;
                case 'brand':
                    $query->where('brand_id', $sectionValue->id);
                    break;
                case 'gender':
                    $query->where('gender_id', $sectionValue->id);
                    break;
            }
        }
        
        return $query->inRandomOrder()->take($limit)->get();
    }

    public function showByType($type)
    {
        $validTypes = [
            'new_arrival' => 'Nuevo en llegada',
            'featured_product' => 'Destacado',
            'top_product' => 'Producto Top',
            'best_product' => 'Mejor Producto',
        ];

        // Comprobar si el tipo existe
        if (!array_key_exists($type, $validTypes)) {
            abort(404);
        }

        $products = Product::withAvg('reviews', 'rating')->withCount('reviews')
            ->with(['variants', 'category', 'productImageGalleries'])
            ->where(['status' => 1, 'is_approved' => 1])
            ->where('product_type', $type)  // Filtrar por el tipo específico
            ->orderBy('id', 'DESC')
            ->paginate(24);

        $pageTitle = $validTypes[$type];
        
        $categories = Category::where(['status' => 1])->get();
        $brands = Brand::where(['status' => 1])->get();
        $genders = Gender::where(['status'=>1])->get();
        $subcategories = SubCategory::where(['status' => 1])->get();
        $productpage_banner_section = Adverisement::where('key', 'productpage_banner_section')->first();
        $productpage_banner_section = json_decode($productpage_banner_section?->value);
        // $relatedProducts = $this->getRelatedProducts($products, 'categories', $categories);
        return view('frontend.pages.product', compact('products', 'categories', 'brands', 'genders', 'productpage_banner_section', 'subcategories', 'pageTitle', 'type'/*, 'relatedProducts'*/));
    }


}