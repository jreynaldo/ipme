<script type="text/javascript">
    function map(latitud, longitud) {
        post('../mapa/punto', {
            latitud: latitud,
            longitud: longitud
        });
    }
    function rep() {
        if ($('#per').val()) {
            $('#productos').html('Por favor espere...');
            $.post('producto', {
                tipo: $('#tipo').val(),
                ges: $('#ges').val(),
                per: $('#per').val(),
                depto: $('#depto').val(),
                cot: $('#cot').val()
            },
            function (result) {
                $('#productos').html(result);
            });
        }
    }
    function init() {
        $("#depto").select2({width: 'resolve'});
        $("#tipo").select2({width: 'resolve'});
        $("#ges").select2({width: 'resolve'});
        $("#per").select2({width: 'resolve'});
        $("#cot").select2({width: 'resolve'});
        $.post('anio', {
            gestion: 2014
        },
        function (result) {
            $("#ges").html(result);
            $("#ges").val($("#ges option").last().val()).trigger('change');
        });
        $.post('cotizador', {
            depto: $("#depto").val()
        },
        function (result) {
            $("#cot").html(result);
            $("#cot").val($("#cot option").last().val()).trigger('change');
        });
    };
    function ges() {
        if ($('#tipo').val() == 1) {
            $.post('semana', {
                gestion: $("#ges").val()
            },
            function (result) {
                $("#per").html(result);
                $("#per").val($("#per option").last().val()).trigger('change');
            });
        } else {
            $.post('mes', {
                gestion: $("#ges").val()
            },
            function (result) {
                $("#per").html(result);
                $("#per").val($("#per option").last().val()).trigger('change');
            });
        }
    }
    function depto() {
        $.post('cotizador', {
            depto: $("#depto").val()
        },
        function (result) {
            $("#cot").html(result);
            $("#cot").trigger('change');
        });
    }
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
                            <i class="fa fa-calendar"></i> Seleccione Periodo
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label>Tipo</label>
                                <?= form_dropdown('tipo', $tipo, 1, 'id="tipo" class="form-control" onchange="ges()"') ?>
                            </div>
                            <div class="form-group">
                                <label>Departamento:</label>
                                <?= form_dropdown('depto', $departamentos, $values['id_departamento'], 'id="depto" class="form-control" onchange="depto()"') ?>
                            </div>
                            <div class="form-group">
                                <label>A&ntilde;o:</label>
                                <select id="ges" onchange="ges()" name="ges" class="form-control"></select>
                            </div>
                            <div class="form-group">
                                <label>Periodo:</label>
                                <select id="per" name="per" onchange="rep()" class="form-control"></select>
                            </div>
                            <div class="form-group">
                                <label>Cotizador:</label>
                                <select id="cot" name="cot" onchange="rep()" class="form-control"></select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <i class="fa fa-calendar"></i> Cotizaciones
                        </div>
                        <div class="panel-body">
                            <div id="productos">
                                Por favor espere...
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>