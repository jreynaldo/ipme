<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('session');
        $this->load->model('Usuario');
        $this->load->model('Informante');
        $this->load->model('Producto');
        if (count($this->session->userdata()) == 1) {
            $this->session->set_userdata(Array('activo' => false, 'permisos' => Array()));
        }
    }

    public function index() {
        $sess = $this->session->userdata();
        $data['title'] = 'Inicio';
        if (in_array('operativo', $sess['permisos'])) {
            $pendientes = $this->Producto->cambios_pendientes($sess['id_usuario']);
            if (count($pendientes) > 0) {
                $data['pendientes'] = '<table class="table table-advance table-bordered tbl">';
                $data['pendientes'] .= '<tr><th>Producto</th><th>Especificación</th><th></th>';
                foreach ($pendientes as $pendiente) {
                    $data['pendientes'] .= '<tr><td>' . $pendiente['codigo'] . '</td><td>' . $pendiente['descripcion'] . '</td><td>';
                    $data['pendientes'] .= '<button title="Editar" class="btn btn-circle btn-primary" onclick="editar(' . $pendiente['id'] . ')"><i class="fa fa-edit"></i></button></td></tr>';
                }
                $data['pendientes'] .= '</table>';
            }
        }
        $this->load->view('templates/header', $sess);
        $this->load->view('inicio/index', $data);
        $this->load->view('templates/footer');
    }

    public function error() {
        $sess = $this->session->userdata();
        $data['title'] = 'Error';
        $this->load->view('templates/header', $sess);
        $this->load->view('inicio/error', $data);
        $this->load->view('templates/footer');
    }

    public function login_form() {
        $this->load->view('inicio/login_form');
    }

    public function login() {
        $login = $this->input->post('login');
        $pass = $this->input->post('pass');
        $sess = $this->Usuario->login($login, $pass);
        if ($sess == null) {
            echo 'Usuario o contraseña Incorrectos';
        } else {
            $sess['permisos'] = $this->Usuario->permisos($login);
            $this->session->set_userdata($sess);
            echo 'Ok';
        }
    }

    public function usuario() {
        $sess = $this->session->userdata();
        $data['title'] = 'Usuario';
        $this->load->view('templates/header', $sess);
        $this->load->view('inicio/usuario', $data);
        $this->load->view('templates/footer');
    }

    public function cambiar_form() {
        if ($this->session->userdata('activo') == true) {
            $this->load->view('inicio/cambiar_form');
        } else {
            echo 'Usuario no encontrado.';
        }
    }

    public function cambiar() {
        if ($this->session->userdata('activo') == true) {
            $pass = $this->input->post('pass');
            $passn = $this->input->post('passn');
            echo $this->Usuario->cambiar($this->session->userdata('login'), $pass, $passn);
        } else {
            echo 'Usuario no encontrado.';
        }
    }

    public function cerrar() {
        session_destroy();
        redirect(site_url(), 'refresh');
    }

    public function crear() {
        $login = $this->input->post('login');
        $pass = $this->input->post('password');
        $carnet = $this->input->post('carnet');
        $nombre = $this->input->post('nombre');
        $paterno = $this->input->post('paterno');
        $materno = $this->input->post('materno');
        $direccion = $this->input->post('direccion');
        $telefono = $this->input->post('telefono');
        $departamento = $this->input->post('departamento');
        $serie = $this->input->post('serie');
        echo $this->Usuario->crear($login, $pass, $carnet, $nombre, $paterno, $materno, $direccion, $telefono, $departamento, $serie);
    }

    public function administrar_usuario() {
        $sess = $this->session->userdata();
        $query = $this->input->get('query', TRUE);
        $estado = $this->input->get('estado', TRUE);
        $dep = $this->input->get('dep', TRUE);
        $s_login = $this->input->get('s_login', TRUE);
        $s_nombre = $this->input->get('s_nombre', TRUE);
        $s_apellido_p = $this->input->get('s_apellido_p', TRUE);
        $s_apellido_m = $this->input->get('s_apellido_m', TRUE);

        if (in_array('usuario', $sess['permisos'])) {
            $data['title'] = 'Usuarios';
              $data['busqueda'] = $query;
            $data['estado'] = $estado;
            $data['dep'] = $dep;
            $data['s_login'] = $s_login;
            $data['s_nombre'] = $s_nombre;
            $data['s_apellido_p'] = $s_apellido_p;
            $data['s_apellido_m'] = $s_apellido_m;
            

            $this->load->view('templates/header', $sess);
            $data['usuarios'] = $this->Usuario->get_usuarios($sess['id_usuario'], $estado,$dep,$s_login,$s_nombre,$s_apellido_p,$s_apellido_m);
            $this->load->view('inicio/administrar_usuario', $data);
            $this->load->view('templates/footer');
        } else {
            redirect(site_url() . '/inicio/error', 'refresh');
        }
    }

    public function usuario_form() {
        $sess = $this->session->userdata();
        if (in_array('usuario', $sess['permisos'])) {
            $data['estado'] = Array('t' => 'Activo', 'f' => 'Inactivo');
            $data['departamentos'] = $this->Informante->get_departamento($sess['id_usuario']);
            $data['grupos'] = $this->Usuario->get_grupos($sess['id_usuario']);
            $data['id'] = $this->input->post('id');
            if ($data['id'] == -1) {
                $data['login'] = '';
                $data['activo'] = 'f';
                $data['carnet'] = '';
                $data['nombre'] = '';
                $data['paterno'] = '';
                $data['materno'] = '';
                $data['direccion'] = '';
                $data['telefono'] = '';
                $data['id_departamento'] = '';
                $data['id_grupo'] = '';
                $data['id_proyecto'] = $sess['id_proyecto'];
                $data['serie'] = '';
            } else {
                $usuario = $this->Usuario->get_usuario($data['id']);
                $data['login'] = $usuario['login'];
                $data['activo'] = $usuario['activo'];
                $data['carnet'] = $usuario['carnet'];
                $data['nombre'] = $usuario['nombre'];
                $data['paterno'] = $usuario['paterno'];
                $data['materno'] = $usuario['materno'];
                $data['direccion'] = $usuario['direccion'];
                $data['telefono'] = $usuario['telefono'];
                $data['id_departamento'] = $usuario['id_departamento'];
                $data['id_grupo'] = $usuario['id_grupo'];
                $data['id_proyecto'] = $usuario['id_proyecto'];
                $data['serie'] = $usuario['serie'];
            }
            $this->load->view('inicio/usuario_form', $data);
        } else {
            echo 'Permiso denegado.';
        }
    }

    public function insert_usuario() {
        $sess = $this->session->userdata();
        if (in_array('usuario', $sess['permisos'])) {
            $login = $this->input->post('login');
            $activo = $this->input->post('activo');
            $carnet = $this->input->post('carnet');
            $nombre = $this->input->post('nombre');
            $paterno = $this->input->post('paterno');
            $materno = $this->input->post('materno');
            $direccion = $this->input->post('direccion');
            $telefono = $this->input->post('telefono');
            $id_departamento = $this->input->post('id_departamento');
            $id_grupo = $this->input->post('id_grupo');
            $id_proyecto = $this->input->post('id_proyecto');
            $serie = $this->input->post('serie');
            echo $this->Usuario->insert_usuario($login, $activo, $carnet, $nombre, $paterno, $materno, $direccion, $telefono, $id_departamento, $id_grupo, $id_proyecto, $serie, $sess['login']);
        } else {
            echo 'Permiso denegado.';
        }
    }

    public function update_usuario() {
        $sess = $this->session->userdata();
        if (in_array('usuario', $sess['permisos'])) {
            $id_usuario = $this->input->post('id_usuario');
            $login = $this->input->post('login');
            $activo = $this->input->post('activo');
            $carnet = $this->input->post('carnet');
            $nombre = $this->input->post('nombre');
            $paterno = $this->input->post('paterno');
            $materno = $this->input->post('materno');
            $direccion = $this->input->post('direccion');
            $telefono = $this->input->post('telefono');
            $id_departamento = $this->input->post('id_departamento');
            $id_grupo = $this->input->post('id_grupo');
            $id_proyecto = $this->input->post('id_proyecto');
            $serie = $this->input->post('serie');
            echo $this->Usuario->update_usuario($id_usuario, $login, $activo, $carnet, $nombre, $paterno, $materno, $direccion, $telefono, $id_departamento, $id_grupo, $id_proyecto, $serie, $sess['login']);
        } else {
            echo 'Permiso denegado.';
        }
    }

}
