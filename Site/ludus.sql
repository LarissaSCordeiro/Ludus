CREATE DATABASE IF NOT EXISTS ludus;
USE ludus;

CREATE TABLE usuario (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(30) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    tipo ENUM('jogador', 'desenvolvedor', 'administrador') NOT NULL,
    senha VARCHAR(255) NOT NULL,
    foto_perfil VARCHAR(255) DEFAULT 'img/usuarios/default.png',
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE jogo (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    estudio VARCHAR(100),
    desenvolvedor VARCHAR(100),
    descricao TEXT,
    imagem VARCHAR(255) DEFAULT 'img/jogos/default.png',
    plataforma VARCHAR(50),
    data_lancamento DATE,
    id_usuario INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id)
);

CREATE TABLE genero (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE jogo_possui_genero (
    id_jogo INT,
    id_genero INT,
    PRIMARY KEY (id_jogo, id_genero),
    FOREIGN KEY (id_jogo) REFERENCES jogo(id),
    FOREIGN KEY (id_genero) REFERENCES genero(id)
);

CREATE TABLE avaliacao (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nota DECIMAL(2,1) NOT NULL,
    data_avaliacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    texto TEXT,
    id_jogo INT NOT NULL,
    id_usuario INT NOT NULL,
    FOREIGN KEY (id_jogo) REFERENCES jogo(id),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id),
    CHECK (nota BETWEEN 0 AND 5)
);

CREATE TABLE comentario (
    id INT PRIMARY KEY AUTO_INCREMENT,
    data_comentario DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    texto TEXT NOT NULL,
    id_usuario INT NOT NULL,
    id_avaliacao INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id),
    FOREIGN KEY (id_avaliacao) REFERENCES avaliacao(id)
);

CREATE TABLE usuario_curte_avaliacao (
    id_usuario INT,
    id_avaliacao INT,
    PRIMARY KEY (id_usuario, id_avaliacao),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id),
    FOREIGN KEY (id_avaliacao) REFERENCES avaliacao(id)
);

CREATE TABLE usuario_favorita_jogo (
    id_usuario INT,
    id_jogo INT,
    PRIMARY KEY (id_usuario, id_jogo),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id),
    FOREIGN KEY (id_jogo) REFERENCES jogo(id)
);