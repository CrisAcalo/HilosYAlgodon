@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Log In verification</div>

                <div class="card-body text-center">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <p>{{ __('You are logged in!') }}</p>
                    <p>Pulsa el siguiente botón para ingresar al sistema</p>
                    <a href="{{ Route('admin.principal') }}" class="btn btn-primary">Wepage Administrator</a>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
