// Toast de notificação global Ludus
(function() {
  if (window.LudusToast) return; // Evita duplicidade

  // Adiciona HTML do toast se não existir
  if (!document.getElementById('toast')) {
    const toastDiv = document.createElement('div');
    toastDiv.id = 'toast';
    toastDiv.className = 'toast hidden';
    toastDiv.innerHTML = `
      <div class="toast-content">
        <span id="toast-icon" class="toast-icon">✔️</span>
        <p id="toast-message">Mensagem</p>
      </div>
    `;
    document.body.appendChild(toastDiv);
  }

  // Adiciona CSS global do toast se não existir
  if (!document.getElementById('toast-style')) {
    const style = document.createElement('style');
    style.id = 'toast-style';
    style.innerHTML = `
.toast {
  position: fixed;
  bottom: 30px;
  left: 50%;
  transform: translateX(-50%);
  background-color: #2ecc71;
  color: white;
  padding: 14px 24px;
  border-radius: 12px;
  font-size: 16px;
  z-index: 9999;
  opacity: 0;
  pointer-events: none;
  transition: opacity 0.4s ease-in-out, transform 0.4s ease;
  box-shadow: 0 8px 16px rgba(0,0,0,0.25);
}
.toast.show {
  opacity: 1;
  pointer-events: auto;
  transform: translateX(-50%) translateY(-10px);
}
.toast.error {
  background-color: #e74c3c;
}
.toast-content {
  display: flex;
  align-items: center;
  gap: 10px;
}
.toast-icon {
  font-size: 20px;
  animation: pop 0.3s ease;
}
@keyframes pop {
  0% { transform: scale(0.8); opacity: 0; }
  100% { transform: scale(1); opacity: 1; }
}
`;
    document.head.appendChild(style);
  }

  // Função global para mostrar toast
  window.LudusToast = function(msg, isError = false) {
    const toast = document.getElementById('toast');
    const icon = document.getElementById('toast-icon');
    const text = document.getElementById('toast-message');
    if (!toast || !icon || !text) return;
    text.textContent = msg;
    toast.classList.remove('hidden');
    toast.classList.add('show');
    if (isError) {
      toast.classList.add('error');
      icon.textContent = '❌';
    } else {
      toast.classList.remove('error');
      icon.textContent = '✔️';
    }
    icon.style.animation = 'none';
    void icon.offsetWidth;
    icon.style.animation = null;
    setTimeout(() => {
      toast.classList.remove('show');
    }, 3000);
  };
})();
