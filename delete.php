<?php
session_start();
require('dbconnect.php');

if(isset($_SESSION['id'])){
    $id = $_REQUEST['id'];

    $messages = $db->prepare('SELECT * FROM posts WHERE id=?');
    $messages->execute(array($id));
    $message = $messages->fetch();

    if ($message['member_id'] == $_SESSION['id']) {
        $del = $db->prepare('DELETE FROM posts WHERE id=?');
        $del->execute(array($id));
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WOEO|Delete</title>
    <link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="css/view.css"> 

</head>
<body>
    <header>
        <div class="details"><h1>Post Details</h1></div>
        <ul class="link">
        <!--&laquo;<-戻るのかなを表現するのにいいかも記号のスタイル指定ができない-->
            <!-- <li><span class="mark">&laquo;</span><a href="index.php">Post page</a></li> -->
            <li><a href="index.php">Post page</a></li>
        </ul>
    </header>

    <div class="content">
        <p class="delete">投稿を削除しました</p>
    </div>
</body>
</html>
