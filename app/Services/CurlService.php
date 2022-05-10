<?php

namespace App\Services;

class CurlService
{
    private $curl;
    private $query = '';
    private $user_agent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/101.0.4951.54 Safari/537.36';
    private $cookie_file;

    public function __construct()
    {
        $this->curl = curl_init(); // 初始化
        $this->cookie_file = storage_path() . '/logs/cookies.txt';
    }

    public function request($url, $data = [], $type = 'GET')
    {
        $ret = '';

        switch ($type) {

            case 'GET';
                $ret = $this->get($url, $data);
                break;

            case 'POST';
                $ret = $this->post($url, $data);
                break;

        }

        return $ret;
    }

    // 模拟浏览器get请求
    public function get($url, $data)
    {
        if ($data) {
            foreach ($data as $key => $value) {
                $this->query .= $key . '=' . $value;
            }

            $url .= '?' . $this->query;
        }

        curl_setopt($this->curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
        curl_setopt($this->curl, CURLOPT_USERAGENT, $this->user_agent); // 模拟用户使用的浏览器
        @curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($this->curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($this->curl, CURLOPT_HTTPGET, 1); // 发送一个常规的Get请求
        curl_setopt($this->curl, CURLOPT_COOKIEFILE, $this->cookie_file); // 读取上面所储存的Cookie信息
        curl_setopt($this->curl, CURLOPT_TIMEOUT, 120); // 设置超时限制防止死循环
        curl_setopt($this->curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        $tmpInfo = curl_exec($this->curl); // 执行操作

        return $tmpInfo;
    }

    // 模拟浏览器post请求
    public function post($url, $data)
    {
        curl_setopt($this->curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
        curl_setopt($this->curl, CURLOPT_USERAGENT, $this->user_agent); // 模拟用户使用的浏览器
        @curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($this->curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($this->curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($this->curl, CURLOPT_COOKIEFILE, $this->cookie_file); // 保存cookie
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
        curl_setopt($this->curl, CURLOPT_TIMEOUT, 120); // 设置超时限制防止死循环
        curl_setopt($this->curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回

        $tmpInfo = curl_exec($this->curl); // 执行操作

        return $tmpInfo;
    }

    public function __destruct()
    {
        //关闭URL请求
        curl_close($this->curl);
    }
}
