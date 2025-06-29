<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Lancamentos extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('lancamentos_model');
    }

    /**
     * Exibe a página principal com a TABELA de Lançamentos
     */
    public function index()
    {
        $data['lancamentos'] = $this->lancamentos_model->get_all_lancamentos();
        $data['title'] = _l('gf_menu_lancamentos');
        $this->load->view('gestaofinanceira/lancamentos/manage', $data);
    }

    /**
     * Função para ADICIONAR um novo lançamento (GET para mostrar, POST para salvar)
     */
    public function create()
    {
        // Se a requisição for um POST, salva os dados
        if ($this->input->post()) {
            $success = $this->lancamentos_model->add($this->input->post());
            if ($success) {
                set_alert('success', 'Lançamento adicionado com sucesso!');
            } else {
                set_alert('danger', 'Ocorreu um erro ao adicionar o lançamento.');
            }
            redirect(admin_url('gestaofinanceira/lancamentos'));
        }

        // Se for um GET, apenas prepara os dados e mostra a página do formulário
        $data['title'] = 'Adicionar Novo Lançamento';
        $data['categorias'] = $this->lancamentos_model->get_categorias();
        $data['centros_custo'] = $this->lancamentos_model->get_centros_custo();

        $this->load->view('gestaofinanceira/lancamentos/form_lancamento', $data);
    }

    /**
     * Função para EDITAR um lançamento existente (GET para mostrar, POST para salvar)
     * @param int $id O ID do lançamento a ser editado
     */
    public function edit($id = '')
    {
        // Garante que um ID válido foi passado
        if ($id == '' || !is_numeric($id)) {
            redirect(admin_url('gestaofinanceira/lancamentos'));
        }

        // Se a requisição for um POST, atualiza os dados
        if ($this->input->post()) {
            // A função 'update' ainda precisa ser criada no seu Lancamentos_model
            $success = $this->lancamentos_model->update($this->input->post(), $id);
            if ($success) {
                set_alert('success', 'Lançamento atualizado com sucesso!');
            } else {
                set_alert('danger', 'Ocorreu um erro ao atualizar o lançamento.');
            }
            redirect(admin_url('gestaofinanceira/lancamentos'));
        }

        // Se for um GET, busca os dados do lançamento para preencher o formulário
        $data['title'] = 'Editar Lançamento';
        
        // A função 'get' ainda precisa ser criada no seu Lancamentos_model
        $data['lancamento'] = $this->lancamentos_model->get($id);
        
        $data['categorias'] = $this->lancamentos_model->get_categorias();
        $data['centros_custo'] = $this->lancamentos_model->get_centros_custo();

        $this->load->view('gestaofinanceira/lancamentos/form_lancamento', $data);
    }
}