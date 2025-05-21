@php
    $genderSliderSection = json_decode($genderSliderSection->value, true);
@endphp

<section class="gender-section py-1">
    <div class="container">
        <div class="row">
            @foreach ($genderSliderSection as $sliderSection)
                @php
                    $lastKey = [];

                    foreach ($sliderSection as $key => $gender) {
                        if ($gender === null) {
                            break;
                        }
                        $lastKey = [$key => $gender];
                    }

                    if (array_keys($lastKey)[0] === 'gender') {
                        $gender = \App\Models\Gender::find($lastKey['gender']);
                        $products = \App\Models\Product::withAvg('reviews', 'rating')->withCount('reviews')
                            ->where('gender_id', $gender->id)
                            ->orderBy('id', 'DESC')
                            ->take(6)
                            ->get()
                            ->where('status', 1);
                    }
                @endphp

                @if ($gender && $products->count())
                    <div class="col-12 mb-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="gender-section-title">{{ $gender->name }}</h3>
                            <a class="ver-todo-gender" href="{{ route('products.by.gender', $gender->slug ) }}" class="gender-section-link">
                                Ver todo <i class="fas fa-arrow-right"></i>
                            </a>
                            
                        </div>
                        <div class="row">
                            @foreach ($products as $item)
                                <div class="col-6 col-md-4 col-lg-2 mb-4">
                                    <a href="{{ route('product-detail', $item->slug) }}" class="gender-card">
                                        <div class="gender-card-img">
                                            <img src="{{ asset($item->thumb_image) }}" alt="{{ $item->name }}" class="img-fluid">
                                        </div>
                                        <div class="gender-card-body">
                                            <h6 class="product-name">{{ Str::limit($item->name, 40) }}</h6>
                                            <div class="rating mb-1">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <i class="{{ $i <= $item->reviews_avg_rating ? 'fas' : 'far' }} fa-star"></i>
                                                @endfor
                                                <span>({{ $item->reviews_count }})</span>
                                            </div>
                                            <div class="price">
                                                @if (checkDiscount($item))
                                                    <span class="new-price">{{ $settings->currency_icon }}{{ $item->offer_price }}</span>
                                                    <span class="old-price">{{ $settings->currency_icon }}{{ $item->price }}</span>
                                                @else
                                                    <span class="new-price">{{ $settings->currency_icon }}{{ $item->price }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</section>

