<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
<?php $this->load->view('admin/gestaofinanceira/_nav'); ?>
                        <div class="row">
                            <div class="col-md-8">
                                <h4 class="no-margin"><?php echo $title; ?></h4>
                            </div>
                            <div class="col-md-4 text-right">
                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalNovaConta">
                                    <i class="fa fa-plus"></i> <?php echo _l('gf_btn_novo'); ?>
                                </button>
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalUploadPlanoContas">
                                    <i class="fa fa-upload"></i> <?php echo _l('gf_btn_upload'); ?>
                                </button>
                                <a href="<?php echo admin_url('gestaofinanceira/download_template/plano_contas'); ?>" class="btn btn-default">
                                    <i class="fa fa-download"></i> <?php echo _l('gf_btn_download_template'); ?>
                                </a>
                            </div>
                        </div>
                        <hr class="hr-panel-heading" />

                        <!-- Estrutura em Árvore do Plano de Contas -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <div id="plano-contas-tree">
                                            <?php echo $this->_render_plano_contas_tree($plano_contas); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabela Detalhada -->
                        <div class="table-responsive">
                            <table class="table table-striped dt-table" id="tabelaPlanoContas">
                                <thead>
                                    <tr>
                                        <th><?php echo _l('gf_plano_contas_codigo'); ?></th>
                                        <th><?php echo _l('gf_plano_contas_nome'); ?></th>
                                        <th><?php echo _l('gf_plano_contas_tipo'); ?></th>
                                        <th><?php echo _l('gf_plano_contas_grupo_dre'); ?></th>
                                        <th><?php echo _l('gf_plano_contas_nivel'); ?></th>
                                        <th><?php echo _l('gf_plano_contas_ativo'); ?></th>
                                        <th width="100"><?php echo _l('options'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($plano_contas as $conta): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo $conta['codigo_conta']; ?></strong>
                                        </td>
                                        <td>
                                            <?php echo str_repeat('&nbsp;&nbsp;&nbsp;', $conta['nivel'] - 1); ?>
                                            <?php if ($conta['nivel'] > 1): ?>
                                                <i class="fa fa-angle-right text-muted"></i>
                                            <?php endif; ?>
                                            <?php echo $conta['nome_conta']; ?>
                                        </td>
                                        <td>
                                            <span class="label label-<?php 
                                                echo $conta['tipo_conta'] == 'Receita' ? 'success' : 
                                                    ($conta['tipo_conta'] == 'Despesa' ? 'danger' : 'info'); 
                                            ?>">
                                                <?php echo _l('gf_tipo_' . strtolower($conta['tipo_conta'])); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($conta['grupo_dre']): ?>
                                                <span class="label label-default"><?php echo $conta['grupo_dre']; ?></span>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge badge-info"><?php echo $conta['nivel']; ?></span>
                                        </td>
                                        <td>
                                            <span class="label label-<?php echo $conta['ativo'] ? 'success' : 'danger'; ?>">
                                                <?php echo $conta['ativo'] ? 'Ativo' : 'Inativo'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                                    <i class="fa fa-cog"></i> <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    <li><a href="#" onclick="editarConta(<?php echo $conta['id']; ?>)">
                                                        <i class="fa fa-edit"></i> <?php echo _l('gf_btn_editar'); ?>
                                                    </a></li>
                                                    <li><a href="#" onclick="adicionarSubconta(<?php echo $conta['id']; ?>)">
                                                        <i class="fa fa-plus"></i> Adicionar Subconta
                                                    </a></li>
                                                    <li><a href="#" onclick="excluirConta(<?php echo $conta['id']; ?>)">
                                                        <i class="fa fa-trash"></i> <?php echo _l('gf_btn_excluir'); ?>
                                                    </a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nova Conta -->
<div class="modal fade" id="modalNovaConta" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo _l('gf_plano_contas_novo'); ?></h4>
            </div>
            <form action="<?php echo admin_url('gestaofinanceira/plano_contas'); ?>" method="POST" id="formConta">
                <div class="modal-body">
                    <input type="hidden" name="id" id="conta_id">
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="codigo_conta"><?php echo _l('gf_plano_contas_codigo'); ?> *</label>
                                <input type="text" name="codigo_conta" id="codigo_conta" class="form-control" 
                                       placeholder="Ex: 1.1.01" required>
                                <small class="text-muted">Formato: 1.1.01 (Grupo.Subgrupo.Conta)</small>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="nome_conta"><?php echo _l('gf_plano_contas_nome'); ?> *</label>
                                <input type="text" name="nome_conta" id="nome_conta" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tipo_conta"><?php echo _l('gf_plano_contas_tipo'); ?> *</label>
                                <select name="tipo_conta" id="tipo_conta" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <option value="Receita"><?php echo _l('gf_tipo_receita'); ?></option>
                                    <option value="Despesa"><?php echo _l('gf_tipo_despesa'); ?></option>
                                    <option value="Ativo"><?php echo _l('gf_tipo_ativo'); ?></option>
                                    <option value="Passivo"><?php echo _l('gf_tipo_passivo'); ?></option>
                                    <option value="Patrimônio"><?php echo _l('gf_tipo_patrimonio'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="grupo_dre"><?php echo _l('gf_plano_contas_grupo_dre'); ?></label>
                                <select name="grupo_dre" id="grupo_dre" class="form-control">
                                    <option value="">Selecione...</option>
                                    <option value="Receita Operacional">Receita Operacional</option>
                                    <option value="Custo Variável">Custo Variável</option>
                                    <option value="Custo Fixo">Custo Fixo</option>
                                    <option value="Despesa Administrativa">Despesa Administrativa</option>
                                    <option value="Despesa Financeira">Despesa Financeira</option>
                                    <option value="Receita Financeira">Receita Financeira</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="conta_pai"><?php echo _l('gf_plano_contas_conta_pai'); ?></label>
                                <select name="conta_pai" id="conta_pai" class="form-control selectpicker" 
                                        data-live-search="true">
                                    <option value="">Conta Principal</option>
                                    <?php foreach ($plano_contas as $conta): ?>
                                        <?php if ($conta['nivel'] < 3): // Máximo 3 níveis ?>
                                        <option value="<?php echo $conta['id']; ?>">
                                            <?php echo $conta['codigo_conta'] . ' - ' . $conta['nome_conta']; ?>
                                        </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nivel"><?php echo _l('gf_plano_contas_nivel'); ?></label>
                                <input type="number" name="nivel" id="nivel" class="form-control" 
                                       min="1" max="3" value="1" readonly>
                                <small class="text-muted">Calculado automaticamente</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="descricao"><?php echo _l('gf_plano_contas_descricao'); ?></label>
                                <textarea name="descricao" id="descricao" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="ativo" id="ativo" value="1" checked>
                                    <?php echo _l('gf_plano_contas_ativo'); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('gf_btn_cancelar'); ?></button>
                    <button type="submit" class="btn btn-primary"><?php echo _l('gf_btn_salvar'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Upload -->
<div class="modal fade" id="modalUploadPlanoContas" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo _l('gf_upload_title'); ?></h4>
            </div>
            <form action="<?php echo admin_url('gestaofinanceira/upload_plano_contas'); ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label><?php echo _l('gf_upload_instrucoes'); ?></label>
                        <p class="text-muted">
                            <?php echo _l('gf_upload_formatos_aceitos'); ?><br>
                            <?php echo _l('gf_upload_tamanho_maximo'); ?>
                        </p>
                    </div>
                    <div class="form-group">
                        <label for="arquivo_plano_contas"><?php echo _l('gf_upload_selecionar_arquivo'); ?></label>
                        <input type="file" name="arquivo_plano_contas" id="arquivo_plano_contas" 
                               class="form-control" accept=".xls,.xlsx,.csv" required>
                    </div>
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> <?php echo _l('gf_upload_template_info'); ?>
                        <br><br>
                        <strong>Colunas do template:</strong>
                        <ul class="list-unstyled" style="margin-top: 10px;">
                            <li>• Código da Conta (Ex: 1.1.01)</li>
                            <li>• Nome da Conta</li>
                            <li>• Tipo (Receita, Despesa, Ativo, Passivo, Patrimônio)</li>
                            <li>• Grupo DRE</li>
                            <li>• Conta Pai (Código)</li>
                            <li>• Descrição</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('gf_btn_cancelar'); ?></button>
                    <button type="submit" class="btn btn-success"><?php echo _l('gf_btn_importar'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#tabelaPlanoContas').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
        },
        "order": [[0, "asc"]]
    });

    // Atualizar nível automaticamente baseado na conta pai
    $('#conta_pai').change(function() {
        var contaPai = $(this).val();
        if (contaPai) {
            // Buscar nível da conta pai via AJAX
            $.get('<?php echo admin_url("gestaofinanceira/get_conta_nivel/"); ?>' + contaPai, function(data) {
                $('#nivel').val(parseInt(data.nivel) + 1);
            }, 'json');
        } else {
            $('#nivel').val(1);
        }
    });

    // Gerar código automaticamente
    $('#tipo_conta, #conta_pai').change(function() {
        var tipo = $('#tipo_conta').val();
        var contaPai = $('#conta_pai').val();
        
        if (tipo && !contaPai) {
            // Gerar código base para conta principal
            var prefixos = {
                'Ativo': '1',
                'Passivo': '2',
                'Patrimônio': '3',
                'Receita': '4',
                'Despesa': '5'
            };
            
            if (prefixos[tipo]) {
                $('#codigo_conta').val(prefixos[tipo] + '.0.00');
            }
        }
    });
});

function editarConta(id) {
    $.get('<?php echo admin_url("gestaofinanceira/plano_contas/"); ?>' + id, function(data) {
        // Implementar preenchimento do formulário
        $('#modalNovaConta').modal('show');
    });
}

function adicionarSubconta(contaPaiId) {
    $('#conta_pai').val(contaPaiId).trigger('change');
    $('#modalNovaConta').modal('show');
}

function excluirConta(id) {
    if (confirm('<?php echo _l("gf_msg_confirmar_exclusao"); ?>')) {
        $.post('<?php echo admin_url("gestaofinanceira/delete_conta/"); ?>' + id, function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert('Erro ao excluir a conta. Verifique se não há lançamentos vinculados.');
            }
        }, 'json');
    }
}
</script>

<?php init_tail(); ?>

