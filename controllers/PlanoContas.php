<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Planocontas extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('planocontas_model');
        // CORREÇÃO: Carregando o helper correto.
        $this->load->helper('gestaofinanceira');
    }

    /**
     * Exibe a página com a lista hierárquica do Plano de Contas.
     */
    public function index()
    {
        if (!has_permission('gestaofinanceira', '', 'view')) {
            access_denied('gestaofinanceira');
        }

        $data['contas'] = $this->planocontas_model->get_plano_contas_hierarquico();
        $data['title'] = _l('gf_menu_plano_contas');
        
        $this->load->view('admin/planocontas/manage', $data);
    }

    /**
     * Adiciona ou Edita uma Conta.
     * @param int $id O ID da conta a ser editada (opcional)
     */
    public function conta($id = '')
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            if ($id == '') {
                // Adicionar
                if (!has_permission('gestaofinanceira', '', 'create')) {
                    access_denied('gestaofinanceira');
                }
                $insert_id = $this->planocontas_model->add_conta($data);
                if ($insert_id) {
                    set_alert('success', _l('added_successfully', _l('gf_conta')));
                }
            } else {
                // Editar
                if (!has_permission('gestaofinanceira', '', 'edit')) {
                    access_denied('gestaofinanceira');
                }
                $success = $this->planocontas_model->update_conta($id, $data);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('gf_conta')));
                }
            }
            redirect(admin_url('gestaofinanceira/planocontas'));
        }

        if ($id == '') {
            $title = 'Adicionar Nova Conta';
        } else {
            $data['conta'] = $this->planocontas_model->get_conta($id);
            $title = 'Editar Conta: ' . $data['conta']['nome_conta'];
        }

        $data['title'] = $title;
        // Busca as contas sintéticas (pai) para o dropdown do formulário
        $data['contas_pai'] = $this->planocontas_model->get_contas_pai();
        
        $this->load->view('admin/planocontas/conta_form', $data);
    }

    /**
     * Deleta uma Conta.
     * @param int $id
     */
    public function delete($id)
    {
        if (!has_permission('gestaofinanceira', '', 'delete')) {
            access_denied('gestaofinanceira');
        }
        if (!$id) {
            redirect(admin_url('gestaofinanceira/planocontas'));
        }
        $response = $this->planocontas_model->delete_conta($id);
        if ($response['success']) {
            set_alert('success', _l('deleted', _l('gf_conta')));
        } else {
            set_alert('warning', $response['message']);
        }
        redirect(admin_url('gestaofinanceira/planocontas'));
    }
    /**
     * NOVO: Processa o upload do ficheiro do plano de contas.
     */
    public function upload()
    {
        if (!has_permission('gestaofinanceira', '', 'create')) {
            access_denied('gestaofinanceira');
        }

        if ($this->input->post() && isset($_FILES['arquivo_plano_contas'])) {
            try {
                $dados = gf_read_spreadsheet_file('arquivo_plano_contas');
                
                $importados = 0;
                foreach ($dados as $linha) {
                    if (empty($linha['A'])) continue;

                    $conta = [
                        'codigo_conta'      => $linha['A'] ?? '',
                        'nome_conta'        => $linha['B'] ?? '',
                        'tipo_conta'        => $linha['C'] ?? 'Despesa',
                        'grupo_dre'         => $linha['D'] ?? '',
                        'aceita_lancamento' => (isset($linha['E']) && strtoupper($linha['E']) == 'SIM') ? 1 : 0,
                    ];

                    if ($this->planocontas_model->add_conta($conta)) {
                        $importados++;
                    }
                }
                set_alert('success', sprintf('Upload realizado com sucesso! %d registos importados.', $importados));

            } catch (Exception $e) {
                set_alert('danger', 'Erro no upload: ' . $e->getMessage());
            }
        }

        redirect(admin_url('gestaofinanceira/planocontas'));
    }
    
    /**
     * NOVO: Gera e descarrega um ficheiro de exemplo para importação.
     */
    public function download_sample()
    {
        require_once(module_dir_path('gestaofinanceira', 'vendor/autoload.php'));

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $headers = [
            'Código da Conta',
            'Nome da Conta',
            'Tipo (Receita ou Despesa)',
            'Grupo DRE',
            'Aceita Lançamento (SIM ou NAO)',
        ];
        $sheet->fromArray($headers, NULL, 'A1');

        $example_data = [
            '5.3.1.01',
            'Manutenção de Veículos',
            'Despesa',
            'Custo Fixo',
            'SIM',
        ];
        $sheet->fromArray($example_data, NULL, 'A2');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'modelo_importacao_plano_contas.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit();
    }
}

