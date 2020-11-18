<?php
require('dbconnect.php');
session_start();

//ログイン確認
if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
  $_SESSION['time'] = time();

  $members = $db->prepare('SELECT * FROM members WHERE id=?');
  $members->execute(array($_SESSION['id']));
  $member = $members->fetch();
} else {
  header('Location: log/login.php');
  exit();
}

//投稿の登録
if (!empty($_POST)) {
  if($_POST['message'] !== '') {
    $message = $db->prepare('INSERT INTO posts SET message=?, member_id=?, reply_message_id=?, created=NOW()');
    $message->execute(array(
      $_POST['message'],
      $member['id'],
      $_POST['reply_post_id']
    ));
    //var_dump($message->errorInfo()); 
    //exit(); 

    header('Location: index.php');
    exit();
  }
}

$page = $_REQUEST['page'];
if ($page == '') {
  $page = 1;
}
$page = max($page, 1);

$counts = $db->query('SELECT COUNT(*) AS cnt FROM posts');
$cnt = $counts->fetch();
$maxPage = ceil($cnt['cnt'] / 5);
$page = min($page, $maxPage);

$start = ($page - 1) * 5;

$posts = $db->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id ORDER BY p.created DESC');
// $posts = $db->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id ORDER BY p.created DESC LIMIT ?, 5');

$posts->bindParam(1, $start, PDO::PARAM_INT);
$posts->execute();

if (isset($_REQUEST['res'])) {
  //返信の処理
  $response = $db->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id AND p.id=?');
  $response->execute(array($_REQUEST['res']));

  $table = $response->fetch();
  $message = '＠' . $table['name'] . ' ' . $table['message'];
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>WOEO|Post page</title>
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/index.css">
</head>

<body>
  <div class="wrap">
    <header class="post-page">
      <h1>Post Page</h1>
    </header>

    <div class="na">
    <div class="content">
      <!-- MENU -->
      <aside>
        <ul class="link">
          <li><a href="index.php">Post Page</a></li>
          <li><a href="personal/personal.php?id=<?php print(htmlspecialchars($_SESSION['id'], ENT_QUOTES));?>">Profile</a></li>
        </ul>
      </aside>

      <!-- 投稿画面 -->
      <article>
        <!-- 投稿場所 -->
        <div class="post">
          <form action="" method="post">
          <dl>
            <!-- <dt><?php //print(htmlspecialchars($member['name'], ENT_QUOTES)); ?>さん、メッセージをどうぞ</dt> -->
            <dd>
              <textarea name="message" cols="30" rows="3" placeholder="何ができましたか？"><?php print(htmlspecialchars($message, ENT_QUOTES)); ?></textarea>
              <input type="hidden" name="reply_post_id" value="<?php print(htmlspecialchars($_REQUEST['res'], ENT_QUOTES));?>" />
            </dd>
          </dl>
            <p class="submit">
              <input type="submit" class="submit2" value="POST">
            </p>
          </form>
        </div>

        <?php foreach ($posts as $post): ?>
        <!-- 投稿内容 -->
        <div class="message">
          <div class="mes-pic">
            <a href="personal/personal.php?id=<?php print(htmlspecialchars($post['member_id']));?>"><img src="member_picture/<?php print(htmlspecialchars($post['picture'], ENT_QUOTES)); ?>" width="48" height="48" alt="<?php print(htmlspecialchars($post['name'], ENT_QUOTES)); ?>" /></a>
          </div>

          <div class="mes-con">
            <p class="name"><?php print(htmlspecialchars($post['name'], ENT_QUOTES)); ?></p>
            <p class="mes"><pre><?php print(htmlspecialchars($post['message'], ENT_QUOTES)); ?></pre></p>
            <span class="re">[<a href="index.php?res=<?php print(htmlspecialchars($post['id'], ENT_QUOTES));?>">Re</a>]</span>
            <p class="day"><a href="view.php?id=<?php print(htmlspecialchars($post['id'], ENT_QUOTES));?>"><?php print(htmlspecialchars($post['created'], ENT_QUOTES)); ?></a>
            </p>

            <?php if ($post['reply_message_id'] > 0): ?>
            <a href="view.php?id=<?php print(htmlspecialchars($post['reply_message_id'])); ?>">
            返信元のメッセージ</a>
            <?php endif; ?>

            <!-- 投稿した本人かどうかの確認 -->
            <?php if ($_SESSION['id'] == $post['member_id']): ?>
            <p class="del">
            [<a href="delete.php?id=<?php print(htmlspecialchars($post['id'], ENT_QUOTES)); ?>"
            style="color: #F33;">削除</a>]
            </p>
            <?php endif; ?>
            </p>
          </div>
        </div> <!--class="message"-->
        <?php endforeach ;?>
      </article>
    </div>
    </div>
    <footer class="footer">
        <span class="last">&copy; WOEO All rights reserved</span>
    </footer>
  </div>
</body>
</html>