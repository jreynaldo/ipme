<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ccod_codificar
 *
 * @author Alberto Daniel Inch Sáinz
 */
class Mapa extends CI_Controller {
    //put your code here
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->model('Informante');
    }
    
    public function index()
    {
        $sess = $this->session->userdata();
        if (in_array('informante', $sess['permisos'])) {
            $this->load->view('templates/header', $sess);
            $data['title'] = 'Recorridos';
            $data['tipo'] = Array(1 => 'Mercado', 2 => 'Comercializadora');
            if ($this->input->get('t')) {
                $data['t'] = $this->input->get('t');
            } else {
                $data['t'] = 1;
            }
            $data['departamento'] = $this->Informante->get_departamento($sess['id_usuario']);
            $data['id_departamento'] = $sess['id_departamento'];
            $this->load->view('mapa/index', $data);
            $this->load->view('templates/footer');
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function mapa() {
        $sess = $this->session->userdata();
        if (in_array('informante', $sess['permisos'])) {
            $tipo = $this->input->post('tipo');
            $anio = $this->input->post('anio');
            $periodo = $this->input->post('periodo');
            $depto = $this->input->post('depto');
            $data['json'] = $this->Informante->get_json($tipo, $anio, $periodo, $depto);
            $this->load->view('mapa/mapa', $data);
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function punto() {
        $sess = $this->session->userdata();
        if (in_array('reporte', $sess['permisos'])) {
            $data['latitud'] = $this->input->post('latitud');
            $data['longitud'] = $this->input->post('longitud');
            $this->load->view('mapa/punto', $data);
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
}