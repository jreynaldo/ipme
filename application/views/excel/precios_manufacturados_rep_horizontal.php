    <Worksheet ss:Name="Alignment">
        <Table>
            <Column ss:Index="3" ss:Width="150"/>
            <Column ss:Index="7" ss:Width="250"/>
            <Column ss:Index="12" ss:Width="50"/>
            <Column ss:Index="13" ss:Width="100"/>
            <?php for ($i = 0; $i < count($periodo); $i++) : ?>
            <Column ss:Index="<?= 14 + $i; ?>" ss:Width="70"/>
            <?php endfor; ?>
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
            <Row ss:Index="4" ss:AutoFitHeight="1">
                <Cell ss:Index="1" ss:StyleID="Header">
                    <Data ss:Type="String">ID PRODUCTO</Data>
                </Cell>
                <Cell ss:Index="2" ss:StyleID="Header">
                    <Data ss:Type="String">CIUDAD</Data>
                </Cell>
                <Cell ss:Index="3" ss:StyleID="Header">
                    <Data ss:Type="String">COMERCIALIZADORA</Data>
                </Cell>
                <Cell ss:Index="4" ss:StyleID="Header">
                    <Data ss:Type="String">ORIGEN</Data>
                </Cell>
                <Cell ss:Index="5" ss:StyleID="Header">
                    <Data ss:Type="String">CODIGO</Data>
                </Cell>
                <Cell ss:Index="6" ss:StyleID="Header">
                    <Data ss:Type="String">PRODUCTO</Data>
                </Cell>
                <Cell ss:Index="7" ss:StyleID="Header">
                    <Data ss:Type="String">ESPECIFICACION</Data>
                </Cell>
                <Cell ss:Index="8" ss:StyleID="Header">
                    <Data ss:Type="String">MARCA</Data>
                </Cell>
                <Cell ss:Index="9" ss:StyleID="Header">
                    <Data ss:Type="String">MODELO</Data>
                </Cell>
                <Cell ss:Index="10" ss:StyleID="Header">
                    <Data ss:Type="String">ENVASE</Data>
                </Cell>
                <Cell ss:Index="11" ss:StyleID="Header">
                    <Data ss:Type="String">UNIDAD/TALLA/PESO</Data>
                </Cell>
                <Cell ss:Index="12" ss:StyleID="Header">
                    <Data ss:Type="String">CARGA</Data>
                </Cell>
                <Cell ss:Index="13" ss:StyleID="Header">
                    <Data ss:Type="String">UNIDAD</Data>
                </Cell>
                <?php for ($i = 0; $i < count($periodo); $i++) : ?>
                <Cell ss:Index="<?= 14 + $i; ?>" ss:StyleID="Header">
                    <Data ss:Type="String"><?= $periodo[$i]['p']; ?></Data>
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
                    <Data ss:Type="String"><?= $reporte[$i]['ciudad']; ?></Data>
                </Cell>
                <Cell ss:Index="3">
                    <Data ss:Type="String"><?= $reporte[$i]['comercializadora']; ?></Data>
                </Cell>
                <Cell ss:Index="4">
                    <Data ss:Type="String"><?= $reporte[$i]['origen'] ?></Data>
                </Cell>
                <Cell ss:Index="5">
                    <Data ss:Type="String"><?= $reporte[$i]['codigo'] ?></Data>
                </Cell>
                <Cell ss:Index="6">
                    <Data ss:Type="String"><?= $reporte[$i]['producto'] ?></Data>
                </Cell>
                <Cell ss:Index="7">
                    <Data ss:Type="String"><?= $reporte[$i]['especificacion'] ?></Data>
                </Cell>
                <Cell ss:Index="8">
                    <Data ss:Type="String"><?= $reporte[$i]['marca'] ?></Data>
                </Cell>
                <Cell ss:Index="9">
                    <Data ss:Type="String"><?= $reporte[$i]['modelo'] ?></Data>
                </Cell>
                <Cell ss:Index="10">
                    <Data ss:Type="String"><?= $reporte[$i]['envase'] ?></Data>
                </Cell>
                <Cell ss:Index="11">
                    <Data ss:Type="String"><?= $reporte[$i]['unidad_talla_peso'] ?></Data>
                </Cell>
                <Cell ss:Index="12">
                    <Data ss:Type="String"><?= $reporte[$i]['carga'] ?></Data>
                </Cell>
                <Cell ss:Index="13" ss:StyleID="Right">
                    <Data ss:Type="String"><?= $reporte[$i]['unidad'] ?></Data>
                </Cell>
                <?php $n = count($keys) - count($periodo);
                for ($j = $n; $j < count($keys); $j ++) {
                    if ($reporte[$i][$keys[$j]] != '') {
                        $celda = explode(';', $reporte[$i][$keys[$j]]);
                        switch ($celda[1]) {
                            case 20:
                                echo '<Cell ss:Index="'.($j + 14 - $n).'" ss:StyleID="Precio20">'."\r\n";
                                break;
                            case 21:
                                echo '<Cell ss:Index="'.($j + 14 - $n).'" ss:StyleID="Precio21">'."\r\n";
                                break;
                            case 30:
                                echo '<Cell ss:Index="'.($j + 14 - $n).'" ss:StyleID="Precio30">'."\r\n";
                                break;
                            case 31:
                                echo '<Cell ss:Index="'.($j + 14 - $n).'" ss:StyleID="Precio31">'."\r\n";
                                break;
                            case 40:
                                echo '<Cell ss:Index="'.($j + 14 - $n).'" ss:StyleID="Precio40">'."\r\n";
                                break;
                            case 51:
                                echo '<Cell ss:Index="'.($j + 14 - $n).'" ss:StyleID="Precio51">'."\r\n";
                                break;
                            case 60:
                                echo '<Cell ss:Index="'.($j + 14 - $n).'" ss:StyleID="Precio51">'."\r\n";
                                break;
                            default :
                                echo '<Cell ss:Index="'.($j + 14 - $n).'" ss:StyleID="Precio">'."\r\n";
                                break;
                        }
                        if ($celda[0] == '') {
                            echo '<Data ss:Type="String"></Data>';
                        } else {
                            echo '<Data ss:Type="Number">'.(Float)$celda[0]."</Data>\r\n";
                        }
                        if (trim($celda[2]) != '' || ($celda[1] != '0' && $celda[1] != '10')) {
                            echo '<Comment ss:Author="10">';
                            echo '<ss:Data xmlns="http://www.w3.org/TR/REC-html40">';
                            switch ($celda[1]) {
                                case 20:
                                    echo "20. Cambio de Esp.  ";
                                    break;
                                case 21:
                                    echo "21. Cambio de Inf.  ";
                                    break;
                                case 30:
                                    echo "30. Liquidación  ";
                                    break;
                                case 31:
                                    echo "31. Promoción/Oferta  ";
                                    break;
                                case 40:
                                    echo "40. Irregular  ";
                                    break;
                                case 51:
                                    echo "51. Inexistencia Temporal  ";
                                    break;
                                case 60:
                                    echo "60. Precio a imputar  ";
                                    break;
                            }
                            echo $celda[2];
                            echo '</ss:Data>';
                            echo '</Comment>';
                        }
                        echo "</Cell>\r\n";
                    } else {
                        if ($j == 8) {
                            echo '<Cell ss:Index="9"><Data ss:Type="String"></Data></Cell>';
                        }
                    }
                } ?>
            </Row>
            <?php endfor; ?>
        </Table>
    </Worksheet>
</Workbook>