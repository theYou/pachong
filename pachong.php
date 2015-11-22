<?php
function _getUrlContent($url)
{
$handle = fopen($url, "r");
if($handle)
{
$content = stream_get_contents($handle,1024*1024);
return $content;
}
else
{
return false;
}
}
function _filterUrl($web_content)
{
$reg_tag_a = '((http|ftp|https)://)(([a-zA-Z0-9\._-]+\.[a-zA-Z]{2,6})|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,4})*(/[a-zA-Z0-9\&%_\./-~-]*)?';
$result = preg_match_all($reg_tag_a,$web_content,$match_result);
if($result)
{
return $match_result[1];
}
}
function _reviseUrl($base_url,$url_list)
{
$url_info = parse_url($base_url);
$base_url = $url_info["scheme"].'://';
if($url_info["user"]&&$url_info["pass"])
{
$base_url .= $url_info["user"].":".$url_info["pass"]."@";
}
$base_url .= $url_info["host"];
if($url_info["port"]){
$base_url .= ":".$url_info["port"];
}
$base_url .= $url_info["path"];
print_r($base_url);
if(is_array($url_list)){
foreach ($url_list as $url_item) 
{
if(preg_match('/^http/',$url_item))
{
//已经是完整的url
$result[] = $url_item;
}
else
{
//不完整的url
$real_url = $base_url.'/'.$url_item;
$result[] = $real_url;
}
}
return $result;
}
else 
{
return;
}
}
function crawler($url){
$content = _getUrlContent($url);
if($content){
$url_list = _reviseUrl($url,_filterUrl($content));
if($url_list){
return $url_list;
}
else 
{
return ;
}
}
else
{
return ;
}
}
function main(){
$current_url = "http://hao123.com/";//初始url
$fp_puts = fopen("url.txt","ab");//记录url列表
$fp_gets = fopen("url.txt","r");//保存url列表
do
{
$result_url_arr = crawler($current_url);
if($result_url_arr)
{
foreach ($result_url_arr as $url)
{
fputs($fp_puts,$url."\r\n");
}
}
}while ($current_url = fgets($fp_gets,1024));//不断获得url
}
main();
?>