<?php
session_start();
require('../dbconnect.php');

//personal_update.phpからきたかの確認
if(empty($_SESSION['re_join'])){
    //ほんとは個人画面へ飛びたい。とりあえずここに飛ぶ
    header('Location: ../index.php');
    exit();
}

if(!empty($_POST)){
    $statement = $db->prepare('UPDATE members SET picture=?, start_eat=?, current_eat=? WHERE id=?'); 
    $statement->execute(array(
        $_SESSION['re_join']['image'],
        $_SESSION['re_join']['start_eat'],
        $_SESSION['re_join']['overcome'],
        $_SESSION['id']
    ));
    //登録したら保管していた値を削除
    unset($_SESSION['re_join']);

    header('Location: personal_thanks.php');
    exit();
    }
?>

<?php
// if(!empty($_POST)){
//     $statement = $db->prepare('INSERT INTO members SET name=?, email=?, password=?, picture=?, start_eat=?, current_eat=?, created=NOW()');
//     $statement->execute(array(
//         $_SESSION['join']['name'],
//         $_SESSION['join']['email'],
//         sha1($_SESSION['join']['password']),
//         $_SESSION['join']['image'],
//         $_SESSION['join']['start_eat'],
//         $_SESSION['join']['overcome']
//     ));
//     //登録したら保管されていた情報を削除
//     unset($_SESSION['join']);

//     header('Location: thanks.php');
//     exit();
// }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>WOEO|Edit</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/personal.css">
</head>
<body>
    <!-- <div id="wrap"> -->
        <header class="header">
            <div class="profile2"><h1>Edit</h1></div>
        </header>
        
    <div class="content">
    <p class="attention2">記入した内容を確認して、「登録する」ボタンをクリックしてください</p>
    <form action="" method="post">
        <input type="hidden" name="action" value="submit" />
        <dl>
            <!-- <dt>アイコン</dt> -->
            <dd>
                <?php if($_SESSION['re_join']['image'] !== ''): ?>
                <img src="../member_picture/<?php print(htmlspecialchars($_SESSION['re_join']['image'], ENT_QUOTES));?>">
                <?php endif;?>
            </dd>
            <dt>なった原因</dt>
            <dd>
                <pre><?php print(htmlspecialchars($_SESSION['re_join']['start_eat'], ENT_QUOTES)); ?></pre>
            </dd>
            <dt>現在の状況</dt>
            <dd>
                <?php print(htmlspecialchars($_SESSION['re_join']['overcome'], ENT_QUOTES)); ?>
            </dd>
        </dl>
        <div class="confirm"><a href="personal_update.php?id=<?php print($_SESSION['id']); ?>&action=rewrite">&laquo;&nbsp;書き直す</a> | <input type="submit" value="登録する" class="submit2"></div>
    </form>
    </div>
    <!-- </div> -->
</body>
</html>