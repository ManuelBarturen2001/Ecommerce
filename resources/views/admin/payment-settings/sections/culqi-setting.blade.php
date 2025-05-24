<section class="section">
    <div class="section-header">
        <h1>Configuración de Culqi</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Configuración de Pasarela Culqi</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.culqi-setting.update')}}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-group">
                                <label>Estado</label>
                                <select name="status" class="form-control">
                                    <option @selected(@$culqi->status == 1) value="1">Activo</option>
                                    <option @selected(@$culqi->status == 0) value="0">Inactivo</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Modo de Cuenta</label>
                                <select name="mode" class="form-control">
                                    <option @selected(@$culqi->mode == 'sandbox') value="sandbox">Sandbox</option>
                                    <option @selected(@$culqi->mode == 'live') value="live">En Vivo</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Nombre del País</label>
                                <input type="text" class="form-control" name="country_name" value="{{@$culqi->country_name}}">
                            </div>

                            <div class="form-group">
                                <label>Nombre de la Moneda</label>
                                <select name="currency_name" class="form-control">
                                    <option @selected(@$culqi->currency_name == 'PEN') value="PEN">Soles Peruanos (PEN)</option>
                                    <option @selected(@$culqi->currency_name == 'USD') value="USD">Dólares USD</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Tasa de Conversión (1 USD = ? {{@$culqi->currency_name ?? 'PEN'}})</label>
                                <input type="text" class="form-control" name="currency_rate" value="{{@$culqi->currency_rate}}">
                            </div>

                            <div class="form-group">
                                <label>Clave Pública (Public Key)</label>
                                <input type="text" class="form-control" name="public_key" value="{{@$culqi->public_key}}">
                            </div>

                            <div class="form-group">
                                <label>Clave Secreta (Secret Key)</label>
                                <input type="text" class="form-control" name="secret_key" value="{{@$culqi->secret_key}}">
                            </div>

                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </form>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h4>Información Importante</h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <h5>Configuración de Culqi:</h5>
                            <ul>
                                <li><strong>Sandbox:</strong> Para pruebas de desarrollo</li>
                                <li><strong>Live:</strong> Para transacciones reales</li>
                                <li><strong>Public Key:</strong> Se usa en el frontend para crear tokens</li>
                                <li><strong>Secret Key:</strong> Se usa en el backend para procesar pagos</li>
                            </ul>
                        </div>
                        
                        <div class="alert alert-warning">
                            <h5>Métodos de Pago Soportados:</h5>
                            <ul>
                                <li>Tarjetas de crédito y débito</li>
                                <li>Yape</li>
                                <li>Plin</li>
                                <li>Billeteras digitales</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>