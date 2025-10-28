<header>
    <figure class="logo">
        <a href="paginainicial.php"><img src="img/NewLudusLogo.png" alt="Logotipo"></a>
    </figure>

    <!-- Navegação do usuário logado -->
    <nav id="nav" class="nav-links">
        <a href="filtragem.php">Games</a>
        <a href="gerenciamento.php">Gerenciamento</a>
        <a href="alteracao_exclusao_jogos.php">Edição de jogos</a>
        <a href="perfil.php">
            <img src="<?= htmlspecialchars($_SESSION['foto_perfil'] ?? 'img/usuarios/default.png') ?>" 
                 alt="Perfil do usuário" 
                 class="user-avatar">
        </a>
    </nav>

    <!-- Barra de pesquisa -->
    <div class="search-container">
        <form action="pesquisa.php" method="GET">
            <input type="text" name="pesquisa" placeholder="Pesquisar..." required>
            <i class="fas fa-search icon"></i>
        </form>
    </div>

    <!-- Menu sanduíche -->
    <div class="hamburger" onclick="toggleMenu()">
        ☰
    </div>
</header>
