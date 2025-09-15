document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById("searchInput");
  const jogosContainer = document.querySelector(".jogos");
  const tituloResultados = document.getElementById("titulo-resultados");

  // Função de debounce (evita múltiplas requisições a cada tecla)
  function debounce(func, delay) {
    let timer;
    return function (...args) {
      clearTimeout(timer);
      timer = setTimeout(() => func.apply(this, args), delay);
    };
  }

  // Função de busca AJAX
  const buscarJogos = () => {
    const query = searchInput.value.trim();

    // Atualiza o título em tempo real
    if (tituloResultados) {
      if (query.length > 0) {
        tituloResultados.innerHTML = `Resultados para: <span>${query}</span>`;
      } else {
        tituloResultados.textContent = "Todos os Jogos :";
      }
    }

    // Faz a requisição AJAX
    fetch(`buscar.php?pesquisa=${encodeURIComponent(query)}`)
      .then(response => response.text())
      .then(data => {
        jogosContainer.innerHTML = data;
      })
      .catch(error => {
        console.error("Erro na busca:", error);
      });
  };

  // Aplica debounce de 400ms
  searchInput.addEventListener("input", debounce(buscarJogos, 400));
});




