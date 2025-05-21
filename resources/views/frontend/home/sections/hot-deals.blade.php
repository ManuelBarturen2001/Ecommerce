<section id="wsus__hot_deals" class="wsus__hot_deals_2">
    <div class="container">

        <div class="wsus__hot_large_item">
            <div class="row">
                <div class="col-xl-12">
                    <div class="wsus__section_header justify-content-start">
                        <div class="monthly_top_filter2 mb-1">
                            <button class="active auto_click" data-filter=".new_arrival" data-type="new_arrival">Nuevo en llegada</button>
                            <button data-filter=".featured_product" data-type="featured_product">Destacado</button>
                            <button data-filter=".top_product" data-type="top_product">Producto Top</button>
                            <button data-filter=".best_product" data-type="best_product">Mejor Producto</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row grid2">
                @foreach ($typeBaseProducts as $key => $products)
                    @foreach ($products as $product)
                        <x-product-card :product="$product" :key="$key" />
                    @endforeach
                @endforeach
            </div>
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <a href="{{ route('products.by.type', ['type' => 'new_arrival']) }}" id="view-more-btn" class="common_btn view-more-btn">Ver más</a>
                </div>
            </div>
        </div>

        <section id="wsus__single_banner" class="home_2_single_banner" style="margin-top: 20px;">
            <div class="container">
                <div class="row">
                    <div class="col-xl-6 col-lg-6">
                        @if ($homepage_secion_banner_three->banner_one->status == 1)
                            <div class="wsus__single_banner_content banner_primary">
                                <a href="{{ $homepage_secion_banner_three->banner_one->banner_url }}">
                                    <img class="img-fluid"
                                        src="{{ asset($homepage_secion_banner_three->banner_one->banner_image) }}"
                                        alt="">
                                </a>
                            </div>
                        @endif
                    </div>
                    <div class="col-xl-6 col-lg-6">
                        <div class="row">
                            <div class="col-12">
                                @if ($homepage_secion_banner_three->banner_two->status == 1)
                                    <div class="wsus__single_banner_content banner_secondary_top">
                                        <a href="{{ $homepage_secion_banner_three->banner_two->banner_url }}">
                                            <img class="img-fluid"
                                                src="{{ asset($homepage_secion_banner_three->banner_two->banner_image) }}"
                                                alt="">
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <div class="col-12 mt-lg-4">
                                <div class="wsus__single_banner_content banner_secondary_bottom">
                                    @if ($homepage_secion_banner_three->banner_three->status == 1)
                                        <a href="{{ $homepage_secion_banner_three->banner_three->banner_url }}">
                                            <img class="img-fluid"
                                                src="{{ asset($homepage_secion_banner_three->banner_three->banner_image) }}"
                                                alt="">
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
</section>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterButtons = document.querySelectorAll('.monthly_top_filter2 button');
        const viewMoreBtn = document.getElementById('view-more-btn');
        
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                const productType = this.getAttribute('data-type');
                viewMoreBtn.href = `/products/tipo/${productType}`;
            });
        });
    });
</script>