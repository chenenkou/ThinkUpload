<?php

class Local
{
    /**
     * 上传文件根目录
     * @var string
     */
    private $rootPath;

    /**
     * 本地上传错误信息
     * @var string
     */
    private $error = ''; //上传错误信息

    /**
     * 构造函数，用于设置上传根路径
     * Local constructor.
     * @param string $root 根目录
     * @param null $config
     */
    public function __construct($root, $config = null){
        $this->rootPath = $root;
    }

    /**
     * 检测上传根目录
     * @return boolean true-检测通过，false-检测失败
     */
    public function checkRootPath(){
        if(!(is_dir($this->rootPath) && is_writable($this->rootPath))){
            $this->error = '上传根目录不存在！请尝试手动创建:'.$this->rootPath;
            return false;
        }
        return true;
    }

    /**
     * 检测上传目录
     * @param  string $savepath 上传目录
     * @return boolean          检测结果，true-通过，false-失败
     */
    public function checkSavePath($savepath){
        /* 检测并创建目录 */
        if (!$this->mkdir($savepath)) {
            return false;
        } else {
            /* 检测目录是否可写 */
            if (!is_writable($this->rootPath . $savepath)) {
                $this->error = '上传目录 ' . $savepath . ' 不可写！';
                return false;
            } else {
                return true;
            }
        }
    }

    /**
     * 保存指定文件
     * @param  array   $file    保存的文件信息
     * @param  boolean $replace 同名文件是否覆盖
     * @return boolean          保存状态，true-成功，false-失败
     */
    public function save($file, $replace=true) {
        $filename = $this->rootPath . $file['savepath'] . $file['savename'];

        /* 不覆盖同名文件 */
        if (!$replace && is_file($filename)) {
            $this->error = '存在同名文件' . $file['savename'];
            return false;
        }

        /* 移动文件 */
        if (!move_uploaded_file($file['tmp_name'], $filename)) {
            $this->error = '文件上传保存错误！';
            return false;
        }

        return true;
    }

    /**
     * 保持指定网络文件
     * @param array $file 保存的文件信息
     * @param bool $replace 同名文件是否覆盖
     * @return bool 保存状态，true-成功，false-失败
     */
    public function put($file, $replace=true)
    {
        $filename = $this->rootPath . $file['savepath'] . $file['savename'];

        /* 不覆盖同名文件 */
        if (!$replace && is_file($filename)) {
            $this->error = '存在同名文件' . $file['savename'];
            return false;
        }

        /* 移动文件 */
        if (!$this->move_file($file['tmp_name'], $filename)) {
            $this->error = '文件上传保存错误！';
            return false;
        }

        return true;
    }

    public function move_file($remote = '', $local)
    {
        try {
            $cp = curl_init($remote);
            $fp = fopen($local, "w");
            curl_setopt($cp, CURLOPT_FILE, $fp);
            curl_setopt($cp, CURLOPT_HEADER, 0);
            curl_exec($cp);
            curl_close($cp);
            fclose($fp);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * 创建目录
     * @param  string $savepath 要创建的穆里
     * @return boolean          创建状态，true-成功，false-失败
     */
    public function mkdir($savepath){
        $dir = $this->rootPath . $savepath;
        if(is_dir($dir)){
            return true;
        }

        if(mkdir($dir, 0777, true)){
            return true;
        } else {
            $this->error = "目录 {$savepath} 创建失败！";
            return false;
        }
    }

    /**
     * 获取最后一次上传错误信息
     * @return string 错误信息
     */
    public function getError(){
        return $this->error;
    }
}