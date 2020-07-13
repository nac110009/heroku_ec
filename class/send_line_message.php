<?php
DEFINE("SUBDOMAIN_ID","mc6468dh00pzd48z0ztdfs6dhzv4");

DEFINE("GRANT_TYPE","client_credentials");
DEFINE("CLIENT_ID","isndt5tkxhl8iw87e6zcrye2");
DEFINE("CLIENT_SECRET","nRtV80TJg1o8ZZukpWA8IvlH");
DEFINE("ACCOUNT_ID",100019644);

DEFINE("LINE_SEND_ID","1622341408");

class Send_LINE_Message
{
  // トークン
  private $tokenInfo;
  // LINE UID
  private $lineUid;
  // LINEメッセージコンテンツ（最大5つ）
  private $lineMessageContents = array();
  // OTTリクエストID
  private $ottRequestId;

  /**
   * コンストラクタ
   *
   */
  function __construct()
  {
    // アクセストークンの取得
    $this->getAccessToken();
    // ユーザー情報取得
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
//    return ["code" => $httpcode, "result" => json_decode($result)];
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
   * LINE UIDのセット
   *
   * @param string $lineUid
   * @return
   */
  function setLineUid($uid)
  {
    $this->lineUid = $uid;
  }

  /**
   * LINEメッセージ送信
   *
   * @return
   */
  function sendLineMsg()
  {
    $apiUrl = $this->tokenInfo->rest_instance_url . "ott/v1/send";
    $messageKey = "TEST20200414";
    $reqPrm = [
      "messageKey"      => $messageKey,
      "from"            => ["senderType" => "line", "senderId" => LINE_SEND_ID],
      "to"              => ["ottId" => $this->lineUid],
      "message"         => ["contents" => $this->lineMessageContents, "customKeys" => array()],
      "validityPeriod"  => 30
    ];
    $req = $this->apiPostRequest($apiUrl, $reqPrm);
    $this->ottRequestId = $req->result->ottRequestId;
  }

  function getOttRequestId()
  {
    return $this->ottRequestId;
  }

  /**
   * テキストメッセージの設定
   *
   * @param string $text
   * @return
   */
  function setTextMessage($textStr)
  {
  	// 
    $contents = [
      "type"          => "text",
      "text"          => $textStr
    ];
    $this->lineMessageContents[] = $contents;
  }
}
