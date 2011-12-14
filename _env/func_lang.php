<?

    function getLanguage() {   
        
        global $tmp_sess_object, $cfg;

        $lang = $tmp_sess_object->getVar('lang');

        if(!$lang) {
            return $cfg['page']['defaultlang'];
        } else {
            return $lang;
        }
    }

    function setLanguage($lang) {
        
        global $tmp_sess_object;

        $tmp_sess_object->setVar('lang',$lang);

        return true;

    }

    function translate($key) {

        $lang = getLanguage();

        require(dire.'_localize/' . $lang . '.php');

        if(@$language[$key]=='' || !@$language[$key])
            return $key;
            
        return @$language[$key];

    }

?>
