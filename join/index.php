<?php
session_start();
require('../dbconnect.php');

//記入がしてあるか確認。$_POSTの存在があるかどうか。
if(!empty($_POST)){
    if($_POST['name'] === '') {
        $error['name'] = 'blank';
    }
    if($_POST['email'] === ''){
        $error['email'] = 'blank';
    }
    //メールアドレスの正規表現
    //([a-zA-Z0-9])+:1回以上の繰り返し, ([a-zA-Z0-9\._-])*:0回以上の繰り返し
    $reg_str = "/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/";
    if(preg_match($reg_str, $_POST['email']) === 0){
        $error['email'] = 'false_email';
    }
    //文字数制限をかけている
    // if(strlen($_POST['password']) < 4){
    //     $error['password'] = 'length';
    // }

    // 半角英数字をそれぞれ1種類以上含む8文字以上100文字以下の正規表現
    $reg_str2 = '/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,100}+\z/i';
    if(preg_match($reg_str2, $_POST['password']) === 0){
        $error['password'] = 'false_password';
    }
    if($_POST['password'] === ''){
        $error['password'] = 'blank';
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
    if($_POST['start_eat'] === ''){
        $error['start_eat'] = 'blank';
    }

    //重複のチェック
    if(empty($error)){
        $member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE email=?');
        $member->execute(array($_POST['email']));
        $record = $member->fetch();
        if($record['cnt'] > 0){
            $error['email'] = 'duplicate';
        }
    }

    if(empty($error)){
        $image = date('YmdHis') . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], '../member_picture/' . $image);
        $_SESSION['join'] = $_POST;
        $_SESSION['join']['image'] = $image;
        header('Location: check.php');
        exit();
    }
}

if($_REQUEST['action'] == 'rewrite' && isset($_SESSION['join'])) {
    $_POST = $_SESSION['join'];
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>WOEO|Sign Up</title>
    <link rel="stylesheet" href="../css/reset.css">
    <!-- <link rel="stylesheet" href="../css/common.css"> -->
    <link rel="stylesheet" href="../css/signup1.css">
</head>

<body>
    <div id="wrap">
        <header class="header">
            <div class="top"><h1>Sign Up</h1></div>
            <ul class="link">
                <li><a href="../index.html">Top</a></li>
            </ul>
        </header>

        <div class="content">
            <p class="attention">次のフォームに必要事項をご記入ください。</p>
            <form action="" method="post" enctype="multipart/form-data">
                <ul class="form-style-1">
                    <li>
                        <label>ユーザ名<span class="required">必須</span></label><input type="text" name="name" class="field-divided" value="<?php print(htmlspecialchars($_POST['name'], ENT_QUOTES)); ?>">
                        <?php if($error['name'] === 'blank'): ?>
                        <p class="error">* ユーザ名を入力してください</p>
                        <?php endif; ?>
                    </li>
                    <li>
                        <label>メールアドレス<span class="required">必須</span></label><input type="email" name="email" class="field-long" value="<?php print(htmlspecialchars($_POST['email'], ENT_QUOTES)); ?>">
                        <?php if($error['email'] === 'blank'): ?>
                        <p class="error">* メールアドレスを入力してください</p>
                        <?php endif; ?>
                        <?php if($error['email'] === 'false_email'): ?>
                        <p class="error">* メールアドレスを正しく入力してください</p>
                        <?php endif; ?>
                        <?php if($error['email'] === 'duplicate'): ?>
                        <p class="error">* 指定されたメールアドレスは既に登録されています</p>
                        <?php endif; ?>
                    </li>
                    <li>
                        <label>パスワード<span class="required">必須</span></label><input type="password" name="password" class="field-long" value="<?php print(htmlspecialchars($_POST['password'], ENT_QUOTES)); ?>">
                        <?php if($error['password'] === 'false_password'): ?>
                        <p class="error">* 半角英数字をそれぞれ1種類以上含む8文字以上で入力してください</p>
                        <?php endif; ?>
                        <?php if($error['password'] === 'blank'): ?>
                        <p class="error">* パスワードを入力してください</p>
                        <?php endif; ?>
                    </li>
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
                        <textarea name="start_eat" placeholder="なぜ会食恐怖症になったのかを具体的にお書きください"  class="field-long field-textarea"><?php print(htmlspecialchars($_POST['start_eat'], ENT_QUOTES)); ?></textarea>
                        <?php if($error['start_eat'] === 'blank'): ?>
                        <p class="error">* なった原因をご記入ください</p>
                        <?php endif; ?>
                    </li>
                    <li>
                        <label>現在の状況</label>
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
    </div>

    <footer class="footer">
            <p class="copy"><small>&copy; WOEO All rights reserved</small></p>
    </footer>

</body>
</html>
