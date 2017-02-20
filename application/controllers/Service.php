<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Canasta
 *
 * @author Alberto Daniel Inch Sáinz
 */
class Service extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Usuario');
        $this->load->model('Informante');
        $this->load->model('Fotografia');
        define('pass', 'kErf45Gwsp$nxAuQm');
    }
    
    ///@brief Actualiza las coordenadas del informante.
    ///@return Cadena HTML con las opciones de seleccion.
    public function coordenadas() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        if ($this->Usuario->get_exists($username) > 0 && $password == pass) {
            $id = $this->input->post('param1');
            $latitud = $this->input->post('param2');
            $longitud = $this->input->post('param3');
            echo $this->Informante->actualizar_coordenadas($id, $latitud, $longitud);
        } else {
            echo 'Permiso denegado.';
        }
    }
    
    ///@brief Actualiza las coordenadas del informante.
    ///@return Cadena HTML con las opciones de seleccion.
    public function upload_image() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        if ($this->Usuario->get_exists($username) > 0 && $password == pass) {
            $prod = $this->input->post('param1');
            $img1 = $this->input->post('param2');
            $img2 = $this->input->post('param3');
            if ($prod && ($img1 || $img2)) {
                if ($img1 && $img2) {
                    echo $this->Fotografia->temp($prod, $img1, $img2);
                } else {
                    if ($img1) {
                        echo $this->Fotografia->temp($prod, $img1, null);
                    } else {
                        echo $this->Fotografia->temp($prod, null, $img2);
                    }
                }
            } else {
                echo 'No realizó ningún cambio.';
            }
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
}