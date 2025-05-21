@extends('frontend.layouts.master')

@section('title')
{{$settings->site_name}} || Pago
@endsection

@section('content')

    <!--============================
        MIGAS DE PAN INICIO
    ==============================-->
    <section id="wsus__breadcrumb">
        <div class="wsus_breadcrumb_overlay">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-12">
                        <div class="breadcrumb__content">
                            <h4>Vendedores</h4>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fas fa-home"></i> Inicio</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Vendedores</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
        MIGAS DE PAN FIN
    ==============================-->


    <!--============================
      VENDEDORES INICIO
    ==============================-->
    <section id="wsus__product_page" class="wsus__vendors">
        <div class="container">
            <div class="row">
                <div class="">
                    <div class="row">
                        @foreach ($vendors as $vendor)
                        <div class="col-xl-6 col-md-6">
                            <div class="wsus__vendor_single">
                                <img src="{{asset($vendor->banner)}}" alt="vendedor" class="img-fluid w-100">
                                <div class="wsus__vendor_text">
                                    <div class="wsus__vendor_text_center">
                                        <h4>{{$vendor->shop_name}}</h4>

                                        <a href="javascript:;"><i class="far fa-phone-alt"></i>
                                            {{$vendor->phone}}</a>
                                        <a href="javascript:;"><i class="fal fa-envelope"></i>
                                            {{$vendor->email}}</a>
                                        <a href="{{route('vendor.products', $vendor->id)}}" class="common_btn">visitar tienda</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                    </div>
                </div>
                <div class="col-xl-12">
                    <section id="pagination">
                        <div class="mt-5">
                            @if ($vendors->hasPages())
                                {{$vendors->links()}}
                            @endif
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </section>
    <!--============================
       VENDEDORES FIN
    ==============================-->
@endsection
