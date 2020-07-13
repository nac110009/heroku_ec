<?php
DEFINE("SUBDOMAIN_ID","mc6468dh00pzd48z0ztdfs6dhzv4");

DEFINE("GRANT_TYPE","client_credentials");
DEFINE("CLIENT_ID","cs0zs94b0xsmmtaal1p4ji15");
DEFINE("CLIENT_SECRET","0VzVb7YcZJysuyXid42sGXGn");
DEFINE("ACCOUNT_ID",100019644);

DEFINE("EVENT_DEFINITION_KEY","APIEvent-20a5ff5b-dbf6-40f9-7cc6-4c3dc0026dd8");

class Journey_Entry
{
  // トークン
  private $tokenInfo;
  // イベントID
  private $eventInstanceId;
  // 連絡先キー
  private $contactKey;
  // 会員情報
  private $memberInfo;

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
   * 会員情報のセット
   *
   * @param Array $memberInfo
   * @return
   */
  function setMemberInfo($memberInfo)
  {
    $this->contactKey = $memberInfo['member_id'];
    $day = new DateTime($memberInfo['birthday']);
    $this->memberInfo = [
	  "MemberCode" => $memberInfo['member_id'],
	  "ContactKey" => $this->contactKey,
	  "Email"      => $memberInfo['mail'],
	  "Name"       => $memberInfo['name'],
	  "Gender"     => $memberInfo['sex'],
	  "Birthday"   => $day->format('Y-m-d'),
	  "Payment"    => $memberInfo['payment']
    ];
  }

  /**
   * ジャーニーにエントリー
   *
   * @return
   */
  function entry()
  {
    $apiUrl = $this->tokenInfo->rest_instance_url . "interaction/v1/events";
    $reqPrm = [
      "ContactKey"         => $this->contactKey,
      "EventDefinitionKey" => EVENT_DEFINITION_KEY,
      "Data"               => $this->memberInfo
    ];
    $req = $this->apiPostRequest($apiUrl, $reqPrm);
    $this->eventInstanceId = $req->result->eventInstanceId;
  }

  function getEventInstanceId()
  {
    return $this->eventInstanceId;
  }
}
