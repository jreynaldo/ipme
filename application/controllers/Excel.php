<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Excel extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('Precio');
        $this->load->model('Informante');
        if (count($this->session->userdata()) == 1) {
            $this->session->set_userdata(Array('activo' => false));
        }
    }
    
    public function index() {
        header('content-type: text/xml');
        header('Content-Disposition: attachment; filename="prueba.xml"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0,pre-check=0');
        header('Pragma: public');
        
        $header['title'] = 'Prueba';
        $header['author'] = 'INE';
        $header['date'] = date('Y-m-dTh:m:sZ', time());
        $this->load->view('excel/header', $header);
        $this->load->view('excel/prueba');
    }
    
    public function precios_agricolas_rep_detallado() {
        set_time_limit(12000);
        ini_set('MAX_EXECUTION_TIME', 12000);
        
        $sess = $this->session->userdata();
        if (in_array('reporte', $sess['permisos'])) {
            header('content-type: text/xml');
            header('Content-Disposition: attachment; filename="Listado Agricolas '.date('Y_m_dTh_m', time()).'.xml"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0,pre-check=0');
            header('Pragma: public');
            
            $header['title'] = 'Detallado de productos agrícolas';
            $header['author'] = 'INE';
            $header['date'] = date('Y-m-dTh:m:sZ', time());
            $this->load->view('excel/header', $header);
            
            $cod = $this->input->post('cod');
            $perini = $this->input->post('perini');
            $perfin = $this->input->post('perfin');
            
            if ($sess['nacional'] == 't') {
                $data['reporte'] = $this->Precio->get_reporte_agricolas_detallado($perini, $perfin, $cod);
            } else {
                $data['reporte'] = $this->Precio->get_reporte_agricolas_detallado($perini, $perfin, $cod, $sess['id_departamento']);
            }
            
            $this->load->view('excel/precios_agricolas_rep_detallado', $data);
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function precios_agricolas_rep_horizontal() {
        set_time_limit(12000);
        ini_set('MAX_EXECUTION_TIME', 12000);
        
        $sess = $this->session->userdata();
        if (in_array('reporte', $sess['permisos'])) {
            header('content-type: text/xml');
            header('Content-Disposition: attachment; filename="Precios Agricolas '.date('Y_m_dTh_m', time()).'.xml"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0,pre-check=0');
            header('Pragma: public');
            
            $header['title'] = 'PRECIOS DETALLADOS POR COTIZACIÓN';
            $header['author'] = 'INE';
            $header['date'] = date('Y-m-dTh:m:sZ', time());
            $this->load->view('excel/header', $header);
            
            $cod = $this->input->post('cod');
            $perini = $this->input->post('perini');
            $perfin = $this->input->post('perfin');
            
            $data['periodo'] = $this->Precio->get_semanas($perini, $perfin);
            if ($sess['nacional'] == 't') {
                $data['reporte'] = $this->Precio->get_reporte_agricolas_horizontal($perini, $perfin, $cod);
            } else {
                $data['reporte'] = $this->Precio->get_reporte_agricolas_horizontal($perini, $perfin, $cod, $sess['id_departamento']);
            }
            
            $this->load->view('excel/precios_agricolas_rep_horizontal', $data);
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function precios_manufacturados_rep_detallado() {
        set_time_limit(96000);
        ini_set('MAX_EXECUTION_TIME', 96000);
        
        $sess = $this->session->userdata();
        if (in_array('reporte', $sess['permisos'])) {
            header('content-type: text/xml');
            header('Content-Disposition: attachment; filename="Listado Manufacturados '.date('Y_m_dTh_m', time()).'.xml"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0,pre-check=0');
            header('Pragma: public');
            
            $header['title'] = 'Detallado de productos manufacturados';
            $header['author'] = 'INE';
            $header['date'] = date('Y-m-dTh:m:sZ', time());
            $this->load->view('excel/header', $header);
            
            $cod = $this->input->post('cod');
            $perini = $this->input->post('perini');
            $perfin = $this->input->post('perfin');
            
            if ($sess['nacional'] == 't') {
                $data['reporte'] = $this->Precio->get_reporte_manufacturados_detallado($perini, $perfin, $cod);
            } else {
                $data['reporte'] = $this->Precio->get_reporte_manufacturados_detallado($perini, $perfin, $cod, $sess['id_departamento']);
            }
            
            $this->load->view('excel/precios_manufacturados_rep_detallado', $data);
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function precios_manufacturados_rep_horizontal() {
        set_time_limit(12000);
        ini_set('MAX_EXECUTION_TIME', 12000);
        
        $sess = $this->session->userdata();
        if (in_array('reporte', $sess['permisos'])) {
            header('content-type: text/xml');
            header('Content-Disposition: attachment; filename="Precios Manufacturados '.date('Y_m_dTh_m', time()).'.xml"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0,pre-check=0');
            header('Pragma: public');
            
            $header['title'] = 'PRECIOS DETALLADOS POR COTIZACIÓN';
            $header['author'] = 'INE';
            $header['date'] = date('Y-m-dTh:m:sZ', time());
            $this->load->view('excel/header', $header);
            
            $cod = str_replace("'", "''", $this->input->post('cod'));
            $perini = $this->input->post('perini');
            $perfin = $this->input->post('perfin');
            
            $data['periodo'] = $this->Precio->get_meses($perini, $perfin);
            if ($sess['nacional'] == 't') {
                $data['reporte'] = $this->Precio->get_reporte_manufacturados_horizontal($perini, $perfin, $cod);
            } else {
                $data['reporte'] = $this->Precio->get_reporte_manufacturados_horizontal($perini, $perfin, $cod, $sess['id_departamento']);
            }
            
            $this->load->view('excel/precios_manufacturados_rep_horizontal', $data);
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function precios_manufacturados_rep_horizontal_unificado() {
        set_time_limit(12000);
        ini_set('MAX_EXECUTION_TIME', 12000);
        
        $sess = $this->session->userdata();
        if (in_array('reporte', $sess['permisos'])) {
            header('content-type: text/xml');
            header('Content-Disposition: attachment; filename="Precios Manufacturados '.date('Y_m_dTh_m', time()).'.xml"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0,pre-check=0');
            header('Pragma: public');
            
            $header['title'] = 'PRECIOS DETALLADOS POR COTIZACIÓN UNIDAD UNIFICADA';
            $header['author'] = 'INE';
            $header['date'] = date('Y-m-dTh:m:sZ', time());
            $this->load->view('excel/header', $header);
            
            $cod = str_replace("'", "''", $this->input->post('cod'));
            $perini = $this->input->post('perini');
            $perfin = $this->input->post('perfin');
            
            $data['periodo'] = $this->Precio->get_meses($perini, $perfin);
            if ($sess['nacional'] == 't') {
                $data['reporte'] = $this->Precio->get_reporte_manufacturados_horizontal_unificado($perini, $perfin, $cod);
            } else {
                $data['reporte'] = $this->Precio->get_reporte_manufacturados_horizontal_unificado($perini, $perfin, $cod, $sess['id_departamento']);
            }
            
            $this->load->view('excel/precios_manufacturados_rep_horizontal', $data);
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function georeferencia_manufacturados() {
        set_time_limit(12000);
        ini_set('MAX_EXECUTION_TIME', 12000);
        
        $sess = $this->session->userdata();
        if (in_array('reporte', $sess['permisos'])) {
            header('content-type: text/xml');
            header('Content-Disposition: attachment; filename="Georeferencia Manufacturados '.date('Y_m_dTh_m', time()).'.xml"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0,pre-check=0');
            header('Pragma: public');
            
            $header['title'] = 'GEOREFERENCIACIÓN';
            $header['author'] = 'INE';
            $header['date'] = date('Y-m-dTh:m:sZ', time());
            $this->load->view('excel/header', $header);
            
            $cod = str_replace("'", "''", $this->input->post('cod'));
            $perini = $this->input->post('perini');
            $perfin = $this->input->post('perfin');
            
            $data['periodo'] = $this->Precio->get_meses($perini, $perfin);
            if ($sess['nacional'] == 't') {
                $data['reporte'] = $this->Precio->get_reporte_manufacturados_geo($perini, $perfin, $cod);
            } else {
                $data['reporte'] = $this->Precio->get_reporte_manufacturados_geo($perini, $perfin, $cod, $sess['id_departamento']);
            }
            
            $this->load->view('excel/georeferencia_manufacturados', $data);
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function directorio_mercados() {
        $sess = $this->session->userdata();
        if (in_array('reporte', $sess['permisos'])) {
            header('content-type: text/xml');
            header('Content-Disposition: attachment; filename="Directorio Mercados.xml"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0,pre-check=0');
            header('Pragma: public');
            
            $header['title'] = 'DIRECTORIO MERCADOS';
            $header['author'] = 'INE';
            $header['date'] = date('Y-m-dTh:m:sZ', time());
            $this->load->view('excel/header', $header);
            
            $data['reporte'] = $this->Informante->get_directorio(1, $sess['id_usuario']);
            
            $this->load->view('excel/directorio_mercados', $data);
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function directorio_comercializadoras() {
        $sess = $this->session->userdata();
        if (in_array('reporte', $sess['permisos'])) {
            header('content-type: text/xml');
            header('Content-Disposition: attachment; filename="Directorio Comercializadoras.xml"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0,pre-check=0');
            header('Pragma: public');
            
            $header['title'] = 'DIRECTORIO COMERCIALIZADORAS';
            $header['author'] = 'INE';
            $header['date'] = date('Y-m-dTh:m:sZ', time());
            $this->load->view('excel/header', $header);
            
            $data['reporte'] = $this->Informante->get_directorio(2, $sess['id_usuario']);
            
            $this->load->view('excel/directorio_comercializadoras', $data);
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function encadenado() {
        set_time_limit(12000);
        ini_set('MAX_EXECUTION_TIME', 12000);
        
        $sess = $this->session->userdata();
        if (in_array('reporte', $sess['permisos'])) {
            header('content-type: text/xml');
            header('Content-Disposition: attachment; filename="Encadenado '.date('Y_m_dTh_m', time()).'.xml"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0,pre-check=0');
            header('Pragma: public');
            
            $header['title'] = 'ENCADENADOS';
            $header['author'] = 'INE';
            $header['date'] = date('Y-m-dTh:m:sZ', time());
            $this->load->view('excel/header', $header);
            
            $cod = str_replace("'", "''", $this->input->post('cod'));
            $perini = $this->input->post('perini');
            $perfin = $this->input->post('perfin');
            
            $data['periodo'] = $this->Precio->get_meses($perini, $perfin);
            if ($sess['nacional'] == 't') {
                $data['reporte'] = $this->Precio->get_reporte_encadenado($perini, $perfin, $cod);
            }
            
            $this->load->view('excel/encadenado', $data);
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
}