<html>
<head>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js"></script>
	<script src="http://content.jwplatform.com/libraries/ZLWskiS7.js"></script>
	<script type="text/javascript">//jwplayer.key="vmAEdu5OJSCiJfE3aWibJZ6338lN/A7tybduu0fdEfxYgi7AkWpjckRUFeI=";</script>
		
	<style>
	body{margin:0px;text-align:center;}
        html, body,#movie_box{height:100%;}
	</style>

</head>
<body>
<?php
register_shutdown_function('shut');
set_error_handler('handler');
function shut(){
    $error = error_get_last();
    if($error && ($error['type'] & E_FATAL)){
        handler();
    }

}
function decodelink($url)
{
	$u = strrev($url);
	return urldecode(base64_decode($u));
}
function handler( ) {
}
ob_start();
include('libs/'. 'gplayer.class.php');

 $link = isset($_GET['u']) ? $_GET['u']: '';  
  
 $poster = isset($_GET['poster']) ? $_GET['poster']: '';  
 $track = isset($_GET['track']) ? $_GET['track']: '';   
 $link = urldecode($link);
 $link = decodelink($link);
 $link = html_entity_decode($link);
 $poster = decodelink($poster);
 $track = decodelink($track);
 $gplayer = new Gplayer($link);
 if ($gplayer->checkUrl()) {

  $dt =  $gplayer->getlink();
  
  if(empty($dt))
  {
	echo "Video not found!";  
  }
  ksort($dt);
  	
	if ($autoplay == "true" || $autoplay == "on")
		$autoplay_attribute = 'true';
	else
		$autoplay_attribute = 'false';
	
	if($poster =='')
	{
		$poster = $dt['image'];
	}
	$source = '';
	 foreach($dt as $key=>$val) { 
	$res =($key <360?240:$key).'p';
	if($key !='image' && $key !='js') { 
		$source[] = "{ 'file' :'".$val['url']."',".
					"'label' : '".$res."',".
					"'type' : '". $val['type']."'}";
		}
	}
	$src = implode(",",$source);
	$src = "[$src]";
  ?>
  <div id="movie_box_wrapper"></div>
<div id="movie_box"></div>
					<script>
					
						jwplayer("movie_box").setup({
						 
						  'image': '<?php echo $poster ?>',						 						 
						 'width': '100%',
						  <?php if($track !='') { ?>
						  'tracks':
							[
								{ 'file': '<?php echo $track ?>' }
							],
							 captions: {
								color: '#ff0077',
								fontsize:14,
								backgroundColor:'#ffffff',
								backgroundOpacity: 0
							},
						  <?php } ?>											  
						  'sources': <?php echo $src ?>						  
						});					
						
					
					</script>
					
<?php } 
ob_end_flush();
?>
</body>
</html>