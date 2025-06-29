# Módulo Gestão de Fazendas para Perfex CRM

## Descrição

Módulo completo e integrado ao Perfex CRM para a gestão financeira e operacional de fazendas de gado de corte (Fazenda Jacamim e Fazenda Marape). O objetivo é substituir o controle manual feito em planilhas por um sistema robusto que oferece cadastros centralizados, lançamento de transações, conciliação bancária, relatórios gerenciais e um dashboard intuitivo.

## Características Principais

### 🏢 Gestão de Entidades
- Cadastro unificado de clientes, fornecedores, credores e funcionários
- Validação de CPF/CNPJ
- Import/Export via Excel e CSV
- Controle de status ativo/inativo

### 💰 Sistema Financeiro Completo
- **Plano de Contas Hierárquico**: Estrutura em árvore para categorização
- **Lançamentos Financeiros**: Contas a pagar, receber e lançamentos orçados
- **Lançamentos Recorrentes**: Automatização de despesas/receitas periódicas
- **Rateio Automático**: Divisão proporcional entre fazendas
- **Contas Bancárias**: Controle de saldos e movimentações

### 🐄 Gestão de Ativos de Gado
- Controle de lotes de gado por categoria (garrotes, novilhas, etc.)
- Rastreamento de custos de aquisição
- Vinculação com lançamentos financeiros
- Relatórios de custo por animal/@

### 📊 Relatórios Gerenciais
- **Dashboard Executivo**: KPIs, gráficos e alertas
- **Fluxo de Caixa (DFC)**: Visão mensal com filtros
- **DRE**: Demonstrativo de resultado por competência
- **Relatório de Endividamento**: Controle de contratos e parcelas
- **Relatórios Operacionais**: Histórico de compras e custos

### 📈 Recursos Avançados
- Gráficos interativos com Chart.js
- Exportação para Excel
- Sistema de permissões integrado
- Log de auditoria
- Interface responsiva
- Localização completa em português brasileiro

## Estrutura do Banco de Dados

### Tabelas Principais
- `tblfaz_entidades` - Clientes, fornecedores, credores
- `tblfaz_plano_contas` - Plano de contas hierárquico
- `tblfaz_centros_custo` - Fazendas e centros de custo
- `tblfaz_contas_bancarias` - Contas bancárias
- `tblfaz_lancamentos_financeiros` - Transações financeiras
- `tblfaz_endividamento` - Contratos de dívida
- `tblfaz_endividamento_parcelas` - Parcelas dos contratos
- `tblfaz_ativos_gado` - Lotes de gado
- `tblfaz_configuracoes` - Configurações do módulo
- `tblfaz_log_atividades` - Log de auditoria

## Instalação

1. Faça o upload dos arquivos para a pasta `modules/gestaofinanceira/` do Perfex CRM
2. Acesse o painel administrativo do Perfex CRM
3. Vá em **Setup > Modules** e ative o módulo "Gestão de Fazendas"
4. As tabelas serão criadas automaticamente durante a ativação

## Configuração Inicial

### 1. Centros de Custo
Os seguintes centros de custo são criados automaticamente:
- Fazenda Jacamim
- Fazenda Marape  
- Rateio Administrativo

### 2. Plano de Contas Básico
Um plano de contas inicial é criado com as principais categorias:
- Receitas (Venda de Gado, Arrendamento)
- Custos Variáveis (Aquisição de Gado, Alimentação, Medicamentos)
- Custos Fixos (Salários, Manutenção, Combustível)

### 3. Configurações de Rateio
Configure os percentuais de rateio em **Gestão de Fazendas > Configurações**:
- Percentual Fazenda Jacamim: 50%
- Percentual Fazenda Marape: 50%

## Funcionalidades de Upload

### Formatos Suportados
- Excel (.xls, .xlsx)
- CSV (.csv)
- Tamanho máximo: 10MB

### Templates Disponíveis
Baixe os templates para importação em massa:
- Template de Entidades
- Template de Plano de Contas
- Template de Contas Bancárias
- Template de Ativos de Gado
- Template de Lançamentos

## Lançamentos Recorrentes

### Frequências Disponíveis
- Mensal
- Bimestral
- Trimestral
- Semestral
- Anual

### Como Usar
1. Marque a opção "Lançamento Recorrente" no formulário
2. Selecione a frequência desejada
3. Defina a quantidade de parcelas (máximo 60)
4. O sistema criará automaticamente todos os lançamentos

## Sistema de Rateio

### Funcionamento
Quando o centro de custo "Rateio Administrativo" é selecionado:
1. O valor é dividido automaticamente entre as fazendas
2. Dois lançamentos são criados (um para cada fazenda)
3. Os percentuais são configuráveis
4. A descrição indica o percentual de cada rateio

## Relatórios

### Fluxo de Caixa
- Baseado na data de liquidação
- Filtros por período, centro de custo e conta bancária
- Gráfico de evolução do saldo
- Exportação para Excel

### DRE (Demonstrativo de Resultado)
- Baseado na data de competência
- Estrutura: Receitas - Custos Variáveis = Margem de Contribuição - Custos Fixos = Resultado
- Indicadores percentuais
- Gráficos de composição

### Dashboard
- Saldo de caixa atual
- Total a pagar/receber
- Gráficos de receitas vs despesas
- Alertas de vencimento (próximos 7 dias)
- KPIs principais

## Permissões

O módulo integra-se com o sistema de permissões do Perfex CRM:
- **View**: Visualizar dados
- **Create**: Criar novos registros
- **Edit**: Editar registros existentes
- **Delete**: Excluir registros

Configure as permissões em **Setup > Staff > Roles**.

## Suporte e Customizações

### Estrutura de Arquivos
```
gestaofinanceira/
├── controllers/
│   └── Gestaofinanceira.php
├── models/
│   └── Gestaofinanceira_model.php
├── views/
│   └── admin/gestaofinanceira/
├── language/
│   └── portuguese_br/
├── helpers/
│   └── gestaofinanceira_helper.php
├── gestaofinanceira.php
├── install.php
└── README.md
```

### Logs de Auditoria
Todas as operações são registradas na tabela `tblfaz_log_atividades` incluindo:
- Usuário responsável
- Ação realizada (CREATE, UPDATE, DELETE)
- Dados anteriores e novos
- IP e User Agent
- Data/hora da operação

## Versão
1.0.0

## Compatibilidade
- Perfex CRM 2.3.0+
- PHP 7.4+
- MySQL 5.7+

## Licença
Desenvolvido especificamente para gestão de fazendas de gado de corte.

---

**Desenvolvido com foco na gestão eficiente de fazendas brasileiras** 🇧🇷

