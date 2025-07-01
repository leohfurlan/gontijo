<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Contasbancarias extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        // CORREÇÃO: Carregando os models e helpers corretos.
        $this->load->model('contasbancarias_model');
        $this->load->model('centroscusto_model');
        $this->load->helper('gestaofinanceira');
    }

    public function index($id = '')
    {
        // CORREÇÃO: Usando a permissão correta do módulo.
        if (!has_permission('gestaofinanceira', '', 'view')) {
            access_denied('gestaofinanceira');
        }

        if ($this->input->post()) {
            // A função _handle_conta_bancaria_form já faz o redirect,
            // então o return aqui é para garantir que o resto do código não execute.
            $this->_handle_conta_bancaria_form();
            return;
        }

        if (is_numeric($id)) {
            $data['conta_bancaria'] = $this->contasbancarias_model->get_conta_bancaria($id);
            if (!$data['conta_bancaria']) {
                show_404();
            }
        }

        $data['title'] = _l('gf_menu_contas_bancarias');
        $data['contas_bancarias'] = $this->contasbancarias_model->get_contas_bancarias();
        // CORREÇÃO: Buscando centros de custo do model correto.
        $data['centros_custo'] = $this->centroscusto_model->get_centros_custo(['ativo' => 1]);
        
        // Assumindo que a sua view 'manage.php' contém tanto a lista como o formulário (modal).
        $this->load->view('admin/contasbancarias/manage', $data);
    }

    public function upload()
    {
        // CORREÇÃO: Usando a permissão correta.
        if (!has_permission('gestaofinanceira', '', 'create')) {
            access_denied('gestaofinanceira');
        }

        if ($this->input->post() && isset($_FILES['arquivo_contas_bancarias'])) {
            $resultado = $this->_process_upload_contas_bancarias($_FILES['arquivo_contas_bancarias']);
            if ($resultado['success']) {
                set_alert('success', sprintf('Upload realizado com sucesso! %d registos importados.', $resultado['importados']));
            } else {
                set_alert('danger', 'Erro no upload: ' . $resultado['erro']);
            }
        }
        // CORREÇÃO: Usando o URL correto para o redirect.
        redirect(admin_url('gestaofinanceira/contasbancarias'));
    }

    public function delete($id)
    {
        // CORREÇÃO: Usando a permissão correta.
        if (!has_permission('gestaofinanceira', '', 'delete')) {
            // Se for uma chamada AJAX, retorna erro. Senão, nega o acesso.
            if ($this->input->is_ajax_request()) {
                ajax_access_denied();
            }
            access_denied('gestaofinanceira');
        }
        
        $response = $this->contasbancarias_model->delete_conta_bancaria($id);
        
        // Resposta para links _delete do Perfex (que esperam redirect com alerta)
        if ($response['success']) {
            set_alert('success', _l('deleted', _l('gf_conta_bancaria')));
        } else {
            set_alert('warning', $response['message']);
        }

        if ($this->input->is_ajax_request()) {
            echo json_encode($response);
        } else {
            redirect(admin_url('gestaofinanceira/contasbancarias'));
        }
    }

    private function _handle_conta_bancaria_form()
    {
        $data = $this->input->post();
        if (isset($data['id']) && !empty($data['id'])) {
            $success = $this->contasbancarias_model->update_conta_bancaria($data['id'], $data);
            $message = $success ? _l('updated_successfully', _l('gf_conta_bancaria')) : _l('problem_updating', _l('gf_conta_bancaria'));
        } else {
            $id = $this->contasbancarias_model->add_conta_bancaria($data);
            $success = $id > 0;
            $message = $success ? _l('added_successfully', _l('gf_conta_bancaria')) : _l('problem_adding', _l('gf_conta_bancaria'));
        }
        set_alert($success ? 'success' : 'danger', $message);
        // CORREÇÃO: Usando o URL correto para o redirect.
        redirect(admin_url('gestaofinanceira/contasbancarias'));
    }

    private function _process_upload_contas_bancarias($arquivo)
    {
        try {
            $dados = $this->_read_uploaded_file($arquivo);
            $importados = 0;
            foreach ($dados as $linha) {
                if (empty($linha[0])) continue;
                $conta_bancaria = [
                    'banco' => $linha[0] ?? '',
                    'agencia' => $linha[1] ?? '',
                    'conta' => $linha[2] ?? '',
                    'saldo_inicial' => floatval($linha[3] ?? 0),
                    'data_saldo_inicial' => $linha[4] ?? date('Y-m-d'),
                    'id_centro_custo' => intval($linha[5] ?? 1)
                ];
                if ($this->contasbancarias_model->add_conta_bancaria($conta_bancaria)) {
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

        // CORREÇÃO: O nome do campo do ficheiro deve ser consistente.
        // O método upload() passa $_FILES['arquivo_contas_bancarias'], então o do_upload() deve usar o nome do campo.
        if (!$this->upload->do_upload('arquivo_contas_bancarias')) {
            throw new Exception($this->upload->display_errors('', ''));
        }
        
        $upload_data = $this->upload->data();
        $file_path = $upload_data['full_path'];
        $file_ext = strtolower($upload_data['file_ext']);
        $dados = [];
        if ($file_ext == '.csv') {
            if (($handle = fopen($file_path, 'r')) !== false) {
                $primeira_linha = true;
                // Usando ';' como delimitador, que é comum em ficheiros CSV no Brasil.
                while (($data = fgetcsv($handle, 1000, ';')) !== false) {
                    if ($primeira_linha) { $primeira_linha = false; continue; }
                    $dados[] = $data;
                }
                fclose($handle);
            }
        } else {
            require_once APPPATH . 'third_party/PhpSpreadsheet/vendor/autoload.php';
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($file_ext == '.xlsx' ? 'Xlsx' : 'Xls');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file_path);
            $worksheet = $spreadsheet->getActiveSheet();
            // Começa da linha 2 para ignorar o cabeçalho
            foreach ($worksheet->getRowIterator(2) as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                $rowData = [];
                foreach ($cellIterator as $cell) {
                    $rowData[] = $cell->getValue();
                }
                $dados[] = $rowData;
            }
        }
        unlink($file_path);
        return $dados;
    }

    /**
     * Adiciona ou Edita uma Conta Bancária.
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
                $insert_id = $this->contasbancarias_model->add_conta_bancaria($data);
                if ($insert_id) {
                    set_alert('success', _l('added_successfully', _l('gf_conta_bancaria')));
                }
            } else {
                // Editar
                if (!has_permission('gestaofinanceira', '', 'edit')) {
                    access_denied('gestaofinanceira');
                }
                $success = $this->contasbancarias_model->update_conta_bancaria($id, $data);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('gf_conta_bancaria')));
                }
            }
            redirect(admin_url('gestaofinanceira/contasbancarias'));
        }

        if ($id == '') {
            $title = 'Adicionar Nova Conta Bancária';
        } else {
            $data['conta_bancaria'] = $this->contasbancarias_model->get_conta_bancaria($id);
            $title = 'Editar Conta Bancária: ' . ($data['conta_bancaria']['banco'] ?? '');
        }

        $data['title'] = $title;
        $data['centros_custo'] = $this->centroscusto_model->get_centros_custo(['ativo' => 1]);
        
        // Carrega a view do formulário
        $this->load->view('admin/contasbancarias/conta_form', $data);
    }
}
