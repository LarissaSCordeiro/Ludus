// ======================== Carrossel ========================
const carousel = document.getElementById('carouselImages');
const images = carousel.querySelectorAll('img');
const dotsContainer = document.getElementById('dotsContainer');
const total = images.length;
let index = 0;
let interval;

// Criar os dots
for (let i = 0; i < total; i++) {
  const dot = document.createElement('div');
  dot.classList.add('dot');
  if (i === 0) dot.classList.add('active');
  dot.addEventListener('click', () => goToSlide(i));
  dotsContainer.appendChild(dot);
}

const updateDots = () => {
  document.querySelectorAll('.dot').forEach((dot, i) => {
    dot.classList.toggle('active', i === index);
  });
};

const goToSlide = (i) => {
  index = i;
  carousel.style.transform = `translateX(-${index * 100}vw)`;
  updateDots();
  resetAutoplay();
};

const nextSlide = () => {
  index = (index + 1) % total;
  goToSlide(index);
};

const prevSlide = () => {
  index = (index - 1 + total) % total;
  goToSlide(index);
};

document.querySelector('.next').addEventListener('click', nextSlide);
document.querySelector('.prev').addEventListener('click', prevSlide);

const startAutoplay = () => {
  interval = setInterval(nextSlide, 8000);
};

const resetAutoplay = () => {
  clearInterval(interval);
  startAutoplay();
};

startAutoplay();

// =================== Menu Sanduíche ===================
function toggleMenu() {
  const nav = document.getElementById('nav');
  nav.classList.toggle('show');
}
// =================== Bloqueio ao atualizar a página(filtro) ===================
document.addEventListener("DOMContentLoaded", function () {
  const generoForm = document.querySelector(".categoria_genero form");
  const jogosContainer = document.querySelector(".jogos");
  const tituloSeletor = document.querySelector(".categoria h2");

  generoForm.addEventListener("click", function (e) {
    if (e.target.tagName === "BUTTON") {
      e.preventDefault();
      const genero = e.target.value;

      fetch("filtragem.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "btn=" + encodeURIComponent(genero)
      })
        .then(response => response.text())
        .then(data => {
          const parser = new DOMParser();
          const doc = parser.parseFromString(data, "text/html");
          const novosJogos = doc.querySelector(".jogos");
          const novoTitulo = doc.querySelector(".categoria h2");

          if (novosJogos && jogosContainer) {
            jogosContainer.innerHTML = novosJogos.innerHTML;
          }
          if (novoTitulo && tituloSeletor) {
            tituloSeletor.textContent = novoTitulo.textContent;
          }
        })
        .catch(error => {
          console.error("Erro ao filtrar:", error);
        })
    }
  })
})

// =================== Dashboard não reeviar comentarios ao recarregar ===================
document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("comentarioForm");
  const msgBox = document.getElementById("msg-feedback");

  if (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault();

      const data = new FormData(form);

      fetch("salvar_comentario.php", {
        method: "POST",
        body: data,
      })
        .then((res) => res.json())
        .then((res) => {
          msgBox.textContent = res.mensagem;
          msgBox.style.color = res.status === "sucesso" ? "green" : "red";
          if (res.status === "sucesso") {
            form.reset();
          }
        })
        .catch(() => {
          msgBox.textContent = "Erro ao conectar com o servidor.";
          msgBox.style.color = "red";
        });
    });
  }
});

// =================== Menu da pagina filtro botoes ===================
document.addEventListener("DOMContentLoaded", function () {
  const toggle = document.querySelector(".menu-toggle");
  const opcoes = document.querySelector(".menu-opcoes");

  toggle.addEventListener("click", function () {
    const isOpen = opcoes.style.display === "block";
    opcoes.style.display = isOpen ? "none" : "block";
  });
  document.addEventListener("click", function (e) {
    if (!toggle.contains(e.target) && !opcoes.contains(e.target)) {
      opcoes.style.display = "none";
    }
  });
  opcoes.addEventListener("click", function (e) {
    if (e.target.tagName === "BUTTON") {
      opcoes.style.display = "none";
    }
  })
})


