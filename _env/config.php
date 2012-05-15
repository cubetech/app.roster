<?php

$cfg = array(
        'page'=>array(
            'title'=>"roster",
            'shortkey'=>"roster",
            'address'=>"localhost/roster",
            'pagecount'=>"3",
            'pagesteps'=>12,
            'autoinclude'=>'_env/addons/',
            'imagesize'=>(1024*1024*5),
            'tmpfolder'=>'/tmp',
        ),
        'mysql'=>array(
            'host'=>getenv('MYSQL_DB_HOST'),
            'user'=>getenv('MYSQL_USERNAME'),
            'password'=>getenv('MYSQL_PASSWORD'),
            'db'=>getenv('MYSQL_DB_NAME'),
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
        'pdf'=>array(
            'big'=>array(
                'orientation'=>"P", // L für Querformat, P für Hochformat
                'width'=>210,
                'height'=>293,
            ),
            'small'=>array(
                'orientation'=>"P", // L für Querformat, P für Hochformat
                'width'=>36,
                'height'=>91,
            ),
        ),
);


if($_SERVER['HTTP_HOST'] != 'lager.ticketpark.ch'){
	include_once('config.local.php');
}//if

?>
