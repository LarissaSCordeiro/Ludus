//Java Script de todas para todas as páginas

// =================== Menu Sanduíche ===================
function toggleMenu() {
  const nav = document.getElementById('nav');
  if (nav) {
    nav.classList.toggle('show');
  }
}
// =================== Gerenciamento ===================
 document.querySelectorAll('.abrir-modal').forEach(botao => {
        botao.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const tipo = this.getAttribute('data-tipo');

            const modal = document.getElementById('modal-confirmacao');
            const inputUsu = document.getElementById('input-excluir-usu');
            const inputComent = document.getElementById('input-excluir-coment');

            inputUsu.value = '';
            inputComent.value = '';

            if (tipo === 'usuario') {
                inputUsu.value = id;
            } else if (tipo === 'comentario') {
                inputComent.value = id;
            }

            modal.classList.remove('hidden-force');
        });
    });

    function fecharModal() {
        const modal = document.getElementById('modal-confirmacao');
        modal.classList.add('hidden-force');
    }

