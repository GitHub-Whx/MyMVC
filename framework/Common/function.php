<?php
	
	//加载控制器并调用相应的方法
	function C($app,$name,$method){
		require_once('app/'.$app.'/Controller/'.$name.'ControllerClass.php');
		eval('$obj = new '.$name.'Controller();$obj->'.$method.'();');//把字符串按php语句来解释 
	}

	//返回模型对象
	function M($app,$name){
		require_once('app/'.$app.'/Model/'.$name.'ModelClass.php');
		//$obj = new indexModel();
		eval('$obj = new '.$name.'Model();');
		return $obj;

	}

	//返回视图对象
	function V($app,$name){
		require_once('app/'.$app.'/View/'.$name.'ViewClass.php');
		eval('$obj = new '.$name.'View();');
		return $obj;
	}


	/*---------------------------------------------------
	
		以下为助手类函数
	
	----------------------------------------------------*/


	/**
	 * [showMessage 显示信息并跳转]
	 * @param  [type] $info [需要显示的信息]
	 * @param  [type] $url  [跳转的url]
	 * @return [type]       [description]
	 */
	 function showMessage($info, $url){
		echo "<script>alert('$info');window.location.href='$url'</script>";
	}
	
	/**
	 * [dump 传递数据以易于阅读的样式格式化后输出]
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	function dump($data){
	    // 定义样式
	    $str='<pre style="display: block;padding: 9.5px;margin: 44px 0 0 0;font-size: 13px;line-height: 1.42857;color: #333;word-break: break-all;word-wrap: break-word;background-color: #F5F5F5;border: 1px solid #CCC;border-radius: 4px;">';
	    // 如果是boolean或者null直接显示文字；否则print
	    if (is_bool($data)) {
	        $show_data=$data ? 'true' : 'false';
	    }elseif (is_null($data)) {
	        $show_data='null';
	    }else{
	        $show_data=print_r($data,true);
	    }
	    $str.=$show_data;
	    $str.='</pre>';
	    echo $str;
	}
?>