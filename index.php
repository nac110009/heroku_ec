<?php
//共通処理
require_once('include/inc_php_start.php');
?>

<!Doctype html>
<html lang="ja">

<head>
  <title>Heroku_ecサイト | トップページ</title>
  <?php include('include/inc_tag_head.php'); ?>
</head><!-- メタ情報やCSSのリンク先を読み込み -->

<body>
<script language="javascript">
//Set the number of days before your cookie should expire
var ExpireDays = 90;
//Do not change anything below this line
qstr = document.location.search;
qstr = qstr.substring(1,qstr.length)
function SetCookie(cookieName,cookieValue,nDays)
{
	var today = new Date();
	var expire = new Date();
	if (nDays==null || nDays==0) nDays=1;
	expire.setTime(today.getTime() + 3600000*24*nDays);
	document.cookie = cookieName+"="+escape(cookieValue)+ ";domain=damp-ocean-97207.herokuapp.com;expires="+expire.toGMTString();
	document.cookie = cookieName+"="+escape(cookieValue)+ ";domain=damp-97207.herokuapp.com;expires="+expire.toGMTString();
}

thevars = qstr.split("&");
for(i=0;i<thevars.length;i++) {
	var element = thevars[i].split('=');
	switch(element[0]) {
		case "sfmc_sub":
			SetCookie("SubscriberID",element[1],ExpireDays);
			break;
		case "j":
			SetCookie("JobID",element[1],ExpireDays);
			break;
		case "l":
			SetCookie("ListID",element[1],ExpireDays);
			break;
		case "u":
			SetCookie("UrlID",element[1],ExpireDays);
			break;
		case "jb":
			SetCookie("BatchID",element[1],ExpireDays);
			break;
		case "mid":
			SetCookie("MemberID",element[1],ExpireDays);
			break;
		default:
			break;
	}
}
</script>
  <header><?php include('include/inc_tag_header.php'); ?></header><!-- ヘッダーの読み込み -->
  <nav><?php include('include/inc_tag_nav.php'); ?></nav><!-- ナビゲーションの読み込み -->

  <main>
    <div class="top-img">
      <img src="image/e_sale_300_1.png" alt="商品画像">
    </div>
    <div id="igdrec_1"></div>
  </main>

  <footer><?php include('include/inc_tag_footer.php'); ?></footer><!-- フッターの読み込み -->
</body>

</html>
