@extends('frontend.layouts.master')
@section('hide_scroll_cart', true)
@section('title')
{{$settings->site_name}} || Pago con Stripe
@endsection
@php
    use App\Models\Departamento;
    use App\Models\Provincia;
    use App\Models\Distrito;

    $address = Session::get('address');

    $departamento = null;
    $provincia = null;
    $distrito = null;

    if ($address && empty($address['is_store_pickup'])) {
        $departamento = Departamento::find($address['dep_id'] ?? null);
        $provincia = Provincia::find($address['prov_id'] ?? null);
        $distrito = Distrito::find($address['dist_id'] ?? null);
    }
@endphp
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
                            <h4>Método de Pago</h4>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fas fa-home"></i> Inicio</a></li>
                                    <li class="breadcrumb-item"><a href="{{route('cart-details')}}">Vista del Carrito</a></li>
                                    <li class="breadcrumb-item"><a href="{{route('user.checkout')}}">Entrega</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Método de Pago</li>
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
        PAYMENT PAGE START
    ==============================-->
    <section id="wsus__payment_page" class="py-5">
        <div class="container">
            <div class="wsus__payment_wrapper">
                <div class="row">
                    <!-- Payment Form Column -->
                    <div class="col-lg-8">
                        <div class="wsus__payment_content">
                            <div class="wsus__payment_header">
                                <div class="wsus__payment_logo">
                                    <img src="{{asset('frontend/images/payment-logos/stripe.png')}}" alt="Stripe" class="img-fluid" style="max-height: 50px;">
                                </div>
                                <h4>Pago seguro con Stripe</h4>
                                <p>Complete su compra de forma segura utilizando su tarjeta de crédito o débito</p>
                            </div>
                            
                            @include('frontend.pages.payment-gateway.stripe')
                            
                            <div class="wsus__payment_security mt-4">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="fas fa-shield-alt security-icon"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-1">Pago 100% Seguro</h5>
                                        <p class="mb-0">Todos los datos están protegidos con encriptación SSL de 256 bits.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary Column -->
                    <div class="col-lg-4">
                        <div class="wsus__order_summary">
                            <div class="wsus__order_summary_header">
                                <h4>Resumen del pedido</h4>
                            </div>

                            <!-- Customer Information -->
                            <div class="wsus__customer_info mb-4">
                                <h5><i class="fas fa-user-circle me-2"></i> Información del cliente</h5>
                                @php
                                    $address = Session::get('address');
                                @endphp
                                @if($address)
                                <div class="wsus__customer_details mt-3">
                                    <p><span>Nombre:</span> {{$address['name']}}</p>
                                    <p><span>Email:</span> {{$address['email']}}</p>
                                    <p><span>Teléfono:</span> {{$address['phone']}}</p>
                                </div>
                                @endif
                            </div>

                            <!-- Shipping Information -->
                        <div class="wsus__shipping_info mb-4">
                            <h5><i class="fas fa-truck me-2"></i> Información de entrega</h5>
                            @if($address)
                            <div class="wsus__shipping_details mt-3">
                                @if(isset($address['is_store_pickup']) && $address['is_store_pickup'])
                                    @php
                                        // Obtener información de la tienda seleccionada
                                        $storePickupId = $address['store_pickup_id'] ?? null;
                                        $selectedStore = null;
                                        if ($storePickupId) {
                                            $selectedStore = App\Models\RetiroTienda::find($storePickupId);
                                        }
                                    @endphp
                                    
                                    <p class="pickup_label"><i class="fas fa-store me-2"></i> Recojo en tienda</p>
                                    <div class="store_info mt-2">
                                        @if($selectedStore)
                                            <p><span>Tienda:</span> {{ $selectedStore->nombre_tienda }}</p>
                                            <p><span>Dirección:</span> {{ $selectedStore->direccion }}, {{ $selectedStore->ciudad }}</p>
                                            <p><span>Teléfono:</span> {{ $selectedStore->telefono }}</p>
                                            <p><span>Horario:</span> {{ $selectedStore->horario_apertura }} - {{ $selectedStore->horario_cierre }}</p>
                                            @if($selectedStore->instrucciones_retiro)
                                                <p><span>Instrucciones:</span> {{ $selectedStore->instrucciones_retiro }}</p>
                                            @endif
                                            <div class="alert alert-info mt-2">
                                                <i class="fas fa-info-circle me-2"></i>
                                                <strong>Importante:</strong> Tienes 5 días para retirar tu pedido desde la confirmación.
                                                @if($selectedStore->documentos_requeridos)
                                                    <br><strong>Documentos requeridos:</strong> {{ $selectedStore->documentos_requeridos }}
                                                @endif
                                            </div>
                                        @else
                                            <!-- Fallback si no se encuentra la tienda -->
                                            <p><span>Nombre:</span> {{ $address['name'] ?? $settings->site_name }}</p>
                                            <p><span>Dirección:</span> {{ $address['address'] ?? $settings->contact_address }}</p>
                                            <p><span>Teléfono:</span> {{ $address['phone'] ?? $settings->contact_phone }}</p>
                                            <p class="text-muted">*Recuerda que tienes 5 días para retirar tu pedido*</p>
                                        @endif
                                    </div>
                                @else
                                    <p class="pickup_label"><i class="fas fa-home me-2"></i> Envío a domicilio</p>
                                    <p><span>Dirección:</span> {{$address['address']}}</p>
                                    <p><span>Departamento:</span> {{ $departamento->nombre ?? 'No disponible' }}</p>
                                    <p><span>Provincia:</span> {{ $provincia->nombre ?? 'No disponible' }}</p>
                                    <p><span>Distrito:</span> {{ $distrito->nombre ?? 'No disponible' }}</p>
                                    <p><span>Código Postal:</span> {{$address['zip']}}</p>
                                @endif
                            </div>
                            @endif
                        </div>

                            <!-- Order Products - Horizontal Layout -->
                            <div class="wsus__order_products mb-4">
                                <h5 class="mb-3"><i class="fas fa-shopping-cart me-2"></i> Productos</h5>
                                
                                <div class="wsus__order_product_horizontal">
                                    @foreach(\Cart::content() as $item)
                                    <div class="wsus__order_product_item">
                                        <div class="wsus__order_product_img">
                                            <img src="{{asset($item->options->image)}}" alt="{{$item->name}}">
                                        </div>
                                        <div class="wsus__order_product_name">
                                            <h6>{{$item->name}}</h6>
                                            @if(isset($item->options->variants))
                                            <small>
                                                @foreach($item->options->variants as $key => $variant)
                                                    {{$key}}: {{$variant}}@if(!$loop->last), @endif
                                                @endforeach
                                            </small>
                                            @endif
                                        </div>
                                        <div class="wsus__order_product_qty">
                                            <span>{{$item->qty}}</span>
                                        </div>
                                        <div class="wsus__order_product_price">
                                            <span>{{$settings->currency_icon}}{{$item->price * $item->qty}}</span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Price Summary -->
                            <div class="wsus__price_summary">
                                <ul>
                                    <li>Subtotal: <span>{{$settings->currency_icon}}{{getCartTotal()}}</span></li>
                                    <li>Envío (+): <span>{{$settings->currency_icon}}{{getShppingFee()}}</span></li>
                                    <li>Descuento (-): <span>{{$settings->currency_icon}}{{getCartDiscount()}}</span></li>
                                    <li class="wsus__total">Total: <span>{{$settings->currency_icon}}{{getFinalPayableAmount()}}</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
        PAYMENT PAGE END
    ==============================-->
@endsection

@section('scripts')

<script>
    $(document).ready(function() {
        // Card number formatting
        $('#card_number').on('input', function() {
            var val = $(this).val().replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            var formattedVal = '';
            
            for(var i = 0; i < val.length; i++) {
                if(i > 0 && i % 4 == 0) {
                    formattedVal += ' ';
                }
                formattedVal += val.charAt(i);
            }
            
            $(this).val(formattedVal);
            
            // Limit to 19 characters (16 digits + 3 spaces)
            if ($(this).val().length > 19) {
                $(this).val($(this).val().slice(0, 19));
            }
        });
        
        // Expiry date formatting (MM/YY)
        $('#expiry_date').on('input', function() {
            var val = $(this).val().replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            
            if (val.length > 2) {
                $(this).val(val.slice(0, 2) + '/' + val.slice(2, 4));
            } else {
                $(this).val(val);
            }
            
            // Limit to 5 characters (MM/YY)
            if ($(this).val().length > 5) {
                $(this).val($(this).val().slice(0, 5));
            }
        });
        
        // CVV - limit to 3 or 4 digits
        $('#cvv').on('input', function() {
            var val = $(this).val().replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            $(this).val(val);
            
            if ($(this).val().length > 4) {
                $(this).val($(this).val().slice(0, 4));
            }
        });
        
        // Form submission animation
        $('#checkout-form').on('submit', function() {
            $('.wsus__payment_btn button').html('<i class="fas fa-spinner fa-spin me-2"></i> Procesando...');
            $('.wsus__payment_btn button').prop('disabled', true);
        });
    });
</script>
@endsection

<style>
    :root {
        --primary: var(--general);
        --secondary: #3498db;
        --success: #2ecc71;
        --danger: #e74c3c;
        --warning: #f39c12;
        --light: #f8f9fa;
        --dark: #343a40;
        --gray: #6c757d;
        --gray-light: #e9ecef;
        --body-bg: #f8f9fa;
    }

    /* General Styles */
    body {
        background-color: var(--body-bg);
        font-family: 'Poppins', sans-serif;
    }

    .wsus_menu_category_bar .far{
        line-height: 2.5;
    }

    .wsus_close_mini_cart .far{
        line-height: 1.8;
    }

    .wsus__scroll_btn .fa-chevron-up{
        line-height: 2;
    }

    #wsus__payment_page {
        background-color: var(--body-bg);
    }

    .wsus__payment_wrapper {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.05);
        padding: 30px;
    }

    /* Form Styling */
    .wsus__payment_content {
        background-color: #fff;
        border-radius: 10px;
        height: 100%;
    }

    .wsus__payment_header {
        text-align: center;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--gray-light);
    }

    .wsus__payment_logo {
        margin-bottom: 15px;
    }

    .wsus__payment_header h4 {
        color: var(--primary);
        font-weight: 600;
        margin-bottom: 10px;
    }

    .wsus__payment_header p {
        color: var(--gray);
    }

    .wsus__payment_form {
        padding: 20px 0;
    }

    .form-label {
        font-weight: 500;
        color: var(--dark);
        margin-bottom: 8px;
    }

    .input-group-text {
        background-color: var(--light);
        border-color: var(--gray-light);
        color: var(--primary);
    }

    .form-control {
        border-color: var(--gray-light);
        padding: 12px;
        font-size: 15px;
    }

    .form-control:focus {
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        border-color: var(--secondary);
    }

    .wsus__card_img {
        text-align: center;
    }

    .wsus__payment_btn .common_btn {
        background-color: var(--primary);
        color: #fff;
        padding: 14px 20px;
        border-radius: 6px;
        font-weight: 600;
        transition: all 0.3s;
        border: none;
        font-size: 16px;
    }

    .wsus__payment_btn .common_btn:hover {
        background-color: var(--secondary);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(52, 152, 219, 0.2);
    }

    .wsus__payment_security {
        padding: 15px;
        border-radius: 8px;
        background-color: var(--light);
        border: 1px solid var(--gray-light);
    }

    .security-icon {
        font-size: 30px;
        color: var(--success);
    }

    .wsus__payment_security h5 {
        color: var(--dark);
        font-weight: 600;
        font-size: 16px;
    }

    .wsus__payment_security p {
        color: var(--gray);
        font-size: 14px;
    }

    /* Order Summary Styling */
    .wsus__order_summary {
        background-color: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        height: 100%;
    }

    .wsus__order_summary_header {
        background-color: var(--primary);
        color: #fff;
        padding: 15px 20px;
    }

    .wsus__order_summary_header h4 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        color: var(--body-bg);
    }

    .wsus__customer_info,
    .wsus__shipping_info,
    .wsus__order_products {
        padding: 15px 20px;
        border-bottom: 1px solid var(--gray-light);
    }

    .wsus__customer_info h5,
    .wsus__shipping_info h5,
    .wsus__order_products h5 {
        font-size: 16px;
        color: var(--primary);
        font-weight: 600;
    }

    .wsus__customer_details p,
    .wsus__shipping_details p {
        margin-bottom: 5px;
        font-size: 14px;
        display: flex;
        justify-content: space-between;
    }

    .wsus__customer_details p span,
    .wsus__shipping_details p span {
        font-weight: 600;
        color: var(--dark);
    }

    .wsus__pickup_point {
        background-color: #e9f7ef;
        border-left: 3px solid var(--success);
        padding: 10px 15px;
        border-radius: 4px;
    }

    .pickup_label {
        color: var(--success);
        font-weight: 600;
        margin: 0;
    }

    /* Horizontal Order Products */
    .wsus__order_product_horizontal {
        max-height: 250px;
        overflow-y: auto;
        padding-right: 5px;
    }

    .wsus__order_product_horizontal::-webkit-scrollbar {
        width: 4px;
    }

    .wsus__order_product_horizontal::-webkit-scrollbar-track {
        background: var(--gray-light);
        border-radius: 10px;
    }

    .wsus__order_product_horizontal::-webkit-scrollbar-thumb {
        background: var(--primary);
        border-radius: 10px;
    }

    .wsus__order_product_item {
        display: flex;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid var(--gray-light);
    }

    .wsus__order_product_item:last-child {
        border-bottom: none;
    }

    .wsus__order_product_img {
        width: 50px;
        height: 50px;
        min-width: 50px;
        margin-right: 10px;
    }

    .wsus__order_product_img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 4px;
    }

    .wsus__order_product_name {
        flex-grow: 1;
        padding-right: 10px;
    }

    .wsus__order_product_name h6 {
        font-size: 14px;
        margin-bottom: 2px;
        font-weight: 600;
        color: var(--dark);
    }

    .wsus__order_product_name small {
        font-size: 12px;
        color: var(--gray);
    }

    .wsus__order_product_qty {
        margin-right: 10px;
        min-width: 30px;
        text-align: center;
    }

    .wsus__order_product_qty span {
        background-color: var(--light);
        border: 1px solid var(--gray-light);
        border-radius: 4px;
        padding: 2px 8px;
        font-size: 13px;
        font-weight: 600;
    }

    .wsus__order_product_price {
        min-width: 70px;
        text-align: right;
    }

    .wsus__order_product_price span {
        font-weight: 600;
        color: var(--dark);
        font-size: 14px;
    }

    /* Price Summary */
    .wsus__price_summary {
        padding: 15px 20px;
    }

    .wsus__price_summary ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .wsus__price_summary ul li {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        color: var(--gray);
        font-size: 14px;
    }

    .wsus__price_summary ul li.wsus__total {
        font-weight: 700;
        font-size: 18px;
        color: var(--primary);
        border-top: 1px solid var(--gray-light);
        padding-top: 12px;
        margin-top: 12px;
    }

    /* Responsive Adjustments */
    @media (max-width: 991px) {
        .wsus__payment_wrapper {
            padding: 15px;
        }
        
        .wsus__payment_content, 
        .wsus__order_summary {
            margin-bottom: 20px;
        }
    }

    @media (max-width: 767px) {
        .wsus__payment_form {
            padding: 15px 0;
        }
        
        .wsus__order_summary_header {
            padding: 10px 15px;
        }
        
        .wsus__customer_info,
        .wsus__shipping_info,
        .wsus__order_products,
        .wsus__price_summary {
            padding: 10px 15px;
        }
        
        .wsus__order_product_horizontal {
            max-height: 200px;
        }
    }

    /* Animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .wsus__payment_content,
    .wsus__order_summary {
        animation: fadeIn 0.5s ease-out;
    }
</style>