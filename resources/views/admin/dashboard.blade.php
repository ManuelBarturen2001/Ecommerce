@extends('admin.layouts.master')

@section('content')
<section class="section">
    <div class="dashboard-container">
        
            <div class="section-header">
                <h1>Panel Principal</h1>
                <div class="summary-date">
                                <span id="currentDate"></span>
                            </div>
            </div>

            <!-- Resumen Principal 
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card summary-card">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="text-muted mb-2">Bienvenido al Panel de Administración</h4>
                                <h2 class="mb-0">Resumen General</h2>
                            </div>
                            <div class="summary-date">
                                <span id="currentDate"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>-->

            <!-- Indicadores Principales -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-4">
                    <div class="card card-highlight h-100">
                        <div class="card-body text-center">
                            <div class="indicator-icon mb-3">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h3 class="indicator-value">{{$settings->currency_icon}}{{ $monthEarnings }}</h3>
                            <p class="indicator-label">Ingresos del Mes</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-4">
                    <div class="card card-highlight h-100">
                        <div class="card-body text-center">
                            <div class="indicator-icon mb-3">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <h3 class="indicator-value">{{ $todaysOrder }}</h3>
                            <p class="indicator-label">Pedidos Hoy</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-4">
                    <div class="card card-highlight h-100">
                        <div class="card-body text-center">
                            <div class="indicator-icon mb-3">
                                <i class="fas fa-users"></i>
                            </div>
                            <h3 class="indicator-value">{{ $totalUsers }}</h3>
                            <p class="indicator-label">Usuarios Totales</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-4">
                    <div class="card card-highlight h-100">
                        <div class="card-body text-center">
                            <div class="indicator-icon mb-3">
                                <i class="fas fa-store"></i>
                            </div>
                            <h3 class="indicator-value">{{ $totalVendors }}</h3>
                            <p class="indicator-label">Vendedores</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráficos -->
            <!-- Filtros de fecha y tipo de gráfico -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Filtrar datos del dashboard</h4>
            </div>
            <div class="card-body">
                <form id="dashboard-filter" method="GET" action="{{ url()->current() }}" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">Fecha inicial</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">Fecha final</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="chart_type" class="form-label">Tipo de gráfico</label>
                        <select id="chart_type" name="chart_type" class="form-select">
                            <option value="daily" {{ $chartType == 'daily' ? 'selected' : '' }}>Diario</option>
                            <option value="monthly" {{ $chartType == 'monthly' ? 'selected' : '' }}>Mensual</option>
                            <option value="yearly" {{ $chartType == 'yearly' ? 'selected' : '' }}>Anual</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">Aplicar filtros</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Gráficos -->
<div class="row mb-4">
    <div class="col-lg-8 col-md-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>
                    @if($chartType == 'daily')
                        Ingresos Diarios
                    @elseif($chartType == 'yearly')
                        Ingresos Anuales
                    @else
                        Ingresos Mensuales
                    @endif
                </h4>
            </div>
            <div class="card-body chart-container">
                <canvas id="revenueChart" height="180"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Estado de Pedidos</h4>
            </div>
            <div class="card-body chart-container">
                <canvas id="orderStatusChart" height="180"></canvas>
            </div>
        </div>
    </div>
</div>

            <!-- Estadísticas de Pedidos -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Estadísticas de Pedidos</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                                    <a href="{{ route('admin.order.index') }}" class="stat-card">
                                        <div class="stat-card-inner">
                                            <div class="stat-icon bg-info">
                                                <i class="fas fa-shopping-bag"></i>
                                            </div>
                                            <div class="stat-details">
                                                <span class="stat-title">Pedidos Totales</span>
                                                <span class="stat-value">{{ $totalOrders }}</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                                    <a href="{{ route('admin.pending-orders') }}" class="stat-card">
                                        <div class="stat-card-inner">
                                            <div class="stat-icon bg-warning">
                                                <i class="fas fa-clock"></i>
                                            </div>
                                            <div class="stat-details">
                                                <span class="stat-title">Pedidos Pendientes</span>
                                                <span class="stat-value">{{ $totalPendingOrders }}</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                                    <a href="{{ route('admin.delivered-orders') }}" class="stat-card">
                                        <div class="stat-card-inner">
                                            <div class="stat-icon bg-success">
                                                <i class="fas fa-check-circle"></i>
                                            </div>
                                            <div class="stat-details">
                                                <span class="stat-title">Pedidos Completados</span>
                                                <span class="stat-value">{{ $totalCompleteOrders }}</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                                    <a href="{{ route('admin.canceled-orders') }}" class="stat-card">
                                        <div class="stat-card-inner">
                                            <div class="stat-icon bg-danger">
                                                <i class="fas fa-times-circle"></i>
                                            </div>
                                            <div class="stat-details">
                                                <span class="stat-title">Pedidos Cancelados</span>
                                                <span class="stat-value">{{ $totalCanceledOrders }}</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ingresos -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Estadísticas de Ingresos</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4 col-md-6 col-12 mb-3">
                                    <div class="earning-card">
                                        <div class="earning-icon bg-success">
                                            <i class="fas fa-coins"></i>
                                        </div>
                                        <div class="earning-info">
                                            <h5>Ingresos de Hoy</h5>
                                            <h3>{{$settings->currency_icon}}{{ $todaysEarnings }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-12 mb-3">
                                    <div class="earning-card">
                                        <div class="earning-icon bg-info">
                                            <i class="fas fa-calendar-week"></i>
                                        </div>
                                        <div class="earning-info">
                                            <h5>Ingresos del Mes</h5>
                                            <h3>{{$settings->currency_icon}}{{ $monthEarnings }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-12 mb-3">
                                    <div class="earning-card">
                                        <div class="earning-icon bg-secondary">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                        <div class="earning-info">
                                            <h5>Ingresos del Año</h5>
                                            <h3>{{$settings->currency_icon}}{{ $yearEarnings }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Catálogo y Marketing -->
            <div class="row">
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h4>Catálogo</h4>
                        </div>
                        <div class="card-body">
                            <div class="catalog-stats">
                                <a href="{{route('admin.category.index')}}" class="catalog-stat-item">
                                    <div class="catalog-icon bg-info">
                                        <i class="fas fa-th-list"></i>
                                    </div>
                                    <div class="catalog-text">
                                        <span class="catalog-title">Categorías</span>
                                        <span class="catalog-value">{{ $totalCategories }}</span>
                                    </div>
                                </a>
                                <a href="{{route('admin.brand.index')}}" class="catalog-stat-item">
                                    <div class="catalog-icon bg-success">
                                        <i class="fas fa-tag"></i>
                                    </div>
                                    <div class="catalog-text">
                                        <span class="catalog-title">Marcas</span>
                                        <span class="catalog-value">{{ $totalBrands }}</span>
                                    </div>
                                </a>
                                <a href="{{route('admin.reviews.index')}}" class="catalog-stat-item">
                                    <div class="catalog-icon bg-warning">
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <div class="catalog-text">
                                        <span class="catalog-title">Reseñas</span>
                                        <span class="catalog-value">{{ $totalReview }}</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h4>Marketing</h4>
                        </div>
                        <div class="card-body">
                            <div class="marketing-stats">
                                <a href="{{route('admin.blog.index')}}" class="marketing-stat-item">
                                    <div class="marketing-icon bg-secondary">
                                        <i class="fas fa-blog"></i>
                                    </div>
                                    <div class="marketing-text">
                                        <span class="marketing-title">Blogs</span>
                                        <span class="marketing-value">{{$totalBlogs}}</span>
                                    </div>
                                </a>
                                <a href="{{route('admin.subscribers.index')}}" class="marketing-stat-item">
                                    <div class="marketing-icon bg-danger">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="marketing-text">
                                        <span class="marketing-title">Suscriptores</span>
                                        <span class="marketing-value">{{$totalSubscriber}}</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
    </div>
</section>
<style>
:root {
    --primary-color: var(--general, #6777ef);
    --secondary-color: #34395e;
    --success-color: #47c363;
    --info-color: #3abaf4;
    --warning-color: #ffc107;
    --danger-color: #fc544b;
    --light-color: #e3eaef;
    --dark-color: #191d21;
}

.dashboard-container {
    padding: 0;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding: 0 0 10px;
    border-bottom: 1px solid #f9f9f9;
}

.section-header h1 {
    margin-bottom: 0;
    font-size: 24px;
}

.chart-container {
    position: relative;
    height: 180px;
    max-height: 180px;
}

/* Tarjeta de resumen */
.summary-card {
    background: (var(--primary-color) 0%, #8e98f3 100%);
    color: #fff;
    border: none;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.summary-date {
    background-color: rgba(255, 255, 255, 0.2);
    padding: 8px 15px;
    border-radius: 20px;
    font-weight: 500;
}

/* Indicadores principales */
.card-highlight {
    border: none;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s, box-shadow 0.3s;
}

.card-highlight:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.indicator-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background:  var(--general);
    color: white;
    font-size: 24px;
    margin-bottom: 15px;
}

.indicator-value {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 5px;
    color: var(--secondary-color);
}

.indicator-label {
    color: #6c757d;
    font-size: 16px;
    margin: 0;
}

/* Tarjetas de estadísticas */
.stat-card {
    display: block;
    text-decoration: none;
    color: inherit;
    transition: transform 0.2s;
}

.stat-card:hover {
    transform: translateY(-3px);
}

.stat-card-inner {
    display: flex;
    align-items: center;
    background-color: #fff;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.stat-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 45px;
    height: 45px;
    border-radius: 10px;
    color: white;
    font-size: 18px;
    margin-right: 15px;
}

.stat-icon.bg-primary { background-color: var(--primary-color); }
.stat-icon.bg-warning { background-color: var(--warning-color); }
.stat-icon.bg-success { background-color: var(--success-color); }
.stat-icon.bg-danger { background-color: var(--danger-color); }

.stat-details {
    display: flex;
    flex-direction: column;
}

.stat-title {
    font-size: 14px;
    color: #6c757d;
}

.stat-value {
    font-size: 18px;
    font-weight: 600;
    color: var(--secondary-color);
}

/* Tarjetas de ingresos */
.earning-card {
    display: flex;
    align-items: center;
    background-color: #fff;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    height: 100%;
}

.earning-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    border-radius: 10px;
    /* background: var(--general) ; */
    color: white;
    font-size: 24px;
    margin-right: 20px;
}

.earning-info h5 {
    font-size: 16px;
    color: #6c757d;
    margin-bottom: 5px;
}

.earning-info h3 {
    font-size: 24px;
    font-weight: 700;
    color: var(--secondary-color);
    margin: 0;
}

/* Catálogo y Marketing */
.catalog-stats, .marketing-stats {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 15px;
}

.catalog-stat-item, .marketing-stat-item {
    display: flex;
    align-items: center;
    background-color: #fff;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    text-decoration: none;
    color: inherit;
    transition: transform 0.2s;
}

.catalog-stat-item:hover, .marketing-stat-item:hover {
    transform: translateY(-3px);
}

.catalog-icon, .marketing-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 45px;
    height: 45px;
    border-radius: 10px;
    background-color: var(--primary-color);
    color: white;
    font-size: 18px;
    margin-right: 15px;
}

.catalog-text, .marketing-text {
    display: flex;
    flex-direction: column;
}

.catalog-title, .marketing-title {
    font-size: 14px;
    color: #6c757d;
}

.catalog-value, .marketing-value {
    font-size: 18px;
    font-weight: 600;
    color: var(--secondary-color);
}

/* Sección Compacta */
.compact-section {
    padding: 0.5rem 0;
}

.row {
    margin-left: -10px;
    margin-right: -10px;
}

.row > [class^="col"] {
    padding-left: 10px;
    padding-right: 10px;
}

.mb-4 {
    margin-bottom: 15px !important;
}

/* Tarjetas más compactas */
.card {
    margin-bottom: 15px;
    border-radius: 6px;
}

.card-header {
    padding: 10px 15px;
    border-bottom: 1px solid rgba(0,0,0,0.05);
    background-color: transparent;
    height: auto;
    min-height: auto;
}

.card-header h4 {
    font-size: 16px;
    margin: 0;
}

.card-body {
    padding: 15px;
}

/* Responsividad */
@media (max-width: 1200px) {
    .catalog-stats, .marketing-stats {
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    }
    
    .indicator-value {
        font-size: 22px;
    }
}

@media (max-width: 992px) {
    .indicator-value {
        font-size: 20px;
    }
    
    .earning-info h3 {
        font-size: 18px;
    }
    
    .indicator-icon {
        width: 50px;
        height: 50px;
        font-size: 20px;
    }
}

@media (max-width: 768px) {
    .stat-icon, .catalog-icon, .marketing-icon {
        width: 36px;
        height: 36px;
        font-size: 14px;
    }
    
    .stat-value, .catalog-value, .marketing-value {
        font-size: 15px;
    }
    
    .earning-icon {
        width: 45px;
        height: 45px;
        font-size: 18px;
    }
    
    .chart-container {
        height: 160px;
    }
}

@media (max-width: 576px) {
    .catalog-stats, .marketing-stats {
        grid-template-columns: 1fr;
    }
    
    .earning-card {
        margin-bottom: 10px;
    }
    
    .chart-container {
        height: 140px;
    }
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fecha actual
    const now = new Date();
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    if (document.getElementById('currentDate')) {
        document.getElementById('currentDate').textContent = now.toLocaleDateString('es-ES', options);
    }
    
    // Asegurarnos que Chart está definido correctamente
    if (typeof Chart !== 'undefined') {
        // Gráfico de ingresos según el tipo seleccionado
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        
        // Obtener datos del backend
        const revenueLabels = {!! json_encode($revenueData['labels'] ?? []) !!};
        const revenueValues = {!! json_encode($revenueData['data'] ?? []) !!};
        
        const revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: revenueLabels,
                datasets: [{
                    label: 'Ingresos',
                    data: revenueValues,
                    backgroundColor: 'rgba(103, 119, 239, 0.2)',
                    borderColor: 'rgba(103, 119, 239, 1)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(103, 119, 239, 1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: true,
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('es-ES', { 
                                        style: 'currency', 
                                        currency: 'PEN'  // Cambia a tu moneda
                                    }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 5,
                            callback: function(value) {
                                return new Intl.NumberFormat('es-ES', { 
                                    style: 'currency', 
                                    currency: 'PEN',  // Cambia a tu moneda
                                    notation: 'compact'
                                }).format(value);
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                elements: {
                    point: {
                        radius: 2,
                        hoverRadius: 4
                    },
                    line: {
                        tension: 0.3
                    }
                }
            }
        });
        
        // Gráfico de estado de pedidos
        const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
        
        // Obtener datos del backend
        const orderStatusLabels = {!! json_encode($orderStatusData['labels'] ?? []) !!};
        const orderStatusValues = {!! json_encode($orderStatusData['data'] ?? []) !!};
        
        const orderStatusChart = new Chart(orderStatusCtx, {
            type: 'doughnut',
            data: {
                labels: orderStatusLabels,
                datasets: [{
                    data: orderStatusValues,
                    backgroundColor: [
                        'rgba(71, 195, 99, 0.8)',
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(252, 84, 75, 0.8)'
                    ],
                    borderColor: [
                        'rgba(71, 195, 99, 1)',
                        'rgba(255, 193, 7, 1)',
                        'rgba(252, 84, 75, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 10,
                            font: {
                                size: 10
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((acc, val) => acc + val, 0);
                                const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '65%'
            }
        });
        
        // Manejo del cambio en los filtros de fecha
        document.querySelectorAll('#start_date, #end_date, #chart_type').forEach(element => {
            element.addEventListener('change', function() {
                // Validar fechas
                const startDate = new Date(document.getElementById('start_date').value);
                const endDate = new Date(document.getElementById('end_date').value);
                
                if (endDate < startDate) {
                    alert('La fecha final no puede ser anterior a la fecha inicial');
                    return;
                }
                
                // Enviar formulario automáticamente al cambiar
                document.getElementById('dashboard-filter').submit();
            });
        });
        
    } else {
        console.error('Chart.js no está disponible. Verifica que se haya cargado correctamente.');
    }
});
</script>
@endpush
@endsection