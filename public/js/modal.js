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

    // Monta a tabela de detalhes
    const tableWrapper = document.createElement('div');
    tableWrapper.classList.add('table-wrapper');

    const table = document.createElement('table');
    table.classList.add('details-table');

    const tbody = document.createElement('tbody');

    const tr = document.createElement('tr');
    const th = document.createElement('th');
    const td = document.createElement('td');
    th.textContent = "Link";
    td.innerHTML = edital.link
            ? `<a href="${edital.link}" target="_blank">Clique aqui</a>`
            : '<span class="muted">Não cadastrado</span>';
    tr.appendChild(th);
    tr.appendChild(td);
    tbody.appendChild(tr);

    table.appendChild(tbody);
    tableWrapper.appendChild(table);
    body.appendChild(tableWrapper);

    // Adiciona o título "Cronograma"
    const h2 = document.createElement('h2');
    h2.textContent = 'Cronograma';
    body.appendChild(h2);

    // Container para as fases
    const container = document.createElement('div');
    container.classList.add('fase-container');

    const tituloFase = {
        inscricao: 'Inscrição',
        resultadoInsc: 'Resultado da inscrição',
        recurso: 'Interposição de recurso',
        resultadoRec: 'Resultado do recurso',
    };

    // Monta as fases
    edital.fases_edital.forEach(fase => {
        const faseDiv = document.createElement('div');
        faseDiv.classList.add('fase');

        const titulo = tituloFase[fase.tipo] || fase.tipo;
        const ordem = fase.ordem ? ` (${fase.ordem}º)` : '';
        const tituloDiv = document.createElement('div');
        tituloDiv.classList.add('fase-titulo');
        tituloDiv.textContent = `${titulo}${ordem}`;

        const dataDiv = document.createElement('div');
        dataDiv.classList.add('fase-data');
        if (fase.data_inicio === fase.data_fim) {
            dataDiv.textContent = formatDate(fase.data_inicio);
        } else {
            dataDiv.textContent = `De ${formatDate(fase.data_inicio)} até ${formatDate(fase.data_fim)}`;
        }

        faseDiv.appendChild(tituloDiv);
        faseDiv.appendChild(dataDiv);
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