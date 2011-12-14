<?
    
    function getPreference($pref) {
        $query = mysql_query('SELECT * FROM preferences WHERE `id`="'.$pref.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
        $preference = mysql_fetch_array($query);

        return $preference['text'];
    }

    function getRegion($code) {
        $reg = array(
                "ag"=>'AG - Aargau',
                "ap"=>'AR/AI - Appenzell',
                "be"=>'BE - Bern',
                "bl"=>'BL - Basel Land',
                "bs"=>'BS - Basel Stadt',
                "fl"=>'F&uuml;rstentum Liechtenstein',
                "fr"=>'FR - Fribourg',
                "ge"=>'GE - Genf',
                "gl"=>'GL - Glarus',
                "gr"=>'GR - Graub&uuml;nden',
                "ju"=>'JU - Jura',
                "lu"=>'LU - Luzern',
                "ne"=>'NE - Neuchatel',
                "nw"=>'NW - Nidwalden',
                "ow"=>'OW - Obwalden',
                "sg"=>'SG - St.Gallen',
                "sh"=>'SH - Schaffhausen',
                "so"=>'SO - Solothurn',
                "sz"=>'SZ - Schwyz',
                "tg"=>'TG - Thurgau',
                "ti"=>'TI - Ticino',
                "ur"=>'UR - Uri',
                "vd"=>'VD - Vaud',
                "zh"=>'ZH - Z&uuml;rich',
                "zg"=>'ZG - Zug',
                );

        $return = @$reg[$code];
        if(!$return || $return=='')
            $return = 'unbekannte Region';
        
        return $return;
    }

    function make_thumb($img_src, $img_width = 1024, $des_src, $img_height = false, $quali = 90) {
        ini_set("memory_limit",-1);
        ini_set("max_execution_time",-1);
        $end     = substr($img_src, -5);
        $end     = stristr($end,'.');
        if(stristr($end,'gif')) {
         $im = imagecreatefromgif($img_src);
        } elseif(stristr($end,'png')) {
         $im = imagecreatefrompng($img_src);
        } else {
         $im = imagecreatefromjpeg($img_src);
        }
        list($src_width, $src_height) = getimagesize($img_src);
        if(!$img_height) {
        if($src_width >= $src_height) {
            $new_image_width = $img_width;
            $new_image_height = $src_height / $src_width * $img_width;
        }
        if($src_width < $src_height) {
            $new_image_height = $img_width;
            $new_image_width = $src_width / $src_height * $img_width;
        }
        } else {
            $new_image_width = $img_width;
            $new_image_height = $img_height;
        }
        $new_image = imagecreatetruecolor($new_image_width, $new_image_height);
        imagecopyresampled($new_image, $im, 0, 0, 0, 0, $new_image_width,$new_image_height, $src_width, $src_height);
        if(stristr($end,'gif')) {
        imagegif($new_image, $des_src, $quali);
        } elseif(stristr($end,'png')) {
        imagepng($new_image, $des_src);
        } else {
        imagejpeg($new_image, $des_src, $quali);
        }
        imagedestroy($new_image);
    }

    function rotate($img_src, $rotate=90, $quali=90, $des_src=false) {
        if($des_src==false) {
         $des_src = $img_src;
        }
        ini_set("memory_limit",-1);
        ini_set("max_execution_time",-1);
        $im = imagecreatefromjpeg($img_src);
        $rotim = imagerotate($im, 360 - $rotate, 0);
        imagejpeg($rotim, $des_src, $quali);
        imagedestroy($rotim);
    }

    function watermark($image, $watermark, $save_as) {
        $Grafik = ImageCreateFromJPEG($image);
        $Wasserzeichen = ImageCreateFromPNG($watermark);

        ImageCopy($Grafik, $Wasserzeichen, imagesx($Grafik)-imagesx($Wasserzeichen), imagesy($Grafik)-imagesy($Wasserzeichen), 0, 0, imagesx($Wasserzeichen), imagesy($Wasserzeichen));

        imagejpeg($Grafik, $save_as);
    }

?>
