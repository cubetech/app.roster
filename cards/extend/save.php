<?

    define('dire','../../');
    include(dire.'_env/exec.php');
    include(dire.'_fpdf/fpdf.php');
    
    $id = vGET('id');
    if(!$id)
        error('transfer');
        
    $price = vGET('price');
    $payed = vGET('payed');
    $date = vGET('date');
    $_date = explode('.', $date);
    $timestamp = mktime(0, 0, 0, $_date[0], $_date[1], $_date[2]);
    $invoice = vGET('invoice');
    $image = vGET('image');
    
    class PDF extends FPDF {
        function Header() {
            $this->Image(dire.'_files/images/thumbs/d84c0956f63526ee32ef62541ef2bd24.jpg',10,8,33);
            $this->SetFont('Arial','B',15);
            $this->Cell(80);
            $this->Cell(30,10,'Titel',1,0,'C');
            $this->Ln(20);
        }
        function Footer() {
            $this->SetY(-15);
            $this->SetFont('Arial','I',10);
            $this->Cell(0,10,'Seite '.$this->PageNo().'/{nb}',0,0,'C');
        }
    }
    
    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(0,10,'Dies ist ein Testtext',0,1);
    $pdf->Output();
    
    var_dump($_POST);
    
    write_header('hallo');
    
    write_footer();

?>