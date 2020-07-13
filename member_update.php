<?php
//共通処理
require_once('include/inc_php_start.php');

if (empty($_SESSION['member_info'])) {
  header("Location: member_login.php");
}

//更新ボタンが押された場合
if (isset($_POST['update'])) {
  $array = [
    "member_id"    => $_SESSION['member_info']['member_id'],
    "name"         => filter_input(INPUT_POST, 'name',         FILTER_SANITIZE_STRING),
    "sex"          => filter_input(INPUT_POST, 'sex',          FILTER_SANITIZE_STRING),
    "birthday"     => filter_input(INPUT_POST, 'birthday',     FILTER_SANITIZE_NUMBER_INT),
    "postal_code"  => filter_input(INPUT_POST, 'postal_code',  FILTER_SANITIZE_NUMBER_INT),
    "address"      => filter_input(INPUT_POST, 'address',      FILTER_SANITIZE_STRING),
    "mail"         => filter_input(INPUT_POST, 'mail',         FILTER_SANITIZE_EMAIL),
    "password"     => filter_input(INPUT_POST, 'password',     FILTER_SANITIZE_STRING),
    "phone_number" => filter_input(INPUT_POST, 'phone_number', FILTER_SANITIZE_NUMBER_INT),
    "payment"      => filter_input(INPUT_POST, 'payment',      FILTER_SANITIZE_STRING)
  ];

  $errors = $vld->valid_check($array);
  //エラー配列が空だったらパスワードをハッシュ化して入れ直し、会員登録メソッドを呼ぶ
  if (empty($errors)) {
    $array['password'] = password_hash($array['password'], PASSWORD_BCRYPT);
    $message = $obj->update_member($array);
  }
} elseif (isset($_SESSION['member_info'])) {
  //テキストボックスの初期値として登録済みの会員情報を表示
  $day = new DateTime($_SESSION['member_info']['birthday']);
  $array = [
    "member_id"    => $_SESSION['member_info']['member_id'],
    "name"         => $_SESSION['member_info']['name'],
    "sex"          => $_SESSION['member_info']['sex'],
    "birthday"     => $day->format('Ymd'),
    "postal_code"  => $_SESSION['member_info']['postal_code'],
    "address"      => $_SESSION['member_info']['address'],
    "mail"         => $_SESSION['member_info']['mail'],
    "password"     => $_SESSION['member_info']['password'],
    "phone_number" => $_SESSION['member_info']['phone_number'],
    "payment"      => $_SESSION['member_info']['payment']
  ];
} else {
  //直前のURLを保持する
  $_SESSION['prev_url'] = $_SERVER["HTTP_REFERER"];
}
?>

<!Doctype html>
<html lang="ja">

<head>
  <title>Heroku_ecサイト | 会員情報変更</title>
  <?php include('include/inc_tag_head.php'); ?>
  <link href="css/member_common.css" rel="stylesheet"><!-- 会員関連画面のCSS -->
</head>

<body>
  <header><?php include('include/inc_tag_header.php'); ?></header><!-- ヘッダーの読み込み -->
  <nav><?php include('include/inc_tag_nav.php'); ?></nav><!-- ナビゲーションの読み込み -->
  <main>
    <h1 class="headline"><span>UPDATE</span></h1>
    <div class="m-container">
      <h4>以下の必須項目を入力してください。</h4>
      <?php if (!empty($errors)) : ?>
        <!-- 入力内容に不備があればエラー内容を表示する -->
        <?php foreach ($errors as $err) : ?>
          <div class="err-msg"><?= $err; ?></div>
        <?php endforeach; ?>
      <?php elseif (!empty($err_msg)) : ?>
        <!-- 登録済みのメールアドレスだった場合のエラーを表示する -->
        <div class="err-msg"><?= $err_msg; ?></div>
      <?php endif; ?>
      <div class="m-container-elem">
        <form action="" method="post">
          <dl>
            <dt>名前<span>≪必須≫</span></dt>
            <dd><input type="text" name="name" maxlength="50" value="<?php if (!empty($array['name'])) echo $obj->h($array['name']); ?>" required></dd>
            <dt>性別<span>≪必須≫</span></dt>
            <dd>
              <input type="radio" name="sex" value="男性" <?php if (empty($array['sex']) || $array['sex'] == '男性') echo 'checked'; ?>> 男性
              <input type="radio" name="sex" value="女性" <?php if (!empty($array['sex']) && $array['sex'] == '女性') echo 'checked'; ?>> 女性
            </dd>
            <dt>生年月日<span>≪必須：半角数字のみ≫</span></dt>
            <dd><input type="text" name="birthday" maxlength="8" pattern="^[0-9]+$" value="<?php if (!empty($array['birthday'])) echo $obj->h($array['birthday']); ?>" required></dd>
            <dt>郵便番号<span>≪必須：半角数字のみ≫</span></dt>
            <dd><input type="text" name="postal_code" maxlength="7" pattern="^[0-9]+$" value="<?php if (!empty($array['postal_code'])) echo $obj->h($array['postal_code']); ?>" required></dd>
            <dt>住所<span>≪必須≫</span></dt>
            <dd><input type="text" name="address" maxlength="255" value="<?php if (!empty($array['address'])) echo $obj->h($array['address']); ?>" required></dd>
            <dt>メールアドレス<span>≪必須≫</span></dt>
            <dd><input type="email" name="mail" maxlength="255" value="<?php if (!empty($array['mail'])) echo $obj->h($array['mail']); ?>" required></dd>
            <dt>パスワード<span>≪必須：半角英数≫</span></dt>
            <dd><input type="password" name="password" maxlength="50" pattern="^[0-9A-Za-z]+$" required></dd>
            <dt>電話番号<span>≪半角数字のみ≫</span></dt>
            <dd><input type="text" name="phone_number" maxlength="11" pattern="^[0-9]+$" value="<?php if (!empty($array['phone_number'])) echo $obj->h($array['phone_number']); ?>"></dd>
            <dt>支払方法</dt>
            <dd>
              <select name="payment">
                <option value="選択なし" <?php if (empty($array['payment'])) echo 'selected'; ?>></option>
                <option value="代金引換" <?php if (!empty($array['payment']) && $array['payment'] == '代金引換') echo 'selected'; ?>>代金引換</option>
                <option value="クレジットカード" <?php if (!empty($array['payment']) && $array['payment'] == 'クレジットカード') echo 'selected'; ?>>クレジットカード</option>
                <option value="電子マネー" <?php if (!empty($array['payment']) && $array['payment'] == '電子マネー') echo 'selected'; ?>>電子マネー</option>
                <option value="コンビニ払い" <?php if (!empty($array['payment']) && $array['payment'] == 'コンビニ払い') echo 'selected'; ?>>コンビニ払い</option>
              </select>
            </dd>
          </dl>
          <div class="container-btn">
            <input type="submit" name="update" value="更新する" class="btn">
          </div>
        </form>

      </div>
    </div>
  </main>
  <footer><?php include('include/inc_tag_footer.php'); ?></footer><!-- フッターの読み込み -->
</body>

</html>
