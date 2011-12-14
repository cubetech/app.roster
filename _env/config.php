<?

$cfg = array(
        'page'=>array(
            'title'=>"iMembr",
            'shortkey'=>"iMembr",
            'address'=>"www.imembr.ch",
            'defaultlang'=>"de",
            'pagecount'=>"3",
            'pagesteps'=>12,
            'stockidlength'=>"4",
            'imgpath'=>"_files/images/original/", // Format: _files/images/original/ (without slashes in front and end of the string)
            'imgthumbpath'=>"_files/images/thumbs/", // Format: _files/images/thumbs/ (without slashes in front and end of the string)
        ),
        'mysql'=>array(
            'host'=>"localhost",
            'user'=>"cubetech",
            'password'=>"cubedev$",
            'db'=>"imembr",
        ),
        'style'=>array(
            'id'=>"imembr",
            'path'=>"_style",
            'expired'=>"darkviolet",
            'expiresoon'=>"darkred",
            'expire'=>"red",
        ),
        'auth'=>array(
            'timeout'=>60*60*24*30,
            'utimeout'=>60*60,
            'cookietimeout'=>60*60*24*30,
        ),
        'languages'=>array(
            'de',
            'fr',
            'it',
            'en',
        ),
);

?>
