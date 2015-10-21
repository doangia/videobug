<?php    
class EmbedController extends Controller {
    public function index($url ='') {
    	$dt = $this->db->select('videos',"url=:url",array(':url'=>$url));
    	$model = array();
    	if($dt)
    	{
    		$id = $dt[0]['id'];
    		$this->meta->title = $dt[0]['title'];
    		$links = $this->db->select('videos_list','id=:id',array(':id'=>$id));
    		// var_dump($links);
    		$json_data = array();
    		$json_data[] = array('s'=> 'Subtitles','u' =>'');
    		$json_data[] = array('s'=> 'image','u' =>'');
    		$json_data[] = array('s'=> 'JS','u' =>'');
    		$json_data[] = array('s'=> 'ADV','u' =>'');
    		foreach ($links as $key=>$val) {
    			if($val['name']=='Picasaweb')
    			{
    				$json_data[] = array('s'=> 'Picasaweb','u' => $this->encodelink($val['link']));
    				unset($links[$key]);
    			}
    		}
    		foreach ($links as $key => $val) {
    			$json_data[] = array('s'=> $val['name'],'u' => $this->encodelink($val['link']));
    		}

    	$json = json_encode($json_data);
    	$model['json'] = $json;
    	}
    	ob_start();
        $this->partial($model);
      $rs = ob_get_clean();
        $rs = $this->stringToHex($rs);
     echo $str = <<<EOT
<script type="text/javascript">

	document.write(unescape('${rs}'));
</script>
EOT;
    }
    private function encodelink($url)
    {
    	$tmp = base64_encode($url);
    	return strrev($tmp);
    }

   private function stringToHex($string) {
    $hexString = '';
    for ($i=0; $i < strlen($string); $i++) {
        $hexString .= '%' . bin2hex($string[$i]);
    }
    return $hexString;
}
}
?>