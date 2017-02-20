/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH."/libraries/tcpdf/tcpdf.php";

class Pdf2 extends TCPDF
{
    function __construct()
    {
        //parent::__construct();
        call_user_func_array('parent::__construct', func_get_args());
    }
    
    //Page header
    public function Header() {
        $image_ipm = $_SERVER['DOCUMENT_ROOT'].'ipm/img/ipm.png';
        $this->Image($image_ipm, 185, 5, 15, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $image_ine = $_SERVER['DOCUMENT_ROOT'].'ipm/img/ine.png';
        $this->Image($image_ine, 10, 10, 25, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $this->SetFont('times', 'I', 9);
        $this->Cell(0, 15, 'Índice de Precios al por Mayor – Catálogo de Productos (Manufacturados)', 0, false, 'C', 0, '', 0, false, 'T', 'M');
        $this->Line(40, 20, 180, 20);
        $this->Line(40, 20.6, 180, 20.6);
        $this->Line(40, 20.7, 180, 20.7);
        $this->Line(40, 20.8, 180, 20.8);
        $this->Line(40, 20.9, 180, 20.9);
        $this->Line(40, 21, 180, 21);
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('times', 'I', 9);
        $this->Cell(0, 10, 'Instituto Nacional de Estadística', 0, false, 'C', 0, '', 0, false, 'T', 'M');
        $this->SetFont('times', 'I', 11);
        $this->Cell(0, 10, $this->getAliasNumPage(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
        $this->Line(30, 290, 190, 290);
    }
}

/* End of file Pdf.php */
/* Location: ./application/libraries/Pdf.php */