<?php
/**
   * db_operation.phpで使用されるSQLクエリの定義
   * 使用されるファイルごとに記載
   */
class Query
{
  //****************************************
  //  product_list.php
  //****************************************
  // 商品一覧を取得する
  static $query_get_product_list = <<<EOL
    SELECT
      *
    FROM
      "public".product
    ORDER BY
      product_id
  EOL;

  // 入力された文字列で商品を検索する
  static $query_search_product = <<<EOL
    SELECT
      *
    FROM
      "public".product
    WHERE
      product_name Like :search_character
    ORDER BY
      product_id
  EOL;

  //****************************************
  //  product_group_list.php
  //****************************************
  // 商品グループ一覧を取得する
  static $query_group_product = <<<EOL
    SELECT
      *
    FROM
      "public".product
    WHERE
      product_type = :product_type
    ORDER BY
      product_id
  EOL;

  //****************************************
  //  product_detail.php
  //****************************************
  // 商品詳細を取得する
  static $query_get_product_detail = <<<EOL
    SELECT
      *
    FROM
      "public".product
    WHERE
      product_id = :product_id
  EOL;

  // カートに追加しようとしている商品が既に追加されているか調べる
  static $query_add_to_cart_select = <<<EOL
    SELECT
      *
    FROM
      "public".cart
    WHERE
      member_id = :member_id
      AND product_id = :product_id
  EOL;

  // カートに追加済みだった場合、個数を合計して更新する
  static $query_add_to_cart_update = <<<EOL
    UPDATE
      "public".cart
    SET
      number    = :number,
      add_time  = :add_time,
      subtotal  = :subtotal
    WHERE
      member_id       = :member_id
      AND product_id  = :product_id
  EOL;

  // カートに追加済みだった場合、個数を合計して更新する
  static $query_add_to_cart_insert = <<<EOL
    INSERT INTO "public".cart(
      session_id,
      product_id,
      number,
      member_id,
      add_time,
      price,
      subtotal
    )
    VALUES(
      :session_id,
      :product_id,
      :number,
      :member_id,
      :add_time,
      :price,
      :subtotal
    )
  EOL;

  //****************************************
  //  purchase_cart.php
  //****************************************
  //カートに追加した商品の一覧を表示する
  static $query_get_cart_list = <<<EOL
    SELECT
      *
    FROM
      "public".cart
    WHERE
      member_id = :member_id
    ORDER BY
      add_time DESC
  EOL;

  // カートに追加した商品の合計金額を取得する
  static $query_get_cart_total_price = <<<EOL
    SELECT
      SUM(subtotal)
    FROM
      "public".cart
    WHERE
      member_id = :member_id
  EOL;

  //****************************************
  //  member_login.php
  //  member_signup.php
  //  member_update.php
  //****************************************
  // 入力されたメールアドレスが登録済みのものか調べる
  static $query_login_member = <<<EOL
    SELECT
      *
    FROM
      "public".member
    WHERE
      mail = :mail
  EOL;

  //****************************************
  //  member_signup.php
  //****************************************
  // 新規会員情報を追加する
  static $query_signup_member = <<<EOL
    INSERT INTO "public".member(
      name,
      sex,
      birthday,
      postal_code,
      address,
      mail,
      password,
      phone_number,
      payment
    )
    VALUES(
      :name,
      :sex,
      :birthday,
      :postal_code,
      :address,
      :mail,
      :password,
      :phone_number,
      :payment
    )
  EOL;

  //****************************************
  //  member_update.php
  //****************************************
  // 会員情報を更新する
  static $query_update_member = <<<EOL
    UPDATE
      "public".member
    SET
      name         = :name,
      sex          = :sex,
      birthday     = :birthday,
      postal_code  = :postal_code,
      address      = :address,
      mail         = :mail,
      password     = :password,
      phone_number = :phone_number,
      payment      = :payment
    WHERE
      member_id = :member_id
  EOL;

  //****************************************
  //  purchase_confrim.php
  //****************************************
  // 受注ヘッダを登録する
  static $query_register_order_header = <<<EOL
    INSERT INTO "public".order_header(
      order_id,
      member_id,
      name,
      sex,
      birthday,
      postal_code,
      address,
      mail,
      delivery_postal_code,
      delivery_address,
      payment,
      purchase_time,
      total_price
    )
    VALUES(
      :order_id,
      :member_id,
      :name,
      :sex,
      :birthday,
      :postal_code,
      :address,
      :mail,
      :delivery_postal_code,
      :delivery_address,
      :payment,
      :purchase_time,
      :total_price
    )
  EOL;

  // 受注明細を登録する
  static $query_register_order_detail = <<<EOL
    INSERT INTO "public".order_detail(
      order_id,
      product_id,
      price,
      number
    )
    VALUES(
      :order_id,
      :product_id,
      :price,
      :number
    )
  EOL;

  //****************************************
  //  purchase_confrim.php
  //  purchase_cart_delete.php
  //****************************************
  // カートの中身を全て削除する
  static $query_delete_cart = <<<EOL
    DELETE
    FROM
      "public".cart
    WHERE
      member_id = :member_id
  EOL;

  // 指定した商品をカートから削除する
  static $query_delete_cart_one = <<<EOL
    DELETE
    FROM
      "public".cart
    WHERE
      member_id = :member_id
      AND product_id = :product_id
  EOL;

  //****************************************
  //  purchase_history.php
  //****************************************
  // 【購入履歴】画面から受注ヘッダ情報を取得する
  static $query_get_order_header_history = <<<EOL
    SELECT
      order_id,
      purchase_time,
      total_price
    FROM
      "public".order_header
    WHERE
      member_id = :param
    ORDER BY
      purchase_time DESC
  EOL;

  // 【購入完了】画面から受注ヘッダ情報を取得する
  static $query_get_order_header_thanks = <<<EOL
    SELECT
      order_id,
      purchase_time,
      total_price
    FROM
      "public".order_header
    WHERE
      order_id = :param
    ORDER BY
      purchase_time DESC
  EOL;

  // 受注明細情報を取得する
  static $query_get_order_detail = <<<EOL
    SELECT
      product_id,
      price,
      number
    FROM
      "public".order_detail
    WHERE
      order_id = :order_id
    ORDER BY
      product_id ASC
  EOL;
}
