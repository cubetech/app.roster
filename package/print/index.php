<?php

    define('dire', '../../');
    include(dire . '_env/exec.php');
    
    require(dire . '_env/addons/php-barcode/php-barcode.php');
    require(dire . '_env/addons/fpdf17/fpdf.php');
    
    $id = vGET('id');
    
    $packageitem = array();
    $query = mysql_query('SELECT pi.*,
                                i.name as name
                                FROM `packageitem` pi
                                LEFT JOIN
                                `item` i ON pi.item_id = i.id
                                WHERE pi.package_id="'.$id.'"
                                AND pi.back="0"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    while($fetch=mysql_fetch_array($query))
        array_push($packageitem, $fetch);
        
    $query = mysql_query('SELECT * FROM `package` WHERE `id`="'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $package = mysql_fetch_array($query);
    
    $pdf = new FPDF();

    $pdf->SetDisplayMode( 50 );
    $pdf->AddPage();
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(0,40);
    $pdf->Ln();
    $pdf->Cell(110);
    $pdf->Cell(0,10,$package['customer']);
    $pdf->Ln();
    $pdf->Cell(110);
    $pdf->Cell(0,10,$package['person']);
    $pdf->Ln();
    $pdf->Cell(0,10);
    $pdf->Ln();
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(0,20,'Ausleihquittung Paket-ID ' . $id);
    $pdf->Ln();
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(40,7,'ID');
    $pdf->Cell(40,7,'Name');
    $pdf->Ln();
    $pdf->SetFont('Arial','',12);
    foreach($packageitem as $pi) {
        $pdf->Cell(40,7,$pi['item_id']);
        $pdf->Cell(40,7,$pi['name']);
        $pdf->Ln();
    }
    
    $pdf->Output( 'package.pdf', 'I');
    
    unlink($image);

?>