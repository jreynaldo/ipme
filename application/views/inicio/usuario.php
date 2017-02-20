<script type="text/javascript">
    function cambiar() {
        BootstrapDialog.show({
            title: 'Cambiar contrase&ntilde;a',
            message: $('<div></div>').load('<?= site_url() ?>/inicio/cambiar_form'),
            buttons: [{
                label: 'Aceptar',
                cssClass: 'btn-primary',
                action: function(dialog) {
                    var $button = this;
                    $button.disable();
                    if ($('#passn').val() === $('#passr').val()) {
                        $.post('<?= site_url() ?>/Inicio/cambiar', {
                            pass: $('#pass').val(),
                            passn: $('#passn').val()
                        }, function(result) {
                            if (result === 'Ok') {
                                dialog.close();
                            } else {
                                alert(result);
                                $button.enable();
                            }
                        });
                    } else {
                        alert('Escriba la misma contraseña en nueva y repetir.');
                        $button.enable();
                    }
                }
            }, {
                label: 'Cancelar',
                action: function(dialog) {
                    dialog.close();
                }
            }]
        });
    }
    function init() {}
</script>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?= $title ?></h1>
        </div>
        <div class="row" style="padding-left: 20px; padding-right: 20px;">
            <table class="table">
                <tbody>
                    <tr>
                        <th>Nombre</th>
                        <td><?= $nombre ?></td>
                    </tr>
                    <tr>
                        <th>Apellido</th>
                        <td><?= $paterno ?></td>
                    </tr>
                    <tr>
                        <th>Estado</th>
                        <td><?= $login ?></td>
                    </tr>
                </tbody>
            </table>
            <div>
                <button class="btn btn-primary" type="button" onclick="cambiar()">
                    <i class="fa fa-key"></i> Cambiar contrase&ntilde;a
                </button>
            </div>
        </div>
    </div>
</div>