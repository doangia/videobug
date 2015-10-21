 <?php 
 require "base.class.php";
class gvideo extends Base {
	private $url;
	
	public function __construct($url) {
		$this->url = $url;
	}
	
	public function getlink()
	{
		$res = null;
		switch($this->checkUrl()){
            case 'plus':
                $res = $this->gplus();
                break;
            case 'picasaweb':
                 $res = $this->gpicasa();
                break;
			case 'drive':
                 $res = $this->gdrive();
                break;
			case 'photos':
                 $res = $this->gphotos();
                break;
            case false:
                return false;
                break;
            default:
                return false;
        }
		return $res;
	}
	public function checkUrl(){
        if($this->url != ''){
            if(preg_match('#plus.google.com#i',$this->url)){
                $type = 'plus';
            }elseif(preg_match('#picasaweb.google.com#i',$this->url)){
                $type = 'picasaweb';
            }elseif(preg_match('#drive.google.com#i',$this->url)){
                $type = 'drive';
            }elseif(preg_match('#photos.google.com/share#i',$this->url)){
                $type = 'photos';
            }else{
                $type = false;
            }
        }else{
            $type = false;
        }
    
        
        return $type;
    }    
    private function get_Plus_json($url) {       
        $content = $this->viewSource($url);        
        $t = explode(" OZ_afsid", $content);
        $t = explode("'", $t[1]);
        $sid = str_replace("'", "", $t[0]);
        $sid = str_replace("=", "", $sid);
        $sid = trim($sid);
        $t = explode("OZ_buildLabel", $content);
        $t = explode("'", $t[1]);
        $ozv = str_replace("'", "", $t[0]);
        $ozv = str_replace("=", "", $ozv);
        $ozv = trim($ozv);
        $urls = parse_url($url);
        $reqid = substr(time(),  - 6);
        parse_str($urls['query'], $info);
        // var_dump($urls);
        if (isset($info['oid']) && isset($info['pid'])) {
            $oid = preg_replace('/[^\-\d]*(\-?\d*).*/', '$1', $info['oid']);
            $pid = preg_replace('/[^\-\d]*(\-?\d*).*/', '$1', $info['pid']);
        }
        elseif(preg_match("#photos/(\d+)/albums/(\d+)/(\d+)#i", $url, $info2)) {
            $oid = $info2[1];
            $pid = $info2[3];
        }
        $urljson = "https://plus.google.com/_/photos/lightbox/photo/$oid/$pid?soc-app=2&cid=0&soc-platform=1&hl=en&ozv=$ozv&avw=phst%3A31&f.sid=$sid&_reqid=$reqid&rt=j";
        if ($info['authkey'] != "") {
            $urljson .= "&authkey=".$info['authkey'];
        }
		// echo $urljson;
        $json = $this->viewSource($urljson);
		if($json !='')
			file_put_contents($this->cache_dir . md5($url), $json);
		return $json;
    }
   
    private function replace_json($link) {
        $datars = str_replace("\u003d", "=", $link);
        $datarss = str_replace("\u0026", "&", $datars);
        return $datarss;
    }
    private function gplus() {
		$url = $this->url;
		// $url = str_replace('–','--',$url);
        if (file_exists($this->cache_dir . md5($url)) && (filemtime($this->cache_dir . md5($url)) > (time() - 60 * 60))) {
            $json = file_get_contents($this->cache_dir . md5($url));
        }
        else {
            $json = $this->get_Plus_json($url);
        }
        $list = array();
        $json = str_replace(")]}'", "", $json);
        
        $arr = array();
        if (preg_match('#\["(http[^"]*)",(\d+),(\d+)\]#i', $json, $arr)) {
            $list['image'] = '';
        }
        $arr = array();
        if (preg_match_all('#\[(\d+),(\d+),(\d+),"(http[^"]*)"\]#i', $json, $arr)) {
            
            for ($i = 0; $i < count($arr[0]); $i++) {
                $list[$arr[3][$i]] = array('url' => $this->get_url_direct($this->replace_json($arr[4][$i])),
										   'type' =>'video/mp4');
            }
        }
        return $list;
    }
	private function gpicasa() {
		$url = $this->url;
		$url = str_replace('–','--',$url);
		$list = array();
		if (stristr($url, '#')) list($url, $id) = explode('#', $url);
	$data = $this->viewSource($url);
	// var_dump($data);
	if($id) $gach = explode($id, $data);
	// var_dump($gach);
	$gach = explode('{"url":"', ($id)?end($gach):$data);
	
	 $v360p = urldecode(reset(explode('"', $gach[2])));
	if(strpos($v360p, 'redirector') || strpos($v360p, '=m18')) {
		$v720p = urldecode(reset(explode('"', $gach[3])));
		$v1080p = urldecode(reset(explode('"', $gach[4])));
    	if(strpos($v1080p, 'redirector') || strpos($v1080p, '=m32')){
			$list[360] = array('url'=>$v360p,'type'=>'video/mp4');
			$list[720] = array('url'=>$v720p,'type'=>'video/mp4');
			$list[1080] = array('url'=>$v1080p,'type'=>'video/mp4');
    		// $js .= '360p=>'.$v360p.'|720p=>'.$v720p.'|1080p=>'.$v1080p;
    	} elseif(strpos($v720p, 'redirector') || strpos($v720p, '=m22')){
			$list[360] = array('url'=>$v360p,'type'=>'video/mp4');
			$list[720] = array('url'=>$v720p,'type'=>'video/mp4');
    		// $js .= '360p=>'.$v360p.'|720p=>'.$v720p;
    	} elseif(strpos($v360p, 'redirector') || strpos($v360p, '=m18')) {
    		// $js .= '360p=>'.$v360p;
			$list[360] = array('url'=>$v360p,'type'=>'video/mp4');			
    	}
	} else {
		preg_match('/https:\/\/picasaweb.google.com\/(.*)\/(.*)#(.*?)/U', $link, $id);
		$s = explode('?', $id[2]);
		if($s[1]) {
			$get = $this->viewSource('https://picasaweb.google.com/data/entry/tiny/user/'.$id[1].'/photoid/'.$id[3].'?'.$s[1].'');
		} else {
			$get = $this->viewSource('https://picasaweb.google.com/data/entry/tiny/user/'.$id[1].'/photoid/'.$id[3].'');
		}
		preg_match_all("/<media:content url='(.*)' height='(.*)' width='(.*)' type='(.*)'\/>/U", $get, $data);
		foreach($data[2] as $i => $quality) {
			if($quality	> '1' and $quality <= '360' and $data[4][$i] == 'video/mpeg4') {
                // $js .= '360p=>'.$data[1][$i].'|';
				$list[360] = array('url'=>$data[1][$i],'type'=>'video/mp4');
			
			} elseif($quality > '360' and $quality <= '720' and $data[4][$i] == 'video/mpeg4') {
                // $js .= '720p=>'.$data[1][$i].'|';
				$list[720] = array('url'=>$data[1][$i],'type'=>'video/mp4');
			} elseif($quality > '720' and $quality <= '1080' and $data[4][$i] == 'video/mpeg4') {
                // $js .= '1080p=>'.$data[1][$i].'|';
				$list[1080] = array('url'=>$data[1][$i],'type'=>'video/mp4');
			}
		}
	}
	$list['image'] ='';
	// return rtrim(str_replace('&amp;', '&',$js), '|');
		return $list;
	}
	private function gdrive() {
		$list = array();
		$url = $this->url;
		if (file_exists($this->cache_dir . md5($url)) && (filemtime($this->cache_dir . md5($url)) > (time() - 60 * 60))) {
			$text = file_get_contents($this->cache_dir . md5($url));
		}
		else {
			$text = $this->viewSource($url);
			file_put_contents($this->cache_dir . md5($url), $text);
		}
		// $text = $this->viewSource($url);
		$info = explode('["url_encoded_fmt_stream_map","',$text);
		$info = explode('"]',$info[1]);
		$json = json_decode('{"media":"'.$info[0].'"}');
		$q = explode('["fmt_list","',$text);
		$q = explode('"]',$q[1]);
		$q = explode(',',$q[0]);
		
		$itag = array();
		foreach($q as $val)
		{
			$tmp = array();
			if(preg_match("#(\d+)/(\d+)x(\d+)/#i",$val,$tmp))
			{
				
				$itag[$tmp[1]] = $tmp[3];
			}
		}
		// var_dump($itag);
		foreach(explode(",",$json->media) as $val)
		{
			parse_str($val,$info );
			// var_dump($info);
			if(strpos($info['type'],'video/mp4') !== false)
				$list[$itag[$info['itag']]] = array('url' => $info['url'],'type' => $info['type']) ;
		}
		// return false;
		
		
		$t = explode('"og:image" content="',$text);
		$t = explode('"',$t[1]);
		$list['image'] = $t[0];
		
		
		return $list;
	}
	private function gphotos() {
		$list = array();
		$itag = array(5=>240,
			  17 =>144,
			  18=>360,
			  22=>720,
			  34 =>360,
			  36=>240,
			  37=>1080,
			  38=>3072,
			  43=>360,
			  44=>480,
			  45=>720,
			  46 =>1080,
			  82 =>360,
			  84 =>720,
			  100 =>360,
			  102=>720);
		$url = $this->url;
		if (file_exists($this->cache_dir . md5($url)) && (filemtime($this->cache_dir . md5($url)) > (time() - 60 * 60))) {
			$text = file_get_contents($this->cache_dir . md5($url));
		}
		else {
			$text = $this->viewSource($url);
			file_put_contents($this->cache_dir . md5($url), $text);
		}
		$info = explode('"url\u003d',$text);
		
		$info = explode('"]',$info[1]);
		// echo $info[0];
		$media = json_decode('{"data":"url\u003d'.$info[0].'"}',true);
		$data = urldecode($media["data"]);
		if(preg_match_all('#url=([^;]*)&itag=([^;]*)&type=([^;]*);#mi',$data,$m)){
			// var_dump($m);
			foreach($m[1] as $key=>$url)
			{
				$list[$itag[$m[2][$key]]] = array('url' => $url,'type'=> $m[3][$key]);
			}
		}
		return $list;
	}
}
