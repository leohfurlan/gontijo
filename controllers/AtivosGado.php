<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ativosgado extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ativosgado_model');
        $this->load->model('centroscusto_model');
        $this->load->helper('gestaofinanceira');
    }

    public function index()
    {
        if (!has_permission('gestaofinanceira', '', 'view')) {
            access_denied('gestaofinanceira');
        }

        $data['title'] = _l('gf_menu_ativos_gado');
        $data['summary'] = $this->ativosgado_model->get_rebanho_summary();
        $data['ativos_gado'] = $this->ativosgado_model->get_ativos_gado();
        
        $this->load->view('admin/ativosgado/manage', $data);
    }

    public function ativo($id = '')
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            if ($id == '') {
                // Adicionar
                $insert_id = $this->ativosgado_model->add_ativo_gado($data);
                if ($insert_id) {
                    set_alert('success', _l('added_successfully', _l('gf_ativo_gado')));
                }
            } else {
                // Editar
                $success = $this->ativosgado_model->update_ativo_gado($id, $data);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('gf_ativo_gado')));
                }
            }
            redirect(admin_url('gestaofinanceira/ativosgado'));
        }

        if ($id == '') {
            $title = 'Adicionar Novo Lote de Gado';
        } else {
            $data['ativo_gado'] = $this->ativosgado_model->get_ativo_gado($id);
            $title = 'Editar Lote de Gado: ' . ($data['ativo_gado']['descricao_lote'] ?? '');
        }

        $data['title']              = $title;
        $data['centros_custo']      = $this->centroscusto_model->get_centros_custo(['ativo' => 1]);
        $data['lancamentos_compra'] = $this->ativosgado_model->get_lancamentos_compra_gado();
        
        $this->load->view('admin/ativosgado/ativo_form', $data);
    }

    public function delete($id)
    {
        if (!has_permission('gestaofinanceira', '', 'delete')) {
            access_denied('gestaofinanceira');
        }
        if (!$id) {
            redirect(admin_url('gestaofinanceira/ativosgado'));
        }
        $response = $this->ativosgado_model->delete_ativo_gado($id);
        if ($response) {
            set_alert('success', _l('deleted', _l('gf_ativo_gado')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('gf_ativo_gado')));
        }
        redirect(admin_url('gestaofinanceira/ativosgado'));
    }

    public function upload()
    {
        if (!has_permission('gestaofinanceira', '', 'create')) {
            access_denied('gestaofinanceira');
        }

        if ($this->input->post() && isset($_FILES['arquivo_ativos_gado'])) {
            try {
                $dados = gf_read_spreadsheet_file('arquivo_ativos_gado');
                
                $importados = 0;
                foreach ($dados as $linha) {
                    if (empty($linha['A'])) continue;

                    $ativo_gado = [
                        'descricao_lote'        => $linha['A'] ?? '',
                        'data_entrada'          => to_sql_date($linha['B']),
                        'categoria'             => $linha['C'] ?? '',
                        'quantidade_cabecas'    => intval($linha['D'] ?? 0),
                        'peso_medio_entrada'    => floatval($linha['E'] ?? 0),
                        'custo_total_aquisicao' => floatval($linha['F'] ?? 0),
                        'id_centro_custo'       => intval($linha['G'] ?? 1)
                    ];

                    if ($this->ativosgado_model->add_ativo_gado($ativo_gado)) {
                        $importados++;
                    }
                }
                set_alert('success', sprintf('Upload realizado com sucesso! %d registos importados.', $importados));

            } catch (Exception $e) {
                set_alert('danger', 'Erro no upload: ' . $e->getMessage());
            }
        }

        redirect(admin_url('gestaofinanceira/ativosgado'));
    }
    
    public function download_sample()
    {
        // CORREÇÃO: O caminho agora aponta para a pasta 'vendor' na raiz do módulo.
        require_once(module_dir_path('gestaofinanceira', 'vendor/autoload.php'));

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $headers = [
            'Descrição do Lote',
            'Data de Entrada (DD/MM/YYYY)',
            'Categoria',
            'Quantidade de Cabeças',
            'Peso Médio de Entrada (kg)',
            'Custo Total de Aquisição',
            'ID do Centro de Custo',
        ];
        $sheet->fromArray($headers, NULL, 'A1');

        $example_data = [
            'Lote de Bezerros Nelore 01',
            date('d/m/Y'),
            'Bezerros',
            50,
            180,
            75000.00,
            1,
        ];
        $sheet->fromArray($example_data, NULL, 'A2');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'modelo_importacao_ativos.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit();
    }
}
