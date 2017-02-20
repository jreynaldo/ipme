<script type="text/javascript">
    function editar(id) {
        post('<?=site_url();?>/canasta/comercializadoras', {id: id});
    }
    function info(id) {
        BootstrapDialog.show({
            title: 'Cotizaci&oacute;n',
            message: $('<div>Cargando...</div>').load('<?=site_url();?>/canasta/cotizacion', {id: id}),
            buttons: [{
                id: 'btnCancelClas',
                label: 'Cancelar',
                action: function(dialog) {
                    dialog.close();
                }
            }]
        });
    }
    function init() {}
</script>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?= $title ?></h1>
        </div>
        <div class="row">
            <div class="panel-body">
                <table class="table table-advance table-bordered tbl">
                    <thead>
                        <tr>
                            <th>Departamento</th>
                            <th>Producto</th>
                            <th>Especificaci&oacute;n</th>
                            <th>Cambio</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pendientes AS $pendiente) : ?>
                        <tr>
                            <td><?= $pendiente['departamento'] ?></td>
                            <td><?= $pendiente['codigo'] ?></td>
                            <td><?= $pendiente['descripcion'] ?></td>
                            <td style="color: green;"><?= $pendiente['cambio'] ?></td>
                            <td>
                                <?php if ($pendiente['cambio'] == 'Especificacion') : ?>
                                    <button title="Editar" class="btn btn-circle btn-primary" onclick="editar(<?=$pendiente['id']?>)"><i class="fa fa-edit"></i></button>
                                <?php endif; ?>
                                <button title="Detalle Cotizaci&oacute;n" class="btn btn-circle btn-info" onclick="info(<?=$pendiente['id']?>)"><i class="fa fa-list"></i></button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>