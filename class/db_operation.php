<?php
class DB_Operation
{
  // メンバ変数
  private $pdo;

  /**
   * コンストラクタ
   *
   */
  function __construct()
  {
    // PDO接続情報読み込み
    require_once 'class/pdo.php';

    // SQLクエリ読み込み
    require_once 'class/query.php';

    //PDOを利用してDBに接続する
    try {
      $this->pdo = get_pdo();
    } catch (PDOException $e) {
      header('Content-Type: text/plain; charset=UTF-8', true, 500);
      exit($e->getMessage());
    }
  }

  /**
   * 出力文字のサニタイズ
   *
   * @param string $str
   * @return
   */
  function h($str)
  {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
  }

  /**
   * エスケープした文字のデコード
   *
   * @param string $str
   * @return
   */
  function h_dec($str)
  {
    return htmlspecialchars_decode($str, ENT_QUOTES | ENT_HTML5);
  }

  /**
   * SQL実行
   *
   * @param string $query
   * @param array $arrays
   * @param bool $is_single
   * @return array $result
   */
  function execute_quey($query, $arrays = null, $is_single = 'true')
  {
    $stmt = $this->pdo->prepare($query);
    // 引数で配列が渡されたらクエリに値をバインドする
    if (!empty($arrays)) {
      foreach ($arrays as $array) {
        $stmt->bindValue($array[0], $array[1], $array[2]);
      }
    }
    //SQL実行
    try {
      $stmt->execute();
      $result = $stmt->fetchAll();
      if (!empty($result[0]) && empty($result[1]) && $is_single == 'true') {
        // fetchAllで結果を取得すると2次元配列が返ってくる
        // 返す結果が1レコードでいい場合は2次元配列の0番目の配列だけを返す
        return $result = $result[0];
      } else {
        // 複数レコードを返す場合、またはsql実行結果がnullの場合はそのまま返す
        return $result;
      }
    } catch (PDOException $e) {
      header('Content-Type: text/plain; charset=UTF-8', true, 500);
      exit($e->getMessage());
    }
  }

  //****************************************
  //  product_list.php
  //****************************************
  /**
   * 商品一覧を取得する
   *
   * @param
   * @return array $result
   */
  function get_product_list()
  {
    $query = Query::$query_get_product_list;
    return $result = $this->execute_quey($query);
  }

  /**
   * 入力された文字列を元に商品を検索する
   *
   * @param string $search_character
   * @return array $result
   */
  function search_product($search_character)
  {
    $query = Query::$query_search_product;
    $arrays = [
      [':search_character', '%' . $search_character . '%', PDO::PARAM_STR]
    ];
    return $result = $this->execute_quey($query, $arrays, $is_single = 'false');
  }

  //****************************************
  //  product_group_list.php
  //****************************************
  /**
   * 商品グループ一覧を取得する
   *
   * @param
   * @return array $result
   */
  function get_group_list($group_name)
  {
    $query = Query::$query_group_product;
    $arrays = [
      [':product_type', $group_name, PDO::PARAM_STR]
    ];
    return $result = $this->execute_quey($query, $arrays);
  }

  //****************************************
  //  product_detail.php
  //****************************************
  /**
   * 商品詳細を取得する
   *
   * @param string $product_id
   * @return array $result
   */
  function get_product_detail($product_id)
  {
    $query = Query::$query_get_product_detail;
    $arrays = [
      [':product_id', $product_id, PDO::PARAM_STR]
    ];
    return $result = $this->execute_quey($query, $arrays);
  }

  /**
   * カートに商品を追加する
   *
   * @param array $array
   * @return array $result
   */
  function add_to_cart($array)
  {
    $query = Query::$query_add_to_cart_select;
    $arrays = [
      [':member_id', $array['member_id'], PDO::PARAM_STR],
      [':product_id', $array['product_id'], PDO::PARAM_STR]
    ];
    $result = $this->execute_quey($query, $arrays);

    if (!empty($result)) {
      $query = Query::$query_add_to_cart_update;
      $arrays = [
        [':number',     $result['number'] + $array['number'],     PDO::PARAM_STR],
        [':add_time',   $array['add_time'],                       PDO::PARAM_STR],
        [':subtotal',   $result['subtotal'] + $array['subtotal'], PDO::PARAM_INT],
        [':member_id',  $array['member_id'],                      PDO::PARAM_STR],
        [':product_id', $array['product_id'],                     PDO::PARAM_STR]
      ];
      $this->execute_quey($query, $arrays);
      return $message = 'カートの中身を更新しました。';
    } else {
      $query = Query::$query_add_to_cart_insert;
      $arrays = [
        [':session_id', $array['session_id'], PDO::PARAM_STR],
        [':product_id', $array['product_id'], PDO::PARAM_STR],
        [':number',     $array['number'],     PDO::PARAM_INT],
        [':member_id',  $array['member_id'],  PDO::PARAM_STR],
        [':add_time',   $array['add_time'],   PDO::PARAM_STR],
        [':price',      $array['price'],      PDO::PARAM_INT],
        [':subtotal',   $array['subtotal'],   PDO::PARAM_INT]
      ];
      $this->execute_quey($query, $arrays);
      return $message = 'カートに追加しました。';
    }
  }

  //****************************************
  //  purchase_cart.php
  //****************************************
  /**
   * カートに追加した商品の一覧を表示する
   *
   * @param array $member_id
   * @return array $result
   */
  function get_cart_list($member_id)
  {
    $query = Query::$query_get_cart_list;
    $arrays = [
      [':member_id', $member_id, PDO::PARAM_STR]
    ];
    return $result = $this->execute_quey($query, $arrays, $is_single = 'false');
  }

  /**
   * カートの合計金額を取得する
   *
   * @param array $member_id
   * @return array $result
   */
  function get_cart_total_price($member_id)
  {
    $query = Query::$query_get_cart_total_price;
    $arrays = [
      [':member_id', $member_id, PDO::PARAM_STR]
    ];
    return $result = $this->execute_quey($query, $arrays);
  }

  //****************************************
  //  member_login.php
  //****************************************
  /**
   * 会員ログインを行う
   *
   * @param string $input_mail
   * @param string $input_password
   * @param string $is_signup
   * @return string $message
   */
  function login_member($input_mail, $input_password, $is_signup = 'false')
  {
    // ログイン直前のURL
    $prev_url = $_SESSION['prev_url'];

    $query = Query::$query_login_member;
    $arrays = [
      [':mail', $input_mail, PDO::PARAM_STR]
    ];
    $result = $this->execute_quey($query, $arrays);

    // 新規登録と会員ログインを判断する
    if ($is_signup == 'true') {
      $_SESSION['member_info'] = $result; //新規登録の場合は登録された内容を格納
      header("Location: $prev_url"); //ログイン画面の直前のページに遷移
    } elseif ($is_signup == 'false' && password_verify($input_password, $result['password'])) { //会員ログインの場合は入力されたパスワードが一致するか判断する
      $_SESSION['member_info'] = $result; //ログイン成功
      header("Location: $prev_url");
    } else {
      return $message = 'ログインに失敗しました。'; //ログイン失敗
    }

    if (!empty($_SESSION['add_cart'])) {
      $_SESSION['add_cart']['member_id'] = $_SESSION['member_info']['member_id'];
      $message = $this->add_to_cart($_SESSION['add_cart']);
      $_SESSION['add_cart']['message'] = $message;
    }
  }

  //****************************************
  //  member_signup.php
  //****************************************
  /**
   * 会員登録を行う
   *
   * @param array $array
   * @return string $message
   */
  function signup_member($array)
  {
    $query = Query::$query_login_member;
    $arrays = [
      [':mail', $array['mail'], PDO::PARAM_STR]
    ];
    $result = $this->execute_quey($query, $arrays);

    //入力されたメールアドレスが未登録なら新規に登録する
    if (empty($result)) {
      $query = Query::$query_signup_member;
      $arrays = [
        [':name',         $array['name'],         PDO::PARAM_STR],
        [':sex',          $array['sex'],          PDO::PARAM_STR],
        [':birthday',     $array['birthday'],     PDO::PARAM_STR],
        [':postal_code',  $array['postal_code'],  PDO::PARAM_STR],
        [':address',      $array['address'],      PDO::PARAM_STR],
        [':mail',         $array['mail'],         PDO::PARAM_STR],
        [':password',     $array['password'],     PDO::PARAM_STR],
        [':phone_number', $array['phone_number'], PDO::PARAM_STR],
        [':payment',      $array['payment'],      PDO::PARAM_STR]
      ];
      $this->execute_quey($query, $arrays);
      $this->login_member($array['mail'], $input_password = '', $is_signup = 'true');
    } else {
      // 登録済みのメールアドレスならエラー
      return $message = 'メールアドレスが登録済みです。';
    }
  }

  //****************************************
  //  member_update.php
  //****************************************
  /**
   * 会員情報更新を行う
   *
   * @param array $array
   * @return string $message
   */
  function update_member($array)
  {
    $query = Query::$query_login_member;
    $arrays = [
      [':mail', $array['mail'], PDO::PARAM_STR]
    ];
    $result = $this->execute_quey($query, $arrays);

    if (empty($result) || $result['member_id'] == $array['member_id']) {
      $query = Query::$query_update_member;
      $arrays = [
        [':member_id',    $array['member_id'],    PDO::PARAM_STR],
        [':name',         $array['name'],         PDO::PARAM_STR],
        [':sex',          $array['sex'],          PDO::PARAM_STR],
        [':birthday',     $array['birthday'],     PDO::PARAM_STR],
        [':postal_code',  $array['postal_code'],  PDO::PARAM_STR],
        [':address',      $array['address'],      PDO::PARAM_STR],
        [':mail',         $array['mail'],         PDO::PARAM_STR],
        [':password',     $array['password'],     PDO::PARAM_STR],
        [':phone_number', $array['phone_number'], PDO::PARAM_STR],
        [':payment',      $array['payment'],      PDO::PARAM_STR]
      ];
      $this->execute_quey($query, $arrays);
      $this->login_member($array['mail'], $input_password = '', $is_signup = 'true');
    } else {
      return $message = 'メールアドレスが登録済みです。';
    }
  }

  //****************************************
  //  purchase_confrim.php
  //****************************************
  /**
   * 注文情報をDBに登録する
   *
   * @param array $array
   */
  function register_order($array)
  {
    $_SESSION['order_id'] = $array['order_id']; //受注IDをセッションに格納
    $this->register_order_header($array); //受注ヘッダの登録
    $this->register_order_detail($array); //受注明細の登録
    $this->delete_cart($array['member_id']); //カートの中身を削除する
  }

  /**
   * 受注ヘッダをDBに登録する
   *
   * @param array $array
   */
  function register_order_header($array)
  {
    $query = Query::$query_register_order_header;
    $arrays = [
      [':order_id',             $array['order_id'],             PDO::PARAM_STR],
      [':member_id',            $array['member_id'],            PDO::PARAM_STR],
      [':name',                 $array['name'],                 PDO::PARAM_STR],
      [':sex',                  $array['sex'],                  PDO::PARAM_STR],
      [':birthday',             $array['birthday'],             PDO::PARAM_STR],
      [':postal_code',          $array['postal_code'],          PDO::PARAM_STR],
      [':address',              $array['address'],              PDO::PARAM_STR],
      [':mail',                 $array['mail'],                 PDO::PARAM_STR],
      [':delivery_postal_code', $array['delivery_postal_code'], PDO::PARAM_STR],
      [':delivery_address',     $array['delivery_address'],     PDO::PARAM_STR],
      [':payment',              $array['payment'],              PDO::PARAM_STR],
      [':purchase_time',        $array['purchase_time'],        PDO::PARAM_STR],
      [':total_price',          $array['total_price'],          PDO::PARAM_STR]
    ];
    $this->execute_quey($query, $arrays);
  }

  /**
   * 受注明細をDBに登録する
   *
   * @param array $array
   */
  function register_order_detail($array)
  {
    $cart_list = $this->get_cart_list($array['member_id']);
    $query = Query::$query_register_order_detail;
    foreach ($cart_list as $row) {
      $product_detail = $this->get_product_detail($row['product_id']);
      $arrays = [
        [':order_id',   $array['order_id'],       PDO::PARAM_STR],
        [':product_id', $row['product_id'],       PDO::PARAM_STR],
        [':price',      $product_detail['price'], PDO::PARAM_STR],
        [':number',     $row['number'],           PDO::PARAM_STR]
      ];
      $this->execute_quey($query, $arrays);
    }
  }

  /**
   * カートの中身を削除する
   *
   * @param array $member_id
   */
  function delete_cart($member_id)
  {
    $query = Query::$query_delete_cart;
    $arrays = [
      [':member_id', $member_id, PDO::PARAM_STR]
    ];
    $this->execute_quey($query, $arrays);
  }

  /**
   * 指定した商品をカートから削除する
   *
   * @param array $member_id
   */
  function delete_cart_one($member_id, $product_id)
  {
    $query = Query::$query_delete_cart_one;
    $arrays = [
      [':member_id',  $member_id,  PDO::PARAM_STR],
      [':product_id', $product_id, PDO::PARAM_STR]
    ];
    $this->execute_quey($query, $arrays);
  }

  //****************************************
  //  purchase_history.php
  //****************************************
  /**
   *  [受注ヘッダテーブル]から[$param]をキーにして値を取得する
   *  キーとなる値は呼び出されるファイルによって変わる
   *
   * @param string $param 受注ID or 会員ID
   * @param string $is_history    呼び出し元が購入履歴かを判別する
   * @return array $result    SQL実行結果
   */
  function get_order_header($param, $is_history = 'true')
  {
    // 購入履歴から呼ばれる場合
    if ($is_history == 'true') {
      $query = Query::$query_get_order_header_history;
    } else {
      // 購入完了から呼ばれる場合
      $query = Query::$query_get_order_header_thanks;
    }
    $arrays = [
      [':param', $param, PDO::PARAM_STR]
    ];
    return $result = $this->execute_quey($query, $arrays, $is_single = 'false');
  }

  /**
   * [受注明細テーブル]から[受注ID]をキーにして[商品ID][単価][個数]を取得する
   *
   * @param string $order_id
   * @return array $result
   */
  function get_order_detail($order_id)
  {
    $query = Query::$query_get_order_detail;
    $arrays = [
      [':order_id', $order_id, PDO::PARAM_STR]
    ];
    return $result = $this->execute_quey($query, $arrays, $is_single = 'false');
  }
}
