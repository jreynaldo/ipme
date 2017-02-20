<script type="text/javascript">
    function detalle(id) {
        BootstrapDialog.show({
            title: 'Detalle Solicitud',
            message: $('<div></div>').load('detalle?id=' + id),
            buttons: [{
                label: 'Aceptar',
                action: function(dialog) {
                    dialog.close();
                }
            }]
        });
    }
    function aprobar(id) {
        $.post('aprobar', {
            id: id
        }, function (result) {
            if (result === 'Ok') {
                window.location = 'aprobar_agricolas';
            } else {
                alert(result);
            }
        });
    }
    function rechazar(id) {
        BootstrapDialog.show({
            title: 'Producto Agricola',
            message: $('<div><label style="float: left">Comentarios:</label><div class="controls"><input id="comentario"/></div></div>'),
            buttons: [{
                label: 'Aceptar',
                cssClass: 'btn-primary',
                action: function(dialog) {
                    var $button = this;
                    $button.disable();
                    $.post('rechazar', {
                        id: id,
                        comentario: $('#comentario').val()
                    }, function (result) {
                        if (result === 'Ok') {
                            window.location = 'aprobar_agricolas';
                        } else {
                            $button.enable();
                            alert(result);
                        }
                    });
                }
            }, {
                label: 'Cancelar',
                action: function(dialog) {
                    dialog.close();
                }
            }]
        });
    }
    function init() {
        $("#mun").select2({width: 'resolve'});
    };
</script>
<div id="page-wrapper">
    <div class="row">
        <div class="row" style="padding-left: 20px; padding-right: 20px; height: 75vh;">
            <div class="row">
                <div>&nbsp;</div>
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="clearfix">Productos</h4>
                        </div>
                        <div class="panel-body">
                            <form action="aprobar_agricolas" method="post">
                                <div class="form-group">
                                    <label>Solicitudes:</label>
                                    <?php
                                        $estados = Array("SOLICITADO" => "Pendiente", "APROBADO" => "Aprobadas", "RECHAZADO" => "Rechazadas");
                                        if (!($estado = $this->input->post('estado'))) {
                                            $estado = "SOLICITADO";
                                        }
                                        echo form_dropdown('estado', $estados, $estado, 'onchange="this.form.submit()" class="form-control"');
                                    ?>
                                </div>
                            </form>
                            <table class="table table-advance table-bordered tbl">
                                <thead>
                                    <tr>
                                        <th>Depto</th>
                                        <th>Mercado</th>
                                        <th>Codigo</th>
                                        <th>Producto</th>
                                        <th>Especificaci&oacute;n</th>
                                        <th>Unidad</th>
                                        <th>Equivalencia</th>
                                        <th>Acci&oacute;n</th>
                                        <th>Justificaci&oacute;n</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php for ($i = 0; $i < count($solicitudesprod); $i++): ?>
                                    <tr>
                                        <td><?=$solicitudesprod[$i]['departamento']?></td>
                                        <td><?=$solicitudesprod[$i]['descripcion']?></td>
                                        <td><?=$solicitudesprod[$i]['codigo']?></td>
                                        <td><?=$solicitudesprod[$i]['producto']?></td>
                                        <td><?=$solicitudesprod[$i]['especificacion']?></td>
                                        <td><?=$solicitudesprod[$i]['unidad']?></td>
                                        <td><?=$solicitudesprod[$i]['equivalencia']?></td>
                                        <td style="color: green;"><?=$solicitudesprod[$i]['accion']?></td>
                                        <td><?=$solicitudesprod[$i]['justificacion']?></td>
                                        <?php if ($estado === 'SOLICITADO') : ?>
                                        <td style="text-align: right">
                                            <button title="Detalle" class="btn btn-circle btn-bordered btn-info" onclick="detalle(<?=$solicitudesprod[$i]['id_producto_sol']?>)"><i class="fa fa-list"></i></button>
                                            <button title="Aprobar" class="btn btn-circle btn-bordered btn-success" onclick="aprobar(<?=$solicitudesprod[$i]['id_producto_sol']?>)"><i class="fa fa-check"></i></button>
                                            <button title="Rechazar" class="btn btn-circle btn-bordered btn-danger" onclick="rechazar(<?=$solicitudesprod[$i]['id_producto_sol']?>)"><i class="fa fa-remove"></i></button>
                                        </td>
                                        <?php endif; ?>
                                    </tr>
                                    <?PHP endfor; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>