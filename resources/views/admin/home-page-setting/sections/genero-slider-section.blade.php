@php
    $sliderSectionGenero = json_decode($sliderSectionGenero->value);
    $selectedGender1 = isset($sliderSectionGenero[0]) ? $sliderSectionGenero[0]->gender ?? null : null;
    $selectedGender2 = isset($sliderSectionGenero[1]) ? $sliderSectionGenero[1]->gender ?? null : null;
@endphp

<div class="tab-pane fade" id="list-slider-genero" role="tabpanel" aria-labelledby="list-settings-list">
    <div class="card border">
        <div class="card-body">
            <form action="{{ route('admin.genero-slider-section') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="mb-3">Part 1</h5>
                        <div class="form-group">
                            <label for="cat_one">Género</label>
                            <select name="cat_one" id="cat_one" class="form-control">
                                <option value="">Seleccionar</option>
                                @foreach ($genders as $gender)
                                    <option {{ $gender->id == $selectedGender1 ? 'selected' : '' }} value="{{ $gender->id }}">{{ $gender->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="mb-3">Part 2</h5>
                        <div class="form-group">
                            <label for="cat_two">Género</label>
                            <select name="cat_two" id="cat_two" class="form-control">
                                <option value="">Seleccionar</option>
                                @foreach ($genders as $gender)
                                    <option {{ $gender->id == $selectedGender2 ? 'selected' : '' }} value="{{ $gender->id }}">{{ $gender->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <button type="submit" class="btn btn-sm btn-primary px-4">Modificar</button>
                </div>
            </form>
        </div>
    </div>
</div>
