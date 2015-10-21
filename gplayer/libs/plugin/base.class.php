 <?php 
class Base {
	public $cache_dir ='cache/';	
	
	
    public function viewSource($url,$opt = array('sslverify' => true,'httpheader' =>true, 'follow' => true,'useheader' => true)) {
        $curl = curl_init();
		// var_dump($opt);
        $useragent = $_SERVER['HTTP_USER_AGENT'];;
        
        $header[0] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
        $header[] = "Accept-Language: en-us,en;q=0.5";
        $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
        $header[] = "Keep-Alive: 115";
        $header[] = "Connection: keep-alive";
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);   
		if($opt['useheader']==true){curl_setopt($curl, CURLOPT_HEADER, 1);}
        curl_setopt($curl, CURLOPT_USERAGENT, $useragent);
		if($opt['cookie'] !=""){curl_setopt($curl, CURLOPT_COOKIE, str_replace('\\"','"',$opt['cookie']));}
        if ($opt['httpheader'] == true) 
			curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		if ($opt['follow'] == true) 
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        if ($opt['sslverify'] == true) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        }
		if($opt['nobody']== true){curl_setopt($curl, CURLOPT_NOBODY, 1);}
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }
	public function get_url_direct($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        $response = curl_exec($ch);
        $resolved = curl_getinfo($ch);
        return $resolved['url'];
    }
}
