<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pdf
{
    protected $dompdf;
    
    public function __construct()
    {
        require_once APPPATH . 'third_party/dompdf/autoload.inc.php';
        $this->dompdf = new Dompdf\Dompdf();
    }
    
    public function loadHtml($html)
    {
        $this->dompdf->loadHtml($html);
    }
    
    public function setPaper($size, $orientation)
    {
        $this->dompdf->setPaper($size, $orientation);
    }
    
    public function render()
    {
        $this->dompdf->render();
    }
    
    public function stream($filename, $options)
    {
        $this->dompdf->stream($filename, $options);
    }
}
?>