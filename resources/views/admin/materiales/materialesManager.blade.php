@extends('admin.layout')

@section('content')
    <div class="card">
        <div class="card-body overflow-x-auto">
            <h1>MATERIALES</h1>
            <button type="button" class="btn btn-primary my-3 float-end" data-bs-toggle="modal" data-bs-target="#newUserModal">
                <i class="bi bi-plus-circle-dotted" style="font-size: 1.5rem;"></i>
            </button>
            <table class="table table-bordered" id="materiales">
                <thead>
                    <tr class="bg-primary text-light">
                        <th scope="col">Id</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Unidad de medida</th>
                        <th scope="col">Cantidad</th>
                        <th scope="col">Costo Total</th>
                        <th scope="col">Costo/Ud medida</th>
                        <th scope="col">Options</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($materiales as $material)
                        <tr>
                            <td>{{ $material->id }}</td>
                            <td>{{ $material->nombre }}</td>
                            <td>{{ $material->ud_medida }}</td>
                            <td>{{ $material->cantidad }}</td>
                            <td>{{ $material->costo_total }}</td>
                            <td>{{ $material->costo_ud_medida }}</td>
                            <td>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modalEditarMaterial{{ $material->id }}">
                                    Editar
                                </button>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#modalDeleteMaterial{{ $material->id }}">
                                    Eliminar
                                </button>
                            </td>
                        </tr>



                        <!-- Modal neditar Material -->
                        <div class="modal fade modal-lg" id="modalEditarMaterial{{ $material->id }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-light">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Editar material:
                                            {{ $material->nombre }}</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>

                                    <form action="{{ route('admin.materiales.edit', encrypt($material->id)) }}"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">

                                            <div class="input-group input-group mb-3">
                                                <label for="nombre" class="input-group-text bg-primary-subtle"
                                                    id="inputGroup-sizing-sm">{{ __('nombre') }}</label>

                                                <input id="nombre" type="text" aria-label="Sizing example input"
                                                    aria-describedby="inputGroup-sizing-sm"
                                                    class="form-control @error('nombre') is-invalid @enderror"
                                                    name="nombre" value="{{ $material->nombre }}" autocomplete="nombre"
                                                    autofocus title="Este campo es obligatorio">

                                                @error('nombre')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror

                                            </div>

                                            <div class="input-group input-group mb-3">
                                                <label for="ud_medida" class="input-group-text bg-primary-subtle"
                                                    id="inputGroup-sizing-sm">Unidad de Medida</label>

                                                <select name="ud_medida"
                                                    class="form-select @error('ud_medida') is-invalid @enderror"
                                                    id="ud_medida" title="Este campo es obligatorio">
                                                    <option selected value="">Elegir...</option>
                                                    <option @if ($material->ud_medida == 'kg') selected @endif
                                                        value="kg">Kilogramo (kg) </option>
                                                    <option @if ($material->ud_medida == 'm2') selected @endif
                                                        value="m2">Metro Cuadrado (m^2) </option>
                                                    <option @if (old('ud_medida') == 'unidad') selected @endif
                                                        value="unidad">Unidad (unidad)</option>
                                                    <option @if (old('ud_medida') == 'm') selected @endif
                                                        value="m">Metro (m)</option>
                                                    id="ud_medida">
                                                    <option value = "">Elegir...</option>
                                                    <option @if ($material->ud_medida == 'kg') selected @endif value="kg">Kilogramo (kg) </option>
                                                    <option @if ($material->ud_medida == 'm2') selected @endif value="m2">Metro Cuadrado (m^2) </option>
                                                    <option @if (old('ud_medida') == 'unidad') selected @endif value="unidad">Unidad (unidad)</option>
                                                    <option @if (old('ud_medida') == 'm') selected @endif value="m">Metro (m)</option>
                                                </select>
                                                @error('ud_medida')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="form-floating mb-3">
                                                <input id="cantidad" type="number" min="0"
                                                    class="form-control @error('cantidad') is-invalid @enderror"
                                                    name="cantidad" value="{{ $material->cantidad }}"
                                                    autocomplete="cantidad" oninput="validarMontoInput(this)"
                                                    placeholder="Insertar el costo">
                                                <label for="cantidad">Cantidad</label>


                                                @error('cantidad')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="input-group mb-3">
                                                <div class="form-floating">
                                                    <input id="costo_total" type="text" min="0"
                                                        class="form-control @error('costo_total') is-invalid @enderror"
                                                        name="costo_total" value="{{ $material->costo_total }}"
                                                        autocomplete="costo_total" oninput="validarMontoInput(this)"
                                                        placeholder="Insertar el costo">
                                                    <label for="costo_total">Costo Total</label>
                                                </div>
                                                <label class="input-group-text">$</label>

                                                @error('costo_total')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            {{-- <div class="input-group input-group mb-3">
                                                <label for="costo_por_ud_medida"
                                                    class="input-group-text bg-primary-subtle"
                                                    id="inputGroup-sizing-sm">Costo por Ud. Medida</label>
                                                <input id="costo_por_ud_medida" type="text"
                                                    class="form-control @error('costo_por_ud_medida') is-invalid @enderror"
                                                    name="costo_por_ud_medida" value="{{ $material->costo_ud_medida }}"
                                                    autocomplete="costo_por_ud_medida" oninput="validarMontoInput(this)"
                                                    title="Este campo es obligatorio">
                                                <label for="nombre" class="input-group-text bg-primary-subtle"
                                                    id="inputGroup-sizing-sm">$</label>
                                                @error('costo_por_ud_medida')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror

                                            </div> --}}

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>


                        <!-- Delete Modal -->
                        <div class="modal fade" id="modalDeleteMaterial{{ $material->id }}" data-bs-backdrop="static"
                            data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="staticBackdropLabel">¿Está seguro de eliminar el
                                            material:
                                            {{ $material->nombre }}?</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="text-danger fw-bold">Los datos no se podrán recuperar</p>
                                    </div>
                                    <div class="modal-footer">
                                        <form action="{{ route('admin.materiales.destroy', encrypt($material->id)) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-primary"
                                                data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="float-right ml-2 btn btn-danger">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal new Material -->
    <div class="modal fade modal-lg" id="newUserModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-light">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Nuevo material</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('admin.materiales.create') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="modal-body">

                        <div class="form-floating mb-3">

                            <input id="nombre" type="text"
                                class="form-control @error('nombre') is-invalid @enderror" name="nombre"
                                value="{{ old('nombre') }}" autocomplete="nombre" autofocus
                                title="Este campo es obligatorio" placeholder="Ingrese el nombre">
                            <label for="nombre">Nombre</label>

                            @error('nombre')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                        <div class="input-group input-group mb-3">
                            <label for="ud_medida" class="input-group-text bg-primary-subtle"
                                id="inputGroup-sizing-sm">Unidad de Medida</label>

                            <select name="ud_medida" class="form-select @error('ud_medida') is-invalid @enderror"
                                id="ud_medida" title="Este campo es obligatorio">
                                <option selected value="">Elegir...</option>
                                <option @if (old('ud_medida') == 'kg') selected @endif value="kg">Kilogramo (kg)
                                </option>
                                <option @if (old('ud_medida') == 'm2') selected @endif value="m2">Metro Cuadrado
                                    (m^2)</option>
                                <option @if (old('ud_medida') == 'unidad') selected @endif value="unidad">Unidad (unidad)
                                </option>
                                <option @if (old('ud_medida') == 'm') selected @endif value="m">Metro (m)
                                </option>
                            </select>
                            @error('ud_medida')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <input id="cantidad" type="number" min="0"
                                class="form-control @error('cantidad') is-invalid @enderror" name="cantidad"
                                value="{{ old('cantidad') }}" autocomplete="cantidad" oninput="validarMontoInput(this)"
                                placeholder="Insertar el costo">
                            <label for="cantidad">Cantidad</label>


                            @error('cantidad')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="input-group mb-3">
                            <div class="form-floating">
                                <input id="costo_total" type="text" min="0"
                                    class="form-control @error('costo_total') is-invalid @enderror" name="costo_total"
                                    value="{{ old('costo_total') }}" autocomplete="costo_total"
                                    oninput="validarMontoInput(this)" placeholder="Insertar el costo">
                                <label for="costo_total">Costo Total</label>
                            </div>
                            <label class="input-group-text">$</label>

                            @error('costo_total')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>


                        {{-- <div class="input-group input-group mb-3">
                            <label for="costo_por_ud_medida" class="input-group-text bg-primary-subtle"
                                id="inputGroup-sizing-sm">Costo por Ud. Medida</label>
                            <input id="costo_por_ud_medida" type="text"
                                class="form-control @error('costo_por_ud_medida') is-invalid @enderror"
                                name="costo_por_ud_medida" value="{{ old('costo_por_ud_medida') }}"
                                autocomplete="costo_por_ud_medida" oninput="validarMontoInput(this)"
                                title="Este campo es obligatorio">
                            <label for="nombre" class="input-group-text bg-primary-subtle"
                                id="inputGroup-sizing-sm">$</label>
                            @error('costo_por_ud_medida')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div> --}}

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
