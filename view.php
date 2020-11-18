<?php 
session_start();
require('dbconnect.php');


if(empty($_REQUEST['id'])) {
  header('Location: index.php');
  exit();
}

$posts = $db->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id AND p.id=?');
$posts->execute(array($_REQUEST['id']));


?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Post Details</title>
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
    <?php 
    //ここから始めれば38文目も$postが使えるはず
    if ($post = $posts->fetch()): 
    ?>
    <div class="picname">
      <ul class="link2">
        <li>
          <!-- ここからその人の個人ユーザに飛べるようにする -->
          <a href="personal/personal.php?id=<?php print(htmlspecialchars($post['member_id']));?>"><img src="member_picture/<?php print(htmlspecialchars($post['picture'], ENT_QUOTES)); ?>">
        </li>
      </a>
        <li class="name">
          <div class="name2"><?php print(htmlspecialchars($post['name'], ENT_QUOTES)); ?></div>
        </li>
      </ul>
    </div>

    <div class="msg">
      <p class="message"><?php print(htmlspecialchars($post['message'], ENT_QUOTES)); ?></p>
      <p class="day"><?php print(htmlspecialchars($post['created'], ENT_QUOTES)); ?></p>
    </div>
    <?php else :?>
    <p class="delete">その投稿は削除されたか、URLが間違えています</p>
    <?php endif; ?>
  </div>

</body>
</html>
