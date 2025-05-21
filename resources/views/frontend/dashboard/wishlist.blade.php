@extends('frontend.dashboard.layouts.master')

@section('title')
{{$settings->site_name}} || Mi Lista de Deseos
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
                            <h4>Mi Lista de Deseos</h4>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fas fa-home"></i> Inicio</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><a href="{{route('user.profile')}}">Mi Cuenta</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Mi Lista de Deseos</li>
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
                          <h4>Mi Lista de Deseos</h4>
                      </div>
                      <div class="orders-container">
                        <div class="wishlist-container">
                            @if(count($wishlistProducts) > 0)
                            <div class="table-responsive wishlist-table">
                                <table class="wishlist-items">
                                    <thead>
                                        <tr>
                                            <th class="wishlist-image">Imagen</th>
                                            <th class="wishlist-product">Producto</th>
                                            <th class="wishlist-quantity">Cantidad</th>
                                            <th class="wishlist-price">Precio</th>
                                            <th class="wishlist-total">Total</th>
                                            <th class="wishlist-action">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($wishlistProducts as $item)
                                        <tr class="wishlist-row product-row" data-product-id="{{$item->product->id}}" data-product-price="{{$item->product->price}}">
                                            <td class="wishlist-image">
                                                <div class="image-container">
                                                    <img src="{{asset($item->product->thumb_image)}}" alt="{{$item->product->name}}" class="img-fluid">
                                                    <a href="{{route('user.wishlist.destory', $item->id)}}" class="remove-item"><i class="far fa-times"></i></a>
                                                </div>
                                            </td>

                                            <td class="wishlist-product">
                                                <a href="{{ route('product-detail', $item->product->slug) }}" class="product-name">{{$item->product->name}}</a>
                                            </td>

                                            <td class="wishlist-quantity">
                                                <div class="quantity-control">
                                                    <button class="qty-btn decrement-btn"><i class="fas fa-minus"></i></button>
                                                    <input type="text" class="qty-input-box" value="1" min="1" max="{{$item->product->qty}}">
                                                    <button class="qty-btn increment-btn"><i class="fas fa-plus"></i></button>
                                                </div>
                                            </td>

                                            <td class="wishlist-price">
                                                <span class="price-amount">{{$settings->currency_icon}}{{$item->product->price}}</span>
                                            </td>
                                            
                                            <td class="wishlist-total product-total">
                                                <span class="total-amount">{{$settings->currency_icon}}{{$item->product->price}}</span>
                                            </td>

                                            <td class="wishlist-action">
                                                <button class="cart-btn add-to-cart-btn" data-product-id="{{$item->product->id}}">
                                                    <i class="fas fa-shopping-cart"></i> Añadir al carrito
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="empty-wishlist">
                                <div class="empty-state">
                                    <i class="fas fa-heart-broken"></i>
                                    <h4>Tu lista de deseos está vacía</h4>
                                    <p>Añade productos a tu lista de deseos para comprarlos más tarde.</p>
                                    <a href="{{route('home')}}" class="continue-shopping">Continuar comprando</a>
                                </div>
                            </div>
                            @endif
                        </div>
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
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
          // Mobile menu handling
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
        
        // Cantidad de productos
        document.querySelectorAll('.decrement-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const input = this.parentElement.querySelector('.qty-input-box');
                const currentValue = parseInt(input.value);
                if (currentValue > 1) {
                    input.value = currentValue - 1;
                    updateProductTotal(this);
                }
            });
        });
        
        document.querySelectorAll('.increment-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const input = this.parentElement.querySelector('.qty-input-box');
                const currentValue = parseInt(input.value);
                const maxValue = parseInt(input.getAttribute('max'));
                if (currentValue < maxValue) {
                    input.value = currentValue + 1;
                    updateProductTotal(this);
                }
            });
        });
        
        document.querySelectorAll('.qty-input-box').forEach(function(input) {
            input.addEventListener('change', function() {
                const currentValue = parseInt(this.value);
                const maxValue = parseInt(this.getAttribute('max'));
                
                if (isNaN(currentValue) || currentValue < 1) {
                    this.value = 1;
                } else if (currentValue > maxValue) {
                    this.value = maxValue;
                }
                
                updateProductTotal(this);
            });
        });
        
        function updateProductTotal(element) {
            const row = element.closest('.product-row');
            const price = parseFloat(row.getAttribute('data-product-price'));
            const quantity = parseInt(row.querySelector('.qty-input-box').value);
            const total = price * quantity;
            
            const currencyIcon = '{{$settings->currency_icon}}';
            row.querySelector('.total-amount').textContent = currencyIcon + total.toFixed(2);
        }
        
        // Añadir al carrito
        document.querySelectorAll('.add-to-cart-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const row = this.closest('.product-row');
                const productId = this.getAttribute('data-product-id');
                const quantity = parseInt(row.querySelector('.qty-input-box').value);
                
                addToCart(productId, quantity);

                // Espera corta antes de recargar si lo necesitas (opcional)
                setTimeout(() => {
                    location.reload();
                }, 300); // 300ms
            });
        });
        
        function addToCart(productId, qty, button) {
            $.ajax({
                url: "{{ route('user.wishlist.add-to-cart-from-wishlist') }}",
                method: "POST",
                data: {
                    product_id: productId,
                    qty: qty,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.status === 'success') {
                        toastr.success(response.message);
                        
                        // Añadir clase de éxito y quitar después de la animación
                        button.classList.remove('adding');
                        button.classList.add('added');
                        
                        setTimeout(() => {
                            button.classList.remove('added');
                        }, 1500);
                        
                        // Actualizar contador del carrito
                        $.ajax({
                            url: "{{ route('cart-count') }}",
                            method: "GET",
                            success: function(data) {
                                $('.cart-count').text(data);
                            }
                        });
                    } else {
                        button.classList.remove('adding');
                        toastr.error(response.message);
                    }
                    location.reload();
                },
                error: function(xhr, status, error) {
                    button.classList.remove('adding');
                    toastr.error('Ha ocurrido un error al añadir el producto al carrito');
                }
            });
        }
      });
    </script>
@endpush    
    <style>
        /* Estilos generales para la lista de deseos */
        .orders-container {
            padding: 0;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .wishlist-container {
            padding: 20px;
        }
        
        /* Estilos para la tabla de lista de deseos */
        .wishlist-table {
            border-radius: 6px;
            overflow: hidden;
            margin-bottom: 20px;
        }
        
        .wishlist-items {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .wishlist-items thead {
            background-color: var(--general);
            color: #fff;
        }
        
        .wishlist-items th {
            padding: 15px;
            text-align: center;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
        }
        
        .wishlist-items tbody tr {
            border-bottom: 1px solid #eee;
            transition: all 0.3s ease;
        }
        
        .wishlist-items tbody tr:hover {
            background-color: rgba(var(--center-rgb), 0.05);
        }
        
        .wishlist-items tbody tr:last-child {
            border-bottom: none;
        }
        
        .wishlist-items td {
            padding: 15px;
            text-align: center;
            vertical-align: middle;
            border: none;
        }
        
        /* Columnas específicas */
        .wishlist-image {
            width: 120px;
        }
        
        .wishlist-product {
            width: 25%;
            min-width: 200px;
        }
        
        .wishlist-quantity {
            width: 150px;
        }
        
        .wishlist-price, .wishlist-total {
            width: 120px;
        }
        
        .wishlist-action {
            width: 150px;
        }
        
        /* Estilos para la imagen del producto */
        .image-container {
            position: relative;
            width: 80px;
            height: 80px;
            margin: 0 auto;
        }
        
        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #eee;
        }
        
        .remove-item {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: #ff5555;
            color: white;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .image-container:hover .remove-item {
            opacity: 1;
        }
        
        /* Estilos para el nombre del producto */
        .product-name {
            color: #333;
            font-weight: 500;
            text-decoration: none;
            display: block;
            transition: color 0.3s ease;
        }
        
        .product-name:hover {
            color: var(--general);
        }
        
        /* Estilos para el control de cantidad */
        .quantity-control {
            display: flex;
            align-items: center;
            justify-content: center;
            max-width: 120px;
            margin: 0 auto;
            border-radius: 30px;
            border: 1px solid #eee;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }
        
        .qty-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f7f7f7;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .qty-btn:hover {
            background-color: var(--general);
            color: white;
        }
        
        .qty-input-box {
            width: 40px;
            border: none;
            text-align: center;
            font-size: 14px;
            font-weight: 600;
            background-color: transparent;
            outline: none;
        }
        
        /* Estilos para precios y totales */
        .price-amount, .total-amount {
            font-weight: 600;
            font-size: 15px;
            color: #333;
        }
        
        .total-amount {
            color: var(--general);
        }
        
        /* Estilos para el botón de añadir al carrito */
        .cart-btn {
            padding: 10px 18px;
            border-radius: 30px;
            border: none;
            background-color: var(--general);
            color: white;
            font-weight: 500;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 3px 10px rgba(var(--center-rgb), 0.3);
        }
        
        .cart-btn:hover {
            background-color: #333;
            transform: translateY(-2px);
        }
        
        .cart-btn.adding {
            opacity: 0.7;
            pointer-events: none;
            animation: pulse 1.5s infinite;
        }
        
        .cart-btn.added {
            background-color: #28a745;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        /* Estado vacío */
        .empty-wishlist {
            padding: 40px 0;
        }
        
        .empty-state {
            text-align: center;
            padding: 30px;
        }
        
        .empty-state i {
            font-size: 60px;
            color: var(--general);
            opacity: 0.3;
            margin-bottom: 20px;
        }
        
        .empty-state h4 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #333;
        }
        
        .empty-state p {
            color: #777;
            margin-bottom: 25px;
        }
        
        .continue-shopping {
            display: inline-block;
            padding: 12px 30px;
            background-color: var(--general);
            color: white;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .continue-shopping:hover {
            background-color: #333;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        /* Responsive */
        @media (max-width: 991px) {
            .wishlist-items th, .wishlist-items td {
                padding: 10px;
            }
            
            .wishlist-product {
                width: auto;
                min-width: 150px;
            }
        }
        
        @media (max-width: 768px) {
            .wishlist-items {
                min-width: 650px;
            }
            
            .wishlist-table {
                overflow-x: auto;
            }
            
            .quantity-control {
                max-width: 100px;
            }
            
            .cart-btn {
                padding: 8px 15px;
                font-size: 12px;
            }
        }
        
        @media (max-width: 576px) {
            .wishlist-container {
                padding: 10px;
            }
        }
    </style>