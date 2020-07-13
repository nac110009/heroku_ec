<?php
require('/app/FuelSDK/vendor/autoload.php');

use FuelSdk\ET_Client;
use FuelSdk\ET_DataExtension;
use FuelSdk\ET_DataExtension_Column;
use FuelSdk\ET_DataExtension_Row;

class Data_Extension
{
  // クライアント
  private $client;

  /**
   * コンストラクタ
   *
   */
  function __construct()
  {
    // クライアントの取得
    $this->client = new ET_Client();
  }

  /**
   * 会員IDからLINE UIDの取得
   *
   * @param string $mid
   * @return
   */
  function getLineUid($mid)
  {
    try {
      $deRow = new ET_DataExtension_Row();
      $deRow->authStub = $this->client;
      $deRow->Name = 'MemberSendInfo';
      $deRow->props = array('MemberCode', 'AddressId');
      $deRow->filter = array('Property' => 'MemberCode','SimpleOperator' => 'equals','Value' => $mid);
      $response = $deRow->get();
      return $response->results[0]->Properties->Property[1]->Value;
    } catch (Exception $e) {
	  echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
  }
}
