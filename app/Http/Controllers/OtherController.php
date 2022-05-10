<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OtherController extends Controller
{
    /**
     * 获取cookie
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCookie(Request $request)
    {
        $result = [
            'code' => 200,
            'msg' => 'success',
            'data' => []
        ];
        try {
            $rules = [
                'username' => 'required',
                'password' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $result['code'] = -1;
                $result['msg'] = $validator->errors()->first();
                return response()->json($result);
            }
            $param = $validator->validated();
            $res = $this->handleGetCookie($param);
            $result['data']['cookie'] = $res;
            return response()->json($result);
        } catch (\Exception $error) {
            $result['code'] = -1;
            $result['msg'] = $error->getMessage();
            return response()->json($result);
        }
    }

    /**
     * 处理操作
     * @param $param
     */
    public function handleGetCookie($param)
    {
        $result = [];
        //处理参数
        $requestData = $this->makeParam($param);
        if (empty($requestData)) {
            throw new \Exception('参数错误');
        }
        //请求API
        $method = 'POST';
        $url = 'http://www.saywash.com/saywash/WashCallManager/j_spring_security_check';
        $requestResult = $this->sendRequest($method, $url, $requestData);
        //cookie
        $headerList = explode(PHP_EOL, $requestResult['header']);
        if (empty($headerList)) {
            throw new \Exception('Login Error');
        }
        foreach ($headerList as $item) {
            if ((strpos($item, 'Location') !== false) && (strpos($item, 'Failed') !== false)) {
                throw new \Exception('Failed Login');
            }
            if ((strpos($item, 'Set-Cookie') !== false) && (strpos($item, 'SESSION') !== false)) {
                $itemTrim = trim($item, 'Set-Cookie:');
                $cookieItemList = explode(';', $itemTrim);
                $cookieItem = trim(current($cookieItemList));
                $result = $cookieItem;
                break;
            }
        }
        return $result;
    }

    /**
     * 处理参数
     * @param $param
     * @return array
     */
    public function makeParam($param)
    {
        $result = [];
        $fieldPrefix = 'j_';
        if (!empty($param)) {
            foreach ($param as $key => $value) {
                $result[$fieldPrefix . $key] = $this->makeRsaEncrypt($value);
            }
        }
        return $result;
    }

    /**
     * @return mixed|string
     */
    public function returnPublicKey()
    {
        $publicKey = "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCrkSPcC53LLNFA7Tate6q0m5h7Pt/A58nt2Mb43XUnt5CMQDWgOwYlSdxl575IMREpxRSjNZ80ZLG+s/8ZEyePMCW51pIsaGvlDx6XjGOpgEhD6PKOll4K+8dT54jxaAV37B2hW0cQrwNhlTSFBGOtBAvEsTs5E9aJu+Tpkh9zwQIDAQAB";
        $publicKey = "-----BEGIN PUBLIC KEY-----\n" . wordwrap($publicKey, 64, "\n", true) . "\n-----END PUBLIC KEY-----\n";
        return $publicKey;
    }

    /**
     * 加密
     * @param $input
     * @return string
     */
    public function makeRsaEncrypt($input)
    {
        $output = '';
        $publicKey = $this->returnPublicKey();
        openssl_public_encrypt($input, $output, $publicKey);
        return base64_encode($output);
    }

    /**
     * @param string $method
     * @param $url
     * @param array $data
     * @return bool|string
     */
    public function sendRequest($method = 'GET', $url, $data = [])
    {
        $result = '';
        switch ($method) {
            case 'POST';
                $result = $this->post($url, $data);
                break;
        }
        return $result;
    }

    /**
     * @param $url
     * @param array $data
     * @return bool|string
     */
    public function post($url, $data = [])
    {
        $param = !empty($data) ? http_build_query($data) : '';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $param);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $header = curl_exec($curl);
        $curlGetInfo = curl_getinfo($curl);
        $result = [
            'http_code' => $curlGetInfo['http_code'] ?? 500,
            'header' => $header,
            'redirect_url' => $curlGetInfo['redirect_url'] ?? '',
        ];
        return $result;
    }

}
