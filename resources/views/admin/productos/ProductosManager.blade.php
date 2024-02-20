@extends('admin.layout')

@section('content')
    <div class="card">
        <div class="card-body overflow-x-auto">
            <h1>PRODUCTOS</h1>
            <button type="button" class="btn btn-primary my-3 float-end" data-bs-toggle="modal" data-bs-target="#newUserModal">
                <i class="bi bi-plus-circle-dotted" style="font-size: 1.5rem;"></i>
            </button>
            <table class="table table-bordered" id="productos">
                <thead>
                    <tr class="bg-primary text-light">
                        <th scope="col">Id</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Descipcion</th>
                        <th scope="col">Costo Unitario</th>
                        <th scope="col">Cantidad</th>
                        <th scope="col">Horas de Trabajo</th>
                        <th scope="col">OP</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productos as $producto)
                        <tr>
                            <td>{{ $producto->id }}</td>
                            <td>{{ $producto->nombre }}</td>
                            <td>{{ $producto->descripcion }}</td>
                            <td>{{ $producto->costo_unitario }}</td>
                            <td>{{ $producto->cantidad }}</td>
                            <td>{{ $producto->horas_trabajo }}</td>
                            <td>
                                <a href="{{ route('admin.productos.details', encrypt($producto->id)) }}"
                                    class="btn btn-primary">
                                    Editar
                                </a>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#modalDeleteMaterial{{ $producto->id }}">
                                    Eliminar
                                </button>
                            </td>
                        </tr>

                        <!-- Delete Modal -->
                        <div class="modal fade" id="modalDeleteMaterial{{ $producto->id }}" data-bs-backdrop="static"
                            data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="staticBackdropLabel">¿Está seguro de eliminar el
                                            producto:
                                            {{ $producto->nombre }}?</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="text-danger fw-bold">Los datos no se podrán recuperar</p>
                                    </div>
                                    <div class="modal-footer">
                                        <form action="{{ route('admin.productos.destroy', encrypt($producto->id)) }}"
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

    <!-- Modal new Producto -->
    <div class="modal fade modal-lg" id="newUserModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-light">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Nuevo producto</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('admin.productos.create') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="modal-body">

                        <div class="form-floating mb-3">
                            <input id="nombre" type="text" class="form-control @error('nombre') is-invalid @enderror"
                                name="nombre" value="{{ old('nombre') }}" autocomplete="nombre"
                                placeholder="Almohada">

                            <label for="nombre">{{ __('Nombre') }}</label>

                            @error('nombre')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" placeholder="Leave a comment here"
                                id="descripcion" name="descripcion"></textarea>
                            <label for="descripcion">Descripcion</label>
                            @error('descripcion')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="input-group mb-3">
                            <div class="form-floating">
                                <input disabled id="costo_unitario" type="text" min="0"
                                    class="form-control @error('costo_unitario') is-invalid @enderror" name="costo_unitario"
                                    value="{{ old('costo_unitario') }}" autocomplete="costo_unitario"
                                    oninput="validarMontoInput(this)" placeholder="Insertar el costo">
                                <label for="costo_unitario">Costo Unitario</label>
                            </div>
                            <label class="input-group-text">$</label>

                            @error('costo_unitario')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <input id="cantidad" type="number" min="0"
                                class="form-control @error('cantidad') is-invalid @enderror" name="cantidad"
                                value="{{ old('cantidad') }}" autocomplete="cantidad"
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
                                value="{{ old('horas_trabajo') }}" autocomplete="horas_trabajo"
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
