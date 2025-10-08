// Comportamentos das janelas modais
const modalDelete = document.getElementById("modal-delete");
const modalEditais = document.getElementById("modal-editais");
const modalClose = document.querySelectorAll(".modal-close");
const modalCancel = modalDelete.querySelector(".modal-cancel");
const modalConfirm = modalDelete.querySelector(".modal-confirm");

// Função para abrir modal de exclusão
window.openModalDelete = function(text, onConfirm) {
    modalDelete.querySelector(".modal-body span").innerText = text;
    modalDelete.style.display = 'flex';

    // Remove event listeners antigos do botão Excluir
    modalConfirm.replaceWith(modalConfirm.cloneNode(true));
    const newmodalConfirm = modalDelete.querySelector(".modal-confirm");

    newmodalConfirm.addEventListener('click', function() {
        onConfirm();
        modalDelete.style.display = 'none';
    });
};

// Função para abrir modal de editais
window.openModalEditais = function(edital) {
    const body = modalEditais.querySelector(".modal-body");
    body.innerHTML = '';

    body.innerHTML += `<p><b>Edital:</b> &nbsp;${edital.nome}</p>`;
    if(edital.link) {
        body.innerHTML += `<p><b>Link:</b> &nbsp;<a href="${edital.link}" target="_blank">Clique aqui</a></p>`;
    } else{
        body.innerHTML += `<p><b>Link:</b> &nbsp;Não cadastrado.</p>`;
    }

    // Container para as fases
    const container = document.createElement('div');
    container.classList.add('fase-container');
    const tituloFase = {"inscricao": "Inscrição", "resultadoInsc": "Resultado da inscrição", "recurso": "Interposição de recurso", "resultadoRec": "Resultado do recurso"};

    edital.fases_edital.forEach(fase => {
        const faseDiv = document.createElement('div');
        faseDiv.classList.add('fase');

        faseDiv.innerHTML = `
            <div class="fase-titulo">${tituloFase[fase.tipo]} (${fase.ordem}º)</div>
        `;

        if(fase.data_inicio == fase.data_fim) {
            faseDiv.innerHTML += `<div class="fase-data">${formatDate(fase.data_inicio)}</div>`;
        } else {
            faseDiv.innerHTML += `<div class="fase-data">De ${formatDate(fase.data_inicio)} até ${formatDate(fase.data_fim)}</div>`;
        }

        container.appendChild(faseDiv);
    });

    body.appendChild(container);
    modalEditais.style.display = 'flex';
};


// Fechar modais com X
modalClose.forEach((btn) => btn.addEventListener("click", function() {
    if(modalDelete) modalDelete.style.display = 'none';
    if(modalEditais) modalEditais.style.display = 'none';
}));

// Fechar modal delete com Cancel
modalCancel.addEventListener("click", () => modalDelete.style.display = 'none');

// Fechar modal clicando fora
window.addEventListener("click", e => { 
    if(e.target === modalDelete) modalDelete.style.display = 'none';
    if(e.target === modalEditais) modalEditais.style.display = 'none'; 
});

function formatDate(dateStr) {
    if (!dateStr) return '';
    // Espera formato yyyy-mm-dd
    const [year, month, day] = dateStr.split('-');
    return `${day}/${month}/${year}`;
}