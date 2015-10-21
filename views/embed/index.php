<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo $this->meta->title; ?></title>
	<style type="text/css">body{ font: 12px "Myriad Pro", "Lucida Grande", "sans-serif"; padding: 0;  margin: 0;}#adplayer{position: absolute;z-index: 999999999;top: 0px;left: 0px;width: 100%;height: 100%;background-color:#000;text-align:center;}#adplayer .closebtt{display: inline-block;background: rgb(204, 204, 204) none repeat scroll 0% 0%;color: black;width: 300px;cursor: pointer;font-size: 18px;text-decoration: none;line-height: 30px;text-align: center;border-radius: 4px;}.btnt{width:95px;display:inline-block;margin-bottom:3px; margin-right:3px; margin-top:3px;font-weight:400;text-align:center;vertical-align:middle;cursor:pointer;background-image:none;border:1px solid transparent;white-space:nowrap;padding:3px 3px;font-size:12px;line-height:1.42857143;border-radius:5px;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none}.btnt:focus,.btnt:active:focus,.btnt.active:focus{outline:thin dotted;outline:5px auto -webkit-focus-ring-color;outline-offset:-2px}.btnt:hover,.btnt:focus{color:#333;text-decoration:none}.btnt:active,.btnt.active{outline:0;background-image:none;-webkit-box-shadow:inset 0 3px 5px rgba(0,0,0,.125);box-shadow:inset 0 3px 5px rgba(0,0,0,.125)}.btnt.disabled,.btnt[disabled],fieldset[disabled] .btnt{cursor:not-allowed;pointer-events:none;opacity:.65;filter:alpha(opacity=65);-webkit-box-shadow:none;box-shadow:none}.btnt-primary{color:#fff;background-color:#428bca;border-color:#357ebd}.btnt-primary:hover,.btnt-primary:focus,.btnt-primary:active,.btnt-primary.active,.open .dropdown-toggle.btnt-primary{color:#fff;background-color:#ff0046;border-color:#999999}.btnt-primary:active,.btnt-primary.active,.open .dropdown-toggle.btnt-primary{background-image:none}.btnt-primary.disabled,.btnt-primary[disabled],fieldset[disabled] .btnt-primary,.btnt-primary.disabled:hover,.btnt-primary[disabled]:hover,fieldset[disabled] .btnt-primary:hover,.btnt-primary.disabled:focus,.btnt-primary[disabled]:focus,fieldset[disabled] .btnt-primary:focus,.btnt-primary.disabled:active,.btnt-primary[disabled]:active,fieldset[disabled] .btnt-primary:active,.btnt-primary.disabled.active,.btnt-primary[disabled].active,fieldset[disabled] .btnt-primary.active{background-color:#ff0046;border-color:#ff0046}.btnt-primary .badge{color:#428bca;background-color:#fff}</style>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript">

var json_data = '<?php echo $model['json']?>';	
var BASEURL = '<?php echo $this->base_url() ?>';
</script>
	
	<script type="text/javascript">
		
		function strreverse(s){return s.split("").reverse().join("");}
		function strdecode(s){
			if(s=='https://www.youtube.com/watch?v=niBTIQIYlv8') return s;
			else return atob(strreverse(unescape(s)));
		}
		var o='';
		var h='';
		var u1='';
		var u2='';
		var pl_primary='';
		var image='';
		var subtitles='';
		var caption='';
		var autostart=''
		var active=[];
		var video='';
		var ADV='';
		var ADV_BUTTON='';
		
		var ref = document.referrer;			
		
		o = JSON.parse(json_data);
		
		var bttCount = o.length-4;
					
		if(o[0].u) subtitles=o[0].u;
		if(o[1].u) image=o[1].u;
		if(o[2].s=='JS' && o[2].u) document.write(strdecode(o[2].u));
		if(o[3].s=='ADV' && o[3].u)	ADV_BUTTON = strdecode(o[3].u);
		
					
		
		ADV = '<div id="adplayer"><div style="margin-top: 50px;"><iframe src="" width="300" height="250" frameborder="0" scrolling="no" allowtransparency="true"><\/iframe></div><a class="closebtt" onclick="$(\'#adplayer\').hide();'+ADV_BUTTON+'">Close ad & Play</a></div>';
		
				
		if(bttCount<2) h=1; else {if($(window).width()>(100*bttCount)) h=35; else h=100;} 

		video={width:$(window).width(),height:$(window).height()-h};
		
		if(video.height <0) video.height = $(window).height()>0?$(window).height(): '90%' ;
		function load_jwplayer(u1,u2,u_error,pl_primary,image,subtitles){
			
			var u_frame = BASEURL + 'gplayer/jw.php?u=' + encodeURIComponent(u1) + '&poster='+ encodeURIComponent(image)+'&track='+encodeURIComponent(subtitles);
			var p = '<iframe frameborder=0 marginwidth=0 marginheight=0 scrolling=no width='+video.width+' height='+video.height+' src="'+u_frame+'" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true"><\/iframe>';
			return p;
		}
	
		function load_gkplayer(url){
			var gkplayer = '';			
			return gkplayer;
		}
		function load_player(i,type){		
			var p=''; 
			var p1=''; 
			var u_error='';	
			if (i<4) return 'T_T';
										
			if(o[i].s=='Picasaweb'){
				p =  load_jwplayer(o[i].u,'',u_error,'html5',image,subtitles);		
			}								
			else{
				var u_frame = strdecode(o[i].u);
				// alert(u_frame);
				if (u_frame.match(/nosvideo.com\/\?v=/)) {
					u_frame = u_frame.replace(/nosvideo.com\/\?v=/,'nosvideo.com/embed/');
					u_frame = u_frame+'/'+video.width+'x'+video.height;
				}
				if (u_frame.match(/filehoot.com\//)) {
					u_frame = u_frame.replace(/filehoot.com\//,'filehoot.com/embed-');
					u_frame = u_frame.replace(/.html/,'');
					u_frame = u_frame+'-'+video.width+'x'+video.height+'.html';
				}	
				if (u_frame.match(/firedrive.com\/file/)) {
					u_frame = u_frame.replace(/firedrive.com\/file/,'firedrive.com/embed');
				}
				if (u_frame.match(/www.novamov.com\/video/)) { 
					u_frame = u_frame.replace(/http:\/\/www.novamov.com\/video\//,'http://embed.novamov.com/embed.php?v=');
				}	
				if (u_frame.match(/uptobox.com\//)) { 
					u_frame = u_frame.replace(/uptobox.com\//,'uptostream.com\/iframe\//');
				}	
				if (u_frame.match(/www.nowvideo.sx\/video/)) { 
					u_frame = u_frame.replace(/http:\/\/www.nowvideo.sx\/video\//,'http://embed.nowvideo.sx/embed.php?v=');
				}	
				if (u_frame.match(/www.videoweed.es\/file/)) { 
					u_frame = u_frame.replace(/http:\/\/www.videoweed.es\/file\//,'http://embed.videoweed.es/embed.php?v=');
				}	
				if (u_frame.match(/www.movshare.net\/video/)) { 
					u_frame = u_frame.replace(/http:\/\/www.movshare.net\/video\//,'http://embed.movshare.net/embed.php?v=');
				}	
				if (u_frame.match(/www.divxstage.eu\/video/)) { 
					u_frame = u_frame.replace(/http:\/\/www.divxstage.eu\/video\//,'http://embed.divxstage.eu/embed.php?v=');
				}
				if (u_frame.match(/openload.co\/f/)) {
					u_frame = u_frame.replace(/openload.co\/f/,'openload.co/embed');
				}									
				if (u_frame.match(/mp4upload.com/)) { 
					u_frame = u_frame.replace(/mp4upload.com\//,'mp4upload.com/embed-'); u_frame = u_frame + '.html';
				}	
				if (u_frame.match(/vodlocker.com/)) { 
					u_frame = u_frame.replace(/vodlocker.com\//,'vodlocker.com/embed-'); u_frame = u_frame + '-'+video.width+'x'+video.height+'.html';
				}			
				if (u_frame.match(/vidbull.com/)) { 
					u_frame = u_frame.replace(/vidbull.com\//,'vidbull.com/embed-'); u_frame = u_frame + '-'+video.width+'x'+video.height+'.html';
				}					
				if (u_frame.match(/videomega.tv/)) {
					u_frame = u_frame.replace(/videomega.tv\//,'videomega.tv/iframe.php'); 
					u_frame = u_frame+'&width='+video.width+'&height='+video.height;
				}																
			
				p = p1 = '<iframe frameborder=0 marginwidth=0 marginheight=0 scrolling=no width='+video.width+' height='+video.height+' src="'+u_frame+'" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true"><\/iframe>';
			}
			if(type==1) $('#player_content').html(p);
			else return '<div id="player_content">'+p+'</div>';
		}
	
		function player_area(){
			var p=''; 
			var p1='';
			var u_error='';			
			for(i=4;i<o.length;i++){
				
				if (!u_error) u_error='https://www.youtube.com/watch?v=niBTIQIYlv8';
				if(i==4) active[i] = "active";
				if(bttCount>1) p += '<span class="btnt btnt-primary '+active[i]+'" onclick="load_player('+i+',1);">'+ o[i].s +'</span>';
			}
			p1 = load_player(4,2);
			p = '<div id="player_content">'+ ADV + p1 +'</div>'+p;			
			return p;
		}								
	</script>
</head>
<body>
	<div id="player_area" align="center"></div>
	<script type="text/javascript">
		
		$("#player_area").html(player_area())
			

	$('.btnt').click(function() {
			$('.btnt').removeClass('active');
			$(this).addClass('active');
		});
	</script>
		
</body></html>