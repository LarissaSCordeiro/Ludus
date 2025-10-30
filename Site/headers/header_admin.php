<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
<style>
    .icon-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #1f1f1f;
        color: #fff;
        margin-left: 10px;
        transition: 0.3s ease;
        font-size: 18px;
        transition: all 0.3s ease-in-out;
    }

    .icon-link:hover {
        background: #cd3dff;
        color: #fff;
        transform: scale(1.15) rotate(5deg);
    }
</style>

<header>
    <figure class="logo">
        <a href="paginainicial.php"><img src="img/NewLudusLogo.png" alt="Logotipo"></a>
    </figure>

    <!-- Navegação do usuário logado -->
    <nav id="nav" class="nav-links">
        <a href="filtragem.php">Games</a>
        <a href="perfil.php">
            <img src="<?= htmlspecialchars($_SESSION['foto_perfil'] ?? 'img/usuarios/default.png') ?>"
                alt="Perfil do usuário" class="user-avatar">
        </a>
        <a href="gerenciamento.php" class="icon-link" title="Gerenciamento">
            <i class="fa-solid fa-gear"></i>
        </a>

        <a href="alteracao_exclusao_jogos.php" class="icon-link" title="Editar jogos">
            <i class="fa-solid fa-pen-to-square"></i>
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