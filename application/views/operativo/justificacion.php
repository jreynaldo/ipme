<script type="text/javascript">
    function reporte() {
        var tipo = $('#id_tipo').val();
        var gestion = $('#gestion').val();
        var periodo = $('#periodo').val();
        var depto = $('#depto').val();
        if (typeof(depto) !== 'undefined') {
            window.location = 'justificacion_rep?tipo=' + tipo + '&gestion=' + gestion + '&periodo=' + periodo + '&depto=' + depto;
        } else {
            alert('Debe seleccionar un departamento.');
        }
    }
    function gestion() {
        $.post('periodo', {
            tipo: $('#id_tipo').val(),
            gestion: $('#gestion').val()
        },
        function (result) {
            $("#periodo").html(result);
            $("#periodo").val($("#periodo option:last").prev().val()).trigger('change');
        });
    }
    function init() {
        $.post('anio', {
            gestion: 2016
        },
        function (result) {
            $("#gestion").html(result);
            $("#gestion").val($("#gestion option").last().val()).trigger('change');
            $("#depto").val(<?=$depto?>).trigger('change');
        });
        $("#id_tipo").select2({width: 'resolve'});
        $("#gestion").select2({width: 'resolve'});
        $("#periodo").select2({width: 'resolve'});
        $("#depto").select2({width: 'resolve'});
    };
</script>
<div id="page-wrapper">
    <div class="row">
        <div class="row" style="padding-left: 20px; padding-right: 20px; height: 75vh;">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="page-header"><?= $title ?></h2>
                </div>
                <div class="col-lg-3">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <i class="fa fa-location-arrow"></i> Seleccione Departamento
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="control-label">Tipo:</label>
                                <select class="form-control" id="id_tipo" onchange="gestion()">
                                    <option value="1" selected="selected">Semanal</option>
                                    <option value="2">Mensual</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">A&ntilde;o:</label>
                                <select class="form-control" id="gestion" onchange="gestion()"></select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Periodo:</label>
                                <select class="form-control" id="periodo"></select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Departamento:</label>
                                <select class="form-control" id="depto">
                                    <?php $keys = array_keys($departamento);
                                    for ($i = 0; $i < count($keys); $i++) : ?>
                                        <option value="<?=$keys[$i]?>"><?=$departamento[$keys[$i]]?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="control-group">
                                <button class="btn btn-primary" type="button" onclick="reporte()">
                                    <i class="fa fa-file"></i> Reporte
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>