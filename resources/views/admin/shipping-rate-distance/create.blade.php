@extends('admin.layouts.master')

@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Tarifas por Distancia</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Crear Tarifa por Distancia</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.shipping-rate-distance.store') }}" method="POST">
                                @csrf

                                <!-- Campo para shipping_rule_id -->
                                <div class="form-group">
                                    <label for="shipping_rule_id">Regla de Envío</label>
                                    <select id="shipping_rule_id" class="form-control" name="shipping_rule_id" required>
                                        <option value="">Seleccione una regla de envío</option>
                                        @foreach($shippingRules as $rule)
                                            <option value="{{ $rule->id }}" {{ old('shipping_rule_id') == $rule->id ? 'selected' : '' }}>
                                                {{ $rule->name }}  <!-- Suponiendo que el modelo ShippingRule tiene un campo 'name' -->
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Campo para min_km -->
                                <div class="form-group">
                                    <label for="min_km">Distancia Mínima (km)</label>
                                    <input type="number" step="0.01" class="form-control" name="min_km" value="{{ old('min_km') }}" required>
                                </div>

                                <!-- Campo para max_km -->
                                <div class="form-group">
                                    <label for="max_km">Distancia Máxima (km)</label>
                                    <input type="number" step="0.01" class="form-control" name="max_km" value="{{ old('max_km') }}" required>
                                </div>

                                <!-- Campo para prince (Precio) -->
                                <div class="form-group">
                                    <label for="price">Precio (S/.)</label>
                                    <input type="number" step="0.01" class="form-control" name="price" value="{{ old('price') }}" required>
                                </div>

                                <!-- Campo para status -->
                                <div class="form-group">
                                    <label for="inputState">Status</label>
                                    <select id="inputState" class="form-control" name="status">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>

                                <!-- Botón para enviar el formulario -->
                                <button type="submit" class="btn btn-primary">Crear</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
