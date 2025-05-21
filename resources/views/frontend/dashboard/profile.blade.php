@extends('frontend.dashboard.layouts.master')

@section('title')
{{$settings->site_name}} || Mi Perfil
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
                            <h4>Mi Perfil</h4>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fas fa-home"></i> Inicio</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><a href="{{route('user.profile')}}">Mi Cuenta</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Mi Perfil</li>
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
                          <h4>Configuración del Perfil</h4>
                      </div>
                      <div class="orders-container">
                        <div class="wsus__dash_pro_area">
                          <h4>Información Básica</h4>

                              <form action="{{route('user.profile.update')}}" method="POST" enctype="multipart/form-data">
                                  @csrf
                                  @method('PUT')
                                  <div class="col-md-12">
                                      <div class="col-md-2">
                                          <div class="wsus__dash_pro_img">
                                            <img src="{{Auth::user()->image ? asset(Auth::user()->image) : asset('frontend/images/ts-2.jpg')}}" alt="img" class="img-fluid w-100">
                                            <input type="file" name="image">
                                          </div>
                                      </div>
                                      <div class="col-md-12 mt-5">
                                        <div class="wsus__dash_pro_single">
                                          <i class="fas fa-user-tie"></i>
                                          <input type="text" placeholder="Nombre" name="name" value="{{Auth::user()->name}}">
                                        </div>
                                      </div>

                                      <div class="col-md-12">
                                        <div class="wsus__dash_pro_single">
                                          <i class="fal fa-envelope-open"></i>
                                          <input type="email" placeholder="Correo Electrónico" name="email" value="{{Auth::user()->email}}">
                                        </div>
                                      </div>

                                  </div>
                                  <div class="col-xl-12">
                                      <button class="common_btn mb-4 mt-2" type="submit">Guardar</button>
                                  </div>
                              </form>


                              <div class="wsus__dash_pass_change mt-2">
                              <form action="{{route('user.profile.update.password')}}" method="POST">
                                  @csrf
                                  <div class="row">
                                      <h4>Actualizar Contraseña</h4>
                                      <div class="col-xl-4 col-md-6">
                                      <div class="wsus__dash_pro_single">
                                          <i class="fas fa-unlock-alt"></i>
                                          <input type="password" placeholder="Contraseña Actual" name="current_password">
                                      </div>
                                      </div>
                                      <div class="col-xl-4 col-md-6">
                                      <div class="wsus__dash_pro_single">
                                          <i class="fas fa-lock-alt"></i>
                                          <input type="password" placeholder="Nueva Contraseña" name="password">
                                      </div>
                                      </div>
                                      <div class="col-xl-4">
                                      <div class="wsus__dash_pro_single">
                                          <i class="fas fa-lock-alt"></i>
                                          <input type="password" placeholder="Confirmar Contraseña" name="password_confirmation">
                                      </div>
                                      </div>
                                      <div class="col-xl-12">
                                      <button class="common_btn" type="submit">Guardar</button>
                                      </div>
                                  </div>
                              </form>
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


