<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Canasta
 *
 * @author Alberto Daniel Inch Sáinz
 */
class Canasta extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('session');
        $this->load->model('Asignacion');
        $this->load->model('Informante');
        $this->load->model('Producto');
        $this->load->model('Catalogo');
        if (count($this->session->userdata()) == 1) {
            $this->session->set_userdata(Array('activo' => false, 'permisos' => Array()));
        }
    }
    
    ///@brief Carga las opciones de seleccion de ciudad según departamento.
    ///@return Cadena HTML con las opciones de seleccion.
    public function ciudad() {
        $sess = $this->session->userdata();
        if (in_array('informante', $sess['permisos'])) {
            $id = $this->input->post('id');
            $ciudades = $this->Informante->get_ciudad($sess['id_usuario'], $id);
            foreach ($ciudades as $key => $ciudad) {
                echo '<OPTION VALUE="'.$key.'">'.$ciudad.'</OPTION>';
            }
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    public function clasificador() {
        $sess = $this->session->userdata();
        if (in_array('informante', $sess['permisos'])) {
            $clasificador = $this->Catalogo->get_clasificador();
            echo '<table id="Clasificador">';
            echo '<thead>';
            echo '<tr>';
                echo '<th>Codigo</th>';
                echo '<th>Descripcion</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            $cod = ''; $cod2 = '';
            for ($i = 0; $i < count($clasificador); $i++) {
                echo '<tr>';
                    if (strlen($clasificador[$i]['codigo']) == 1) {
                        $cod2 = $clasificador[$i]['codigo'];
                    }
                    if ($cod != $cod2) {
                        $cod = $cod2;
                        echo '<td>'.$clasificador[$i]['codigo'].'</td>';
                    } else {
                        if (strlen($clasificador[$i]['codigo']) == 10) {
                            echo '<td><font color="white">'.$cod.'</font><a style="cursor: pointer" onclick="codif(\''.$clasificador[$i]['codigo'].'\')">'.$clasificador[$i]['codigo'].'</a></td>';
                        } else {
                            echo '<td><font color="white">'.$cod.'</font>'.$clasificador[$i]['codigo'].'</td>';
                        }
                    }
                    echo $clasificador[$i]['descripcion'];
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
            echo '<script type="text/javascript">$("#Clasificador").DataTable({paging: false});</script>';
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    public function pendientes() {
        $sess = $this->session->userdata();
        if (in_array('informante', $sess['permisos'])) {
            $data['title'] = 'Cambios de esp./inf. pendientes';
            $this->load->view('templates/header', $sess);
            $data['pendientes'] = $this->Producto->cambios_pendientes($sess['id_usuario'], true);
            $this->load->view('canasta/pendientes', $data);
            $this->load->view('templates/footer');
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function cotizacion() {
        $sess = $this->session->userdata();
        if (in_array('informante', $sess['permisos'])) {
            $id = $this->input->post('id');
            $cotizaciones = $this->Producto->cotizacion($id);
            echo '<table>';
            foreach ($cotizaciones as $cotizacion) {
                echo '<tr>';
                echo '<th>'.$cotizacion['pregunta'].'</th>';
                echo '<td>'.$cotizacion['respuesta'].'</td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function informantes() {
        $sess = $this->session->userdata();
        if (in_array('informante', $sess['permisos'])) {
            $id_prod = $this->input->get('id_prod');
            $id_informador = $this->input->get('id_upm');
            $id_tipolistado = $this->input->get('id_tipo');
            $id_departamento = $this->input->get('id_depto');
            $informantes = $this->Informante->get_informantes($id_tipolistado, $id_departamento, $id_informador);
            echo '<table id="Informantes" class="table table-advance">';
            echo '<thead>';
            echo '<tr>';
                echo '<th>Carga</th>';
                echo '<th>Nro</th>';
                echo '<th>Informante</th>';
                echo '<th>Direccion</th>';
                echo '<th>Entre Calles</th>';
                echo '<th></th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            if (count($informantes) == 0) {
                echo '<tr><td colspan="5">No existen otros informantes.</td></tr>';
            }
            for ($i = 0; $i < count($informantes); $i++) {
                echo '<tr>';
                    echo '<td>'.$informantes[$i]['carga'].'</td>';
                    echo '<td>'.$informantes[$i]['recorrido_carga'].'</td>';
                    echo '<td>'.$informantes[$i]['informante'].'</td>';
                    echo '<td>'.$informantes[$i]['direccion'].'</td>';
                    echo '<td>'.$informantes[$i]['entre_calles'].'</td>';
                    echo '<td><button title="Fusionar" class="btn btn-primary" onclick="fusionar(this,'.$id_prod.','.$informantes[$i]['id_informador'].')"><i class="fa fa-random"/></button></td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    public function mercados() {
        $sess = $this->session->userdata();
        if (in_array('informante', $sess['permisos'])) {
            $data['title'] = 'Mercados';
            $this->load->view('templates/header', $sess);
            $id_usuario = $sess['id_usuario'];
            $data['departamentos'] = $this->Informante->get_departamento($id_usuario);
            $data['mercados'] = $this->Informante->get_mercados($id_usuario);
            $this->load->view('canasta/mercados', $data);
            $this->load->view('templates/footer');
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function tabla_mercados() {
        $sess = $this->session->userdata();
        if (in_array('informante', $sess['permisos'])) {
            $mercado = '%'.$this->input->post('mercado').'%';
            $producto = '%'.$this->input->post('producto').'%';
            $id_usuario = $this->session->userdata('id_usuario');
            $mercados = $this->Informante->get_mercados($id_usuario, $mercado, $producto);
            echo '<table class="table table-striped table-bordered table-hover dataTable">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th style="width: 15px;"></th>';
                        echo '<th>Departamento</th>';
                        echo '<th>Mercado</th>';
                        echo '<th></th>';
                        echo '<th></th>';
                        echo '<th></th>';
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                    echo '<tr></tr>';
                    for ($i = 0; $i < count($mercados); $i++) {
                    echo '<tr>';
                        echo '<td><a id="exp'.$i.'" onclick="expand('.$i.')" href="#">+</a></td>';
                        echo '<td>'.$mercados[$i]['departamento'].'</td>';
                        echo '<td>'.$mercados[$i]['descripcion'].'</td>';
                        echo '<td><button class="btn btn-primary" onclick="editinf('.$mercados[$i]['id_informador'].')"><i class="fa fa-edit"></i> Editar</button></td>';
                        echo '<td><button class="btn btn-primary" onclick="addprod('.$mercados[$i]['id_informador'].')"><i class="fa fa-plus"></i> Agregar Producto</button></td>';
                        echo '<td><button class="btn btn-primary" onclick="delinf('.$mercados[$i]['id_informador'].')"><i class="fa fa-trash"></i> Descartar</button></td>';
                    echo '</tr>';
                    echo '<tr>';
                        echo '<td></td>';
                        echo '<td colspan="5">';
                            echo '<table id="tab'.$i.'" style="display: none;">';
                                echo '<tr style="font-weight: bold">';
                                    echo '<td style="color: blue">Codigo</td>';
                                    echo '<td style="color: blue">Nombre</td>';
                                    echo '<td style="color: blue">Descripcion</td>';
                                    echo '<td style="color: blue">Unidad</td>';
                                    echo '<td style="color: blue">Equivalencia</td>';
                                    echo '<td></td>';
                                    echo '<td></td>';
                                    echo '<td></td>';
                                    echo '<td></td>';
                                echo '</tr>';
                                for ($j = 0; $j < count($mercados[$i]['productos']); $j++) {
                                echo '<tr>';
                                    echo '<td style="color: #0090FF">'.$mercados[$i]['productos'][$j]['codigo'].'</td>';
                                    echo '<td style="color: #0090FF">'.$mercados[$i]['productos'][$j]['producto'].'</td>';
                                    echo '<td style="color: #0090FF">'.$mercados[$i]['productos'][$j]['especificacion'].'</td>';
                                    echo '<td style="color: #0090FF">'.$mercados[$i]['productos'][$j]['unidad'].'</td>';
                                    echo '<td style="color: #0090FF">'.$mercados[$i]['productos'][$j]['equivalencia'].'</td>';
                                    echo '<td><button title="Editar" class="btn btn-bordered btn-default" onclick="editprod('.$mercados[$i]['productos'][$j]['id_producto'].')"><i class="fa fa-edit"/></button></td>';
                                    echo '<td><button title="Fusionar" class="btn btn-bordered btn-default" onclick="selupm('.$mercados[$i]['productos'][$j]['id_producto'].','.$mercados[$i]['id_departamento'].','.$mercados[$i]['id_informador'].')"><i class="fa fa-random"/></button></td>';
                                    echo '<td><button title="Descartar" class="btn btn-bordered btn-default" onclick="delprod('.$mercados[$i]['productos'][$j]['id_producto'].')"><i class="fa fa-trash"/></button></td>';
                                    echo '<td><button title="Imagen" class="btn btn-bordered btn-default" onclick="imagen('.$mercados[$i]['productos'][$j]['id_producto'].')"><i class="fa fa-picture-o"/></button></td>';
                                echo '</tr>';
                                }
                            echo '</table>';
                        echo '</td>';
                    echo '</tr>';
                    }
                echo '</tbody>';
            echo '</table>';
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    public function mercado_form() {
        $sess = $this->session->userdata();
        if (in_array('informante', $sess['permisos'])) {
            $data = $this->Asignacion->get_values($sess['id_usuario']);
            $data['departamentos'] = $this->Informante->get_departamento($sess['id_usuario']);
            $data['id'] = $this->input->post('id');
            if ($data['id'] != FALSE) {
                $mercado = $this->Informante->get_informador($data['id']);
                $data['id_departamento'] = $mercado['id_departamento'];
                $data['ciudades'] = $this->Informante->get_ciudad($sess['id_usuario'], $data['id_departamento']);
                $data['id_ciudad'] = $mercado['id_ciudad'];
                $data['mercado'] = $mercado['descripcion'];
                $data['zona'] = $mercado['zona'];
            } else {
                $data['ciudades'] = $this->Informante->get_ciudad($sess['id_usuario'], $data['id_departamento']);
                if (count($data['ciudades']) == 0) {
                    $data['id_ciudad'] = 0;
                } else {
                    $keys = array_keys($data['ciudades']);
                    $data['id_ciudad'] = $data['ciudades'][$keys[0]];
                }
                $data['mercado'] = '';
                $data['zona'] = '';
            }
            $this->load->view('canasta/mercado_form', $data);
        } else {
            echo 'Permiso denegado.';
        }
    }

    public function insert_mercado() {
        $sess = $this->session->userdata();
        if (in_array('informante', $sess['permisos'])) {
            $depto = $this->input->post('depto');
            $ciudad = $this->input->post('ciudad');
            $mercado = $this->input->post('mercado');
            $zona = $this->input->post('zona');
            echo $this->Informante->insert_mercado($depto, $ciudad, $mercado, $zona, $sess['login']);
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    public function update_mercado() {
        $sess = $this->session->userdata();
        if (in_array('informante', $sess['permisos'])) {
            $id = $this->input->post('id');
            $depto = $this->input->post('depto');
            $ciudad = $this->input->post('ciudad');
            $mercado = $this->input->post('mercado');
            $zona = $this->input->post('zona');
            $justificacion = $this->input->post('justificacion');
            echo $this->Informante->update_mercado($id, $depto, $ciudad, $mercado, $zona, $justificacion, $sess['login']);
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    public function discard_mercado() {
        $sess = $this->session->userdata();
        if (in_array('informante', $sess['permisos'])) {
            $id = $this->input->post('id');
            $justificacion = $this->input->post('justificacion');
            echo $this->Informante->discard_mercado($id, $justificacion, $sess['login']);
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    public function agricola_form() {
        $sess = $this->session->userdata();
        if (in_array('informante', $sess['permisos'])) {
            $id = $this->input->post('id');
            $data['id'] = $id;
            $data['id_informador'] = $this->input->post('id_informador');
            $cpc = $this->Catalogo->get_agricola();
            $cpca = Array();
            for ($i = 0; $i < count($cpc); $i++) {
                $cpca[$cpc[$i]['descripcion']] = $cpc[$i]['descripcion'];
            }
            $data['cpca'] = $cpca;
            if ($id != FALSE) {
                $producto = $this->Informante->get_producto($id);
                $data['codigo'] = $producto['codigo'];
                $data['producto'] = $producto['producto'];
                $data['especificacion'] = $producto['especificacion'];
                $data['cantidad_inicial'] = $producto['cantidad_inicial'];
                $data['unidad_inicial'] = $producto['unidad_inicial'];
                $data['factor_ajuste'] = $producto['factor_ajuste'];
                $data['cantidad_a_cotizar'] = $producto['cantidad_a_cotizar'];
                $data['unidad_a_cotizar'] = $producto['unidad_a_cotizar'];
                $data['cantidad_equivalente'] = $producto['cantidad_equivalente'];
                $data['unidad_convencional'] = $producto['unidad_convencional'];
                $data['origen'] = $producto['origen'];
            } else {
                $data['codigo'] = '';
                $data['producto'] = '';
                $data['especificacion'] = '';
                $data['cantidad_a_cotizar'] = '';
                $data['unidad_a_cotizar'] = '';
                $data['cantidad_equivalente'] = '';
                $data['unidad_convencional'] = '';
                $data['origen'] = '';
            }
            $this->load->view('canasta/agricola_form', $data);
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    public function insert_prod_agri() {
        $sess = $this->session->userdata();
        if (in_array('informante', $sess['permisos'])) {
            $id_informador = $this->input->post('id_informador');
            $codigo = $this->input->post('codigo');
            $producto = $this->input->post('producto');
            $especificacion = $this->input->post('especificacion');
            $cantidad_a_cotizar = $this->input->post('cantidad_a_cotizar');
            $unidad_a_cotizar = $this->input->post('unidad_a_cotizar');
            $cantidad_equivalente = $this->input->post('cantidad_equivalente');
            $unidad_convencional = $this->input->post('unidad_convencional');
            $origen = $this->input->post('origen');
            echo $this->Producto->insert_prod_agri($id_informador, $codigo, $producto, $especificacion, $cantidad_a_cotizar, $unidad_a_cotizar, $cantidad_equivalente, $unidad_convencional, $origen, $sess['login']);
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    public function update_prod_agri() {
        $sess = $this->session->userdata();
        if (in_array('informante', $sess['permisos'])) {
            $id = $this->input->post('id');
            $codigo = $this->input->post('codigo');
            $producto = $this->input->post('producto');
            $especificacion = $this->input->post('especificacion');
            $factor_ajuste = $this->input->post('factor_ajuste');
            $cantidad_a_cotizar = $this->input->post('cantidad_a_cotizar');
            $unidad_a_cotizar = $this->input->post('unidad_a_cotizar');
            $cantidad_equivalente = $this->input->post('cantidad_equivalente');
            $unidad_convencional = $this->input->post('unidad_convencional');
            $origen = $this->input->post('origen');
            $justificacion = $this->input->post('justificacion');
            echo $this->Producto->update_prod_agri($id, $codigo, $producto, $especificacion, $factor_ajuste, $cantidad_a_cotizar, $unidad_a_cotizar, $cantidad_equivalente, $unidad_convencional, $origen, $justificacion, $sess['login']);
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    public function discard_prod_agri() {
        $sess = $this->session->userdata();
        if (in_array('informante', $sess['permisos'])) {
            $id = $this->input->post('id');
            $justificacion = $this->input->post('justificacion');
            echo $this->Producto->discard_prod_agri($id, $justificacion, $sess['login']);
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    public function fusion_prod_agri() {
        $sess = $this->session->userdata();
        if (in_array('informante', $sess['permisos'])) {
            $id = $this->input->post('id');
            $id_upm = $this->input->post('id_upm');
            $justificacion = $this->input->post('justificacion');
            echo $this->Producto->fusion_prod_agri($id, $id_upm, $justificacion, $sess['login']);
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    public function sol_mercados() {
        $sess = $this->session->userdata();
        if (in_array('informante', $sess['permisos'])) {
            $data['title'] = 'Solicitudes';
            $this->load->view('templates/header', $sess);
            if ($estado = $this->input->post('estado')) {
                $data['solicitudesprod'] = $this->Producto->get_sol_agircolas($sess['login'], $estado);
            } else {
                $data['solicitudesprod'] = $this->Producto->get_sol_agircolas($sess['login']);
            }
            $this->load->view('canasta/sol_mercados', $data);
            $this->load->view('templates/footer');
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function aprobar_agricolas() {
        $sess = $this->session->userdata();
        if (in_array('aprobar', $sess['permisos'])) {
            $data['title'] = 'Solicitudes';
            $this->load->view('templates/header', $sess);
            if ($estado = $this->input->post('estado')) {
                $data['solicitudesprod'] = $this->Producto->get_sol_agircolas('%', $estado);
            } else {
                $data['solicitudesprod'] = $this->Producto->get_sol_agircolas();
            }
            $this->load->view('canasta/aprobar_agricolas', $data);
            $this->load->view('templates/footer');
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function detalle() {
        $sess = $this->session->userdata();
        if (in_array('informante', $sess['permisos'])) {
            $id = $this->input->get('id');
            $sol = $this->Producto->get_solicitud($id);
            echo '<table>';
                echo '<tr>';
                    echo '<th>Solicitud</th>';
                    echo '<th>Registro Actual</th>';
                echo '</tr>';
                echo '<tr>';
                    echo '<td style="color: '.((count($sol) == 2 && $sol[0]['departamento'] !== $sol[1]['departamento']) ? '#FF0000' : '#0000FF').'"><b>Departamento:</b><br>'.$sol[0]['departamento'].'</td>';
                    echo '<td>'.(count($sol) == 2 ? '<b>Departamento:</b><br>'.$sol[1]['departamento'] : '').'</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<td style="color: '.((count($sol) == 2 && $sol[0]['descripcion'] !== $sol[1]['descripcion']) ? '#FF0000' : '#0000FF').'"><b>Nombre Comercial:</b><br>'.$sol[0]['descripcion'].'</td>';
                    echo '<td>'.(count($sol) == 2 ? '<b>Informante:</b><br>'.$sol[1]['descripcion'] : '').'</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<td style="color: '.((count($sol) == 2 && $sol[0]['codigo'] !== $sol[1]['codigo']) ? '#FF0000' : '#0000FF').'"><b>Codigo:</b><br>'.$sol[0]['codigo'].'</td>';
                    echo '<td>'.(count($sol) == 2 ? '<b>Codigo:</b><br>'.$sol[1]['codigo'] : '').'</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<td style="color: '.(count($sol) == 2 && $sol[0]['producto'] !== $sol[1]['producto'] ? '#FF0000' : '#0000FF').'"><b>Producto:</b><br>'.$sol[0]['producto'].'</td>';
                    echo '<td>'.(count($sol) == 2 ? '<b>Producto:</b><br>'.$sol[1]['producto'] : '').'</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<td style="color: '.(count($sol) == 2 && $sol[0]['especificacion'] !== $sol[1]['especificacion'] ? '#FF0000' : '#0000FF').'"><b>Especificaci&oacute;n:</b><br>'.$sol[0]['especificacion'].'</td>';
                    echo '<td>'.(count($sol) == 2 ? '<b>Especificaci&oacute;n:</b><br>'.$sol[1]['especificacion'] : '').'</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<td style="color: '.(count($sol) == 2 && $sol[0]['base'] !== $sol[1]['base'] ? '#FF0000' : '#0000FF').'"><b>Unidad Inicial:</b><br>'.$sol[0]['base'].'</td>';
                    echo '<td>'.(count($sol) == 2 ? '<b>Unidad Inicial:</b><br>'.$sol[1]['base'] : '').'</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<td style="color: '.(count($sol) == 2 && $sol[0]['factor_ajuste'] !== $sol[1]['factor_ajuste'] ? '#FF0000' : '#0000FF').'"><b>Factor de ajuste:</b><br>'.$sol[0]['factor_ajuste'].'</td>';
                    echo '<td>'.(count($sol) == 2 ? '<b>Factor de ajuste:</b><br>'.$sol[1]['factor_ajuste'] : '').'</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<td style="color: '.(count($sol) == 2 && $sol[0]['unidad'] !== $sol[1]['unidad'] ? '#FF0000' : '#0000FF').'"><b>Unidad:</b><br>'.$sol[0]['unidad'].'</td>';
                    echo '<td>'.(count($sol) == 2 ? '<b>Unidad:</b><br>'.$sol[1]['unidad'] : '').'</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<td style="color: '.(count($sol) == 2 && $sol[0]['equivalencia'] !== $sol[1]['equivalencia'] ? '#FF0000' : '#0000FF').'"><b>Equivalencia:</b><br>'.$sol[0]['equivalencia'].'</td>';
                    echo '<td>'.(count($sol) == 2 ? '<b>Equivalencia:</b><br>'.$sol[1]['equivalencia'] : '').'</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<td style="color: '.(count($sol) == 2 && $sol[0]['factor'] !== $sol[1]['factor'] ? '#FF0000' : '#0000FF').'"><b>Factor:</b><br>'.$sol[0]['factor'].'</td>';
                    echo '<td>'.(count($sol) == 2 ? '<b>Factor:</b><br>'.$sol[1]['factor'] : '').'</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<td style="color: '.(count($sol) == 2 && $sol[0]['unidad_final'] !== $sol[1]['unidad_final'] ? '#FF0000' : '#0000FF').'"><b>Unidad Final:</b><br>'.$sol[0]['unidad_final'].'</td>';
                    echo '<td>'.(count($sol) == 2 ? '<b>Unidad Final:</b><br>'.$sol[1]['unidad_final'] : '').'</td>';
                echo '</tr>';
                if ($sol[0]['id_boleta'] == 2) {
                    echo '<tr>';
                        echo '<td style="color: '.(count($sol) == 2 && $sol[0]['unidad_talla_peso'] !== $sol[1]['unidad_talla_peso'] ? '#FF0000' : '#0000FF').'"><b>Unidad/Talla/Peso:</b><br>'.$sol[0]['unidad_talla_peso'].'</td>';
                        echo '<td>'.(count($sol) == 2 ? '<b>Unidad/Talla/Peso:</b><br>'.$sol[1]['unidad_talla_peso'] : '').'</td>';
                    echo '</tr>';
                    echo '<tr>';
                        echo '<td style="color: '.(count($sol) == 2 && $sol[0]['marca'] !== $sol[1]['marca'] ? '#FF0000' : '#0000FF').'"><b>Marca:</b><br>'.$sol[0]['marca'].'</td>';
                        echo '<td>'.(count($sol) == 2 ? '<b>Marca:</b><br>'.$sol[1]['marca'] : '').'</td>';
                    echo '</tr>';
                    echo '<tr>';
                        echo '<td style="color: '.(count($sol) == 2 && $sol[0]['modelo'] !== $sol[1]['modelo'] ? '#FF0000' : '#0000FF').'"><b>Modelo:</b><br>'.$sol[0]['modelo'].'</td>';
                        echo '<td>'.(count($sol) == 2 ? '<b>Modelo:</b><br>'.$sol[1]['modelo'] : '').'</td>';
                    echo '</tr>';
                    echo '<tr>';
                        echo '<td style="color: '.(count($sol) == 2 && $sol[0]['envase'] !== $sol[1]['envase'] ? '#FF0000' : '#0000FF').'"><b>Envase:</b><br>'.$sol[0]['envase'].'</td>';
                        echo '<td>'.(count($sol) == 2 ? '<b>Envase:</b><br>'.$sol[1]['envase'] : '').'</td>';
                    echo '</tr>';
                }
                echo '<tr>';
                    echo '<td style="color: '.(count($sol) == 2 && $sol[0]['origen'] !== $sol[1]['origen'] ? '#FF0000' : '#0000FF').'"><b>Origen:</b><br>'.$sol[0]['origen'].'</td>';
                    echo '<td>'.(count($sol) == 2 ? '<b>Origen:</b><br>'.$sol[1]['origen'] : '').'</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<td style="color: '.(count($sol) == 2 && $sol[0]['procedencia'] !== $sol[1]['procedencia'] ? '#FF0000' : '#0000FF').'"><b>Procedencia:</b><br>'.$sol[0]['procedencia'].'</td>';
                    echo '<td>'.(count($sol) == 2 ? '<b>Procedencia:</b><br>'.$sol[1]['procedencia'] : '').'</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<td style="color: #0000FF"><b>Id:</b><br/>'.$sol[0]['id_producto'].'</td>';
                    echo '<td><b>Id:</b><br/>'.$sol[0]['id_producto'].'</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<td><img id="img1" src="'.site_url().'/Imagen/temp1?id='.$sol[0]['id_producto'].'" style="width: 150px; height: 150px;"/></td>';
                    echo '<td><img id="img2" src="'.site_url().'/Imagen/image1?id='.$sol[0]['id_producto'].'" style="width: 150px; height: 150px;"/></td>';
                echo '</tr>';
            echo '</tablet>';
        } else {
            echo 'Acceso denegado!';
        }
    }

// -------------- Comercializadoras ----------------
    public function comercializadoras() {
        $sess = $this->session->userdata();
        if (in_array('informante', $sess['permisos'])) {
            $data['title'] = 'Comercializadoras';
            $this->load->view('templates/header', $sess);
            $data['comercializadoras'] = $this->Informante->get_comercializadoras($sess['id_usuario']);
            if ($id = $this->input->post('id')) {
                $data['id'] = $id;
            }
            $this->load->view('canasta/comercializadoras', $data);
            $this->load->view('templates/footer');
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function tabla_comercializadoras() {
        $sess = $this->session->userdata();
        if (in_array('informante', $sess['permisos'])) {
            $comercializadora = '%'.$this->input->post('comercializadora').'%';
            $producto = '%'.$this->input->post('producto').'%';
            $id_usuario = $this->session->userdata('id_usuario');
            $comercializadoras = $this->Informante->get_comercializadoras($id_usuario, $comercializadora, $producto);
            echo '<table class="table table-advance table-bordered tbl">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th style="width: 15px;"></th>';
                        echo '<th>Departamento</th>';
                        echo '<th>Nombre</th>';
                        echo '<th>Direcci&oacute;n</th>';
                        echo '<th>Entre Calles</th>';
                        echo '<th></th>';
                        echo '<th></th>';
                        echo '<th></th>';
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                    echo '<tr></tr>';
                    for ($i = 0; $i < count($comercializadoras); $i++) {
                    echo '<tr>';
                        echo '<td><a id="exp'.$i.'" onclick="expand('.$i.')" href="#">+</a></td>';
                        echo '<td>'.$comercializadoras[$i]['departamento'].'</td>';
                        echo '<td>'.$comercializadoras[$i]['descripcion'].'</td>';
                        echo '<td>'.$comercializadoras[$i]['direccion'].'</td>';
                        echo '<td>'.$comercializadoras[$i]['entre_calles'].'</td>';
                        echo '<td><button title="Editar" class="btn btn-bordered btn-primary" onclick="editinf('.$comercializadoras[$i]['id_informador'].')"><i class="fa fa-edit"></i></button></td>';
                        echo '<td><button title="Agregar Producto" class="btn btn-bordered btn-primary" onclick="addprod('.$comercializadoras[$i]['id_informador'].')"><i class="fa fa-plus"></i></button></td>';
                        echo '<td><button title="Descartar" class="btn btn-bordered btn-primary" onclick="delinf('.$comercializadoras[$i]['id_informador'].')"><i class="fa fa-trash"></i></button></td>';
                    echo '</tr>';
                    echo '<tr>';
                        echo '<td></td>';
                        echo '<td colspan="7">';
                            echo '<table id="tab'.$i.'" style="display: none;">';
                                echo '<tr style="font-weight: bold">';
                                    echo '<td style="color: blue">Codigo</td>';
                                    echo '<td style="color: blue">Producto</td>';
                                    echo '<td style="color: blue">Especificaci&oacute;n</td>';
                                    echo '<td style="color: blue">Tama/Talla/Peso</td>';
                                    echo '<td style="color: blue">Marca</td>';
                                    echo '<td style="color: blue">Modelo</td>';
                                    echo '<td style="color: blue">Unidad</td>';
                                    echo '<td style="color: blue">Equivalencia</td>';
                                    echo '<td style="color: blue">Envase</td>';
                                    echo '<td style="color: blue">Origen</td>';
                                    echo '<td></td>';
                                    echo '<td></td>';
                                    echo '<td></td>';
                                    echo '<td></td>';
                                echo '</tr>';
                                for ($j = 0; $j < count($comercializadoras[$i]['productos']); $j++) {
                                echo '<tr>';
                                    echo '<td style="color: #0090FF">'.$comercializadoras[$i]['productos'][$j]['codigo'].'</td>';
                                    echo '<td style="color: #0090FF">'.$comercializadoras[$i]['productos'][$j]['producto'].'</td>';
                                    echo '<td style="color: #0090FF">'.$comercializadoras[$i]['productos'][$j]['especificacion'].'</td>';
                                    echo '<td style="color: #0090FF">'.$comercializadoras[$i]['productos'][$j]['unidad_talla_peso'].'</td>';
                                    echo '<td style="color: #0090FF">'.$comercializadoras[$i]['productos'][$j]['marca'].'</td>';
                                    echo '<td style="color: #0090FF">'.$comercializadoras[$i]['productos'][$j]['modelo'].'</td>';
                                    echo '<td style="color: #0090FF">'.$comercializadoras[$i]['productos'][$j]['unidad'].'</td>';
                                    echo '<td style="color: #0090FF">'.$comercializadoras[$i]['productos'][$j]['equivalencia'].'</td>';
                                    echo '<td style="color: #0090FF">'.$comercializadoras[$i]['productos'][$j]['envase'].'</td>';
                                    echo '<td style="color: #0090FF">'.$comercializadoras[$i]['productos'][$j]['origen'].'</td>';
                                    echo '<td><button title="Editar" class="btn btn-bordered btn-default" onclick="editprod('.$comercializadoras[$i]['productos'][$j]['id_producto'].')"><i class="fa fa-edit"/></button></td>';
                                    echo '<td><button title="Fusionar" class="btn btn-bordered btn-default" onclick="selupm('.$comercializadoras[$i]['productos'][$j]['id_producto'].','.$comercializadoras[$i]['id_departamento'].','.$comercializadoras[$i]['id_informador'].')"><i class="fa fa-random"/></button></td>';
                                    echo '<td><button title="Descartar" class="btn btn-bordered btn-default" onclick="delprod('.$comercializadoras[$i]['productos'][$j]['id_producto'].')"><i class="fa fa-trash"/></button></td>';
                                    echo '<td><button title="Imagen" class="btn btn-bordered btn-default" onclick="imagen('.$comercializadoras[$i]['productos'][$j]['id_producto'].')"><i class="fa fa-picture-o"/></button></td>';
                                echo '</tr>';
                                }
                            echo '</table>';
                        echo '</td>';
                    echo '</tr>';
                    }
                echo '</tbody>';
            echo '</table>';
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    public function comercializadora_form() {
        $sess = $this->session->userdata();
        if (in_array('informante', $sess['permisos'])) {
            $data = $this->Asignacion->get_values($sess['id_usuario']);
            $data['departamentos'] = $this->Informante->get_departamento($sess['id_usuario']);
            $data['id'] = $this->input->post('id');
            if ($data['id'] != FALSE) {
                $comercializadora = $this->Informante->get_informador($data['id']);
                $data['id_departamento'] = $comercializadora['id_departamento'];
                $data['ciudades'] = $this->Informante->get_ciudad($sess['id_usuario'], $data['id_departamento']);
                $data['id_ciudad'] = $comercializadora['id_ciudad'];
                $data['nit'] = $comercializadora['nit'];
                $data['regine'] = $comercializadora['regine'];
                $data['descripcion'] = $comercializadora['descripcion'];
                $data['nombre_informante'] = $comercializadora['nombre_informante'];
                $data['direccion'] = $comercializadora['direccion'];
                $data['numero'] = $comercializadora['numero'];
                $data['entre_calles'] = $comercializadora['entre_calles'];
                $data['edificio'] = $comercializadora['edificio'];
                $data['piso'] = $comercializadora['piso'];
                $data['oficina'] = $comercializadora['oficina'];
                $data['zona'] = $comercializadora['zona'];
                $data['referencia'] = $comercializadora['referencia'];
                $data['telefono'] = $comercializadora['telefono'];
                $data['fax'] = $comercializadora['fax'];
                $data['casilla'] = $comercializadora['casilla'];
                $data['e_mail'] = $comercializadora['e_mail'];
                $data['pagina_web'] = $comercializadora['pagina_web'];
                $data['carga'] = $comercializadora['carga'];
            } else {
                $data['ciudades'] = $this->Informante->get_ciudad($sess['id_usuario'], $data['id_departamento']);
                if (count($data['ciudades']) == 0) {
                    $data['id_ciudad'] = 0;
                } else {
                    $keys = array_keys($data['ciudades']);
                    $data['id_ciudad'] = $data['ciudades'][$keys[0]];
                }
                $data['nit'] = null;
                $data['regine'] = null;
                $data['descripcion'] = '';
                $data['nombre_informante'] = '';
                $data['direccion'] = '';
                $data['numero'] = '';
                $data['entre_calles'] = '';
                $data['edificio'] = '';
                $data['piso'] = '';
                $data['oficina'] = '';
                $data['zona'] = '';
                $data['referencia'] = '';
                $data['telefono'] = '';
                $data['fax'] = '';
                $data['casilla'] = '';
                $data['e_mail'] = '';
                $data['pagina_web'] = '';
                $data['carga'] = 'A';
            }
            $this->load->view('canasta/comercializadora_form', $data);
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    public function insert_comercializadora() {
        $sess = $this->session->userdata();
        if (in_array('informante', $sess['permisos'])) {
            $depto = $this->input->post('depto');
            $ciudad = $this->input->post('ciudad');
            $nit = $this->input->post('nit');
            $regine = $this->input->post('regine');
            $descripcion = $this->input->post('descripcion');
            $nombre_informante = $this->input->post('nombre_informante');
            $direccion = $this->input->post('direccion');
            $numero = $this->input->post('numero');
            $entre_calles = $this->input->post('entre_calles');
            $edificio = $this->input->post('edificio');
            $piso = $this->input->post('piso');
            $oficina = $this->input->post('oficina');
            $zona = $this->input->post('zona');
            $referencia = $this->input->post('referencia');
            $telefono = $this->input->post('telefono');
            $fax = $this->input->post('fax');
            $casilla = $this->input->post('casilla');
            $e_mail = $this->input->post('e_mail');
            $pagina_web = $this->input->post('pagina_web');
            $carga = $this->input->post('carga');
            $usuario = $sess['login'];
            echo $this->Informante->insert_comercializadora($depto, $ciudad, $nit, $regine, $descripcion, $nombre_informante, $direccion, $numero, $entre_calles, $edificio, $piso, $oficina, $zona, $referencia, $telefono, $fax, $casilla, $e_mail, $pagina_web, $carga, $usuario);
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    public function update_comercializadora() {
        $sess = $this->session->userdata();
        if (in_array('informante', $sess['permisos'])) {
            $id = $this->input->post('id');
            $depto = $this->input->post('depto');
            $ciudad = $this->input->post('ciudad');
            $nit = $this->input->post('nit');
            $regine = $this->input->post('regine');
            $descripcion = $this->input->post('descripcion');
            $nombre_informante = $this->input->post('nombre_informante');
            $direccion = $this->input->post('direccion');
            $numero = $this->input->post('numero');
            $entre_calles = $this->input->post('entre_calles');
            $edificio = $this->input->post('edificio');
            $piso = $this->input->post('piso');
            $oficina = $this->input->post('oficina');
            $zona = $this->input->post('zona');
            $referencia = $this->input->post('referencia');
            $telefono = $this->input->post('telefono');
            $fax = $this->input->post('fax');
            $casilla = $this->input->post('casilla');
            $e_mail = $this->input->post('e_mail');
            $pagina_web = $this->input->post('pagina_web');
            $carga = $this->input->post('carga');
            $justificacion = $this->input->post('justificacion');
            $usuario = $this->session->userdata('usuario');
            echo $this->Informante->update_comercializadora($id, $depto, $ciudad, $nit, $regine, $descripcion, $nombre_informante, $direccion, $numero, $entre_calles, $edificio, $piso, $oficina, $zona, $referencia, $telefono, $fax, $casilla, $e_mail, $pagina_web, $carga, $justificacion, $usuario);
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    public function discard_comercializadora() {
        $sess = $this->session->userdata();
        if (in_array('informante', $sess['permisos'])) {
            $id = $this->input->post('id');
            $justificacion = $this->input->post('justificacion');
            $usuario = $this->session->userdata('usuario');
            echo $this->Informante->discard_comercializadora($id, $justificacion, $usuario);
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    public function manufacturado_form() {
        $sess = $this->session->userdata();
        if (in_array('informante', $sess['permisos'])) {
            $id = $this->input->post('id');
            $id_informador = $this->input->post('id_informador');
            if ($id != FALSE) {
                $producto = $this->Informante->get_producto($id);
                $values['codigo'] = $producto['codigo'];
                $values['producto'] = $producto['producto'];
                $values['especificacion'] = $producto['especificacion'];
                $values['cantidad_inicial'] = $producto['cantidad_inicial'];
                $values['unidad_inicial'] = $producto['unidad_inicial'];
                $values['factor_ajuste'] = $producto['factor_ajuste'];
                $values['cantidad_a_cotizar'] = $producto['cantidad_a_cotizar'];
                $values['unidad_a_cotizar'] = $producto['unidad_a_cotizar'];
                $values['cantidad_equivalente'] = $producto['cantidad_equivalente'];
                $values['unidad_convencional'] = $producto['unidad_convencional'];
                $values['unidad_talla_peso'] = $producto['unidad_talla_peso'];
                $values['marca'] = $producto['marca'];
                $values['modelo'] = $producto['modelo'];
                $values['envase'] = $producto['envase'];
                $values['origen'] = $producto['origen'];
                $values['procedencia'] = $producto['procedencia'];
                $values['id'] = $id;
                $values['id_informador'] = $id_informador;
            } else {
                $values['codigo'] = '';
                $values['producto'] = '';
                $values['especificacion'] = '';
                $values['cantidad_inicial'] = '';
                $values['unidad_inicial'] = '';
                $values['factor_ajuste'] = '';
                $values['cantidad_a_cotizar'] = '';
                $values['unidad_a_cotizar'] = '';
                $values['cantidad_equivalente'] = '';
                $values['unidad_convencional'] = '';
                $values['unidad_talla_peso'] = '';
                $values['marca'] = '';
                $values['modelo'] = '';
                $values['envase'] = '';
                $values['origen'] = '';
                $values['procedencia'] = '';
                $values['id'] = $id;
                $values['id_informador'] = $id_informador;
            }
            $this->load->view('canasta/manufacturado_form', $values);
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    public function insert_prod_man() {
        $sess = $this->session->userdata();
        if (in_array('informante', $sess['permisos'])) {
            $codigo = $this->input->post('codigo');
            $producto = $this->input->post('producto');
            $especificacion = $this->input->post('especificacion');
            $cantidad_a_cotizar = $this->input->post('cantidad_a_cotizar');
            $unidad_a_cotizar = $this->input->post('unidad_a_cotizar');
            $cantidad_equivalente = $this->input->post('cantidad_equivalente');
            $unidad_convencional = $this->input->post('unidad_convencional');
            $unidad_talla_peso = $this->input->post('unidad_talla_peso');
            $marca = $this->input->post('marca');
            $modelo = $this->input->post('modelo');
            $envase = $this->input->post('envase');
            $origen = $this->input->post('origen');
            $procedencia = $this->input->post('procedencia');
            $id_informador = $this->input->post('id_informador');
            $usuario = $sess['login'];
            echo $this->Producto->insert_prod_man($codigo, $producto, $especificacion, $cantidad_a_cotizar, $unidad_a_cotizar, $cantidad_equivalente, $unidad_convencional, $unidad_talla_peso, $marca, $modelo, $envase, $origen, $procedencia, $id_informador, $usuario);
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    public function update_prod_man() {
        $sess = $this->session->userdata();
        if (in_array('informante', $sess['permisos'])) {
            $id = $this->input->post('id');
            $codigo = $this->input->post('codigo');
            $producto = $this->input->post('producto');
            $especificacion = $this->input->post('especificacion');
            $factor_ajuste = $this->input->post('factor_ajuste');
            $cantidad_a_cotizar = $this->input->post('cantidad_a_cotizar');
            $unidad_a_cotizar = $this->input->post('unidad_a_cotizar');
            $cantidad_equivalente = $this->input->post('cantidad_equivalente');
            $unidad_convencional = $this->input->post('unidad_convencional');
            $unidad_talla_peso = $this->input->post('unidad_talla_peso');
            $marca = $this->input->post('marca');
            $modelo = $this->input->post('modelo');
            $envase = $this->input->post('envase');
            $origen = $this->input->post('origen');
            $procedencia = $this->input->post('procedencia');
            $justificacion = $this->input->post('justificacion');
            $usuario = $sess['login'];
            echo $this->Producto->update_prod_man($id, $codigo, $producto, $especificacion, $factor_ajuste, $cantidad_a_cotizar, $unidad_a_cotizar, $cantidad_equivalente, $unidad_convencional, $unidad_talla_peso, $marca, $modelo, $envase, $origen, $procedencia, $justificacion, $usuario);
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    public function discard_prod_man() {
        $sess = $this->session->userdata();
        if (in_array('informante', $sess['permisos'])) {
            $id = $this->input->post('id');
            $justificacion = $this->input->post('justificacion');
            echo $this->Producto->discard_prod_man($id, $justificacion, $sess['login']);
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    public function fusion_prod_man() {
        $sess = $this->session->userdata();
        if (in_array('informante', $sess['permisos'])) {
            $id = $this->input->post('id');
            $id_informador = $this->input->post('id_informador');
            $justificacion = $this->input->post('justificacion');
            $usuario = $sess['login'];
            echo $this->Producto->fusion_prod_man($id, $id_informador, $justificacion, $usuario);
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    public function sol_comercializadoras() {
        $sess = $this->session->userdata();
        if (in_array('informante', $sess['permisos'])) {
            $data['title'] = 'Solicitudes';
            $this->load->view('templates/header', $sess);
            if ($estado = $this->input->post('estado')) {
                $data['solicitudesprod'] = $this->Producto->get_sol_manufacturados($sess['login'], $estado);
            } else {
                $data['solicitudesprod'] = $this->Producto->get_sol_manufacturados($sess['login']);
            }
            $this->load->view('canasta/sol_comercializadoras', $data);
            $this->load->view('templates/footer');
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function aprobar_manufacturados() {
        $sess = $this->session->userdata();
        if (in_array('aprobar', $sess['permisos'])) {
            $data['title'] = 'Solicitudes';
            $this->load->view('templates/header', $sess);
            if ($estado = $this->input->post('estado')) {
                $data['solicitudesprod'] = $this->Producto->get_sol_manufacturados('%', $estado);
            } else {
                $data['solicitudesprod'] = $this->Producto->get_sol_manufacturados();
            }
            $this->load->view('canasta/aprobar_manufacturados', $data);
            $this->load->view('templates/footer');
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function manufacturado_edit_form() {
        $sess = $this->session->userdata();
        if (in_array('informante', $sess['permisos'])) {
            $id = $this->input->post('id');
            $producto = $this->Producto->get_producto_sol($id);
            if ($producto['accion'] == 'EDITAR') {
                $values = $producto;
                $values['id'] = $id;
                $this->load->view('canasta/manufacturado_edit_form', $values);
            } else {
                echo 'Acci&oacute;n no admitida.';
            }
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    public function update_sol_man() {
        $sess = $this->session->userdata();
        if (in_array('informante', $sess['permisos'])) {
            $id = $this->input->post('id_producto_sol');
            $codigo = $this->input->post('codigo');
            $producto = $this->input->post('producto');
            $especificacion = $this->input->post('especificacion');
            $factor_ajuste = $this->input->post('factor_ajuste');
            $cantidad_a_cotizar = $this->input->post('cantidad_a_cotizar');
            $unidad_a_cotizar = $this->input->post('unidad_a_cotizar');
            $cantidad_equivalente = $this->input->post('cantidad_equivalente');
            $unidad_convencional = $this->input->post('unidad_convencional');
            $unidad_talla_peso = $this->input->post('unidad_talla_peso');
            $marca = $this->input->post('marca');
            $modelo = $this->input->post('modelo');
            $envase = $this->input->post('envase');
            $origen = $this->input->post('origen');
            $procedencia = $this->input->post('procedencia');
            $justificacion = $this->input->post('justificacion');
            $usuario = $sess['login'];
            echo $this->Producto->update_sol_man($id, $codigo, $producto, $especificacion, $factor_ajuste, $cantidad_a_cotizar, $unidad_a_cotizar, $cantidad_equivalente, $unidad_convencional, $unidad_talla_peso, $marca, $modelo, $envase, $origen, $procedencia, $justificacion, $usuario);
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    public function aprobar() {
        $sess = $this->session->userdata();
        if (in_array('informante', $sess['permisos'])) {
            $id = $this->input->post('id');
            echo $this->Producto->aprobar_prod($id, $sess['login']);
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    public function rechazar() {
        $sess = $this->session->userdata();
        if (in_array('informante', $sess['permisos'])) {
            $id = $this->input->post('id');
            $comentario = $this->input->post('comentario');
            echo $this->Producto->rechazar_prod($id, $comentario, $sess['login']);
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    public function image() {
        $sess = $this->session->userdata();
        if (in_array('informante', $sess['permisos'])) {
            $data['title'] = 'Imagen';
            $this->load->view('templates/header', $sess);
            $data['id'] = $this->input->get('id');
            $data['orig'] = $this->input->get('orig');
            $this->load->view('canasta/image', $data);
            $this->load->view('templates/footer');
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
}