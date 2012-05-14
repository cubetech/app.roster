<?php

    define('dire', '../../');
    include(dire . '_env/exec.php');
    
    require(dire . '_env/addons/Barcode39/Barcode39.php');
    require(dire . '_env/addons/fpdf17/fpdf.php');
    
    $id = vGET('id');
    
    if(isset($id) && $id) { 
    
        $query = mysql_query('SELECT * FROM `barcode` WHERE `id`="'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
        $barcode = mysql_fetch_array($query);
    
    }
    
    if(isset($barcode) && $barcode) {
    
        $pdf = new FPDF($cfg['pdf']['small']['orientation'], 'mm', array($cfg['pdf']['small']['width'], $cfg['pdf']['small']['height']));

    } else {
            
        $pdf = new FPDF($cfg['pdf']['big']['orientation'], 'mm', array($cfg['pdf']['big']['width'], $cfg['pdf']['big']['height']));
    
    }

    $pdf->SetDisplayMode( 50 );
    $pdf->AddPage();
    
    function createImg($rotate=false, $code=false) {
    
        global $cfg;
    
        if(!$code) {
            $code = code13code();
        }
    
        $image = $cfg['page']['tmpfolder'] . '/' . time() . rand(100000,999999) . '.gif';
        
        $bc = new Barcode39($code);
        $bc->draw($image);
            
        if($rotate) {
            $img = imagecreatefromgif($image);
            $rotate = imagerotate($img, 90, 0);
            imagegif($rotate, $image);
        }
    
        $size = getimagesize($image);
    
        return array('img'=>$image, 'size'=>$size);
        
    }
    
    $dpi = 100;
    $k = $dpi / 25.4; // bei mm

    $w = $pdf->w * $k; // get page width pixel
    $h = $pdf->h * $k; // get page height pixel
    
    if(isset($barcode) && $barcode) {
    
        $img = createImg(true, $barcode['barcode']);
        $x = (($w/2) - ($img['size'][0]/2)) / $k; // set x-pos page mm
        $y = (($h/2) - ($img['size'][1]/2)) / $k; // set y-pos page mm
        $pdf->Image($img['img'], $x, 0);
        
    } else {
    
        for($i=0;$i<=9;($i++)) {
        
            $pos = 15+$i*27;
            
            $img = createImg();
            $pdf->Image($img['img'], 31, $pos); 
            unlink($img['img']);
            
            $img = createImg();
            $pdf->Image($img['img'], 120, $pos); 
            unlink($img['img']);
        
        }
        
    }
    #$pdf->Image($image, $x, $y, $size[0] * 25.4 / $dpi); 

    $pdf->Output( 'barcode.pdf', 'I');

?>