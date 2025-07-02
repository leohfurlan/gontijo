<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <a href="<?php echo admin_url('gestaofinanceira/centroscusto/centro'); ?>" class="btn btn-info pull-left display-block">
                                <i class="fa fa-plus-circle"></i> Novo Centro de Custo
                            </a>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />

                        <table class="table dt-table" id="tabela-centros-custo">
                            <thead>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Tipo</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </thead>
                            <tbody>
                                <?php foreach($centros_custo as $centro) { ?>
                                    <tr>
                                        <td><?php echo $centro['id']; ?></td>
                                        <td>
                                            <a href="<?php echo admin_url('gestaofinanceira/centroscusto/centro/' . $centro['id']); ?>">
                                                <?php echo $centro['nome']; ?>
                                            </a>
                                        </td>
                                        <td><?php echo $centro['tipo']; ?></td>
                                        <td><?php echo get_status_badge($centro['ativo'] ? 'Ativo' : 'Inativo'); ?></td>
                                        <td>
                                            <?php echo icon_btn('gestaofinanceira/centroscusto/centro/' . $centro['id'], 'pencil-square-o'); ?>
                                            <?php echo icon_btn('gestaofinanceira/centroscusto/delete/' . $centro['id'], 'remove', 'btn-danger _delete'); ?>
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
        $('#tabela-centros-custo').DataTable({
            "language": app.options.datatables_lang,
            "order": [[1, "asc"]]
        });
    });
</script>
</body>
</html>
