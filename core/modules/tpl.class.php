<?php
namespace core\module;
class tpl
{
	const require = [
        'module'=>[],
        'functions'=>[]
    ];
	public function open($dir,$file)
	{
		$file = $dir.'/'.$file.'.tpl';
		if(file_exists($file)){
			if(filesize($file) != 0){
				$text = fopen($file,'r');
				$data = fread($text, filesize($file));
				fclose($text);
			}else{
				$data = '';
			}
			$data = $this->includeTpl($dir,'include','include',$data);
		}else{
			$data = '';
		}

		return $data;
	}
	public function select($trs,$template,$unsettag=0)
	{

		if(preg_match_all("/(\[".$trs."\](.*?)\[\/".$trs."\])/s", $template,$arr)){
			if(count($arr[0]) > 1){
				if($unsettag == 1){
					foreach($arr[0] as $key => $value){
						$arr[0][$key] = str_replace('['.$trs.']', '', $arr[0][$key]);
						$arr[0][$key] = str_replace('[/'.$trs.']', '', $arr[0][$key]);
					}
				}
				$template = $arr[0];
			}else{
				if($unsettag == 1){
					$arr[0][0] = str_replace('['.$trs.']', '', $arr[0][0]);
					$arr[0][0] = str_replace('[/'.$trs.']', '', $arr[0][0]);
				}
				$template = $arr[0][0];
			}
		}else{
			$template = '';
		}
		return $template;
	}
	public function selectdata($opentag,$closetag,$trs,$conid,$template)
	{
		if(preg_match_all("/(\\".$opentag.")(.*?)(\\".$closetag.")/s", $template,$arr)){
			$i = 0;
			foreach($arr['0'] as $value){
				$novalue = $value;
				$value = str_replace($opentag, '', $value);
				$value = str_replace($closetag, '', $value);
				$value = explode(",", $value);
				if($value[0] == $trs){
					if(isset($value[$conid])){
						$string = $value[$conid];
						$template = str_replace($novalue, $string, $template);
					}else{
						$template = str_replace($novalue, '', $template);
					}
				}
				$i++;
			}
		}
		return $template;
	}
	public function array2tpl($tag,$array,$template)
	{
        if($tag==null){
            $prefix = "";
        }else{
			$prefix = $tag.":";
		}
		if(preg_match_all("/\{".$prefix."(.*?)\}/s", $template,$arr)){
			foreach($arr[0] as $value){
                $value = str_replace('{'.$prefix,'',$value);
				$value = str_replace('}','',$value);
				if(isset($array[$value])){
                    $template = str_replace('{'.$prefix.$value.'}',$array[$value],$template);
				}
			}
		}
		return $template;
	}
	public function hidden($tag,$template)
	{
		$TPL = $this->select($tag,$template,0);
		return str_replace($TPL,'',$template);
	}
	public function removeTag($tag,$template)
	{
		$TPL = $this->select($tag,$template,0);
		$TPLnoTag = $this->select($tag,$template,1);
		return str_replace($TPL,$TPLnoTag,$template);
	}
	public function includeTpl($dir,$incDir,$tagName,$template)
	{
		if(preg_match_all("/\{".$tagName.":(.*?)\}/s", $template,$arr)){
			foreach($arr[0] as $value){
				$value = str_replace('{'.$tagName.':','',$value);
				$value = str_replace('}','',$value);
				$included = $this->open($dir.$incDir,$value);
				$template = str_replace('{'.$tagName.':'.$value.'}',$included,$template);
			}

		}
		return $template;
	}
	public function arrayCompile($array,$tag,$template)
	{
		$i=0;
		$implode = array();
        foreach($array as $arr){
       		$implode[$i] = $this->select($tag,$template,true);
        	if(is_array($implode[$i])) $implode[$i] = $implode[$i][0];
            foreach($arr as $key => $value){
		    	$implode[$i] = str_replace('{'.$key.'}',$value,$implode[$i]);
        	}
        	$i++;

        }
		return str_replace($this->select($tag,$template,false),implode($implode),$template);
	}
	public function comments($open,$close,$tpl)
	{
		$open 	= preg_quote($open);
		$close 	= preg_quote($close);
		$open 	= str_replace('/','\/',$open);
		$close 	= str_replace('/','\/',$close);
		$tpl 	= preg_replace("/(".$open."(.*?)".$close.")/s","",$tpl);

		return $tpl;
	}
}
