@extends('frontend.layouts.master')
@section('hide_scroll_cart', true)
@section('title')
{{ $settings->site_name }} || Pago Exitoso
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
                        <h4>Pago Exitoso</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home"></i> Inicio</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('cart-details') }}">Carrito</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Pago Exitoso</li>
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
    PAYMENT SUCCESS START
==============================-->
<section id="wsus__payment_success" class="py-5">
    <div class="container">
        <div class="wsus__payment_success_wrapper">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                <!-- Success Header -->
                <div class="wsus__success_header text-center mb-4">
                    <div class="wsus__success_icon mb-3">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h2>¡Gracias por tu compra!</h2>
                    <p class="lead">Tu pedido ha sido procesado correctamente</p>
                    <div class="wsus__order_number mt-3">
                        <h5>Número de pedido: <span>{{ $order->invocie_id }}</span></h5>
                    </div>
                </div>

                <!-- Order Information -->
                <div class="wsus__order_info mb-4">
                    <div class="wsus__order_info_header d-flex justify-content-between align-items-center">
                        <h4>Resumen del pedido</h4>
                        <span class="wsus__order_date">{{ $order->created_at->format('d/m/Y') }}</span>
                    </div>

                    <div class="wsus__order_products">
                        @foreach ($order->orderProducts as $item)
                        <div class="wsus__order_product_item">
                            <div class="d-flex align-items-center">
                                <div class="wsus__order_product_img me-3">
                                    <img src="{{ asset($item->product->thumb_image ?? 'uploads/default.png') }}" alt="{{ $item->product_name }}">
                                </div>
                                <div class="wsus__order_product_info">
                                    <h5>{{ $item->product_name }}</h5>
                                    @if ($item->variants)
                                    <p class="wsus__product_variants">
                                        @php
                                            $variants = json_decode($item->variants, true);
                                        @endphp
                                        @foreach ($variants as $key => $variant)
                                            {{ $key }}: {{ is_array($variant) ? implode(', ', $variant) : $variant }}@if (!$loop->last), @endif
                                        @endforeach
                                    </p>
                                    @endif
                                    <div class="wsus__order_product_meta d-flex align-items-center">
                                        <span class="wsus__product_qty">Cantidad: {{ $item->qty }}</span>
                                        <span class="wsus__product_price ms-auto">{{ $order->currency_icon }}{{ $item->unit_price * $item->qty }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="wsus__order_summary">
                        <ul>
                            <li>Subtotal: <span>{{ $order->currency_icon }}{{ $order->sub_total }}</span></li>
                            @php
                                $shippingMethod = json_decode($order->shpping_method, true);
                                $shippingCost = isset($shippingMethod['cost']) ? $shippingMethod['cost'] : 0;
                            @endphp
                            <li>Envío: <span>{{ $order->currency_icon }}{{ $shippingCost }}</span></li>
                            <li>Descuento: <span>-{{ $order->currency_icon }}{{ $order->coupon != 'null' ? json_decode($order->coupon, true)['discount'] ?? 0 : 0 }}</span></li>
                            <li class="wsus__total">Total pagado: <span>{{ $order->currency_icon }}{{ $order->amount }}</span></li>
                        </ul>
                    </div>
                </div>

                <!-- Customer & Shipping Information -->
                <div class="row">
                    <!-- Customer Information -->
                    <div class="col-md-6 mb-4">
                        <div class="wsus__customer_info">
                            <h4><i class="fas fa-user-circle me-2"></i> Información del cliente</h4>
                            @php
                                $address = json_decode($order->order_address, true);
                                // Obtener información del cliente desde el modelo User
                                $user = App\Models\User::find($order->user_id);
                            @endphp
                            <div class="wsus__customer_details mt-3">
                                <p><span>Nombre:</span> {{ $user ? $user->name : 'Cliente' }}</p>
                                <p><span>Email:</span> {{ $user ? $user->email : 'Email Cliente' }}</p>
                                <p><span>Teléfono:</span> {{ $user ? $user->phone : 'Phone Cliente' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Information -->
                    <div class="col-md-6 mb-4">
                        <div class="wsus__shipping_info">
                            <h4><i class="fas fa-box me-2"></i> Información de entrega</h4>
                            <div class="wsus__shipping_details mt-3">
                                @if (isset($address['is_store_pickup']) && $address['is_store_pickup'])
                                    @php
                                        // Obtener información de la tienda seleccionada
                                        $storePickupId = $address['store_pickup_id'] ?? null;
                                        $selectedStore = null;
                                        if ($storePickupId) {
                                            $selectedStore = App\Models\RetiroTienda::find($storePickupId);
                                        }
                                    @endphp
                                    
                                    <div class="wsus__pickup_point">
                                        <p class="pickup_label"><i class="fas fa-store me-2"></i> Recojo en tienda</p>
                                        <div class="store_info mt-2">
                                            @if($selectedStore)
                                                <p><span>Tienda:</span> {{ $selectedStore->nombre_tienda }}</p>
                                                <p><span>Dirección:</span> {{ $selectedStore->direccion }}, {{ $selectedStore->ciudad }}</p>
                                                <p><span>Teléfono:</span> {{ $selectedStore->telefono }}</p>
                                                <p><span>Horario:</span> {{ $selectedStore->horario_apertura }} - {{ $selectedStore->horario_cierre }}</p>
                                                @if($selectedStore->instrucciones_retiro)
                                                    <p><span class="me-5">Instrucciones:</span> {{ $selectedStore->instrucciones_retiro }}</p>
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
                                                <p><span>Nombre:</span> {{ $settings->site_name }}</p>
                                                <p><span>Dirección:</span> {{ $settings->contact_address }}</p>
                                                <p><span>Teléfono:</span> {{ $settings->contact_phone }}</p>
                                                <p class="text-muted">*Recuerda que tienes 5 días para retirar tu pedido*</p>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    @php
                                        $departamento = null;
                                        $provincia = null;
                                        $distrito = null;
                                        
                                        if (isset($address['dep_id'])) {
                                            $departamento = \App\Models\Departamento::find($address['dep_id']);
                                        }
                                        
                                        if (isset($address['prov_id'])) {
                                            $provincia = \App\Models\Provincia::find($address['prov_id']);
                                        }
                                        
                                        if (isset($address['dist_id'])) {
                                            $distrito = \App\Models\Distrito::find($address['dist_id']);
                                        }
                                    @endphp
                                    
                                    <p><span>Dirección:</span> {{ $address['address'] ?? '' }}</p>
                                    <p><span>Departamento:</span> {{ $departamento ? $departamento->nombre : 'No disponible' }}</p>
                                    <p><span>Provincia:</span> {{ $provincia ? $provincia->nombre : 'No disponible' }}</p>
                                    <p><span>Distrito:</span> {{ $distrito ? $distrito->nombre : 'No disponible' }}</p>
                                    <p><span>Código Postal:</span> {{ $address['zip'] ?? '' }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Status -->
                <div class="wsus__order_status mb-4">
                    <h4 class="mb-3">Estado del pedido</h4>
                    <div class="wsus__order_status_tracker">
                        <div class="wsus__status_step active">
                            <div class="wsus__status_number">1</div>
                            <div class="wsus__status_text">Pedido recibido</div>
                        </div>
                        <div class="wsus__status_line"></div>
                        <div class="wsus__status_step">
                            <div class="wsus__status_number">2</div>
                            <div class="wsus__status_text">En preparación</div>
                        </div>
                        <div class="wsus__status_line"></div>
                        @if (isset($address['is_store_pickup']) && $address['is_store_pickup'])
                            <div class="wsus__status_step">
                                <div class="wsus__status_number">3</div>
                                <div class="wsus__status_text">Listo para recoger</div>
                            </div>
                            <div class="wsus__status_line"></div>
                            <div class="wsus__status_step">
                                <div class="wsus__status_number">4</div>
                                <div class="wsus__status_text">Entregado</div>
                            </div>
                        @else
                            <div class="wsus__status_step">
                                <div class="wsus__status_number">3</div>
                                <div class="wsus__status_text">En camino</div>
                            </div>
                            <div class="wsus__status_line"></div>
                            <div class="wsus__status_step">
                                <div class="wsus__status_number">4</div>
                                <div class="wsus__status_text">Entregado</div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="wsus__success_buttons text-center">
                    <a href="{{ route('product-traking.index') }}" class="wsus__tracking_btn"><i class="fas fa-route me-2"></i> Seguimiento de Pedido</a>
                    <a href="{{ route('home') }}" class="wsus__continue_shopping_btn"><i class="fas fa-shopping-bag me-2"></i> Continuar Comprando</a>
                </div>

            </div>
        </div>
    </div>
</div>
</section>
<!--============================
    PAYMENT SUCCESS END
==============================-->
@endsection


<style>

    .wsus_menu_category_bar .far{
        line-height: 2.5;
    }

    .wsus_close_mini_cart .far{
        line-height: 1.8;
    }

    .wsus__scroll_btn .fa-chevron-up{
        line-height: 2;
    }
    
    .wsus__pickup_point {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    border-left: 4px solid var(--general);
    }

    .pickup_label {
        font-weight: 600;
        color: var(--general);
        margin-bottom: 10px;
    }

    .store_info p {
        margin-bottom: 5px;
    }

    .store_info p span {
        font-weight: 600;
        color: #555;
        margin-right: 5px;
    }
    /* Success Page Styles */
    #wsus__payment_success {
        background-color: #f9f9f9;
    }
    
    .wsus__payment_success_wrapper {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
        padding: 30px;
    }
    
    .wsus__success_header {
        padding-bottom: 25px;
        border-bottom: 1px solid #eee;
    }
    
    .wsus__success_icon {
        font-size: 60px;
        color: #4CAF50;
        margin-bottom: 15px;
        animation: scaleIn 0.5s ease-in-out;
    }
    
    @keyframes scaleIn {
        0% { transform: scale(0); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }
    
    .wsus__success_header h2 {
        font-size: 28px;
        font-weight: 700;
        color: #333;
        margin-bottom: 10px;
    }
    
    .wsus__success_header p.lead {
        font-size: 18px;
        color: #666;
    }
    
    .wsus__order_number {
        margin-top: 20px;
    }
    
    .wsus__order_number h5 {
        display: inline-block;
        background: #f7f7f7;
        padding: 8px 15px;
        border-radius: 5px;
        font-size: 16px;
    }
    
    .wsus__order_number h5 span {
        font-weight: 700;
        color: #d94b38;
    }
    
    /* Order Information */
    .wsus__order_info {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 30px;
    }
    
    .wsus__order_info_header {
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
        margin-bottom: 15px;
    }
    
    .wsus__order_info_header h4 {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 0;
    }
    
    .wsus__order_date {
        font-size: 14px;
        color: #777;
    }
    
    .wsus__order_product_item {
        padding: 15px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .wsus__order_product_item:last-child {
        border-bottom: none;
    }
    
    .wsus__order_product_img {
        width: 70px;
        height: 70px;
        overflow: hidden;
        border: 1px solid #eee;
        border-radius: 5px;
    }
    
    .wsus__order_product_img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .wsus__order_product_info h5 {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .wsus__product_variants {
        font-size: 13px;
        color: #666;
        margin-bottom: 8px;
    }
    
    .wsus__product_qty {
        font-size: 14px;
        color: #555;
    }
    
    .wsus__product_price {
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }
    
    .wsus__order_summary {
        margin-top: 20px;
        border-top: 1px solid #eee;
        padding-top: 15px;
    }
    
    .wsus__order_summary ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .wsus__order_summary ul li {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        font-size: 15px;
        color: #555;
    }
    
    .wsus__order_summary ul li.wsus__total {
        font-size: 18px;
        font-weight: 700;
        color: #333;
        border-top: 1px solid #eee;
        margin-top: 8px;
        padding-top: 12px;
    }
    
    /* Customer & Shipping Information */
    .wsus__customer_info,
    .wsus__shipping_info {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 8px;
        padding: 20px;
        height: 100%;
    }
    
    .wsus__customer_info h4,
    .wsus__shipping_info h4 {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }
    
    .wsus__customer_info i,
    .wsus__shipping_info i {
        color: #666;
    }
    
    .wsus__customer_details p,
    .wsus__shipping_details p {
        margin-bottom: 8px;
        font-size: 14px;
        color: #555;
    }
    
    .wsus__customer_details p span,
    .wsus__shipping_details p span {
        font-weight: 600;
        color: #333;
        display: inline-block;
        width: 80px;
    }
    
    /* Order Status */
    .wsus__order_status {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 8px;
        padding: 20px;
    }
    
    .wsus__order_status h4 {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 20px;
    }
    
    .wsus__order_status_tracker {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
    }
    
    .wsus__status_step {
        display: flex;
        flex-direction: column;
        align-items: center;
        z-index: 2;
        flex: 1;
    }
    
    .wsus__status_number {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: #e1e1e1;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        margin-bottom: 8px;
        transition: all 0.3s ease;
    }
    
    .wsus__status_step.active .wsus__status_number {
        background: #4CAF50;
    }
    
    .wsus__status_text {
        font-size: 13px;
        text-align: center;
        color: #777;
    }
    
    .wsus__status_step.active .wsus__status_text {
        color: #4CAF50;
        font-weight: 600;
    }
    
    .wsus__status_line {
        height: 3px;
        flex-grow: 1;
        background: #e1e1e1;
        position: relative;
        z-index: 1;
    }
    
    .wsus__estimated_delivery {
        text-align: center;
        margin-top: 15px;
    }
    
    .wsus__estimated_delivery p {
        font-size: 14px;
        color: #555;
    }
    
    /* Action Buttons */
    .wsus__success_buttons {
    margin-top: 30px;
    text-align: center;
    }

    .wsus__tracking_btn,
    .wsus__continue_shopping_btn {
        display: inline-block;
        padding: 12px 25px;
        border-radius: 5px;
        font-weight: 600;
        transition: all 0.3s ease;
        margin: 0 10px;
        transform: scale(1);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .wsus__tracking_btn {
        background-color: var(--general);
        color: #fff;
    }

    .wsus__tracking_btn:hover {
        background-color: var(--general);
        color: #fff;
        transform: scale(1.05);
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
    }

    .wsus__continue_shopping_btn {
        background-color: #28a745;
        color: #fff;
    }

    .wsus__continue_shopping_btn:hover {
        background-color: #218838;
        color: #fff;
        transform: scale(1.05);
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
    }
        
    /* Responsive Styles */
    @media (max-width: 768px) {
        .wsus__payment_success_wrapper {
            padding: 20px 15px;
        }
        
        .wsus__success_icon {
            font-size: 50px;
        }
        
        .wsus__success_header h2 {
            font-size: 24px;
        }
        
        .wsus__order_status_tracker {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .wsus__status_step {
            flex-direction: row;
            width: 100%;
            margin-bottom: 15px;
        }
        
        .wsus__status_number {
            margin-right: 15px;
            margin-bottom: 0;
        }
        
        .wsus__status_line {
            height: 30px;
            width: 3px;
            margin-left: 16px;
        }
        
        .wsus__action_buttons {
            display: flex;
            flex-direction: column;
        }
        
        .wsus__track_btn,
        .wsus__continue_btn {
            margin: 5px 0;
        }
    }
    
    @media (max-width: 576px) {
        .wsus__success_header h2 {
            font-size: 22px;
        }
        
        .wsus__success_header p.lead {
            font-size: 16px;
        }
        
        .wsus__order_product_item {
            flex-direction: column;
        }
        
        .wsus__order_product_img {
            margin-bottom: 10px;
        }
    }
</style>
