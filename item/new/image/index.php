<?php

    define('dire', '../../../');
    include(dire . '_env/exec.php');

    $path = dire . '_image/item/';
    $imagesize = $cfg['page']['imagesize'];
    
    $valid_formats = array("jpg", "png", "gif", "bmp","jpeg");
    
    if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {

        $name = $_FILES['photoimg']['name'];
        $size = $_FILES['photoimg']['size'];
                
        if(strlen($name)) {
            list($txt, $ext) = explode(".", $name);
            if(in_array($ext,$valid_formats)) {
                if($size<$imagesize) { // Image size max
                    $actual_image_name = time().".".$ext;
                    $tmp = $_FILES['photoimg']['tmp_name'];
                    if(move_uploaded_file($tmp, $path.$actual_image_name)) {
                        echo "<img src='". substr($path, 3) . $actual_image_name . "' class='preview'>";
                    } else {
                        echo '<div class="alert alert-error"><a class="close" data-dismiss="alert">x</a>Fehlgeschlagen!</div>';
                    }
                } else {
                    echo '<div class="alert alert-error"><a class="close" data-dismiss="alert">x</a>Maximale Dateigr&ouml;sse:<br />' . getSize($imagesize) . '</div>'; 
                }
            } else {
                echo '<div class="alert alert-error"><a class="close" data-dismiss="alert">x</a>Ung&uuml;ltiges Dateiformat! Nur Bilder sind erlaubt.</div>';
            }
        } else {
            echo '<div class="alert alert-error"><a class="close" data-dismiss="alert">x</a>Bitte ein Bild ausw&auml;hlen!</div>';
        }
        
        exit;
        
    } else {
        
        header('Location: ../');
    
    }
    
?>