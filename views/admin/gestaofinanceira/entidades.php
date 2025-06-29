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
                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalNovaEntidade">
                                    <i class="fa fa-plus"></i> <?php echo _l('gf_btn_novo'); ?>
                                </button>
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalUploadEntidades">
                                    <i class="fa fa-upload"></i> <?php echo _l('gf_btn_upload'); ?>
                                </button>
                                <a href="<?php echo admin_url('gestaofinanceira/download_template/entidades'); ?>" class="btn btn-default">
                                    <i class="fa fa-download"></i> <?php echo _l('gf_btn_download_template'); ?>
                                </a>
                            </div>
                        </div>
                        <hr class="hr-panel-heading" />

                        <!-- Tabela de Entidades -->
                        <div class="table-responsive">
                            <table class="table table-striped dt-table" id="tabelaEntidades">
                                <thead>
                                    <tr>
                                        <th><?php echo _l('gf_entidades_nome_razao'); ?></th>
                                        <th><?php echo _l('gf_entidades_cpf_cnpj'); ?></th>
                                        <th><?php echo _l('gf_entidades_tipo'); ?></th>
                                        <th><?php echo _l('gf_entidades_contato'); ?></th>
                                        <th><?php echo _l('gf_entidades_telefone'); ?></th>
                                        <th><?php echo _l('gf_entidades_email'); ?></th>
                                        <th><?php echo _l('gf_entidades_ativo'); ?></th>
                                        <th width="100"><?php echo _l('options'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($entidades as $entidade): ?>
                                    <tr>
                                        <td><?php echo $entidade['nome_razao_social']; ?></td>
                                        <td><?php echo $entidade['cpf_cnpj']; ?></td>
                                        <td>
                                            <span class="label label-<?php 
                                                echo $entidade['tipo_entidade'] == 'Cliente' ? 'success' : 
                                                    ($entidade['tipo_entidade'] == 'Fornecedor' ? 'warning' : 'info'); 
                                            ?>">
                                                <?php echo _l('gf_tipo_' . strtolower($entidade['tipo_entidade'])); ?>
                                            </span>
                                        </td>
                                        <td><?php echo $entidade['contato_principal']; ?></td>
                                        <td><?php echo $entidade['telefone']; ?></td>
                                        <td><?php echo $entidade['email']; ?></td>
                                        <td>
                                            <span class="label label-<?php echo $entidade['ativo'] ? 'success' : 'danger'; ?>">
                                                <?php echo $entidade['ativo'] ? 'Ativo' : 'Inativo'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                                    <i class="fa fa-cog"></i> <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    <li><a href="#" onclick="editarEntidade(<?php echo $entidade['id']; ?>)">
                                                        <i class="fa fa-edit"></i> <?php echo _l('gf_btn_editar'); ?>
                                                    </a></li>
                                                    <li><a href="#" onclick="excluirEntidade(<?php echo $entidade['id']; ?>)">
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

<!-- Modal Nova Entidade -->
<div class="modal fade" id="modalNovaEntidade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo _l('gf_entidades_novo'); ?></h4>
            </div>
            <form action="<?php echo admin_url('gestaofinanceira/entidades'); ?>" method="POST" id="formEntidade">
                <div class="modal-body">
                    <input type="hidden" name="id" id="entidade_id">
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="nome_razao_social"><?php echo _l('gf_entidades_nome_razao'); ?> *</label>
                                <input type="text" name="nome_razao_social" id="nome_razao_social" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tipo_entidade"><?php echo _l('gf_entidades_tipo'); ?> *</label>
                                <select name="tipo_entidade" id="tipo_entidade" class="form-control" required>
                                    <option value="Cliente"><?php echo _l('gf_tipo_cliente'); ?></option>
                                    <option value="Fornecedor"><?php echo _l('gf_tipo_fornecedor'); ?></option>
                                    <option value="Credor"><?php echo _l('gf_tipo_credor'); ?></option>
                                    <option value="Funcionário"><?php echo _l('gf_tipo_funcionario'); ?></option>
                                    <option value="Outro"><?php echo _l('gf_tipo_outro'); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cpf_cnpj"><?php echo _l('gf_entidades_cpf_cnpj'); ?></label>
                                <input type="text" name="cpf_cnpj" id="cpf_cnpj" class="form-control" 
                                       placeholder="000.000.000-00 ou 00.000.000/0000-00">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="contato_principal"><?php echo _l('gf_entidades_contato'); ?></label>
                                <input type="text" name="contato_principal" id="contato_principal" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="telefone"><?php echo _l('gf_entidades_telefone'); ?></label>
                                <input type="text" name="telefone" id="telefone" class="form-control" 
                                       placeholder="(00) 00000-0000">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email"><?php echo _l('gf_entidades_email'); ?></label>
                                <input type="email" name="email" id="email" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="endereco"><?php echo _l('gf_entidades_endereco'); ?></label>
                                <textarea name="endereco" id="endereco" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="ativo" id="ativo" value="1" checked>
                                    <?php echo _l('gf_entidades_ativo'); ?>
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
<div class="modal fade" id="modalUploadEntidades" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo _l('gf_upload_title'); ?></h4>
            </div>
            <form action="<?php echo admin_url('gestaofinanceira/upload_entidades'); ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label><?php echo _l('gf_upload_instrucoes'); ?></label>
                        <p class="text-muted">
                            <?php echo _l('gf_upload_formatos_aceitos'); ?><br>
                            <?php echo _l('gf_upload_tamanho_maximo'); ?>
                        </p>
                    </div>
                    <div class="form-group">
                        <label for="arquivo_entidades"><?php echo _l('gf_upload_selecionar_arquivo'); ?></label>
                        <input type="file" name="arquivo_entidades" id="arquivo_entidades" 
                               class="form-control" accept=".xls,.xlsx,.csv" required>
                    </div>
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> <?php echo _l('gf_upload_template_info'); ?>
                        <br><br>
                        <strong>Colunas do template:</strong>
                        <ul class="list-unstyled" style="margin-top: 10px;">
                            <li>• Nome/Razão Social</li>
                            <li>• CPF/CNPJ</li>
                            <li>• Tipo (Cliente, Fornecedor, Credor, Funcionário, Outro)</li>
                            <li>• Contato Principal</li>
                            <li>• Telefone</li>
                            <li>• Email</li>
                            <li>• Endereço</li>
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
    $('#tabelaEntidades').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
        },
        "order": [[0, "asc"]]
    });

    // Máscaras para campos
    $('#cpf_cnpj').mask('000.000.000-00', {
        translation: {
            '0': {pattern: /[0-9]/}
        },
        onKeyPress: function(val, e, field, options) {
            var masks = ['000.000.000-00', '00.000.000/0000-00'];
            var mask = (val.length > 14) ? masks[1] : masks[0];
            field.mask(mask, options);
        }
    });

    $('#telefone').mask('(00) 00000-0000');
});

function editarEntidade(id) {
    $.get('<?php echo admin_url("gestaofinanceira/entidades/"); ?>' + id, function(data) {
        // Implementar preenchimento do formulário
        $('#modalNovaEntidade').modal('show');
    });
}

function excluirEntidade(id) {
    if (confirm('<?php echo _l("gf_msg_confirmar_exclusao"); ?>')) {
        $.post('<?php echo admin_url("gestaofinanceira/delete_entidade/"); ?>' + id, function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert('Erro ao excluir a entidade. Verifique se não há lançamentos vinculados.');
            }
        }, 'json');
    }
}
</script>

<?php init_tail(); ?>

