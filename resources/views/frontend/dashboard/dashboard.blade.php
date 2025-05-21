@extends('frontend.dashboard.layouts.master')

@section('title')
{{$settings->site_name}} || Mis Direcciones
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
                            <h4>Mis Direcciones</h4>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fas fa-home"></i> Inicio</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Mis Direcciones</li>
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
                          <h4>Historial de Direcciones</h4>
                      </div>
                      <div class="orders-container">
                        <div class="row">
                          
                              <h3>Panel de Usuario</h3>
                              <br>
                            <div class="dashboard_content">
                              <div class="wsus__dashboard">
                                <div class="row">
                                  <div class="col-xl-2 col-6 col-md-4">
                                    <a class="wsus__dashboard_item red" href="{{route('user.orders.index')}}">
                                      <i class="fas fa-cart-plus"></i>
                                      <p>Pedidos Totales</p>
                                      <h4 style="color:#ffff">{{$totalOrder}}</h4>
                                    </a>
                                  </div>
                                  <div class="col-xl-2 col-6 col-md-4">
                                    <a class="wsus__dashboard_item green" href="dsahboard_download.html">
                                      <i class="fas fa-clock"></i>
                                      <p>Pedidos Pendientes</p>
                                      <h4 style="color:#ffff">{{$pendingOrder}}</h4>
                                    </a>
                                  </div>
                                  <div class="col-xl-2 col-6 col-md-4">
                                    <a class="wsus__dashboard_item sky" href="dsahboard_review.html">
                                      <i class="fas fa-check-circle"></i>
                                      <p>Pedidos Completados</p>
                                      <h4 style="color:#ffff">{{$completeOrder}}</h4>
                                    </a>
                                  </div>
                                  <div class="col-xl-2 col-6 col-md-4">
                                    <a class="wsus__dashboard_item blue" href="{{route('user.review.index')}}">
                                      <i class="fas fa-star"></i>
                                      <p>Reseñas</p>
                                      <h4 style="color:#ffff">{{$reviews}}</h4>
                                    </a>
                                  </div>

                                  <div class="col-xl-2 col-6 col-md-4">
                                    <a class="wsus__dashboard_item purple" href="{{route('user.wishlist.index')}}">
                                      <i class="fas fa-heart"></i>
                                      <p>Lista de Deseos</p>
                                      <h4 style="color:#ffff">{{$wishlist}}</h4>
                                    </a>
                                  </div>

                                  <div class="col-xl-2 col-6 col-md-4">
                                      <a class="wsus__dashboard_item orange" href="{{route('user.profile')}}">
                                        <i class="fas fa-user-shield"></i>
                                        <p>Perfil</p>
                                        <h4 style="color:#ffff">-</h4>
                                      </a>
                                  </div>
                                </div>

                              </div>
                            </div>
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
@endpush



