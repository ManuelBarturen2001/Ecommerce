@extends('frontend.dashboard.layouts.master')

@section('title')
{{$settings->site_name}} || Mis Pedidos
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
                            <h4>Mis Pedidos</h4>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fas fa-home"></i> Inicio</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><a href="{{route('user.profile')}}">Mi Cuenta</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Mis Pedidos</li>
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
                          <h4>Historial de Pedidos</h4>
                          <div class="card-header-actions">
                              <div class="search-box">
                                <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                    <input type="text" id="orderSearch" placeholder="Buscar por número de pedido..." style="flex: 1; min-width: 200px;">
                                    <i class="fas fa-search" style="color: #000000; font-size: 1.2rem;"></i>
                                </div>
                              </div>
                              <!-- <div class="filter-dropdown">
                                  <button class="filter-btn" id="filterBtn">
                                      <i class="fas fa-filter"></i>
                                      Filtrar
                                  </button>
                              </div> -->
                          </div>
                      </div>
                      
                      <div class="orders-container">
                          <!-- Loading State -->
                          <div class="loading" id="loadingState">
                              <i class="fas fa-spinner"></i>
                              <p>Cargando pedidos...</p>
                          </div>

                          <!-- Orders Grid -->
                          <div class="orders-grid" id="ordersGrid">
                              @forelse($orders as $order)
                              <div class="order-card" data-order="{{$order->invocie_id}}">
                                  <div class="order-card-header">
                                      <div class="order-number">Pedido #{{$order->invocie_id}}</div>
                                      <div class="order-date">{{date('d \d\e M \d\e\l y', strtotime($order->created_at))}}</div>
                                  </div>
                                  <div class="order-card-body">
                                      <div class="order-info">
                                          <div class="info-row">
                                              <span class="info-label">Cantidad:</span>
                                              <span class="info-value">{{$order->product_qty}} productos</span>
                                          </div>
                                          <div class="info-row">
                                              <span class="info-label">Monto:</span>
                                              <span class="info-value order-amount">S/ {{number_format($order->amount, 2)}}</span>
                                          </div>
                                          <div class="info-row">
                                              <span class="info-label">Estado del pedido:</span>
                                              @switch($order->order_status)
                                                  @case('pending')
                                                      <span class="status-badge status-pending">Pendiente</span>
                                                      @break
                                                  @case('processed_and_ready_to_ship')
                                                      <span class="status-badge status-processed">Procesado</span>
                                                      @break
                                                  @case('dropped_off')
                                                      <span class="status-badge status-processed">Enviado</span>
                                                      @break
                                                  @case('shipped')
                                                      <span class="status-badge status-shipped">Enviado</span>
                                                      @break
                                                  @case('out_for_delivery')
                                                      <span class="status-badge status-shipped">En camino</span>
                                                      @break
                                                  @case('delivered')
                                                      <span class="status-badge status-delivered">Entregado</span>
                                                      @break
                                                  @case('canceled')
                                                      <span class="status-badge status-canceled">Cancelado</span>
                                                      @break
                                                  @default
                                                      <span class="status-badge status-pending">Pendiente</span>
                                              @endswitch
                                          </div>
                                          <div class="info-row">
                                              <span class="info-label">Estado del pago:</span>
                                              @if($order->payment_status === 1)
                                                  <span class="status-badge payment-complete">Completo</span>
                                              @else
                                                  <span class="status-badge payment-pending">Pendiente</span>
                                              @endif
                                          </div>
                                          <div class="info-row">
                                              <span class="info-label">Método de pago:</span>
                                              <span class="info-value">{{$order->payment_method}}</span>
                                          </div>
                                      </div>
                                      <div class="order-actions">
                                          <a href="{{route('user.orders.show', $order->id)}}" class="view-details-btn">
                                              <i class="fas fa-eye"></i> Ver detalles
                                          </a>
                                      </div>
                                  </div>
                              </div>
                              @empty
                              <div class="empty-state">
                                  <i class="fas fa-shopping-bag"></i>
                                  <h3>No hay pedidos</h3>
                                  <p>Aún no tienes ningún pedido registrado.</p>
                              </div>
                              @endforelse
                              @if($orders->count() > 3)
                                  <div class="text-center mt-4" id="loadMoreContainer">
                                      <button id="loadMoreBtn" class="btn btn-primary">Ver más</button>
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
          // Search functionality
          const searchInput = document.getElementById('orderSearch');
          const orderCards = document.querySelectorAll('.order-card');
          const emptyState = document.getElementById('emptyState');
          const ordersGrid = document.getElementById('ordersGrid');

          if (searchInput) {
              searchInput.addEventListener('input', function() {
                  const searchTerm = this.value.toLowerCase();
                  let visibleCards = 0;

                  orderCards.forEach(card => {
                      const orderNumber = card.getAttribute('data-order');
                      const cardText = card.textContent.toLowerCase();
                      
                      if (orderNumber.includes(searchTerm) || cardText.includes(searchTerm)) {
                          card.style.display = 'block';
                          visibleCards++;
                      } else {
                          card.style.display = 'none';
                      }
                  });

                  // Show/hide empty state based on visible cards
                  if (visibleCards === 0 && orderCards.length > 0) {
                      // Create temporary empty message for search
                      if (!document.querySelector('.search-empty')) {
                          const searchEmpty = document.createElement('div');
                          searchEmpty.className = 'empty-state search-empty';
                          searchEmpty.innerHTML = `
                              <i class="fas fa-search"></i>
                              <h3>No se encontraron pedidos</h3>
                              <p>No hay pedidos que coincidan con tu búsqueda.</p>
                          `;
                          ordersGrid.appendChild(searchEmpty);
                      }
                      document.querySelector('.search-empty').style.display = 'block';
                  } else {
                      const searchEmpty = document.querySelector('.search-empty');
                      if (searchEmpty) {
                          searchEmpty.style.display = 'none';
                      }
                  }
              });
          }

          // Smooth loading animation
          const loadingState = document.getElementById('loadingState');
          
          if (loadingState && orderCards.length > 0) {
              loadingState.style.display = 'block';
              ordersGrid.style.display = 'none';
              
              setTimeout(() => {
                  loadingState.style.display = 'none';
                  ordersGrid.style.display = 'grid';
              }, 800);
          } else if (loadingState) {
              loadingState.style.display = 'none';
          }

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

          // Filter functionality placeholder
          const filterBtn = document.getElementById('filterBtn');
          if (filterBtn) {
              filterBtn.addEventListener('click', function() {
                  // Aquí puedes agregar funcionalidad de filtrado
                  console.log('Filter clicked - implement filter logic here');
              });
          }

          // Mostrar solo los primeros 3 pedidos
          const orderCardsArray = Array.from(document.querySelectorAll('.order-card'));
          const loadMoreBtn = document.getElementById('loadMoreBtn');
          const loadMoreContainer = document.getElementById('loadMoreContainer');

          if (orderCardsArray.length > 3) {
              orderCardsArray.forEach((card, index) => {
                  if (index >= 3) {
                      card.style.display = 'none';
                  }
              });
          }

          let currentIndex = 3; // Ya se mostraron 3

        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', () => {
                const nextIndex = currentIndex + 3;

                for (let i = currentIndex; i < nextIndex && i < orderCardsArray.length; i++) {
                    orderCardsArray[i].style.display = 'block';
                }

                currentIndex = nextIndex;

                if (currentIndex >= orderCardsArray.length) {
                    loadMoreContainer.style.display = 'none';
                }
            });
        }

        

      });
    </script>
@endpush