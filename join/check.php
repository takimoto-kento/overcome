<?php
session_start();
require('../dbconnect.php');

if(!isset($_SESSION['join'])) {
    header('Locaion: index.php');
}

if(!empty($_POST)){
    $statement = $db->prepare('INSERT INTO members SET name=?, email=?, password=?, picture=?, start_eat=?, current_eat=?, created=NOW()');
    $statement->execute(array(
        $_SESSION['join']['name'],
        $_SESSION['join']['email'],
        sha1($_SESSION['join']['password']),
        $_SESSION['join']['image'],
        $_SESSION['join']['start_eat'],
        $_SESSION['join']['overcome']
    ));
    //登録したら保管されていた情報を削除
    unset($_SESSION['join']);

    header('Location: thanks.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>WOEO|Sign Up</title>
    <link rel="stylesheet" href="../css/reset.css" />
    <link rel="stylesheet" href="../css/signup.css">
</head>
<body>
<div class="wrap">
    <header>
        <div class="sign-up"><h1>Sign Up</h1></div>
        <!-- <ul class="link">
            <li><a href="index.html">Top</a></li>
        </ul> -->
    </header>

    <div class="content">
        <p class="attention">記入した内容を確認して、「登録する」ボタンをクリックしてください</p>
        <form action="" method="post">
            <input type="hidden" name="action" value="submit" />
            <dl class="frame">
                <dt>ニックネーム</dt>
                <dd class="fill-out">
                    <?php print(htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES)); ?>
                </dd>
                <dt>メールアドレス</dt>
                <dd class="fill-out">
                <?php print(htmlspecialchars($_SESSION['join']['email'], ENT_QUOTES)); ?>
                </dd>
                <dt>パスワード</dt>
                <dd class="fill-out">
                【表示されません】
                </dd>
                <dt>アイコン</dt>
                <dd class="fill-out">
                    <?php if($_SESSION['join']['image'] !== ''): ?>
                    <img src="../member_picture/<?php print(htmlspecialchars($_SESSION['join']['image'], ENT_QUOTES));?>">
                    <?php endif;?>
                </dd>
                <dt>なった原因</dt>
                <dd class="fill-out">
                <?php print(htmlspecialchars($_SESSION['join']['start_eat'], ENT_QUOTES)); ?>
                </dd>
                <dt>現在の状況</dt>
                <dd class="fill-out">
                <?php print(htmlspecialchars($_SESSION['join']['overcome'], ENT_QUOTES)); ?>
                </dd>
            </dl>
            <div class=conform><a href="index.php?action=rewrite">&laquo;&nbsp;書き直す</a> | <input type="submit" value="登録する" /></div>
        </form>
    </div>

    <footer class="footer">
            <p class="copy"><small>&copy; WOEO All rights reserved</small></p>
    </footer>

</div>
</body>
</html>