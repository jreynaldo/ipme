<script type="text/javascript">
    function agregar() {
        BootstrapDialog.show({
            title: 'Usuario',
            message: $('<div>Cargando ...</div>').load('usuario_form', {id: -1}),
            buttons: [{
                    label: 'Aceptar',
                    cssClass: 'btn-primary',
                    action: function (dialog) {
                        var $button = this;
                        $button.disable();
                        $.post('insert_usuario', $('#form_usuario').serialize(), function (result) {
                            if (result === 'Ok') {
                                location.reload();
                            } else {
                                $button.enable();
                                alert(result);
                            }
                        });
                    }
                }, {
                    label: 'Cancelar',
                    action: function (dialog) {
                        dialog.close();
                    }
                }]
        });
    }
    function editar(id) {
        BootstrapDialog.show({
            title: 'Usuario',
            message: $('<div>Cargando ...</div>').load('usuario_form', {id: id}),
            buttons: [{
                    label: 'Aceptar',
                    cssClass: 'btn-primary',
                    action: function (dialog) {
                        var $button = this;
                        $button.disable();
                        $.post('update_usuario', $('#form_usuario').serialize(), function (result) {
                            if (result === 'Ok') {
                                location.reload();
                            } else {
                                $button.enable();
                                alert(result);
                            }
                        });
                    }
                }, {
                    label: 'Cancelar',
                    action: function (dialog) {
                        dialog.close();
                    }
                }]
        });
    }
    function init() {
    }
</script>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?= $title ?></h1>
        </div>
        <div class="row">
            <div class="panel-body">
                <div>
                    <button class="btn btn-primary" onclick="agregar()"><i class="fa fa-plus"></i> Agregar Usuario</button>
                </div>
                <div class="container">  

                    <form  class="form-horizontal" id="form" method="GET" action="<?= base_url() ?>index.php/inicio/administrar_usuario">


                        <div class="row">    
                            <div class="col-md-6">  

                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="col-sm-2 control-label">Estado:</label>
                                    <div class="col-sm-10">
                                        <select name="estado"  style="width:200px;height:30px;" class="form-control">
                                            <option value="TRUE" <?php echo set_select('estado', 'TRUE', !isset($estado) ? TRUE : ($estado == "TRUE" ? TRUE : FALSE)); ?> >Activos</option>
                                            <option value="FALSE" <?php echo set_select('estado', 'FALSE', !isset($estado) ? FALSE : ($estado == "FALSE" ? TRUE : FALSE)); ?> >Inactivos</option>
                                        </select>  
                                    </div>
                                </div>                        

                                <div class="form-group">

                                    <label for="exampleInputEmail1" class="col-sm-2 control-label">Departamento:</label>
                                    <div class="col-sm-10">                       

                                        <select  style="width:200px;height:30px" class="form-control" name="dep">
                                            <option value="0" <?php echo set_select('dep', '0', !isset($dep) ? TRUE : ($dep == "0" ? TRUE : FALSE)); ?> >Todos</option>
                                            <option value="1" <?php echo set_select('dep', '1', !isset($dep) ? FALSE : ($dep == "1" ? TRUE : FALSE)); ?> >Chuquisaca</option>
                                            <option value="2" <?php echo set_select('dep', '2', !isset($dep) ? FALSE : ($dep == "2" ? TRUE : FALSE)); ?> >La Paz</option>
                                            <option value="3" <?php echo set_select('dep', '3', !isset($dep) ? FALSE : ($dep == "3" ? TRUE : FALSE)); ?> >Cochabamba</option>
                                            <option value="4" <?php echo set_select('dep', '4', !isset($dep) ? FALSE : ($dep == "4" ? TRUE : FALSE)); ?> >Oruro</option>
                                            <option value="5" <?php echo set_select('dep', '5', !isset($dep) ? FALSE : ($dep == "5" ? TRUE : FALSE)); ?> >Potosi</option>
                                            <option value="6" <?php echo set_select('dep', '6', !isset($dep) ? FALSE : ($dep == "6" ? TRUE : FALSE)); ?> >Tarija</option>
                                            <option value="7" <?php echo set_select('dep', '7', !isset($dep) ? FALSE : ($dep == "7" ? TRUE : FALSE)); ?> >Santa Cruz</option>
                                            <option value="8" <?php echo set_select('dep', '8', !isset($dep) ? FALSE : ($dep == "8" ? TRUE : FALSE)); ?> >Beni</option>
                                            <option value="9" <?php echo set_select('dep', '9', !isset($dep) ? FALSE : ($dep == "9" ? TRUE : FALSE)); ?> >Pando</option>
                                        </select>     
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="col-sm-2 control-label">Usuario:</label>
                                    <div class="col-sm-10">
                                        <input type="text" style="width:200px;height:30px" name="s_login" value="<?= $s_login ?>"  class="form-control" id="s_login" placeholder="Usuario">
                                    </div>    

                                </div>                            





                                <div>
                                    <button class="btn btn-primary" id="buscar"></i> Buscar</button>
                                </div>

                            </div>
                            <div class="col-md-6">  
                                <div   class="form-group">
                                    <label for="exampleInputEmail1" style="vertical-align: down"class="col-sm-2 control-label">Nombre:</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="s_nombre" style="width:200px;height:30px" value="<?= $s_nombre ?>"  class="form-control" id="s_nombre" placeholder="Nombre">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="col-sm-2 control-label">Apellido Paterno :</label>
                                    <div class="col-sm-10">
                                        <input type="text"  style="width:200px;height:30px" name="s_apellido_p" value="<?= $s_apellido_p ?>"  class="form-control" id="s_apellido_p" placeholder="Apellido Paterno">
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="col-sm-2 control-label">Apellido Materno:</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="s_apellido_m" style="width:200px;height:30px" value="<?= $s_apellido_m ?>"  class="form-control" id="s_apellido_m" placeholder="Apellido Materno">
                                    </div>  
                                </div>  
                            </div>
                        </div>

                    </form>


                </div>
                <BR>

                <table class="table table-advance table-bordered tbl">
                    <thead>
                        <tr>         <th>Departamento</th>


                            <th>Usuario</th>
                            <th>Activo</th>
                            <th>Nombre</th>
                            <th>Apellido Paterno</th>
                            <th>Apellido Materno</th>
                            <th>Tipo Usuario</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios AS $usuario) : ?>
                            <tr>
                                <td><?= $usuario['descripcion'] ?></td>

                                <td><?= $usuario['login'] ?></td>
                                <td><?= $usuario['activo'] == 't' ? '<input type="checkbox" onclick="return false" checked="checked"/>' : '<input type="checkbox" onclick="return false"/>' ?></td>
                                <td><?= $usuario['nombre'] ?></td>
                                <td><?= $usuario['paterno'] ?></td>
                                <td><?= $usuario['materno'] ?></td>
                                <td><?= $usuario['grupo'] ?></td>
                                <td>
                                    <button title="Editar" class="btn btn-circle btn-primary" onclick="editar(<?= $usuario['id_usuario'] ?>)"><i class="fa fa-edit"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>