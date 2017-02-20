    <Worksheet ss:Name="Alignment">
        <Table>
            <Column ss:Index="2" ss:Width="100"/>
            <Row ss:Index="1">
                <Cell ss:Index="1" ss:StyleID="Red">
                    <Data ss:Type="String"><?= $title; ?></Data>
                </Cell>
            </Row>
            <Row ss:Index="2">
                <Cell ss:Index="1" ss:StyleID="Red">
                    <Data ss:Type="String">(En bolivianos)</Data>
                </Cell>
            </Row>
            <Row ss:index="3">
                <?php for ($i = 0; $i < count($periodo); $i++) : ?>
                <Cell ss:Index="<?= 11 + $i * 6; ?>" ss:StyleID="Week" ss:MergeAcross="5">
                    <Data ss:Type="String"><?= $periodo[$i]['p']; ?></Data>
                </Cell>
                <?php endfor; ?>
            </Row>
            <Row ss:Index="4">
                <Cell ss:Index="1">
                    <Data ss:Type="String">ID PRODUCTO</Data>
                </Cell>
                <Cell ss:Index="2">
                    <Data ss:Type="String">DEPARTAMENTO</Data>
                </Cell>
                <Cell ss:Index="3">
                    <Data ss:Type="String">MERCADO</Data>
                </Cell>
                <Cell ss:Index="4">
                    <Data ss:Type="String">CODIGO</Data>
                </Cell>
                <Cell ss:Index="5">
                    <Data ss:Type="String">PRODUCTO</Data>
                </Cell>
                <Cell ss:Index="6">
                    <Data ss:Type="String">ESPECIFICACION</Data>
                </Cell>
                <Cell ss:Index="7">
                    <Data ss:Type="String">UNIDAD</Data>
                </Cell>
                <Cell ss:Index="8">
                    <Data ss:Type="String">EQUIVALENCIA</Data>
                </Cell>
                <Cell ss:Index="9">
                    <Data ss:Type="String">FACTOR</Data>
                </Cell>
                <Cell ss:Index="10">
                    <Data ss:Type="String">UNIDAD FINAL</Data>
                </Cell>
                <?php for ($i = 0; $i < count($periodo); $i++) : ?>
                    <Cell ss:Index="<?= 11 + $i * 6; ?>" ss:StyleID="BottomLeft">
                        <Data ss:Type="String">Pre1</Data>
                    </Cell>
                    <Cell ss:Index="<?= 12 + $i * 6; ?>" ss:StyleID="Bottom">
                        <Data ss:Type="String">Pre2</Data>
                    </Cell>
                    <Cell ss:Index="<?= 13 + $i * 6; ?>" ss:StyleID="Bottom">
                        <Data ss:Type="String">Pre3</Data>
                    </Cell>
                    <Cell ss:Index="<?= 14 + $i * 6; ?>" ss:StyleID="Bottom">
                        <Data ss:Type="String">Pre4</Data>
                    </Cell>
                    <Cell ss:Index="<?= 15 + $i * 6; ?>" ss:StyleID="Bottom">
                        <Data ss:Type="String">Pre5</Data>
                    </Cell>
                    <Cell ss:Index="<?= 16 + $i * 6; ?>" ss:StyleID="BottomRight">
                        <Data ss:Type="String">Pre6</Data>
                    </Cell>
                <?php endfor; ?>
            </Row>
            <?php if (count($reporte) > 0) {
                $keys = array_keys($reporte[0]);
            }
            for ($i = 0; $i < count($reporte); $i++) : ?>
            <Row ss:Index="<?= $i + 5; ?>">
                <Cell ss:Index="1">
                    <Data ss:Type="Number"><?= $reporte[$i]['id_producto']; ?></Data>
                </Cell>
                <Cell ss:Index="2">
                    <Data ss:Type="String"><?= $reporte[$i]['departamento']; ?></Data>
                </Cell>
                <Cell ss:Index="3">
                    <Data ss:Type="String"><?= $reporte[$i]['mercado']; ?></Data>
                </Cell>
                <Cell ss:Index="4">
                    <Data ss:Type="String"><?= $reporte[$i]['codigo'] ?></Data>
                </Cell>
                <Cell ss:Index="5">
                    <Data ss:Type="String"><?= $reporte[$i]['producto'] ?></Data>
                </Cell>
                <Cell ss:Index="6">
                    <Data ss:Type="String"><?= $reporte[$i]['especificacion'] ?></Data>
                </Cell>
                <Cell ss:Index="7">
                    <Data ss:Type="String"><?= $reporte[$i]['unidad'] ?></Data>
                </Cell>
                <Cell ss:Index="8">
                    <Data ss:Type="String"><?= $reporte[$i]['equivalencia'] ?></Data>
                </Cell>
                <Cell ss:Index="9">
                    <Data ss:Type="String"><?= $reporte[$i]['factor'] ?></Data>
                </Cell>
                <Cell ss:Index="10">
                    <Data ss:Type="String"><?= $reporte[$i]['unidad_final'] ?></Data>
                </Cell>
                <?php for ($j = 10; $j < count($keys); $j += 2) : ?>
                <Cell ss:Index="<?= ($j - 10) / 2 + 11; ?>" ss:StyleID="Precio">
                    <?php if ($reporte[$i][$keys[$j]] != '') : ?>
                    <Data ss:Type="Number"><?= $reporte[$i][$keys[$j]]; ?></Data>
                    <?php endif; if ($reporte[$i][$keys[$j + 1]] != '') : ?>
                    <Comment ss:Author="10">
                        <ss:Data xmlns="http://www.w3.org/TR/REC-html40"><?= $reporte[$i][$keys[$j + 1]]; ?></ss:Data>
                    </Comment>
                    <?php endif; ?>
                </Cell>
                <?php endfor; ?>
            </Row>
            <?php endfor; ?>
        </Table>
    </Worksheet>
</Workbook>