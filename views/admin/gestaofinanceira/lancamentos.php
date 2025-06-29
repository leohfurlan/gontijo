<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h4 class="no-margin"><?php echo $title; ?></h4>
                            </div>
                            <div class="col-md-4 text-right">
                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalNovoLancamento">
                                    <i class="fa fa-plus"></i> <?php echo _l('gf_btn_novo'); ?>
                                </button>
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalUploadLancamentos">
                                    <i class="fa fa-upload"></i> <?php echo _l('gf_btn_upload'); ?>
                                </button>
                                <a href="<?php echo admin_url('gestaofinanceira/download_template/lancamentos'); ?>" class="btn btn-default">
                                    <i class="fa fa-download"></i> <?php echo _l('gf_btn_download_template'); ?>
                                </a>
                            </div>
                        </div>
                        <hr class="hr-panel-heading" />

                        <!-- Filtros -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <form method="GET" action="<?php echo admin_url('gestaofinanceira/lancamentos'); ?>">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label><?php echo _l('gf_filtro_data_inicio'); ?></label>
                                                        <input type="date" name="data_inicio" class="form-control" 
                                                               value="<?php echo $this->input->get('data_inicio'); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label><?php echo _l('gf_filtro_data_fim'); ?></label>
                                                        <input type="date" name="data_fim" class="form-control" 
                                                               value="<?php echo $this->input->get('data_fim'); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label><?php echo _l('gf_filtro_centro_custo'); ?></label>
                                                        <select name="centro_custo" class="form-control">
                                                            <option value="">Todos</option>
                                                            <?php foreach ($centros_custo as $centro): ?>
                                                            <option value="<?php echo $centro['id']; ?>" 
                                                                    <?php echo $this->input->get('centro_custo') == $centro['id'] ? 'selected' : ''; ?>>
                                                                <?php echo $centro['nome']; ?>
                                                            </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>&nbsp;</label><br>
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="fa fa-search"></i> Filtrar
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabela de Lançamentos -->
                        <div class="table-responsive">
                            <table class="table table-striped dt-table" id="tabelaLancamentos">
                                <thead>
                                    <tr>
                                        <th><?php echo _l('gf_lancamentos_descricao'); ?></th>
                                        <th><?php echo _l('gf_lancamentos_valor'); ?></th>
                                        <th><?php echo _l('gf_plano_contas_nome'); ?></th>
                                        <th><?php echo _l('gf_centros_custo_nome'); ?></th>
                                        <th><?php echo _l('gf_lancamentos_data_vencimento'); ?></th>
                                        <th><?php echo _l('gf_lancamentos_status'); ?></th>
                                        <th><?php echo _l('gf_lancamentos_tipo'); ?></th>
                                        <th width="100"><?php echo _l('options'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($lancamentos as $lancamento): ?>
                                    <tr>
                                        <td><?php echo $lancamento['descricao']; ?></td>
                                        <td class="<?php echo $lancamento['tipo_conta'] == 'Receita' ? 'text-success' : 'text-danger'; ?>">
                                            R$ <?php echo number_format($lancamento['valor'], 2, ',', '.'); ?>
                                        </td>
                                        <td><?php echo $lancamento['nome_conta']; ?></td>
                                        <td><?php echo $lancamento['centro_custo_nome']; ?></td>
                                        <td><?php echo _d($lancamento['data_vencimento']); ?></td>
                                        <td>
                                            <span class="label label-<?php 
                                                echo $lancamento['status'] == 'Pago' || $lancamento['status'] == 'Recebido' ? 'success' : 'warning'; 
                                            ?>">
                                                <?php echo _l('gf_status_' . strtolower(str_replace(' ', '_', $lancamento['status']))); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="label label-<?php echo $lancamento['tipo_lancamento'] == 'Realizado' ? 'primary' : 'info'; ?>">
                                                <?php echo _l('gf_tipo_' . strtolower($lancamento['tipo_lancamento'])); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                                    <i class="fa fa-cog"></i> <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    <li><a href="#" onclick="editarLancamento(<?php echo $lancamento['id']; ?>)">
                                                        <i class="fa fa-edit"></i> <?php echo _l('gf_btn_editar'); ?>
                                                    </a></li>
                                                    <li><a href="#" onclick="excluirLancamento(<?php echo $lancamento['id']; ?>)">
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

<!-- Modal Novo Lançamento -->
<div class="modal fade" id="modalNovoLancamento" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo _l('gf_lancamentos_novo'); ?></h4>
            </div>
            <form action="<?php echo admin_url('gestaofinanceira/lancamentos'); ?>" method="POST" id="formLancamento">
                <div class="modal-body">
                    <input type="hidden" name="id" id="lancamento_id">
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="descricao"><?php echo _l('gf_lancamentos_descricao'); ?> *</label>
                                <textarea name="descricao" id="descricao" class="form-control" rows="3" required></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="valor"><?php echo _l('gf_lancamentos_valor'); ?> *</label>
                                <input type="number" name="valor" id="valor" class="form-control" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_plano_contas"><?php echo _l('gf_plano_contas_nome'); ?> *</label>
                                <select name="id_plano_contas" id="id_plano_contas" class="form-control selectpicker" 
                                        data-live-search="true" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach ($plano_contas as $conta): ?>
                                    <option value="<?php echo $conta['id']; ?>">
                                        <?php echo $conta['codigo_conta'] . ' - ' . $conta['nome_conta']; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_centro_custo"><?php echo _l('gf_centros_custo_nome'); ?> *</label>
                                <select name="id_centro_custo" id="id_centro_custo" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach ($centros_custo as $centro): ?>
                                    <option value="<?php echo $centro['id']; ?>"><?php echo $centro['nome']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_entidade"><?php echo _l('gf_entidades_nome_razao'); ?></label>
                                <select name="id_entidade" id="id_entidade" class="form-control selectpicker" 
                                        data-live-search="true">
                                    <option value="">Selecione...</option>
                                    <?php foreach ($entidades as $entidade): ?>
                                    <option value="<?php echo $entidade['id']; ?>">
                                        <?php echo $entidade['nome_razao_social']; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="data_competencia"><?php echo _l('gf_lancamentos_data_competencia'); ?> *</label>
                                <input type="date" name="data_competencia" id="data_competencia" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="data_vencimento"><?php echo _l('gf_lancamentos_data_vencimento'); ?> *</label>
                                <input type="date" name="data_vencimento" id="data_vencimento" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="data_liquidacao"><?php echo _l('gf_lancamentos_data_liquidacao'); ?></label>
                                <input type="date" name="data_liquidacao" id="data_liquidacao" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tipo_lancamento"><?php echo _l('gf_lancamentos_tipo'); ?> *</label>
                                <select name="tipo_lancamento" id="tipo_lancamento" class="form-control" required>
                                    <option value="Realizado"><?php echo _l('gf_tipo_realizado'); ?></option>
                                    <option value="Orçado"><?php echo _l('gf_tipo_orcado'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="status"><?php echo _l('gf_lancamentos_status'); ?> *</label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="A Pagar"><?php echo _l('gf_status_a_pagar'); ?></option>
                                    <option value="Pago"><?php echo _l('gf_status_pago'); ?></option>
                                    <option value="A Receber"><?php echo _l('gf_status_a_receber'); ?></option>
                                    <option value="Recebido"><?php echo _l('gf_status_recebido'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="id_conta_bancaria"><?php echo _l('gf_contas_bancarias_title'); ?></label>
                                <select name="id_conta_bancaria" id="id_conta_bancaria" class="form-control">
                                    <option value="">Selecione...</option>
                                    <?php foreach ($contas_bancarias as $conta): ?>
                                    <option value="<?php echo $conta['id']; ?>">
                                        <?php echo $conta['banco'] . ' - ' . $conta['agencia'] . '/' . $conta['conta']; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="numero_nf"><?php echo _l('gf_lancamentos_numero_nf'); ?></label>
                                <input type="text" name="numero_nf" id="numero_nf" class="form-control">
                            </div>
                        </div>
                    </div>

                    <!-- Checkbox para Lançamento Recorrente -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="recorrente" name="recorrente" value="1">
                                    Lançamento Recorrente
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Campos para Recorrência (ocultos inicialmente) -->
                    <div id="camposRecorrencia" style="display: none;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="frequencia">Frequência</label>
                                    <select name="frequencia" id="frequencia" class="form-control">
                                        <option value="mensal">Mensal</option>
                                        <option value="bimestral">Bimestral</option>
                                        <option value="trimestral">Trimestral</option>
                                        <option value="semestral">Semestral</option>
                                        <option value="anual">Anual</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="quantidade_parcelas">Quantidade de Parcelas</label>
                                    <input type="number" name="quantidade_parcelas" id="quantidade_parcelas" 
                                           class="form-control" min="1" max="60" value="12">
                                </div>
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
<div class="modal fade" id="modalUploadLancamentos" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo _l('gf_upload_title'); ?></h4>
            </div>
            <form action="<?php echo admin_url('gestaofinanceira/upload_lancamentos'); ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label><?php echo _l('gf_upload_instrucoes'); ?></label>
                        <p class="text-muted">
                            <?php echo _l('gf_upload_formatos_aceitos'); ?><br>
                            <?php echo _l('gf_upload_tamanho_maximo'); ?>
                        </p>
                    </div>
                    <div class="form-group">
                        <label for="arquivo_lancamentos"><?php echo _l('gf_upload_selecionar_arquivo'); ?></label>
                        <input type="file" name="arquivo_lancamentos" id="arquivo_lancamentos" 
                               class="form-control" accept=".xls,.xlsx,.csv" required>
                    </div>
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> <?php echo _l('gf_upload_template_info'); ?>
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
    $('#tabelaLancamentos').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
        },
        "order": [[4, "desc"]]
    });

    // Mostrar/ocultar campos de recorrência
    $('#recorrente').change(function() {
        if ($(this).is(':checked')) {
            $('#camposRecorrencia').show();
        } else {
            $('#camposRecorrencia').hide();
        }
    });

    // Atualizar status baseado na data de liquidação
    $('#data_liquidacao').change(function() {
        var dataLiquidacao = $(this).val();
        var statusSelect = $('#status');
        
        if (dataLiquidacao) {
            var statusAtual = statusSelect.val();
            if (statusAtual === 'A Pagar') {
                statusSelect.val('Pago');
            } else if (statusAtual === 'A Receber') {
                statusSelect.val('Recebido');
            }
        }
    });
});

function editarLancamento(id) {
    // Implementar edição via AJAX
    $.get('<?php echo admin_url("gestaofinanceira/lancamentos/"); ?>' + id, function(data) {
        // Preencher formulário com dados do lançamento
        // Implementar conforme necessário
    });
}

function excluirLancamento(id) {
    if (confirm('<?php echo _l("gf_msg_confirmar_exclusao"); ?>')) {
        $.post('<?php echo admin_url("gestaofinanceira/delete_lancamento/"); ?>' + id, function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert('Erro ao excluir o lançamento.');
            }
        }, 'json');
    }
}
</script>

<?php init_tail(); ?>

