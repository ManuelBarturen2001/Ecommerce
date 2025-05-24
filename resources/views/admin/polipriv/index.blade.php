@extends('admin.layouts.master')

@section('content')
      <!-- Main Content -->
        <section class="section">
          <div class="section-header">
            <h1>Politicas y Privacidad</h1>
          </div>

          <div class="section-body">

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">

                  </div>
                  <div class="card-body">
                    <form action="{{route('admin.politica-and-privacidad.update')}}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label>Contentido</label>
                            <textarea name="content" class="summernote">{!!@$content->content!!}</textarea>
                        </div>

                        <button type="submmit" class="btn btn-primary">Modificar</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </section>

@endsection