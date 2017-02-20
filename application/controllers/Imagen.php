<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Imagen
 *
 * @author Alberto Daniel Inch Sáinz
 */
class Imagen extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('Fotografia');
    }
    
    public function image1() {
        $prod = $this->input->get('id');
        $img = $this->Fotografia->get_imagen1($prod);
        header('Content-type: image');
        echo base64_decode($img);
    }
    
    public function image2() {
        $prod = $this->input->get('id');
        $img = $this->Fotografia->get_imagen2($prod);
        header('Content-type: image');
        echo base64_decode($img);
    }
    
    public function upload_image() {
        $sess = $this->session->userdata();
        if (in_array('informante', $sess['permisos'])) {
            $prod = $this->input->post('id');
            $img1 = $this->input->post('img1');
            $img2 = $this->input->post('img2');
            if ($prod && ($img1 || $img2)) {
                if ($img1 && $img2) {
                    echo $this->Fotografia->imagenes($prod, $img1, $img2);
                } else {
                    if ($img1) {
                        echo $this->Fotografia->imagenes($prod, $img1, null);
                    } else {
                        echo $this->Fotografia->imagenes($prod, null, $img2);
                    }
                }
            } else {
                echo 'No realizó ningún cambio.';
            }
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
    
    public function temp1() {
        $prod = $this->input->get('id');
        $img = $this->Fotografia->get_temp1($prod);
        header('Content-type: image');
        echo base64_decode($img);
    }
    
    public function temp2() {
        $prod = $this->input->get('id');
        $img = $this->Fotografia->get_temp2($prod);
        header('Content-type: image');
        echo base64_decode($img);
    }
    
    public function justificativo() {
        $sess = $this->session->userdata();
        if (in_array('informante', $sess['permisos'])) {
            $gestion = $this->input->get('gestion');
            $semana = $this->input->get('semana');
            $origen = $this->input->get('origen');
            $codigo = $this->input->get('codigo');
            $img = $this->Fotografia->get_justificativo($gestion, $semana, $sess['id_departamento'], $origen, $codigo);
            header('Content-type: image');
            echo base64_decode($img);
        } else {
            echo 'Acceso denegado.';
        }
    }
    
    public function upload_justificativo() {
        $sess = $this->session->userdata();
        if (in_array('informante', $sess['permisos'])) {
            $gestion = $this->input->post('gestion');
            $mes = $this->input->post('mes');
            $semana = $this->input->post('semana');
            $origen = $this->input->post('origen');
            $codigo = $this->input->post('codigo');
            $img1 = $this->input->post('img');
            echo $this->Fotografia->justificativo($gestion, $mes, $semana, $sess['id_departamento'], $origen, $codigo, $img1);
        } else {
            echo 'Acceso denegado.';
        }
    }
}
