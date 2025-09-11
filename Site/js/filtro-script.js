// =================== Filtro com AJAX ===================
document.querySelectorAll(".btn-genre").forEach(button => {
  button.addEventListener("click", () => {
    const genero = button.getAttribute("data-genero");

    
    const novaURL = new URL(window.location.href);
    novaURL.searchParams.set("btn", genero);
    history.pushState({ genero }, "", novaURL);

    
    filtrarPorGenero(genero);
  });
});

function filtrarPorGenero(genero) {
  fetch("filtragem.php?btn=" + encodeURIComponent(genero), {
    method: "GET"
  })
    .then(response => response.text())
    .then(data => {
      const parser = new DOMParser();
      const doc = parser.parseFromString(data, "text/html");
      const novosJogos = doc.querySelector(".jogos");
      const novoTitulo = doc.querySelector(".categoria h2");

      if (novosJogos && novoTitulo) {
        document.querySelector(".jogos").innerHTML = novosJogos.innerHTML;
        document.querySelector(".categoria h2").textContent = novoTitulo.textContent;
      }
    })
    .catch(error => {
      console.error("Erro ao filtrar:", error);
    });
}


window.addEventListener("popstate", (event) => {
  const genero = event.state?.genero || new URL(window.location.href).searchParams.get("btn");
  if (genero) {
    filtrarPorGenero(genero);
  } else {
    fetch("filtragem.php", {
      method: "GET"
    })
      .then(response => response.text())
      .then(data => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(data, "text/html");
        const novosJogos = doc.querySelector(".jogos");
        const novoTitulo = doc.querySelector(".categoria h2");

        if (novosJogos && novoTitulo) {
          document.querySelector(".jogos").innerHTML = novosJogos.innerHTML;
          document.querySelector(".categoria h2").textContent = novoTitulo.textContent;
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
//=================== Desaparecer ao pesquisar (filtragem) ===================

const inputPesquisa = document.getElementById('searchInput');
const container = document.querySelector('.buttons-genre'); 

const displayOriginal = getComputedStyle(container).display;

inputPesquisa.addEventListener('input', function() {
  if (this.value.length > 0) {
    container.style.display = 'none'; 
  } else {
    container.style.display = displayOriginal; 
  }
});


