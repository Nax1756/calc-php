	<?php

	ini_set('error_reporting', E_ALL);
	ini_set('display_errors',1);
	ini_set('display_startup_errors', 1);

$domain = parse_url($_POST['link_for_parse']);

//https://www.copart.com/ru/lot/25909900

function get_iaai($url){
		global $current_proxy;
		global $useragent;
		$useragent = 'IAA Buyer/9.0 Dalvik/2.1.0 (Linux; U; Android 5.1.1; SM-G9350 Build/LMY48Z)';
		
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);

			if (strlen($current_proxy)>0)
			{
			   curl_setopt($curl, CURLOPT_PROXY, "".$current_proxy."");
			   curl_setopt ($curl, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //, CURLPROXY_SOCKS5);
			}
			
			
			//curl_setopt($curl, CURLOPT_COOKIEJAR, 'cookie.txt'); 
			//curl_setopt($curl, CURLOPT_COOKIEFILE, 'cookie.txt');

			curl_setopt($curl, CURLOPT_HEADER, 1);

			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 0);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		//	curl_setopt($curl, CURLOPT_REFERER, $referer);

			//	echo 'count=>'.count($post);
				
		//if (count($post)>0)
		//	{
			 // curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
			   
				 //$postdata = $post;//json_encode($post);
				
//echo 	$postdata;
				
			//	curl_setopt($curl, CURLOPT_POST, 1);
				//curl_easy_setopt(curl, CURLOPT_POSTFIELDSIZE, 12L);
			//	curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
			//}
			
	$headers = [
		'Accept-Encoding: gzip',
		'Connection: Keep-Alive',
		'Host: mapp.iaai.com',
		'User-Agent: IAA Buyer/9.0 Dalvik/2.1.0 (Linux; U; Android 5.1.1; SM-G9350 Build/LMY48Z)'

							];

							//print_r($headers);
							
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

			curl_setopt($curl, CURLOPT_USERAGENT, $useragent);

			$res = curl_exec($curl);
			curl_close($curl);

			return $res;
		}


function isfind($find_value,$src_text){ //функция поска текста в строке
		  $pos = strpos($src_text, $find_value);
			if ($pos === false) {
			return 'null';
			} else {
			return $pos;
			}
	}

	function pars($from,$to,$source){
		$from_source=explode($from,$source);
		$to_source=explode($to,$from_source[1]);
		if (count(trim($to_source[0]))>0) {return trim($to_source[0]);} else{return '';}
	}
	
	$useragent = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.117 Safari/537.36 OPR/66.0.3515.36 (Edition Campaign 34)';
	
	//$page = get_iaai('https://www.iaai.com/ru-ru/VehicleSearch/SearchDetails?keyword='https://www.iaai.com/SiteSearch/SearchKeyword/, array('search'=>$_GET['id']));
	$page = get_iaai('https://mapp.iaai.com/acserviceswebapi/api/GetVehicleDetailsV2/?itemId=35127176&userId=&culturecode=en&devicetype=android');
	
	print_r($page);
	/* 
	$page = get_iaai('https://www.iaai.com/SiteSearch/SearchKeyword/', array('search'=>$_GET['id']));
	
	print_r($page);
	
	$page = get_iaai('https://www.iaai.com/VehicleDetails?itemid=34856984&RowNumber=0&similarVehicleItemId=&isNext=&loadRecent=true', '');
	
	print_r($page);
	
	$page = get_iaai('https://www.iaai.com/ru-ru/VehicleSearch/SearchDetails?keyword='.$_GET['id'], '');

	print_r($page);
	 */
	$page = explode('jsVehiclesAddedForWatch', $page);
	$page = explode('class="table-actions-footer"', $page[1]);

	$param = explode('"data-list__item', $page[0]);//explode('data-list__item"', $page[0]);

	$result['state'] = substr( pars('">', '</', pars( 'class="data-list__value', '</li>', $param[2])), -2);//"NJ";
	$result['price'] = null;
	$result['engine'] = null;
	$result['fuel_type'] = null;
	
	$result['images'] = pars('<img src="', '"', $page[0]);
	$result['title'] = pars('">', '</a', pars('class="heading-7"', '</h3>', $page[0]));//"2006 TOYOTA PRIUS"
	$result['vin'] = pars('">', '</s', pars('data-list__value">', '</li>', $param[9]));
	$result['domain'] = "www.iaai.com";
	$result['year'] = substr($result['title'], 0, 4);

	
	echo json_encode($result);
	
	
	
?>