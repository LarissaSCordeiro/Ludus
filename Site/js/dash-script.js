// =================== Alerta de comentário ===================
window.addEventListener("DOMContentLoaded", () => {
  const alertBox = document.querySelector(".mensagem-alerta");

  if (alertBox) {
    setTimeout(() => {
      alertBox.style.opacity = "0";
      alertBox.style.transform = "translateY(-10px)";
      setTimeout(() => alertBox.style.display = "none", 500);
    }, 10000);
  }
});
// =================== Envio de comentário ===================
window.addEventListener("DOMContentLoaded", () => {
  const alertBox = document.querySelector(".mensagem-envio");

  if (alertBox) {
    setTimeout(() => {
      alertBox.style.opacity = "0";
      alertBox.style.transform = "translateY(-10px)";
      setTimeout(() => alertBox.style.display = "none", 500);
    }, 10000);
  }
});
//(Apos carregar volta aonde o usuario estava)
document.addEventListener('DOMContentLoaded', function () {
    const scrollY = localStorage.getItem('scrollY');
    if (scrollY !== null) {
        window.scrollTo(0, parseInt(scrollY));
        localStorage.removeItem('scrollY');
    }

    document.getElementById('btn_comentario').addEventListener('click', function () {
        localStorage.setItem('scrollY', window.scrollY);
        document.getElementById('comentarioForm').submit();
    });
});
