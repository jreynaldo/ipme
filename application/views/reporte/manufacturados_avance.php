<meta charset="utf-8"/>
<script src="<?= base_url() ?>dist/amcharts_3.3.4/amcharts.js" type="text/javascript"></script>
<script src="<?= base_url() ?>dist/amcharts_3.3.4/serial.js" type="text/javascript"></script>
<script type="text/javascript">
    function init() {
        var chartData = <?= $chartData ?>;

        var chart = new AmCharts.AmSerialChart();
        chart.dataProvider = chartData;
        chart.categoryField = "departamento";

        chart.graphs = <?= $etiqueta ?>;
        chart.categoryAxis.gridPosition = 'start';
        chart.categoryAxis.labelRotation = 45;
        chart.angle = 30;
        chart.depth3D = 15;
        var legend = new AmCharts.AmLegend();
        legend.autoMargins = true;
        legend.useGraphSettings = true;
        chart.legend = legend;
        chart.valueAxes = [{stackType: "regular"}];
        chart.startDuration = 1;

        chart.write('chartdiv');
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
                            <h4 class="clearfix"><?= $title; ?></h3></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <i class="fa fa-user fa-fw"></i>Periodo
                        </div>
                        <div class="panel-body">
                            <?php if ($this->input->get('anterior')): ?>
                            <a href="manufacturados_avance" class="btn btn-primary">Siguiente Mes</a>
                            <?php else: ?>
                                <a href="manufacturados_avance?anterior=1" class="btn btn-primary">Anterior Mes</a>
                            <?php endif; ?>
                            <h3><i class="icon-ok-circle"></i>Consolidación mes: <?= $periodo; ?></h3>
                            <div id="chartdiv" style="width: 100%; height: 460px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>