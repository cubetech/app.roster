<?

    function getDomain($id) {
        $query = mysql_query('SELECT * FROM `domain` WHERE `id`="'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
        $result = mysql_fetch_array($query);
        return $result['domain'];
    }

?>