<?php
class Response{

	/**
	 * [ConvertToType 转换成对应的数据类型]
	 * @param [type] $type    [需要转换的格式]
	 * @param [type] $code    [状态吗]
	 * @param string $message [提示信息]
	 * @param array  $data    [数据]
	 * @return [type]         [字符串]
	 */
	public static function ConvertToType($code = 200,$message = '',$data = array(),$type = 'json'){
		if (!is_numeric($code)) {
			return '';
		}

		$result = [
			"code" 		=> $code,
            "message"	=> $message,
            "data" 		=> $data
		];

		if ($type == 'json') {
			return self::json($code,$message,$data);
		} elseif ($type == 'xml') {
			return self::xml($code,$message,$data);
		} elseif ($type == 'array') {	//调试模式
			var_dump($result);
		} else {
			return '只支持Json,Xml两种格式转换！';
		}

	}

	/**
	 * [json 生成json数据]
	 * @param [type] $code    [状态吗]
	 * @param string $message [提示信息]
	 * @param array  $data    [数据]
	 * @return [type]         [字符串]
	 */
	public static function json($code,$message = '',$data = array()){
		if (!is_numeric($code)) {
			return '';
		}
	    $result = [
			"code" 		=> $code,
            "message"	=> $message,
            "data" 		=> $data
		];
	    return json_encode($result);
	}

	/**
	 * [xml 生成XML数据]
	 * @param [type] $code    [状态吗]
	 * @param string $message [提示信息]
	 * @param array  $data    [数据]
	 * @return [type]         [字符串]
	 */
	public static function xml($code,$message = '',$data = array()){
		if (!is_numeric($code)) {
			return '';
		}
	    $result = [
			"code" 		=> $code,
            "message"	=> $message,
            "data" 		=> $data
		];
	    header('Content-Type:text/xml');
	    $xml = "<?xml version='1.0' encoding='UTF-8'?>\n";
	    $xml .= "<root>";
	    $xml .= self::encodeXml($result);
	    $xml .= "</root>";
	    return $xml;
	}

	//将数据解析为XML字符串
	public static function encodeXml($data){
	    $attr = $xml = "";
	    foreach($data as $key => $value){
	            if(is_numeric($key)){
	                $attr = " id='{$key}'";
	                $key = "item";
	            }
	            $xml .= "<{$key}{$attr}>";
	            $xml .= is_array($value)?self::encodeXml($value):$value;	//递归调用
	            $xml .= "</$key>";
	    }
	    return $xml;
	}
    
}
