<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Instalação do Módulo Gestão de Fazendas
 * 
 * Este arquivo contém todas as migrations necessárias para criar
 * a estrutura de banco de dados do módulo de gestão de fazendas.
 */

$CI = &get_instance();
$db_prefix = db_prefix();

// 1. Tabela de Entidades (Clientes, Fornecedores, Credores)
$sql_entidades = "CREATE TABLE IF NOT EXISTS `{$db_prefix}tblfaz_entidades` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nome_razao_social` VARCHAR(255) NOT NULL,
    `cpf_cnpj` VARCHAR(18) NULL,
    `tipo_entidade` VARCHAR(20) NOT NULL DEFAULT 'Cliente',
    `contato_principal` VARCHAR(100) NULL,
    `telefone` VARCHAR(20) NULL,
    `email` VARCHAR(100) NULL,
    `endereco` TEXT NULL,
    `ativo` BOOLEAN NOT NULL DEFAULT TRUE,
    `data_cadastro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_tipo_entidade` (`tipo_entidade`),
    INDEX `idx_ativo` (`ativo`),
    INDEX `idx_cpf_cnpj` (`cpf_cnpj`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

if ($CI->db->query($sql_entidades)) {
    // Inserir entidades padrão
    $CI->db->query("INSERT IGNORE INTO `{$db_prefix}tblfaz_entidades` 
        (`nome_razao_social`, `tipo_entidade`) VALUES 
        ('Fazenda Jacamim', 'Cliente'),
        ('Fazenda Marape', 'Cliente'),
        ('Fornecedor Padrão', 'Fornecedor')");
}

// 2. Tabela de Centros de Custo
$sql_centros_custo = "CREATE TABLE IF NOT EXISTS `{$db_prefix}tblfaz_centros_custo` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nome` VARCHAR(100) NOT NULL,
    `tipo` VARCHAR(50) NOT NULL DEFAULT 'Operacional',
    `ativo` BOOLEAN NOT NULL DEFAULT TRUE,
    `data_cadastro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_tipo` (`tipo`),
    INDEX `idx_ativo` (`ativo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

if ($CI->db->query($sql_centros_custo)) {
    // Inserir centros de custo padrão
    $CI->db->query("INSERT IGNORE INTO `{$db_prefix}tblfaz_centros_custo` 
        (`nome`, `tipo`) VALUES 
        ('Fazenda Jacamim', 'Operacional'),
        ('Fazenda Marape', 'Operacional'),
        ('Rateio Administrativo', 'Administrativo')");
}

// 3. Tabela de Plano de Contas
$sql_plano_contas = "CREATE TABLE IF NOT EXISTS `{$db_prefix}tblfaz_plano_contas` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `id_pai` INT NULL,
    `codigo_conta` VARCHAR(50) NOT NULL,
    `nome_conta` VARCHAR(255) NOT NULL,
    `tipo_conta` VARCHAR(10) NOT NULL,
    `grupo_dre` VARCHAR(50) NULL,
    `aceita_lancamento` BOOLEAN NOT NULL DEFAULT TRUE,
    `ativo` BOOLEAN NOT NULL DEFAULT TRUE,
    `data_cadastro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`id_pai`) REFERENCES `{$db_prefix}tblfaz_plano_contas`(`id`) ON DELETE SET NULL,
    INDEX `idx_tipo_conta` (`tipo_conta`),
    INDEX `idx_grupo_dre` (`grupo_dre`),
    INDEX `idx_aceita_lancamento` (`aceita_lancamento`),
    INDEX `idx_ativo` (`ativo`),
    UNIQUE KEY `uk_codigo_conta` (`codigo_conta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

if ($CI->db->query($sql_plano_contas)) {
    // Inserir plano de contas básico
    $CI->db->query("INSERT IGNORE INTO `{$db_prefix}tblfaz_plano_contas` 
        (`codigo_conta`, `nome_conta`, `tipo_conta`, `grupo_dre`, `aceita_lancamento`) VALUES 
        ('4.0.0.00', 'RECEITAS', 'Receita', 'Receita Operacional', FALSE),
        ('4.1.0.00', 'Receita Operacional', 'Receita', 'Receita Operacional', FALSE),
        ('4.1.1.00', 'Venda de Gado', 'Receita', 'Receita Operacional', TRUE),
        ('4.1.2.00', 'Arrendamento', 'Receita', 'Receita Operacional', TRUE),
        ('5.0.0.00', 'DESPESAS', 'Despesa', 'Custo Variável', FALSE),
        ('5.1.0.00', 'Custos Variáveis', 'Despesa', 'Custo Variável', FALSE),
        ('5.1.1.00', 'Aquisição de Gado', 'Despesa', 'Custo Variável', TRUE),
        ('5.1.2.00', 'Alimentação Animal', 'Despesa', 'Custo Variável', TRUE),
        ('5.1.3.00', 'Medicamentos Veterinários', 'Despesa', 'Custo Variável', TRUE),
        ('5.2.0.00', 'Custos Fixos', 'Despesa', 'Custo Fixo', FALSE),
        ('5.2.1.00', 'Salários e Encargos', 'Despesa', 'Custo Fixo', TRUE),
        ('5.2.2.00', 'Manutenção de Equipamentos', 'Despesa', 'Custo Fixo', TRUE),
        ('5.2.3.00', 'Combustível', 'Despesa', 'Custo Fixo', TRUE)");
}

// 4. Tabela de Contas Bancárias
$sql_contas_bancarias = "CREATE TABLE IF NOT EXISTS `{$db_prefix}tblfaz_contas_bancarias` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `id_centro_custo` INT NOT NULL,
    `banco` VARCHAR(100) NOT NULL,
    `agencia` VARCHAR(10) NOT NULL,
    `conta` VARCHAR(20) NOT NULL,
    `saldo_inicial` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `data_saldo_inicial` DATE NOT NULL,
    `ativo` BOOLEAN NOT NULL DEFAULT TRUE,
    `data_cadastro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`id_centro_custo`) REFERENCES `{$db_prefix}tblfaz_centros_custo`(`id`) ON DELETE RESTRICT,
    INDEX `idx_centro_custo` (`id_centro_custo`),
    INDEX `idx_ativo` (`ativo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

$CI->db->query($sql_contas_bancarias);

// 5. Tabela de Lançamentos Financeiros
$sql_lancamentos = "CREATE TABLE IF NOT EXISTS `{$db_prefix}tblfaz_lancamentos_financeiros` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `id_plano_contas` INT NOT NULL,
    `id_entidade` INT NULL,
    `id_centro_custo` INT NOT NULL,
    `id_conta_bancaria` INT NULL,
    `descricao` TEXT NOT NULL,
    `valor` DECIMAL(15,2) NOT NULL,
    `tipo_lancamento` VARCHAR(20) NOT NULL DEFAULT 'Realizado',
    `data_competencia` DATE NOT NULL,
    `data_vencimento` DATE NOT NULL,
    `data_liquidacao` DATE NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'A Pagar',
    `numero_nf` VARCHAR(50) NULL,
    `id_lote_gado` INT NULL,
    `observacoes` TEXT NULL,
    `data_cadastro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `data_atualizacao` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`id_plano_contas`) REFERENCES `{$db_prefix}tblfaz_plano_contas`(`id`) ON DELETE RESTRICT,
    FOREIGN KEY (`id_entidade`) REFERENCES `{$db_prefix}tblfaz_entidades`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`id_centro_custo`) REFERENCES `{$db_prefix}tblfaz_centros_custo`(`id`) ON DELETE RESTRICT,
    FOREIGN KEY (`id_conta_bancaria`) REFERENCES `{$db_prefix}tblfaz_contas_bancarias`(`id`) ON DELETE SET NULL,
    INDEX `idx_plano_contas` (`id_plano_contas`),
    INDEX `idx_entidade` (`id_entidade`),
    INDEX `idx_centro_custo` (`id_centro_custo`),
    INDEX `idx_conta_bancaria` (`id_conta_bancaria`),
    INDEX `idx_tipo_lancamento` (`tipo_lancamento`),
    INDEX `idx_status` (`status`),
    INDEX `idx_data_competencia` (`data_competencia`),
    INDEX `idx_data_vencimento` (`data_vencimento`),
    INDEX `idx_data_liquidacao` (`data_liquidacao`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

$CI->db->query($sql_lancamentos);

// 6. Tabela de Endividamento (Contratos de Dívida)
$sql_endividamento = "CREATE TABLE IF NOT EXISTS `{$db_prefix}tblfaz_endividamento` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `id_credor` INT NOT NULL,
    `numero_contrato` VARCHAR(100) NOT NULL,
    `descricao` VARCHAR(255) NOT NULL,
    `valor_contrato` DECIMAL(15,2) NOT NULL,
    `taxa_juros_aa` DECIMAL(8,5) NOT NULL DEFAULT 0.00000,
    `data_contratacao` DATE NOT NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'Ativo',
    `observacoes` TEXT NULL,
    `data_cadastro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `data_atualizacao` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`id_credor`) REFERENCES `{$db_prefix}tblfaz_entidades`(`id`) ON DELETE RESTRICT,
    INDEX `idx_credor` (`id_credor`),
    INDEX `idx_status` (`status`),
    INDEX `idx_data_contratacao` (`data_contratacao`),
    UNIQUE KEY `uk_numero_contrato` (`numero_contrato`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

$CI->db->query($sql_endividamento);

// 7. Tabela de Parcelas de Endividamento
$sql_parcelas = "CREATE TABLE IF NOT EXISTS `{$db_prefix}tblfaz_endividamento_parcelas` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `id_endividamento` INT NOT NULL,
    `id_lancamento` INT NULL,
    `numero_parcela` INT NOT NULL,
    `data_vencimento` DATE NOT NULL,
    `valor_parcela` DECIMAL(15,2) NOT NULL,
    `valor_juros` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `valor_principal` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `status` VARCHAR(20) NOT NULL DEFAULT 'Aberta',
    `data_pagamento` DATE NULL,
    `data_cadastro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `data_atualizacao` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`id_endividamento`) REFERENCES `{$db_prefix}tblfaz_endividamento`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`id_lancamento`) REFERENCES `{$db_prefix}tblfaz_lancamentos_financeiros`(`id`) ON DELETE SET NULL,
    INDEX `idx_endividamento` (`id_endividamento`),
    INDEX `idx_lancamento` (`id_lancamento`),
    INDEX `idx_status` (`status`),
    INDEX `idx_data_vencimento` (`data_vencimento`),
    UNIQUE KEY `uk_endividamento_parcela` (`id_endividamento`, `numero_parcela`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

$CI->db->query($sql_parcelas);

// 8. Tabela de Ativos de Gado (Lotes de Gado)
$sql_ativos_gado = "CREATE TABLE IF NOT EXISTS `{$db_prefix}tblfaz_ativos_gado` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `id_lancamento_compra` INT NULL,
    `id_centro_custo` INT NOT NULL,
    `descricao_lote` VARCHAR(255) NOT NULL,
    `data_entrada` DATE NOT NULL,
    `categoria` VARCHAR(50) NOT NULL,
    `quantidade_cabecas` INT NOT NULL,
    `peso_medio_entrada` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `custo_total_aquisicao` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `status_lote` VARCHAR(20) NOT NULL DEFAULT 'Ativo',
    `observacoes` TEXT NULL,
    `data_cadastro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `data_atualizacao` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`id_lancamento_compra`) REFERENCES `{$db_prefix}tblfaz_lancamentos_financeiros`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`id_centro_custo`) REFERENCES `{$db_prefix}tblfaz_centros_custo`(`id`) ON DELETE RESTRICT,
    INDEX `idx_lancamento_compra` (`id_lancamento_compra`),
    INDEX `idx_centro_custo` (`id_centro_custo`),
    INDEX `idx_categoria` (`categoria`),
    INDEX `idx_status_lote` (`status_lote`),
    INDEX `idx_data_entrada` (`data_entrada`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

$CI->db->query($sql_ativos_gado);

// 9. Tabela de Configurações do Módulo
$sql_configuracoes = "CREATE TABLE IF NOT EXISTS `{$db_prefix}tblfaz_configuracoes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `chave` VARCHAR(100) NOT NULL,
    `valor` TEXT NULL,
    `descricao` VARCHAR(255) NULL,
    `tipo` VARCHAR(20) NOT NULL DEFAULT 'string',
    `data_cadastro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `data_atualizacao` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `uk_chave` (`chave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

if ($CI->db->query($sql_configuracoes)) {
    // Inserir configurações padrão
    $CI->db->query("INSERT IGNORE INTO `{$db_prefix}tblfaz_configuracoes` 
        (`chave`, `valor`, `descricao`, `tipo`) VALUES 
        ('percentual_rateio_jacamim', '50', 'Percentual de rateio para Fazenda Jacamim', 'numeric'),
        ('percentual_rateio_marape', '50', 'Percentual de rateio para Fazenda Marape', 'numeric'),
        ('moeda_padrao', 'BRL', 'Moeda padrão do sistema', 'string'),
        ('formato_data', 'd/m/Y', 'Formato de data padrão', 'string')");
}

// 10. Tabela de Log de Atividades (Auditoria)
$sql_log_atividades = "CREATE TABLE IF NOT EXISTS `{$db_prefix}tblfaz_log_atividades` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `usuario_id` INT NOT NULL,
    `tabela` VARCHAR(100) NOT NULL,
    `registro_id` INT NOT NULL,
    `acao` VARCHAR(20) NOT NULL,
    `dados_anteriores` JSON NULL,
    `dados_novos` JSON NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `data_atividade` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_usuario` (`usuario_id`),
    INDEX `idx_tabela` (`tabela`),
    INDEX `idx_registro` (`registro_id`),
    INDEX `idx_acao` (`acao`),
    INDEX `idx_data_atividade` (`data_atividade`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

$CI->db->query($sql_log_atividades);

// Criar views para relatórios
$sql_view_saldo_contas = "CREATE OR REPLACE VIEW `{$db_prefix}view_saldo_contas_bancarias` AS
SELECT 
    cb.id,
    cb.banco,
    cb.agencia,
    cb.conta,
    cb.saldo_inicial,
    COALESCE(SUM(
        CASE 
            WHEN pc.tipo_conta = 'Receita' AND lf.data_liquidacao IS NOT NULL THEN lf.valor
            WHEN pc.tipo_conta = 'Despesa' AND lf.data_liquidacao IS NOT NULL THEN -lf.valor
            ELSE 0
        END
    ), 0) as movimentacao,
    (cb.saldo_inicial + COALESCE(SUM(
        CASE 
            WHEN pc.tipo_conta = 'Receita' AND lf.data_liquidacao IS NOT NULL THEN lf.valor
            WHEN pc.tipo_conta = 'Despesa' AND lf.data_liquidacao IS NOT NULL THEN -lf.valor
            ELSE 0
        END
    ), 0)) as saldo_atual
FROM `{$db_prefix}tblfaz_contas_bancarias` cb
LEFT JOIN `{$db_prefix}tblfaz_lancamentos_financeiros` lf ON cb.id = lf.id_conta_bancaria
LEFT JOIN `{$db_prefix}tblfaz_plano_contas` pc ON lf.id_plano_contas = pc.id
WHERE cb.ativo = 1
GROUP BY cb.id, cb.banco, cb.agencia, cb.conta, cb.saldo_inicial;";

$CI->db->query($sql_view_saldo_contas);

// Mensagem de sucesso
echo "Módulo Gestão de Fazendas instalado com sucesso!<br>";
echo "Todas as tabelas foram criadas e dados iniciais inseridos.<br>";
echo "Total de tabelas criadas: 10<br>";
echo "Total de views criadas: 1<br>";

