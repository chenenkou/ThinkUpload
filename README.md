# ThinkUpload

ThinkUpload是一个PHP文件上传工具。目前支持本地，FTP，七牛，又拍云，新浪SAE，百度云存储BCS上传处理。

## ThinkUpload 怎么使用？

ThinkUpload的使用比较简单，你只需要引入ThinkUpload类，实例化一个ThinkUpload的对象并传入要使用的图片处理库类型和要处理的图片，就可以对图片进行操作了。关键代码如下：（以ThinkPHP为例，非ThinkPHP框架请使用PHP原生的文件引入方法）
``` php
//引入上传处理库
import('ORG.Util.ThinkUpload.Upload');
// 配置并实列化
$config = array(); // 上传配置
$driver = ''; // 上传驱动
$driverConfig = array(); // 上传驱动配置
$Upload = new Upload($config, $driver, $driverConfig);
// 上传文件
$info = $Upload->upload($_FILES);
// 获取错误信息
if (!$info) $info = $Upload->getError();
```

## ThinkUpload有哪些配置？
``` php
$config = array(
    'mimes'         =>  array(), //允许上传的文件MiMe类型
    'maxSize'       =>  0, //上传的文件大小限制 (0-不做限制)
    'exts'          =>  array(), //允许上传的文件后缀
    'autoSub'       =>  true, //自动子目录保存文件
    'subName'       =>  array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
    'rootPath'      =>  './Uploads/', //保存根路径
    'savePath'      =>  '', //保存路径
    'saveName'      =>  array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
    'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
    'replace'       =>  false, //存在同名是否覆盖
    'hash'          =>  true, //是否生成hash编码
    'callback'      =>  false, //检测文件是否存在回调，如果存在返回文件信息数组
    'driver'        =>  '', // 文件上传驱动
    'driverConfig'  =>  array(), // 上传驱动配置
);
```
