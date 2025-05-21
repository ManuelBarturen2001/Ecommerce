@php
    $popularCategories = json_decode($popularCategory->value, true);
    $productsGrouped = [];
    $viewMoreLinks = [];
@endphp
<section id="wsus__monthly_top" class="wsus__monthly_top_2">
    <div class="container">
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                @if ($homepage_secion_banner_one->banner_one->status == 1)
                <div class="wsus__monthly_top_banner">
                    <a href="{{$homepage_secion_banner_one->banner_one->banner_url}}">
                        <img class="img-fluid" src="{{asset($homepage_secion_banner_one->banner_one->banner_image)}}" alt="">
                    </a>
                </div>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="wsus__section_header for_md">
                    <h3>Categorías Populares</h3>
                    <div class="monthly_top_filter">
                        @foreach ($popularCategories as $index => $popularCategory)
                                @php
                                    $categoryName = '';
                                    if(array_key_exists('sub_category', $popularCategory)){
                                        $subCategory = \App\Models\SubCategory::find($popularCategory['sub_category']);
                                        $productsGrouped[$index] = \App\Models\Product::withAvg('reviews', 'rating')
                                            ->with(['variants', 'category', 'productImageGalleries'])
                                            ->where('sub_category_id', $subCategory->id)->orderBy('id', 'DESC')->take(12)->get()->where('status', 1);
                                        $categoryName = $subCategory->name;
                                        $viewMoreLinks[$index] = route('products.by.subcategory', $subCategory->slug);
                                    } elseif(array_key_exists('category', $popularCategory)) {
                                        $mainCategory = \App\Models\Category::find($popularCategory['category']);
                                        $productsGrouped[$index] = \App\Models\Product::withAvg('reviews', 'rating')
                                            ->with(['variants', 'category', 'productImageGalleries'])
                                            ->where('category_id', $mainCategory->id)->orderBy('id', 'DESC')->take(12)->get()->where('status', 1);
                                        $categoryName = $mainCategory->name;
                                        $viewMoreLinks[$index] = route('products.by.category', $mainCategory->slug);
                                    } elseif(array_key_exists('child_category', $popularCategory)) {
                                        $childCategory = \App\Models\ChildCategory::find($popularCategory['child_category']);
                                        $productsGrouped[$index] = \App\Models\Product::withAvg('reviews', 'rating')
                                            ->with(['variants', 'category', 'productImageGalleries'])
                                            ->where('child_category_id', $childCategory->id)->orderBy('id', 'DESC')->take(12)->get()->where('status', 1);
                                        $subCategory = \App\Models\SubCategory::find($childCategory->sub_category_id);
                                        $categoryName = $subCategory->name;
                                        $viewMoreLinks[$index] = route('products.by.childcategory', $childCategory->slug);
                                    }
                                @endphp
                                <button class="{{ $loop->first ? 'auto_click active' : '' }}" data-filter=".category-{{$index}}" data-index="{{$index}}">{{$categoryName}}</button>
                            @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="row grid">
                    @foreach ($productsGrouped as $index => $products)
                        @foreach ($products as $item)
                            <div class="col-xl-2 col-6 col-sm-6 col-md-4 col-lg-3  category-{{$index}}">
                                <a class="wsus__hot_deals__single" href="{{route('product-detail', $item->slug)}}">
                                    <div class="wsus__hot_deals__single_img">
                                        <img src="{{asset($item->thumb_image)}}" alt="bag" class="img-fluid w-100">
                                    </div>
                                    <div class="wsus__hot_deals__single_text">
                                        <h5>{!!limitText($item->name, )!!}</h5>
                                        <p class="wsus__rating">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $item->reviews_avg_rating)
                                                <i class="fas fa-star"></i>
                                                @else
                                                <i class="far fa-star"></i>
                                                @endif
                                            @endfor

                                        </p>
                                        @if (checkDiscount($item))
                                            <p class="wsus__tk">{{$settings->currency_icon}}{{$item->offer_price}} <del>{{$settings->currency_icon}}{{$item->price}}</del></p>
                                        @else
                                            <p class="wsus__tk">{{$settings->currency_icon}}{{$item->price}}</p>
                                        @endif
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    @endforeach
                </div>
                <div class="row mt-4">
                    <div class="col-12 text-center">
                        @foreach ($viewMoreLinks as $index => $link)
                            <a href="{{ $link }}" class="common_btn view-more-btn view-more-link category-link category-link-{{$index}}" style="{{ $index === 0 ? '' : 'display: none;' }}">Ver más</a>
                        @endforeach
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</section>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const buttons = document.querySelectorAll('.monthly_top_filter button');
        const viewMoreLinks = document.querySelectorAll('.category-link');

        buttons.forEach(btn => {
            btn.addEventListener('click', function () {
                const index = this.getAttribute('data-index');

                // Oculta todos los botones de "ver más"
                viewMoreLinks.forEach(link => link.style.display = 'none');

                // Muestra solo el correspondiente
                const targetLink = document.querySelector(`.category-link-${index}`);
                if (targetLink) targetLink.style.display = 'inline-block';
            });
        });
    });
</script>
