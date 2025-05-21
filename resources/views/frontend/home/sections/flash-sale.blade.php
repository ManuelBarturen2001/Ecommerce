<section id="wsus__flash_sell" class="wsus__flash_sell_2">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="offer_time" style="background: url({{ asset('frontend/images/banerflash.png') }})">
                    <div class="wsus__flash_coundown">
                        <span class="end_text">Venta Flash</span>
                        <div class="simply-countdown simply-countdown-one"></div>
                        <a class="common_btn" href="{{ route('flash-sale') }}">ver más <i class="fas fa-caret-right"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row flash_sell_slider">
            @php
                $products = \App\Models\Product::withAvg('reviews', 'rating')->withCount('reviews')
                    ->with(['variants', 'category', 'productImageGalleries'])
                    ->whereIn('id', $flashSaleItems)
                    ->where('status', 1)
                    ->where('is_approved', 1)
                    ->get();
            @endphp

            @foreach ($products as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </div>
</section>

@push('scripts')
@php
    $endDateWithTime = \Carbon\Carbon::parse($flashSaleDate->end_date)->setTime(23, 59, 59);
@endphp

<script>
    $(document).ready(function(){
        simplyCountdown('.simply-countdown-one', {
            year: {{ $endDateWithTime->format('Y') }},
            month: {{ $endDateWithTime->format('m') }},
            day: {{ $endDateWithTime->format('d') }},
            hours: {{ $endDateWithTime->format('H') }},
            minutes: {{ $endDateWithTime->format('i') }},
            seconds: {{ $endDateWithTime->format('s') }},
        });
    });
</script>

@endpush
