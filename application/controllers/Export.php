<?php

/**
 * Description of Export
 *
 * @author Alberto Daniel Inch Sáinz
 */
class Export extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('Csv');
        $this->load->model('Asignacion');
        if (count($this->session->userdata()) == 1) {
            $this->session->set_userdata(Array('activo' => false));
        }
    }
    
    public function index() {
        $serie = $this->input->get('serie');
        $fila = $this->Csv->get_registrado($serie);
        
        $version = $this->input->get('version');
        /*if ($version != '1.0') {
            echo 'Debe actualizar la aplicación.';
            return;
        }*/
        
        if ($fila != NULL) {
            $proy = $fila['id_proyecto'];
            $file = tempnam('', 'zip');
            $zip = new ZipArchive();

            $zip->open($file, ZipArchive::OVERWRITE);

            $zip->addFromString('seg_proyecto', $this->Csv->seg_proyecto());
            $zip->addFromString('seg_usuario', $this->Csv->seg_usuario($proy));
            $zip->addFromString('seg_grupo', $this->Csv->seg_grupo());
            $zip->addFromString('seg_permiso', $this->Csv->seg_permiso());
            $zip->addFromString('seg_boleta', $this->Csv->seg_boleta($proy));
            
            $zip->addFromString('enc_tipo', $this->Csv->enc_tipo());
            $zip->addFromString('enc_nivel', $this->Csv->enc_nivel());
            $zip->addFromString('enc_seccion', $this->Csv->enc_seccion($proy));
            $zip->addFromString('enc_pregunta', $this->Csv->enc_pregunta($proy));
            $zip->addFromString('enc_respuesta', $this->Csv->enc_respuesta($proy));
            $zip->addFromString('enc_flujo', $this->Csv->enc_flujo($proy));
            $zip->addFromString('enc_regla', $this->Csv->enc_regla($proy));
            
            $zip->close();

            header('Content-Type: application/zip');
            header('Content-Length: '.filesize($file));
            header('Content-Disposition: attachment; filename="CSV_'.$serie.'.zip"');
            readfile($file);
            unlink($file);
            exit();
        } else {
            echo 'decisionMessage:crearUsuario:Movil no registrado!:¿Desea crearlo?';
        }
    }
    
    public function asignacion() {
        $serie = $this->input->get('serie');
        $tipo = $this->input->get('tipo');
        $fila = $this->Csv->get_registrado($serie);
        
        $version = $this->input->get('version');
        /*if ($version != '1.0') {
            echo 'Debe actualizar la aplicación.';
            return;
        }*/
        
        $periodo = $this->input->get('periodo');
        if ($periodo == '') {
            $periodo = NULL;
        } else {
            if (strlen($periodo) <> 6) {
                $periodo = NULL;
            } else {
                $periodo = substr($periodo, 0, 4).'-'.substr($periodo, 4, 2);
            }
        }
        
        if ($fila != NULL) {
            $usuario = $fila['id_usuario'];
            $file = tempnam('', 'zip');
            $zip = new ZipArchive();

            $zip->open($file, ZipArchive::OVERWRITE);
            
            $this->Asignacion->set_exportado($usuario);

            $zip->addFromString('seg_informador', $this->Csv->seg_informador($usuario, $tipo, $periodo));
            $zip->addFromString('seg_asignacion', $this->Csv->seg_asignacion($usuario, $tipo, $periodo));
            
            $zip->addFromString('enc_informante', $this->Csv->enc_informante($usuario, $tipo, $periodo));
            $zip->addFromString('enc_encuesta', $this->Csv->enc_encuesta($usuario, $tipo, $periodo));
            
            $zip->addFromString('cotizacion', $this->Csv->cotizacion($usuario, $tipo, $periodo));
            
            $zip->close();

            header('Content-Type: application/zip');
            header('Content-Length: '.filesize($file));
            header('Content-Disposition: attachment; filename="CSV_'.$serie.'.zip"');
            readfile($file);
            unlink($file);
            exit();
        } else {
            echo 'Movil no registrado.';
        }
    }
}
