// developer-request.js
// Gerencia a modal de solicitação de desenvolvedor

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('developerModal');
    const btnTornar = document.getElementById('btnTornarDesenvolvedor');
    const modalClose = document.getElementById('modalClose');
    const modalCancel = document.getElementById('modalCancel');
    const developerForm = document.getElementById('developerForm');
    const motivoTextarea = document.getElementById('motivo');
    const charCount = document.getElementById('charCount');
    const submitBtn = document.querySelector('.btn-submit');

    // Atualizar contador de caracteres
    if (motivoTextarea) {
        motivoTextarea.addEventListener('input', function() {
            const currentLength = this.value.length;
            charCount.textContent = `${currentLength}/1000`;
            
            // Mudar cor se perto do limite
            if (currentLength > 900) {
                charCount.style.color = '#ff6b6b';
            } else if (currentLength > 800) {
                charCount.style.color = '#f4961e';
            } else {
                charCount.style.color = '#999';
            }
        });
    }

    // Abrir modal
    if (btnTornar) {
        btnTornar.addEventListener('click', function(e) {
            e.preventDefault();
            modal.classList.add('show');
            motivoTextarea.focus();
        });
    }

    // Fechar modal
    function closeModal() {
        modal.classList.remove('show');
        developerForm.reset();
        charCount.textContent = '0/1000';
        charCount.style.color = '#999';
    }

    if (modalClose) {
        modalClose.addEventListener('click', closeModal);
    }

    if (modalCancel) {
        modalCancel.addEventListener('click', closeModal);
    }

    // Fechar modal ao clicar fora dele
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            closeModal();
        }
    });

    // Fechar com Escape
    window.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && modal.classList.contains('show')) {
            closeModal();
        }
    });

    // Enviar formulário
    if (developerForm) {
        developerForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const motivo = motivoTextarea.value.trim();

            // Validações
            if (motivo.length < 10) {
                LudusToast('O motivo deve ter no mínimo 10 caracteres', true);
                return;
            }

            if (motivo.length > 1000) {
                LudusToast('O motivo não pode exceder 1000 caracteres', true);
                return;
            }

            submitBtn.disabled = true;
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';

            try {
                const formData = new FormData();
                formData.append('motivo', motivo);

                const response = await fetch('request_developer.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.sucesso) {
                    LudusToast(data.mensagem, false);
                    closeModal();
                    
                    if (data.link_teste) {
                        setTimeout(() => {
                            window.location.href = data.link_teste;
                        }, 1500);
                    }
                } else {
                    LudusToast(data.erro || 'Erro ao enviar solicitação', true);
                }
            } catch (error) {
                console.error('Erro:', error);
                LudusToast('Erro ao conectar ao servidor. Tente novamente.', true);
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    }
    
});
