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
                                <a class="wsus__category" href="{{route('products.by.category', $product->category->slug)}}">{{$product->category->name}} </a>
                                <p class="wsus__pro_rating">
                                    @php
                                        $avgRating = $product->reviews_avg_rating;
                                        $fullRating = floor($avgRating);
                                        $emptyRating = 5 - $fullRating;
                                    @endphp

                                    @for ($i = 0; $i < $fullRating; $i++)
                                        <i class="fas fa-star"></i>
                                    @endfor

                                    @for ($i = 0; $i < $emptyRating; $i++)
                                        <i class="far fa-star"></i>
                                    @endfor
                                    
                                    <span>({{$product->reviews_count}})</span>
                                </p>
                                <a class="wsus__pro_name" href="{{route('product-detail', $product->slug)}}">{{limitText($product->name, 53)}}</a>
                                <p class="wsus__price">@if(checkDiscount($product)) {{$settings->currency_icon}}{{$product->offer_price}} <del>{{$settings->currency_icon}}{{$product->price}}</del> @else {{$settings->currency_icon}}{{$product->price}} @endif</p>
                                <a class="add_cart" data-id="{{$product->id}}" href="#">añadir al carrito</a>
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