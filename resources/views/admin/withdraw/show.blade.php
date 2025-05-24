@extends('admin.layouts.master')

@section('content')
      <!-- Contenido Principal -->
        <section class="section">
          <div class="section-header">
            <h1>Solicitud de Retiro</h1>
          </div>

          <div class="section-body">
            <div class="invoice">
              <div class="invoice-print">
                <div class="row mt-4">
                  <div class="col-md-12">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td><b>Método de Retiro</b></td>
                                <td>{{ $request->method }}</td>
                            </tr>
                            <tr>
                                <td><b>Comisión de Retiro</b></td>
                                <td>{{ ($request->withdraw_charge / $request->total_amount) * 100 }} %</td>
                            </tr>

                            <tr>
                                <td><b>Monto de Comisión de Retiro</b></td>
                                <td>{{ $settings->currency_icon }} {{ $request->withdraw_charge }}</td>
                            </tr>
                            <tr>
                                <td><b>Monto Total</b></td>
                                <td>{{ $settings->currency_icon }} {{ $request->total_amount }}</td>
                            </tr>
                            <tr>
                                <td><b>Monto a Retirar</b></td>
                                <td>{{ $settings->currency_icon }} {{ $request->withdraw_amount }}</td>
                            </tr>
                            <tr>
                                <td><b>Estado</b></td>
                                <td>
                                    @if ($request->status == 'pending')
                                        <span class="badge bg-warning">Pendiente</span>
                                    @elseif($request->status == 'paid')
                                        <span class="badge bg-success">Pagado</span>
                                    @else
                                        <span class="badge bg-danger">Rechazado</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><b>Información de la Cuenta</b></td>
                                <td>{!! $request->account_info !!}</td>
                            </tr>
                        </tbody>
                    </table>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </section>

        <section class="section">
            <div class="section-body">
              <div class="invoice">
                <div class="invoice-print">
                  <div class="row mt-4">
                    <div class="col-md-4">
                        <form action="{{ route('admin.withdraw.update', $request->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="">Estado</label>
                                <select name="status" class="form-control" id="">
                                    <option @selected($request->status === 'pending') value="pending">Pendiente</option>
                                    <option @selected($request->status === 'paid') value="paid">Pagado</option>
                                    <option @selected($request->status === 'declined') value="declined">Rechazado</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary" type="submit">Actualizar</button>
                            </div>
                        </form>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </section>

@endsection
