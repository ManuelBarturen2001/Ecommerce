@extends('frontend.layouts.master')

@section('title')
{{$settings->site_name}} || Seguimiento de pedido
@endsection

@section('content')
    <!--============================
        PANORAMA DE NAVEGACIÓN INICIO
    ==============================-->
    <section id="wsus__breadcrumb">
        <div class="wsus_breadcrumb_overlay">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-12">
                        <div class="breadcrumb__content">
                            <h4>Seguimiento de Pedido</h4>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fas fa-home"></i> Inicio</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Seguimiento de Pedido</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
        PANORAMA DE NAVEGACIÓN FIN
    ==============================-->


    <!--============================
        SEGUIMIENTO DE PEDIDO INICIO
    ==============================-->
    <section id="wsus__login_register">
        <div class="container">
            <div class="wsus__track_area">
                <div class="row">
                    <div class="col-xl-5 col-md-10 col-lg-8 m-auto">

                        <form class="tack_form" action="{{route('product-traking.index')}}" method="GET">

                            <h4 class="text-center">seguimiento de pedido</h4>
                            <p class="text-center">realiza el seguimiento del estado de tu pedido</p>
                            <div class="wsus__track_input">
                                <label class="d-block mb-2">ID de factura*</label>
                                <input type="text" placeholder="H25-21578455" name="tracker" value="{{@$order->invocie_id}}">
                            </div>
                            <button type="submit" class="common_btn">seguimiento</button>
                        </form>
                    </div>
                </div>
                @if (isset($order))
                <div class="row">
                    <div class="col-xl-12">
                        <div class="wsus__track_header">
                            <div class="wsus__track_header_text">
                                <div class="row">
                                    <div class="col-xl-3 col-sm-6 col-lg-3">
                                        <div class="wsus__track_header_single">
                                            <h5>Fecha de pedido</h5>
                                            <p>{{date('d M Y', strtotime(@$order->created_at))}}</p>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-sm-6 col-lg-3">
                                        <div class="wsus__track_header_single">
                                            <h5>Comprado por:</h5>
                                            <p>{{@$order->user->name}}</p>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-sm-6 col-lg-3">
                                        <div class="wsus__track_header_single">
                                            <h5>Estado:</h5>
                                            <p>{{@$order->order_status}}</p>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-sm-6 col-lg-3">
                                        <div class="wsus__track_header_single border_none">
                                            <h5>Seguimiento:</h5>
                                            <p>{{@$order->invocie_id}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-12">
                        <ul class="progtrckr" data-progtrckr-steps="4">

                            <li class="progtrckr_done icon_one check_mark">Pendiente</li>

                            @if (@$order->order_status == 'canceled')
                                <li class="icon_four red_mark">Pedido Cancelado</li>
                            @else
                            <li class="progtrckr_done icon_two
                            @if (@$order->order_status == 'processed_and_ready_to_ship' ||
                                @$order->order_status == 'dropped_off' ||
                                @$order->order_status == 'shipped' ||
                                @$order->order_status == 'out_for_delivery' ||
                                @$order->order_status == 'delivered')
                            check_mark
                            @endif">Pedido en proceso</li>
                            <li class="icon_three
                            @if (
                                @$order->order_status == 'out_for_delivery' ||
                                @$order->order_status == 'delivered')
                            check_mark
                            @endif
                            ">En camino</li>
                            <li class="icon_four
                            @if (
                                @$order->order_status == 'delivered')
                            check_mark
                            @endif
                            ">Entregado</li>
                            @endif

                        </ul>
                    </div>
                    <div class="col-xl-12">
                        <a href="{{url('/')}}" class="common_btn"><i class="fas fa-chevron-left"></i> volver al inicio</a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
    <!--============================
        SEGUIMIENTO DE PEDIDO FIN
    ==============================-->
@endsection
