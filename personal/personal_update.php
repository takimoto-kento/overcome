<?php
session_start();
require('../dbconnect.php');

//personal_update.phpからきたかの確認
// if(empty($_REQUEST['id'])){
    //ほんとは個人画面へ飛びたい。とりあえずここに飛ぶ
//     header('Location: personal.php');
//     exit();
// }

//$_SESSIONで値を$start_eat格納
$start_eat = $_SESSION['start_eat'];
// if(isset($_REQUEST['id'])){
// 	$remembers = $db->prepare('SELECT * FROM members WHERE id=?');
// 	$remembers->execute(array($_REQUEST['id']));	

// 	$remember = $remembers->fetch();
	//写真は取り出さなくていい？？
	// $_POST['picture'] = $remembers['picture'];
	// $_POST['start_eat'] = $remember['start_eat'];
	//$remembers['current_eat']も必要ない？？
	// $_POST['current_eat'] = $remember['current_eat'];
	//記入がしてあるか確認。$_POSTの存在があるかどうか。

// }


//actionがあればpersonal_check.phpへ飛ぶようにした
if(!empty($_POST)){
	//会食恐怖症
	$start_eat = $_POST['start_eat'];
	//会食恐怖症になった原因が空の場合
	if($_POST['start_eat'] === ''){
		$error['start_eat'] = 'blank';
	}
	if($_FILES['image']['name'] === ''){
		$error['image'] = 'blank';
	}

	$fileName = $_FILES['image']['name'];
	if(!empty($fileName)){
		//文字検索
		$ext = substr($fileName, -3);
		if($ext !='jpg' && $ext != 'gif' && $ext != 'png'){
			$error['image'] = 'type';
		}
	}
	if(empty($error)){
		$image = date('YmdHis') . $_FILES['image']['name'];
		move_uploaded_file($_FILES['image']['tmp_name'], '../member_picture/' . $image);
		$_SESSION['re_join'] = $_POST;
		$_SESSION['re_join']['image'] = $image;
		header('Location: personal_check.php');
		exit();
	}
}

//やり直し
if($_REQUEST['action'] == 'rewrite' && isset($_SESSION['re_join']) && $_REQUEST['id'] == $_SESSION['id']) {
	$_POST = $_SESSION['re_join'];
	$start_eat = $_POST['start_eat'];

}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>WOEO|Edit</title>
	<link rel="stylesheet" href="../css/reset.css">
	<link rel="stylesheet" href="../css/signup1.css">
</head>

<body>
	<header class="header">
		<div class="top"><h1>Profile Edit</h1></div>
		<ul class="link">
			<li><a href="personal.php?id=<?php print(htmlspecialchars($_SESSION['id'], ENT_QUOTES));?>">Profile</a></li>
		</ul>
	</header>

	<div class="content">
		<p class="attention">次のフォームに必要事項をご記入ください。</p>
		<form action="" method="post" enctype="multipart/form-data">
            <ul class="form-style-1">
                <li>
                    <label>アイコン<span class="required">必須</span></label><input type="file" name="image" size="" class="field-long">
                    <?php if($error['image'] === 'type'): ?>
                    <p class="error">* 写真などは「.gif」または「.jpg」「.png」の画像を指定してください</p>
                    <?php endif; ?>
                    <?php if($error['image'] === 'blank'): ?>
                    <p class="error">*　アイコンを指定してください</p>
                    <?php endif; ?>
                    <?php if(!empty($error)): ?>
                    <p class="error">*　恐れ入りますが、もう1度画像を選択してください</p>
                    <?php endif; ?>
                </li>
                <li>
                    <label>会食恐怖症になったきっかけ<span class="required">必須</span></label>
                    <textarea name="start_eat"  class="field-long field-textarea"><?php print(htmlspecialchars($start_eat, ENT_QUOTES)); ?></textarea>
                    <?php if($error['start_eat'] === 'blank'): ?>
                    <p class="error">* なった原因をご記入ください</p>
                    <?php endif; ?>
                </li>
                <li>
                    <label>現在の状況<span class="required">必須</span></label>
                    <select name="overcome" class="field-select">
                    <option value="これから取り組む">これから取り組む</option>
                    <option value="克服途中">克服途中</option>
                    <option value="克服した">克服した</option>
                    </select>
                    <?php if(!empty($error)): ?>
                    <p class="error">*　恐れ入りますが、もう1度選択してください</p>
                    <?php endif; ?>
                </li>
                <li>
                    <input type="submit" value="確認へ">
                </li>
            </ul>
        </form>
	</div>
</body>
</html>
