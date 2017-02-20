<script type="text/javascript">
    function editar(id) {
        post('<?=site_url();?>/canasta/comercializadoras', {id: id});
    }
    function init() {
        <?php if (isset($pendientes)) { ?>
            BootstrapDialog.show({
                title: 'Productos que requieren su atención.',
                message: $('<div><?=$pendientes;?></div>'),
                buttons: [{
                    label: 'Aceptar',
                    cssClass: 'btn-primary',
                    action: function(dialog) {
                        dialog.close();
                    }
                }]
            });
        <?php } ?>
    }
</script>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?= $title ?></h1>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-android fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge">Versi&oacute;n 1.08</div>
                                <div>Aplicaci&oacute;n Android IPM</div>
                            </div>
                        </div>
                    </div>
                    <a href="<?= base_url() ?>download/ipm.apk">
                        <div class="panel-footer">
                            <span class="pull-left">Descargar</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="panel panel-green">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-globe fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge">Cartograf&iacute;a</div>
                                <div>Mapa de Bolivia</div>
                            </div>
                        </div>
                    </div>
                    <a href="<?= base_url() ?>download/bolivia.map" download="bolivia.map">
                        <div class="panel-footer">
                            <span class="pull-left">Descargar</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>