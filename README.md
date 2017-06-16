# lingbei
领呗商城


获取签名示例
<pre><code>/**
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
            "key"=> "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ",  //此参数必须传入，获取sign
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
    }</code></pre>
