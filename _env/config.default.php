<?

$cfg = array(
        'page'=>array(
            'title'=>"workr",
            'shortkey'=>"workr",
            'address'=>"localhost/workr",
            'pagecount'=>"3",
            'pagesteps'=>12,
        ),
        'mysql'=>array(
            'host'=>"localhost",
            'user'=>"workr",
            'password'=>"workr$",
            'db'=>"workr",
        ),
        'style'=>array(
            'id'=>"workr",
            'path'=>"_style",
        ),
        'auth'=>array(
            'timeout'=>60*60*24*30,
            'utimeout'=>60*60,
            'cookietimeout'=>60*60*24*30,
        ),
        'mail'=>array(
        	'from'=>"workr",
        	'fromaddress'=>"info@workr.ch",
        ),
);

?>
