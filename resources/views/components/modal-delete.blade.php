<div class="modal" id="modal-delete">
    <div class="modal-content">
        
        <div class="modal-header">
            <h2 class="modal-warning">Atenção!</h2>
            <span class="modal-close">&times;</span>
        </div>
        
        <div class="modal-body">
            <p>Você está prestes a excluir os seguintes registros:</p>
            <ul>
                @foreach ($items ?? [] as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
            <p style="color:red; font-weight:bold;">
                ATENÇÃO: Ao apagar estes dados, todos os demais registros do banco de dados que, de alguma forma, estão associados a esses dados também serão excluídos (exclusão em cascada).
            </p>
        </div>

        <div class="modal-footer">
            <button class="modal-cancel">Cancelar</button>
            <button class="modal-delete">Excluir</button>
        </div>
    </div>
</div>