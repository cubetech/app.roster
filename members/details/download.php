<?

    define(dire, '../../');
    include(dire.'_env/exec.php');
    
    allowed();
    
    $id = vGET('id');
    if(!isset($id) || $id<1)
        error('transfer');
    
    $query = mysql_query('SELECT * FROM `files` WHERE `id`="'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $image = mysql_fetch_array($query);

    header("Content-Type: ".image_type_to_mime_type(exif_imagetype(dire.$cfg['page']['imgpath'].$image['name'])));
    header("Content-Disposition: attachment; filename=".$image['originalname']);
    header('Content-Length: '.$image['size']);

    readfile(dire.$cfg['page']['imgpath'].$image['name']);
    exit;

?>