# 使用PHP抓取Bing每日图像并为己所用
Bing搜索的首页每天都会推送一张很漂亮的图片，把它保存下来，当做电脑桌面或是自己的网站背景图还不是美滋滋……

既然要抓取这张图片，首先就得弄清这张图是从何而来的。经过对必应首页的抓包，我们可以获得首页图的获取API。它的格式是这样的：

```
http://cn.bing.com/HPImageArchive.aspx?format=js&idx=0&n=1
```
注意，这里有几个GET参数，它们的作用分别是：

1. n，必要参数。这是输出信息的数量。比如n=1，即为1条，以此类推，至多输出8条。
2. format，非必要。返回结果的格式，不存在或者等于xml时，输出为xml格式，等于js时，输出json格式
3. idx，非必要。不存在或者等于0时，输出当天的图片，-1为已经预备用于明天显示的信息，1则为昨天的图片，以此类推，idx最多获取到前16天的图片信息

这里将n设定为1、format设定为js、idx设定为1，去发出GET请求，返回的数据是这样的：

```
{
    "images": [
        {
            "startdate": "20161222",
            "fullstartdate": "201612221600",
            "enddate": "20161223",
            "url": "/az/hprichbg/rb/TheDomeEdinburgh_ZH-CN11993142817_1920x1080.jpg",
            "urlbase": "/az/hprichbg/rb/TheDomeEdinburgh_ZH-CN11993142817",
            "copyright": "爱丁堡一家叫做The Dome的夜店，苏格兰 (© Marty McKillop/500px)",
            "copyrightlink": "http://www.bing.com/search?q=The+Dome,+Edinburgh&form=hpcapt&mkt=zh-cn",
            "quiz": "/search?q=Bing+homepage+quiz&filters=WQOskey:%22HPQuiz_20161222_TheDomeEdinburgh%22&FORM=HPQUIZ",
            "wp": false,
            "hsh": "376393c9b49c6d8d1a6e7c2d38343105",
            "drk": 1,
            "top": 1,
            "bot": 1,
            "hs": []
        }
    ],
    "tooltips": {
        "loading": "正在加载...",
        "previous": "上一个图像",
        "next": "下一个图像",
        "walle": "此图片不能下载用作壁纸。",
        "walls": "下载今日美图。仅限用作桌面壁纸。"
    }
}
```
其中的“images”节点下的“url”值便是我们要获取的图像地址。我们把它取出来，再加上Bing的网址前缀(http://cn.bing.com)即组合成了完整的图像地址。比如说上面返回数据的完整图像地址是这样的：

```
http://cn.bing.com/az/hprichbg/rb/TheDomeEdinburgh_ZH-CN11993142817_1920x1080.jpg
```
知道了背景图的获取方式，接下来就是用PHP去动态抓取了。

如果你只是单纯的想用作网页背景的话，你只需新建一个php文件，里面贴入如下代码：

```php
<?php
$str = file_get_contents('http://cn.bing.com/HPImageArchive.aspx?idx=0&n=1');   // 从bing获取数据
 
if(preg_match('/<url>([^<]+)<\/url>/isU', $str, $matches)) { // 正则匹配抓取图片url
    $imgurl = 'http://cn.bing.com'.$matches[1];
} else {  // 如果由于某些原因，没抓取到图片地址
    $imgurl = 'http://img.infinitynewtab.com/InfinityWallpaper/2_14.jpg'; // 使用默认的图像(默认图像链接可修改为自己的)
}
 
header("Location: {$imgurl}");    // 跳转至目标图像
```
然后把这个php文件上传到你的服务器或者是网站空间，访问这个php应该就能看到被跳转到了Bing的图片。

使用方法：直接将那个php文件的绝对地址当做图片放进网页中即可。

比如说，如果你的这个php的地址为“http://www.myweb.cn/bing.php”，那么你在你自己的网页的css中这么写就能当背景使用了：
```
body{
    width:100%;
    height:100%;
    background: url(http://www.myweb.cn/bing.php) no-repeat;
    -moz-background-size: cover;    /*背景图片拉伸以铺满全屏*/
    -ms-background-size: cover;
    -webkit-background-size: cover;
    background-size: cover;
}
```
以上方法只是简单地跳转，如果想要抓取这张图片并保存到服务器呢？这里直接贴代码：
```
<?php

$path = 'temp';   //设置图片缓存文件夹
$filename = date("Ymd") . '.jpg';  //用年月日来命名新的文件名
if (!file_exists($path.'/'. $filename))    //如果文件不存在，则说明今天还没有进行缓存
{
    if(!file_exists($path)) //如果目录不存在
    {
        mkdir($path, 0777); //创建缓存目录
    }
    $str = file_get_contents('http://cn.bing.com/HPImageArchive.aspx?format=js&idx=0&n=1'); //读取必应api，获得相应数据
    $str = json_decode($str,true);
    $imgurl = 'http://cn.bing.com'.$str['images'][0]['url'];    //获取图片url
    $img = grabImage($imgurl, $path.'/'.$filename); //读取并保存图片
    $handle = fopen("dat.txt", "a");    //用于存放图片信息，如果不需要保存图片的相关信息，可以把下面这些去掉。
    if ($handle)
    {
        $copyright = $str['images'][0]['copyright'];    //说明
        $startdate = $str['images'][0]['startdate'];
        $fullstartdate = $str['images'][0]['fullstartdate'];
        $enddate = $str['images'][0]['enddate'];
        $urlbase = $str['images'][0]['urlbase'];
        $copyrightlink = $str['images'][0]['copyrightlink'];
        $quiz = $str['images'][0]['quiz'];
        $wp = $str['images'][0]['wp'];
        $hsh = $str['images'][0]['hsh'];
        $drk = $str['images'][0]['drk'];
        $top = $str['images'][0]['top'];
        $bot = $str['images'][0]['bot'];
        $tempArr = array("imgurl"=>$imgurl,"copyright"=>$copyright, "startdate"=>$startdate,
        "fullstartdate"=>$fullstartdate, "enddate"=> $enddate, "urlbase"=>$urlbase,
        "copyrightlink"=> $copyrightlink, "quiz"=>$quiz, "wp"=> $wp,
        "hsh"=>$hsh,"drk"=>$drk, "top"=> $top, "bot"=> $bot);   //将相关信息放进数组中
        fwrite($handle, json_encode($tempArr) ."\r\n"); //最终以json格式保存在文本文档中
        fclose($handle);
    }
}
/**
 * 远程抓取图片并保存
 * @param $url 图片url
 * @param $filename 保存名称和路径
 */
function grabImage($url, $filename = "")
{
    if($url == "") return false; //如果$url地址为空，直接退出
    if ($filename == "") //如果没有指定新的文件名
    {
        $ext = strrchr($url, ".");  //得到$url的图片格式
        $filename = date("Ymd") . $ext;  //用天月面时分秒来命名新的文件名
    }
    ob_start();         //打开输出
    readfile($url);     //输出图片文件
    $img = ob_get_contents();   //得到浏览器输出
    ob_end_clean();             //清除输出并关闭
    $size = strlen($img);       //得到图片大小
    $fp2 = @fopen($filename, "a");
    fwrite($fp2, $img);         //向当前目录写入图片文件，并重新命名
    fclose($fp2);
    return $filename;           //返回新的文件名
}
```
这样，如果这个php被访问，它就会自动启动抓取并保存。你可以用阿里云监控或其他类型的网站监控服务来实现每天自动运行这个php。

不过呢，这里也有免费的api，链接：http://api.mkblog.cn/bing/jump.php

效果：![](http://api.mkblog.cn/bing/jump.php)
