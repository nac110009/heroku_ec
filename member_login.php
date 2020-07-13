<?php
//共通処理
require_once('include/inc_php_start.php');

if (!empty($_SESSION['member_info'])) {
  header("Location: product_list.php");
}

//ログインボタンが押されたらログイン判定関数を呼び出す
if (isset($_POST['login'])) {
  $input_mail     = filter_input(INPUT_POST, 'mail',     FILTER_SANITIZE_EMAIL);
  $input_password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
  $message  = $obj->login_member($input_mail, $input_password);
} elseif (!empty($_SERVER["HTTP_REFERER"])) {
  //直前のURLがある場合は保持する
  $_SESSION['prev_url'] = $_SERVER["HTTP_REFERER"];
} else {
  //直前のURLが無い場合は商品一覧画面のURLを入れる
  $_SESSION['prev_url'] = 'product_list.php';
}
?>

<!Doctype html>
<html lang="ja">

<head>
  <title>Heroku_ecサイト | 	会員ログイン</title>
  <?php include('include/inc_tag_head.php'); ?>
  <link href="css/member_common.css" rel="stylesheet">
</head>

<body>
  <header><?php include('include/inc_tag_header.php'); ?></header><!-- ヘッダーの読み込み -->
  <nav><?php include('include/inc_tag_nav.php'); ?></nav><!-- ナビゲーションの読み込み -->
  <main>
    <h1 class="headline"><span>LOGIN</span></h1>
    <div class="m-container">
      <h4>メールアドレスとパスワードを入力してください。</h4>
      <div class="err-msg"><?php if (!empty($message)) echo $message; ?></div>
      <div class="m-container-elem">
        <form action="" method="post">
          <dl>
            <dt>メールアドレス</dt>
            <dd><input type="email" name="mail" maxlength="255" value="<?php if (!empty($input_mail)) echo $obj->h($input_mail); ?>" required></dd>
            <dt>パスワード<span>≪半角英数≫<span></dt>
            <dd><input type="password" name="password" maxlength="20" pattern="^[0-9A-Za-z]+$" required></dd>
          </dl>
          <div class="container-btn">
            <input type="submit" name="login" value="ログイン" class="btn">
          </div>
        </form>
      </div>
    </div>
  </main>
  <footer><?php include('include/inc_tag_footer.php'); ?></footer><!-- フッターの読み込み -->
</body>

</html>
