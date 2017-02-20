<script type="text/javascript">
    function productos() {
        $('#productos').html('Por favor espere...');
        $.post('producto_indice', {},
        function (result) {
            $('#productos').html(result);
        });
    }
    function horizontal() {
        var cods = '';
        $('input:checkbox[name=codigo]:checked').each(function(i, val) {
            cods = cods + "'" + $(val).val() + "',";
        });
        if (cods.length > 0) {
            cods = cods.substr(0, cods.length -1);
            var perini = $("#gesini").val() + '_' + ($("#mesini").val() < 10 ? '0' + $("#mesini").val() : $("#mesini").val());
            var perfin = $("#gesfin").val() + '_' + ($("#mesfin").val() < 10 ? '0' + $("#mesfin").val() : $("#mesfin").val());
            post('../excel/encadenado', {
                cod: cods,
                perini: perini,
                perfin: perfin
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
        $("#mesini").select2({width: '120'});
        $("#gesfin").select2({width: '80'});
        $("#mesfin").select2({width: '120'});
        $.post('anio', {
            gestion: 2014
        },
        function (result) {
            $("#gesini").html(result);
            $("#gesini").trigger('change');
        });
    };
    function gesini() {
        $.post('mes', {
            gestion: $("#gesini").val()
        },
        function (result) {
            $("#mesini").html(result);
            $("#mesini").trigger('change');
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
        $.post('mes', {
            gestion: $("#gesfin").val()
        },
        function (result) {
            $("#mesfin").html(result);
            $("#mesfin").val($("#mesfin option").last().val()).trigger('change');
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
                            <div><div style="width: 60px; text-align: right; float: left;">Mes: &nbsp;</div><select id="mesini" name="mesini"></select></div>
                            <div>&nbsp;</div>
                            <div style="background-color: #b6d1f2; color: white; font-size: medium; padding: 5px">&nbsp;Fin</div>
                            <div>&nbsp;</div>
                            <div><div style="width: 60px; text-align: right; float: left;">A&ntilde;o: &nbsp;</div><select id="gesfin" onchange="gesfin()" name="gesfin"></select></div>
                            <div>&nbsp;</div>
                            <div><div style="width: 60px; text-align: right; float: left;">Mes: &nbsp;</div><select id="mesfin" name="mesfin"></select></div>
                            <div>&nbsp;</div>
                            <div style="margin-bottom: 10px">
                                <button class="btn btn-primary" type="button" onclick="horizontal()">
                                    <i class="fa fa-table"></i> Reporte Detallado
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
                            <!--&nbsp;Buscar:<input id="search" type="text" onkeyup="oTable.search($(this).val()).draw();"/>-->
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