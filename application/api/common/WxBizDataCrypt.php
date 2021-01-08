<?php

namespace app\api\common;

/**
 * 对微信小程序用户加密数据的解密示例代码.
 *
 * @copyright Copyright (c) 1998-2014 Tencent Inc.
 */

/**
 * error code 说明.
 * <ul>

 *    <li>-41001: encodingAesKey 非法</li>
 *    <li>-41003: aes 解密失败</li>
 *    <li>-41004: 解密后得到的buffer非法</li>
 *    <li>-41005: base64加密失败</li>
 *    <li>-41016: base64解密失败</li>
 * </ul>
 */
// $appid = 'wx4f4bc4dec97d474b';
// $sessionKey = 'tiihtNczf5v6AKRyjwEUhQ==';

// $encryptedData="CiyLU1Aw2KjvrjMdj8YKliAjtP4gsMZM
//                 QmRzooG2xrDcvSnxIMXFufNstNGTyaGS
//                 9uT5geRa0W4oTOb1WT7fJlAC+oNPdbB+
//                 3hVbJSRgv+4lGOETKUQz6OYStslQ142d
//                 NCuabNPGBzlooOmB231qMM85d2/fV6Ch
//                 evvXvQP8Hkue1poOFtnEtpyxVLW1zAo6
//                 /1Xx1COxFvrc2d7UL/lmHInNlxuacJXw
//                 u0fjpXfz/YqYzBIBzD6WUfTIF9GRHpOn
//                 /Hz7saL8xz+W//FRAUid1OksQaQx4CMs
//                 8LOddcQhULW4ucetDf96JcR3g0gfRK4P
//                 C7E/r7Z6xNrXd2UIeorGj5Ef7b1pJAYB
//                 6Y5anaHqZ9J6nKEBvB4DnNLIVWSgARns
//                 /8wR2SiRS7MNACwTyrGvt9ts8p12PKFd
//                 lqYTopNHR1Vf7XjfhQlVsAJdNiKdYmYV
//                 oKlaRv85IfVunYzO0IKXsyl7JCUjCpoG
//                 20f0a04COwfneQAGGwd5oa+T8yO5hzuy
//                 Db/XcxxmK01EpqOyuxINew==";

// $iv = 'r7BXXKkLb8qrSNn05n0qiA==';
// $pc = new WXBizDataCrypt($appid, $sessionKey);
// $errCode = $pc->decryptData($encryptedData, $iv, $data );

// if ($errCode == 0) {
//     print($data . "\n");
// } else {
//     print($errCode . "\n");
// }

class WxBizDataCrypt
{
    private $appid;
    private $sessionKey;

    private $OK = 0;
    private $IllegalAesKey = -41001;
    private $IllegalIv = -41002;
    private $IllegalBuffer = -41003;
    private $DecodeBase64Error = -41004;

    /**
     * 构造函数
     * @param $sessionKey string 用户在小程序登录后获取的会话密钥
     * @param $appid string 小程序的appid
     */
    public function __construct($appid, $sessionKey)
    {
        $this->sessionKey = $sessionKey;
        $this->appid = $appid;
    }


    /**
     * 检验数据的真实性，并且获取解密后的明文.
     * @param $encryptedData string 加密的用户数据
     * @param $iv string 与用户数据一同返回的初始向量
     * @param $data string 解密后的原文
     *
     * @return int 成功0，失败返回对应的错误码   string $data
     */
    public function decryptData($encryptedData, $iv, &$data)
    {
        if (strlen($this->sessionKey) != 24) {
            return $this->IllegalAesKey;
        }
        $aesKey=base64_decode($this->sessionKey);

        
        if (strlen($iv) != 24) {
            return $this->IllegalIv;
        }
        $aesIV=base64_decode($iv);

        $aesCipher=base64_decode($encryptedData);

        $result=openssl_decrypt( $aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);

        $dataObj=json_decode( $result );
        if( $dataObj  == NULL )
        {
            return $this->IllegalBuffer;
        }
        if( $dataObj->watermark->appid != $this->appid )
        {
            return $this->IllegalBuffer;
        }
        $data = $result;
        return $this->OK;
    }

}
