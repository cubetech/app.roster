<?

$cfg = array(
        'page'=>array(
            'title'=>"roster",
            'shortkey'=>"roster",
            'address'=>"localhost/roster",
            'pagecount'=>"3",
            'pagesteps'=>12,
            'autoinclude'=>'_env/addons/',
            'imagesize'=>(1024*1024*5),
        ),
        'mysql'=>array(
            'host'=>"localhost",
            'user'=>"roster",
            'password'=>"roster$",
            'db'=>"roster",
        ),
        'style'=>array(
            'id'=>"roster",
            'path'=>"_style",
        ),
        'auth'=>array(
            'timeout'=>60*60*24*30,
            'utimeout'=>60*60,
            'cookietimeout'=>60*60*24*30,
        ),
        'mail'=>array(
        	'from'=>"roster",
        	'fromaddress'=>"info@roster.ch",
        ),
);

?>
