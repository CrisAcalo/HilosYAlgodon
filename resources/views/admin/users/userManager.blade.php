@extends('admin.layout')

@section('content')
    <div class="card">
        <div class="card-body">
            <h1>USUARIOS</h1>
            <button type="button" class="btn btn-primary my-3 float-end" data-bs-toggle="modal" data-bs-target="#newUserModal">
                <i class="bi bi-person-fill-add ms-1" style="font-size: 1.5rem;"></i>
            </button>
            <table class="table table-bordered">
                <thead>
                    <tr class="bg-primary text-light">
                        <th scope="col">Id</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Roles</th>
                        <th scope="col">Options</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    @php
                        $roles = $user->assignatedRoles(encrypt($user->id));
                    @endphp
                        <tr>
                            <th scope="row">{{ $user->id }}</th>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if ($roles)
                                    @foreach ($roles as $rol)
                                        {{ $rol }}-
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                <a href="{{ Route('admin.users.show', encrypt($user->id)) }}" class="btn btn-info">
                                    Editar
                                </a>
                                <a href="{{ Route('admin.impersonate', $user->id) }}" class="btn btn-primary">
                                    Ver
                                </a>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#deleteUserModal{{ $user->id }}">
                                    Eliminar
                                </button>
                            </td>
                        </tr>

                        <!-- Delete Modal -->
                        <div class="modal fade" id="deleteUserModal{{ $user->id }}" data-bs-backdrop="static"
                            data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="staticBackdropLabel">¿Está seguro de eliminar a
                                            {{ $user->name }}?</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="text-danger fw-bold">Los datos no se podrán recuperar</p>
                                    </div>
                                    <div class="modal-footer">
                                        <form action="{{ route('admin.users.destroy', encrypt($user->id)) }}"
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
            {{ $users->links() }}
        </div>
    </div>

    <!-- Modal new user -->
    <div class="modal fade modal-lg" id="newUserModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-light">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Nuevo usuario</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="modal-body">

                        <div class="input-group input-group mb-3">
                            <label for="name" class="input-group-text bg-primary-subtle"
                                id="inputGroup-sizing-sm">{{ __('Name') }}</label>


                            <input id="name" type="text" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-sm"
                                class="form-control @error('name') is-invalid @enderror" name="name"
                                value="{{ old('name') }}" required autocomplete="name" autofocus>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                        <div class="input-group input-group mb-3">
                            <label for="email" class="input-group-text bg-primary-subtle"
                                id="inputGroup-sizing-sm">{{ __('E-Mail Address') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required autocomplete="email">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                        <div class="input-group input-group mb-3">
                            <label for="password" class="input-group-text bg-primary-subtle"
                                id="inputGroup-sizing-sm">{{ __('Password') }}</label>
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password" required
                                autocomplete="new-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>
                        <div class="input-group input-group mb-3">
                            <label for="password-confirm" class="input-group-text bg-primary-subtle"
                                id="inputGroup-sizing-sm">{{ __('Confirm Password') }}</label>
                            <input id="password-confirm" type="password" class="form-control"
                                name="password_confirmation" required autocomplete="new-password">
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
