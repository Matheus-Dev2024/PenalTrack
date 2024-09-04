-- Conexão com o banco de dados policia
\c policia;

-- Criação do schema srh
CREATE SCHEMA srh;

CREATE TABLE IF NOT EXISTS srh.sig_servidor
(
    id_servidor     SERIAL PRIMARY KEY,
    nome            VARCHAR(255) NOT NULL,
    matricula       VARCHAR(30),
    cpf             VARCHAR(11),
    dt_nascimento   DATE,
    email           VARCHAR(150),
    email_funcional VARCHAR
);
