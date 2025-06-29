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
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalUploadContas">
                                    <i class="fa fa-upload"></i> <?php echo _l('gf_btn_upload'); ?>
                                </button>
                                <a href="<?php echo admin_url('gestaofinanceira/download_template/contas_bancarias'); ?>" class="btn btn-default">
                                    <i class="fa fa-download"></i> <?php echo _l('gf_btn_download_template'); ?>
                                </a>
                            </div>
                        </div>
                        <hr class="hr-panel-heading" />

                        <!-- Cards das Contas Bancárias -->
                        <div class="row">
                            <?php foreach ($contas_bancarias as $conta): ?>
                            <div class="col-md-6">
                                <div class="panel panel-<?php echo $conta['ativa'] ? 'primary' : 'default'; ?>">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <i class="fa fa-bank"></i> <?php echo $conta['banco']; ?>
                                            <?php if (!$conta['ativa']): ?>
                                                <span class="label label-danger pull-right">Inativa</span>
                                            <?php endif; ?>
                                        </h4>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>Agência:</strong> <?php echo $conta['agencia']; ?><br>
                                                <strong>Conta:</strong> <?php echo $conta['conta']; ?><br>
                                                <?php if ($conta['digito']): ?>
                                                    <strong>Dígito:</strong> <?php echo $conta['digito']; ?><br>
                                                <?php endif; ?>
                                                <strong>Tipo:</strong> <?php echo $conta['tipo_conta']; ?>
                                            </div>
                                            <div class="col-md-6">
                                                <small class="text-muted">Saldo Atual</small>
                                                <h4 class="<?php echo $conta['saldo_atual'] >= 0 ? 'text-success' : 'text-danger'; ?>">
                                                    R$ <?php echo number_format($conta['saldo_atual'], 2, ',', '.'); ?>
                                                </h4>
                                                
                                                <small class="text-muted">Limite</small>
                                                <p class="text-info">
                                                    R$ <?php echo number_format($conta['limite_credito'] ?? 0, 2, ',', '.'); ?>
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <?php if ($conta['observacoes']): ?>
                                            <hr>
                                            <small class="text-muted"><?php echo $conta['observacoes']; ?></small>
                                        <?php endif; ?>
                                        
                                        <hr>
                                        
                                        <div class="btn-group btn-group-justified">
                                            <a href="<?php echo admin_url('gestaofinanceira/extrato_bancario/' . $conta['id']); ?>" 
                                               class="btn btn-default btn-sm">
                                                <i class="fa fa-list"></i> Extrato
                                            </a>
                                            <a href="#" onclick="editarConta(<?php echo $conta['id']; ?>)" 
                                               class="btn btn-default btn-sm">
                                                <i class="fa fa-edit"></i> Editar
                                            </a>
                                            <a href="#" onclick="conciliarConta(<?php echo $conta['id']; ?>)" 
                                               class="btn btn-default btn-sm">
                                                <i class="fa fa-check"></i> Conciliar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Tabela Detalhada -->
                        <div class="table-responsive">
                            <table class="table table-striped dt-table" id="tabelaContasBancarias">
                                <thead>
                                    <tr>
                                        <th><?php echo _l('gf_contas_bancarias_banco'); ?></th>
                                        <th><?php echo _l('gf_contas_bancarias_agencia'); ?></th>
                                        <th><?php echo _l('gf_contas_bancarias_conta'); ?></th>
                                        <th><?php echo _l('gf_contas_bancarias_tipo'); ?></th>
                                        <th class="text-right"><?php echo _l('gf_contas_bancarias_saldo'); ?></th>
                                        <th class="text-right">Limite</th>
                                        <th><?php echo _l('gf_contas_bancarias_ativa'); ?></th>
                                        <th width="100"><?php echo _l('options'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($contas_bancarias as $conta): ?>
                                    <tr>
                                        <td><strong><?php echo $conta['banco']; ?></strong></td>
                                        <td><?php echo $conta['agencia']; ?></td>
                                        <td>
                                            <?php echo $conta['conta']; ?>
                                            <?php if ($conta['digito']): ?>
                                                -<?php echo $conta['digito']; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="label label-<?php 
                                                echo $conta['tipo_conta'] == 'Corrente' ? 'primary' : 
                                                    ($conta['tipo_conta'] == 'Poupança' ? 'success' : 'info'); 
                                            ?>">
                                                <?php echo $conta['tipo_conta']; ?>
                                            </span>
                                        </td>
                                        <td class="text-right <?php echo $conta['saldo_atual'] >= 0 ? 'text-success' : 'text-danger'; ?>">
                                            R$ <?php echo number_format($conta['saldo_atual'], 2, ',', '.'); ?>
                                        </td>
                                        <td class="text-right text-info">
                                            R$ <?php echo number_format($conta['limite_credito'] ?? 0, 2, ',', '.'); ?>
                                        </td>
                                        <td>
                                            <span class="label label-<?php echo $conta['ativa'] ? 'success' : 'danger'; ?>">
                                                <?php echo $conta['ativa'] ? 'Ativa' : 'Inativa'; ?>
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
                                                    <li><a href="<?php echo admin_url('gestaofinanceira/extrato_bancario/' . $conta['id']); ?>">
                                                        <i class="fa fa-list"></i> Ver Extrato
                                                    </a></li>
                                                    <li><a href="#" onclick="conciliarConta(<?php echo $conta['id']; ?>)">
                                                        <i class="fa fa-check"></i> Conciliação
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

<!-- Modal Nova Conta Bancária -->
<div class="modal fade" id="modalNovaConta" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo _l('gf_contas_bancarias_novo'); ?></h4>
            </div>
            <form action="<?php echo admin_url('gestaofinanceira/contas_bancarias'); ?>" method="POST" id="formConta">
                <div class="modal-body">
                    <input type="hidden" name="id" id="conta_id">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="banco"><?php echo _l('gf_contas_bancarias_banco'); ?> *</label>
                                <select name="banco" id="banco" class="form-control selectpicker" 
                                        data-live-search="true" required>
                                    <option value="">Selecione...</option>
                                    <option value="Banco do Brasil">Banco do Brasil</option>
                                    <option value="Bradesco">Bradesco</option>
                                    <option value="Caixa Econômica Federal">Caixa Econômica Federal</option>
                                    <option value="Itaú">Itaú</option>
                                    <option value="Santander">Santander</option>
                                    <option value="Sicoob">Sicoob</option>
                                    <option value="Sicredi">Sicredi</option>
                                    <option value="Banco Inter">Banco Inter</option>
                                    <option value="Nubank">Nubank</option>
                                    <option value="C6 Bank">C6 Bank</option>
                                    <option value="Outro">Outro</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tipo_conta"><?php echo _l('gf_contas_bancarias_tipo'); ?> *</label>
                                <select name="tipo_conta" id="tipo_conta" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <option value="Corrente">Conta Corrente</option>
                                    <option value="Poupança">Conta Poupança</option>
                                    <option value="Investimento">Conta Investimento</option>
                                    <option value="Cartão">Cartão de Crédito</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="agencia"><?php echo _l('gf_contas_bancarias_agencia'); ?> *</label>
                                <input type="text" name="agencia" id="agencia" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="conta"><?php echo _l('gf_contas_bancarias_conta'); ?> *</label>
                                <input type="text" name="conta" id="conta" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="digito"><?php echo _l('gf_contas_bancarias_digito'); ?></label>
                                <input type="text" name="digito" id="digito" class="form-control" maxlength="2">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="saldo_inicial"><?php echo _l('gf_contas_bancarias_saldo_inicial'); ?></label>
                                <input type="number" name="saldo_inicial" id="saldo_inicial" class="form-control" 
                                       step="0.01" value="0.00">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="limite_credito">Limite de Crédito</label>
                                <input type="number" name="limite_credito" id="limite_credito" class="form-control" 
                                       step="0.01" value="0.00">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="observacoes"><?php echo _l('gf_contas_bancarias_observacoes'); ?></label>
                                <textarea name="observacoes" id="observacoes" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="ativa" id="ativa" value="1" checked>
                                    <?php echo _l('gf_contas_bancarias_ativa'); ?>
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
<div class="modal fade" id="modalUploadContas" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo _l('gf_upload_title'); ?></h4>
            </div>
            <form action="<?php echo admin_url('gestaofinanceira/upload_contas_bancarias'); ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label><?php echo _l('gf_upload_instrucoes'); ?></label>
                        <p class="text-muted">
                            <?php echo _l('gf_upload_formatos_aceitos'); ?><br>
                            <?php echo _l('gf_upload_tamanho_maximo'); ?>
                        </p>
                    </div>
                    <div class="form-group">
                        <label for="arquivo_contas"><?php echo _l('gf_upload_selecionar_arquivo'); ?></label>
                        <input type="file" name="arquivo_contas" id="arquivo_contas" 
                               class="form-control" accept=".xls,.xlsx,.csv" required>
                    </div>
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> <?php echo _l('gf_upload_template_info'); ?>
                        <br><br>
                        <strong>Colunas do template:</strong>
                        <ul class="list-unstyled" style="margin-top: 10px;">
                            <li>• Banco</li>
                            <li>• Agência</li>
                            <li>• Conta</li>
                            <li>• Dígito</li>
                            <li>• Tipo (Corrente, Poupança, Investimento, Cartão)</li>
                            <li>• Saldo Inicial</li>
                            <li>• Limite de Crédito</li>
                            <li>• Observações</li>
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
    $('#tabelaContasBancarias').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
        },
        "order": [[0, "asc"]]
    });
});

function editarConta(id) {
    $.get('<?php echo admin_url("gestaofinanceira/contas_bancarias/"); ?>' + id, function(data) {
        $('#conta_id').val(data.id);
        $('#banco').val(data.banco).trigger('change');
        $('#agencia').val(data.agencia);
        $('#conta').val(data.conta);
        $('#digito').val(data.digito);
        $('#tipo_conta').val(data.tipo_conta);
        $('#saldo_inicial').val(data.saldo_inicial);
        $('#limite_credito').val(data.limite_credito);
        $('#observacoes').val(data.observacoes);
        $('#ativa').prop('checked', data.ativa == 1);
        $('#modalNovaConta').modal('show');
    }, 'json');
}

function conciliarConta(id) {
    // Implementar modal de conciliação bancária
    alert('Funcionalidade de conciliação em desenvolvimento');
}

function excluirConta(id) {
    if (confirm('<?php echo _l("gf_msg_confirmar_exclusao"); ?>')) {
        $.post('<?php echo admin_url("gestaofinanceira/delete_conta_bancaria/"); ?>' + id, function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert('Erro ao excluir a conta bancária. Verifique se não há lançamentos vinculados.');
            }
        }, 'json');
    }
}
</script>

<?php init_tail(); ?>

