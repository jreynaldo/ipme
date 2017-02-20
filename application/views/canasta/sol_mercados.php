<script type="text/javascript">
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
                            <form action="sol_mercados" method="post">
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
                                        <th>Mercado</th>
                                        <th>Codigo</th>
                                        <th>Producto</th>
                                        <th>Especificaci&oacute;n</th>
                                        <th>Unidad</th>
                                        <th>Equivalencia</th>
                                        <th>Acci&oacute;n</th>
                                        <th>Observaciones</th>
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
                                        <td><?=$solicitudesprod[$i]['comentario']?></td>
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