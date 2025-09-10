// Modal
const modal = document.getElementById("modal-delete");
const modalClose = modal.querySelector(".modal-close");
const modalCancel = modal.querySelector(".modal-cancel");
const modalDelete = modal.querySelector(".modal-delete");

// Função para abrir modal com texto 
window.openDeleteModal = function(text, onConfirm) {
    modal.querySelector(".modal-body span").innerText = text;
    modal.style.display = 'flex';

    // Remove event listeners antigos do botão Excluir
    modalDelete.replaceWith(modalDelete.cloneNode(true));
    const newmodalDelete = modal.querySelector(".modal-delete");

    // Adiciona o confirm callback
    newmodalDelete.addEventListener('click', function() {
        onConfirm();
        modal.style.display = 'none';
    });
};

// Fechar modal
modalClose.addEventListener("click", () => modal.style.display = 'none');
modalCancel.addEventListener("click", () => modal.style.display = 'none');
window.addEventListener("click", e => { if(e.target === modal) modal.style.display = 'none'; });