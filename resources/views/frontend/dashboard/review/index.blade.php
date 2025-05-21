@extends('frontend.dashboard.layouts.master')

@section('title')
{{$settings->site_name}} || Mis Reseñas
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
                        <h4>Mis Reseñas</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fas fa-home"></i> Inicio</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('user.profile')}}">Mi Cuenta</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Mis Reseñas</li>
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
                            <h4>Mis Reseñas de Productos</h4>
                        </div>
                        <div class="reviews-container">
                            @if(count($reviews) > 0)
                                <div class="row">
                                    @foreach($reviews as $review)
                                        <div class="col-md-6 col-xl-4 mb-4">
                                            <div class="review-card">
                                                <div class="review-header">
                                                    <div class="product-image">
                                                        @php
                                                            $thumbnailPath = '';
                                                            // Verificar si el producto existe y si tiene una imagen
                                                            if($review->product && isset($review->product->thumb_image)){
                                                                $thumbnailPath = $review->product->thumb_image;
                                                            }
                                                        @endphp
                                                        
                                                        @if($thumbnailPath)
                                                            <img src="{{ asset($thumbnailPath) }}" alt="{{ $review->product->name }}">
                                                        @else
                                                            <div class="no-image">
                                                                <i class="fas fa-image"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="product-info">
                                                        <h5><a href="{{ route('product-detail', $review->product->slug) }}">{{ $review->product->name }}</a></h5>
                                                        <div class="review-date">
                                                            <i class="far fa-calendar-alt"></i> {{ $review->created_at->format('d M, Y') }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="review-content">
                                                    <div class="review-rating">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $review->rating)
                                                                <i class="fas fa-star"></i>
                                                            @else
                                                                <i class="far fa-star"></i>
                                                            @endif
                                                        @endfor
                                                        <span class="rating-text">({{ $review->rating }}/5)</span>
                                                    </div>
                                                    <div class="review-text">
                                                        <p>{{ $review->review }}</p>
                                                    </div>
                                                    <div class="review-status {{ $review->status == 1 ? 'approved' : 'pending' }}">
                                                        {{ $review->status == 1 ? 'Aprobada' : 'Pendiente' }}
                                                    </div>
                                                </div>
                                                @if(count($review->reviewGalleries) > 0)
                                                    <div class="review-gallery">
                                                        <h6>Imágenes:</h6>
                                                        <div class="gallery-images">
                                                            @foreach($review->reviewGalleries as $gallery)
                                                                <div class="gallery-img">
                                                                    <img src="{{ asset($gallery->image) }}" alt="Review Image">
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="reviews-pagination">
                                    {{ $reviews->links() }}
                                </div>
                            @else
                                <div class="no-reviews">
                                    <div class="no-data-found">
                                        <img src="{{ asset('frontend/images/no-data.svg') }}" alt="No Reviews">
                                        <h5>No has realizado ninguna reseña todavía</h5>
                                        <p>¡Cuando compres y reseñes productos, aparecerán aquí!</p>
                                    </div>
                                </div>
                            @endif
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