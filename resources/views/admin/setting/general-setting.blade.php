<div class="tab-pane fade show active" id="list-home" role="tabpanel" aria-labelledby="list-home-list">
<div class="card border">
    <div class="card-body">
        <form action="{{route('admin.generale-setting-update')}}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nombre del Sitio</label>
                <input type="text" class="form-control" name="site_name" value="{{@$generalSettings->site_name}}">
            </div>

            <div class="form-group">
                <label>Diseño</label>
                <select name="layout" class="form-control">
                    <option {{@$generalSettings->layout == 'LTR' ? 'selected' : ''}} value="LTR">LTR</option>
                    <option {{@$generalSettings->layout == 'RTL' ? 'selected' : ''}} value="RTL">RTL</option>
                </select>
            </div>

            <div class="form-group">
                <label>Correo de Contacto</label>
                <input type="text" class="form-control" name="contact_email" value="{{@$generalSettings->contact_email}}">
            </div>

            <div class="form-group">
                <label>Teléfono de Contacto</label>
                <input type="text" class="form-control" name="contact_phone" value="{{@$generalSettings->contact_phone}}">
            </div>

            <div class="form-group">
                <label>Dirección de Contacto</label>
                <input type="text" class="form-control" name="contact_address" value="{{@$generalSettings->contact_address}}">
            </div>

            <div class="form-group">
                <label>URL de Google Maps</label>
                <input type="text" class="form-control" name="map" value="{{@$generalSettings->map}}">
            </div>

            <hr>

            <div class="form-group">
                <label>Nombre de la Moneda Predeterminada</label>
                <select name="currency_name" class="form-control select2">
                    <option value="">Seleccionar</option>
                    @foreach (config('settings.currecy_list') as $currency)
                        <option {{@$generalSettings->currency_name == $currency ? 'selected' : ''}} value="{{$currency}}">{{$currency}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Icono de la Moneda</label>
                <input type="text" class="form-control" name="currency_icon" value="{{@$generalSettings->currency_icon}}">
            </div>

            <div class="form-group">
                <label>Zona Horaria</label>
                <select name="time_zone" class="form-control select2">
                    <option value="">Seleccionar</option>
                    @foreach (config('settings.time_zone') as $key => $timeZone)
                        <option {{@$generalSettings->time_zone == $key ? 'selected' : ''}} value="{{$key}}">{{$key}}</option>
                    @endforeach
                </select>
            </div>

            {{-- ✅ Nuevo campo para el color del dashboard --}}
            <div class="form-group">
                <label>Color Primario del Dashboard</label>
                <input id="colorInput" type="color" name="color" class="form-control" value="{{@$generalSettings->color ?? '#950D0D'}}">
            </div>

            <button type="submit" class="btn btn-primary">Actualizar</button>
        </form>
    </div>
</div>
</div>

<script>
    function rgbToHex(r, g, b) {
        return "#" + 
            (1 << 24 | r << 16 | g << 8 | b).toString(16).slice(1).toUpperCase();
    }

    document.getElementById('colorInput').addEventListener('input', function(e) {
        var rgb = e.target.value;  // Asume que el valor está en formato RGB.
        var match = rgb.match(/^rgb\s?\(\s?(\d+)\s?,\s?(\d+)\s?,\s?(\d+)\s?\)$/);
        
        if (match) {
            var r = parseInt(match[1], 10);
            var g = parseInt(match[2], 10);
            var b = parseInt(match[3], 10);
            var hexColor = rgbToHex(r, g, b);
            
            document.getElementById('colorInput').value = hexColor; // Actualiza el input de color con el valor hexadecimal.
        }
    });
</script>
