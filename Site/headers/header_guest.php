<header>
    <!-- Logo do Ludus -->
    <figure class="logo">
        <a href="index.php"><img src="img/NewLudusLogo.png" alt="Logotipo"></a>
    </figure>
    
    <!-- Barra de navegação -->
    <nav id="nav" class="nav-links">
        <a href="filtragem.php">Games</a>
        <!-- Botão de entrar e links -->
        <a href="login.php" class="a-Button">Entrar</a>
        <a href="cadastro.php">Criar uma conta</a>
    </nav>

    <!-- Barra de pesquisa personalizada -->
    <div class="search-container">
        <form action="pesquisa.php" method="GET">
            <input type="text" name="pesquisa" placeholder="Pesquisar..." required>
            <i class="fas fa-search icon"></i>
        </form>
    </div>

    <!-- Ícone do menu sanduíche -->
    <div class="hamburger" onclick="toggleMenu()">
        ☰
    </div>
</header>
