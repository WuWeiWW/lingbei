<?php

namespace Common\Util;

/**
 * 领呗API操作类
 * Class LingbeiApi
 * @package lingbei\api
 */
class LingbeiApi
{

    private $url = "http://localhost:91/index.php/Api/Api/recharge";


    /**
     * 充值示例
     */
    public function index()
    {
        $lingbeiApi = new LingbeiApi();
        ## 将传的值键值对 排列  如
        $randStr = $lingbeiApi->getRandChar(32);
        $data = array(
            "appid"=> "5XkDYhfz1agrJmPAc2VRzYa62VhPV3TD",
            "r_str"=> $randStr,
            "time"=> time(),
            "remark"=> "充值给子公司",
            "recharge_amount"=> "100",
            "recharge_mobile"=> "13111111111",
            "recharge_type"=> "1",
            "key"=> "fXdtDNT1VupJ1bSCzIMT0YfQNbz6TUWG",
        );
        $result = $lingbeiApi->sendTransfer($data);

    }

    /**
     * 进行转账
     * @param array $data 发送的数据
     * @return mixed 返回数据
     */
    function sendTransfer($data=array())
    {

        $sign = $this->getSign($data);
        $data['sign'] = $sign;
        unset($data['key']); //生成签名后，移除key
        $str = json_encode($data);
        $result = $this->sendPost($str);
        return json_decode($result,true);
    }

    function sendPost($data=""){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,            $this->url );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_POST,           1 );
        curl_setopt($ch, CURLOPT_POSTFIELDS,     $data );
        curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/plain'));
        $return = curl_exec($ch);
        return $return;
    }

    /**
     *  检测签名
     * @param $data
     * @return bool 签名是否正确
     */
    function checkSign($data,$key="")
    {
        foreach ($data as $k => $v) {
            $Parameters[strtolower($k)] = $v;
        }
        $sign = $Parameters['sign']; //获取 传入的 sign
        unset($Parameters['key']); // 不进入加密
        unset($Parameters['sign']); //不进入加密

        //签名步骤一：按字典序排序参数 并组装成 key=value&key2=value2
        ksort($Parameters);
        $String = $this->formatBizQueryParaMap($Parameters, false);
//签名步骤二：在string后加入KEY
        $String = $String . "&key=" . $key;
        //签名步骤三：MD5加密
        $result = strtoupper(md5($String));
        return $sign == $result;
    }




    /**
     * 获取指定长度的随机字符串
     * @param int $length
     * @return string 指定长度的字符串
     */
    function getRandChar($length)
    {
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol) - 1;
        for ($i = 0; $i < $length; $i++) {
            $str .= $strPol[rand(0, $max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }
        return $str;
    }

    /**
     * 获得签名
     * @param $data
     * 传入参数示例:$data = array(
             appid"=> "5XkDYhfz1agrJmPAc2VRzYa62VhPV3TD",
            "r_str"=> "6vL0Xff7hJy9aax2dgyhwfrPGQw9NgFe",
            "time"=> "1492478454",
            "remark"=> "充值给子公司",
            "recharge_amount"=> "100",
            "recharge_mobile"=> "13111111111",
            "recharge_type"=> "1",
            "key"=> "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ",  //次参数必须传入，获取sign
            );
     * code
     * @return string
     */
    function getSign($data)
    {
        foreach ($data as $k => $v) {
            $Parameters[strtolower($k)] = $v;
        }
        $key = $Parameters['key'];
        unset($Parameters['key']); //key 添加到最后一个参数

        //签名步骤一：按字典序排序参数 并组装成 key=value&key2=value2
        ksort($Parameters);
        $String = $this->formatBizQueryParaMap($Parameters, false);

        //签名步骤二：在string后加入KEY
        $String = $String . "&key=" . $key;
        //签名步骤三：MD5加密
        $result = strtoupper(md5($String));
        return $result;
    }

    /**
     * 将数组转成uri字符串
     * @param array $paraMap
     * @param bool $urlencode
     * @return string
     */
    function formatBizQueryParaMap($paraMap, $urlencode)
    {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v) {
            if ($urlencode) {
                $v = urlencode($v);
            }
            $buff .= strtolower($k) . "=" . $v . "&";
        }
        $reqPar;
        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff) - 1);
        }
        return $reqPar;
    }
}







