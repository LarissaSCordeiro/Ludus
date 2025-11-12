<?php
// Helper para obter listas de jogos para destaques
if (!function_exists('getNewGames')) {
    function getNewGames($mysqli, $limit = 8)
    {
        $limit = (int)$limit;
        $sql = "SELECT id, nome, imagem, data_lancamento
                FROM jogo
                ORDER BY data_lancamento DESC
                LIMIT $limit";
        $res = $mysqli->query($sql);
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }
}

if (!function_exists('getPopularGames')) {
    function getPopularGames($mysqli, $limit = 8)
    {
        $limit = (int)$limit;
        $sql = "SELECT j.id, j.nome, j.imagem, j.data_lancamento,
                       COALESCE(AVG(a.nota),0) AS avg_rating,
                       COUNT(DISTINCT a.id) AS total_reviews,
                       COUNT(DISTINCT f.id_usuario) AS favorites
                FROM jogo j
                LEFT JOIN avaliacao a ON a.id_jogo = j.id
                LEFT JOIN usuario_favorita_jogo f ON f.id_jogo = j.id
                GROUP BY j.id
                ORDER BY favorites DESC, avg_rating DESC, total_reviews DESC
                LIMIT $limit";
        $res = $mysqli->query($sql);
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }
}

if (!function_exists('getTrendingGames')) {
    function getTrendingGames($mysqli, $limit = 8, $days = 14)
    {
        $limit = (int)$limit;
        $days = (int)$days;
        $sql = "SELECT j.id, j.nome, j.imagem, j.data_lancamento,
                       COUNT(a.id) AS recent_reviews,
                       MAX(a.data_avaliacao) AS last_review
                FROM jogo j
                LEFT JOIN avaliacao a ON a.id_jogo = j.id AND a.data_avaliacao >= DATE_SUB(NOW(), INTERVAL $days DAY)
                GROUP BY j.id
                ORDER BY recent_reviews DESC, last_review DESC, j.data_lancamento DESC
                LIMIT $limit";
        $res = $mysqli->query($sql);
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }
}

if (!function_exists('game_image')) {
    function game_image($path)
    {
        $path = trim($path);
        if (empty($path)) return 'img/jogos/default.png';
        return $path;
    }
}
