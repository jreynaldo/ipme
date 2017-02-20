<script type="text/javascript">
    function select(id) {
        $('#id').val(id);
        $('#find').val('');
        $('#btnCancelClas').trigger('click');
    }
    function buscar() {
        BootstrapDialog.show({
            title: 'Producto',
            message: $('<div></div>').load('productos?id_tipo=' + $('#id_tipo').val() + '&descripcion=' + $('#find').val()),
            buttons: [{
                id: 'btnCancelClas',
                label: 'Cancelar',
                action: function(dialog) {
                    dialog.close();
                }
            }]
        });
    }
    function editar() {
        BootstrapDialog.show({
            title: 'Producto',
            message: $('<div></div>').load('precio?id_tipo=' + $('#id_tipo').val() + '&id=' + $('#id').val() + '&gestion=' + $('#gestion').val() + '&periodo=' + $('#periodo').val()),
            buttons: [{
                id: 'btnCancel',
                label: 'Cancelar',
                action: function(dialog) {
                    dialog.close();
                }
            }]
        });
    }
    function guardar(button, id_asignacion, correlativo, id_pregunta, fila, obj) {
        button.disabled = true;
        $.post('guardar', {
            id_asignacion: id_asignacion,
            correlativo: correlativo,
            id_pregunta: id_pregunta,
            fila: fila,
            valor: $('#' + obj).val()
        },
        function (result) {
            if (result === 'Ok') {
                $('#btnCancel').trigger('click');
            } else {
                alert(result);
            }
            button.disabled = false;
        });
    }
    function gestion() {
        $.post('periodo', {
            tipo: $('#id_tipo').val(),
            gestion: $('#gestion').val()
        },
        function (result) {
            $("#periodo").html(result);
            $("#periodo").val($("#periodo option").last().val()).trigger('change');
        });
    }
    function init() {
        $.post('anio', {
            gestion: 2014
        },
        function (result) {
            $("#gestion").html(result);
            $("#gestion").val($("#gestion option").last().val()).trigger('change');
        });
        $("#id_tipo").select2({width: 'resolve'});
        $("#gestion").select2({width: 'resolve'});
        $("#periodo").select2({width: 'resolve'});
    };
</script>
<div id="page-wrapper">
    <div class="row">
        <div class="row" style="padding-left: 20px; padding-right: 20px; height: 75vh;">
            <div class="row">
                <div>&nbsp;</div>
                <div class="col-lg-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="clearfix"><?= $title ?></h4>
                        </div>
                        <div class="panel-body">
                            <div class="form-group" style="padding-bottom: 10px;">
                                <label class="control-label">Tipo:</label>
                                <select class="form-control" id="id_tipo" onchange="gestion()">
                                    <option value="1">Agricola</option>
                                    <option value="2">Manufacturado</option>
                                </select>
                            </div>
                            <div class="form-group input-group">
                                <label class="control-label">Identificador:</label>
                                <div class="input-group">
                                    <input class="form-control" id="id" type="text" style="width: 50px;"/>
                                    <input class="form-control" id="find" type="text" style="width: 100px;"/>
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="button" onclick="buscar()">Buscar</button>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group" style="padding-bottom: 10px;">
                                <label class="control-label">A&ntilde;o:</label>
                                <select class="form-control" id="gestion" onchange="gestion()"></select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Periodo:</label>
                                <select class="form-control" id="periodo"></select>
                            </div>
                            <div style="padding-top: 10px;">
                                <button class="btn btn-primary" type="button" onclick="editar()">
                                    <i class="icon-edit"></i> Editar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>