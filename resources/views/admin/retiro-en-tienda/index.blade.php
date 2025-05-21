@extends('admin.layouts.master')

@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Retiro en Tienda</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Retiro en Tienda</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Listado de Tiendas</h4>
                            <div class="card-header-action">
                                <a href="{{ route('admin.retiro-en-tienda.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Añadir Tienda
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-tiendas">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Dirección</th>
                                            <th>Ciudad</th>
                                            <th>Teléfono</th>
                                            <th>Horario</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($tiendas as $tienda)
                                        <tr>
                                            <td>{{ $tienda->id }}</td>
                                            <td>{{ $tienda->nombre_tienda }}</td>
                                            <td>{{ $tienda->direccion }}</td>
                                            <td>{{ $tienda->ciudad }}</td>
                                            <td>{{ $tienda->telefono ?? 'N/A' }}</td>
                                            <td>
                                                @if($tienda->horario_apertura && $tienda->horario_cierre)
                                                    {{ date('h:i A', strtotime($tienda->horario_apertura)) }} - {{ date('h:i A', strtotime($tienda->horario_cierre)) }}
                                                @else
                                                    No especificado
                                                @endif
                                            </td>
                                            <td>
                                                <div class="badge badge-{{ $tienda->estado ? 'success' : 'danger' }}">
                                                    {{ $tienda->estado ? 'Activo' : 'Inactivo' }}
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.retiro-en-tienda.edit', $tienda->id) }}" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('admin.retiro-en-tienda.cambiar-estado', $tienda->id) }}" class="btn btn-{{ $tienda->estado ? 'warning' : 'success' }} btn-sm">
                                                    <i class="fas fa-{{ $tienda->estado ? 'times' : 'check' }}"></i>
                                                </a>
                                                <form action="{{ route('admin.retiro-en-tienda.destroy', $tienda->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar esta tienda?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center">No hay tiendas registradas</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
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
    $(document).ready(function() {
        $('#table-tiendas').DataTable();
    });
</script>
@endpush