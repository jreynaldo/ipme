<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Reporte
 *
 * @author Alberto Daniel Inch Sáinz
 */
class Cuentas extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('session');
        $this->load->library('Excel');
        $this->load->model('Periodo');
        $this->load->model('Producto');
        $this->load->model('Asignacion');
        $this->load->model('Usuario');
        $this->load->model('Indice');
        if (count($this->session->userdata()) == 1) {
            $this->session->set_userdata(Array('activo' => false, 'permisos' => Array()));
        }
    }
    
    public function reporte() {
        $sess = $this->session->userdata();
        if (in_array('cuentas', $sess['permisos'])) {
            $data['title'] = 'Promedio';
            $this->load->view('templates/header', $sess);
            $data['permisos'] = $sess['permisos'];
            $data['aprobado'] = $this->Indice->get_variable('aprobado');
            $this->load->view('cuentas/reporte', $data);
            $this->load->view('templates/footer');
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function ind_reporte() {
        $sess = $this->session->userdata();
        if (in_array('cuentas', $sess['permisos'])) {
            $data['title'] = 'Indice';
            $this->load->view('templates/header', $sess);
            $data['permisos'] = $sess['permisos'];
            $data['aprobado'] = $this->Indice->get_variable('aprobado');
            $this->load->view('cuentas/ind_reporte', $data);
            $this->load->view('templates/footer');
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function productos() {
        $sess = $this->session->userdata();
        if (in_array('cuentas', $sess['permisos'])) {
            $sector = $this->input->post('sector');
            $clasificacion = $this->input->post('clasificacion');
            $data['productos'] = $this->Producto->get_productos($sector, $clasificacion);
            $this->load->view('reporte/productos', $data);
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    public function imputado() {
        set_time_limit(48000);
        ini_set('MAX_EXECUTION_TIME', 48000);
        
        $sess = $this->session->userdata();
        if (in_array('indice', $sess['permisos'])) {
            $sector = $this->input->post('sector');
            if ($sector > 3) {
                redirect(site_url().'/inicio/error', 'refresh');
                return;
            }
            $informacion = '1';//$this->input->post('informacion');
            $clasificacion = '8';//$this->input->post('clasificacion');
            if ($clasificacion > 8) {
                redirect(site_url().'/inicio/error', 'refresh');
                return;
            }
            $codigos = $this->input->post('codigos');
            $periodicidad = '1';//$this->input->post('periodicidad');
            $gesini = $this->input->post('gesini');
            $perini = $this->input->post('perini');
            if (strlen($perini) == 1) {
                $perini = '0'.$perini;
            }
            if ($gesini.'-'.$perini < '2014-08') {
                redirect(site_url().'/inicio/error', 'refresh');
                return;
            }
            $gesfin = $this->input->post('gesfin');
            $perfin = $this->input->post('perfin');
            if (Strlen($perfin) == 1) {
                $perfin = '0'.$perfin;
            }
            $aprobado = $this->Indice->get_variable('aprobado');
            if ($gesfin.'-'.$perfin > $aprobado) {
                redirect(site_url().'/inicio/error', 'refresh');
                return;
            }
            $cuadros = Array(1, 2);//explode(',', $this->input->post('cuadro'));

            $objPHPExcel = new PHPExcel();
            switch ($sector) {
                case 1:
                    $objPHPExcel->getProperties()->setTitle("Precios Productos Agricolas")->setDescription("Precios Productos Agricolas");
                    $archivo = "Precios Productos Agricolas";
                    break;
                case 1.1:
                    $objPHPExcel->getProperties()->setTitle("Precios Productos Agricolas Nacional")->setDescription("Precios Productos Agricolas Nacional");
                    $archivo = "Precios Productos Agricolas Nacional";
                    break;
                case 1.2:
                    $objPHPExcel->getProperties()->setTitle("Precios Productos Agricolas Importado")->setDescription("Precios Productos Agricolas Importado");
                    $archivo = "Precios Productos Agricolas Importado";
                    break;
                case 2:
                    $objPHPExcel->getProperties()->setTitle("Precios Productos Manufacturados")->setDescription("Precios Productos Manufacturados");
                    $archivo = "Precios Productos Manufacturados";
                    break;
                case 2.1:
                    $objPHPExcel->getProperties()->setTitle("Precios Productos Manufacturados Nacional")->setDescription("Precios Productos Manufacturados Nacional");
                    $archivo = "Precios Productos Manufacturados Nacional";
                    break;
                case 2.2:
                    $objPHPExcel->getProperties()->setTitle("Precios Productos Manufacturados Importado")->setDescription("Precios Productos Manufacturados Importado");
                    $archivo = "Precios Productos Manufacturados Importado";
                    break;
                case 0.2:
                    $objPHPExcel->getProperties()->setTitle("Precios Productos Importados")->setDescription("Precios Productos Importados");
                    $archivo = "Precios Productos Importados";
                    break;
            }
            
            if (in_array(1, $cuadros)) {
                $reporte = $this->Indice->get_imputado($sector, $informacion, $clasificacion, $codigos, $periodicidad, $gesini.'-'.$perini, $gesfin.'-'.$perfin);
                
                $objPHPExcel->setActiveSheetIndex(0);
                $sheet = $objPHPExcel->getActiveSheet();

                $sheet->setCellValue('A1', "PRECIOS");
                $sheet->getStyle('A1')->getFont()->getColor()->setRGB('8F0000');
                $sheet->setCellValue('A2', "(En bolivianos)");
                $sheet->getStyle('A2')->getFont()->getColor()->setRGB('8F0000');

                $sheet->getRowDimension(4)->setRowHeight(-1);
                $col = 'A'; $col2 = ''; $col3 = ''; $gestion = '';
                $keys = array_keys($reporte[0]);
                for ($i = 0; $i < count($reporte); $i++) {
                    $col = 'A';
                    for ($j = 0; $j < count($keys); $j++) {
                        if ($i == 0) {
                            $sheet->getStyle($col.'4')->getAlignment()->setWrapText(true);
                            $sheet->getStyle($col.'4')->getFont()->setSize(9);

                            if ($j < 5) {
                                $sheet->mergeCells($col.'3:'.$col.'4');
                                $sheet->setCellValue($col.'3', strtoupper($keys[$j]));
                            } else {
                                $label = explode('_', $keys[$j]);
                                if ($gestion <> $label[0]) {
                                    $gestion = $label[0];
                                    $sheet->getStyle($col.'3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                    $sheet->setCellValue($col.'3', $gestion);
                                    //$sheet->getCell($col.'3')->setValueExplicit($gestion, PHPExcel_Cell_DataType::TYPE_STRING);
                                    if ($col3 <> '') {
                                        $sheet->mergeCells($col3.'3:'.$col2.'3');
                                        $sheet->getStyle($col2.'3')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                                    }
                                    $col3 = $col;
                                }
                                $sheet->setCellValue($col.'4', $label[1]);
                                $sheet->getStyle($col2.'4')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                                $col2 = $col;
                            }
                            $sheet->getStyle($col.'4')->getFont()->setBold(true);
                            $sheet->getStyle($col.'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            switch ($j) {
                                case 0:
                                    $sheet->getStyle('A3:A4')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                                    $sheet->getStyle('A3:A4')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                                    $sheet->getColumndimension($col)->setWidth(15);
                                    break;
                                case 1:
                                    $sheet->getStyle('B3:B4')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                                    $sheet->getColumndimension($col)->setWidth(12);
                                    break;
                                case 2:
                                    $sheet->getStyle('C3:C4')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                                    $sheet->getColumndimension($col)->setWidth(12);
                                    break;
                                case 3:
                                    $sheet->getStyle('D3:D4')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                                    $sheet->getColumndimension($col)->setWidth(26);
                                    break;
                                case 4:
                                    $sheet->getStyle('E3:E4')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                                    $sheet->getColumndimension($col)->setWidth(15);
                                    break;
                                default:
                                    $sheet->getColumndimension($col)->setWidth(10);
                                    $sheet->getStyle($col)->getNumberFormat()->setFormatCode('0.00');
                                    break;
                            }
                        }
                        if ($j == 0) {
                            $sheet->getStyle($col.($i + 5))->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                        }
                        $sheet->getStyle($col.($i + 5))->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                        $sheet->getStyle($col.($i + 5))->getNumberFormat()->setFormatCode('0.00');
                        if ($j == 2) {
                            $sheet->getCell($col.($i + 5))->setValueExplicit($reporte[$i][$keys[$j]], PHPExcel_Cell_DataType::TYPE_STRING);
                        } else {
                            $sheet->setCellValue($col.($i + 5), $reporte[$i][$keys[$j]]);
                        }
                        $col++;
                    }
                }
                $sheet->mergeCells($col3.'3:'.$col2.'3');
                $sheet->getStyle($col2.'3')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $sheet->getStyle('A3:'.$col2.'3')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $sheet->getStyle('F4:'.$col2.'4')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $sheet->getStyle('A5:'.$col2.'5')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $sheet->getStyle('A'.($i + 4).':'.$col2.($i + 4))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

                $sheet->setTitle("Por ciudad");
            }
            
            if (in_array(2, $cuadros)) {
                $reporte = $this->Indice->get_imputado_nac($sector, $informacion, $clasificacion, $codigos, $periodicidad, $gesini.'-'.$perini, $gesfin.'-'.$perfin);
                
                if (count($cuadros) > 1) {
                    $objWorksheet = new PHPExcel_Worksheet($objPHPExcel);
                    $objPHPExcel->addSheet($objWorksheet);
                    $objPHPExcel->setActiveSheetIndex(1);
                }
                $sheet = $objPHPExcel->getActiveSheet();
                
                $sheet->setCellValue('A1', "PRECIOS");
                $sheet->getStyle('A1')->getFont()->getColor()->setRGB('8F0000');
                $sheet->setCellValue('A2', "(En bolivianos)");
                $sheet->getStyle('A2')->getFont()->getColor()->setRGB('8F0000');

                $sheet->getRowDimension(4)->setRowHeight(-1);
                $col = 'A'; $col2 = ''; $col3 = ''; $gestion = '';
                $keys = array_keys($reporte[0]);
                for ($i = 0; $i < count($reporte); $i++) {
                    $col = 'A';
                    for ($j = 0; $j < count($keys); $j++) {
                        if ($i == 0) {
                            $sheet->getStyle($col.'4')->getAlignment()->setWrapText(true);
                            $sheet->getStyle($col.'4')->getFont()->setSize(9);

                            if ($j < 4) {
                                $sheet->mergeCells($col.'3:'.$col.'4');
                                $sheet->setCellValue($col.'3', strtoupper($keys[$j]));
                            } else {
                                $label = explode('_', $keys[$j]);
                                if ($gestion <> $label[0]) {
                                    $gestion = $label[0];
                                    $sheet->getStyle($col.'3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                    $sheet->setCellValue($col.'3', $gestion);
                                    //$sheet->getCell($col.'3')->setValueExplicit($gestion, PHPExcel_Cell_DataType::TYPE_STRING);
                                    if ($col3 <> '') {
                                        $sheet->mergeCells($col3.'3:'.$col2.'3');
                                        $sheet->getStyle($col2.'3')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                                    }
                                    $col3 = $col;
                                }
                                $sheet->setCellValue($col.'4', $label[1]);
                                $sheet->getStyle($col2.'4')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                                $col2 = $col;
                            }
                            $sheet->getStyle($col.'4')->getFont()->setBold(true);
                            $sheet->getStyle($col.'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            switch ($j) {
                                case 0:
                                    $sheet->getStyle('B3:B4')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                                    $sheet->getColumndimension($col)->setWidth(12);
                                    break;
                                case 1:
                                    $sheet->getStyle('C3:C4')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                                    $sheet->getColumndimension($col)->setWidth(12);
                                    break;
                                case 2:
                                    $sheet->getStyle('D3:D4')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                                    $sheet->getColumndimension($col)->setWidth(26);
                                    break;
                                case 3:
                                    $sheet->getStyle('E3:E4')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                                    $sheet->getColumndimension($col)->setWidth(15);
                                    break;
                                default:
                                    $sheet->getColumndimension($col)->setWidth(10);
                                    $sheet->getStyle($col)->getNumberFormat()->setFormatCode('0.00');
                                    break;
                            }
                        }
                        if ($j == 0) {
                            $sheet->getStyle($col.($i + 5))->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                        }
                        $sheet->getStyle($col.($i + 5))->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                        $sheet->getStyle($col.($i + 5))->getNumberFormat()->setFormatCode('0.00');
                        if ($j == 1) {
                            $sheet->getCell($col.($i + 5))->setValueExplicit($reporte[$i][$keys[$j]], PHPExcel_Cell_DataType::TYPE_STRING);
                        } else {
                            $sheet->setCellValue($col.($i + 5), $reporte[$i][$keys[$j]]);
                        }
                        $col++;
                    }
                }
                $sheet->mergeCells($col3.'3:'.$col2.'3');
                $sheet->getStyle($col2.'3')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $sheet->getStyle('A3:'.$col2.'3')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $sheet->getStyle('E4:'.$col2.'4')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $sheet->getStyle('A5:'.$col2.'5')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $sheet->getStyle('A'.($i + 4).':'.$col2.($i + 4))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

                $sheet->setTitle("Nacional");
            }

            // Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$archivo.' '.(new DateTime())->format('Y_m_d').'.xlsx"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function indice() {
        set_time_limit(12000);
        ini_set('MAX_EXECUTION_TIME', 12000);
        
        $sess = $this->session->userdata();
        if (in_array('indice', $sess['permisos'])) {
            $sector = $this->input->post('sector');
            if ($sector > 4) {
                redirect(site_url().'/inicio/error', 'refresh');
                return;
            }
            $clasificaciones = explode(',', $this->input->post('clasificacion'));
            if (count($clasificaciones) <> 1 || $clasificaciones[0] > 4) {
                redirect(site_url().'/inicio/error', 'refresh');
                return;
            }
            $gesini = $this->input->post('gesini');
            $perini = $this->input->post('perini');
            if (strlen($perini) == 1) {
                $perini = '0'.$perini;
            }
            if ($gesini.'-'.$perini < '2014-08') {
                redirect(site_url().'/inicio/error', 'refresh');
                return;
            }
            $gesfin = $this->input->post('gesfin');
            $perfin = $this->input->post('perfin');
            if (strlen($perfin) == 1) {
                $perfin = '0'.$perfin;
            }
            $aprobado = $this->Indice->get_variable('aprobado');
            if ($gesfin.'-'.$perfin > $aprobado) {
                redirect(site_url().'/inicio/error', 'refresh');
                return;
            }
            $cuadros = Array('1');//explode(',', $this->input->post('cuadro'));
            
            if ($sector < 7 || $sector == 10) {
                $etiqueta = $this->Indice->get_etiquetas();
            } else {
                if ($sector > 10) {
                    $etiqueta = $this->Indice->get_etiquetas_ciiu();
                } else {
                    $etiqueta = $this->Indice->get_etiquetas_cpc();
                }
            }
            
            $objPHPExcel = new PHPExcel();
            
            $objPHPExcel->setActiveSheetIndex(0);
            
            $archivo = ''; $celda = '';
            $z = 1;
            foreach ($cuadros AS $tipo) {
                $f = 1;
                foreach ($clasificaciones AS $clasificacion) {
                    $var = '';
                    switch ($tipo) {
                        case 1:
                            $reporte = $this->Indice->get_indice($sector, $clasificacion, $gesini.'_'.$perini, $gesfin.'_'.$perfin);
                            $var = 'Indice';
                            break;
                        case 2:
                            $reporte = $this->Indice->get_variacion($sector, $clasificacion, $gesini.'_'.$perini, $gesfin.'_'.$perfin, 1);
                            $var = 'Variación Mensual Indice';
                            break;
                        case 3:
                            $reporte = $this->Indice->get_variacion($sector, $clasificacion, $gesini.'_'.$perini, $gesfin.'_'.$perfin, 12);
                            $var = 'Variación Interanual Indice';
                            break;
                        case 4:
                            $reporte = $this->Indice->get_incidencia($sector, $clasificacion, $gesini.'_'.$perini, $gesfin.'_'.$perfin);
                            $var = 'Incidencia Mensual';
                            break;
                    }
                    switch ((Int)$sector) {
                        case 1:
                            $objPHPExcel->getProperties()->setTitle($var." Agricola")->setDescription($var." Agricola");
                            $archivo = 'Agricola';
                            break;
                        case 2:
                            $objPHPExcel->getProperties()->setTitle($var." Manufacturado")->setDescription($var." Manufacturado");
                            $archivo = 'Manufacturado';
                            break;
                        case 3:
                            $objPHPExcel->getProperties()->setTitle($var." Importado")->setDescription($var." Importado");
                            $archivo = 'Importado';
                            break;
                        case 7:
                            $objPHPExcel->getProperties()->setTitle($var." Nacional CPC")->setDescription($var." Nacional");
                            $archivo = 'Nacional CPC';
                            break;
                        case 8:
                            $objPHPExcel->getProperties()->setTitle($var." Importado CPC")->setDescription($var." Importado");
                            $archivo = 'Importado CPC';
                            break;
                        case 9:
                            $objPHPExcel->getProperties()->setTitle($var." CPC")->setDescription($var." CPC");
                            $archivo = 'CPC';
                            break;
                        case 11:
                            $objPHPExcel->getProperties()->setTitle($var." Nacional CIIU")->setDescription($var." Nacional");
                            $archivo = 'Nacional CIIU';
                            break;
                        case 12:
                            $objPHPExcel->getProperties()->setTitle($var." Importado CIIU")->setDescription($var." Importado");
                            $archivo = 'Importado CIIU';
                            break;
                        case 13:
                            $objPHPExcel->getProperties()->setTitle($var." CIIU")->setDescription($var." CIIU");
                            $archivo = 'CIIU';
                            break;
                        default:
                            $objPHPExcel->getProperties()->setTitle($var." General")->setDescription($var." General");
                            $archivo = 'General';
                            break;
                    }
                    
                    if ($sector < 7) {
                        switch ($clasificacion) {
                            case 1:
                                $celda = "SECCIÓN";
                                break;
                            case 2:
                                $celda = "DIVISION";
                                break;
                            case 4:
                                $celda = "GRUPO";
                                break;
                            case 6:
                                $celda = "SUBGRUPO";
                                break;
                            case 8:
                                $celda = "PRODUCTO";
                                break;
                            case 10:
                                $celda = "VARIEDAD";
                                break;
                        }
                    }
                    if ($sector > 7 && $sector < 11) {
                        switch ($clasificacion) {
                            case 5:
                                $celda = "CPC Nivel 5";
                                break;
                            case 4:
                                $celda = "CPC Nivel 4";
                                break;
                            case 3:
                                $celda = "CPC Nivel 3";
                                break;
                            case 2:
                                $celda = "CPC Nivel 2";
                                break;
                            case 1:
                                $celda = "CPC Nivel 1";
                                break;
                        }
                    }
                    if ($sector > 10) {
                        switch ($clasificacion) {
                            case 13:
                                $celda =  "CIIU General";
                                break;
                            case 12:
                                $celda =  "CIIU Importado";
                                break;
                            case 11:
                                $celda =  "CIIU Nacional";
                                break;
                        }
                    }
                    $sheet = $objPHPExcel->getActiveSheet();
                    $sheet->setCellValue('A'.$f, "BOLIVIA: ÍNDICE DE PRECIOS AL POR MAYOR,  POR MES SEGÚN ".$celda.", 2014 - 2016");
                    $sheet->getStyle('A'.$f)->getFont()->getColor()->setRGB('8F0000');

                    if ($reporte != null) {
                        $f+= 2;
                        $keys = array_keys($reporte[0]);
                        for ($i = 0; $i < count($reporte); $i++) {
                            $col = 'A';
                            for ($j = 0; $j < count($keys); $j++) {
                                if ($i == 0) {
                                    $sheet->getStyle($col.$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                    $sheet->setCellValue($col.$f, $etiqueta[$keys[$j]]);
                                    $sheet->getStyle($col.$f)->getFont()->getColor()->setRGB('8F0000');
                                    $sheet->getColumndimension($col)->setWidth(20);
                                    $sheet->getStyle($col.$f)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                                    $sheet->getStyle($col.($f + 1))->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                                    if ($j > 0 && $tipo == 1) {
                                        $sheet->setCellValue($col.($f + 1), '=AVERAGE('.$col.($f + 2).':'.$col.($f + 13).')');
                                    }
                                    $col2 = $col;
                                }
                                if ($tipo == 4) {
                                    $sheet->getStyle($col.($i + $f + 2))->getNumberFormat()->setFormatCode('0.0000');
                                } else {
                                    $sheet->getStyle($col.($i + $f + 2))->getNumberFormat()->setFormatCode('0.00');
                                }
                                $sheet->setCellValue($col.($i + $f + 2), $reporte[$i][$keys[$j]]);
                                $sheet->getStyle($col.($i + $f + 2))->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                                $col++;
                            }
                        }
                        $sheet->getStyle('A'.$f.':'.$col2.$f)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                        $sheet->getStyle('A'.$f.':'.$col2.$f)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                        $f+= $i + 1;
                        $sheet->getStyle('A'.$f.':'.$col2.$f)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                        $f++;
                        $sheet->setCellValue('A'.$f, 'Fuente: INSTITUTO NACIONAL DE ESTADÍSTICA');
                        $sheet->getStyle('A'.$f)->getFont()->getColor()->setRGB('8F0000');
                        $f+= 2;

                        $sheet->setTitle($var);

                        if (($sector == 4 || $sector == 5) && $tipo != 4) {
                            $dsl = array(new PHPExcel_Chart_DataSeriesValues('String', "'$var'!B3", NULL, 1), 
                                new PHPExcel_Chart_DataSeriesValues('String', "'$var'!C3", NULL, 1),
                                new PHPExcel_Chart_DataSeriesValues('String', "'$var'!D3", NULL, 1), 
                                new PHPExcel_Chart_DataSeriesValues('String', "'$var'!E3", NULL, 1));

                            $xal = array(new PHPExcel_Chart_DataSeriesValues('String', "'$var'!A5:A".($i + 4), NULL, 90));

                            $dsv = array(new PHPExcel_Chart_DataSeriesValues('Number', "'$var'!B5:B".($i + 4), NULL, 90),
                                new PHPExcel_Chart_DataSeriesValues('Number', "'$var'!C5:C".($i + 4), NULL, 90),
                                new PHPExcel_Chart_DataSeriesValues('Number', "'$var'!D5:D".($i + 4), NULL, 90),
                                new PHPExcel_Chart_DataSeriesValues('Number', "'$var'!E5:E".($i + 4), NULL, 90));

                            $ds = new PHPExcel_Chart_DataSeries(PHPExcel_Chart_DataSeries::TYPE_LINECHART, PHPExcel_Chart_DataSeries::GROUPING_STANDARD, range(0, count($dsv) - 1), $dsl, $xal, $dsv);

                            $pa = new PHPExcel_Chart_PlotArea(NULL, array($ds));
                            $legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);
                            $title = new PHPExcel_Chart_Title($var);

                            $chart = new PHPExcel_Chart('chart1', $title, $legend, $pa, true, 0, new PHPExcel_Chart_Title('Periodo'), new PHPExcel_Chart_Title($var));

                            $chart->setTopLeftPosition('A'.($i + 8));
                            $chart->setBottomRightPosition('F'.($i + 30));
                            $sheet->addChart($chart);
                        }
                    }
                }
                
                $objWorksheet = new PHPExcel_Worksheet($objPHPExcel);
                $objPHPExcel->addSheet($objWorksheet);
                $objPHPExcel->setActiveSheetIndex($z);
                $z++;
            }
            
            $objPHPExcel->removeSheetByIndex($z - 1);

            // Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Indice '.$archivo.' '.$celda.' '.(new DateTime())->format('Ymd').'.xlsx"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            if ($sector >= 4) {
                $objWriter->setIncludeCharts(TRUE);
            }
            $objWriter->save('php://output');
            exit;
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
}