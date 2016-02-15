<?php
	
/**
@ EMS Почта России (автоматизированный) - 5.5
@ + с расширенной админ-панелью + международный
@ Разработчик: Эльхан Исаев
@ ICQ: 27-27-27-27
@ Сайт: http://7777777.pro
**/

class ModelShippingEms extends Model {



//Connect to EMS
	public function connect_ems ($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 15);
		$out = curl_exec($ch);
		$List = json_decode($out, TRUE);
		curl_close($ch);
		return $List;
	}
//Connect to EMS

//Any Funcs For EMS Grabs
public function transl($str)  {
    $tr = array
    	(
    "А"=>"a","Б"=>"b","В"=>"v","Г"=>"g", "Д"=>"d","Е"=>"e","Ж"=>"zh","З"=>"z","И"=>"i","Й"=>"i","К"=>"k","Л"=>"l","М"=>"m","Н"=>"n","О"=>"o","П"=>"p","Р"=>"r","С"=>"s","Т"=>"t",
    "У"=>"u","Ф"=>"f","Х"=>"kh","Ц"=>"c","Ч"=>"ch","Ш"=>"sh","Щ"=>"shh","Ъ"=>"","Ы"=>"y","Ь"=>"","Э"=>"e","Ю"=>"yu","Я"=>"ya","а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"zh",
    "з"=>"z","и"=>"i","й"=>"j","к"=>"k","л"=>"l","м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r","с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h","ц"=>"c","ч"=>"ch","ш"=>"sh","щ"=>"shh","ъ"=>"","ы"=>"y","ь"=>"","э"=>"je","ю"=>"ju","я"=>"ja"," "=>"-"
		);
    return strtr($str,$tr);
}

public function kolvo($slovo) { 
$k=1; 
for ($i=0;$i<strlen($slovo);$i++) {
if ($slovo[$i]==" ") $k++;
	} 
return $k; 
}

public function dest_check($str_reg, $str_city) {

if($this->kolvo(iconv('utf-8','windows-1251',$str_city)) > 1) $res_1 = 'region--'.$this->transl($str_city); else $res_1 = 'city--'.$this->transl($str_city); 
if($this->kolvo(iconv('utf-8','windows-1251',$str_reg)) > 1) $res_2 = 'region--'.$this->transl($str_reg); else $res_2 = 'city--'.$this->transl($str_reg);

//----------------
$urlRussia = 'http://emspost.ru/api/rest/?method=ems.get.locations&type=russia&plain=true';
$RussiaList = $this->connect_ems($urlRussia);
//----------------

$gorod = ""; 
$reg = "";

	foreach($RussiaList['rsp']['locations'] as $key=>$val)
	{
	foreach($val as $i=>$j)
        {

	if( in_array ($res_1, $RussiaList['rsp']['locations'][$key]) ) $gorod = $res_1;
    if( in_array ($res_2, $RussiaList['rsp']['locations'][$key]) ) $reg = $res_2;
    					$ress = $gorod;
    if(empty($ress)) 	$ress = $reg;
    if(empty($ress)) 	$ress = 'error';

		}
	}
	return $ress;

} 

public function rus_reg($ems_format_reg) 
{

//----------------
$urlRussia = 'http://emspost.ru/api/rest/?method=ems.get.locations&type=russia&plain=true';
$RussiaList = $this->connect_ems($urlRussia);
//----------------
	
	foreach($RussiaList['rsp']['locations'] as $key=>$val)
	{
	foreach($val as $i=>$j)
        {
	if( in_array ($ems_format_reg, $RussiaList['rsp']['locations'][$key]) ) $res = $RussiaList['rsp']['locations'][$key]['name'];
    if(empty($res) || !isset($res)) $res = 'error'; //not found out Russia
		}
	}
	if($res != "error") { return mb_convert_case(mb_strtolower($res,'utf-8'), MB_CASE_TITLE, 'utf-8');} else return $res;

}

public function country_check($ciso)
{

//----------------
$urlc = 'http://emspost.ru/api/rest/?method=ems.get.locations&type=countries';
$listc = $this->connect_ems($urlc);
//----------------

	foreach($listc['rsp']['locations'] as $key=>$val)
	{
	foreach($val as $i=>$j)
        {
	if( in_array ($ciso, $listc['rsp']['locations'][$key]) ) $res = $ciso;
    if(empty($res) || !isset($res)) $res = 'error'; //not found
		}
	}
	return $res;

}
//Any Funcs For EMS Grabs


	public function getQuote($address) 
	{
		$this->load->language('shipping/ems');

		if ( $this->config->get('ems_status') && isset($address) && !empty($address) )
		{

			$dops = '';

			//FROM CITY
      		$query = $this->db->query("SELECT name, country_id FROM " . DB_PREFIX . "zone WHERE zone_id = '" . $this->config->get('config_zone_id') . "'");
			$emscity = $this->config->get('ems_city_from');
			$checkemscity = $this->rus_reg($emscity);
			if($emscity == "0") $city_from = $this->dest_check('FALSE', $query->row['name']);  
				else {
				if($checkemscity != "error") $city_from = $this->config->get('ems_city_from'); 
				else $city_from = "error"; 
				}
		
			if(isset($query->row['country_id'])) $country_id_from = $query->row['country_id'];
			
			//TO CITY
			$query = $this->db->query("SELECT name, country_id FROM " . DB_PREFIX . "zone WHERE zone_id = '" . (int)$address['zone_id'] . "'");
			$city_to = $this->dest_check($query->row['name'],$address['city']);
			$country_id = $query->row['country_id'];
			$tociid = $query->row['name'];
			
			//FROM COUNTRY
			$query = $this->db->query("SELECT name, iso_code_2 FROM " . DB_PREFIX . "country WHERE country_id = '" . (int)$country_id_from . "'");
			$country_name_from = $query->row['name'];
			if(isset($query->row['iso_code_2']))  $country_iso_from = $query->row['iso_code_2'];
			
			//TO COUNTRY	
			$query = $this->db->query("SELECT name, iso_code_2 FROM " . DB_PREFIX . "country WHERE country_id = '" . (int)$country_id . "'");
			$country_name = $query->row['name'];
			$country_iso = $query->row['iso_code_2'];
			
			
			
			if($country_iso != 'RU') { $dops = '&type=att'; $city_to = $this->country_check($country_iso); }
			

			if($city_from=="error" || $city_to=="error") $status = FALSE; else $status = TRUE;
			if ($city_from == $city_to && $this->config->get('ems_in')=="0") $status = FALSE; else $status = TRUE;

		} else $status = FALSE;

		$method_data = array();

		if($this->config->get('config_weight_class_id') == 1)
		{
		$ems_dopl_ves = $this->config->get('ems_dopl_ves')/1000;
		$normves = number_format(preg_replace("/[^0-9-.]+/is", '', $this->weight->format($this->cart->getWeight(), $this->config->get('config_weight_class_id'))), 4, '.', '') / 1 + $ems_dopl_ves; //основа кг
		$normves_text = $normves.' кг.';
		}
		else
		{
		$ems_dopl_ves = $this->config->get('ems_dopl_ves');
		$normves = number_format(preg_replace("/[^0-9-.]+/is", '', $this->weight->format($this->cart->getWeight(), $this->config->get('config_weight_class_id'))), 4, '.', '') / 1000 + $ems_dopl_ves; //основа граммы
		$normves_text = ($normves*1000).' г.';
		}


//Обозначения
$mname = ( $this->config->get('ems_mname')=="" || $this->config->get('ems_mname')===FALSE ) ? $this->language->get('text_title') : $mname = $this->config->get('ems_mname');
//Обозначения


//Ошибки
$text_error = FALSE;

if($status && ($this->config->get('ems_max_weight') < $normves) ) $text_error .= '<li>Расчет и выбор недоступен, по причине превышения допустимого веса в '.$this->config->get('ems_max_weight').' кг. </li>';
if($city_to=="error") $text_error .= '<li>Расчет и выбор недоступен, по причине ввода некорректного пункта (города или области) доставки!</li>';
	
if($text_error )
{
$method_data = array
	(
	'title'      	=> $mname,
	'sort_order' 	=> $this->config->get('ems_sort_order'),
	'quote'      	=> '',
	'error'      	=> $text_error
	);

$status = FALSE;
return $method_data;
}
//Ошибки

		if ( $status && ( $this->config->get('ems_max_weight') >= $normves ) ) 
		{
		
		// Ценовая сумма товаров годных для объявления ценности
		$product_total_obl = 0;
		$products = $this->cart->getProducts();
	    foreach ($products as $product) 
		{
		if($product['price']<'50000') $product_total_obl += $product['price'];
		}
		//*****//	


			$url = 'http://emspost.ru/api/rest/?method=ems.calculate&from=' . $city_from . '&to=' . $city_to . '&weight=' .  $normves .$dops;
			
			//----------------
			$quote_data = array();
			$response_array = $this->connect_ems($url);
			//----------------
		
		if($response_array['rsp']['stat'] == 'ok') 
			{
				
$ems_vidd = $this->config->get('ems_vid'); //В пределах страны
$ems_vidd_out = $this->config->get('ems_vid_out'); //За границу
$ems_plus = (intval($this->config->get('ems_plus'))) ? $this->config->get('ems_plus') : 0; //вес добавляемый
$ems_dopl = (intval($this->config->get('ems_dopl'))) ? $this->config->get('ems_dopl') : 0; //сумма добавляемая
$dopl = $this->cart->countProducts()*$ems_dopl;

//Некоторые исключения
if($ems_dopl_ves>0) $ves_tx = "( c учетом ".($ems_dopl_ves*1000)." г. - вес упаковки )"; else $ves_tx = "";
if( $this->config->get('ems_vid') == "" || $this->config->get('ems_vid') === FALSE ) $ems_vidd = '[EMS] %from% - %to%, сроки: %mind% - %maxd% дней, вес: %ves%';
if( $country_iso != 'RU' && $this->config->get('ems_vid_out') == "" || $this->config->get('ems_vid_out') === FALSE ) $ems_vidd = '[EMS] %from% - %to%, сроки: 5 - 14 дней, вес: %ves% кг.'; 
//Некоторые исключения

if($response_array['rsp']['term']['min'] == $response_array['rsp']['term']['max'])
{
$ems_vidd = str_replace(array('дня', 'дней', 'дн.'), "", $ems_vidd);
$ems_vidd = preg_replace("#%mind%(.+?)%maxd%#si", "в течение ". ($response_array['rsp']['term']['max']+$ems_plus) ." дн.", $ems_vidd);
}

if($country_iso == 'RU') 
{
$endtext = str_replace
 ( 
array('%from%', '%to%', '%mind%', '%maxd%', '%ves%'), 
array( $this->rus_reg($city_from), $this->rus_reg($city_to), $response_array['rsp']['term']['min']+$ems_plus, $response_array['rsp']['term']['max']+$ems_plus, $normves_text ), 
$ems_vidd
 );
}
	else
{
$endtext = str_replace
 ( 
array('%from%', '%to%', '%ves%', '%from_city%', '%to_city%'), 
array( $country_name_from, $country_name, $normves, $this->config->get('ems_city_from'), $tociid ), 
$ems_vidd_out
 );
}


$quote_data['ems'] = array
 (
'code'         	=> 'ems.ems',
'title'        	=> $endtext.' '.$ves_tx,
'cost'          => ($this->config->get('config_currency') == 'RUB')?$response_array['rsp']['price']+$dopl:$this->currency->convert($response_array['rsp']['price']+$dopl, 'RUB', $this->config->get('config_currency')),
'tax_class_id' 	=> 0,
'text'          => ($this->config->get('config_currency') == 'RUB')?$this->currency->format($response_array['rsp']['price']+$dopl):$this->currency->format($this->currency->convert($response_array['rsp']['price']+$dopl, 'RUB', $this->config->get('config_currency')))
 );

if ($this->config->get('ems_ob')=="1" && $product_total_obl>0 )
{
$quote_data['ems_2'] = array
 (
'code'         	=> 'ems.ems_2',
'title'        	=> $endtext.' '.$ves_tx.' <b>(с объявленной ценностью)</b>',
'cost'          => ($this->config->get('config_currency') == 'RUB') ? $response_array['rsp']['price']+$product_total_obl/100+$dopl : $this->currency->convert($response_array['rsp']['price'] + $product_total_obl/100+$dopl, 'RUB', $this->config->get('config_currency')),
'tax_class_id' 	=> 0,
'text'          => ($this->config->get('config_currency') == 'RUB')?$this->currency->format($response_array['rsp']['price']+$product_total_obl/100+$dopl):$this->currency->format($this->currency->convert($response_array['rsp']['price']+$product_total_obl/100+$dopl, 'RUB', $this->config->get('config_currency')))
 );
}

if( $this->config->get('ems_desc') && $this->config->get('ems_description') == "" )
{
$description = <<<HTML

<div style="border: 1px dotted; text-decoration: none; font-weight: normal; padding: 4px;">

«EMS Russian Рost» является филиалом ФГУП «Почта России» и оказывает услуги экспресс-доставки, обеспечивая высокую надежность, привлекательное соотношение цены и качества. Мы создали прочную систему обслуживания клиентов, таким образом, чтобы обеспечить их качественной, надежной и доступной услугой по всей России и 190 странам мира.
<br>
«EMS Russian Рost» обеспечивает контроль безопасности и сохранности экспресс — отправлений на всем пути следования, благодаря хорошо развитой сети магистральных перевозок. Искусство проведения отправлений через транспортные процедуры, выбора транспортного средства, оптимизации маршрутов, разработки документации и комплектации грузов, позволяет «EMS Russian Рost» быстро и точно осуществлять доставку, что является отличительной особенностью от других видов почтовых услуг.	

</div>

HTML;
} 
elseif( $this->config->get('ems_desc') && $this->config->get('ems_description') != "") 
$description = '<div style="border: 1px dotted; text-decoration: none; font-weight: normal; padding: 4px;">'.html_entity_decode($this->config->get('ems_description')).'</div>'; 
else $description = "";

$method_data = array
 (
'code'       	=> 'ems',
'title'      	=> $mname.$description,
'quote'      	=> $quote_data,
'sort_order' 	=> $this->config->get('ems_sort_order'),
'error'      	=> FALSE
 );
			}

		}
		return $method_data;
	}
	



}
?>