<?php defined('BASEPATH') or exit('No direct script access allowed');
foreach ($contas as $conta) { ?>
<li data-id="<?php echo $conta['id']; ?>">
    <div class="row">
        <div class="col-md-5">
            <i class="fa fa-book"></i> 
            <a href="<?php echo admin_url('gestaofinanceira/planocontas/conta/' . $conta['id']); ?>">
                <?php echo $conta['codigo_conta']; ?> - <?php echo $conta['nome_conta']; ?>
            </a>
        </div>
        <div class="col-md-2">
            <?php echo $conta['tipo_conta']; ?>
        </div>
        <div class="col-md-2">
            <?php if ($conta['aceita_lancamento'] == 1) {
                echo '<span class="label label-success">Sim</span>';
            } else {
                echo '<span class="label label-default">Não</span>';
            } ?>
        </div>
        <div class="col-md-3 text-right">
            <?php echo icon_btn('gestaofinanceira/planocontas/conta/' . $conta['id'], 'pencil-square-o'); ?>
            <?php echo icon_btn('gestaofinanceira/planocontas/delete/' . $conta['id'], 'remove', 'btn-danger _delete'); ?>
        </div>
    </div>
    <?php if (isset($conta['children'])) { ?>
        <ul>
            <?php // Chama a si mesmo para renderizar os filhos
            $this->load->view('admin/planocontas/_plano_contas_row', ['contas' => $conta['children']]); 
            ?>
        </ul>
    <?php } ?>
</li>
<?php } ?>
