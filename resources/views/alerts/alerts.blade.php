@if(session('aviso'))
<div id="uno" class="modal-window">
    <div>
        <a href="#" title="Close" class="modal-close">Cerrar</a>
        <h1>Aviso!</h1>
        <div class="justify">
            {{session('aviso')}}
            <p>¡Gracias por su confianza!</p>
        </div>
    </div>
</div>

@endif