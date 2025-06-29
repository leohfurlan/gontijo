<?php

defined('BASEPATH') or exit('No direct script access allowed');

// Usaremos o prefixo 'tblgf_' para nossas tabelas, para facilitar a identificação
$CI = &get_instance();
$db_prefix = db_prefix();

// Tabela de Centros de Custo
$sql_centros_custo = "CREATE TABLE IF NOT EXISTS `{$db_prefix}gf_centros_custo` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nome` VARCHAR(100) NOT NULL,
  `ativo` BOOLEAN NOT NULL DEFAULT TRUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

if ($CI->db->query($sql_centros_custo)) {
    // Insere os centros de custo iniciais se a tabela foi criada com sucesso
    $CI->db->query("INSERT INTO `{$db_prefix}gf_centros_custo` (`nome`) VALUES ('Fazenda Jacamim'), ('Fazenda Marape'), ('Rateio');");
}

// Tabela de Categorias (Plano de Contas)
$CI->db->query("CREATE TABLE IF NOT EXISTS `{$db_prefix}gf_categorias` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nome` VARCHAR(100) NOT NULL,
  `tipo` ENUM('receita', 'despesa') NOT NULL,
  `ativo` BOOLEAN NOT NULL DEFAULT TRUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

// Tabela Principal de Lançamentos
$sql_lancamentos = "CREATE TABLE IF NOT EXISTS `{$db_prefix}gf_lancamentos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `descricao` VARCHAR(255) NOT NULL,
  `valor` DECIMAL(15,2) NOT NULL,
  `tipo` ENUM('receita', 'despesa') NOT NULL,
  `data_vencimento` DATE NOT NULL,
  `data_pagamento` DATE NULL,
  `status` ENUM('a_pagar_receber', 'pago_recebido') NOT NULL,
  `centro_custo_id` INT NOT NULL,
  `categoria_id` INT NOT NULL,
  `fornecedor_cliente_id` INT NULL,
  `criado_em` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

$CI->db->query($sql_lancamentos);