<?
    
    define('dire','../../');
    include(dire.'_env/exec.php');
    
    allowed();
    session_start();
    
        $file = vGET('document');
        $id = vGET('mid');
        $_SESSION['modtime'] = time();

        if(!$file['name']) {
            echo "ERROR: Transfer";
            die();
        }

        if(GetImageSize($file['tmp_name'])<1) {
            echo "ERROR: Falsches Format";
            die();
        }

        $uploadDir = dire.'_files/images/original/';
        $resizeDir = dire.'_files/images/thumbs/';

        $originalName = basename($file['name']);
        $newFileName = md5(basename($file['name']).microtime().rand(10000,99999)) . '.' . pathinfo(basename($file['name']), PATHINFO_EXTENSION);

        $uploadFile = $uploadDir . $newFileName;
        if(move_uploaded_file($file['tmp_name'], $uploadFile)) {
            mysql_query('INSERT INTO `files` (mid, name, originalname, size, modified, count) VALUES ("'.$id.'", "'.$newFileName.'", "'.$originalName.'", "'.$file['size'].'", "'.time().'", "0")') or sqlError(__FILE__,__LINE__,__FUNCTION__);
            $id = mysql_insert_id();
            $image = new SimpleImage();
            $image->load($uploadDir.$newFileName);
            $image->resizeToWidth(100);
            $image->save($resizeDir.$newFileName);
        }

        echo $id . "," . getSize($file['size']);
    
?>