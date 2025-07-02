<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lancamentos extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('lancamentos_model');
        $this->load->model('planocontas_model');
        $this->load->model('entidades_model');
        $this->load->model('centroscusto_model');
        $this->load->helper('gestaofinanceira');
    }

    public function index()
    {
        if (!has_permission('gestaofinanceira', '', 'view')) {
            access_denied('gestaofinanceira');
        }
        
        $data['lancamentos'] = $this->lancamentos_model->get_all_lancamentos();
        $data['title'] = _l('gf_menu_lancamentos');
        
        $this->load->view('admin/lancamentos/manage', $data);
    }

    public function lancamento($id = '')
    {
        if ($this->input->post()) {
            $data = $this->input->post();

            if (isset($data['data_vencimento'])) {
                $data['data_vencimento'] = to_sql_date($data['data_vencimento']);
            }
            if (isset($data['data_liquidacao']) && !empty($data['data_liquidacao'])) {
                $data['data_liquidacao'] = to_sql_date($data['data_liquidacao']);
            } else {
                $data['data_liquidacao'] = null;
            }

            if ($id == '') {
                // Adicionar
                $insert_id = $this->lancamentos_model->add_lancamento($data);
                if ($insert_id) {
                    set_alert('success', _l('added_successfully', _l('lancamento')));
                    redirect(admin_url('gestaofinanceira/lancamentos'));
                }
            } else {
                // Editar
                $success = $this->lancamentos_model->update_lancamento($id, $data);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('lancamento')));
                }
                redirect(admin_url('gestaofinanceira/lancamentos'));
            }
        }

        if ($id == '') {
            $title = 'Adicionar Novo Lançamento';
        } else {
            $data['lancamento'] = $this->lancamentos_model->get_lancamento($id);
            $title = 'Editar Lançamento: ' . ($data['lancamento']['descricao'] ?? '');
        }

        $data['title']           = $title;
        $data['contas']          = $this->planocontas_model->get_contas_lancamento();
        $data['centros_custo']   = $this->centroscusto_model->get_centros_custo(['ativo' => 1]);
        $data['entidades']       = $this->entidades_model->get_entidades(['ativo' => 1]);
        
        $this->load->view('admin/lancamentos/form_lancamento', $data);
    }
    
    public function delete($id)
    {
        if (!has_permission('gestaofinanceira', '', 'delete')) {
            access_denied('gestaofinanceira');
        }
        if (!$id) {
            redirect(admin_url('gestaofinanceira/lancamentos'));
        }
        $success = $this->lancamentos_model->delete_lancamento($id);
        if ($success) {
            set_alert('success', _l('deleted', _l('lancamento')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('lancamento')));
        }
        redirect(admin_url('gestaofinanceira/lancamentos'));
    }
    /**
     * NOVO: Processa o upload do ficheiro de lançamentos.
     */
    public function upload()
    {
        if (!has_permission('gestaofinanceira', '', 'create')) {
            access_denied('gestaofinanceira');
        }

        if ($this->input->post() && isset($_FILES['arquivo_lancamentos'])) {
            try {
                $dados = gf_read_spreadsheet_file('arquivo_lancamentos');
                
                $importados = 0;
                foreach ($dados as $linha) {
                    if (empty($linha['A'])) continue;

                    $lancamento = [
                        'descricao'         => $linha['A'] ?? '',
                        'valor'             => floatval($linha['B'] ?? 0),
                        'data_vencimento'   => to_sql_date($linha['C']),
                        'data_competencia'  => to_sql_date($linha['D']),
                        'id_plano_contas'   => intval($linha['E'] ?? 0),
                        'id_centro_custo'   => intval($linha['F'] ?? 0),
                        'id_entidade'       => intval($linha['G'] ?? 0),
                        'status'            => $linha['H'] ?? 'A Pagar',
                    ];

                    if ($this->lancamentos_model->add_lancamento($lancamento)) {
                        $importados++;
                    }
                }
                set_alert('success', sprintf('Upload realizado com sucesso! %d registos importados.', $importados));

            } catch (Exception $e) {
                set_alert('danger', 'Erro no upload: ' . $e->getMessage());
            }
        }

        redirect(admin_url('gestaofinanceira/lancamentos'));
    }
    
    /**
     * NOVO: Gera e descarrega um ficheiro de exemplo para importação de lançamentos.
     */
    public function download_sample()
    {
        require_once(module_dir_path('gestaofinanceira', 'vendor/autoload.php'));

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $headers = [
            'Descrição',
            'Valor',
            'Data Vencimento (DD/MM/YYYY)',
            'Data Competência (DD/MM/YYYY)',
            'ID da Categoria (Plano de Contas)',
            'ID do Centro de Custo',
            'ID da Entidade (Cliente/Fornecedor)',
            'Status (Ex: A Pagar, Pago, A Receber)',
        ];
        $sheet->fromArray($headers, NULL, 'A1');

        $example_data = [
            'Compra de Ração para o Lote 05',
            '2500.50',
            date('d/m/Y', strtotime('+15 days')),
            date('d/m/Y'),
            '8', // Exemplo: ID da conta "Alimentação Animal"
            '1', // Exemplo: ID do centro de custo "Fazenda Jacamim"
            '3', // Exemplo: ID da entidade "Fornecedor Padrão"
            'A Pagar',
        ];
        $sheet->fromArray($example_data, NULL, 'A2');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'modelo_importacao_lancamentos.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit();
    }
}

