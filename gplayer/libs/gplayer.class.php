 <?php 
class Gplayer {
	private $url;
	private $cache_dir ='cache/';	
	protected $setting;
	public function __construct($url) {
		$this->url = $url;
		include('setting.php');
		
		$this->setting = $setting;
	}
	
	public function getlink()
	{
		$res = null;
		if($plugin = $this->checkUrl() )
		{
			// echo $plugin;
			if($plugin=="dirrect")
			{
				 $res['360'] = array('url' => $this->url,'type'=>'video/mp4');
				 return $res;
			}
			
			include 'plugin/'.$plugin.'.php';
			if (!class_exists( $plugin)) {
				trigger_error("Unable to load class: $class", E_USER_WARNING);
				
			}
			else{				
				$c = new $plugin($this->url);
				$res = $c->getlink();
				
			}			
				
		}
		
		return $res;
	}
	public function checkUrl(){
		 $type = false;
		
        if($this->url != ''){
			foreach($this->setting as $setting){ 
				$type = false;
				if(preg_match('#'.$setting['domain'].'#i',$this->url)){
					return $type = $setting['plugin'];
					
				}
			}
			$type='dirrect';
			
        }else{
            $type = false;
        } 
       
        return $type;
    }	
    private function viewSource($url,$opt = array('sslverify' => true,'httpheader' =>true, 'follow' => true,'useheader' => true)) {
        $curl = curl_init();
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
        if ($opt['httpheader'] == true) 
			curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		if ($opt['follow'] == true) 
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        if ($opt['sslverify'] == true) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        }
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }
}
