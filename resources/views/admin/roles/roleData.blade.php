@extends('admin.layout')

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.roles.role.updateRole', encrypt($rol->id)) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="text-center">
                    <h1>ROL: {{ $rol->name }}</h1>
                </div>
                <div class="row">
                    <div class="input-group">
                        <span class="input-group-text" id="inputGroup-sizing-sm">Nombre</span>
                        <input name="name" type="text" class="form-control" aria-label="Sizing example input"
                            aria-describedby="inputGroup-sizing-sm" value="{{ $rol->name }}" required>
                    </div>
                    <p class="card-text mt-3"><small class="text-body-secondary">Última actualización:
                            {{ $rol->updated_at }}</small>
                    </p>
                </div>
                <hr>
                @php
                    $currentAssignation = json_decode($rol->assignation, true);
                @endphp
                <div class="row mx-auto">
                    <div class="text-center">
                        <h1>Alcance</h2>
                    </div>
                    <div class="col mx-auto">
                        <div class="container text-center mt-3">
                            <div class="row align-items-start">
                                <div class="col">
                                    <div class="text-center">
                                        <h3>Usuarios</h3>
                                    </div>
                                    <ul class="list-group text-start">
                                        @foreach ($users as $user)
                                            <li class="list-group-item list-group-item-action list-group-item-info">
                                                <input name="user_{{ $user->id }}" value="user_{{ $user->id }}"
                                                    class="form-check-input me-1" type="checkbox"
                                                    id="user{{ $user->id }}"
                                                    @if ($currentAssignation) 
                                                        @if (in_array($user->id, $currentAssignation))
                                                            checked 
                                                        @endif
                                                    @endif>
                                                    
                                                <label class="form-check-label stretched-link"
                                                    for="user{{ $user->id }}"> {{ $user->name }} </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success float-end mt-3">Guardar</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection
