<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Controller principal do módulo Gestão de Fazendas
 */
class Gestaofinanceira extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('gestaofinanceira_model');
        $this->load->helper('gestaofinanceira');
    }

    /**
     * Dashboard principal
     */
    public function index()
    {
        if (!has_permission('gestao_fazendas', '', 'view')) {
            access_denied('gestao_fazendas');
        }

        $data['title'] = _l('gf_dashboard_title');
        
        // Dados para o dashboard
        $data['saldo_caixa'] = $this->gestaofinanceira_model->get_saldo_total_caixa();
        $data['total_pagar'] = $this->gestaofinanceira_model->get_total_a_pagar();
        $data['total_receber'] = $this->gestaofinanceira_model->get_total_a_receber();
        $data['receitas_despesas'] = $this->gestaofinanceira_model->get_receitas_despesas_ultimos_meses(6);
        $data['evolucao_fluxo'] = $this->gestaofinanceira_model->get_evolucao_fluxo_caixa(12);
        $data['alertas_vencimento'] = $this->gestaofinanceira_model->get_alertas_vencimento(7);
        
        $this->load->view('admin/gestaofinanceira/dashboard', $data);
    }

    /**
     * Gestão de Entidades (Clientes/Fornecedores)
     */
    public function entidades($id = '')
    {
        if (!has_permission('gestao_fazendas', '', 'view')) {
            access_denied('gestao_fazendas');
        }

        if ($this->input->post()) {
            $this->_handle_entidade_form();
            return;
        }

        if (is_numeric($id)) {
            $data['entidade'] = $this->gestaofinanceira_model->get_entidade($id);
            if (!$data['entidade']) {
                show_404();
            }
        }

        $data['title'] = _l('gf_entidades_title');
        $data['entidades'] = $this->gestaofinanceira_model->get_entidades();
        
        $this->load->view('admin/gestaofinanceira/entidades', $data);
    }

    /**
     * Gestão do Plano de Contas
     */
    public function plano_contas($id = '')
    {
        if (!has_permission('gestao_fazendas', '', 'view')) {
            access_denied('gestao_fazendas');
        }

        if ($this->input->post()) {
            $this->_handle_plano_contas_form();
            return;
        }

        if (is_numeric($id)) {
            $data['conta'] = $this->gestaofinanceira_model->get_conta($id);
            if (!$data['conta']) {
                show_404();
            }
        }

        $data['title'] = _l('gf_plano_contas_title');
        $data['contas'] = $this->gestaofinanceira_model->get_plano_contas_hierarquico();
        $data['contas_pai'] = $this->gestaofinanceira_model->get_contas_pai();
        
        $this->load->view('admin/gestaofinanceira/plano_contas', $data);
    }

    /**
     * Gestão de Contas Bancárias
     */
    public function contas_bancarias($id = '')
    {
        if (!has_permission('gestao_fazendas', '', 'view')) {
            access_denied('gestao_fazendas');
        }

        if ($this->input->post()) {
            $this->_handle_conta_bancaria_form();
            return;
        }

        if (is_numeric($id)) {
            $data['conta_bancaria'] = $this->gestaofinanceira_model->get_conta_bancaria($id);
            if (!$data['conta_bancaria']) {
                show_404();
            }
        }

        $data['title'] = _l('gf_contas_bancarias_title');
        $data['contas_bancarias'] = $this->gestaofinanceira_model->get_contas_bancarias();
        $data['centros_custo'] = $this->gestaofinanceira_model->get_centros_custo();
        
        $this->load->view('admin/gestaofinanceira/contas_bancarias', $data);
    }

    /**
     * Gestão de Ativos de Gado
     */
    public function ativos_gado($id = '')
    {
        if (!has_permission('gestao_fazendas', '', 'view')) {
            access_denied('gestao_fazendas');
        }

        if ($this->input->post()) {
            $this->_handle_ativo_gado_form();
            return;
        }

        if (is_numeric($id)) {
            $data['ativo_gado'] = $this->gestaofinanceira_model->get_ativo_gado($id);
            if (!$data['ativo_gado']) {
                show_404();
            }
        }

        $data['title'] = _l('gf_ativos_gado_title');
        $data['ativos_gado'] = $this->gestaofinanceira_model->get_ativos_gado();
        $data['centros_custo'] = $this->gestaofinanceira_model->get_centros_custo();
        $data['lancamentos_compra'] = $this->gestaofinanceira_model->get_lancamentos_compra_gado();
        
        $this->load->view('admin/gestaofinanceira/ativos_gado', $data);
    }

    /**
     * Lançamentos Financeiros
     */
    public function lancamentos($id = '')
    {
        if (!has_permission('gestao_fazendas', '', 'view')) {
            access_denied('gestao_fazendas');
        }

        if ($this->input->post()) {
            $this->_handle_lancamento_form();
            return;
        }

        if (is_numeric($id)) {
            $data['lancamento'] = $this->gestaofinanceira_model->get_lancamento($id);
            if (!$data['lancamento']) {
                show_404();
            }
        }

        $data['title'] = _l('gf_lancamentos_title');
        $data['lancamentos'] = $this->gestaofinanceira_model->get_lancamentos();
        $data['plano_contas'] = $this->gestaofinanceira_model->get_contas_lancamento();
        $data['entidades'] = $this->gestaofinanceira_model->get_entidades();
        $data['centros_custo'] = $this->gestaofinanceira_model->get_centros_custo();
        $data['contas_bancarias'] = $this->gestaofinanceira_model->get_contas_bancarias();
        
        $this->load->view('admin/gestaofinanceira/lancamentos', $data);
    }

    /**
     * Contas a Pagar
     */
    public function contas_pagar()
    {
        if (!has_permission('gestao_fazendas', '', 'view')) {
            access_denied('gestao_fazendas');
        }

        $data['title'] = _l('gf_menu_contas_pagar');
        $data['contas_pagar'] = $this->gestaofinanceira_model->get_contas_pagar();
        
        $this->load->view('admin/gestaofinanceira/contas_pagar', $data);
    }

    /**
     * Contas a Receber
     */
    public function contas_receber()
    {
        if (!has_permission('gestao_fazendas', '', 'view')) {
            access_denied('gestao_fazendas');
        }

        $data['title'] = _l('gf_menu_contas_receber');
        $data['contas_receber'] = $this->gestaofinanceira_model->get_contas_receber();
        
        $this->load->view('admin/gestaofinanceira/contas_receber', $data);
    }

    /**
     * Relatórios
     */
    public function relatorios()
    {
        if (!has_permission('gestao_fazendas', '', 'view')) {
            access_denied('gestao_fazendas');
        }

        $data['title'] = _l('gf_menu_relatorios');
        
        $this->load->view('admin/gestaofinanceira/relatorios', $data);
    }

    /**
     * Fluxo de Caixa
     */
    public function fluxo_caixa()
    {
        if (!has_permission('gestao_fazendas', '', 'view')) {
            access_denied('gestao_fazendas');
        }

        $data['title'] = _l('gf_menu_fluxo_caixa');
        
        // Filtros
        $data_inicio = $this->input->get('data_inicio') ?: date('Y-m-01');
        $data_fim = $this->input->get('data_fim') ?: date('Y-m-t');
        $centro_custo = $this->input->get('centro_custo');
        
        $data['fluxo_caixa'] = $this->gestaofinanceira_model->get_fluxo_caixa($data_inicio, $data_fim, $centro_custo);
        $data['centros_custo'] = $this->gestaofinanceira_model->get_centros_custo();
        $data['filtros'] = [
            'data_inicio' => $data_inicio,
            'data_fim' => $data_fim,
            'centro_custo' => $centro_custo
        ];
        
        $this->load->view('admin/gestaofinanceira/fluxo_caixa', $data);
    }

    /**
     * DRE - Demonstrativo de Resultado
     */
    public function dre()
    {
        if (!has_permission('gestao_fazendas', '', 'view')) {
            access_denied('gestao_fazendas');
        }

        $data['title'] = _l('gf_menu_dre');
        
        // Filtros
        $data_inicio = $this->input->get('data_inicio') ?: date('Y-m-01');
        $data_fim = $this->input->get('data_fim') ?: date('Y-m-t');
        $centro_custo = $this->input->get('centro_custo');
        
        $data['dre'] = $this->gestaofinanceira_model->get_dre($data_inicio, $data_fim, $centro_custo);
        $data['centros_custo'] = $this->gestaofinanceira_model->get_centros_custo();
        $data['filtros'] = [
            'data_inicio' => $data_inicio,
            'data_fim' => $data_fim,
            'centro_custo' => $centro_custo
        ];
        
        $this->load->view('admin/gestaofinanceira/dre', $data);
    }

    /**
     * Relatório de Endividamento
     */
    public function endividamento()
    {
        if (!has_permission('gestao_fazendas', '', 'view')) {
            access_denied('gestao_fazendas');
        }

        $data['title'] = _l('gf_menu_endividamento');
        $data['contratos'] = $this->gestaofinanceira_model->get_contratos_endividamento();
        $data['evolucao_divida'] = $this->gestaofinanceira_model->get_evolucao_divida();
        
        $this->load->view('admin/gestaofinanceira/endividamento', $data);
    }

    /**
     * Configurações
     */
    public function configuracoes()
    {
        if (!has_permission('gestao_fazendas', '', 'edit')) {
            access_denied('gestao_fazendas');
        }

        if ($this->input->post()) {
            $this->_handle_configuracoes_form();
            return;
        }

        $data['title'] = _l('gf_menu_configuracoes');
        $data['configuracoes'] = $this->gestaofinanceira_model->get_configuracoes();
        
        $this->load->view('admin/gestaofinanceira/configuracoes', $data);
    }

    /**
     * Métodos privados para manipulação de formulários
     */
    private function _handle_entidade_form()
    {
        $data = $this->input->post();
        
        if (isset($data['id']) && !empty($data['id'])) {
            $success = $this->gestaofinanceira_model->update_entidade($data['id'], $data);
            $message = $success ? _l('gf_msg_sucesso_salvar') : _l('gf_msg_erro_salvar');
        } else {
            $id = $this->gestaofinanceira_model->add_entidade($data);
            $success = $id > 0;
            $message = $success ? _l('gf_msg_sucesso_salvar') : _l('gf_msg_erro_salvar');
        }

        set_alert($success ? 'success' : 'danger', $message);
        redirect(admin_url('gestaofinanceira/entidades'));
    }

    private function _handle_plano_contas_form()
    {
        $data = $this->input->post();
        
        if (isset($data['id']) && !empty($data['id'])) {
            $success = $this->gestaofinanceira_model->update_conta($data['id'], $data);
            $message = $success ? _l('gf_msg_sucesso_salvar') : _l('gf_msg_erro_salvar');
        } else {
            $id = $this->gestaofinanceira_model->add_conta($data);
            $success = $id > 0;
            $message = $success ? _l('gf_msg_sucesso_salvar') : _l('gf_msg_erro_salvar');
        }

        set_alert($success ? 'success' : 'danger', $message);
        redirect(admin_url('gestaofinanceira/plano_contas'));
    }

    private function _handle_conta_bancaria_form()
    {
        $data = $this->input->post();
        
        if (isset($data['id']) && !empty($data['id'])) {
            $success = $this->gestaofinanceira_model->update_conta_bancaria($data['id'], $data);
            $message = $success ? _l('gf_msg_sucesso_salvar') : _l('gf_msg_erro_salvar');
        } else {
            $id = $this->gestaofinanceira_model->add_conta_bancaria($data);
            $success = $id > 0;
            $message = $success ? _l('gf_msg_sucesso_salvar') : _l('gf_msg_erro_salvar');
        }

        set_alert($success ? 'success' : 'danger', $message);
        redirect(admin_url('gestaofinanceira/contas_bancarias'));
    }

    private function _handle_ativo_gado_form()
    {
        $data = $this->input->post();
        
        if (isset($data['id']) && !empty($data['id'])) {
            $success = $this->gestaofinanceira_model->update_ativo_gado($data['id'], $data);
            $message = $success ? _l('gf_msg_sucesso_salvar') : _l('gf_msg_erro_salvar');
        } else {
            $id = $this->gestaofinanceira_model->add_ativo_gado($data);
            $success = $id > 0;
            $message = $success ? _l('gf_msg_sucesso_salvar') : _l('gf_msg_erro_salvar');
        }

        set_alert($success ? 'success' : 'danger', $message);
        redirect(admin_url('gestaofinanceira/ativos_gado'));
    }

    private function _handle_lancamento_form()
    {
        $data = $this->input->post();
        
        // Verificar se é lançamento recorrente
        if (isset($data['recorrente']) && $data['recorrente'] == '1') {
            $this->_processar_lancamento_recorrente($data);
        } else {
            // Verificar se precisa de rateio
            if ($data['id_centro_custo'] == $this->_get_centro_custo_rateio_id()) {
                $this->_processar_rateio($data);
            } else {
                // Lançamento normal
                if (isset($data['id']) && !empty($data['id'])) {
                    $success = $this->gestaofinanceira_model->update_lancamento($data['id'], $data);
                    $message = $success ? _l('gf_msg_sucesso_salvar') : _l('gf_msg_erro_salvar');
                } else {
                    $id = $this->gestaofinanceira_model->add_lancamento($data);
                    $success = $id > 0;
                    $message = $success ? _l('gf_msg_sucesso_salvar') : _l('gf_msg_erro_salvar');
                }
            }
        }

        set_alert($success ? 'success' : 'danger', $message);
        redirect(admin_url('gestaofinanceira/lancamentos'));
    }

    /**
     * Processar lançamentos recorrentes
     */
    private function _processar_lancamento_recorrente($data)
    {
        $frequencia = $data['frequencia'] ?? 'mensal';
        $quantidade_parcelas = intval($data['quantidade_parcelas'] ?? 12);
        $data_base = $data['data_vencimento'];
        
        // Remover campos específicos de recorrência
        unset($data['recorrente'], $data['frequencia'], $data['quantidade_parcelas']);
        
        $success = true;
        $parcelas_criadas = 0;
        
        for ($i = 0; $i < $quantidade_parcelas; $i++) {
            $data_parcela = $data;
            
            // Calcular data da parcela
            switch ($frequencia) {
                case 'mensal':
                    $data_parcela['data_vencimento'] = date('Y-m-d', strtotime($data_base . " +{$i} months"));
                    $data_parcela['data_competencia'] = date('Y-m-d', strtotime($data['data_competencia'] . " +{$i} months"));
                    break;
                case 'bimestral':
                    $meses = $i * 2;
                    $data_parcela['data_vencimento'] = date('Y-m-d', strtotime($data_base . " +{$meses} months"));
                    $data_parcela['data_competencia'] = date('Y-m-d', strtotime($data['data_competencia'] . " +{$meses} months"));
                    break;
                case 'trimestral':
                    $meses = $i * 3;
                    $data_parcela['data_vencimento'] = date('Y-m-d', strtotime($data_base . " +{$meses} months"));
                    $data_parcela['data_competencia'] = date('Y-m-d', strtotime($data['data_competencia'] . " +{$meses} months"));
                    break;
                case 'semestral':
                    $meses = $i * 6;
                    $data_parcela['data_vencimento'] = date('Y-m-d', strtotime($data_base . " +{$meses} months"));
                    $data_parcela['data_competencia'] = date('Y-m-d', strtotime($data['data_competencia'] . " +{$meses} months"));
                    break;
                case 'anual':
                    $data_parcela['data_vencimento'] = date('Y-m-d', strtotime($data_base . " +{$i} years"));
                    $data_parcela['data_competencia'] = date('Y-m-d', strtotime($data['data_competencia'] . " +{$i} years"));
                    break;
            }
            
            // Adicionar número da parcela na descrição
            $data_parcela['descricao'] = $data['descricao'] . " (Parcela " . ($i + 1) . "/{$quantidade_parcelas})";
            
            // Verificar se precisa de rateio
            if ($data_parcela['id_centro_custo'] == $this->_get_centro_custo_rateio_id()) {
                $this->_processar_rateio($data_parcela);
            } else {
                $id = $this->gestaofinanceira_model->add_lancamento($data_parcela);
                if ($id > 0) {
                    $parcelas_criadas++;
                } else {
                    $success = false;
                }
            }
        }
        
        $message = $success ? 
            sprintf('Lançamento recorrente criado com sucesso! %d parcelas geradas.', $parcelas_criadas) : 
            'Erro ao criar lançamento recorrente.';
            
        return $success;
    }

    /**
     * Processar rateio entre fazendas
     */
    private function _processar_rateio($data)
    {
        $configuracoes = $this->gestaofinanceira_model->get_configuracoes();
        $percentual_jacamim = floatval($configuracoes['percentual_rateio_jacamim'] ?? 50);
        $percentual_marape = floatval($configuracoes['percentual_rateio_marape'] ?? 50);
        
        $valor_total = floatval($data['valor']);
        $valor_jacamim = ($valor_total * $percentual_jacamim) / 100;
        $valor_marape = ($valor_total * $percentual_marape) / 100;
        
        $centros_custo = $this->gestaofinanceira_model->get_centros_custo();
        $id_jacamim = null;
        $id_marape = null;
        
        foreach ($centros_custo as $centro) {
            if (stripos($centro['nome'], 'jacamim') !== false) {
                $id_jacamim = $centro['id'];
            } elseif (stripos($centro['nome'], 'marape') !== false) {
                $id_marape = $centro['id'];
            }
        }
        
        $success = true;
        
        // Lançamento para Fazenda Jacamim
        if ($id_jacamim && $valor_jacamim > 0) {
            $data_jacamim = $data;
            $data_jacamim['id_centro_custo'] = $id_jacamim;
            $data_jacamim['valor'] = $valor_jacamim;
            $data_jacamim['descricao'] = $data['descricao'] . " (Rateio Jacamim - {$percentual_jacamim}%)";
            
            $id = $this->gestaofinanceira_model->add_lancamento($data_jacamim);
            if (!$id) $success = false;
        }
        
        // Lançamento para Fazenda Marape
        if ($id_marape && $valor_marape > 0) {
            $data_marape = $data;
            $data_marape['id_centro_custo'] = $id_marape;
            $data_marape['valor'] = $valor_marape;
            $data_marape['descricao'] = $data['descricao'] . " (Rateio Marape - {$percentual_marape}%)";
            
            $id = $this->gestaofinanceira_model->add_lancamento($data_marape);
            if (!$id) $success = false;
        }
        
        return $success;
    }

    /**
     * Obter ID do centro de custo de rateio
     */
    private function _get_centro_custo_rateio_id()
    {
        $centros_custo = $this->gestaofinanceira_model->get_centros_custo();
        
        foreach ($centros_custo as $centro) {
            if (stripos($centro['nome'], 'rateio') !== false) {
                return $centro['id'];
            }
        }
        
        return null;
    }

    private function _handle_configuracoes_form()
    {
        $data = $this->input->post();
        $success = $this->gestaofinanceira_model->update_configuracoes($data);
        $message = $success ? _l('gf_msg_sucesso_salvar') : _l('gf_msg_erro_salvar');

        set_alert($success ? 'success' : 'danger', $message);
        redirect(admin_url('gestaofinanceira/configuracoes'));
    }

    /**
     * Upload de Entidades via arquivo
     */
    public function upload_entidades()
    {
        if (!has_permission('gestao_fazendas', '', 'create')) {
            access_denied('gestao_fazendas');
        }

        if ($this->input->post() && isset($_FILES['arquivo_entidades'])) {
            $resultado = $this->_process_upload_entidades($_FILES['arquivo_entidades']);
            
            if ($resultado['success']) {
                set_alert('success', sprintf('Upload realizado com sucesso! %d registros importados.', $resultado['importados']));
            } else {
                set_alert('danger', 'Erro no upload: ' . $resultado['erro']);
            }
        }

        redirect(admin_url('gestaofinanceira/entidades'));
    }

    /**
     * Upload de Plano de Contas via arquivo
     */
    public function upload_plano_contas()
    {
        if (!has_permission('gestao_fazendas', '', 'create')) {
            access_denied('gestao_fazendas');
        }

        if ($this->input->post() && isset($_FILES['arquivo_plano_contas'])) {
            $resultado = $this->_process_upload_plano_contas($_FILES['arquivo_plano_contas']);
            
            if ($resultado['success']) {
                set_alert('success', sprintf('Upload realizado com sucesso! %d registros importados.', $resultado['importados']));
            } else {
                set_alert('danger', 'Erro no upload: ' . $resultado['erro']);
            }
        }

        redirect(admin_url('gestaofinanceira/plano_contas'));
    }

    /**
     * Upload de Contas Bancárias via arquivo
     */
    public function upload_contas_bancarias()
    {
        if (!has_permission('gestao_fazendas', '', 'create')) {
            access_denied('gestao_fazendas');
        }

        if ($this->input->post() && isset($_FILES['arquivo_contas_bancarias'])) {
            $resultado = $this->_process_upload_contas_bancarias($_FILES['arquivo_contas_bancarias']);
            
            if ($resultado['success']) {
                set_alert('success', sprintf('Upload realizado com sucesso! %d registros importados.', $resultado['importados']));
            } else {
                set_alert('danger', 'Erro no upload: ' . $resultado['erro']);
            }
        }

        redirect(admin_url('gestaofinanceira/contas_bancarias'));
    }

    /**
     * Upload de Ativos de Gado via arquivo
     */
    public function upload_ativos_gado()
    {
        if (!has_permission('gestao_fazendas', '', 'create')) {
            access_denied('gestao_fazendas');
        }

        if ($this->input->post() && isset($_FILES['arquivo_ativos_gado'])) {
            $resultado = $this->_process_upload_ativos_gado($_FILES['arquivo_ativos_gado']);
            
            if ($resultado['success']) {
                set_alert('success', sprintf('Upload realizado com sucesso! %d registros importados.', $resultado['importados']));
            } else {
                set_alert('danger', 'Erro no upload: ' . $resultado['erro']);
            }
        }

        redirect(admin_url('gestaofinanceira/ativos_gado'));
    }

    /**
     * Upload de Lançamentos via arquivo
     */
    public function upload_lancamentos()
    {
        if (!has_permission('gestao_fazendas', '', 'create')) {
            access_denied('gestao_fazendas');
        }

        if ($this->input->post() && isset($_FILES['arquivo_lancamentos'])) {
            $resultado = $this->_process_upload_lancamentos($_FILES['arquivo_lancamentos']);
            
            if ($resultado['success']) {
                set_alert('success', sprintf('Upload realizado com sucesso! %d registros importados.', $resultado['importados']));
            } else {
                set_alert('danger', 'Erro no upload: ' . $resultado['erro']);
            }
        }

        redirect(admin_url('gestaofinanceira/lancamentos'));
    }

    /**
     * Métodos privados para processamento de uploads
     */
    private function _process_upload_entidades($arquivo)
    {
        try {
            $dados = $this->_read_uploaded_file($arquivo);
            $importados = 0;
            
            foreach ($dados as $linha) {
                if (empty($linha[0])) continue; // Pula linhas vazias
                
                $entidade = [
                    'nome_razao_social' => $linha[0] ?? '',
                    'cpf_cnpj' => $linha[1] ?? '',
                    'tipo_entidade' => $linha[2] ?? 'Cliente',
                    'contato_principal' => $linha[3] ?? '',
                    'telefone' => $linha[4] ?? '',
                    'email' => $linha[5] ?? '',
                    'endereco' => $linha[6] ?? ''
                ];
                
                if ($this->gestaofinanceira_model->add_entidade($entidade)) {
                    $importados++;
                }
            }
            
            return ['success' => true, 'importados' => $importados];
            
        } catch (Exception $e) {
            return ['success' => false, 'erro' => $e->getMessage()];
        }
    }

    private function _process_upload_plano_contas($arquivo)
    {
        try {
            $dados = $this->_read_uploaded_file($arquivo);
            $importados = 0;
            
            foreach ($dados as $linha) {
                if (empty($linha[0])) continue; // Pula linhas vazias
                
                $conta = [
                    'codigo_conta' => $linha[0] ?? '',
                    'nome_conta' => $linha[1] ?? '',
                    'tipo_conta' => $linha[2] ?? 'Despesa',
                    'grupo_dre' => $linha[3] ?? '',
                    'aceita_lancamento' => ($linha[4] ?? '1') == '1' ? 1 : 0
                ];
                
                if ($this->gestaofinanceira_model->add_conta($conta)) {
                    $importados++;
                }
            }
            
            return ['success' => true, 'importados' => $importados];
            
        } catch (Exception $e) {
            return ['success' => false, 'erro' => $e->getMessage()];
        }
    }

    private function _process_upload_contas_bancarias($arquivo)
    {
        try {
            $dados = $this->_read_uploaded_file($arquivo);
            $importados = 0;
            
            foreach ($dados as $linha) {
                if (empty($linha[0])) continue; // Pula linhas vazias
                
                $conta_bancaria = [
                    'banco' => $linha[0] ?? '',
                    'agencia' => $linha[1] ?? '',
                    'conta' => $linha[2] ?? '',
                    'saldo_inicial' => floatval($linha[3] ?? 0),
                    'data_saldo_inicial' => $linha[4] ?? date('Y-m-d'),
                    'id_centro_custo' => intval($linha[5] ?? 1)
                ];
                
                if ($this->gestaofinanceira_model->add_conta_bancaria($conta_bancaria)) {
                    $importados++;
                }
            }
            
            return ['success' => true, 'importados' => $importados];
            
        } catch (Exception $e) {
            return ['success' => false, 'erro' => $e->getMessage()];
        }
    }

    private function _process_upload_ativos_gado($arquivo)
    {
        try {
            $dados = $this->_read_uploaded_file($arquivo);
            $importados = 0;
            
            foreach ($dados as $linha) {
                if (empty($linha[0])) continue; // Pula linhas vazias
                
                $ativo_gado = [
                    'descricao_lote' => $linha[0] ?? '',
                    'data_entrada' => $linha[1] ?? date('Y-m-d'),
                    'categoria' => $linha[2] ?? 'Garrotes',
                    'quantidade_cabecas' => intval($linha[3] ?? 0),
                    'peso_medio_entrada' => floatval($linha[4] ?? 0),
                    'custo_total_aquisicao' => floatval($linha[5] ?? 0),
                    'id_centro_custo' => intval($linha[6] ?? 1)
                ];
                
                if ($this->gestaofinanceira_model->add_ativo_gado($ativo_gado)) {
                    $importados++;
                }
            }
            
            return ['success' => true, 'importados' => $importados];
            
        } catch (Exception $e) {
            return ['success' => false, 'erro' => $e->getMessage()];
        }
    }

    private function _process_upload_lancamentos($arquivo)
    {
        try {
            $dados = $this->_read_uploaded_file($arquivo);
            $importados = 0;
            
            foreach ($dados as $linha) {
                if (empty($linha[0])) continue; // Pula linhas vazias
                
                $lancamento = [
                    'descricao' => $linha[0] ?? '',
                    'valor' => floatval($linha[1] ?? 0),
                    'data_competencia' => $linha[2] ?? date('Y-m-d'),
                    'data_vencimento' => $linha[3] ?? date('Y-m-d'),
                    'id_plano_contas' => intval($linha[4] ?? 1),
                    'id_centro_custo' => intval($linha[5] ?? 1),
                    'tipo_lancamento' => $linha[6] ?? 'Realizado',
                    'status' => $linha[7] ?? 'A Pagar',
                    'numero_nf' => $linha[8] ?? ''
                ];
                
                if ($this->gestaofinanceira_model->add_lancamento($lancamento)) {
                    $importados++;
                }
            }
            
            return ['success' => true, 'importados' => $importados];
            
        } catch (Exception $e) {
            return ['success' => false, 'erro' => $e->getMessage()];
        }
    }

    /**
     * Método para ler arquivos Excel/CSV
     */
    private function _read_uploaded_file($arquivo)
    {
        $config['upload_path'] = './uploads/temp/';
        $config['allowed_types'] = 'xls|xlsx|csv';
        $config['max_size'] = 10240; // 10MB
        $config['encrypt_name'] = TRUE;

        // Criar diretório se não existir
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0755, true);
        }

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('arquivo_entidades') && 
            !$this->upload->do_upload('arquivo_plano_contas') &&
            !$this->upload->do_upload('arquivo_contas_bancarias') &&
            !$this->upload->do_upload('arquivo_ativos_gado') &&
            !$this->upload->do_upload('arquivo_lancamentos')) {
            throw new Exception($this->upload->display_errors());
        }

        $upload_data = $this->upload->data();
        $file_path = $upload_data['full_path'];
        $file_ext = strtolower($upload_data['file_ext']);

        $dados = [];

        if ($file_ext == '.csv') {
            // Processar CSV
            if (($handle = fopen($file_path, "r")) !== FALSE) {
                $primeira_linha = true;
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if ($primeira_linha) {
                        $primeira_linha = false;
                        continue; // Pular cabeçalho
                    }
                    $dados[] = $data;
                }
                fclose($handle);
            }
        } else {
            // Processar Excel (.xls/.xlsx)
            require_once APPPATH . 'third_party/PhpSpreadsheet/vendor/autoload.php';
            
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader(
                $file_ext == '.xlsx' ? 'Xlsx' : 'Xls'
            );
            $spreadsheet = $reader->load($file_path);
            $worksheet = $spreadsheet->getActiveSheet();
            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();

            for ($row = 2; $row <= $highestRow; $row++) { // Começar da linha 2 (pular cabeçalho)
                $rowData = [];
                for ($col = 'A'; $col <= $highestColumn; $col++) {
                    $rowData[] = $worksheet->getCell($col . $row)->getCalculatedValue();
                }
                $dados[] = $rowData;
            }
        }

        // Remover arquivo temporário
        unlink($file_path);

        return $dados;
    }

    /**
     * Download de templates para upload
     */
    public function download_template($tipo)
    {
        if (!has_permission('gestao_fazendas', '', 'view')) {
            access_denied('gestao_fazendas');
        }

        $templates = [
            'entidades' => [
                'filename' => 'template_entidades.csv',
                'headers' => ['Nome/Razão Social', 'CPF/CNPJ', 'Tipo', 'Contato', 'Telefone', 'Email', 'Endereço'],
                'example' => ['João Silva', '123.456.789-00', 'Cliente', 'João', '(11) 99999-9999', 'joao@email.com', 'Rua A, 123']
            ],
            'plano_contas' => [
                'filename' => 'template_plano_contas.csv',
                'headers' => ['Código', 'Nome da Conta', 'Tipo', 'Grupo DRE', 'Aceita Lançamento (1/0)'],
                'example' => ['4.1.1.01', 'Venda de Gado', 'Receita', 'Receita Operacional', '1']
            ],
            'contas_bancarias' => [
                'filename' => 'template_contas_bancarias.csv',
                'headers' => ['Banco', 'Agência', 'Conta', 'Saldo Inicial', 'Data Saldo', 'ID Centro Custo'],
                'example' => ['Banco do Brasil', '1234', '12345-6', '10000.00', '2024-01-01', '1']
            ],
            'ativos_gado' => [
                'filename' => 'template_ativos_gado.csv',
                'headers' => ['Descrição Lote', 'Data Entrada', 'Categoria', 'Quantidade', 'Peso Médio', 'Custo Total', 'ID Centro Custo'],
                'example' => ['Lote 001', '2024-01-01', 'Garrotes', '50', '300.00', '75000.00', '1']
            ],
            'lancamentos' => [
                'filename' => 'template_lancamentos.csv',
                'headers' => ['Descrição', 'Valor', 'Data Competência', 'Data Vencimento', 'ID Plano Contas', 'ID Centro Custo', 'Tipo', 'Status', 'Número NF'],
                'example' => ['Compra de ração', '5000.00', '2024-01-01', '2024-01-30', '7', '1', 'Realizado', 'A Pagar', 'NF-001']
            ]
        ];

        if (!isset($templates[$tipo])) {
            show_404();
        }

        $template = $templates[$tipo];
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $template['filename'] . '"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, $template['headers']);
        fputcsv($output, $template['example']);
        fclose($output);
    }

    /**
     * Métodos AJAX
     */
    public function delete_entidade($id)
    {
        if (!has_permission('gestao_fazendas', '', 'delete')) {
            ajax_access_denied();
        }

        $success = $this->gestaofinanceira_model->delete_entidade($id);
        echo json_encode(['success' => $success]);
    }

    public function delete_conta($id)
    {
        if (!has_permission('gestao_fazendas', '', 'delete')) {
            ajax_access_denied();
        }

        $success = $this->gestaofinanceira_model->delete_conta($id);
        echo json_encode(['success' => $success]);
    }

    public function delete_conta_bancaria($id)
    {
        if (!has_permission('gestao_fazendas', '', 'delete')) {
            ajax_access_denied();
        }

        $success = $this->gestaofinanceira_model->delete_conta_bancaria($id);
        echo json_encode(['success' => $success]);
    }

    public function delete_ativo_gado($id)
    {
        if (!has_permission('gestao_fazendas', '', 'delete')) {
            ajax_access_denied();
        }

        $success = $this->gestaofinanceira_model->delete_ativo_gado($id);
        echo json_encode(['success' => $success]);
    }

    public function delete_lancamento($id)
    {
        if (!has_permission('gestao_fazendas', '', 'delete')) {
            ajax_access_denied();
        }

        $success = $this->gestaofinanceira_model->delete_lancamento($id);
        echo json_encode(['success' => $success]);
    }
}

