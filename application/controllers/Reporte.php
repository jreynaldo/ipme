<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Reporte
 *
 * @author Alberto Daniel Inch Sáinz
 */
class Reporte extends CI_Controller {
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
    
    public function anio() {
        $gestion = $this->input->post('gestion');
        $anios = $this->Periodo->get_anios($gestion);
        foreach ($anios as $anio) {
            echo '<option value="'.$anio['anio'].'">'.$anio['anio'].'</option>';
        }
    }
    
    public function mes() {
        $gestion = $this->input->post('gestion');
        $meses = $this->Periodo->get_meses($gestion);
        foreach ($meses as $mes) {
            echo '<option value="'.$mes['id'].'">'.$mes['mes'].'</option>';
        }
    }
    
    public function semana() {
        $gestion = $this->input->post('gestion');
        $semanas = $this->Periodo->get_semanas($gestion);
        foreach ($semanas as $semana) {
            echo '<option value="'.$semana['sem'].'">'.$semana['semana'].'</option>';
        }
    }
    
    public function periodo() {
        $tipo = $this->input->post('tipo');
        $gestion = $this->input->post('gestion');
        $periodos = $this->Periodo->get_periodo($tipo, $gestion);
        foreach ($periodos as $periodo) {
            echo '<option value="'.$periodo['id'].'">'.$periodo['periodo'].'</option>';
        }
    }
    
    public function avance_mercados() {
        $sess = $this->session->userdata();
        if (in_array('reporte', $sess['permisos'])) {
            $data['title'] = 'Productos Agricolas';
            $this->load->view('templates/header', $sess);
            $id_usuario = $this->session->userdata('id_usuario');
            $anterior = $this->input->get('anterior');
            if ($anterior == false) {
                $anterior = 0;
            }
            $data['title'] = 'Avance asignación (Agricolas)';
            $periodo = $this->Periodo->get_ultimo_periodo($id_usuario, 1, $anterior);
            $data['periodo'] = $periodo;
            $consolid = $this->Asignacion->get_avance_asignacion_mercados($id_usuario, $periodo);
            $data['chartData'] = $consolid[0];
            $data['etiqueta'] = $consolid[1];
            $this->load->view('reporte/avance_mercados', $data);
            $this->load->view('templates/footer');
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function avance_comercializadoras() {
        $sess = $this->session->userdata();
        if (in_array('reporte', $sess['permisos'])) {
            $data['title'] = 'Productos Agricolas';
            $this->load->view('templates/header', $sess);
            $id_usuario = $this->session->userdata('id_usuario');
            $anterior = $this->input->get('anterior');
            if ($anterior == false) {
                $anterior = 0;
            }
            $data['title'] = 'Avance asignación (Manufacturados)';
            $periodo = $this->Periodo->get_ultimo_periodo($id_usuario, 2, $anterior);
            $data['periodo'] = $periodo;
            $consolid = $this->Asignacion->get_avance_asignacion_comercializadoras($id_usuario, $periodo);
            $data['chartData'] = $consolid[0];
            $data['etiqueta'] = $consolid[1];
            $this->load->view('reporte/avance_comercializadoras', $data);
            $this->load->view('templates/footer');
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function agricolas_avance() {
        $sess = $this->session->userdata();
        if (in_array('reporte', $sess['permisos'])) {
            $data['title'] = 'Productos Agricolas';
            $this->load->view('templates/header', $sess);
            $id_usuario = $this->session->userdata('id_usuario');
            $anterior = $this->input->get('anterior');
            if ($anterior == false) {
                $anterior = 0;
            }
            $data['title'] = 'Avance consolidación (Agricolas)';
            $periodo = $this->Periodo->get_ultimo_periodo($id_usuario, 1, $anterior);
            $data['periodo'] = $periodo;
            $consolid = $this->Asignacion->get_avance_agricolas($id_usuario, $periodo);
            $data['chartData'] = $consolid[0];
            $data['etiqueta'] = $consolid[1];
            $this->load->view('reporte/agricolas_avance', $data);
            $this->load->view('templates/footer');
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function manufacturados_avance() {
        $sess = $this->session->userdata();
        if (in_array('reporte', $sess['permisos'])) {
            $data['title'] = 'Productos Agricolas';
            $this->load->view('templates/header', $sess);
            $id_usuario = $this->session->userdata('id_usuario');
            $anterior = $this->input->get('anterior');
            if ($anterior == false) {
                $anterior = 0;
            }
            $data['title'] = 'Avance consolidación (Manufacturados)';
            $periodo = $this->Periodo->get_ultimo_periodo($id_usuario, 2, $anterior);
            $data['periodo'] = $periodo;
            $consolid = $this->Asignacion->get_avance_manufacturados($id_usuario, $periodo);
            $data['chartData'] = $consolid[0];
            $data['etiqueta'] = $consolid[1];
            $this->load->view('reporte/manufacturados_avance', $data);
            $this->load->view('templates/footer');
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function precios_agricolas() {
        $sess = $this->session->userdata();
        if (in_array('reporte', $sess['permisos'])) {
            $data['title'] = 'Productos Agricolas';
            $this->load->view('templates/header', $sess);
            $data['permisos'] = $sess['permisos'];
            $this->load->view('reporte/precios_agricolas', $data);
            $this->load->view('templates/footer');
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function productos_agricolas() {
        $sess = $this->session->userdata();
        if (in_array('reporte', $sess['permisos'])) {
            $data['productos'] = $this->Producto->get_producto_agricola();
            $this->load->view('reporte/productos', $data);
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    public function precios_manufacturados() {
        $sess = $this->session->userdata();
        if (in_array('reporte', $sess['permisos'])) {
            $data['title'] = 'Productos Manufacturados';
            $this->load->view('templates/header', $sess);
            $data['permisos'] = $sess['permisos'];
            $this->load->view('reporte/precios_manufacturados', $data);
            $this->load->view('templates/footer');
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function encadenado() {
        $sess = $this->session->userdata();
        if (in_array('reporte', $sess['permisos'])) {
            $data['title'] = 'Encadenado';
            $this->load->view('templates/header', $sess);
            $data['permisos'] = $sess['permisos'];
            $this->load->view('reporte/encadenado', $data);
            $this->load->view('templates/footer');
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function productos_manufacturados() {
        $sess = $this->session->userdata();
        if (in_array('reporte', $sess['permisos'])) {
            $data['productos'] = $this->Producto->get_producto_manufacturado();
            $this->load->view('reporte/productos', $data);
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    function monitoreo() {
        $sess = $this->session->userdata();
        if (in_array('reporte', $sess['permisos'])) {
            $data['title'] = 'Monitoreo';
            $this->load->view('templates/header', $sess);
            $data['tipo'] = Array(1 => 'Mercado', 2 => 'Comercializadora');
            $data['departamentos'] = $this->Asignacion->get_departamento($sess['id_usuario']);
            $data['values'] = $this->Usuario->get_values($sess['id_usuario']);
            $this->load->view('reporte/monitoreo', $data);
            $this->load->view('templates/footer');
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function cotizador() {
        $sess = $this->session->userdata();
        if (in_array('reporte', $sess['permisos'])) {
            $depto = $this->input->post('depto');
            $cotizadores = $this->Usuario->get_cotizador($depto);
            foreach ($cotizadores as $cotizador) {
                echo '<option value="'.$cotizador['login'].'">'.$cotizador['login'].'</option>';
            }
        } else {
            echo 'Acceso denegado!';
        }
    }
    
    public function producto() {
        $sess = $this->session->userdata();
        if (in_array('reporte', $sess['permisos'])) {
            $tipo = $this->input->post('tipo');
            $ges = $this->input->post('ges');
            $per = $this->input->post('per');
            $depto = $this->input->post('depto');
            $cot = $this->input->post('cot');
            if ($tipo == 1) {
                $producto = $this->Producto->agricola($ges, $per, $depto, $cot);
            } else {
                $producto = $this->Producto->manufacturado($ges, $per, $depto, $cot);
            }
            echo '<table class="table table-advance table-bordered tbl">';
            echo '    <thead>';
            echo '        <tr>';
            echo '           <th>Fecha</th>';
            echo '           <th>Modificado</th>';
            echo '           <th>Producto</th>';
            echo '           <th>Coordenadas</th>';
            echo '        </tr>';
            echo '    </thead>';
            echo '    <tbody>';
            foreach ($producto AS $p) {
                echo '<tr>';
                    echo '<td>'.$p['feccre'].'</td>';
                    echo '<td>'.$p['fecmod'].'</td>';
                    echo '<td>'.$p['producto'].'</td>';
                    echo '<td><a style="cursor: pointer" onclick="map('.$p['longitud'].','.$p['latitud'].')">'.$p['latitud'].','.$p['longitud'].'</a></td>';
                    echo '</tr>';
            }
            echo '    </tbody>';
            echo '</table>';
        } else {
            return 'Acceso denegado!';
        }
    }
    
    function mayor_incidencia() {
        $sess = $this->session->userdata();
        if (in_array('reporte', $sess['permisos'])) {
            $data['title'] = 'Productos con mayor incidencia';
            $this->load->view('templates/header', $sess);
            $per = str_replace('-', '_', $this->Periodo->get_ultimo_periodo($sess['id_usuario'], 2, 0));
            $data['positiva'] = $this->Indice->get_incidencia2(1, $per);
            $data['negativa'] = $this->Indice->get_incidencia2(2, $per);
            $this->load->view('reporte/mayor_incidencia', $data);
            $this->load->view('templates/footer');
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function catalogo() {
        $sess = $this->session->userdata();
        if (in_array('reporte', $sess['permisos'])) {
            $data['title'] = 'Catalogos';
            $this->load->view('templates/header', $sess);
            $data['departamento'] = $this->Asignacion->get_departamento($sess['id_usuario']);
            $this->load->view('reporte/catalogo', $data);
            $this->load->view('templates/footer');
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function pendientes() {
        $sess = $this->session->userdata();
        if (in_array('informante', $sess['permisos'])) {
            $data['title'] = 'Cambios de especificación pendientes';
            $this->load->view('templates/header', $sess);
            $data['pendientes'] = $this->Producto->cambios_pendientes_nacional($sess['id_usuario']);
            $this->load->view('canasta/pendientes', $data);
            $this->load->view('templates/footer');
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    function calcular() {
        $sess = $this->session->userdata();
        if (in_array('indice', $sess['permisos'])) {
            $data['title'] = 'Imputación';
            $this->load->view('templates/header', $sess);
            $data['permisos'] = $sess['permisos'];
            $data['imputacion'] = $this->Indice->get_variable("imputacion");
            $data['encadenados'] = $this->Indice->get_variable("encadenados");
            $this->load->view('reporte/calcular', $data);
            $this->load->view('templates/footer');
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function promediar() {
        set_time_limit(12000);
        ini_set('MAX_EXECUTION_TIME', 12000);
        
        $sess = $this->session->userdata();
        if (in_array('indice', $sess['permisos'])) {
            echo $this->Indice->promediar();
        } else {
            echo 'Error! Usuario incorrecto.';
        }
    }
    
    public function imputar_agricolas() {
        set_time_limit(12000);
        ini_set('MAX_EXECUTION_TIME', 12000);
        
        $sess = $this->session->userdata();
        if (in_array('indice', $sess['permisos'])) {
            echo $this->Indice->imputar_agricolas();
        } else {
            echo 'Error! Usuario incorrecto.';
        }
    }
    
    public function imputar_agricolas2() {
        set_time_limit(12000);
        ini_set('MAX_EXECUTION_TIME', 12000);
        
        $sess = $this->session->userdata();
        if (in_array('indice', $sess['permisos'])) {
            echo $this->Indice->imputar_agricolas2();
        } else {
            echo 'Error! Usuario incorrecto.';
        }
    }
    
    public function imputar_manufacturados() {
        set_time_limit(12000);
        ini_set('MAX_EXECUTION_TIME', 12000);
        
        $sess = $this->session->userdata();
        if (in_array('indice', $sess['permisos'])) {
            echo $this->Indice->imputar_manufacturados();
        } else {
            echo 'Error! Usuario incorrecto.';
        }
    }
    
    public function imputar_manufacturados2() {
        set_time_limit(12000);
        ini_set('MAX_EXECUTION_TIME', 12000);
        
        $sess = $this->session->userdata();
        if (in_array('indice', $sess['permisos'])) {
            echo $this->Indice->imputar_manufacturados2();
        } else {
            echo 'Error! Usuario incorrecto.';
        }
    }
    
    public function encadenados() {
        set_time_limit(12000);
        ini_set('MAX_EXECUTION_TIME', 12000);
        
        $sess = $this->session->userdata();
        if (in_array('indice', $sess['permisos'])) {
            echo $this->Indice->encadenados();
        } else {
            echo 'Error! Usuario incorrecto.';
        }
    }
    
    public function producto_indice() {
        $sess = $this->session->userdata();
        if (in_array('reporte', $sess['permisos'])) {
            $data['productos'] = $this->Producto->get_producto_indice();
            $this->load->view('reporte/productos', $data);
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    public function varios() {
        $sess = $this->session->userdata();
        if (in_array('indice', $sess['permisos'])) {
            $data['title'] = 'Reporte';
            $this->load->view('templates/header', $sess);
            $data['permisos'] = $sess['permisos'];
            
            $this->load->view('reporte/varios', $data);
            $this->load->view('templates/footer');
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function productos() {
        $sess = $this->session->userdata();
        if (in_array('reporte', $sess['permisos'])) {
            $sector = $this->input->post('sector');
            $clasificacion = $this->input->post('clasificacion');
            $data['productos'] = $this->Producto->get_productos($sector, $clasificacion);
            $this->load->view('reporte/productos', $data);
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    public function aprobar() {
        $sess = $this->session->userdata();
        if (in_array('periodo', $sess['permisos'])) {
            $data['title'] = 'Aprobar Periodo';
            $this->load->view('templates/header', $sess);
            $data['permisos'] = $sess['permisos'];
            $data['aprobado'] = $this->Indice->get_variable("aprobado");
            $this->load->view('reporte/aprobar', $data);
            $this->load->view('templates/footer');
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function guardar() {
        $sess = $this->session->userdata();
        if (in_array('periodo', $sess['permisos'])) {
            $gestion = $this->input->post('gestion');
            $periodo = $this->input->post('periodo');
            if (strlen($periodo) == 1) {
                $periodo = '0'.$periodo;
            }
            echo $this->Indice->set_variable('aprobado', $gestion.'-'.$periodo);
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
            $informacion = $this->input->post('informacion');
            $clasificacion = $this->input->post('clasificacion');
            $codigos = $this->input->post('codigos');
            $periodicidad = $this->input->post('periodicidad');
            $gesini = $this->input->post('gesini');
            $perini = $this->input->post('perini');
            if (strlen($perini) == 1) {
                $perini = '0'.$perini;
            }
            $gesfin = $this->input->post('gesfin');
            $perfin = $this->input->post('perfin');
            if (Strlen($perfin) == 1) {
                $perfin = '0'.$perfin;
            }
            $cuadros = explode(',', $this->input->post('cuadro'));

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

                $objPHPExcel->getActiveSheet()->setCellValue('A1', "PRECIOS");
                $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setRGB('8F0000');
                $objPHPExcel->getActiveSheet()->setCellValue('A2', "(En bolivianos)");
                $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->getColor()->setRGB('8F0000');

                $objPHPExcel->getActiveSheet()->getRowDimension(4)->setRowHeight(-1);
                $col = 'A'; $col2 = ''; $col3 = ''; $gestion = '';
                $keys = array_keys($reporte[0]);
                for ($i = 0; $i < count($reporte); $i++) {
                    $col = 'A';
                    for ($j = 0; $j < count($keys); $j++) {
                        if ($i == 0) {
                            $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getAlignment()->setWrapText(true);
                            $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getFont()->setSize(9);

                            if ($j < 5) {
                                $objPHPExcel->getActiveSheet()->mergeCells($col.'3:'.$col.'4');
                                $objPHPExcel->getActiveSheet()->setCellValue($col.'3', strtoupper($keys[$j]));
                            } else {
                                $label = explode('_', $keys[$j]);
                                if ($gestion <> $label[0]) {
                                    $gestion = $label[0];
                                    $objPHPExcel->getActiveSheet()->getStyle($col.'3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                    $objPHPExcel->getActiveSheet()->setCellValue($col.'3', $gestion);
                                    //$objPHPExcel->getActiveSheet()->getCell($col.'3')->setValueExplicit($gestion, PHPExcel_Cell_DataType::TYPE_STRING);
                                    if ($col3 <> '') {
                                        $objPHPExcel->getActiveSheet()->mergeCells($col3.'3:'.$col2.'3');
                                        $objPHPExcel->getActiveSheet()->getStyle($col2.'3')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                                    }
                                    $col3 = $col;
                                }
                                $objPHPExcel->getActiveSheet()->setCellValue($col.'4', $label[1]);
                                $objPHPExcel->getActiveSheet()->getStyle($col2.'4')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                                $col2 = $col;
                            }
                            $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getFont()->setBold(true);
                            $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            switch ($j) {
                                case 0:
                                    $objPHPExcel->getActiveSheet()->getStyle('A3:A4')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                                    $objPHPExcel->getActiveSheet()->getStyle('A3:A4')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                                    $objPHPExcel->getActiveSheet()->getColumndimension($col)->setWidth(15);
                                    break;
                                case 1:
                                    $objPHPExcel->getActiveSheet()->getStyle('B3:B4')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                                    $objPHPExcel->getActiveSheet()->getColumndimension($col)->setWidth(12);
                                    break;
                                case 2:
                                    $objPHPExcel->getActiveSheet()->getStyle('C3:C4')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                                    $objPHPExcel->getActiveSheet()->getColumndimension($col)->setWidth(12);
                                    break;
                                case 3:
                                    $objPHPExcel->getActiveSheet()->getStyle('D3:D4')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                                    $objPHPExcel->getActiveSheet()->getColumndimension($col)->setWidth(26);
                                    break;
                                case 4:
                                    $objPHPExcel->getActiveSheet()->getStyle('E3:E4')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                                    $objPHPExcel->getActiveSheet()->getColumndimension($col)->setWidth(15);
                                    break;
                                default:
                                    $objPHPExcel->getActiveSheet()->getColumndimension($col)->setWidth(10);
                                    $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('0.00');
                                    break;
                            }
                        }
                        if ($j == 0) {
                            $objPHPExcel->getActiveSheet()->getStyle($col.($i + 5))->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                        }
                        $objPHPExcel->getActiveSheet()->getStyle($col.($i + 5))->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                        $objPHPExcel->getActiveSheet()->getStyle($col.($i + 5))->getNumberFormat()->setFormatCode('0.00');
                        if ($j == 2) {
                            $objPHPExcel->getActiveSheet()->getCell($col.($i + 5))->setValueExplicit($reporte[$i][$keys[$j]], PHPExcel_Cell_DataType::TYPE_STRING);
                        } else {
                            $objPHPExcel->getActiveSheet()->setCellValue($col.($i + 5), $reporte[$i][$keys[$j]]);
                        }
                        $col++;
                    }
                }
                $objPHPExcel->getActiveSheet()->mergeCells($col3.'3:'.$col2.'3');
                $objPHPExcel->getActiveSheet()->getStyle($col2.'3')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $objPHPExcel->getActiveSheet()->getStyle('A3:'.$col2.'3')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $objPHPExcel->getActiveSheet()->getStyle('F4:'.$col2.'4')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $objPHPExcel->getActiveSheet()->getStyle('A5:'.$col2.'5')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $objPHPExcel->getActiveSheet()->getStyle('A'.($i + 4).':'.$col2.($i + 4))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

                $objPHPExcel->getActiveSheet()->setTitle("Por ciudad");
            }
            
            if (in_array(2, $cuadros)) {
                $reporte = $this->Indice->get_imputado_nac($sector, $informacion, $clasificacion, $codigos, $periodicidad, $gesini.'-'.$perini, $gesfin.'-'.$perfin);
                
                if (count($cuadros) > 1) {
                    $objWorksheet = new PHPExcel_Worksheet($objPHPExcel);
                    $objPHPExcel->addSheet($objWorksheet);
                    $objPHPExcel->setActiveSheetIndex(1);
                }
                $objPHPExcel->getActiveSheet()->setCellValue('A1', "PRECIOS");
                $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setRGB('8F0000');
                $objPHPExcel->getActiveSheet()->setCellValue('A2', "(En bolivianos)");
                $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->getColor()->setRGB('8F0000');

                $objPHPExcel->getActiveSheet()->getRowDimension(4)->setRowHeight(-1);
                $col = 'A'; $col2 = ''; $col3 = ''; $gestion = '';
                $keys = array_keys($reporte[0]);
                for ($i = 0; $i < count($reporte); $i++) {
                    $col = 'A';
                    for ($j = 0; $j < count($keys); $j++) {
                        if ($i == 0) {
                            $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getAlignment()->setWrapText(true);
                            $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getFont()->setSize(9);

                            if ($j < 4) {
                                $objPHPExcel->getActiveSheet()->mergeCells($col.'3:'.$col.'4');
                                $objPHPExcel->getActiveSheet()->setCellValue($col.'3', strtoupper($keys[$j]));
                            } else {
                                $label = explode('_', $keys[$j]);
                                if ($gestion <> $label[0]) {
                                    $gestion = $label[0];
                                    $objPHPExcel->getActiveSheet()->getStyle($col.'3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                    $objPHPExcel->getActiveSheet()->setCellValue($col.'3', $gestion);
                                    //$objPHPExcel->getActiveSheet()->getCell($col.'3')->setValueExplicit($gestion, PHPExcel_Cell_DataType::TYPE_STRING);
                                    if ($col3 <> '') {
                                        $objPHPExcel->getActiveSheet()->mergeCells($col3.'3:'.$col2.'3');
                                        $objPHPExcel->getActiveSheet()->getStyle($col2.'3')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                                    }
                                    $col3 = $col;
                                }
                                $objPHPExcel->getActiveSheet()->setCellValue($col.'4', $label[1]);
                                $objPHPExcel->getActiveSheet()->getStyle($col2.'4')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                                $col2 = $col;
                            }
                            $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getFont()->setBold(true);
                            $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            switch ($j) {
                                case 0:
                                    $objPHPExcel->getActiveSheet()->getStyle('B3:B4')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                                    $objPHPExcel->getActiveSheet()->getColumndimension($col)->setWidth(12);
                                    break;
                                case 1:
                                    $objPHPExcel->getActiveSheet()->getStyle('C3:C4')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                                    $objPHPExcel->getActiveSheet()->getColumndimension($col)->setWidth(12);
                                    break;
                                case 2:
                                    $objPHPExcel->getActiveSheet()->getStyle('D3:D4')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                                    $objPHPExcel->getActiveSheet()->getColumndimension($col)->setWidth(26);
                                    break;
                                case 3:
                                    $objPHPExcel->getActiveSheet()->getStyle('E3:E4')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                                    $objPHPExcel->getActiveSheet()->getColumndimension($col)->setWidth(15);
                                    break;
                                default:
                                    $objPHPExcel->getActiveSheet()->getColumndimension($col)->setWidth(10);
                                    $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('0.00');
                                    break;
                            }
                        }
                        if ($j == 0) {
                            $objPHPExcel->getActiveSheet()->getStyle($col.($i + 5))->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                        }
                        $objPHPExcel->getActiveSheet()->getStyle($col.($i + 5))->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                        $objPHPExcel->getActiveSheet()->getStyle($col.($i + 5))->getNumberFormat()->setFormatCode('0.00');
                        if ($j == 1) {
                            $objPHPExcel->getActiveSheet()->getCell($col.($i + 5))->setValueExplicit($reporte[$i][$keys[$j]], PHPExcel_Cell_DataType::TYPE_STRING);
                        } else {
                            $objPHPExcel->getActiveSheet()->setCellValue($col.($i + 5), $reporte[$i][$keys[$j]]);
                        }
                        $col++;
                    }
                }
                $objPHPExcel->getActiveSheet()->mergeCells($col3.'3:'.$col2.'3');
                $objPHPExcel->getActiveSheet()->getStyle($col2.'3')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $objPHPExcel->getActiveSheet()->getStyle('A3:'.$col2.'3')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $objPHPExcel->getActiveSheet()->getStyle('E4:'.$col2.'4')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $objPHPExcel->getActiveSheet()->getStyle('A5:'.$col2.'5')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $objPHPExcel->getActiveSheet()->getStyle('A'.($i + 4).':'.$col2.($i + 4))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

                $objPHPExcel->getActiveSheet()->setTitle("Nacional");
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
            $clasificaciones = explode(',', $this->input->post('clasificacion'));
            $gesini = $this->input->post('gesini');
            $perini = $this->input->post('perini');
            if (strlen($perini) == 1) {
                $perini = '0'.$perini;
            }
            $gesfin = $this->input->post('gesfin');
            $perfin = $this->input->post('perfin');
            if (strlen($perfin) == 1) {
                $perfin = '0'.$perfin;
            }
            $cuadros = explode(',', $this->input->post('cuadro'));
            
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
                    $objPHPExcel->getActiveSheet()->setCellValue('A'.$f, "BOLIVIA: ÍNDICE DE PRECIOS AL POR MAYOR,  POR MES SEGÚN ".$celda.", 2014 - 2016");
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$f)->getFont()->getColor()->setRGB('8F0000');

                    if ($reporte != null) {
                        $f+= 2;
                        $keys = array_keys($reporte[0]);
                        for ($i = 0; $i < count($reporte); $i++) {
                            $col = 'A';
                            for ($j = 0; $j < count($keys); $j++) {
                                if ($i == 0) {
                                    $objPHPExcel->getActiveSheet()->getStyle($col.$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                    $objPHPExcel->getActiveSheet()->setCellValue($col.$f, $etiqueta[$keys[$j]]);
                                    $objPHPExcel->getActiveSheet()->getStyle($col.$f)->getFont()->getColor()->setRGB('8F0000');
                                    $objPHPExcel->getActiveSheet()->getColumndimension($col)->setWidth(20);
                                    $objPHPExcel->getActiveSheet()->getStyle($col.$f)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                                    $objPHPExcel->getActiveSheet()->getStyle($col.($f + 1))->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                                    if ($j > 0 && $tipo == 1) {
                                        $objPHPExcel->getActiveSheet()->setCellValue($col.($f + 1), '=AVERAGE('.$col.($f + 2).':'.$col.($f + 13).')');
                                    }
                                    $col2 = $col;
                                }
                                if ($tipo == 4) {
                                    $objPHPExcel->getActiveSheet()->getStyle($col.($i + $f + 2))->getNumberFormat()->setFormatCode('0.0000');
                                } else {
                                    $objPHPExcel->getActiveSheet()->getStyle($col.($i + $f + 2))->getNumberFormat()->setFormatCode('0.00');
                                }
                                $objPHPExcel->getActiveSheet()->setCellValue($col.($i + $f + 2), $reporte[$i][$keys[$j]]);
                                $objPHPExcel->getActiveSheet()->getStyle($col.($i + $f + 2))->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                                $col++;
                            }
                        }
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$f.':'.$col2.$f)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$f.':'.$col2.$f)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                        $f+= $i + 1;
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$f.':'.$col2.$f)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                        $f++;
                        $objPHPExcel->getActiveSheet()->setCellValue('A'.$f, 'Fuente: INSTITUTO NACIONAL DE ESTADÍSTICA');
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$f)->getFont()->getColor()->setRGB('8F0000');
                        $f+= 2;

                        $objPHPExcel->getActiveSheet()->setTitle($var);

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
                            $objPHPExcel->getActiveSheet()->addChart($chart);
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
    
    public function incidencia() {
        $sess = $this->session->userdata();
        if (in_array('indice', $sess['permisos'])) {
            $ges = $this->input->post('ges');
            $per = $this->input->post('per');
            if (strlen($per) == 1) {
                $per = '0'.$per;
            }
            $cuadros = explode(',', $this->input->post('cuadro'));
            
            $objPHPExcel = new PHPExcel();
            
            $objPHPExcel->setActiveSheetIndex(0);
            
            $z = 1;
            foreach ($cuadros AS $tipo) {
                $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setRGB('8F0000');
                switch ($tipo) {
                    case 1:
                        $var = 'Incidencia positiva';
                        break;
                    case 2:
                        $var = 'Incidencia negativa';
                        break;
                }
                $objPHPExcel->getActiveSheet()->setCellValue('A1', $var);
                $reporte = $this->Indice->get_incidencia2($tipo, $ges.'_'.$per);
                $keys = array_keys($reporte[0]);
                $f = 3;
                for ($i = 0; $i < count($reporte); $i++) {
                    $col = 'A';
                    for ($j = 0; $j < count($keys); $j++) {
                        if ($i == 0) {
                            $objPHPExcel->getActiveSheet()->getStyle($col.$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            $objPHPExcel->getActiveSheet()->setCellValue($col.$f, $keys[$j]);
                            $objPHPExcel->getActiveSheet()->getStyle($col.$f)->getFont()->getColor()->setRGB('8F0000');
                            $objPHPExcel->getActiveSheet()->getColumndimension($col)->setWidth(20);
                            $objPHPExcel->getActiveSheet()->getStyle($col.$f)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                            $objPHPExcel->getActiveSheet()->getStyle($col.($f + 1))->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                            $col2 = $col;
                        }
                        //$objPHPExcel->getActiveSheet()->getStyle($col.($i + $f + 1))->getNumberFormat()->setFormatCode('0.00');
                        $objPHPExcel->getActiveSheet()->setCellValue($col.($i + $f + 1), $reporte[$i][$keys[$j]]);
                        $objPHPExcel->getActiveSheet()->getStyle($col.($i + $f + 1))->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                        $col++;
                    }
                }
                $objPHPExcel->getActiveSheet()->getStyle('A'.$f.':'.$col2.$f)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$f.':'.$col2.$f)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $f+= $i;
                $objPHPExcel->getActiveSheet()->getStyle('A'.$f.':'.$col2.$f)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $f++;
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$f, 'Fuente: INSTITUTO NACIONAL DE ESTADÍSTICA');
                $objPHPExcel->getActiveSheet()->getStyle('A'.$f)->getFont()->getColor()->setRGB('8F0000');
                $f+= 2;

                $objPHPExcel->getActiveSheet()->setTitle($var);

                $objWorksheet = new PHPExcel_Worksheet($objPHPExcel);
                $objPHPExcel->addSheet($objWorksheet);
                $objPHPExcel->setActiveSheetIndex($z);
                $z++;
            }
            
            $objPHPExcel->removeSheetByIndex($z - 1);

            // Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Incidencia.xlsx"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function semanal() {
        $sess = $this->session->userdata();
        if (in_array('indice', $sess['permisos'])) {
            $data['title'] = 'Reporte Semanal';
            $this->load->view('templates/header', $sess);
            $data['permisos'] = $sess['permisos'];
            $this->load->view('reporte/semanal', $data);
            $this->load->view('templates/footer');
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function semanal_rep() {
        set_time_limit(12000);
        ini_set('MAX_EXECUTION_TIME', 12000);
        
        $sess = $this->session->userdata();
        if (in_array('indice', $sess['permisos'])) {
            $id_usuario = $sess['id_usuario'];
            $nacional = $sess['nacional'];

            $cod = $this->input->post('cod');
            $gesini = $this->input->post('gesini');
            $semini = $this->input->post('semini');
            $gesfin = $this->input->post('gesfin');
            $semfin = $this->input->post('semfin');

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle("Precios Productos Agricolas")->setDescription("Precios Productos Agricolas");

            // Assign cell values
            if ($nacional == 't') {
                $reporte = $this->Indice->get_reporte_productos_agricolas_mercados($cod, $gesini, $semini, $gesfin, $semfin);
            } else {
                $reporte = $this->Indice->get_reporte_productos_agricolas_mercados($cod, $gesini, $semini, $gesfin, $semfin, $sess['id_departamento']);
            }

            $objWorksheet = new PHPExcel_Worksheet($objPHPExcel);
            $objPHPExcel->addSheet($objWorksheet);
            $objPHPExcel->setActiveSheetIndex(1);

            $objPHPExcel->getActiveSheet()->setCellValue('A1', "BOLIVIA: PRECIOS PROMEDIOS POR MERCADOS DE PRODUCTOS AGRÍCOLAS");
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setRGB('8F0000');
            $objPHPExcel->getActiveSheet()->setCellValue('A2', "(En bolivianos)");
            $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->getColor()->setRGB('8F0000');

            $objPHPExcel->getActiveSheet()->getRowDimension(4)->setRowHeight(-1);
            $col = 'A';
            $keys = array_keys($reporte[0]);
            for ($i = 0; $i < count($reporte); $i++) {
                $col = 'A'; $mes = ''; $suma = 0; $cont = 0; $prod = 1;
                for ($j = 0; $j < count($keys) - 1; $j++) {
                    if ($j > 5) {
                        if ($mes != explode(" ", $keys[$j])[1]) {
                            if ($mes != '') {
                                if ($i == 0) {
                                    $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getFont()->getColor()->setRGB('00008F');
                                    $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getAlignment()->setWrapText(true);
                                    $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getFont()->setSize(9);
                                    $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getFont()->setBold(true);
                                    $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                    $objPHPExcel->getActiveSheet()->setCellValue($col.'4', 'PROMEDIO '.$mes);
                                    $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('0.00');
                                }
                                if ($cont > 0) {
                                    $objPHPExcel->getActiveSheet()->setCellValue($col.($i + 5), pow($prod, 1/$cont));
                                }
                                $col++;
                                $suma = 0;
                                $cont = 0;
                                $prod = 1;
                            }
                            $mes = explode(" ", $keys[$j])[1];
                        }
                        if ($reporte[$i][$keys[$j]] != NULL) {
                            $suma = $suma + $reporte[$i][$keys[$j]];
                            $cont = $cont + 1;
                            $prod = $prod * $reporte[$i][$keys[$j]];
                        }
                    }
                    if ($i == 0) {
                        $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getAlignment()->setWrapText(true);
                        $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getFont()->setSize(9);
                        $objPHPExcel->getActiveSheet()->setCellValue($col.'4', strtoupper($keys[$j]));
                        $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getFont()->setBold(true);
                        $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        switch ($j) {
                            case 0:
                                $objPHPExcel->getActiveSheet()->getColumndimension($col)->setWidth(10);
                                break;
                            case 1:
                                $objPHPExcel->getActiveSheet()->getColumndimension($col)->setWidth(16);
                                break;
                            case 2:
                                $objPHPExcel->getActiveSheet()->getColumndimension($col)->setWidth(26);
                                break;
                            case 3:
                                $objPHPExcel->getActiveSheet()->getColumndimension($col)->setWidth(15);
                                break;
                            case 4:
                                $objPHPExcel->getActiveSheet()->getColumndimension($col)->setWidth(40);
                                break;
                            case 5:
                                $objPHPExcel->getActiveSheet()->getColumndimension($col)->setWidth(10);
                                break;
                            default:
                                $objPHPExcel->getActiveSheet()->getColumndimension($col)->setWidth(10);
                                $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('0.00');
                                break;
                        }
                    }
                    if ($j == 0) {
                        $objPHPExcel->getActiveSheet()->getCell($col.($i + 5))->setValueExplicit($reporte[$i][$keys[$j]], PHPExcel_Cell_DataType::TYPE_STRING);
                    } else {
                        $objPHPExcel->getActiveSheet()->setCellValue($col.($i + 5), $reporte[$i][$keys[$j]]);
                    }
                    $col++;
                }
                if ($mes != '') {
                    if ($i == 0) {
                        $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getFont()->getColor()->setRGB('00008F');
                        $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getAlignment()->setWrapText(true);
                        $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getFont()->setSize(9);
                        $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getFont()->setBold(true);
                        $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->setCellValue($col.'4', 'PROMEDIO '.$mes);
                        $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('0.00');
                    }
                    if ($cont > 0) {
                        $objPHPExcel->getActiveSheet()->setCellValue($col.($i + 5), pow($prod, 1/$cont));
                    }
                }
            }

            $objPHPExcel->getActiveSheet()->setTitle("Mercados");

            if ($nacional == 't') {
                $reporteDept = $this->Indice->get_reporte_productos_agricolas_departamental($cod, $gesini, $semini, $gesfin, $semfin);
            } else {
                $reporteDept = $this->Indice->get_reporte_productos_agricolas_departamental($cod, $gesini, $semini, $gesfin, $semfin, $sess['id_departamento']);
            }
            // Assign cell values
            $objWorksheet = new PHPExcel_Worksheet($objPHPExcel);
            $objPHPExcel->addSheet($objWorksheet);
            $objPHPExcel->setActiveSheetIndex(2);

            $objPHPExcel->getActiveSheet()->setCellValue('A1', "BOLIVIA: PRECIOS PROMEDIOS POR DEPARTAMENTOS DE PRODUCTOS AGRÍCOLAS");
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setRGB('8F0000');
            $objPHPExcel->getActiveSheet()->setCellValue('A2', "(En bolivianos)");
            $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->getColor()->setRGB('8F0000');

            $objPHPExcel->getActiveSheet()->getRowDimension(4)->setRowHeight(-1);
            $col = 'A';
            $keys = array_keys($reporteDept[0]);
            for ($i = 0; $i < count($reporteDept); $i++) {
                $col = 'A'; $mes = ''; $suma = 0; $cont = 0; $prod = 1;
                for ($j = 0; $j < count($keys) - 1; $j++) {
                    if ($j > 4) {
                        if ($mes != explode(" ", $keys[$j])[1]) {
                            if ($mes != '') {
                                if ($i == 0) {
                                    $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getFont()->getColor()->setRGB('00008F');
                                    $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getAlignment()->setWrapText(true);
                                    $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getFont()->setSize(9);
                                    $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getFont()->setBold(true);
                                    $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                    $objPHPExcel->getActiveSheet()->setCellValue($col.'4', 'PROMEDIO '.$mes);
                                    $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('0.00');
                                }
                                if ($cont > 0) {
                                    $objPHPExcel->getActiveSheet()->setCellValue($col.($i + 5), pow($prod, 1/$cont));
                                }
                                $col++;
                                $suma = 0;
                                $cont = 0;
                                $prod = 1;
                            }
                            $mes = explode(" ", $keys[$j])[1];
                        }
                        if ($reporteDept[$i][$keys[$j]] != NULL) {
                            $suma = $suma + $reporteDept[$i][$keys[$j]];
                            $cont = $cont + 1;
                            $prod = $prod * $reporteDept[$i][$keys[$j]];
                        }
                    }
                    if ($i == 0) {
                        $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getAlignment()->setWrapText(true);
                        $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getFont()->setSize(9);
                        $objPHPExcel->getActiveSheet()->setCellValue($col.'4', strtoupper($keys[$j]));
                        $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getFont()->setBold(true);
                        $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        switch ($j) {
                            case 0:
                                $objPHPExcel->getActiveSheet()->getColumndimension($col)->setWidth(10);
                                break;
                            case 1:
                                $objPHPExcel->getActiveSheet()->getColumndimension($col)->setWidth(20);
                                break;
                            case 2:
                                $objPHPExcel->getActiveSheet()->getColumndimension($col)->setWidth(15);
                                break;
                            case 3:
                                $objPHPExcel->getActiveSheet()->getColumndimension($col)->setWidth(40);
                                break;
                            case 4:
                                $objPHPExcel->getActiveSheet()->getColumndimension($col)->setWidth(10);
                                break;
                            default:
                                $objPHPExcel->getActiveSheet()->getColumndimension($col)->setWidth(10);
                                $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('0.00');
                                break;
                        }
                    }
                    if ($j == 0) {
                        $objPHPExcel->getActiveSheet()->getCell($col.($i + 5))->setValueExplicit($reporteDept[$i][$keys[$j]], PHPExcel_Cell_DataType::TYPE_STRING);
                    } else {
                        $objPHPExcel->getActiveSheet()->setCellValue($col.($i + 5), $reporteDept[$i][$keys[$j]]);
                    }
                    $col++;
                }
                if ($mes != '') {
                    if ($i == 0) {
                        $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getFont()->getColor()->setRGB('00008F');
                        $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getAlignment()->setWrapText(true);
                        $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getFont()->setSize(9);
                        $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getFont()->setBold(true);
                        $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->setCellValue($col.'4', 'PROMEDIO '.$mes);
                        $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('0.00');
                    }
                    if ($cont > 0) {
                        $objPHPExcel->getActiveSheet()->setCellValue($col.($i + 5), pow($prod, 1/$cont));
                    }
                }
            }

            $objPHPExcel->getActiveSheet()->setTitle("Dptal");

            if ($nacional == 't') {
                $reporteNac = $this->Indice->get_reporte_productos_agricolas_nacional($cod, $gesini, $semini, $gesfin, $semfin);
                // Assign cell values
                $objWorksheet = new PHPExcel_Worksheet($objPHPExcel);
                $objPHPExcel->addSheet($objWorksheet);
                $objPHPExcel->setActiveSheetIndex(3);

                $objPHPExcel->getActiveSheet()->setCellValue('A1', "BOLIVIA: PRECIOS PROMEDIOS DE PRODUCTOS AGRÍCOLAS");
                $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setRGB('8F0000');
                $objPHPExcel->getActiveSheet()->setCellValue('A2', "(En bolivianos)");
                $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->getColor()->setRGB('8F0000');

                $objPHPExcel->getActiveSheet()->getRowDimension(4)->setRowHeight(-1);
                $col = 'A';
                $keys = array_keys($reporteNac[0]);
                for ($i = 0; $i < count($reporteNac); $i++) {
                    $col = 'A'; $mes = ''; $suma = 0; $cont = 0; $prod = 1;
                    for ($j = 0; $j < count($keys); $j++) {
                        if ($j > 3) {
                            if ($mes != explode(" ", $keys[$j])[1]) {
                                if ($mes != '') {
                                    if ($i == 0) {
                                        $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getFont()->getColor()->setRGB('00008F');
                                        $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getAlignment()->setWrapText(true);
                                        $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getFont()->setSize(9);
                                        $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getFont()->setBold(true);
                                        $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                        $objPHPExcel->getActiveSheet()->setCellValue($col.'4', 'PROMEDIO '.$mes);
                                        $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('0.00');
                                    }
                                    if ($cont > 0) {
                                        $objPHPExcel->getActiveSheet()->setCellValue($col.($i + 5), pow($prod, 1/$cont));
                                    }
                                    $col++;
                                    $suma = 0;
                                    $cont = 0;
                                    $prod = 1;
                                }
                                $mes = explode(" ", $keys[$j])[1];
                            }
                            if ($reporteNac[$i][$keys[$j]] != NULL) {
                                $suma = $suma + $reporteNac[$i][$keys[$j]];
                                $cont = $cont + 1;
                                $prod = $prod * $reporteNac[$i][$keys[$j]];
                            }
                        }
                        if ($i == 0) {
                            $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getAlignment()->setWrapText(true);
                            $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getFont()->setSize(9);
                            $objPHPExcel->getActiveSheet()->setCellValue($col.'4', strtoupper($keys[$j]));
                            $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getFont()->setBold(true);
                            $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            switch ($j) {
                                case 0:
                                    $objPHPExcel->getActiveSheet()->getColumndimension($col)->setWidth(10);
                                    break;
                                case 1:
                                    $objPHPExcel->getActiveSheet()->getColumndimension($col)->setWidth(15);
                                    break;
                                case 2:
                                    $objPHPExcel->getActiveSheet()->getColumndimension($col)->setWidth(40);
                                    break;
                                case 3:
                                    $objPHPExcel->getActiveSheet()->getColumndimension($col)->setWidth(10);
                                    break;
                                default:
                                    $objPHPExcel->getActiveSheet()->getColumndimension($col)->setWidth(10);
                                    $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('0.00');
                                    break;
                            }
                        }
                        if ($j == 0) {
                            $objPHPExcel->getActiveSheet()->getCell($col.($i + 5))->setValueExplicit($reporteNac[$i][$keys[$j]], PHPExcel_Cell_DataType::TYPE_STRING);
                        } else {
                            $objPHPExcel->getActiveSheet()->setCellValue($col.($i + 5), $reporteNac[$i][$keys[$j]]);
                        }
                        $col++;
                    }
                    if ($mes != '') {
                        if ($i == 0) {
                            $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getFont()->getColor()->setRGB('00008F');
                            $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getAlignment()->setWrapText(true);
                            $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getFont()->setSize(9);
                            $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getFont()->setBold(true);
                            $objPHPExcel->getActiveSheet()->getStyle($col.'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            $objPHPExcel->getActiveSheet()->setCellValue($col.'4', 'PROMEDIO '.$mes);
                            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('0.00');
                        }
                        if ($cont > 0) {
                            $objPHPExcel->getActiveSheet()->setCellValue($col.($i + 5), pow($prod, 1/$cont));
                        }
                    }
                }

                $objPHPExcel->getActiveSheet()->setTitle("Nacional");
            }

            // Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Precios imputados agricolas.xlsx"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
}