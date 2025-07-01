<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <a href="<?php echo admin_url('gestaofinanceira/entidades/entidade'); ?>" class="btn btn-info pull-left display-block">
                                <i class="fa fa-plus-circle"></i> Nova Entidade
                            </a>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />

                        <table class="table dt-table" id="tabela-entidades">
                            <thead>
                                <th>ID</th>
                                <th>Nome / Razão Social</th>
                                <th>CPF / CNPJ</th>
                                <th>Tipo</th>
                                <th>Telefone</th>
                                <th>Ações</th>
                            </thead>
                            <tbody>
                                <?php foreach($entidades as $entidade) { ?>
                                    <tr>
                                        <td><?php echo $entidade['id']; ?></td>
                                        <td>
                                            <a href="<?php echo admin_url('gestaofinanceira/entidades/entidade/' . $entidade['id']); ?>">
                                                <?php echo $entidade['nome_razao_social']; ?>
                                            </a>
                                        </td>
                                        <td><?php echo $entidade['cpf_cnpj']; ?></td>
                                        <td><?php echo $entidade['tipo_entidade']; ?></td>
                                        <td><?php echo $entidade['telefone']; ?></td>
                                        <td>
                                            <?php echo icon_btn('gestaofinanceira/entidades/entidade/' . $entidade['id'], 'pencil-square-o'); ?>
                                            <?php echo icon_btn('gestaofinanceira/entidades/delete/' . $entidade['id'], 'remove', 'btn-danger _delete'); ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(function() {
        $('#tabela-entidades').DataTable({
            "language": app.options.datatables_lang,
            "order": [[1, "asc"]]
        });
    });
</script>
</body>
</html>
