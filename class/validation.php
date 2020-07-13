<?php
class Validation
{
  private $error = array();

  /**
   * コンストラクタ
   *
   */
  function __construct()
  {
    // エラーメッセージ配列
    $this->error = array();
  }

  /**
   * バリデーションチェック
   *
   * @param Array $array チェック文字列
   * @return Array $errors エラーメッセージ配列
   */
  function valid_check($array)
  {
    $this->valid_check_name($array['name']);
    $this->valid_check_sex($array['sex']);
    $this->valid_check_birthday($array['birthday']);
    $this->valid_check_postal_code($array['postal_code']);
    $this->valid_check_address($array['address']);
    $this->valid_check_mail($array['mail']);
    $this->valid_check_password($array['password']);
    $this->valid_check_phone_number($array['phone_number']);
    $this->valid_check_payment($array['payment']);
    return $errors = $this->error;
  }

  /**
   * 【名前】チェック
   *
   * @param String $str チェック文字列
   */
  function valid_check_name($str): void
  {
    // 未入力
    if ($str == null || strcmp($str, "") == 0) {
      $this->error[] = '【 名前 】入力されていません。';
    }
    // 文字数オーバー
    if (strlen($str) > 50) {
      $this->error[] = '【 名前 】文字数が50文字を超えています。';
    }
  }

  /**
   * 【性別】チェック
   *
   * @param String $str チェック文字列
   */
  function valid_check_sex($str): void
  {
    // 未選択
    if ($str == null || strcmp($str, "") == 0) {
      $this->error[] = '【 性別 】選択されていません。';
    }
  }


  /**
   * 【生年月日】チェック
   *
   * @param String $str チェック文字列
   */
  function valid_check_birthday($str): void
  {
    // 半角数字かつ8文字でない場合
    if (!preg_match("/^[0-9]{8}+$/", $str)) {
      $this->error[] = '【 生年月日 】半角数字8文字で入力してください。';
    }
  }

  /**
   * 【郵便番号】チェック
   *
   * @param String $str チェック文字列
   */
  function valid_check_postal_code($str): void
  {
    // 半角数字かつ7文字でない場合
    if (!preg_match("/^[0-9]{7}+$/", $str)) {
      $this->error[] = '【 郵便番号 】半角数字7文字で入力してください。';
    }
  }

  /**
   * 【住所】チェック
   *
   * @param String $str チェック文字列
   */
  function valid_check_address($str): void
  {
    // 未入力
    if ($str == null || strcmp($str, "") == 0) {
      $this->error[] = '【 住所 】入力されていません。';
    }
    // 文字数オーバー
    if (strlen($str) > 255) {
      $this->error[] = '【 住所 】文字数が255文字を超えています。';
    }
  }

  /**
   * 【メールアドレス】チェック
   *
   * @param String $str チェック文字列
   */
  function valid_check_mail($str): void
  {
    // メールアドレス形式でない場合
    if (!preg_match('/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/iD', $str)) {
      $this->error[] = '【 メールアドレス 】正しい形式で入力してください。';
    }
  }

  /**
   * 【パスワード】チェック
   *
   * @param String $str チェック文字列
   */
  function valid_check_password($str): void
  {
    // 半角英数かつ8文字以上でない場合
    if (!preg_match("/^[a-zA-Z0-9]{8,}+$/", $str)) {
      $this->error[] = '【 パスワード 】半角英数8文字以上で入力してください。';
    }
  }

  /**
   * 【電話番号】チェック
   *
   * @param String $str チェック文字列
   */
  function valid_check_phone_number($str): void
  {
    // 任意項目のため入力があった時だけ判定
    if (!$str == null || !strcmp($str, "") == 0) {
      // 半角数字かつ11文字以内でない場合
      if (!preg_match("/^[0-9]{10,11}+$/", $str)) {
        $this->error[] = '【 電話番号 】半角数字10文字または11文字で入力してください。';
      }
    }
  }

  /**
   * 【支払い方法】チェック
   *
   * @param String $str チェック文字列
   */
  function valid_check_payment($str): void
  {
    // 未選択
    if ($str == null || strcmp($str, "") == 0) {
      $this->error[] = '【 支払い方法 】選択されていません。';
    }
  }
}
