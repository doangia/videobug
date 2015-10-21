<?php
    
class HomeController extends Controller {
    public function index() {
    	// $dt = 
    	 $dt = $this->db->select("videos");
        $this->view($dt);
    }
    
    public function setting()
    {
    	
    }

    public function form($id=0)
    {
    	$dt['id'] = $id;
    	$dt['title'] = '';
    	if(is_numeric($id) && $id>0){
    		$video = $this->db->select("videos","id=$id");
    		$links = $this->db->select("videos_list","id=$id");
    		$dt['title'] = $video[0]['title'];
    		$dt['id'] = $video[0]['id'];
    		$dt['links'] = $links;
    	}
    	 $this->view($dt);
    }
    public function save()
    {
    	 $video['id'] = $this->post('id');
    	 $video['title'] = $this->post('title');
    	 $t = time();
    	 $video['url'] = base_convert($t, 10, 36);
    	 $links = $this->post('link');
    	 if($video['id']==0)
    	 {
    	 	$this->db->insert("videos",$video);
    	 	$id = $this->db->lastInsertId();
    	 	foreach ($links as $val) {
    	 		$link = array('id'=>$id,'link'=>$val,'name' =>$this->getdomain($val));
    	 		$this->db->insert("videos_list",$link);
    	 	}
    	 }
    	 elseif(is_numeric( $video['id']) &&  $video['id']>0)
    	 {
    	 	$this->db->update("videos",$video,"id=:id",array(':id'=>$video['id']));
    	 	$this->db->delete("videos_list","id=:id",array(':id'=>$video['id']));
    	 	foreach ($links as $val) {
    	 		$link = array('id'=>$video['id'],'link'=>$val,'name' =>$this->getdomain($val));
    	 		$this->db->insert("videos_list",$link);
    	 	}
    	 }
    	
    	 	$this->redirect();
    	 
    	 
    }
    public function delete($id=0)
    {
    	if($id >0){
    		$this->db->delete("videos_list","id=:id",array(':id'=>$id));
    		$this->db->delete("videos","id=:id",array(':id'=>$id));
    	}
    	$this->redirect();
    }
    private function getdomain($url='')
    {
    	$domain = str_ireplace('www.', '', parse_url($url, PHP_URL_HOST));
    	$domain = reset(explode(".", $domain));
    	return ucfirst($domain);
    }
}

?>