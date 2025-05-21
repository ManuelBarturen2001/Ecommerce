@php
    $address = json_decode($order->order_address);
    $shipping = json_decode($order->shpping_method);
    $coupon = json_decode($order->coupon);
@endphp

@extends('frontend.dashboard.layouts.master')

@section('title')
{{$settings->site_name}} || Detalle del Pedido
@endsection

@section('content')
<!-- Botón de menú móvil -->
<button class="mobile-menu-toggle" id="mobileMenuToggle">
    <i class="fas fa-bars"></i>
</button>
<div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>

<!--============================
        BREADCRUMB START
    ==============================-->
    
    <section id="wsus__breadcrumb">
        <div class="wsus_breadcrumb_overlay">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-12">
                        <div class="breadcrumb__content">
                            <h4>Detalle del Pedido</h4>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fas fa-home"></i> Inicio</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><a href="{{route('user.profile')}}">Mi Cuenta</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><a href="{{route('user.orders.index')}}">Mis Ordenes</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Detalle del Pedido</li>
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

<!-- Dashboard Area -->
<section class="dashboard-area">
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-lg-3 col-xl-2">
                    @include('frontend.dashboard.layouts.sidebar')
                </div>
                 <!-- Main Content -->
                <div class="col-lg-9 col-xl-10">
                  <div class="dashboard-main-content">
                    <div class="dashboard-card">
                      <div class="dashboard-card-header">
                          <h4>Detalle de la Orden</h4>
                      </div>
                      <div class="orders-container">
                        <!--============================
                            INVOICE PAGE START
                        ==============================-->
                            <section id="invoice-details" class="invoice-print">
                                <div class="order-detail-wrapper">
                                    <div class="order-header">
                                        <div class="order-status-badge 
                                            {{ strtolower(config('order_status.order_status_admin')[$order->order_status]['status']) }}">
                                            {{ config('order_status.order_status_admin')[$order->order_status]['status'] }}
                                        </div>
                                        <div class="order-id">
                                            <h3>Pedido #{{ $order->invocie_id }}</h3>
                                        </div>
                                    </div>
                                
                                    <div class="info-cards-container">
                                        <div class="info-card billing-info">
                                            <div class="card-header">
                                                <i class="fas fa-file-invoice"></i>
                                                <h5>Información de facturación</h5>
                                            </div>
                                            <div class="card-content">
                                                <h6>{{ $address->name }}</h6>
                                                <p><i class="fas fa-envelope"></i> {{ $address->email }}</p>
                                                <p><i class="fas fa-phone-alt"></i> {{ $address->phone }}</p>
                                                <p><i class="fas fa-map-marker-alt"></i> {{ $address->address }}</p>
                                            </div>
                                        </div>
                                        
                                        <div class="info-card shipping-info">
                                            <div class="card-header">
                                                <i class="fas fa-shipping-fast"></i>
                                                <h5>Información de envío</h5>
                                            </div>
                                            <div class="card-content">
                                                <h6>{{ $address->name }}</h6>
                                                <p><i class="fas fa-envelope"></i> {{ $address->email }}</p>
                                                <p><i class="fas fa-phone-alt"></i> {{ $address->phone }}</p>
                                                <p><i class="fas fa-map-marker-alt"></i> {{ $address->address }}</p>
                                            </div>
                                        </div>
                                        
                                        <div class="info-card payment-info">
                                            <div class="card-header">
                                                <i class="fas fa-credit-card"></i>
                                                <h5>Información de pago</h5>
                                            </div>
                                            <div class="card-content">
                                                <p><span>Método:</span> {{ $order->payment_method }}</p>
                                                <p><span>Estado:</span> 
                                                    <span class="payment-status {{ strtolower($order->payment_status) }}">
                                                        {{ $order->payment_status }}
                                                    </span>
                                                </p>
                                                <p><span>ID Transacción:</span> {{ $order->transaction->transaction_id }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="order-products">
                                        <h4>Productos del pedido</h4>
                                        <div class="table-responsive">
                                            <table class="table order-products-table">
                                                <thead>
                                                    <tr>
                                                        <th>Producto</th>
                                                        <th>Vendedor</th>
                                                        <th>Precio unitario</th>
                                                        <th>Cantidad</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($order->orderProducts as $product)
                                                        @php
                                                            $variants = json_decode($product->variants);
                                                        @endphp
                                                        <tr>
                                                            <td class="product-info">
                                                                <div class="product-name">{{ $product->product_name }}</div>
                                                                @if($variants)
                                                                <div class="product-variants">
                                                                    @foreach ($variants as $key => $item)
                                                                        <span class="variant-item">
                                                                            <strong>{{ $key }}:</strong> {{ $item->name }}
                                                                            <span class="variant-price">({{ $settings->currency_icon }}{{ $item->price }})</span>
                                                                        </span>
                                                                    @endforeach
                                                                </div>
                                                                @endif
                                                            </td>
                                                            <td>{{ $product->vendor->shop_name }}</td>
                                                            <td>{{ $settings->currency_icon }}{{ $product->unit_price }}</td>
                                                            <td>{{ $product->qty }}</td>
                                                            <td>{{ $settings->currency_icon }}{{ $product->unit_price * $product->qty }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    
                                    <div class="order-summary">
                                        <div class="summary-lines">
                                            <div class="summary-line">
                                                <span>Subtotal:</span>
                                                <span>{{ @$settings->currency_icon }}{{ @$order->sub_total }}</span>
                                            </div>
                                            <div class="summary-line">
                                                <span>Cargo de Envío (+):</span>
                                                <span>{{ @$settings->currency_icon }}{{ @$shipping->cost }}</span>
                                            </div>
                                            <div class="summary-line">
                                                <span>Descuento de Cupón (-):</span>
                                                <span>{{ @$settings->currency_icon }}{{ @$coupon->discount ? $coupon->discount : 0 }}</span>
                                            </div>
                                            <div class="summary-line total">
                                                <span>Monto Total:</span>
                                                <span>{{ @$settings->currency_icon }}{{ @$order->amount }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="order-actions">
                                        <button class="btn print_invoice"><i class="fas fa-print"></i> Imprimir factura</button>
                                    </div>
                                </div>
                            </section>
                            <!--============================
                            INVOICE PAGE END
                        ==============================-->
                            
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
          // Mobile menu handling (mantener funcionalidad existente)
          const mobileMenuToggle = document.getElementById('mobileMenuToggle');
          const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
          
          if (mobileMenuToggle) {
              mobileMenuToggle.addEventListener('click', function() {
                  const sidebar = document.querySelector('.user-sidebar');
                  if (sidebar) {
                      sidebar.classList.toggle('active');
                      mobileMenuOverlay.classList.toggle('active');
                      document.body.classList.toggle('menu-open');
                  }
              });
          }

          if (mobileMenuOverlay) {
              mobileMenuOverlay.addEventListener('click', function() {
                  const sidebar = document.querySelector('.user-sidebar');
                  if (sidebar) {
                      sidebar.classList.remove('active');
                      this.classList.remove('active');
                      document.body.classList.remove('menu-open');
                  }
              });
          }
          const closeSidebarBtn = document.getElementById('closeSidebarBtn');

        if (closeSidebarBtn) {
            closeSidebarBtn.addEventListener('click', function() {
                const sidebar = document.querySelector('.user-sidebar');
                const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');

                if (sidebar && mobileMenuOverlay) {
                    sidebar.classList.remove('active');
                    mobileMenuOverlay.classList.remove('active');
                    document.body.classList.remove('menu-open');
                }
            });
        }


      });
    </script>
    <script>
        $('.print_invoice').on('click', function() {
            let printBody = $('.invoice-print');
            let originalContents = $('body').html();

            $('body').html(printBody.html());

            window.print();

            $('body').html(originalContents);

        })
    </script>
@endpush
