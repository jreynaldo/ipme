<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Sqlite
 *
 * @author Alberto Daniel Inch Sáinz
 */
class Sqlite extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('Asignacion');
        $this->load->model('Usuario');
        $this->load->model('Informante');
        $this->load->model('Encuesta');
    }
    
    public function apk() {
        $pass = $this->input->post('password');
        if ($pass == 'kErf45Gwsp$nxAuQm') {
            $target_dir = getcwd().'/download';
            $file_name = '/'.basename($_FILES["file"]["name"]);
            if (!file_exists($target_dir)) {
                mkdir($target_dir);
            }
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_dir.$file_name)) {
                echo "Ok";
            } else {
                echo "Error.";
            }
        }
    }
    
    public function uploadapk() {
        $this->load->view('sqlite/uploadapk');
    }
    
    public function uploadzip() {
        $this->load->view('sqlite/uploadzip');
    }
    
    ///@brief Carga un SQLite comprimido desde Android.
    public function zip() {
        set_time_limit(12000);
        ini_set('MAX_EXECUTION_TIME', 12000);
        $usuario = $this->input->post('username');
        $pass = $this->input->post('password');
        $version = $this->input->post('version');
        $boleta = $this->input->post('boleta');
	
        if ($usuario) {
            $id = $this->Usuario->get_exists($usuario);
            if ($id > 0 && $pass == 'kErf45Gwsp$nxAuQm') {
                $proyecto = $this->Usuario->get_proyecto($usuario);
                if ($_FILES) {
                    $target_dir = getcwd().'/uploads/'.$usuario;
                    $file_name = '/'.basename($_FILES["file"]["name"]);
                    if (!file_exists($target_dir)) {
                        mkdir($target_dir);
                    }
                    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_dir.$file_name)) {
                        if ($version != '1.08') {
                            echo 'Debe actualizar la aplicación.';
                            return;
                        }
                        if ($boleta != $proyecto['version_boleta']) {
                            echo 'Debe actualizar las preguntas.';
                            return;
                        }
                        $zip = new ZipArchive;
                        $res = $zip->open($target_dir.$file_name);
                        if ($res === TRUE) {
                            $zip->extractTo($target_dir);
                            $zip->close();
                            $ids = $this->Asignacion->get_asignacion($id);
                            $bd = new SQLite3($target_dir.'/ipm');
                            $resenc = $bd->query("SELECT * FROM enc_encuesta WHERE id_asignacion IN($ids)");
                            $encuesta = Array();
                            while ($row = $resenc->fetchArray(SQLITE3_ASSOC)) {
                                $encuesta[] = $row;
                            }
                            $res = $this->Encuesta->consolidar(json_encode($encuesta));
                            if ($res == 'Ok') {
                                $resinf = $bd->query("SELECT id_asignacion, correlativo, id, cod, apiestado, usumod, fecmod FROM enc_informante WHERE id_asignacion IN($ids)");
                                $informante = Array();
                                while ($row = $resinf->fetchArray(SQLITE3_ASSOC)) {
                                    $informante[] = $row;
                                }
                                echo $this->Informante->consolidar(json_encode($informante));
                            }
                            $bd->close();
                        } else {
                            print_r('Error al descomprimir archivo.');
                        }
                    } else {
                        print_r('No ha podido abrir el archivo.');
                    }
                } else {
                    print_r('Debe enviar un archivo.');
                }
            } else {
                print_r('Usuario o contrase&ntilde;a incorrectos.');
            }
        } else {
            print_r('Parámetro incorrecto.');
        }
    }
    
    public function zip_manual() {
        $sess = $this->session->userdata();
        if (in_array('upload', $sess['permisos'])) {
            set_time_limit(12000);
            ini_set('MAX_EXECUTION_TIME', 12000);
            $usuario = $this->input->post('username');
            $boleta = $this->input->post('boleta');
            $gestion = $this->input->post('gestion');
            $periodo = $this->input->post('periodo');
            $id = $this->Usuario->get_exists($usuario);
            if ($id > 0) {
                if ($_FILES) {
                    $target_dir = getcwd().'/uploads/'.$usuario;
                    $file_name = '/'.basename($_FILES["file"]["name"]);
                    if (!file_exists($target_dir)) {
                        mkdir($target_dir);
                    }
                    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_dir.$file_name)) {
                        $zip = new ZipArchive;
                        $res = $zip->open($target_dir.$file_name);
                        if ($res === TRUE) {
                            $zip->extractTo($target_dir);
                            $zip->close();
                            $ids = $this->Asignacion->get_asignacion2($boleta, $id, $gestion, $periodo);
                            $bd = new SQLite3($target_dir.'/ipm');
                            $resenc = $bd->query("SELECT * FROM enc_encuesta WHERE id_asignacion IN($ids)");
                            $encuesta = Array();
                            while ($row = $resenc->fetchArray(SQLITE3_ASSOC)) {
                                $encuesta[] = $row;
                            }
                            $res = $this->Encuesta->consolidar(json_encode($encuesta));
                            if ($res == 'Ok') {
                                $resinf = $bd->query("SELECT id_asignacion, correlativo, id, cod, apiestado, usumod, fecmod FROM enc_informante WHERE id_asignacion IN($ids)");
                                $informante = Array();
                                while ($row = $resinf->fetchArray(SQLITE3_ASSOC)) {
                                    $informante[] = $row;
                                }
                                echo $this->Informante->consolidar(json_encode($informante));
                            }
                            $bd->close();
                        } else {
                            print_r('Error al descomprimir archivo.');
                        }
                    } else {
                        print_r('No ha podido abrir el archivo.');
                    }
                } else {
                    print_r('Debe enviar un archivo.');
                }
            } else {
                print_r('Usuario o contrase&ntilde;a incorrectos.');
            }
        } else {
            redirect(site_url().'/inicio/error', 'refresh');
        }
    }
}
