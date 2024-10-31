<?php

	//ini_set('error_reporting', E_ALL);
	ini_set('display_errors',0);
	ini_set('display_startup_errors', 0);

//$domain = parse_url('https://www.iaai.com/vehicledetails/35093183/0/');//parse_url($_POST['link_for_parse']);
//print_r($domain);
//$domain = parse_url('https://www.iaai.com/VehicleDetails?itemid=35104188&RowNumber=0&similarVehicleItemId=&isNext=&loadRecent=true');//parse_url($_POST['link_for_parse']);
//print_r($domain);

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
			curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, 0);// не проверять SSL сертификат
			curl_setopt ($curl, CURLOPT_SSL_VERIFYHOST, 0);// не проверять Host SSL сертификата
			//curl_setopt($curl, CURLOPT_VERBOSE, FALSE);

			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 0);
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


	$page = get_iaai('https://mapp.iaai.com/acserviceswebapi/api/GetVehicleDetailsV2/?itemId='.$_GET['id'].'&userId=&culturecode=en&devicetype=android');	
	echo $page;
?>