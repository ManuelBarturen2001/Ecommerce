@extends('frontend.dashboard.layouts.master')
@section('title')
{{$settings->site_name}} || Solicitud Vendedor
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
                        <h4>Solicitud Vendedor</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fas fa-home"></i> Inicio</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('user.profile')}}">Mi Cuenta</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Solicitar ser Vendedor</li>
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
                            <h4>Terminos y Condiciones para ser Vendedor</h4>
                        </div>

                        <div class="vendor-request-container">
                            <div class="row">
                              <div class="vendor-request-content">
                                  <div class="vendor-information-panel">
                                      <div class="info-content">
                                          {!! @$content->content !!}
                                      </div>
                                  </div>

                                  <div class="vendor-form-panel">
                                      
                                          <form action="{{ route('user.vendor-request.create') }}" method="POST" enctype="multipart/form-data">
                                              @csrf
                                              <div class="form-group">
                                                  <label for="shop_image">Banner de la Tienda</label>
                                                  <div class="file-upload-wrapper">
                                                      <div class="file-upload-preview">
                                                          <img id="shop_image_preview" src="{{ asset('images/placeholder-image.jpg') }}" alt="Banner Preview">
                                                      </div>
                                                      <div class="file-upload-input">
                                                          <input type="file" name="shop_image" id="shop_image" class="form-control">
                                                          <span class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></span>
                                                          <span class="upload-text">Click para seleccionar o arrastra una imagen</span>
                                                      </div>
                                                  </div>
                                              </div>

                                              <div class="form-group">
                                                  <label for="shop_name">Nombre de la Tienda</label>
                                                  <div class="input-with-icon">
                                                      <i class="fas fa-store"></i>
                                                      <input type="text" name="shop_name" id="shop_name" class="form-control" placeholder="Nombre de tu tienda">
                                                  </div>
                                              </div>

                                              <div class="row">
                                                  <div class="col-md-6">
                                                      <div class="form-group">
                                                          <label for="shop_email">Correo de la Tienda</label>
                                                          <div class="input-with-icon">
                                                              <i class="fas fa-envelope"></i>
                                                              <input type="email" name="shop_email" id="shop_email" class="form-control" placeholder="correo@tutienda.com">
                                                          </div>
                                                      </div>
                                                  </div>

                                                  <div class="col-md-6">
                                                      <div class="form-group">
                                                          <label for="shop_phone">Teléfono de la Tienda</label>
                                                          <div class="input-with-icon">
                                                              <i class="fas fa-phone-alt"></i>
                                                              <input type="tel" name="shop_phone" id="shop_phone" class="form-control" placeholder="(+34) 123 456 789">
                                                          </div>
                                                      </div>
                                                  </div>
                                              </div>

                                              <div class="form-group">
                                                  <label for="shop_address">Dirección de la Tienda</label>
                                                  <div class="input-with-icon">
                                                      <i class="fas fa-map-marker-alt"></i>
                                                      <input type="text" name="shop_address" id="shop_address" class="form-control" placeholder="Dirección completa">
                                                  </div>
                                              </div>

                                              <div class="form-group">
                                                  <label for="about">Acerca de Ti</label>
                                                  <div class="input-with-icon textarea-container">
                                                      <i class="fas fa-align-left"></i>
                                                      <textarea name="about" id="about" class="form-control" rows="6" placeholder="Cuéntanos sobre tu tienda, productos y experiencia..."></textarea>
                                                  </div>
                                              </div>

                                              <div class="form-action">
                                                  <button type="submit" class="btn-submit">
                                                      <i class="fas fa-paper-plane"></i> Enviar Solicitud
                                                  </button>
                                              </div>
                                          </form>
                                       <!-- .form-wrapper -->
                                  </div> <!-- .vendor-form-panel -->
                              </div> <!-- .vendor-request-content -->
                            </div> <!-- .row -->
                        </div> <!-- .vendor-request-container -->

                    </div> <!-- .dashboard-card -->
                </div> <!-- .dashboard-main-content -->
            </div> <!-- .col-lg-9 -->
        </div> <!-- .row -->
    </div> <!-- .container-fluid -->
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

    // Mostrar la vista previa de la imagen seleccionada
    const shopImageInput = document.getElementById('shop_image');
    const shopImagePreview = document.getElementById('shop_image_preview');
    
    if (shopImageInput && shopImagePreview) {
        shopImageInput.addEventListener('change', function(event) {
            if (event.target.files.length > 0) {
                const selectedFile = event.target.files[0];
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    shopImagePreview.src = e.target.result;
                };
                
                reader.readAsDataURL(selectedFile);
            }
        });
    }
  });
</script>
@endpush
