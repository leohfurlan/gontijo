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
                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalNovoContrato">
                                    <i class="fa fa-plus"></i> Novo Contrato
                                </button>
                                <button type="button" class="btn btn-success" onclick="exportarEndividamento()">
                                    <i class="fa fa-file-excel-o"></i> <?php echo _l('gf_btn_exportar'); ?>
                                </button>
                            </div>
                        </div>
                        <hr class="hr-panel-heading" />

                        <!-- Resumo do Endividamento -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-warning">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <i class="fa fa-exclamation-triangle"></i> Resumo do Endividamento
                                        </h4>
                                    </div>
                                    <div class="panel-body">
                                        <?php
                                        $total_contratos = count($contratos);
                                        $valor_total_original = 0;
                                        $saldo_devedor = 0;
                                        $valor_pago = 0;
                                        $parcelas_vencidas = 0;
                                        $valor_vencido = 0;
                                        
                                        foreach ($contratos as $contrato) {
                                            $valor_total_original += $contrato['valor_total'];
                                            $saldo_devedor += $contrato['saldo_devedor'];
                                            $valor_pago += $contrato['valor_pago'];
                                            
                                            foreach ($contrato['parcelas'] as $parcela) {
                                                if ($parcela['status'] == 'Vencida') {
                                                    $parcelas_vencidas++;
                                                    $valor_vencido += $parcela['valor_parcela'];
                                                }
                                            }
                                        }
                                        ?>
                                        
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="text-center">
                                                    <h4 class="text-info">Contratos Ativos</h4>
                                                    <h3 class="text-info"><?php echo $total_contratos; ?></h3>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="text-center">
                                                    <h4 class="text-primary">Valor Original</h4>
                                                    <h3 class="text-primary">R$ <?php echo number_format($valor_total_original, 2, ',', '.'); ?></h3>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="text-center">
                                                    <h4 class="text-success">Valor Pago</h4>
                                                    <h3 class="text-success">R$ <?php echo number_format($valor_pago, 2, ',', '.'); ?></h3>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="text-center">
                                                    <h4 class="text-warning">Saldo Devedor</h4>
                                                    <h3 class="text-warning">R$ <?php echo number_format($saldo_devedor, 2, ',', '.'); ?></h3>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="text-center">
                                                    <h4 class="text-danger">Parcelas Vencidas</h4>
                                                    <h3 class="text-danger"><?php echo $parcelas_vencidas; ?></h3>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="text-center">
                                                    <h4 class="text-danger">Valor Vencido</h4>
                                                    <h3 class="text-danger">R$ <?php echo number_format($valor_vencido, 2, ',', '.'); ?></h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filtros -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <form method="GET" action="<?php echo admin_url('gestaofinanceira/endividamento'); ?>">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Status do Contrato</label>
                                                        <select name="status" class="form-control">
                                                            <option value="">Todos</option>
                                                            <option value="Ativo" <?php echo $this->input->get('status') == 'Ativo' ? 'selected' : ''; ?>>Ativo</option>
                                                            <option value="Quitado" <?php echo $this->input->get('status') == 'Quitado' ? 'selected' : ''; ?>>Quitado</option>
                                                            <option value="Cancelado" <?php echo $this->input->get('status') == 'Cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Credor</label>
                                                        <select name="credor" class="form-control">
                                                            <option value="">Todos</option>
                                                            <?php foreach ($credores as $credor): ?>
                                                            <option value="<?php echo $credor['id']; ?>" 
                                                                    <?php echo $this->input->get('credor') == $credor['id'] ? 'selected' : ''; ?>>
                                                                <?php echo $credor['nome_razao_social']; ?>
                                                            </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Centro de Custo</label>
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

                        <!-- Lista de Contratos -->
                        <div class="row">
                            <?php foreach ($contratos as $contrato): ?>
                            <div class="col-md-6">
                                <div class="panel panel-<?php echo $contrato['status'] == 'Ativo' ? 'warning' : ($contrato['status'] == 'Quitado' ? 'success' : 'danger'); ?>">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <i class="fa fa-file-text"></i> <?php echo $contrato['descricao']; ?>
                                            <span class="label label-<?php echo $contrato['status'] == 'Ativo' ? 'warning' : ($contrato['status'] == 'Quitado' ? 'success' : 'danger'); ?> pull-right">
                                                <?php echo $contrato['status']; ?>
                                            </span>
                                        </h4>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>Credor:</strong> <?php echo $contrato['credor_nome']; ?><br>
                                                <strong>Data Contrato:</strong> <?php echo _d($contrato['data_contrato']); ?><br>
                                                <strong>Valor Total:</strong> R$ <?php echo number_format($contrato['valor_total'], 2, ',', '.'); ?><br>
                                                <strong>Taxa Juros:</strong> <?php echo number_format($contrato['taxa_juros'], 2, ',', '.'); ?>% a.m.
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Parcelas:</strong> <?php echo $contrato['numero_parcelas']; ?>x<br>
                                                <strong>Valor Pago:</strong> <span class="text-success">R$ <?php echo number_format($contrato['valor_pago'], 2, ',', '.'); ?></span><br>
                                                <strong>Saldo Devedor:</strong> <span class="text-danger">R$ <?php echo number_format($contrato['saldo_devedor'], 2, ',', '.'); ?></span><br>
                                                <strong>Centro Custo:</strong> <?php echo $contrato['centro_custo_nome']; ?>
                                            </div>
                                        </div>
                                        
                                        <?php if ($contrato['observacoes']): ?>
                                            <hr>
                                            <small class="text-muted"><?php echo $contrato['observacoes']; ?></small>
                                        <?php endif; ?>
                                        
                                        <hr>
                                        
                                        <div class="btn-group btn-group-justified">
                                            <a href="#" onclick="verParcelas(<?php echo $contrato['id']; ?>)" 
                                               class="btn btn-default btn-sm">
                                                <i class="fa fa-list"></i> Parcelas
                                            </a>
                                            <a href="#" onclick="editarContrato(<?php echo $contrato['id']; ?>)" 
                                               class="btn btn-default btn-sm">
                                                <i class="fa fa-edit"></i> Editar
                                            </a>
                                            <a href="#" onclick="quitarContrato(<?php echo $contrato['id']; ?>)" 
                                               class="btn btn-success btn-sm">
                                                <i class="fa fa-check"></i> Quitar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Tabela de Parcelas Próximas ao Vencimento -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-danger">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <i class="fa fa-warning"></i> Parcelas Próximas ao Vencimento (Próximos 30 dias)
                                        </h4>
                                    </div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Contrato</th>
                                                        <th>Credor</th>
                                                        <th>Parcela</th>
                                                        <th>Valor</th>
                                                        <th>Vencimento</th>
                                                        <th>Status</th>
                                                        <th>Ações</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($parcelas_proximas as $parcela): ?>
                                                    <tr class="<?php echo $parcela['status'] == 'Vencida' ? 'danger' : 'warning'; ?>">
                                                        <td><?php echo $parcela['contrato_descricao']; ?></td>
                                                        <td><?php echo $parcela['credor_nome']; ?></td>
                                                        <td><?php echo $parcela['numero_parcela'] . '/' . $parcela['total_parcelas']; ?></td>
                                                        <td>R$ <?php echo number_format($parcela['valor_parcela'], 2, ',', '.'); ?></td>
                                                        <td>
                                                            <?php echo _d($parcela['data_vencimento']); ?>
                                                            <?php if ($parcela['status'] == 'Vencida'): ?>
                                                                <br><small class="text-danger">
                                                                    <?php echo calculate_days_between($parcela['data_vencimento'], date('Y-m-d')); ?> dias em atraso
                                                                </small>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <span class="label label-<?php echo $parcela['status'] == 'Vencida' ? 'danger' : 'warning'; ?>">
                                                                <?php echo $parcela['status']; ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-success btn-xs" 
                                                                    onclick="pagarParcela(<?php echo $parcela['id']; ?>)">
                                                                <i class="fa fa-check"></i> Pagar
                                                            </button>
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
            </div>
        </div>
    </div>
</div>

<!-- Modal Novo Contrato -->
<div class="modal fade" id="modalNovoContrato" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Novo Contrato de Endividamento</h4>
            </div>
            <form action="<?php echo admin_url('gestaofinanceira/endividamento'); ?>" method="POST" id="formContrato">
                <div class="modal-body">
                    <input type="hidden" name="id" id="contrato_id">
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="descricao">Descrição do Contrato *</label>
                                <input type="text" name="descricao" id="descricao" class="form-control" 
                                       placeholder="Ex: Financiamento para compra de gado" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_credor">Credor *</label>
                                <select name="id_credor" id="id_credor" class="form-control selectpicker" 
                                        data-live-search="true" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach ($credores as $credor): ?>
                                    <option value="<?php echo $credor['id']; ?>">
                                        <?php echo $credor['nome_razao_social']; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_centro_custo">Centro de Custo *</label>
                                <select name="id_centro_custo" id="id_centro_custo" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach ($centros_custo as $centro): ?>
                                    <option value="<?php echo $centro['id']; ?>"><?php echo $centro['nome']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="valor_total">Valor Total *</label>
                                <input type="number" name="valor_total" id="valor_total" class="form-control" 
                                       step="0.01" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="taxa_juros">Taxa de Juros (% a.m.)</label>
                                <input type="number" name="taxa_juros" id="taxa_juros" class="form-control" 
                                       step="0.01" min="0" value="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="numero_parcelas">Número de Parcelas *</label>
                                <input type="number" name="numero_parcelas" id="numero_parcelas" class="form-control" 
                                       min="1" max="360" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="data_contrato">Data do Contrato *</label>
                                <input type="date" name="data_contrato" id="data_contrato" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="data_primeiro_vencimento">Primeiro Vencimento *</label>
                                <input type="date" name="data_primeiro_vencimento" id="data_primeiro_vencimento" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="observacoes">Observações</label>
                                <textarea name="observacoes" id="observacoes" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Contrato</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Definir data atual como padrão
    $('#data_contrato').val(new Date().toISOString().split('T')[0]);
    
    // Calcular primeiro vencimento (30 dias após a data do contrato)
    $('#data_contrato').change(function() {
        var dataContrato = new Date($(this).val());
        dataContrato.setMonth(dataContrato.getMonth() + 1);
        $('#data_primeiro_vencimento').val(dataContrato.toISOString().split('T')[0]);
    });
});

function verParcelas(contratoId) {
    // Implementar modal com lista de parcelas
    window.open('<?php echo admin_url("gestaofinanceira/parcelas_contrato/"); ?>' + contratoId, '_blank');
}

function editarContrato(id) {
    $.get('<?php echo admin_url("gestaofinanceira/endividamento/"); ?>' + id, function(data) {
        // Implementar preenchimento do formulário
        $('#modalNovoContrato').modal('show');
    });
}

function quitarContrato(id) {
    if (confirm('Confirma a quitação antecipada deste contrato?')) {
        $.post('<?php echo admin_url("gestaofinanceira/quitar_contrato/"); ?>' + id, function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert('Erro ao quitar o contrato.');
            }
        }, 'json');
    }
}

function pagarParcela(parcelaId) {
    if (confirm('Confirma o pagamento desta parcela?')) {
        $.post('<?php echo admin_url("gestaofinanceira/pagar_parcela/"); ?>' + parcelaId, function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert('Erro ao registrar o pagamento.');
            }
        }, 'json');
    }
}

function exportarEndividamento() {
    var params = new URLSearchParams(window.location.search);
    params.append('export', 'excel');
    window.open('<?php echo admin_url("gestaofinanceira/endividamento"); ?>?' + params.toString());
}
</script>

<?php init_tail(); ?>

