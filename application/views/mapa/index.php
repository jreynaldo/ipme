<script type="text/javascript">
    function init() {
        $.post('operativo/anio', {
            gestion: 2014
        }, function(result) {
            $('#anio').html(result);
            $('#anio').val($('#anio option').last().val()).trigger('change');
        });
        $('#tipo').select2();
        $('#depto').select2();
        $('#anio').select2();
        $('#periodo').select2();
    }
    function cambio() {
        $.post('operativo/periodo', {
            tipo: $('#tipo').val(),
            gestion: $('#anio').val()
        },
        function (result) {
            $('#periodo').html(result);
            $('#periodo').val($('#periodo option').last().val()).trigger('change');
        });
    }
    function mapa() {
        post('mapa/mapa', {
            tipo: $("#tipo").val(),
            anio: $("#anio").val(),
            periodo: $("#periodo").val(),
            depto: $("#depto").val()
        });
    }
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
                            <form id="form_agric">
                                <div class="form-group">
                                    <label>Tipo:</label>
                                    <?= form_dropdown('tipo', $tipo, $t, 'id="tipo" class="form-control" onchange="cambio()"') ?>
                                </div>
                                <div class="form-group">
                                    <label>Departamento:</label>
                                    <?= form_dropdown('depto', $departamento, $id_departamento, 'id="depto" class="form-control"') ?>
                                </div>
                                <div class="form-group">
                                    <label>A&ntilde;o:</label>
                                    <select id="anio" onchange="cambio()" name="anio" class="form-control"></select>
                                </div>
                                <div class="form-group">
                                    <label>Periodo:</label>
                                    <select id="periodo" name="periodo" class="form-control"></select>
                                </div>
                                <div>
                                    <button class="btn btn-primary" type="button" onclick="mapa()">
                                        <i class="icon-globe"></i> Mapa
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>