@extends('frontend.layouts.master')

@section('title')
{{$settings->site_name}} || @if(isset($pageTitle)) {{$pageTitle}} @else Productos @endif
@endsection
@section('content')
        <!--============================
        BREADCRUMB START
    ==============================-->
    <section id="wsus__breadcrumb">
        <div class="wsus_breadcrumb_overlay">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-12">
                        <div class="breadcrumb__content">
                            <h4>@if(isset($pageTitle)) {{$pageTitle}} @else Productos @endif</h4>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fas fa-home"></i> Inicio</a></li>
                                    <li class="breadcrumb-item"><a href="{{route('products.index')}}">Producto</a></li>
                                    @if(isset($pageTitle))
                                    <li class="breadcrumb-item active" aria-current="page">{{$pageTitle}}</li>
                                    @endif
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
        BREADCRUMB END
    ==============================-->
    <!--============================
        PRODUCT PAGE START
    ==============================-->
    <section id="wsus__product_page">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-4">
                    <div class="wsus__sidebar_filter">
                        <p>filtrar</p>
                        <span class="wsus__filter_icon">
                            <i class="far fa-minus" id="minus"></i>
                            <i class="far fa-plus" id="plus"></i>
                        </span>
                    </div>
                    <div class="wsus__product_sidebar" id="sticky_sidebar">
                        <form id="filter_form" action="{{url()->current()}}">
                            <!-- Mantener los parámetros actuales excepto los que vamos a actualizar -->
                            @foreach (request()->query() as $key => $value)
                                @if(!in_array($key, ['category', 'brand', 'gender', 'range', 'subcategory']))
                                    <input type="hidden" name="{{$key}}" value="{{$value}}" />
                                @endif
                            @endforeach
                            
                            <div class="accordion" id="accordionExample">
                                <!-- Filtro de categorías - oculto si estamos en una página de categoría -->
                                @if(!isset($currentSection) || $currentSection != 'category')
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Todas las Categorías
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show"
                                        aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="wsus__sidebar_categories">
                                                @foreach ($categories as $category)
                                                <div class="form-check">
                                                <input class="form-check-input filter-checkbox" type="checkbox" 
                                                    name="category[]" value="{{$category->slug}}" 
                                                    id="category_{{$category->id}}"
                                                    {{ in_array($category->slug, (array)($activeFilters['category'] ?? request()->category)) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="category_{{$category->id}}">
                                                        {{$category->name}}
                                                    </label>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <!-- Filtro de subcategorías - mostrar solo las relevantes según la categoría actual -->
                                @if(!isset($currentSection) || $currentSection != 'subcategory')
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingSubCat">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseSubCat" aria-expanded="true"
                                            aria-controls="collapseSubCat">
                                            Subcategorías
                                        </button>
                                    </h2>
                                    <div id="collapseSubCat" class="accordion-collapse collapse show"
                                        aria-labelledby="headingSubCat" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="wsus__sidebar_brands">
                                                @foreach ($subcategories as $sub)
                                                <div class="form-check">
                                                    <input class="form-check-input filter-checkbox" type="checkbox"
                                                        name="subcategory[]" value="{{ $sub->slug }}"
                                                        id="subcat_{{ $sub->id }}"
                                                        {{ in_array($sub->slug, (array)($activeFilters['subcategory'] ?? request()->subcategory)) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="subcat_{{ $sub->id }}">
                                                        {{ $sub->name }}
                                                    </label>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <!-- Filtro de marca - mostrar siempre -->
                                @if(!isset($currentSection) || $currentSection != 'brand')
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTwo">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                            Marca
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse show"
                                        aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="wsus__sidebar_brands">
                                                @foreach ($brands as $brand)
                                                <div class="form-check">
                                                    <input class="form-check-input filter-checkbox" type="checkbox"
                                                        name="brand[]" value="{{ $brand->slug }}"
                                                        id="brand_{{ $brand->id }}"
                                                        {{ in_array($brand->slug, (array)($activeFilters['brand'] ?? request()->brand)) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="brand_{{ $brand->id }}">
                                                        {{ $brand->name }}
                                                    </label>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <!-- Filtro de géneros - ocultar en páginas de género -->
                                @if(!isset($currentSection) || $currentSection != 'gender')
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingThree">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseThree" aria-expanded="true"
                                            aria-controls="collapseThree">
                                            Género
                                        </button>
                                    </h2>
                                    <div id="collapseThree" class="accordion-collapse collapse show"
                                        aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="wsus__sidebar_brand">
                                                @foreach ($genders as $gender)
                                                <div class="form-check">
                                                    <input class="form-check-input filter-checkbox" type="checkbox"
                                                        name="gender[]" value="{{ $gender->slug }}"
                                                        id="gender_{{ $gender->id }}"
                                                        {{ in_array($gender->slug, (array)($activeFilters['gender'] ?? request()->gender)) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="gender_{{ $gender->id }}">
                                                        {{ $gender->name }}
                                                    </label>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTwo">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            Precio
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse show"
                                        aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="price_ranger">
                                                <input type="hidden" id="slider_range" name="range" class="flat-slider" />
                                                <div class="price_ranger_values mt-2">
                                                    <span>Rango: </span>
                                                    <span id="price_range_display">S/. 0 - S/. 3000</span>
                                                </div>
                                                <button type="submit" class="common_btn mt-3">filtrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <button type="submit" class="btn btn-primary mt-4">Aplicar filtros</button> -->
                            <div class="text-center mt-4">
                                    <a href="{{ url()->current() }}" class="btn" style="background-color: var(--general); color: white;">
                                        <i class="fas fa-times-circle"></i> Limpiar filtros
                                    </a>
                            </div>
                        </form>
                        <!-- @if($productpage_banner_section && $productpage_banner_section->banner_one->status == 1)
                            <div class="wsus__sidebar_banner mt_35">
                                <a href="{{ $productpage_banner_section->banner_one->banner_url }}">
                                    <img src="{{ asset($productpage_banner_section->banner_one->banner_image) }}" alt="banner" class="img-fluid w-100">
                                </a>
                            </div>
                        @endif -->
                    </div>
                </div>
                <div class="col-xl-9 col-lg-8">
                    <div class="row">
                        <div class="col-xl-12 d-none d-md-block mt-md-4 mt-lg-0">
                            <div class="wsus__product_topbar">
                                <div class="wsus__product_topbar_left">
                                    <div class="nav nav-pills" id="v-pills-tab" role="tablist"
                                        aria-orientation="vertical">
                                        <button class="nav-link list-view active" id="v-pills-home-tab" data-bs-toggle="pill"
                                            data-bs-target="#v-pills-home" type="button" role="tab"
                                            aria-controls="v-pills-home" aria-selected="true">
                                            <i class="fas fa-th"></i>
                                        </button>
                                        <button class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill"
                                            data-bs-target="#v-pills-profile" type="button" role="tab"
                                            aria-controls="v-pills-profile" aria-selected="false">
                                            <i class="fas fa-list-ul"></i>
                                        </button>
                                    </div>

                                </div>
                                <div class="wsus__topbar_select">
                                    <p>Mostrando {{ $products->firstItem() }} a {{ $products->lastItem() }} de {{ $products->total() }} productos</p>
                                </div>
                            </div>
                        </div>
                        @if (isset($currentSection))
                        <div class="col-12 mt-3">
                            <div class="current-filter-section">
                                <!-- <h4>{{ $pageTitle }}</h4> -->
                                <div class="active-filters">
                                    @if (isset($currentSection) && $currentSection == 'category')
                                        <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary">
                                            {{ $category->name }} <i class="fas fa-times"></i>
                                        </a>
                                    @elseif (isset($currentSection) && $currentSection == 'subcategory')
                                        <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary">
                                            {{ $subcategory->name }} <i class="fas fa-times"></i>
                                        </a>
                                    @elseif (isset($currentSection) && $currentSection == 'childcategory')
                                        <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary">
                                            {{ $childcategory->name }} <i class="fas fa-times"></i>
                                        </a>
                                    @elseif (isset($currentSection) && $currentSection == 'gender')
                                        <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary">
                                            {{ $gender->name }} <i class="fas fa-times"></i>
                                        </a>
                                    @elseif (isset($currentSection) && $currentSection == 'brand')
                                        <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary">
                                            {{ $brand->name }} <i class="fas fa-times"></i>
                                        </a>
                                    @elseif (isset($currentSection) && $currentSection == 'type')
                                        <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary">
                                            {{ $pageTitle }} <i class="fas fa-times"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="tab-content" id="v-pills-tabContent">
                            <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel"
                                aria-labelledby="v-pills-home-tab">
                                <div class="row">
                                    @if (count($products) == 0)
                                        <div class="col-12 text-center mt-5">
                                            <h3>No se encontraron productos con los filtros seleccionados</h3>
                                        </div>
                                    @endif
                                    
                                    @foreach ($products as $product)
                                    <div class="col-xl-4 col-sm-6">
                                        <div class="wsus__product_item">
                                            <span class="wsus__new">{{productType($product->product_type)}}</span>
                                            @if(checkDiscount($product))
                                                <span class="wsus__minus">-{{calculateDiscountPercent($product->price, $product->offer_price)}}%</span>
                                            @endif
                                            <a class="wsus__pro_link" href="{{route('product-detail', $product->slug)}}">
                                                <img src="{{asset($product->thumb_image)}}" alt="product" class="img-fluid w-100 img_1" />
                                                <img src="@if(isset($product->productImageGalleries[0]->image)) {{asset($product->productImageGalleries[0]->image)}} @else {{asset($product->thumb_image)}} @endif" alt="product" class="img-fluid w-100 img_2" />
                                            </a>
                                            <ul class="wsus__single_pro_icon">
                                                <li><a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal" class="show_product_modal" data-id="{{$product->id}}"><i class="far fa-eye"></i></a></li>
                                                <li><a href="#" class="add_to_wishlist" data-id="{{$product->id}}"><i class="far fa-heart"></i></a></li>
                                                <!-- <li><a href="#"><i class="far fa-random"></i></a> -->
                                            </ul>
                                            <div class="wsus__product_details">
                                                <a class="wsus__category" href="#">{{$product->category->name}} </a>
                                                <p class="wsus__pro_rating">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= $product->ratings_avg_review)
                                                        <i class="fas fa-star"></i>
                                                        @else
                                                        <i class="far fa-star"></i>
                                                        @endif
                                                    @endfor
                                                    <span>({{$product->reviews_count}} reseña)</span>
                                                </p>
                                                <a class="wsus__pro_name" href="{{route('product-detail', $product->slug)}}">{{limitText($product->name, 53)}}</a>
                                                @if(checkDiscount($product))
                                                    <p class="wsus__price">S/.{{$product->offer_price}} <del>S/.{{$product->price}}</del></p>
                                                @else
                                                    <p class="wsus__price">S/.{{$product->price}}</p>
                                                @endif
                                                <form class="shopping-cart-form">
                                                    <input type="hidden" name="product_id" value="{{$product->id}}">
                                                    @foreach ($product->variants as $variant)
                                                    @if ($variant->status != 0)
                                                        <select class="d-none" name="variants_items[]">
                                                            @foreach ($variant->productVariantItems as $variantItem)
                                                                @if ($variantItem->status != 0)
                                                                    <option value="{{$variantItem->id}}" {{$variantItem->is_default == 1 ? 'selected' : ''}}>{{$variantItem->name}} (S/.{{$variantItem->price}})</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    @endif
                                                    @endforeach
                                                    <input class="" name="qty" type="hidden" min="1" max="100" value="1" />
                                                    <button class="add_cart" type="submit">añadir al carrito</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="tab-pane fade" id="v-pills-profile" role="tabpanel"
                                aria-labelledby="v-pills-profile-tab">
                                <div class="row">
                                    @if (count($products) == 0)
                                        <div class="col-12 text-center mt-5">
                                            <h3>No se encontraron productos con los filtros seleccionados</h3>
                                        </div>
                                    @endif
                                    
                                    @foreach ($products as $product)
                                    <div class="col-xl-12">
                                        <div class="wsus__product_item wsus__list_view">
                                            <span class="wsus__new">{{productType($product->product_type)}}</span>
                                            @if(checkDiscount($product))
                                                <span class="wsus__minus">-{{calculateDiscountPercent($product->price, $product->offer_price)}}%</span>
                                            @endif
                                            <a class="wsus__pro_link" href="{{route('product-detail', $product->slug)}}">
                                                <img src="{{asset($product->thumb_image)}}" alt="product" class="img-fluid w-100 img_1" />
                                                <img src="@if(isset($product->productImageGalleries[0]->image)) {{asset($product->productImageGalleries[0]->image)}} @else {{asset($product->thumb_image)}} @endif" alt="product" class="img-fluid w-100 img_2" />
                                            </a>
                                            <div class="wsus__product_details">
                                                <a class="wsus__category" href="#">{{$product->category->name}} </a>
                                                <p class="wsus__pro_rating">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= $product->ratings_avg_review)
                                                        <i class="fas fa-star"></i>
                                                        @else
                                                        <i class="far fa-star"></i>
                                                        @endif
                                                    @endfor
                                                    <span>({{$product->reviews_count}} reseña)</span>
                                                </p>
                                                <a class="wsus__pro_name" href="{{route('product-detail', $product->slug)}}">{{limitText($product->name, 53)}}</a>
                                                @if(checkDiscount($product))
                                                    <p class="wsus__price">S/.{{$product->offer_price}} <del>S/.{{$product->price}}</del></p>
                                                @else
                                                    <p class="wsus__price">S/.{{$product->price}}</p>
                                                @endif
                                                <form class="shopping-cart-form">
                                                    <input type="hidden" name="product_id" value="{{$product->id}}">
                                                    @foreach ($product->variants as $variant)
                                                    @if ($variant->status != 0)
                                                        <select class="d-none" name="variants_items[]">
                                                            @foreach ($variant->productVariantItems as $variantItem)
                                                                @if ($variantItem->status != 0)
                                                                    <option value="{{$variantItem->id}}" {{$variantItem->is_default == 1 ? 'selected' : ''}}>{{$variantItem->name}} (S/.{{$variantItem->price}})</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    @endif
                                                    @endforeach
                                                    <input class="" name="qty" type="hidden" min="1" max="100" value="1" />
                                                    <button class="add_cart" type="submit">añadir al carrito</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @if (count($products) > 0)
                        <div class="col-xl-12">
                            <div class="mt-5" style="display: flex; justify-content: center;">
                                {{ $products->appends(request()->query())->links() }}
                            </div>
                        </div>
                        @endif

                        <!-- INICIO: Sección de productos relacionados -->
                        @if(isset($relatedProducts) && count($relatedProducts) > 0)
                        <section class="wsus__related_product mt-5 mb-5">
                            <div class="container">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <h3 class="mb-4">Productos Relacionados</h3>
                                        <div class="row related-products-slider">
                                            @foreach($relatedProducts as $product)
                                            <div class="col-xl-3 col-sm-6">
                                                <div class="wsus__product_item">
                                                    <span class="wsus__new">{{productType($product->product_type)}}</span>
                                                    @if(checkDiscount($product))
                                                        <span class="wsus__minus">-{{calculateDiscountPercent($product->price, $product->offer_price)}}%</span>
                                                    @endif
                                                    <a class="wsus__pro_link" href="{{route('product-detail', $product->slug)}}">
                                                        <img src="{{asset($product->thumb_image)}}" alt="product" class="img-fluid w-100 img_1" />
                                                        <img src="@if(isset($product->productImageGalleries[0]->image)) {{asset($product->productImageGalleries[0]->image)}} @else {{asset($product->thumb_image)}} @endif" alt="product" class="img-fluid w-100 img_2" />
                                                    </a>
                                                    <ul class="wsus__single_pro_icon">
                                                        <li><a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal" class="show_product_modal" data-id="{{$product->id}}"><i class="far fa-eye"></i></a></li>
                                                        <li><a href="#" class="add_to_wishlist" data-id="{{$product->id}}"><i class="far fa-heart"></i></a></li>
                                                    </ul>
                                                    <div class="wsus__product_details">
                                                        <a class="wsus__category" href="#">{{$product->category->name}} </a>
                                                        <p class="wsus__pro_rating">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                @if ($i <= $product->ratings_avg_review)
                                                                <i class="fas fa-star"></i>
                                                                @else
                                                                <i class="far fa-star"></i>
                                                                @endif
                                                            @endfor
                                                            <span>({{$product->reviews_count}} reseña)</span>
                                                        </p>
                                                        <a class="wsus__pro_name" href="{{route('product-detail', $product->slug)}}">{{limitText($product->name, 53)}}</a>
                                                        @if(checkDiscount($product))
                                                            <p class="wsus__price">S/.{{$product->offer_price}} <del>S/.{{$product->price}}</del></p>
                                                        @else
                                                            <p class="wsus__price">S/.{{$product->price}}</p>
                                                        @endif
                                                        <form class="shopping-cart-form">
                                                            <input type="hidden" name="product_id" value="{{$product->id}}">
                                                            @foreach ($product->variants as $variant)
                                                            @if ($variant->status != 0)
                                                                <select class="d-none" name="variants_items[]">
                                                                    @foreach ($variant->productVariantItems as $variantItem)
                                                                        @if ($variantItem->status != 0)
                                                                            <option value="{{$variantItem->id}}" {{$variantItem->is_default == 1 ? 'selected' : ''}}>{{$variantItem->name}} (S/.{{$variantItem->price}})</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            @endif
                                                            @endforeach
                                                            <input class="" name="qty" type="hidden" min="1" max="100" value="1" />
                                                            <button class="add_cart" type="submit">añadir al carrito</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        @endif
                        <!-- FIN: Sección de productos relacionados -->

                        <!-- @if(isset($productpage_banner_section->banner_two) && $productpage_banner_section->banner_two->status == 1)
                            <div class="col-xl-12">
                                <div class="wsus__sidebar_banner mt_35 mb_25">
                                    <a href="{{ $productpage_banner_section->banner_two->banner_url }}">
                                        <img src="{{ asset($productpage_banner_section->banner_two->banner_image) }}" alt="banner" class="img-fluid w-100">
                                    </a>
                                </div>
                            </div>
                        @endif -->
                        
                    </div>
                </div>
            </div>
        </div>
        
        <div class="container-fluid" style="margin-top: 30px; margin-bottom: 0;">
    <div class="row justify-content-center">
        <div class="col-12 col-md-11 col-lg-10" style="padding: 0;">
            @if ($productpage_banner_section->banner_one->status == 1)
                <a href="{{ $productpage_banner_section->banner_one->banner_url }}">
                    <img
                        src="{{ asset($productpage_banner_section->banner_one->banner_image) }}"
                        alt=""
                        class="img-fluid"
                        style="width: 100%; height: auto; display: block;"
                    >
                </a>
            @endif
        </div>
    </div>
</div>



    </section>
    
    <!-- Product Modal -->
    <!--============================
        PRODUCT PAGE END
    ==============================-->

@endsection

@push('scripts')
<script>
    $(document).ready(function(){
        // Slider range para filtro de precios
        @php
            if(request()->has('range') && request()->range != ''){
                $price = explode(';', request()->range);
                $from = $price[0];
                $to = $price[1];
            } else {
                $from = 0;
                $to = 3000;
            }
        @endphp

        if ($('#slider_range').length) {
        $('#slider_range').flatslider({
            min: 0, 
            max: 3000,
            step: 10,
            values: [{{$from}}, {{$to}}],
            range: true,
            einheit: 'S/.',
            onChange: function(values) {
                $('#price_range_display').text('S/. ' + values[0] + ' - S/. ' + values[1]);
            }
        });
        $('#price_range_display').text('S/. {{$from}} - S/. {{$to}}');
    }

        
        // Cuando cambia un checkbox de filtro, enviar el formulario
        $('.filter-checkbox').change(function() {
            $('#filter_form').submit();
        });
        
        // Mostrar vista de lista o grid según preferencia guardada
        @if(Session::has('product_list_style'))
            @if(Session::get('product_list_style') == 'grid')
                $('#v-pills-home-tab').addClass('active');
                $('#v-pills-profile-tab').removeClass('active');
                $('#v-pills-home').addClass('show active');
                $('#v-pills-profile').removeClass('show active');
            @else
                $('#v-pills-profile-tab').addClass('active');
                $('#v-pills-home-tab').removeClass('active');
                $('#v-pills-profile').addClass('show active');
                $('#v-pills-home').removeClass('show active');
            @endif
        @endif
        
        // Cambiar vista de lista/grid
        $('.list-view').click(function(){
            let style = 'grid';
            if($(this).attr('id') == 'v-pills-profile-tab'){
                style = 'list';
            }
            
            $.ajax({
                method: 'GET',
                url: "{{ route('change-product-list-view') }}",
                data: {style: style},
                success: function(data){
                    console.log(data);
                }
            })
        });
        
        
    });
</script>
@endpush
