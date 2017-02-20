<script type="text/javascript">
    function productos() {
        $('#productos').html('Por favor espere...');
        $.post('productos_agricolas', {},
        function (result) {
            $('#productos').html(result);
        });
    }
    function detallado() {
        var cods = '';
        $('input:checkbox[name=codigo]:checked').each(function(i, val) {
            cods = cods + "'" + $(val).val() + "',";
        });
        if (cods.length > 0) {
            cods = cods.substr(0, cods.length -1);
            var perini = $("#gesini").val() + '_' + ($("#semini").val() < 10 ? '0' + $("#semini").val() : $("#semini").val());
            var perfin = $("#gesfin").val() + '_' + ($("#semfin").val() < 10 ? '0' + $("#semfin").val() : $("#semfin").val());
            post('../excel/precios_agricolas_rep_detallado', {
                perini: perini,
                perfin: perfin,
                cod: cods
            });
        } else {
            alert('Debe seleccionar al menos un producto.');
        }
    }
    function horizontal() {
        var cods = '';
        $('input:checkbox[name=codigo]:checked').each(function(i, val) {
            cods = cods + "'" + $(val).val() + "',";
        });
        if (cods.length > 0) {
            cods = cods.substr(0, cods.length -1);
            var perini = $("#gesini").val() + '.' + ($("#semini").val() < 10 ? '0' + $("#semini").val() : $("#semini").val());
            var perfin = $("#gesfin").val() + '.' + ($("#semfin").val() < 10 ? '0' + $("#semfin").val() : $("#semfin").val());
            post('../excel/precios_agricolas_rep_horizontal', {
                cod: cods,
                perini: perini,
                perfin: perfin
            });
        } else {
            alert('Debe seleccionar al menos un producto.');
        }
    }
    function procedencia() {
        var cods = '';
        $('input:checkbox[name=codigo]:checked').each(function(i, val) {
            cods = cods + "'" + $(val).val() + "',";
        });
        if (cods.length > 0) {
            cods = cods.substr(0, cods.length -1);
            post('../excel/precios_agricolas_rep_codigos', {
                gesini: $("#gesini").val(),
                semini: $("#semini").val(),
                gesfin: $("#gesfin").val(),
                semfin: $("#semfin").val(),
                cod: cods
            });
        } else {
            alert('Debe seleccionar al menos un producto.');
        }
    }
    function select() {
        $('input:checkbox[name=codigo]').prop('checked', true);
    }
    function unselect() {
        $('input:checkbox[name=codigo]').prop('checked', false);
    }
    function init() {
        productos();
        $("#gesini").select2({width: '80'});
        $("#semini").select2({width: '200'});
        $("#gesfin").select2({width: '80'});
        $("#semfin").select2({width: '200'});
        $.post('anio', {
            gestion: 2014
        },
        function (result) {
            $("#gesini").html(result);
            $("#gesini").trigger('change');
        });
    };
    function gesini() {
        $.post('semana', {
            gestion: $("#gesini").val()
        },
        function (result) {
            $("#semini").html(result);
            $("#semini").trigger('change');
        });
        $.post('anio', {
            gestion: $("#gesini").val()
        },
        function (result) {
            $("#gesfin").html(result);
            $("#gesfin").val($("#gesfin option").last().val()).trigger('change');
        });
    }
    function gesfin() {
        $.post('semana', {
            gestion: $("#gesfin").val()
        },
        function (result) {
            $("#semfin").html(result);
            $("#semfin").val($("#semfin option").last().val()).trigger('change');
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
                <div class="col-lg-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <i class="fa fa-calendar"></i> Seleccione Periodo
                        </div>
                        <div class="panel-body">
                            <div style="background-color: #b6d1f2; color: white; font-size: medium; padding: 5px">&nbsp;Inicio</div>
                            <div>&nbsp;</div>
                            <div><div style="width: 60px; text-align: right; float: left;">A&ntilde;o: &nbsp;</div><select id="gesini" onchange="gesini()" name="gesini"></select></div>
                            <div>&nbsp;</div>
                            <div><div style="width: 60px; text-align: right; float: left;">Mes: &nbsp;</div><select id="semini" name="semini"></select></div>
                            <div>&nbsp;</div>
                            <div style="background-color: #b6d1f2; color: white; font-size: medium; padding: 5px">&nbsp;Fin</div>
                            <div>&nbsp;</div>
                            <div><div style="width: 60px; text-align: right; float: left;">A&ntilde;o: &nbsp;</div><select id="gesfin" onchange="gesfin()" name="gesfin"></select></div>
                            <div>&nbsp;</div>
                            <div><div style="width: 60px; text-align: right; float: left;">Mes: &nbsp;</div><select id="semfin" name="semfin"></select></div>
                            <div>&nbsp;</div>
                            <div style="margin-bottom: 10px">
                                <button class="btn btn-primary" type="button" onclick="detallado()">
                                    <i class="fa fa-list"></i> Lista Detallada
                                </button>
                            </div>
                            <div style="margin-bottom: 10px">
                                <button class="btn btn-primary" type="button" onclick="horizontal()">
                                    <i class="fa fa-table"></i> Reporte Detallado
                                </button>
                            </div>
                            <div>
                                <button class="btn btn-primary" type="button" onclick="procedencia()">
                                    <i class="fa fa-arrows"></i> Procendencia
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <i class="fa fa-apple"></i> Marque los Productos
                        </div>
                        <div class="panel-body">
                            <button class="btn btn-primary" type="button" onclick="productos()">
                                <i class="fa fa-refresh"></i> Actualizar
                            </button>
                            <button class="btn btn-primary" type="button" onclick="select()">
                                <i class="fa fa-check-square-o"></i> Marcar Todo
                            </button>
                            <button class="btn btn-primary" type="button" onclick="unselect()">
                                <i class="fa fa-square-o"></i> Desmarcar Todo
                            </button>
                            <br/><br/>
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

<div style="clear: both;"></div>