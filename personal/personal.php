<?php
session_start();
require('../dbconnect.php');

//ログインの確認
if(isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
	if($_REQUEST['id']){
		$personals = $db->prepare('SELECT name, picture, start_eat, current_eat FROM members WHERE id=?');
		$personals->execute(array($_REQUEST['id']));
		$personal = $personals->fetch();
	}

	$id = $_SESSION['id'];
	// $_SESSION['time'] = time();
	// $personal['name'] = $_SESSION['name'];
	// $personal['picture'] = $_SESSION['picture'];
	// $personal['start_eat'] = $_SESSION['start_eat'];
	// $personal['current_eat'] = $_SESSION['current_eat'];
}else{
	header('Location: ../log/login.php');
	exit();
}


//新しい値が格納される
// $nana = "友達";
// $nana = "知り合い";
// print($nana);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>WOEO|Profile</title>
	<link rel="stylesheet" href="../css/reset.css">
	<link rel="stylesheet" href="../css/personal.css">
</head>

<body>
	<div class="wrap">
	<header>
    	<div class="profile"><h1>Profile</h1></div>
        <ul class="link">
        	<li><a href="../index.php">Post Page</a></li>
			<?php if($_SESSION['id'] == $_REQUEST['id']):?>
			<li><a href="personal_update.php?id=<?php print(htmlspecialchars($id, ENT_QUOTES));?>">Edit</a></li>
			<?php endif; ?>
			<?php if($_SESSION['id'] == $_REQUEST['id']):?>
			<li><a href="../log/logout.php">Logout</a></li>
			<?php endif; ?>
        </ul>
	</header>
	
	<div class="content">
		<dl>
			<!-- <dt>アイコン</dt> -->
			<dd class="pic">
				<img src="../member_picture/<?php print(htmlspecialchars($personal['picture'], ENT_QUOTES)); ?>" width="" height="" alt="<?php print(htmlspecialchars($personal['name'], ENT_QUOTES)); ?>">
			</dd>
			<!-- <dt>ニックネーム</dt> -->
			<dd class="name">
				<?php print(htmlspecialchars($personal['name'], ENT_QUOTES)); ?>
			</dd>
			<dt>なった理由</dt>
			<dd class="start">
				<?php print(htmlspecialchars($personal['start_eat'], ENT_QUOTES)); ?>
			</dd>
			<dt>現在の状況</dt>
			<dd>
				<?php print(htmlspecialchars($personal['current_eat'], ENT_QUOTES)); ?>
			</dd>
		</dl>
	</div>
	</div>
</body>
</html>