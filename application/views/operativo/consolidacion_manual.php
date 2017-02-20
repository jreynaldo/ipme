<div id="page-wrapper">
    <div class="row">
        <div class="row" style="padding-left: 20px; padding-right: 20px; height: 75vh;">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="page-header"><?= $title ?></h2>
                </div>
                <div class="col-lg-12">
                    <form enctype="multipart/form-data" action="<?= base_url().'index.php/sqlite/zip_manual' ?>" method="POST">
                        <div class="form-group">
                            <label>Nombre de usuario:</label>
                            <input name="username" type="text" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label>Operativo:</label>
                            <select name="boleta" class="form-control">
                                <option value="1">Mercados</option>
                                <option value="2">Comercializadoras</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Gesti&oacute;n:</label>
                            <input name="gestion" type="text" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label>Periodo (mes/semana):</label>
                            <input name="periodo" type="text" class="form-control"/>
                        </div>
                        <div class="form_group">
                            <label>Enviar este archivo:</label>
                            <input name="file" type="file"/>
                        </div>
                        <div>
                            &nbsp;
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Consolidar" class="btn btn-primary"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>