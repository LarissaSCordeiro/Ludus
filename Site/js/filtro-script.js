// =================== Filtro com AJAX ===================
document.addEventListener("DOMContentLoaded", function () {
  const generoForm = document.querySelector(".categoria_genero form");
  const jogosContainer = document.querySelector(".jogos");
  const tituloSeletor = document.querySelector(".categoria h2");

  if (generoForm && jogosContainer && tituloSeletor) {
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

            if (novosJogos && novoTitulo) {
              jogosContainer.innerHTML = novosJogos.innerHTML;
              tituloSeletor.textContent = novoTitulo.textContent;
            }
          })
          .catch(error => {
            console.error("Erro ao filtrar:", error);
          });
      }
    });
  }
});

// =================== Menu da página de filtros ===================
document.addEventListener("DOMContentLoaded", function () {
  const toggle = document.querySelector(".menu-toggle");
  const opcoes = document.querySelector(".menu-opcoes");

  if (toggle && opcoes) {
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
    });
  }
});

//=================== PESQUISA DE JOGOS (filtragem) ===================
 function irParaDashboard(idJogo) {
      window.location.href = 'dashboard.php?id=' + idJogo;
    }

    window.addEventListener('load', () => {
      const input = document.getElementById('searchInput');

      if (!sessionStorage.getItem('searchLoaded')) {
        // Primeira vez na página: mantém valor do PHP
        sessionStorage.setItem('searchLoaded', 'true');
      } else {
        // Recarregou a página: limpa o input
        input.value = '';
      }
    });

    if (window.history.replaceState) {
      const url = new URL(window.location.href);
      if (url.searchParams.has('pesquisa')) {
        url.searchParams.delete('pesquisa');
        window.history.replaceState({}, document.title, url.pathname);
      }
    }