<?php

	ini_set('error_reporting', E_ALL);
	ini_set('display_errors',0);
	ini_set('display_startup_errors', 0);

$domain = parse_url(strtolower(strtolower($_POST['link_for_parse'])));

	function pars($from,$to,$source){
		$from_source=explode($from,$source);
		$to_source=explode($to,$from_source[1]);
		if (count(trim($to_source[0]))>0) {return trim($to_source[0]);} else{return '';}
	}
	
	if ($domain['host']=='www.copart.com' || $domain['host']=='copart.com'){

//$domain = parse_url(strtolower($_POST['link_for_parse']));

	$ch = curl_init();
	$url	=	'https://taurus-group.com.ua/calc2/parse_link/parse.php';

	//print_r($url);

	$opt=http_build_query(array( 
			'link_for_parse' => strtolower($_POST['link_for_parse'])
		));
		
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.3) Gecko/2008092417 Firefox/3.0.3');
	curl_setopt($ch, CURLOPT_REFERER, 'https://taurus-group.com.ua/calc2/');
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $opt);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	$result = json_decode(curl_exec($ch) , true);

	$result['state'] = str_replace('*N', 'CA', $result['state']);	
	
	file_put_contents('img/'.md5(strtolower($_POST['link_for_parse'])).'.jpg', file_get_contents(str_replace('\\/', '/', $result['images'])));
	//
	$result['images'] = 'append/img/'.md5(strtolower($_POST['link_for_parse'])).'.jpg';
	
	echo json_encode($result);
	
	} else {
		
		$iaai_get = str_replace('parse.php', 'iaai.php', 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
		
		$query = pars('itemid=','&', strtolower($_POST['link_for_parse']));
		
		if ($query!=''){
			$query = pars('itemid=','&', strtolower($_POST['link_for_parse']));
			// если линк формата https://www.iaai.com/VehicleDetails?itemid=35104188&RowNumber=0&similarVehicleItemId=&isNext=&loadRecent=true
			
		} else if (pars('vehicledetails/','/', strtolower($_POST['link_for_parse']))!=''){
			// если линк формата https://www.iaai.com/vehicledetails/35093183/0/
			$query = pars('vehicledetails/','/', strtolower($_POST['link_for_parse']));
			
		} else if(pars('&itemid=','&', strtolower($_POST['link_for_parse']))!=''){
			//https://www.iaai.com/Vehicledetails?similarVehicleItemId=35093183&itemid=34939311&sstno=26740698&RowNumber=0
			$query = pars('&itemid=','&', strtolower($_POST['link_for_parse']));
			
		} else {
			$query = '';
		}
		
		$data = file_get_contents($iaai_get.'?id='.$query);
			//echo $data;
			$result['state'] = substr( str_replace(array('(', ')', '-'),'', pars('"BranchLink":"', '"', $data)), -2 ); 
	
	$result['price'] = str_replace(',','',pars('"BuyNowOfferAmount":', '"', $data));//str_replace(',','',pars('ACV":"$', '"', $data));
	
	$eng = pars('"Text":"', '"', pars('"Name":"Engine"', ']', $data));
	$eng_1 = explode(' ', $eng);
	
	//if ($eng_1[0]!=
	
	$result['engine'] = preg_replace("/[^0-9\.]/", '', $eng_1[0]); //round( preg_replace("/[^0-9\.]/", '', pars('"Text":"', '"', pars('"Name":"Engine"', ']', $data))), 1, PHP_ROUND_HALF_DOWN);
	
	
	$result['fuel_type'] = str_replace('Other', "Hybrid", pars('"Text":"', '"', pars('"Name":"FuelType"', ']', $data) )); 
	
	$result['images'] = pars('"images":[{"Url":"', '"', $data);
	$result['title'] = pars('AsapMake":"', '"', $data).' '.pars('"AsapModel":"', '"', $data).' '.pars('","Series":"', '"', $data);//"2006 TOYOTA PRIUS"
	$result['vin'] = pars('"VIN":"', '"', $data);
	$result['domain'] = "www.iaai.com";
	$result['year'] = pars('{"Year":"','"', $data);
	
	file_put_contents('img/'.md5(strtolower($_POST['link_for_parse'])).'.jpeg', file_get_contents($result['images']));
	$result['images'] = 'append/img/'.md5(strtolower($_POST['link_for_parse'])).'.jpeg';
	
	
	echo json_encode($result); 
		
		
//	echo $result;
		
	}
	
	
?>