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
                                    <li class="breadcrumb-item active" aria-current="page"><a href="{{route('user.profile')}}">Mi Cuenta</a></li>
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
                        <div class="wsus__dashboard_add">
                          <div class="row">
                            @foreach ($addresses as $address)
                            <div class="col-xl-6">
                              <div class="wsus__dash_add_single">
                                <h4>Dirección de facturación</h4>
                                <ul>
                                  <li><span>Nombre:</span> {{$address->name}}</li>
                                  <li><span>Teléfono:</span> {{$address->phone}}</li>
                                  <li><span>Correo electrónico:</span> {{$address->email}}</li>
                                  <li><span>Departamento:</span> {{ $address->departamento->nombre ?? '' }}</li>
                                  <li><span>Provincia:</span> {{ $address->provincia->nombre ?? '' }}</li>
                                  <li><span>Distrito:</span> {{ $address->distrito->nombre ?? '' }}</li>
                                  <li><span>Código postal:</span> {{$address->zip}}</li>
                                  <li><span>Dirección:</span> {{$address->address}}</li>
                                </ul>
                                <div class="wsus__address_btn">
                                  <a href="{{route('user.address.edit', $address->id)}}" class="edit"><i class="fal fa-edit"></i> Editar</a>
                                  <a href="{{route('user.address.destroy', $address->id)}}" class="del delete-item"><i class="fal fa-trash-alt"></i> Eliminar</a>
                                </div>
                              </div>
                            </div>
                            @endforeach
                            <div class="col-12">
                              <a href="{{route('user.address.create')}}" class="add_address_btn common_btn"><i class="far fa-plus"></i>
                                Agregar nueva dirección</a>
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

