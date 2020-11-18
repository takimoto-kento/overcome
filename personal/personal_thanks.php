<?php
session_start();
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
	<header>
            <div class="profile"><h1>Edit</h1></div>
    </header>

	<div class="content">
	<p class="complete">プロフィールの変更が完了しました</p>
	<p class="back"><a href="personal.php?id=<?php print(htmlspecialchars($_SESSION['id'], ENT_QUOTES));?>">Profile</a></p>
	</div>

</body>
</html>