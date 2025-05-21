@extends('frontend.layouts.master')

@section('title')
{{$settings->site_name}} || Flash Sale
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
                            <h4>Flash Sale</h4>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fas fa-home"></i> Inicio</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Flash Sale</li>
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
            DAILY DEALS DETAILS START
        ==============================-->
    <section id="wsus__daily_deals">
        <div class="container">
            <div class="wsus__offer_details_area">
                <div class="row">

                </div>

                <div class="row">
                    <div class="col-xl-12">
                        <div class="wsus__section_header rounded-0">
                            <h3>flash sale</h3>
                            <div class="wsus__offer_countdown">
                                <span class="end_text">Termina en :</span>
                                <div class="simply-countdown simply-countdown-one"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    @php
                        $products = \App\Models\Product::withAvg('reviews', 'rating')->withCount('reviews')
                    ->with(['variants', 'category', 'productImageGalleries'])
                        ->whereIn('id', $flashSaleItems)->get();
                    @endphp
                    @foreach ($products as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
                {{-- <div class="mt-5">
                    @if ($flashSaleItems->hasPages())
                        {{$flashSaleItems->links()}}
                    @endif
                </div> --}}
            </div>
        </div>
    </section>
    <!--============================
            DAILY DEALS DETAILS END
        ==============================-->

@endsection

@php
    $endDateWithTime = \Carbon\Carbon::parse($flashSaleDate->end_date)->setTime(23, 59, 59);
@endphp

@push('scripts')
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
