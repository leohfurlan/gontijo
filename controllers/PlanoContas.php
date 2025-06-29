<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PlanoContas extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('gestaofinanceira_model');
        $this->load->helper('gestaofinanceira');
    }

    public function index($id = '')
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

    public function upload()
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

    public function delete($id)
    {
        if (!has_permission('gestao_fazendas', '', 'delete')) {
            ajax_access_denied();
        }
        $success = $this->gestaofinanceira_model->delete_conta($id);
        echo json_encode(['success' => $success]);
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

    private function _process_upload_plano_contas($arquivo)
    {
        try {
            $dados = $this->_read_uploaded_file($arquivo);
            $importados = 0;
            foreach ($dados as $linha) {
                if (empty($linha[0])) continue;
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

    private function _read_uploaded_file($file)
    {
        $this->load->library('upload');
        $path = FCPATH . 'uploads/tmp/';
        if (!is_dir($path)) { mkdir($path, 0755, true); }
        $config['upload_path'] = $path;
        $config['allowed_types'] = 'csv|xls|xlsx';
        $this->upload->initialize($config);
        if (!$this->upload->do_upload($file)) {
            throw new Exception($this->upload->display_errors('', ''));
        }
        $upload_data = $this->upload->data();
        $file_path = $upload_data['full_path'];
        $file_ext = strtolower($upload_data['file_ext']);
        $dados = [];
        if ($file_ext == '.csv') {
            if (($handle = fopen($file_path, 'r')) !== false) {
                $primeira_linha = true;
                while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                    if ($primeira_linha) { $primeira_linha = false; continue; }
                    $dados[] = $data;
                }
                fclose($handle);
            }
        } else {
            require_once APPPATH . 'third_party/PhpSpreadsheet/vendor/autoload.php';
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($file_ext == '.xlsx' ? 'Xlsx' : 'Xls');
            $spreadsheet = $reader->load($file_path);
            $worksheet = $spreadsheet->getActiveSheet();
            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();
            for ($row = 2; $row <= $highestRow; $row++) {
                $rowData = [];
                for ($col = 'A'; $col <= $highestColumn; $col++) {
                    $rowData[] = $worksheet->getCell($col . $row)->getCalculatedValue();
                }
                $dados[] = $rowData;
            }
        }
        unlink($file_path);
        return $dados;
    }
}
