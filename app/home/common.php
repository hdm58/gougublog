<?php
// 这是home公共文件
function pageError($msg = "页面错误")
{
    echo '<div style="text-align:center; padding:50px 0; font-size:36px;">' . $msg . '</div>';exit;
}

/**
 *+----------------------------------------------------------
 * 字符串截取，支持中文和其他编码
 *+----------------------------------------------------------
 * @static
 * @access public
 *+----------------------------------------------------------
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
 *+----------------------------------------------------------
 * @return string
 *+----------------------------------------------------------
 */
function msubstr($str, $start=0,$length, $charset="utf-8", $suffix=false){
	if(function_exists("mb_substr")){
		$slice=mb_substr($str, $start, $length, $charset);
		if($suffix){
		  	if(mb_strlen($str,'utf-8')>$length){
		    	return $slice."...";
		    }else{
				return $slice;
			}
		}else{
		   	return $slice;
		}
	}elseif(function_exists('iconv_substr')){
		if($suffix){
		   return iconv_substr($str,$start,$length,$charset)."...";
		}else{
		   return iconv_substr($str,$start,$length,$charset);
		}
	}

	$re['utf-8'] = "/[x01-x7f]|[xc2-xdf][x80-xbf]|[xe0-xef][x80-xbf]{2}|[xf0-xff][x80-xbf]{3}/";
	$re['gb2312'] = "/[x01-x7f]|[xb0-xf7][xa0-xfe]/";
	$re['gbk'] = "/[x01-x7f]|[x81-xfe][x40-xfe]/";
	$re['big5'] = "/[x01-x7f]|[x81-xfe]([x40-x7e]|xa1-xfe])/";
	preg_match_all($re[$charset], $str, $match);
	$slice = join("",array_slice($match[0], $start, $length));		 
	if($suffix) {
		if(strlen($slice)>$length){
		 	return $slice."…";
		}else{
		 	return $slice;
		}
	}
	return $slice;
}

//读取导航列表，用于前台
function get_navs($name)
{
    if (!get_cache('homeNav' . $name)) {
        $nav_id = \think\facade\Db::name('Nav')->where(['name' => $name, 'status' => 1])->value('id');
        if (empty($nav_id)) {
            return '';
        }
        $list = \think\facade\Db::name('NavInfo')->where(['nav_id' => $nav_id, 'status' => 1])->order('sort asc')->select();
        \think\facade\Cache::tag('homeNav')->set('homeNav' . $name, $list);
    }
    $navs = get_cache('homeNav' . $name);
    return $navs;
}

//文章按日归档统计
function date_document($arrData)
{
    $documents = array();
    foreach ($arrData as $index => $value) {
        $archivesTime = date("Y-m-d", $value['create_time']);
        if (empty($documents[$archivesTime])) {
            $documents[$archivesTime] = 1;
        } else {
            $documents[$archivesTime] += 1;
        }
    }
    return $documents;
}
//文章归档按月
function month_document($arrData)
{
    $documents = array();
    foreach ($arrData as $index => $value) {
        $value['create_date'] = date("d", $value['create_time']);
        $archivesTime = date("Y-m", $value['create_time']);
        $documents[$archivesTime][] = $value;
    }
    return $documents;
}
//文章归档按年
function year_document($arrData)
{
    $documents = array();
    foreach ($arrData as $index => $value) {
        $dateArray = explode("-", $index);
        $archivesTime = $dateArray[0];
        $documents[$archivesTime][$dateArray[1]]['list'] = $value;
        $documents[$archivesTime][$dateArray[1]]['count'] = count($value);
    }
    return $documents;
}
