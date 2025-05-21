@php
    $categoryProductSliderSectionThree = json_decode($categoryProductSliderSectionThree->value, true);
@endphp

                <section class="section-dual-columns brand_slider_2" id="wsus__brand_sleder">
                    <div class="container">
                        <div class="row">
                            @foreach ($categoryProductSliderSectionThree as $sliderSectionThree)
                            @php
                                $lastKey = [];
                                foreach ($sliderSectionThree as $key => $category) {
                                    if ($category === null) {
                                        break;
                                    }
                                    $lastKey = [$key => $category];
                                }
                                if (array_keys($lastKey)[0] === 'category') {
                                    $category = \App\Models\Category::find($lastKey['category']);
                                    $products = \App\Models\Product::withAvg('reviews', 'rating')->withCount('reviews')
                                    ->where('category_id', $category->id)
                                        ->orderBy('id', 'DESC')
                                        ->take(3)
                                        ->get()
                                        ->where('status', 1);
                                } elseif (array_keys($lastKey)[0] === 'sub_category') {
                                    $category = \App\Models\SubCategory::find($lastKey['sub_category']);
                                    $products = \App\Models\Product::withAvg('reviews', 'rating')->withCount('reviews')
                                    ->where('sub_category_id', $category->id)
                                        ->orderBy('id', 'DESC')
                                        ->take(3)
                                        ->get()
                                        ->where('status', 1);
                                } else {
                                    $category = \App\Models\ChildCategory::find($lastKey['child_category']);
                                    $products = \App\Models\Product::withAvg('reviews', 'rating')->withCount('reviews')
                                    ->where('child_category_id', $category->id)
                                        ->orderBy('id', 'DESC')
                                        ->take(3)
                                        ->get()
                                        ->where('status', 1);
                                }
                                @endphp
                                @php
                                    $categoryLink = '#'; // fallback

                                    if (array_keys($lastKey)[0] === 'category') {
                                        $categoryLink = route('products.by.category', $category->slug);
                                    } elseif (array_keys($lastKey)[0] === 'sub_category') {
                                        $categoryLink = route('products.by.subcategory', $category->slug);
                                    } else {
                                        $categoryLink = route('products.by.childcategory', $category->slug);
                                    }
                                @endphp

                                <div class="col-xl-6 col-md-6">
                                    <div class="dual-box-wrapper">
                                        <div class="dual-box-header">
                                            <h6>{{ $category->name }}</h6>
                                            <a href="{{ $categoryLink }}">Ver todo</a> 
                                        </div>
                                        <div class="dual-products-grid">
                                            @foreach ($products as $item)
                                                <a href="{{ route('product-detail', $item->slug) }}" class="dual-product-card">
                                                    <div class="dual-product-image">
                                                        <img src="{{ asset($item->thumb_image) }}" alt="{{ $item->name }}">
                                                    </div>
                                                    <div class="dual-product-body">
                                                        <h6 class="dual-product-title">{{ limitText($item->name, 40) }}</h6>
                                                        <div class="dual-product-rating">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                <i class="{{ $i <= $item->reviews_avg_rating ? 'fas' : 'far' }} fa-star"></i>
                                                            @endfor
                                                            <span>({{ $item->reviews_count }})</span>
                                                        </div>
                                                        <div class="dual-product-price">
                                                            @if (checkDiscount($item))
                                                                {{ $settings->currency_icon }}{{ $item->offer_price }}
                                                                <del>{{ $settings->currency_icon }}{{ $item->price }}</del>
                                                            @else
                                                                {{ $settings->currency_icon }}{{ $item->price }}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
<style>
.section-dual-columns {
    background-color: rgb(248, 249, 250);
    margin-top: 40px;
    margin-bottom: 10px;
}

.dual-box-wrapper {
    /* background: #fff; */
    border-radius: 14px;
    padding: 20px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
    margin-bottom: 10px;
}

.dual-box-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.dual-box-header h6 {
    font-size: 18px;
    font-weight: 600;
    color: #222;
    margin: 0;
    transition: color 0.3s ease;
}

.dual-box-header h3:hover {
    color: var(--general);
}

.dual-box-header a {
    color: var(--general);
    font-weight: 600;
    text-decoration: none;
    font-size: 16px;
    transition: all 0.3s ease;
}

.dual-box-header a:hover {
    text-decoration: underline;
}

.dual-products-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
}

.dual-product-card {
    flex: 0 0 calc(33.333% - 10px);
    /* background: #fafafa; */
    border: 1px solid #eee;
    border-radius: 10px;
    overflow: hidden;
    transition: all 0.3s ease;
    text-decoration: none;
}

.dual-product-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    transform: translateY(-4px);
}

.dual-product-image img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    display: block;
}

.dual-product-body {
    padding: 10px;
}

.dual-product-title {
    font-size: 0.95rem;
    font-weight: 500;
    margin-bottom: 6px;
    color: #333;
    transition: color 0.3s ease;
}

.dual-product-card:hover .dual-product-title {
    color: var(--general);
}

.dual-product-rating {
    font-size: 14px;
    color: #f7c948;
    margin-bottom: 5px;
}

.dual-product-rating span {
    color: #777;
    margin-left: 4px;
    font-size: 13px;
}

.dual-product-price {
    font-size: 18px;
    font-weight: 600;
    margin-top: 5px;
    color: #353535;
}

.dual-product-price :hover {
    font-size: 18px;
    font-weight: 600;
    margin-top: 5px;
    color: #353535;
}

.dual-product-price del {
    font-size: 14px;
    color: #999;
    margin-left: 6px;
}

/* RESPONSIVE */
@media (max-width: 991px) {
    .dual-product-card {
        flex: 0 0 calc(50% - 10px);
    }
}

@media (max-width: 575px) {
    .dual-product-card {
        flex: 0 0 100%;
    }

    .dual-box-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 6px;
    }
}

</style>