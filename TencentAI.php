<?php
//error_reporting(0);
 /**
 *	腾讯AI PHP, 十个功能完整调用例子
 *	填入ID和key上传到服务器就能食用了
 *	调用参数 wd= type= id=
 *	wd为上传的text，或者图片的base64代码，建议用post提交给本php
 *	id为识别代码
 *	type对应功能类型
 *	随机字nonce_str建议自行添加
 *	@Author: fornow <QQ313397677>
 *	@非官方，仅供参考
 *	@link http://heartdream.cn
 *	@version 0.2
 */
header("Content-Type: application/json; charset=utf-8");
if(is_array($_REQUEST)&&count($_REQUEST)>0){ //先判断是否传值了
if(isset($_REQUEST["wd"])){//是否存在"wd"的参数
$word=$_REQUEST["wd"];
}else{
 echo "非法调用！";
 exit();}
if(isset($_REQUEST["type"])){//是否存在"type"的参数
$type=$_REQUEST["type"];
}else{
$type='1';}//如果没有输入type那么就是1，输入的话就不管咯！
}
if(isset($_REQUEST["id"])){//是否存在"id"的参数
$id=$_REQUEST["id"];
}else{
$id=10000;
}
if($word==''){
echo '我需要一个目标';
exit();
}
// $keyword=urlencode(iconv('UTF-8', 'GB2312', $word));
$keyword=urlencode($word);
$appid="";//请填入你的ID
$nonce_str="fornow313397677";
$timestamp = time();
$app_key="";//请填入你的key
if($type==1){//基础聊天
$session=$id;
$str = "app_id={$appid}&nonce_str={$nonce_str}&question={$keyword}&session={$session}&time_stamp={$timestamp}";
$str=$str."&app_key={$app_key}";
$str= md5($str);
$sign=strtoupper($str);
$data="app_id={$appid}&time_stamp={$timestamp}&nonce_str={$nonce_str}&session={$session}&sign={$sign}&question={$keyword}";
$url="https://api.ai.qq.com/fcgi-bin/nlp/nlp_textchat";
$resp=post($url,$data);
$arr = json_decode($resp);
	if($arr->data->answer==''){
echo "失败！";
}else{
echo $arr->data->answer;
}}
if($type==2){//情感分析，-1负面，0中性，1正面
$str = "app_id={$appid}&nonce_str={$nonce_str}&text={$keyword}&time_stamp={$timestamp}";
$str=$str."&app_key={$app_key}";
$str= md5($str);
$sign=strtoupper($str);
$data="app_id={$appid}&time_stamp={$timestamp}&nonce_str={$nonce_str}&sign={$sign}&text={$keyword}";
$url="https://api.ai.qq.com/fcgi-bin/nlp/nlp_textpolar";
$resp=post($url,$data);
$arr = json_decode($resp);
	if($arr->data->polar==''){
echo "失败！";
}else{
echo $arr->data->polar;
}}
if($type==3){//语音合成,最多300字
$str = "app_id={$appid}&model_type=0&nonce_str={$nonce_str}&speed=0&text={$keyword}&time_stamp={$timestamp}";
$str=$str."&app_key={$app_key}";
$str= md5($str);
$sign=strtoupper($str);
$data="app_id={$appid}&time_stamp={$timestamp}&nonce_str={$nonce_str}&sign={$sign}&text={$keyword}&speed=0&model_type=0";
$url="https://api.ai.qq.com/fcgi-bin/aai/aai_tta";
$resp=post($url,$data);
// $resp=get($url.'?'.$data);
//echo $url.'?'.$data;
$arr = json_decode($resp);
	if($arr->data->voice==''){
echo "失败！";
}else{
echo $arr->data->voice;
}}
if($type==4){//鉴黄，需要图片1M内base64码
$str = "app_id={$appid}&image={$keyword}&nonce_str={$nonce_str}&time_stamp={$timestamp}";
$str=$str."&app_key={$app_key}";
$str= md5($str);
$sign=strtoupper($str);
$data="app_id={$appid}&time_stamp={$timestamp}&nonce_str={$nonce_str}&sign={$sign}&image={$keyword}";
$url="https://api.ai.qq.com/fcgi-bin/vision/vision_porn";
$resp=post($url,$data);
$arr = json_decode($resp);
$num = count($arr->data->tag_list);
	if($num==10){
echo "色情级别为:". $arr->data->tag_list[9]->tag_confidence . "/100";
}else{
echo "失败！";
}}
if($type==5){//暴恐识别，需要图片1M内base64码
$str = "app_id={$appid}&image={$keyword}&nonce_str={$nonce_str}&time_stamp={$timestamp}";
$str=$str."&app_key={$app_key}";
$str= md5($str);
$sign=strtoupper($str);
$data="app_id={$appid}&time_stamp={$timestamp}&nonce_str={$nonce_str}&sign={$sign}&image={$keyword}";
$url="https://api.ai.qq.com/fcgi-bin/image/image_terrorism";
$resp=post($url,$data);
$arr = json_decode($resp);
$num = count($arr->data->tag_list);
	if($num==8){
echo "暴恐级别为:".(100 - $arr->data->tag_list[7]->tag_confidence)."/100";
}else{
echo "失败！";
}}
if($type==6){//OCR识别，需要图片1M内base64码
$str = "app_id={$appid}&image={$keyword}&nonce_str={$nonce_str}&time_stamp={$timestamp}";
$str=$str."&app_key={$app_key}";
$str= md5($str);
$sign=strtoupper($str);
$data="app_id={$appid}&time_stamp={$timestamp}&nonce_str={$nonce_str}&sign={$sign}&image={$keyword}";
$url="https://api.ai.qq.com/fcgi-bin/ocr/ocr_generalocr";
$resp=post($url,$data);
$arr = json_decode($resp);
$num = count($arr->data->item_list);
	if($num>0){
for($i=0;$i<$num;++$i){ 
echo ($i+1)."、". $arr->data->item_list[$i]->itemstring."\r\n"; 
}}else{
echo "失败！";
}}
if($type==7){//看图说话，需要图片1M内base64码
$session_id=$id;
$str = "app_id={$appid}&image={$keyword}&nonce_str={$nonce_str}&session_id={$session_id}&time_stamp={$timestamp}";
$str=$str."&app_key={$app_key}";
$str= md5($str);
$sign=strtoupper($str);
$data="app_id={$appid}&time_stamp={$timestamp}&nonce_str={$nonce_str}&sign={$sign}&image={$keyword}&session_id={$session_id}";
$url="https://api.ai.qq.com/fcgi-bin/vision/vision_imgtotext";
$resp=post($url,$data);
$arr = json_decode($resp);
	if($arr->data->text==''){
echo "失败！";
}else{
echo $arr->data->text;
}}
if($type==8){//美食图片识别，需要图片1M内base64码
$str = "app_id={$appid}&image={$keyword}&nonce_str={$nonce_str}&time_stamp={$timestamp}";
$str=$str."&app_key={$app_key}";
$str= md5($str);
$sign=strtoupper($str);
$data="app_id={$appid}&time_stamp={$timestamp}&nonce_str={$nonce_str}&sign={$sign}&image={$keyword}";
$url="https://api.ai.qq.com/fcgi-bin/image/image_food";
$resp=post($url,$data);
$arr = json_decode($resp);
	if($arr->data->confidence==''){
echo "失败！";
}else{
echo "美食级别为:".(number_format($arr->data->confidence,2,'','') / 1).'/100';//干掉科学计数法并保留2位小数
}}
if($type==9){//图片标签识别，需要图片1M内base64码
$str = "app_id={$appid}&image={$keyword}&nonce_str={$nonce_str}&time_stamp={$timestamp}";
$str=$str."&app_key={$app_key}";
$str= md5($str);
$sign=strtoupper($str);
$data="app_id={$appid}&time_stamp={$timestamp}&nonce_str={$nonce_str}&sign={$sign}&image={$keyword}";
$url="https://api.ai.qq.com/fcgi-bin/image/image_tag";
$resp=post($url,$data);
$arr = json_decode($resp);
$num = count($arr->data->tag_list);
	if($num>0){
for($i=0;$i<$num;++$i){ 
echo ($i+1)."、". $arr->data->tag_list[$i]->tag_name.'可能为：'.$arr->data->tag_list[$i]->tag_confidence . "/100\r\n"; 
}}else{
echo "失败！";
}}
if($type==0){//颜龄检测，需要图片1M内base64码,无法适用
$str = "app_id={$appid}&image={$keyword}&nonce_str={$nonce_str}&time_stamp={$timestamp}";
$str=$str."&app_key={$app_key}";
$str= md5($str);
$sign=strtoupper($str);
$data="app_id={$appid}&time_stamp={$timestamp}&nonce_str={$nonce_str}&sign={$sign}&image={$keyword}";
$url="https://api.ai.qq.com/fcgi-bin/ptu/ptu_faceage";
$resp=post($url,$data);
$arr = json_decode($resp);
	if($arr->data->image==''){
echo "失败！";
}else{
echo '可能颜龄为：' . $arr->data->image;
}}
function get($url){//带超时的，get访问
$opts = array( 
	'http'=>array( 
	'method'=>"GET", 
	'timeout'=>1, //单位是秒
) ); 
// if($ck=file_get_contents($url, false, stream_context_create($opts))===FALSE){
// return FALSE;
// }
return file_get_contents($url, false, stream_context_create($opts));
}
function post($url,$data){//带超时的，post访问,提交的不是数组哦
// $query = http_build_query($data); 
$query =$data; 
$options['http'] = array(
     'timeout'=>1,
     'method' => 'POST',
	 'header' => "Content-type: application/x-www-form-urlencoded ",
     'content' => $query,
    );
$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);
return $result;
}
function curlpost($url = '', $param = '') {//带超时的，curlpost访问,提交的是数组哦
if (empty($url) || empty($param)) {
return false;
}
$postUrl = $url;
$curlPost = $param;
$ch = curl_init();
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
curl_setopt($ch, CURLOPT_URL,$postUrl);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
curl_setopt($ch, CURLOPT_TIMEOUT,1);//单位是秒
$data = curl_exec($ch);
curl_close($ch);
return $data."|".$param."|".$curlPost;
}
?>
