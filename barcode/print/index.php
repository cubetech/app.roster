<?php

    define('dire', '../../');
    include(dire . '_env/exec.php');
    
    require(dire . '_env/addons/php-barcode/php-barcode.php');
    require(dire . '_env/addons/fpdf17/fpdf.php');
    
    $id = vGET('id');
    
    $query = mysql_query('SELECT * FROM `barcode` WHERE `id`="'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $barcode = mysql_fetch_array($query);
    
    $pdf = new FPDF($cfg['pdf']['orientation'], 'mm', array($cfg['pdf']['width'], $cfg['pdf']['height']));

    $pdf->SetDisplayMode( 50 );
    $pdf->AddPage();
    
    ob_start();
    barcode_print($barcode['barcode']);
    $source = ob_get_contents();
    ob_end_clean();
    
    $image = $cfg['page']['tmpfolder'] . '/' . time() . rand(100000,999999) . '.png';
    $dpi = 100;
    
    $resource = imagecreatefromstring($source);
    $resource = imagerotate($resource, 90, 0);
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

    $pdf->Output( 'barcode.pdf', 'I');
    
    unlink($image);

?>