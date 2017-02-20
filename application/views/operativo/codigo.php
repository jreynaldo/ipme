<script type="text/javascript">
    function calcular() {
        var alea = $("#alea").val();
        $.post('calcular', {
            alea: alea
        }, function(result) {
            alert(result);
        });
    }
    function init() {
        //$("#alea").keydown(function(event){return numero(event);});
    }
</script>
<div id="page-wrapper">
    <div class="row">
        <div class="row" style="padding-left: 20px; padding-right: 20px; height: 75vh;">
            <div class="row">
                <div>&nbsp;</div>
                <div class="col-lg-3">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="clearfix"><i class="fa fa-codepen"></i> <?= $title ?></h4>
                        </div>
                        <div class="panel-body">
                            <div class="form-group" style="padding-bottom: 10px;">
                                <label class="control-label">Codigo:</label>
                                <input id="alea" name="alea" type="number"/>
                            </div>
                            <div style="padding-top: 10px;">
                                <button class="btn btn-primary" type="button" onclick="calcular()">
                                    <i class="fa fa-calculator"></i> Calcular
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>