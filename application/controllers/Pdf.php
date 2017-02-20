<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Reporte
 *
 * @author Alberto Daniel Inch Sáinz
 */
class Pdf extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('session');
        $this->load->library('Pdf1');
        $this->load->library('Pdf2');
        ob_clean();
        $this->load->model('Producto');
        if (count($this->session->userdata()) == 1) {
            $this->session->set_userdata(Array('activo' => false, 'permisos' => Array()));
        }
    }
    
    public function catalogo() {
        set_time_limit(12000);
        ini_set('MAX_EXECUTION_TIME', 12000);
        $tipo = $this->input->get('tipo');
        $depto = $this->input->get('depto');
        
        // create new PDF document
        if ($tipo === '1') {
            $pdf = new Pdf1(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        } else {
            $pdf = new Pdf2(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        }

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('INE');
        $pdf->SetTitle('Catalogo');

        // set default header data
        if ($tipo === 1) {
            $pdf->SetHeaderData('../../../../../img/ipm.png', 10, '', 'Índice de Precios al por Mayor – Catálogo de Productos (Agrícolas)');
        } else {
            $pdf->SetHeaderData('../../../../../img/ipm.png', 10, '', 'Índice de Precios al por Mayor – Catálogo de Productos (Manufacturados)');
        }

        // set header and footer fonts
        $pdf->setHeaderFont(Array('aefurat', 'I', 11));
        $pdf->setFooterFont(Array('helvetica', '', 11));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(15, 27, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 25);

        // set image scale factor
        $pdf->setImageScale(1.25);
        
        // set font
        $pdf->SetFont('times', '', 11);

        $agricolas = $this->Producto->get_productos_pdf($tipo, $depto);
        // Body ----------------------------------------------------
        for ($i = 0; $i < count($agricolas); $i++) {
            // add a page
            if ($i % 2 == 0) {
                $pdf->AddPage();
            }
            $html = '<table border="1">';
                $html .= '<tr>';
                    $html .= '<td width="140">';
                        $html .= '<b>CODIGO IPM</b>';
                    $html .= '</td>';
                    $html .= '<td width="320">'.$agricolas[$i]['id_producto'].'</td>';
                    $html .= '<td width="180" rowspan="14" align="center">';
                        $html .= '<table border="1"><tr><td>';
                    if ($agricolas[$i]['img1'] != null) {
                        $html .= '<img src="data:image/gif;base64,'.$agricolas[$i]['img1'].'"/>';
                    }
                        $html .= '</td></tr><tr><td>';
                    if ($agricolas[$i]['img2']) {
                        $html .= '<img src="data:image/gif;base64,'.$agricolas[$i]['img2'].'"/>';
                    }
                        $html .= '</td></tr></table>';
                    $html .= '</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                    $html .= '<td>';
                        $html .= '<b>DEPARTAMENTO</b>';
                    $html .= '</td>';
                    $html .= '<td>'.$agricolas[$i]['departamento'].'</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                    $html .= '<td>';
                        $html .= '<b>CODIGO</b>';
                    $html .= '</td>';
                    $html .= '<td>'.$agricolas[$i]['codigo'].'</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                    $html .= '<td>';
                        $html .= '<b>PRODUCTO</b>';
                    $html .= '</td>';
                    $html .= '<td>'.$agricolas[$i]['producto'].'</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                    $html .= '<td>';
                        $html .= '<b>ESPECIFICACION</b>';
                    $html .= '</td>';
                    $html .= '<td>'.$agricolas[$i]['especificacion'].'</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                    $html .= '<td>';
                        $html .= '<b>Unidad Talla Peso</b>';
                    $html .= '</td>';
                    $html .= '<td>'.$agricolas[$i]['unidad_talla_peso'].'</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                    $html .= '<td>';
                        $html .= '<b>Marca</b>';
                    $html .= '</td>';
                    $html .= '<td>'.$agricolas[$i]['marca'].'</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                    $html .= '<td>';
                        $html .= '<b>Modelo</b>';
                    $html .= '</td>';
                    $html .= '<td>'.$agricolas[$i]['modelo'].'</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                    $html .= '<td>';
                        $html .= '<b>Cantidad a Cotizar</b>';
                    $html .= '</td>';
                    $html .= '<td>'.$agricolas[$i]['cantidad_a_cotizar'].'</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                    $html .= '<td>';
                        $html .= '<b>Equivalencia</b>';
                    $html .= '</td>';
                    $html .= '<td>'.$agricolas[$i]['equivalencia'].'</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                    $html .= '<td>';
                        $html .= '<b>Envase</b>';
                    $html .= '</td>';
                    $html .= '<td>'.$agricolas[$i]['envase'].'</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                    $html .= '<td>';
                        $html .= '<b>Origen</b>';
                    $html .= '</td>';
                    $html .= '<td>'.$agricolas[$i]['origen'].'</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                    $html .= '<td>';
                        $html .= '<b>Procedencia</b>';
                    $html .= '</td>';
                    $html .= '<td>'.$agricolas[$i]['procedencia'].'</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                    $html .= '<td>';
                        $html .= '<b>Informante</b>';
                    $html .= '</td>';
                    $html .= '<td>'.$agricolas[$i]['descripcion'].'</td>';
                $html .= '</tr>';
            $html .= '</table>';
            $pdf->writeHTML($html, true, false, true, false, '');
        }

        // ---------------------------------------------------------

        //Close and output PDF document
        $pdf->Output('Catalogo.pdf', 'I');
    }
}