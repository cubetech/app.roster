<?php

    define('dire', '../../');
    include(dire . '_env/exec.php');
    
    require(dire . '_env/addons/php-barcode/php-barcode.php');
    require(dire . '_env/addons/fpdf17/fpdf.php');
    
    $pdf = new FPDF($cfg['pdf']['orientation'], 'mm', array($cfg['pdf']['width'], $cfg['pdf']['height']));

    $pdf->SetDisplayMode( 50 );
    $pdf->AddPage();
    
    ob_start();
    barcode_print('888888888888');
    $source = ob_get_contents();
    ob_end_clean();
    
    $image = '/tmp/' . time() . rand(100000,999999) . '.png';
    $dpi = 72;
    
    $resource = imagecreatefromstring($source);
    imagepng($resource, $image);

    $size = @getimagesize($image);
    

    // $k = $dpi / 2.54;     // bei cm
    // $k = $dpi;         // bei inch

    $k = $dpi / 25.4; // bei mm

    $w = $pdf->w * $k; // get page width pixel
    $h = $pdf->h * $k; // get page height pixel

    $x = (($w/2) - ($size[0]/2)) / $k; // set x-pos page mm
    $y = (($h/2) - ($size[1]/2)) / $k; // set y-pos page mm

    $pdf->Image($image, $x, $y, $size[0] * 25.4 / $dpi); 

    $pdf->Output( 'pdf.pdf', 'I');

?>