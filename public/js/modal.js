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

    body.innerHTML += `<p><b>Edital:</b> ${edital.nome}</p>`;

    // Container para as fases
    const container = document.createElement('div');
    container.classList.add('fase-container');

    edital.fases_edital.forEach(fase => {
        const faseDiv = document.createElement('div');
        faseDiv.classList.add('fase');

        faseDiv.innerHTML = `
            <div class="fase-titulo">${fase.tipo} (${fase.ordem}º)</div>
            <div class="fase-datas">${formatDate(fase.data_inicio)} até ${formatDate(fase.data_fim)}</div>
        `;
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


// Função para formatar datas dd/mm/yyyy
function formatDate(dateStr) {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
}