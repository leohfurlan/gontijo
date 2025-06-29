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
     * Exibe a página principal de Lançamentos (tabela e botão)
     */
    public function index()
    {
        $data['lancamentos'] = $this->lancamentos_model->get_all_lancamentos();
        $data['centros_custo'] = $this->lancamentos_model->get_centros_custo();
        
        $data['title'] = _l('gf_menu_lancamentos');
        $this->load->view('gestaofinanceira/lancamentos/manage', $data);
    }

    /**
     * Prepara o formulário para Adicionar e retorna para o modal
     */
    public function form_lancamento()
    {
        // Se a requisição for um POST, salva os dados.
        if ($this->input->post()) {
            $success = $this->lancamentos_model->add($this->input->post());
            $message = $success ? 'Lançamento adicionado com sucesso!' : 'Erro ao adicionar lançamento.';
            echo json_encode(['success' => $success, 'message' => $message]);
            die;
        }

        // Se for um GET (clique no botão), carrega os dados e mostra o formulário.
        $data['title'] = 'Adicionar Novo Lançamento';
        $data['categorias'] = $this->lancamentos_model->get_categorias();
        $data['centros_custo'] = $this->lancamentos_model->get_centros_custo();

        $this->load->view('gestaofinanceira/lancamentos/form_lancamento', $data);
    }
}