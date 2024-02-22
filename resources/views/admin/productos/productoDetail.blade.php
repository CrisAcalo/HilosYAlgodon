@extends('admin.layout')

@section('content')
    <div class="d-flex flex-wrap w-100 justify-content-center">
        <div class="card mb-3 flex-fill">
            <div class="card-header bg-info">
                <h4>Perfil del producto: {{ $producto->nombre }}</h4>
            </div>
            <div class="card-body">
                <div class="col mx-auto">
                    <form action="{{ route('admin.productos.edit', encrypt($producto->id)) }}" id="update_product_info"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="modal-body">

                            <div class="form-floating mb-3">
                                <input id="nombre" type="text"
                                    class="form-control @error('nombre') is-invalid @enderror" name="nombre"
                                    value="{{ $producto->nombre }}" required autocomplete="nombre" placeholder="Almohada">

                                <label for="nombre">{{ __('Nombre') }}</label>

                                @error('nombre')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('descripcion') is-invalid @enderror"
                                    placeholder="Leave a comment here" id="descripcion" name="descripcion"
                                    value="{{ $producto->descripcion }}">
                                <label for="descripcion">Descripcion</label>
                                @error('descripcion')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="input-group">
                                <div class="form-floating">
                                    <input disabled id="costo_unitario" type="text" min="0"
                                        class="form-control @error('costo_unitario') is-invalid @enderror"
                                        name="costo_unitario" value="{{ $producto->costo_unitario }}" required
                                        autocomplete="costo_unitario" oninput="validarMontoInput(this)"
                                        placeholder="Insertar el costo">
                                    <label for="costo_unitario">Costo Unitario</label>
                                </div>
                                <label class="input-group-text">$</label>

                                @error('costo_unitario')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                
                            </div>
                            <label class=" mb-3 text-secondary">Valor por hora de trabajo: {{round($configuraciones->sueldo_base/30/8,2)}}</label>

                            <div class="form-floating mb-3">
                                <input id="cantidad" type="number" min="0"
                                    class="form-control @error('cantidad') is-invalid @enderror" name="cantidad"
                                    value="{{ $producto->cantidad }}" required autocomplete="cantidad"
                                    oninput="validarMontoInput(this)" placeholder="Insertar el costo">
                                <label for="cantidad">Cantidad</label>


                                @error('cantidad')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input id="horas_trabajo" type="number" min="0"
                                    class="form-control @error('horas_trabajo') is-invalid @enderror" name="horas_trabajo"
                                    value="{{ $producto->horas_trabajo }}" required autocomplete="horas_trabajo"
                                    oninput="validarMontoInput(this)" placeholder="Insertar el costo">
                                <label for="horas_trabajo">Horas de trabajo</label>


                                @error('horas_trabajo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary me-3" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-info">Guardar</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <div class="card mb-3 mx-3">
            <div class="card-header bg-info">
                <h4>Asignacion de Materiales</h4>
            </div>
            <div class="card-body">
                <div class="text-end mb-3">
                    <button href="" class="btn btn-success px-2" data-bs-toggle="modal"
                        data-bs-target="#newRequirementsModal">
                        <i class="bi bi-plus-lg"></i> Modificar Materiales
                    </button>
                </div>

                <form action="{{ route('admin.productos.editCantidadMateriales', encrypt($producto->id)) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <ul class="list-group text-start">
                        @foreach ($materialesAsignados as $asignacion)
                            @php
                                $material = $asignacion->getMaterial(encrypt($asignacion->material_id));
                            @endphp
                            <div class="input-group">
                                <span class="input-group-text" for="asignacion_{{ $asignacion->id }}">
                                    {{ $material->nombre }} </span>
                                <input name="{{ $asignacion->id }}" min="0" step="0.001" type="number"
                                    id="asignacion_{{ $asignacion->id }}" class="form-control"
                                    oninput="validarMontoInput(this)" value="{{ $asignacion->cantidad }}" required>
                                <span class="input-group-text" for="asignacion_{{ $asignacion->id }}">
                                    {{ $material->ud_medida }} </span>
                                <span class="input-group-text" for="asignacion_{{ $asignacion->id }}">
                                    {{ $material->costo_ud_medida }}$ c/u </span>
                            </div>
                        @endforeach
                    </ul>
                    <div class="modal-footer mt-3">
                        <button type="button" class="btn btn-secondary me-3" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Asignar</button>
                    </div>
                </form>

            </div>
        </div>

    </div>

    <!-- MODAL PARA AÑADIR MATERIALES -->
    <div class="modal fade modal-md" id="newRequirementsModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modificar Materiales</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.productos.editAsignacionMateriales', encrypt($producto->id)) }}"
                        id="update_product_info" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="col mx-auto">
                            <table class="table table-bordered w-100" id="asignarMateriales">
                                <thead>
                                    <tr class="bg-primary text-light">
                                        <th>Nombre</th>
                                        <th>OP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($materiales as $material)
                                        <tr>
                                            <td>{{ $material->nombre }}</td>
                                            <td>
                                                <input name="material_{{ $material->id }}"
                                                    value="material_{{ $material->id }}" class="form-check-input"
                                                    type="checkbox" id="material_{{ $material->id }}"
                                                    @if ($idsMaterialesAsignados) @if (in_array($material->id, $idsMaterialesAsignados))
                                                    checked @endif
                                                    @endif>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Añadir</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
