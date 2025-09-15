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
//Data comentario em tempo real
function atualizarTempos() {
    const elementos = document.querySelectorAll("[data-comentario]");

    elementos.forEach(el => {
        const timestamp = parseInt(el.getAttribute("data-comentario")) * 1000;
        const agora = new Date().getTime();
        const diffSegundos = Math.floor((agora - timestamp) / 1000);

        let texto = "";

        if (diffSegundos < 60) {
            texto = `agora`;
        } else if (diffSegundos < 3600) {
            const minutos = Math.floor(diffSegundos / 60);
            texto = `há ${minutos} minuto${minutos !== 1 ? "s" : ""}`;
        } else if (diffSegundos < 86400) {
            const horas = Math.floor(diffSegundos / 3600);
            texto = `há ${horas} hora${horas !== 1 ? "s" : ""}`;
        } else if (diffSegundos < 31536000) {
            const dias = Math.floor(diffSegundos / 86400);
            texto = `há ${dias} dia${dias !== 1 ? "s" : ""}`;
        } else {
            const anos = Math.floor(diffSegundos / 31536000);
            texto = `há ${anos} ano${anos !== 1 ? "s" : ""}`;
        }

        el.textContent = texto;
    });
}

setInterval(atualizarTempos, 1000);
document.addEventListener("DOMContentLoaded", atualizarTempos);


