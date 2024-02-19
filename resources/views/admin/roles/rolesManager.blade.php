@extends('admin.layout')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="text-center">
                <h1>ROLES</h1>
            </div>
            {{-- button new card modal --}}
            <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#newRolModal">
                Nuevo Rol
            </button>
            <div>
                <ul class="list-group">
                    @foreach ($roles as $rol)
                        <div class="input-group">
                            <a href="{{ Route('admin.roles.role.show', encrypt($rol)) }}"
                                class="list-group-item list-group-item-action list-group-item-info col mb-1">
                                {{$rol->id}}.- {{ $rol->name }}</a>
                            <button type="button" class="btn btn-danger mb-1 " data-bs-toggle="modal"
                                data-bs-target="#deleteRol{{ $rol->id }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>


                        <!-- Modal delete role -->
                        <div class="modal fade" id="deleteRol{{ $rol->id }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-4" id="exampleModalLabel">¿Está seguro de eliminar el rol {{$rol->id}}?</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="text-danger fw-bold">Los datos no se podrán recuperar</p>
                                    </div>
                                    <div class="modal-footer">
                                        <form action="{{route('admin.roles.deleteRole',encrypt($rol->id))}}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')

                                            <button type="button" class="btn btn-primary"
                                                data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-danger">Eliminar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>


    <!-- Modal new role -->
    <div class="modal fade modal-lg" id="newRolModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Nuevo rol</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.roles.newRole') }}" id="update_product_info" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="col mx-auto">
                            <div class="card-body p-0">
                                <div class="input-group input-group">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">Nombre</span>
                                    <input name="name" type="text" class="form-control"
                                        aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" required>
                                </div>
                            </div>

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