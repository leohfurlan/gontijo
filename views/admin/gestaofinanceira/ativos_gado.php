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
                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalNovoAtivo">
                                    <i class="fa fa-plus"></i> <?php echo _l('gf_btn_novo'); ?>
                                </button>
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalUploadAtivos">
                                    <i class="fa fa-upload"></i> <?php echo _l('gf_btn_upload'); ?>
                                </button>
                                <a href="<?php echo admin_url('gestaofinanceira/download_template/ativos_gado'); ?>" class="btn btn-default">
                                    <i class="fa fa-download"></i> <?php echo _l('gf_btn_download_template'); ?>
                                </a>
                            </div>
                        </div>
                        <hr class="hr-panel-heading" />

                        <!-- Resumo por Categoria -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <i class="fa fa-pie-chart"></i> Resumo do Rebanho
                                        </h4>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <?php 
                                            $total_animais = 0;
                                            $valor_total = 0;
                                            $categorias_resumo = [];
                                            
                                            foreach ($ativos_gado as $ativo) {
                                                $categoria = $ativo['categoria'];
                                                if (!isset($categorias_resumo[$categoria])) {
                                                    $categorias_resumo[$categoria] = [
                                                        'quantidade' => 0,
                                                        'valor_total' => 0
                                                    ];
                                                }
                                                $categorias_resumo[$categoria]['quantidade'] += $ativo['quantidade'];
                                                $categorias_resumo[$categoria]['valor_total'] += $ativo['valor_total'];
                                                $total_animais += $ativo['quantidade'];
                                                $valor_total += $ativo['valor_total'];
                                            }
                                            
                                            $cores_categorias = [
                                                'Garrotes' => 'primary',
                                                'Novilhas' => 'success', 
                                                'Bezerros' => 'info',
                                                'Vacas' => 'warning',
                                                'Touros' => 'danger'
                                            ];
                                            ?>
                                            
                                            <?php foreach ($categorias_resumo as $categoria => $dados): ?>
                                            <div class="col-md-2">
                                                <div class="panel panel-<?php echo $cores_categorias[$categoria] ?? 'default'; ?>">
                                                    <div class="panel-body text-center">
                                                        <h3><?php echo $dados['quantidade']; ?></h3>
                                                        <p class="text-muted"><?php echo $categoria; ?></p>
                                                        <small>R$ <?php echo number_format($dados['valor_total'], 2, ',', '.'); ?></small>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endforeach; ?>
                                            
                                            <div class="col-md-2">
                                                <div class="panel panel-default">
                                                    <div class="panel-body text-center">
                                                        <h3 class="text-primary"><?php echo $total_animais; ?></h3>
                                                        <p class="text-muted">Total Animais</p>
                                                        <small class="text-success">R$ <?php echo number_format($valor_total, 2, ',', '.'); ?></small>
                                                    </div>
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
                                        <form method="GET" action="<?php echo admin_url('gestaofinanceira/ativos_gado'); ?>">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label><?php echo _l('gf_filtro_categoria'); ?></label>
                                                        <select name="categoria" class="form-control">
                                                            <option value="">Todas</option>
                                                            <option value="Garrotes" <?php echo $this->input->get('categoria') == 'Garrotes' ? 'selected' : ''; ?>>Garrotes</option>
                                                            <option value="Novilhas" <?php echo $this->input->get('categoria') == 'Novilhas' ? 'selected' : ''; ?>>Novilhas</option>
                                                            <option value="Bezerros" <?php echo $this->input->get('categoria') == 'Bezerros' ? 'selected' : ''; ?>>Bezerros</option>
                                                            <option value="Vacas" <?php echo $this->input->get('categoria') == 'Vacas' ? 'selected' : ''; ?>>Vacas</option>
                                                            <option value="Touros" <?php echo $this->input->get('categoria') == 'Touros' ? 'selected' : ''; ?>>Touros</option>
                                                        </select>
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
                                                        <label><?php echo _l('gf_filtro_data_inicio'); ?></label>
                                                        <input type="date" name="data_inicio" class="form-control" 
                                                               value="<?php echo $this->input->get('data_inicio'); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>&nbsp;</label><br>
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="fa fa-search"></i> Filtrar
                                                        </button>
                                                        <button type="button" class="btn btn-success" onclick="exportarAtivos()">
                                                            <i class="fa fa-file-excel-o"></i> <?php echo _l('gf_btn_exportar'); ?>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabela de Ativos -->
                        <div class="table-responsive">
                            <table class="table table-striped dt-table" id="tabelaAtivosGado">
                                <thead>
                                    <tr>
                                        <th><?php echo _l('gf_ativos_gado_descricao'); ?></th>
                                        <th><?php echo _l('gf_ativos_gado_categoria'); ?></th>
                                        <th><?php echo _l('gf_ativos_gado_quantidade'); ?></th>
                                        <th><?php echo _l('gf_ativos_gado_peso_medio'); ?></th>
                                        <th><?php echo _l('gf_ativos_gado_valor_unitario'); ?></th>
                                        <th><?php echo _l('gf_ativos_gado_valor_total'); ?></th>
                                        <th><?php echo _l('gf_centros_custo_nome'); ?></th>
                                        <th><?php echo _l('gf_ativos_gado_data_aquisicao'); ?></th>
                                        <th width="100"><?php echo _l('options'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ativos_gado as $ativo): ?>
                                    <tr>
                                        <td><strong><?php echo $ativo['descricao']; ?></strong></td>
                                        <td>
                                            <span class="label label-<?php echo $cores_categorias[$ativo['categoria']] ?? 'default'; ?>">
                                                <?php echo $ativo['categoria']; ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-primary"><?php echo $ativo['quantidade']; ?></span>
                                        </td>
                                        <td class="text-right">
                                            <?php echo $ativo['peso_medio'] ? number_format($ativo['peso_medio'], 0) . ' kg' : '-'; ?>
                                        </td>
                                        <td class="text-right">
                                            R$ <?php echo number_format($ativo['valor_unitario'], 2, ',', '.'); ?>
                                        </td>
                                        <td class="text-right text-success">
                                            <strong>R$ <?php echo number_format($ativo['valor_total'], 2, ',', '.'); ?></strong>
                                        </td>
                                        <td><?php echo $ativo['centro_custo_nome']; ?></td>
                                        <td><?php echo _d($ativo['data_aquisicao']); ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                                    <i class="fa fa-cog"></i> <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    <li><a href="#" onclick="editarAtivo(<?php echo $ativo['id']; ?>)">
                                                        <i class="fa fa-edit"></i> <?php echo _l('gf_btn_editar'); ?>
                                                    </a></li>
                                                    <li><a href="#" onclick="verHistorico(<?php echo $ativo['id']; ?>)">
                                                        <i class="fa fa-history"></i> Ver Histórico
                                                    </a></li>
                                                    <li><a href="#" onclick="excluirAtivo(<?php echo $ativo['id']; ?>)">
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

<!-- Modal Novo Ativo -->
<div class="modal fade" id="modalNovoAtivo" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo _l('gf_ativos_gado_novo'); ?></h4>
            </div>
            <form action="<?php echo admin_url('gestaofinanceira/ativos_gado'); ?>" method="POST" id="formAtivo">
                <div class="modal-body">
                    <input type="hidden" name="id" id="ativo_id">
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="descricao"><?php echo _l('gf_ativos_gado_descricao'); ?> *</label>
                                <input type="text" name="descricao" id="descricao" class="form-control" 
                                       placeholder="Ex: Lote de Garrotes Nelore" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="categoria"><?php echo _l('gf_ativos_gado_categoria'); ?> *</label>
                                <select name="categoria" id="categoria" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <option value="Garrotes">Garrotes</option>
                                    <option value="Novilhas">Novilhas</option>
                                    <option value="Bezerros">Bezerros</option>
                                    <option value="Vacas">Vacas</option>
                                    <option value="Touros">Touros</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="quantidade"><?php echo _l('gf_ativos_gado_quantidade'); ?> *</label>
                                <input type="number" name="quantidade" id="quantidade" class="form-control" 
                                       min="1" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="peso_medio"><?php echo _l('gf_ativos_gado_peso_medio'); ?> (kg)</label>
                                <input type="number" name="peso_medio" id="peso_medio" class="form-control" 
                                       step="0.1" min="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="valor_unitario"><?php echo _l('gf_ativos_gado_valor_unitario'); ?> *</label>
                                <input type="number" name="valor_unitario" id="valor_unitario" class="form-control" 
                                       step="0.01" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="valor_total"><?php echo _l('gf_ativos_gado_valor_total'); ?></label>
                                <input type="number" name="valor_total" id="valor_total" class="form-control" 
                                       step="0.01" readonly>
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
                                <label for="data_aquisicao"><?php echo _l('gf_ativos_gado_data_aquisicao'); ?> *</label>
                                <input type="date" name="data_aquisicao" id="data_aquisicao" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="origem"><?php echo _l('gf_ativos_gado_origem'); ?></label>
                                <input type="text" name="origem" id="origem" class="form-control" 
                                       placeholder="Ex: Fazenda São João">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="raca">Raça</label>
                                <select name="raca" id="raca" class="form-control">
                                    <option value="">Selecione...</option>
                                    <option value="Nelore">Nelore</option>
                                    <option value="Angus">Angus</option>
                                    <option value="Brahman">Brahman</option>
                                    <option value="Gir">Gir</option>
                                    <option value="Guzerá">Guzerá</option>
                                    <option value="Tabapuã">Tabapuã</option>
                                    <option value="Mestiço">Mestiço</option>
                                    <option value="Outro">Outro</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="observacoes"><?php echo _l('gf_ativos_gado_observacoes'); ?></label>
                                <textarea name="observacoes" id="observacoes" class="form-control" rows="3"></textarea>
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
<div class="modal fade" id="modalUploadAtivos" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo _l('gf_upload_title'); ?></h4>
            </div>
            <form action="<?php echo admin_url('gestaofinanceira/upload_ativos_gado'); ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label><?php echo _l('gf_upload_instrucoes'); ?></label>
                        <p class="text-muted">
                            <?php echo _l('gf_upload_formatos_aceitos'); ?><br>
                            <?php echo _l('gf_upload_tamanho_maximo'); ?>
                        </p>
                    </div>
                    <div class="form-group">
                        <label for="arquivo_ativos"><?php echo _l('gf_upload_selecionar_arquivo'); ?></label>
                        <input type="file" name="arquivo_ativos" id="arquivo_ativos" 
                               class="form-control" accept=".xls,.xlsx,.csv" required>
                    </div>
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> <?php echo _l('gf_upload_template_info'); ?>
                        <br><br>
                        <strong>Colunas do template:</strong>
                        <ul class="list-unstyled" style="margin-top: 10px;">
                            <li>• Descrição</li>
                            <li>• Categoria (Garrotes, Novilhas, Bezerros, Vacas, Touros)</li>
                            <li>• Quantidade</li>
                            <li>• Peso Médio (kg)</li>
                            <li>• Valor Unitário</li>
                            <li>• Centro de Custo</li>
                            <li>• Data de Aquisição</li>
                            <li>• Origem</li>
                            <li>• Raça</li>
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
    $('#tabelaAtivosGado').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
        },
        "order": [[7, "desc"]]
    });

    // Calcular valor total automaticamente
    $('#quantidade, #valor_unitario').on('input', function() {
        var quantidade = parseFloat($('#quantidade').val()) || 0;
        var valorUnitario = parseFloat($('#valor_unitario').val()) || 0;
        var valorTotal = quantidade * valorUnitario;
        $('#valor_total').val(valorTotal.toFixed(2));
    });

    // Definir data atual como padrão
    $('#data_aquisicao').val(new Date().toISOString().split('T')[0]);
});

function editarAtivo(id) {
    $.get('<?php echo admin_url("gestaofinanceira/ativos_gado/"); ?>' + id, function(data) {
        // Implementar preenchimento do formulário
        $('#modalNovoAtivo').modal('show');
    });
}

function verHistorico(id) {
    // Implementar modal de histórico do ativo
    alert('Histórico do ativo em desenvolvimento');
}

function excluirAtivo(id) {
    if (confirm('<?php echo _l("gf_msg_confirmar_exclusao"); ?>')) {
        $.post('<?php echo admin_url("gestaofinanceira/delete_ativo/"); ?>' + id, function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert('Erro ao excluir o ativo.');
            }
        }, 'json');
    }
}

function exportarAtivos() {
    var params = new URLSearchParams(window.location.search);
    params.append('export', 'excel');
    window.open('<?php echo admin_url("gestaofinanceira/ativos_gado"); ?>?' + params.toString());
}
</script>

<?php init_tail(); ?>

