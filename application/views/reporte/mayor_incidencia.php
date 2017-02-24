<script type="text/javascript">
    function init() { }
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
                            <i class="fa fa-calculator"></i> Incidencia Positiva
                        </div>
                        <div class="panel-body">
                            <table class="table table-striped table-bordered table-hover">
                                <tr>
                                    <th>Origen</th>
                                    <th>Especificacion</th>
                                    <th>Incidencia</th>
                                    <th>Variación</th>
                                </tr>
                                <?php for ($i = 0; $i < 5; $i++) : ?>
                                <tr>
                                    <td><?= $positiva[$i]['origen']; ?></td>
                                    <td><?= $positiva[$i]['especificacion']; ?></td>
                                    <td style="text-align: right"><?= number_format($positiva[$i]['incidencia'], 5); ?></td>
                                    <td style="text-align: right"><?= number_format($positiva[$i]['variacion'], 5); ?></td>
                                </tr>
                                <?php endfor; ?>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <i class="fa fa-calculator"></i> Incidencia Negativa
                        </div>
                        <div class="panel-body">
                            <table class="table table-striped table-bordered table-hover">
                                <tr>
                                    <th>Origen</th>
                                    <th>Especificacion</th>
                                    <th>Incidencia</th>
                                    <th>Variación</th>
                                </tr>
                                <?php for ($i = 0; $i < 5; $i++) : ?>
                                <tr>
                                    <td><?= $negativa[$i]['origen']; ?></td>
                                    <td><?= $negativa[$i]['especificacion']; ?></td>
                                    <td style="text-align: right"><?= number_format($negativa[$i]['incidencia'], 5); ?></td>
                                    <td style="text-align: right"><?= number_format($negativa[$i]['variacion'], 5); ?></td>
                                </tr>
                                <?php endfor; ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>