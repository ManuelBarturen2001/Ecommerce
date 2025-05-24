<div class="tab-pane fade" id="list-profile" role="tabpanel" aria-labelledby="list-profile-list">
    <div class="card border">
        <div class="card-body">
            <form action="{{route('admin.email-setting-update')}}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>Correo Electrónico</label>
                    <input type="text" class="form-control" name="email" value="{{$emailSettings->email}}">
                </div>

                <div class="form-group">
                    <label>Servidor de Correo (Mail Host)</label>
                    <input type="text" class="form-control" name="host" value="{{$emailSettings->host}}">
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nombre de usuario SMTP</label>
                            <input type="text" class="form-control" name="username" value="{{$emailSettings->username}}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Contraseña SMTP</label>
                            <input type="text" class="form-control" name="password" value="{{$emailSettings->password}}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Puerto de Correo</label>
                            <input type="text" class="form-control" name="port" value="{{$emailSettings->port}}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Encriptación del Correo</label>
                            <select name="encryption" id="" class="form-control">
                                    <option {{$emailSettings->encryption == 'tls' ? 'selected' : ''}} value="tls">TLS</option>
                                    <option {{$emailSettings->encryption == 'ssl' ? 'selected' : ''}} value="ssl">SSL</option>
                            </select>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Actualizar</button>
            </form>
        </div>
    </div>
</div>
