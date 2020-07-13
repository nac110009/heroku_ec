<?php
$member_name = "ゲスト";
$member_id   = 0;

//会員名の取得
if (isset($_SESSION['member_info'])) {
  //ログイン済みの場合は会員名を表示する
  $member_name = $_SESSION['member_info']['name'];
  $member_id   = $_SESSION['member_info']['member_id'];
}
?>

<div id="luxbar" class="luxbar-default">
  <input type="checkbox" class="luxbar-checkbox" id="luxbar-checkbox" />
  <div class="luxbar-menu luxbar-menu-right luxbar-menu-material-red">
    <ul class="luxbar-navigation">
      <li class="luxbar-header">
        <!-- 検索ボックス -->
        <form action="product_list.php" method="get" class="search_container">
          <input type="text" name="search_char" placeholder="キーワード検索">
          <input type="submit" name="search" value="→" class="fas">
        </form>
        <label class="luxbar-hamburger luxbar-hamburger-doublespin" id="luxbar-hamburger" for="luxbar-checkbox"> <span></span> </label>
      </li>
      <li class="luxbar-item">
        <a href="<?php if ($member_id != 0) echo 'member_update.php'; ?>"><?= 'ようこそ 【' . $member_name . '】 さん'; ?></a>
      </li>
      <li class="luxbar-item"><a href="product_list.php"><i class="fas fa-shopping-bag"></i> 商品一覧</a></li>
      <?php if ($member_id == 0) : ?>
        <li class="luxbar-item"><a href="member_login.php"><i class="fas fa-sign-in-alt"></i> ログイン</a></li>
        <li class="luxbar-item"><a href="member_signup.php"><i class="fas fa-user-plus"></i> 会員登録</a></li>
      <?php elseif ($member_id != 0) : ?>
        <li class="luxbar-item"><a href="member_logout.php"><i class="fas fa-sign-out-alt"></i> ログアウト</a></li>
        <li class="luxbar-item"><a href="purchase_history.php"><i class="fas fa-history"></i> 購入履歴</a></li>
      <?php endif; ?>
      <li class="luxbar-item"><a href="purchase_cart.php"><i class="fas fa-shopping-cart"></i> カート</a></li>
    </ul>
  </div>
</div>
