@extends('admin.layout')

@section('content')
    <div class="card">
        <div class="card-body">

            <div class="card">
                <div class="card-header bg-info">
                    <h1>Perfil de {{ $user->name }}</h1>
                </div>
                <div class="card-body">
                    <div class="col mx-auto">
                        <form action="{{ route('admin.users.update', encrypt($user->id)) }}" id="update_product_info"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="col mx-auto">
                                <div class="card-body p-0">
                                    <div class="input-group input-group mb-3">
                                        <span class="input-group-text bg-info-subtle"
                                            id="inputGroup-sizing-sm">Nombre</span>
                                        <input name="name" type="text" class="form-control"
                                            aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"
                                            value="{{ $user->name }}">
                                    </div>

                                    <div class="input-group input-group mb-3">
                                        <span class="input-group-text bg-info-subtle"
                                            id="inputGroup-sizing-sm">Correo</span>
                                        <input name="email" type="text" class="form-control"
                                            aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"
                                            placeholder="{{ $user->email }}">
                                    </div>

                                    <p class="card-text"><small class="text-muted">Modificado por última vez:
                                            {{ $user->updated_at }}</small></p>
                                </div>
                                <button type="submit" class="btn btn-info float-end">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            <div class="card mx-auto mt-3 p-3">
                <h3>Roles</h3>
                <form action="{{ route('admin.updateUserRoles', encrypt($user->id)) }}" id="update_product_info"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <ul class="list-group text-start">
                        @foreach ($roles as $rol)
                            @php
                                $currentAssignation = json_decode($rol->assignation, true);
                            @endphp
                            <li class="list-group-item list-group-item-action list-group-item-info">
                                <input name="rol_{{ $rol->id }}" value="rol_{{ $rol->id }}"
                                    class="form-check-input me-1" type="checkbox" id="rol{{ $rol->id }}"
                                    @if ($currentAssignation) @if (in_array($user->id, $currentAssignation))
                                            checked @endif
                                    @endif>

                                <label class="form-check-label stretched-link" for="rol{{ $rol->id }}">
                                    {{ $rol->name }} </label>
                            </li>
                        @endforeach
                    </ul>
                    <button type="submit" class="btn btn-info mt-3 float-end">Guardar</button>
                </form>
            </div>
        </div>
    </div>
@endsection
