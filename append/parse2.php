<?php

	//ini_set('error_reporting', E_ALL);
	ini_set('display_errors',0);
	ini_set('display_startup_errors', 0);

$domain = parse_url($_POST['link_for_parse']);

//https://www.copart.com/ru/lot/25909900

function get_iaai($url){
		//global $current_proxy;
		//global $useragent;
		//$useragent = 'IAA Buyer/9.0 Dalvik/2.1.0 (Linux; U; Android 5.1.1; SM-G9350 Build/LMY48Z)';
		
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);

			//if (strlen($current_proxy)>0)
			//{ 
			//   curl_setopt($curl, CURLOPT_PROXY, "".$current_proxy."");
			//   curl_setopt ($curl, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //, CURLPROXY_SOCKS5);
			//}
			
			
			//curl_setopt($curl, CURLOPT_COOKIEJAR, 'cookie.txt'); 
			//curl_setopt($curl, CURLOPT_COOKIEFILE, 'cookie.txt');

			curl_setopt($curl, CURLOPT_HEADER, 0);
			//curl_setopt($curl, CURLOPT_VERBOSE, FALSE);

			//curl_setopt($curl, CURLOPT_RETURNTRANSFER, 0);
			//curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
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
			
	$headers = array(
		'Accept-Encoding: gzip',
		'Connection: Keep-Alive',
		'Host: mapp.iaai.com',
		'User-Agent: IAA Buyer/9.0 Dalvik/2.1.0 (Linux; U; Android 5.1.1; SM-G9350 Build/LMY48Z)'

							);

			//print_r($headers);
							
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

			//curl_setopt($curl, CURLOPT_USERAGENT, $useragent);

			$res = curl_exec($curl);
			curl_close($curl);

			return $res;
		}

function json_clean_decode($json, $assoc = false, $depth = 512, $options = 0) {
    // search and remove comments like /* */ and //
    $json = preg_replace("#(/\*([^*]|[\r\n]|(\*+([^*/]|[\r\n])))*\*+/)|([\s\t]//.*)|(^//.*)#", '', $json);
   
    if(version_compare(phpversion(), '5.4.0', '>=')) {
        $json = json_decode($json, $assoc, $depth, $options);
    }
    elseif(version_compare(phpversion(), '5.3.0', '>=')) {
        $json = json_decode($json, $assoc, $depth);
    }
    else {
        $json = json_decode($json, $assoc);
    }

    return $json;
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
	
	//$useragent = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.117 Safari/537.36 OPR/66.0.3515.36 (Edition Campaign 34)';
	
	//$page = get_iaai('https://www.iaai.com/ru-ru/VehicleSearch/SearchDetails?keyword='https://www.iaai.com/SiteSearch/SearchKeyword/, array('search'=>$_GET['id']));
	
	$data = '';
	$page = get_iaai('https://mapp.iaai.com/acserviceswebapi/api/GetVehicleDetailsV2/?itemId=35127176&userId=&culturecode=en&devicetype=android');
	
	$data = $page;
	
	$result['state'] = substr( str_replace(array('(', ')', '-'),'', pars('"BranchLink":"', '"', $data)), -2 ); 
	
	$result['price'] = pars('ACV":"$', '"', $data);
	$result['engine'] =  preg_replace("/[^0-9\.]/", '', pars('"Text":"', '"', pars('"Name":"Engine"', ']', $data) ) );
	$result['fuel_type'] = pars('"Text":"', '"', pars('"Name":"FuelType"', ']', $data) );
	
	$result['images'] = pars('"images":[{"Url":"', '"', $data);
	$result['title'] = pars('AsapMake":"', '"', $data).' '.pars('"AsapModel":"', '"', $data).' '.pars('","Series":"', '"', $data);//"2006 TOYOTA PRIUS"
	$result['vin'] = pars('"VIN":"', '"', $data);
	$result['domain'] = "www.iaai.com";
	$result['year'] = pars('{"Year":"','"', $data);
	
	echo json_encode($result);
	 
	
	
?>