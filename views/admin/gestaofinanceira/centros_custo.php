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
                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalNovoCentro">
                                    <i class="fa fa-plus"></i> <?php echo _l('gf_btn_novo'); ?>
                                </button>
                            </div>
                        </div>
                        <hr class="hr-panel-heading" />

                        <!-- Cards dos Centros de Custo -->
                        <div class="row">
                            <?php foreach ($centros_custo as $centro): ?>
                            <div class="col-md-4">
                                <div class="panel panel-<?php echo $centro['ativo'] ? 'primary' : 'default'; ?>">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <i class="fa fa-building"></i> <?php echo $centro['nome']; ?>
                                            <?php if (!$centro['ativo']): ?>
                                                <span class="label label-danger pull-right">Inativo</span>
                                            <?php endif; ?>
                                        </h4>
                                    </div>
                                    <div class="panel-body">
                                        <?php if ($centro['descricao']): ?>
                                            <p class="text-muted"><?php echo $centro['descricao']; ?></p>
                                        <?php endif; ?>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <small class="text-muted">Receitas (Mês)</small>
                                                <h5 class="text-success">R$ <?php echo number_format($centro['receitas_mes'] ?? 0, 2, ',', '.'); ?></h5>
                                            </div>
                                            <div class="col-md-6">
                                                <small class="text-muted">Despesas (Mês)</small>
                                                <h5 class="text-danger">R$ <?php echo number_format($centro['despesas_mes'] ?? 0, 2, ',', '.'); ?></h5>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <small class="text-muted">Resultado (Mês)</small>
                                                <?php 
                                                $resultado = ($centro['receitas_mes'] ?? 0) - ($centro['despesas_mes'] ?? 0);
                                                ?>
                                                <h5 class="<?php echo $resultado >= 0 ? 'text-success' : 'text-danger'; ?>">
                                                    R$ <?php echo number_format($resultado, 2, ',', '.'); ?>
                                                </h5>
                                            </div>
                                        </div>
                                        
                                        <hr>
                                        
                                        <div class="btn-group btn-group-justified">
                                            <a href="<?php echo admin_url('gestaofinanceira/lancamentos?centro_custo=' . $centro['id']); ?>" 
                                               class="btn btn-default btn-sm">
                                                <i class="fa fa-list"></i> Lançamentos
                                            </a>
                                            <a href="#" onclick="editarCentro(<?php echo $centro['id']; ?>)" 
                                               class="btn btn-default btn-sm">
                                                <i class="fa fa-edit"></i> Editar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Tabela Detalhada -->
                        <div class="table-responsive">
                            <table class="table table-striped dt-table" id="tabelaCentrosCusto">
                                <thead>
                                    <tr>
                                        <th><?php echo _l('gf_centros_custo_nome'); ?></th>
                                        <th><?php echo _l('gf_centros_custo_descricao'); ?></th>
                                        <th class="text-right">Receitas (Mês)</th>
                                        <th class="text-right">Despesas (Mês)</th>
                                        <th class="text-right">Resultado (Mês)</th>
                                        <th><?php echo _l('gf_centros_custo_ativo'); ?></th>
                                        <th width="100"><?php echo _l('options'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($centros_custo as $centro): ?>
                                    <?php 
                                    $resultado = ($centro['receitas_mes'] ?? 0) - ($centro['despesas_mes'] ?? 0);
                                    ?>
                                    <tr>
                                        <td><strong><?php echo $centro['nome']; ?></strong></td>
                                        <td><?php echo $centro['descricao']; ?></td>
                                        <td class="text-right text-success">
                                            R$ <?php echo number_format($centro['receitas_mes'] ?? 0, 2, ',', '.'); ?>
                                        </td>
                                        <td class="text-right text-danger">
                                            R$ <?php echo number_format($centro['despesas_mes'] ?? 0, 2, ',', '.'); ?>
                                        </td>
                                        <td class="text-right <?php echo $resultado >= 0 ? 'text-success' : 'text-danger'; ?>">
                                            R$ <?php echo number_format($resultado, 2, ',', '.'); ?>
                                        </td>
                                        <td>
                                            <span class="label label-<?php echo $centro['ativo'] ? 'success' : 'danger'; ?>">
                                                <?php echo $centro['ativo'] ? 'Ativo' : 'Inativo'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                                    <i class="fa fa-cog"></i> <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    <li><a href="#" onclick="editarCentro(<?php echo $centro['id']; ?>)">
                                                        <i class="fa fa-edit"></i> <?php echo _l('gf_btn_editar'); ?>
                                                    </a></li>
                                                    <li><a href="<?php echo admin_url('gestaofinanceira/lancamentos?centro_custo=' . $centro['id']); ?>">
                                                        <i class="fa fa-list"></i> Ver Lançamentos
                                                    </a></li>
                                                    <li><a href="#" onclick="excluirCentro(<?php echo $centro['id']; ?>)">
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

<!-- Modal Novo Centro de Custo -->
<div class="modal fade" id="modalNovoCentro" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo _l('gf_centros_custo_novo'); ?></h4>
            </div>
            <form action="<?php echo admin_url('gestaofinanceira/centros_custo'); ?>" method="POST" id="formCentro">
                <div class="modal-body">
                    <input type="hidden" name="id" id="centro_id">
                    
                    <div class="form-group">
                        <label for="nome"><?php echo _l('gf_centros_custo_nome'); ?> *</label>
                        <input type="text" name="nome" id="nome" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="descricao"><?php echo _l('gf_centros_custo_descricao'); ?></label>
                        <textarea name="descricao" id="descricao" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="ativo" id="ativo" value="1" checked>
                            <?php echo _l('gf_centros_custo_ativo'); ?>
                        </label>
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

<script>
$(document).ready(function() {
    $('#tabelaCentrosCusto').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
        },
        "order": [[0, "asc"]]
    });
});

function editarCentro(id) {
    $.get('<?php echo admin_url("gestaofinanceira/centros_custo/"); ?>' + id, function(data) {
        $('#centro_id').val(data.id);
        $('#nome').val(data.nome);
        $('#descricao').val(data.descricao);
        $('#ativo').prop('checked', data.ativo == 1);
        $('#modalNovoCentro').modal('show');
    }, 'json');
}

function excluirCentro(id) {
    if (confirm('<?php echo _l("gf_msg_confirmar_exclusao"); ?>')) {
        $.post('<?php echo admin_url("gestaofinanceira/delete_centro/"); ?>' + id, function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert('Erro ao excluir o centro de custo. Verifique se não há lançamentos vinculados.');
            }
        }, 'json');
    }
}
</script>

<?php init_tail(); ?>

