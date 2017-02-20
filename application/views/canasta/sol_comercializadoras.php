<script type="text/javascript">
    function editar(id_sol) {
        BootstrapDialog.show({
            title: 'Producto Manufacturado',
            message: $('<div>Cargando ...</div>').load('manufacturado_edit_form', {id: id_sol}),
            buttons: [{
                label: 'Aceptar',
                cssClass: 'btn-primary',
                action: function(dialog) {
                    var $button = this;
                    $button.disable();
                    var flag = true; var mensaje = '';
                    if ($('#codigo').val().length !== 10) {
                        flag = false;
                        mensaje += 'El codigo debe tener 10 dígitos.\n';
                    }
                    var vars = ['#producto','#especificacion','#cantidad_a_cotizar','#unidad_a_cotizar',
                        '#cantidad_equivalente','#justificacion'];
                    var mesg = ['Debe introducir el producto a cotizar.\n','Debe introducir la especificación del producto.\n',
                        'Debe especificar la cantidad a cotizar.\n','Debe especificar la unidad a cotizar.\n',
                        'Debe especificar la cantidad equivalente.\n','Debe justificar el cambio.\n'];
                    for (var i = 0; i < vars.length; i++) {
                        if ($(vars[i]).val().length === 0) {
                            flag = false;
                            mensaje += mesg[i];
                        }
                    }
                    if (flag) {
                        $.post('update_sol_man', $('#form_menu').serialize(), function (result) {
                            if (result === 'Ok') {
                                window.location = 'sol_comercializadoras';
                            } else {
                                $button.enable();
                                alert(result);
                            }
                        });
                    } else {
                        alert(mensaje);
                        $button.enable();
                    }
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
    }
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
                            <form action="sol_comercializadoras" method="post">
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
                                        <th>Departamento</th>
                                        <th>Nombre Comercial</th>
                                        <th>Codigo</th>
                                        <th>Producto</th>
                                        <th>Especificaci&oacute;n</th>
                                        <th>Marca</th>
                                        <th>Modelo</th>
                                        <th>Unidad</th>
                                        <th>Equivalencia</th>
                                        <th>Acci&oacute;n</th>
                                        <th>Comentario</th>
                                        <?php if ($estado == 'RECHAZADO') {
                                            echo '<th></th>';
                                        } ?>
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
                                        <td><?=$solicitudesprod[$i]['marca']?></td>
                                        <td><?=$solicitudesprod[$i]['modelo']?></td>
                                        <td><?=$solicitudesprod[$i]['unidad']?></td>
                                        <td><?=$solicitudesprod[$i]['equivalencia']?></td>
                                        <td style="color: green;"><?=$solicitudesprod[$i]['accion']?></td>
                                        <td><?=$solicitudesprod[$i]['comentario']?></td>
                                        <?php if ($estado == 'RECHAZADO') : ?>
                                        <td>
                                            <button title="Editar" class="btn btn-circle btn-bordered btn-primary" onclick="editar(<?=$solicitudesprod[$i]['id_producto_sol']?>)"><i class="fa fa-edit"></i></button>
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