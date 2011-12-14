<?

    function getInfo($info) {
        $url = 'http://rho.4teamwork.ch/ispAPI.php?function=' . $info;
        if( !extension_loaded('curl') ){
            die('Activate CURL');
        }
        // init curl
        $curlHandle = curl_init();

        // options
        curl_setopt($curlHandle, CURLOPT_URL, $url);
        // set the url to fetch
        curl_setopt($curlHandle, CURLOPT_HEADER, 0);
        // set headers (0 = no headers in result)
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1); // type of transfer (1 = to string)
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, 1); // time to wait in seconds
        $content = curl_exec($curlHandle);
        // make the call
        curl_close($curlHandle);
        // close the connection
        return $content;
    }

?>