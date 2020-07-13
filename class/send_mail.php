<?php
DEFINE("SUBDOMAIN_ID","mc6468dh00pzd48z0ztdfs6dhzv4");

DEFINE("GRANT_TYPE","client_credentials");
DEFINE("CLIENT_ID","5l0bxyxg22b8veuldtle5ax6");
DEFINE("CLIENT_SECRET","LOjvvn5oa8tt5hFMx05aguL1");
DEFINE("ACCOUNT_ID",100019644);

DEFINE("DEFAULT_TRIGGER","TriggerMailTest");
DEFINE("DEFAULT_FROM_NAME","株式会社エヌ・エイ・シー");
DEFINE("DEFAULT_FROM_MAIL","info@hc.nac-care.com");

class Send_Mail
{
  // トークン
  private $tokenInfo;
  // トリガーメールの外部キー
  private $triggerKey;
  // 購読者キー
  private $subscriberKey;
  // メールアドレス
  private $mailAddress;
  // リクエストID
  private $requestId;
  // レスポンス
  private $responses = array();

  /**
   * コンストラクタ
   *
   */
  function __construct()
  {
    // アクセストークンの取得
    $this->getAccessToken();
    // カスタマーキーの初期化
    $this->triggerKey = DEFAULT_TRIGGER;
  }

  /**
   * ヘッダ定義
   *
   * @return string $header
   */
  function getHeader()
  {
    if (!$this->tokenInfo) {
      return array(
	    'Content-Type: application/json; charser=UTF-8'
      );
    } else {
      return array(
	    'Content-Type: application/json',
	    'Authorization: Bearer ' . $this->tokenInfo->access_token
      );
    }
  }

  /**
   * POSTでのAPI実行処理
   *
   * @param int $code
   * @param array $result
   * @return stdClass $stdRes
   */
  function setResult($code, $result)
  {
    $stdRes = new stdClass;
    $stdRes->status = ($code >= 200 && $code < 300);
    $stdRes->result = json_decode($result);
    return $stdRes;
  }

  /**
   * POSTでのAPI実行処理
   *
   * @param string $url
   * @param array $req
   * @return array $result
   */
  function apiPostRequest($url, $req)
  {
  	// APIをPOSTで実行
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($req, JSON_UNESCAPED_UNICODE));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getHeader());
    $result = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    curl_close($ch);
    return $this->setResult($code, $result);
  }

  /**
   * GETでのAPI実行処理
   *
   * @param string $url
   * @return array $result
   */
  function apiGetRequest($url)
  {
  	// 
    $ch = curl_init($url);
    curl_setopt($ch3, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getHeader());
    $result = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    curl_close($ch);
    return $this->setResult($code, $result);
//    return ["code" => $httpcode, "result" => json_decode($result)];
  }

  /**
   * アクセストークン取得
   *
   * @return
   */
  function getAccessToken()
  {
    $tokUrl = "https://" . SUBDOMAIN_ID . ".auth.marketingcloudapis.com/v2/token";
    $reqPrm = [
	  "grant_type"    => GRANT_TYPE,
	  "client_id"     => CLIENT_ID,
	  "client_secret" => CLIENT_SECRET,
	  "account_id"    => ACCOUNT_ID
    ];
    $req = $this->apiPostRequest($tokUrl, $reqPrm);
    $this->tokenInfo = $req->result;
  }

  function getToken()
  {
    return $this->tokenInfo->access_token;
  }

  /**
   * 購読者キーのセット
   *
   * @param string $subscriberKey
   * @return
   */
  function setSubscriberKey($subscriberKey)
  {
    $this->subscriberKey = $subscriberKey;
  }

  /**
   * メールアドレスのセット
   *
   * @param string $mailAddress
   * @return
   */
  function setMailAddress($mailAddress)
  {
    $this->mailAddress = $mailAddress;
  }

  /**
   * メール送信
   *
   * @return
   */
  function sendMail()
  {
    $apiUrl = $this->tokenInfo->rest_instance_url . "/messaging/v1/messageDefinitionSends/key:" . $this->triggerKey . "/send";
    $sKey = ($this->subscriberKey) ? $this->subscriberKey : $this->mailAddress;
    $reqPrm = [
      "From"            => ["Address" => DEFAULT_FROM_MAIL, "Name" => DEFAULT_FROM_NAME],
      "To"              => ["Address" => $this->mailAddress, "SubscriberKey" => $sKey],
      "Options"         => ["RequestType" => "ASYNC"]
    ];
    $req = $this->apiPostRequest($apiUrl, $reqPrm);
    $this->requestId = $req->requestId;
    $this->responses = $req->responses;
  }

  function getRequestId()
  {
    return $this->requestId;
  }
  function getResponses()
  {
    return $this->responses;
  }

}
