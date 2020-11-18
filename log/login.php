<?php
require('../dbconnect.php');
session_start();

if($_COOKIE['email'] !== ''){
  $email = $_COOKIE['email'];
}

if(!empty($_POST)){
  //メールアドレス欄を改めて記入した場合は下記のように新たに値が保管される。
  $email = $_POST['email'];

  if($_POST['email'] !== '' && $_POST['password'] !== ''){
    $login = $db->prepare('SELECT * FROM members WHERE email=? AND password=?');
    $login->execute(array(
      //$emailではダメなの？？<-おそらく$emailこれでもいけるはず
      $_POST['email'],
      sha1($_POST['password'])
    ));
    $member = $login->fetch();

    if($member){
      $_SESSION['id'] = $member['id'];
      $id = $_SESSION['id'];
      $_SESSION['time'] = time();
      //ここで個人画面の内容を抜き出した。
      $_SESSION['name'] = $member['name'];
      $_SESSION['picture'] = $member['picture'];
      $_SESSION['start_eat'] = $member['start_eat'];
      $_SESSION['current_eat'] = $member['current_eat'];

      //次回からは自動でログインするにチェックが押されていた場合
      if($_POST['save'] === 'on'){
        setcookie('email', $_POST['email'], time()+60*60*24*14);
      }

      //実際には個人画面に飛べるようにする
      // header('Location: index.php');
      //個人画面に飛ぶようにしたい
      header('Location: ../personal/personal.php?id=' . $id);
      exit();
    } else {
      $error['login'] = 'failed';
    }
  } else {
    $error['login'] = 'blank';
  }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>WOEO|Login</title>
  <link rel="stylesheet" href="../css/reset.css">
  <!-- <link rel="stylesheet" href="css/common.css"> -->
  <link rel="stylesheet" href="../css/signup1.css">
</head>

<body>
  <div id="wrap">
      <header class="header">
          <div class="top"><h1>Login</h1></div>
          <ul class="link">
              <li><a href="../index.html">Top</a></li>
          </ul>
      </header>

    <div class="content">
      <div class="lead">
        <p class="attention2">メールアドレスとパスワードを記入してログインしてください。</p>
        <p class="attention2">入会手続きがまだの方はこちらからどうぞ。</p>
        <p class="attention2">&raquo;<a href="../join/index.php">Sign up</a></p>
      </div>
      <form action="" method="post">
        <ul class="form-style-1">
          <li>
            <label>メールアドレス<span class="required">必須</span></label><input type="text" name="email" class="field-long" value="<?php echo htmlspecialchars($email, ENT_QUOTES); ?>">
            <?php if ($error['login'] === 'blank' ): ?>
            <p class = "error">* メールアドレスとパスワードをご記入ください</p>
            <?php endif; ?>
            <?php if ($error['login'] === 'failed' ): ?>
            <p class = "error">* ログインに失敗しました。正しくご記入ください</p>
            <?php endif; ?>
          </li>
          <li>
            <label>パスワード<span class="required">必須</span></label><input type="password" name="password" class="field-long" value="<?php echo htmlspecialchars($_POST['password'], ENT_QUOTES); ?>">
          </li>
          <li>
            <label>ログイン情報の記録</label>
            <input id="save" type="checkbox" name="save" value="on"><label for="save">次回からは自動的にログインする</label>
          </li>
          <li>
          <input type="submit" value="ログインする">
          </li>
        </ul>
      </form>
    </div>
    <footer class="footer">
        <p class="copy"><small>&copy; WOEO All rights reserved</small></p>
    </footer>

</div>
</body>
</html>
