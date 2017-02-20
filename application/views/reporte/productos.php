<table id="Productos" class="table table-bordered">
    <thead>
        <tr>
            <th style="width: 80px;">Codigo</th>
            <th>Producto</th>
        </tr>
    </thead>
    <tbody>
        <?php for ($i = 0; $i < count($productos); $i++) : ?>
        <tr>
            <td>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="codigo" value="<?= $productos[$i]['codigo']; ?>"/> <?= $productos[$i]['codigo']; ?>
                    </label>
                </div>
            </td>
            <td><?= $productos[$i]['descripcion']; ?></td>
        </tr>
        <?php endfor; ?>
    </tbody>
</table>
<script type="text/javascript">
    oTable = $('#Productos').DataTable({paging: false, info: false, autoWidth: false});
</script>