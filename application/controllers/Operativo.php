<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Canasta
 *
 * @author Alberto Daniel Inch Sáinz
 */
class Operativo extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('session');
        $this->load->library('Word');
        $this->load->model('Fotografia');
        $this->load->model('Asignacion');
        $this->load->model('Precio');
        $this->load->model('Informante');
        if (count($this->session->userdata()) == 1) {
            $this->session->set_userdata(Array('activo' => false, 'permisos' => Array()));
        }
    }
    
    public function asignar_mercados() {
        $sess = $this->session->userdata();
        if (in_array('operativo', $sess['permisos'])) {
            $data['title'] = 'Asignación de Mercados';
            $this->load->view('templates/header', $sess);
            $data['departamentos'] = $this->Asignacion->get_departamento($sess['id_usuario']);
            $data['values'] = $this->Asignacion->get_values($sess['id_usuario']);
            $depto = $this->input->get('dep');
            if ($depto) {
                $data['values']['id_departamento'] = $depto;
            } else {
                $depto = $data['values']['id_departamento'];
            }
            $data['cotizadores'] = $this->Asignacion->get_cotizador($depto);
            $data['mercados'] = $this->Asignacion->get_mercado($depto);
            $data['asignacion'] = $this->Asignacion->get_asignacion_mercados($depto);
            $this->load->view('operativo/asignar_mercados', $data);
            $this->load->view('templates/footer');
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function guardar_mercados() {
        $sess = $this->session->userdata();
        if (in_array('operativo', $sess['permisos'])) {
            $cot = $this->input->post('cot');
            $upms = $this->input->post('upms');
            echo $this->Asignacion->guardar_mercados($cot, $upms, $sess['login']);
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    public function asignar_comercializadoras() {
        $sess = $this->session->userdata();
        if (in_array('operativo', $sess['permisos'])) {
            $data['title'] = 'Asignacion de Comercializadoras';
            $this->load->view('templates/header', $sess);
            $data['departamentos'] = $this->Asignacion->get_departamento($sess['id_usuario']);
            $data['values'] = $this->Asignacion->get_values($sess['id_usuario']);
            $depto = $this->input->get('dep');
            if ($depto) {
                $data['values']['id_departamento'] = $depto;
            } else {
                $depto = $data['values']['id_departamento'];
            }
            $data['cotizadores'] = $this->Asignacion->get_cotizador($depto);
            $data['comercializadoras'] = $this->Asignacion->get_comercializadora($depto);
            $data['asignacion'] = $this->Asignacion->get_asignacion_comercializadoras($depto);
            $this->load->view('operativo/asignar_comercializadoras', $data);
            $this->load->view('templates/footer');
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function guardar_comercializadoras() {
        $sess = $this->session->userdata();
        if (in_array('operativo', $sess['permisos'])) {
            $cot = $this->input->post('cot');
            $upms = $this->input->post('upms');
            echo $this->Asignacion->guardar_comercializadoras($cot, $upms, $sess['login']);
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    public function recorrido() {
        $sess = $this->session->userdata();
        if (in_array('operativo', $sess['permisos'])) {
            $data['title'] = 'Recorrido Mercados/Comercializadoras';
            $this->load->view('templates/header', $sess);
            $data['departamentos'] = $this->Asignacion->get_departamento($sess['id_usuario']);
            $data['values'] = $this->Asignacion->get_values($sess['id_usuario']);
            $depto = $this->input->get('dep');
            if ($depto) {
                $data['values']['id_departamento'] = $depto;
            } else {
                $depto = $data['values']['id_departamento'];
            }
            $data['tipo'] = Array(1 => 'Mercado', 2 => 'Comercializadora');
            $tipo = $this->input->get('tipo');
            if ($tipo) {
                $data['values']['id_tipo'] = $tipo;
            } else {
                $data['values']['id_tipo'] = 1;
                $tipo = 1;
            }
            $data['carga'] = $this->Asignacion->get_carga($depto, $tipo);
            $carga = $this->input->get('carga');
            if ($carga) {
                $data['values']['id_carga'] = $carga;
            } else {
                if (count($data['carga']) > 0) {
                    $data['values']['id_carga'] = $data['carga'][key($data['carga'])];
                    $carga = $data['values']['id_carga'];
                } else {
                    $data['values']['id_carga'] = null;
                    $carga = null;
                }
            }
            if ($tipo == 1) {
                $data['upms'] = $this->Asignacion->get_mercados_ord($depto, $carga);
            } else {
                $data['upms'] = $this->Asignacion->get_comercializadoras_ord($depto, $carga);
            }
            $this->load->view('operativo/recorrido', $data);
            $this->load->view('templates/footer');
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function subir() {
        $sess = $this->session->userdata();
        if (in_array('operativo', $sess['permisos'])) {
            $id = $this->input->post('id');
            echo $this->Asignacion->subir($id);
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    public function bajar() {
        $sess = $this->session->userdata();
        if (in_array('operativo', $sess['permisos'])) {
            $id = $this->input->post('id');
            echo $this->Asignacion->bajar($id);
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    public function revision() {
        $sess = $this->session->userdata();
        if (in_array('revision', $sess['permisos'])) {
            $data['title'] = 'Cambio de precio y código';
            $this->load->view('templates/header', $sess);
            $this->load->view('operativo/revision', $data);
            $this->load->view('templates/footer');
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function anio() {
        $sess = $this->session->userdata();
        if (in_array('operativo', $sess['permisos'])) {
            $gestion = $this->input->post('gestion');
            $anios = $this->Asignacion->get_anios($gestion);
            foreach ($anios as $anio) {
                echo '<option value="'.$anio['anio'].'">'.$anio['anio'].'</option>';
            }
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    public function periodo() {
        $sess = $this->session->userdata();
        if (in_array('operativo', $sess['permisos'])) {
            $tipo = $this->input->post('tipo');
            $gestion = $this->input->post('gestion');
            $periodos = $this->Asignacion->get_periodo($tipo, $gestion);
            foreach ($periodos as $periodo) {
                echo '<option value="'.$periodo['id'].'">'.$periodo['periodo'].'</option>';
            }
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    public function precio() {
        $sess = $this->session->userdata();
        if (in_array('revision', $sess['permisos'])) {
            $id_tipo = $this->input->get('id_tipo');
            $id = $this->input->get('id');
            $gestion = $this->input->get('gestion');
            $periodo = $this->input->get('periodo');
            $precios = $this->Precio->get_precios($id_tipo, $id, $gestion, $periodo);
            $fila = 0;
            echo '<table>';
            for ($i = 0; $i < count($precios); $i++) {
                if ($precios[$i]['fila'] != $fila) {
                    echo '<tr>';
                        echo '<td colspan="2">'.$precios[$i]['nro'].'</td>';
                    echo '</tr>';
                    $fila = $precios[$i]['fila'];
                }
                echo '<tr>';
                if ($precios[$i]['id_pregunta'] == 6 || $precios[$i]['id_pregunta'] == 21) {
                    echo '<td>Precio:</td>';
                    echo '<td><input id="resp'.($i + 1).'" type="text" value="'.$precios[$i]['respuesta'].'"/>';
                    echo '<button class="btn btn-primary" type="button" onclick="guardar(this, '.$precios[$i]['id_asignacion'].', '.$precios[$i]['correlativo'].', '.$precios[$i]['id_pregunta'].', '.$precios[$i]['fila'].', \'resp'.($i + 1).'\')"><i class="icon-save"></i> Guardar</button></td>';
                } else {
                    echo '<td>Codigo:</td>';
                    echo '<td><input id="cod'.($i + 1).'" type="text" value="'.$precios[$i]['codigo_respuesta'].'"/>';
                    echo '<button class="btn btn-primary" type="button" onclick="guardar(this, '.$precios[$i]['id_asignacion'].', '.$precios[$i]['correlativo'].', '.$precios[$i]['id_pregunta'].', '.$precios[$i]['fila'].', \'cod'.($i + 1).'\')"><i class="icon-save"></i> Guardar</button></td>';
                }
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    public function guardar() {
        $sess = $this->session->userdata();
        if (in_array('revision', $sess['permisos'])) {
            $id_asignacion = $this->input->post('id_asignacion');
            $correlativo = $this->input->post('correlativo');
            $id_pregunta = $this->input->post('id_pregunta');
            $fila = $this->input->post('fila');
            $valor = $this->input->post('valor');
            echo $this->Precio->editar($id_asignacion, $correlativo, $id_pregunta, $fila, $valor, $sess['login']);
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    public function productos() {
        $sess = $this->session->userdata();
        if (in_array('revision', $sess['permisos'])) {
            $id_tipo = $this->input->get('id_tipo');
            $descripcion = $this->input->get('descripcion');
            $productos = $this->Precio->get_productos($id_tipo, $descripcion);
            echo '<table id="prod">';
                echo '<tr>';
                    echo '<th>Departamento</th>';
                    if ($id_tipo == 1) {
                        echo '<th>Mercado</th>';
                    } else {
                        echo '<th>Comercializadora</th>';
                    }
                    echo '<th>Id</th>';
                    echo '<th>Producto</th>';
                    echo '<th>Especificacion</th>';
                echo '</tr>';
            for ($i = 0; $i < count($productos); $i++) {
                echo '<tr>';
                    echo '<td>'.$productos[$i]['departamento'].'</td>';
                    echo '<td>'.$productos[$i]['descripcion'].'</td>';
                    echo '<td><a href="#" onclick="select('.$productos[$i]['id_producto'].')">'.$productos[$i]['id_producto'].'</a></td>';
                    echo '<td>'.$productos[$i]['producto'].'</td>';
                    echo '<td>'.$productos[$i]['especificacion'].'</td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    public function mapa() {
        $sess = $this->session->userdata();
        if (in_array('operativo', $sess['permisos'])) {
            $id = $this->input->get('id');
            $data['upm'] = $this->Informante->get_punto_json($id);
            $depto = $this->input->get('depto');
            $tipo = $this->input->get('tipo');
            $carga = $this->input->get('carga');
            $data['upms'] = $this->Informante->get_puntos_json($depto, $tipo, $carga, $id);
            $this->load->view('operativo/mapa', $data);
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function guardar_punto() {
        $sess = $this->session->userdata();
        if (in_array('operativo', $sess['permisos'])) {
            $id = $this->input->post('id');
            $json = $this->input->post('json');
            if ($this->Informante->guardar_punto($id, $json) != 1) {
                echo 'Error!';
            }
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function consolidacion_manual() {
        $sess = $this->session->userdata();
        if (in_array('operativo', $sess['permisos'])) {
            $data['title'] = 'Consolidación Manual';
            $this->load->view('templates/header', $sess);
            $this->load->view('operativo/consolidacion_manual', $data);
            $this->load->view('templates/footer');
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function codigo() {
        $sess = $this->session->userdata();
        if (in_array('operativo', $sess['permisos'])) {
            $data['title'] = 'Codigo de Activación';
            $this->load->view('templates/header', $sess);
            $this->load->view('operativo/codigo', $data);
            $this->load->view('templates/footer');
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function calcular() {
        $sess = $this->session->userdata();
        if (in_array('operativo', $sess['permisos'])) {
            $string = (String)$this->input->post('alea');
            $buffer = array();
            for($i = 0; $i < strlen($string); $i++) {
                $buffer[] = ord($string[$i]);
            }
            $crc = 0xffff;
            for ($i = 0; $i < count($buffer); $i++) {
                $crc = ((($crc >> 8) & 0xff) | ($crc << 8)) & 0xffff;
                $crc ^= ($buffer[$i] & 0xff);
                $crc ^= (($crc & 0xff) >> 4);
                $crc ^= ($crc << 12) & 0xffff;
                $crc ^= (($crc & 0xff) << 5) & 0xffff;
            }
            $crc &= 0xffff;
            echo dechex($crc);
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    public function variacion() {
        set_time_limit(12000);
        ini_set('MAX_EXECUTION_TIME', 12000);
        
        $sess = $this->session->userdata();
        if (in_array('operativo', $sess['permisos'])) {
            $neg = $this->input->get('neg');
            $tipo = $this->input->get('tipo');
            if (!$tipo) {
                $tipo = 1;
            }
            $data['neg'] = $neg;
            $data['tipo'] = $tipo;
            $data['title'] = 'Justificaciones a las variaciones del ultimo periodo';
            $this->load->view('templates/header', $sess);
            $data['observacion'] = $this->Precio->get_observacion($sess['id_departamento'], $tipo, $sess['login']);
            $data['variaciones'] = $this->Precio->get_variacion($sess['id_usuario'], $tipo, $neg);
            $this->load->view('operativo/variacion', $data);
            $this->load->view('templates/footer');
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function guardar_variacion() {
        $sess = $this->session->userdata();
        if (in_array('operativo', $sess['permisos'])) {
            $obj = $this->input->post();
            $obs = $obj['observacion'];
            unset($obj['observacion']);
            echo $this->Precio->set_just($sess['id_departamento'], json_encode($obj), $obs);
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    public function guardar_variacion_mes() {
        $sess = $this->session->userdata();
        if (in_array('operativo', $sess['permisos'])) {
            $obj = $this->input->post();
            $obs = $obj['observacion'];
            unset($obj['observacion']);
            echo $this->Precio->set_just_mes($sess['id_departamento'], json_encode($obj), $obs);
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    public function justificacion() {
        $sess = $this->session->userdata();
        if (in_array('operativo', $sess['permisos'])) {
            $data['title'] = 'Justificaciones';
            $this->load->view('templates/header', $sess);
            $data['departamento'] = $this->Asignacion->get_departamento($sess['id_usuario']);
            $data['depto'] = $sess['id_departamento'];
            $this->load->view('operativo/justificacion', $data);
            $this->load->view('templates/footer');
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function justificacion_rep() {
        set_time_limit(12000);
        ini_set('MAX_EXECUTION_TIME', 12000);
        
        $sess = $this->session->userdata();
        if (in_array('operativo', $sess['permisos'])) {
            $tipo = (Int)$this->input->get('tipo');
            $gestion = (Int)$this->input->get('gestion');
            $periodo = (Int)$this->input->get('periodo');
            $depto = (Int)$this->input->get('depto');
            $objPHPWord = new PHPWord();
            $section = $objPHPWord->createSection();
            if ($tipo == 1) {
                $section->addText(utf8_decode('Productos Agrícolas que Tuvieron Variación Positiva'), Array('bold'=>true, 'size'=>18), Array('align'=>'center'));
            } else {
                $section->addText(utf8_decode('Productos Manufacturados que Tuvieron Variación Positiva'), Array('bold'=>true, 'size'=>18), Array('align'=>'center'));
            }
            $observacion = $this->Precio->get_observacion($depto, $tipo, $sess['login']);
            $variaciones = $this->Precio->get_just($tipo, $gestion, $periodo, $depto, false);
            $i = 1;
            foreach ($variaciones as $variacion) {
                $section->addText(utf8_decode($variacion['especificacion']), Array('bold'=>true, 'size'=>14));
                $justs = explode("\n", $variacion['justificacion']);
                foreach ($justs as $jus) {
                    $section->addText(utf8_decode($jus), Array(), Array('align'=>'both'));
                }
            
                $img = $this->Fotografia->get_justificativo($variacion['gestion'], $variacion['semana'], $depto, $variacion['origen'], $variacion['codigo']);
                if ($img != null) {
                    $file = sys_get_temp_dir().'/temp'.$i.'.jpeg';
                    file_put_contents($file, base64_decode($img));

                    $section->addImage($file, Array('align'=>'center'));
                }
                $i++;
            }
            
            $section = $objPHPWord->createSection();
            if ($tipo == 1) {
                $section->addText(utf8_decode('Productos Agrícolas que Tuvieron Variación Negativa'), Array('bold'=>true, 'size'=>18), Array('align'=>'center'));
            } else {
                $section->addText(utf8_decode('Productos Manufacturados que Tuvieron Variación Negativa'), Array('bold'=>true, 'size'=>18), Array('align'=>'center'));
            }
            $observacion = $this->Precio->get_observacion($depto, $tipo, $sess['login']);
            $variaciones = $this->Precio->get_just($tipo, $gestion, $periodo, $depto, true);
            $i = 101;
            foreach ($variaciones as $variacion) {
                $section->addText(utf8_decode($variacion['especificacion']), Array('bold'=>true, 'size'=>14));
                $justs = explode("\n", $variacion['justificacion']);
                foreach ($justs as $jus) {
                    $section->addText(utf8_decode($jus), Array(), Array('align'=>'both'));
                }
            
                $img = $this->Fotografia->get_justificativo($variacion['gestion'], $variacion['semana'], $depto, $variacion['origen'], $variacion['codigo']);
                if ($img != null) {
                    $file = sys_get_temp_dir().'/temp'.$i.'.jpeg';
                    file_put_contents($file, base64_decode($img));

                    $section->addImage($file, Array('align'=>'center'));
                }
                $i++;
            }
            
            $section->addText(utf8_decode('Observaciones generales'), Array('bold'=>true, 'size'=>14));
            $section->addText(utf8_decode($observacion));

            // Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
            header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
            header('Content-Disposition: attachment;filename="justificacion.docx"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPWord_IOFactory::createWriter($objPHPWord, 'Word2007');
            $objWriter->save('php://output');
            exit;
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
}