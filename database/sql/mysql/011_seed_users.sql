-- Seed de usuário inicial (MySQL)
-- Execute após criar as tabelas em 001_create_core_tables.sql

INSERT IGNORE INTO users(name,email,password,role,suite_number,preferred_currency)
VALUES ('Lucas Vacari','lucas@lrvweb.com.br','$2y$10$3YAHki.1HX7vSHh3OaO1JuV1KUdrNfmIkseijCKhn05yCQPP/shIu','admin','PF-000002','BRL');
