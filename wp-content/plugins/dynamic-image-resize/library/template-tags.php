<?php

function dynamic_image_resize_url($src,$args){
	$src .= '?';
	foreach($args as $key => $value){
		$src .= $key.'='.$value.'&';
	}
	return substr($src,0,strlen($src)-1);
}