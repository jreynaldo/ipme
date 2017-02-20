<script type="text/javascript">
    function guardar(button) {
        button.disabled = true;
        var img1 = $('#img1').prop('src');
        var index = img1.indexOf('base64') + 7;
        if (index > 6) {
            img1 = img1.substr(index);
        } else {
            img1 = null;
        }
        var img2 = $('#img2').prop('src');
        index = img2.indexOf('base64') + 7;
        if (index > 6) {
            img2 = img2.substr(index);
        } else {
            img2 = null;
        }
        $.post('../imagen/upload_image', {
            id: $('#id').val(),
            img1: img1,
            img2: img2
        }, function (result) {
            if (result === 'Ok') {
                window.location.href = "<?= $orig ?>";
            } else {
                alert(result);
            }
            button.disabled = false;
        });
    }
    function image(id) {
        if (id === 'img1') {
            $('#img1').css('opacity', 1);
            $('#img2').css('opacity', 0.3);
        } else {
            $('#img1').css('opacity', 0.3);
            $('#img2').css('opacity', 1);
        }
        img = '#' + id;
    }
    function resize() {
        paste($(img).prop('src'));
    }
    function paste(src) {
        var imgt = document.createElement('img');
        imgt.onload = function() {
            var MAX_WIDTH = 500;
            var MAX_HEIGHT = 500;
            var width = imgt.width;
            var height = imgt.height;
            var resize = false;
            if (width > height) {
                if (width > MAX_WIDTH) {
                    height *= MAX_WIDTH / width;
                    width = MAX_WIDTH;
                    resize = true;
                }
            } else {
                if (height > MAX_HEIGHT) {
                    width *= MAX_HEIGHT / height;
                    height = MAX_HEIGHT;
                    resize = true;
                }
            }
            if (resize) {
                var canvas = document.createElement('canvas');
                canvas.width = width;
                canvas.height = height;
                var ctx = canvas.getContext("2d");
                ctx.drawImage(imgt, 0, 0, width, height);
                var img1 = canvas.toDataURL("image/jpeg", 0.75);
                $(img).prop('src', img1);
            } else {
                $(img).prop('src', src);
            }
        };
        imgt.src = src;
    }
    function load(files) {
        if (!files.length) {
            alert('No se encontró el archivo.');
        } else {
            var reader = new FileReader();
            reader.onload = function(e) {
                paste(e.target.result);
            };
            reader.readAsDataURL(files[0]);
        }
    }
    function cargar(event) {
        $('#fileinput').trigger('click');
        event.preventDefault();
    }
    function init() {
        $('#img2').css('opacity', 0.3);
        img = '#img1';
        
        $(function() {
            $.pasteImage(paste);
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
                            <form id="formFile">
                                <div style="display:none">
                                    <input id="fileinput" type="file" accept="image/*" onchange="load(this.files)"/>
                                </div>
                            </form>
                            <input id="id" type="hidden" value="<?=$id?>"/>
                            <div style="float: left; margin: 10px">
                                <div><b>Imagen Producto</b></div>
                                <div>
                                    <img id="img1" src="<?=site_url()?>/Imagen/image1?id=<?=$id?>" onClick="image(this.id)" style="width: 150px; height: 150px;"/>
                                </div>
                            </div>
                            <div style="margin: 10px">
                                <div><b>Imagen Envase al Por Mayor</b></div>
                                <div>
                                    <img id="img2" src="<?=site_url()?>/Imagen/image2?id=<?=$id?>" onClick="image(this.id)" style="width: 150px; height: 150px;"/>
                                </div>
                            </div>
                            <div>
                                <button class="btn btn-info" onclick="cargar(event)"><i class="fa fa-upload"></i></button>
                                <button class="btn btn-primary" onClick="guardar(this)">Guardar</button>
                                <button class="btn btn-info" onclick="resize()"><i class="fa fa-image"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>