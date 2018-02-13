<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<html>
	<head>
		<title>猫のかわいい瞬間をみつけよう(^・・^)</title>
	</head>
<form action = "cat's_SNS.php" method = "post"><!_ テキスト保存を行うためのファイルを呼び出し_>
	<font size="5">猫のかわいい瞬間を共有する掲示板(^・・^)</font><br/></br>
</form>

<?php


$dsn = 'data base name';
$user = 'user name';
$password = 'password';

try{
   $pdo = new PDO($dsn, $user, $password);

    print('<br>');

    if ($pdo == null){
        print('接続に失敗しました。<br>');
    }else{
    }
}catch (PDOException $e){
    print('Error:'.$e->getMessage());
    die();
}
if(!empty($_POST['rewrite_number'])) echo"編集内容をフォームに打ち込むにゃ＞(^・・^)".'<br>';

$rewrite_sign="not_rewrite";//編集のためのコメントか否かを判断する変数　入力を受けるまでは当然not

/******************************* 編集(STEP1) *******************************/
//編集時以外は名前とコメント打ち込みボックスには何も表示しない//
$rewrite_name="";//空の文字列
$rewrite_comment="";//空の文字列

if(!empty ($_POST['rewrite_number']))
{
	$Existance = 0;//指定した番号が存在しないときは、ループ終了時にも値が0
	$sql = 'SELECT * FROM community';
	$results = $pdo -> query($sql);
//入手した配列の行数分ループ//
	foreach ($results as $row)//$rowの中にはテーブルのカラム名が入る
	{
    //投稿番号が編集番号と同じ場合、投稿者名とコメント内容を保存//
		if($row['id']==$_POST['rewrite_number'])
		{
			$Existance = 1;//編集番号と同じIDを持つ投稿が存在したことを示す
		//パスワード参照//
			if($row['password']===$_POST['password_r'])
			{
				$rewrite_name=$row['name'];
				$rewrite_comment=$row['comment'];
				$rewrite_sign="rewrite";//次のコメントが編集によるものだと判断させる		
			}
			else
			{
				echo "パスワードが違います！\n";
			}
			
		}
	}
}
?>
<form>
	<font size="3">
		<?php
			if(($Existance===0)&&(!empty ($_POST['rewrite_number'])))echo "編集指定した投稿番号は存在しません</br>";
			$Existance=0;
		?>
	</font>
</form>
<?php

/******************************* 編集(STEP2) *******************************/
//updateによって編集する
if($_POST['rewrite_sign']=="rewrite")
{
	$id = $_POST['recieved_rewrite_number'];
	$new_name = $_POST['name'];
	$new_comment = $_POST['comment'];
	$new_password = $_POST['password_w'];
	
	$sql = "update community set name='$new_name', comment='$new_comment', password='$new_password' where id = $id"; 
	$result = $pdo->query($sql);
}

/******************************* 新規投稿 *******************************/
//insertを行って、データを入力する.
else if(!empty($_POST['name'])&&!empty($_POST['comment'])&&empty($_POST['password_w']))
{
	echo "パスワードを設定してください！";
}
else if(!empty($_POST['name'])&&!empty($_POST['comment']))
{
	

	$sql = 'SELECT * FROM community';
	$results = $pdo -> query($sql);
	$id=1;
	foreach ($results as $row)
	{
			$id=$row['id'];
	}
	$sql = $pdo -> prepare("INSERT INTO community (id,name,comment,time,password) VALUES (:id,:name, :comment, :time, :password)");//executeとセット		 
	$sql -> bindParam(':id', $id, PDO::PARAM_STR);//変数固定
	$sql -> bindParam(':name', $name, PDO::PARAM_STR);//変数固定
	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	$sql -> bindParam(':time', $time, PDO::PARAM_STR);
	$sql -> bindParam(':password', $password, PDO::PARAM_STR);

//新しいコメントを書込み//
	$id++;//投稿履歴の最後のコメントの次の番号を新規投稿の番号にする
	$name = $_POST['name'];
	$comment = $_POST['comment'];
	$time = date('Y/m/d H:i:s');
	$password = $_POST['password_w'];//コメント情報に加えて、パスワードを最後に保存
	$sql -> execute();//実行
}

/******************************* 削除 *******************************/
//入力したデータをdeleteによって削除する
//削除対象番号を受け取った時のみ動作
$Existance = 0;//指定した番号が存在しないときは、ループ終了時にも値が0
if(!empty ($_POST['delete_number']))
{
	$sql = 'SELECT * FROM community';
	$results = $pdo -> query($sql);
	foreach ($results as $row){
		if($row['id']==$_POST['delete_number'])
		{
			$Existance =1;//削除番号と同じIDを持つ投稿が存在したことを示す
		//パスワード参照//
			if($row['password']===$_POST['password_d'])
			{
				$id = $_POST['delete_number'];
				$sql = "delete from community where id=$id";  
				$result = $pdo->query($sql);
				break;
			}
			else
			{
				echo "パスワードが違います！\n";
				break;
			}
		}
	}
	
}
?>
<form>
	<font size="3">
		<?php
			if(($Existance===0)&&!empty ($_POST['delete_number']))echo "削除指定した投稿番号は存在しません</br>";
		?>
	</font>
</form>

<?php/******************************* 基本フォーム *******************************/?>
<form action = "cat's_SNS.php" method = "post"><!_ テキスト書込を行うためのファイルを呼び出し_>
	名前　 ：<input type="text"name="name"value="<?php echo $rewrite_name;?>"/><!_ 名前入力を求める_>
	コメント：<input type="text"name="comment"value="<?php echo $rewrite_comment;?>"<!_ コメント入力を求める_>
	<br>
	パスワード：<input type="password"name="password_w"placeholder="削除編集時に必要です"/><!_ パスワード初期設定_>
	<input type="hidden"name="rewrite_sign"value="<?php echo $rewrite_sign;?>"/><!_　編集のためのコメントかどうか示す_>
	<input type="hidden"name="recieved_rewrite_number"value="<?php echo $_POST['rewrite_number'];?>"/><!_　編集対象番号を受け渡し_>
	<input type="hidden"name="password_r"value="<?php echo $_POST['password_r'];?>"/><!_　編集時に受け取ったパスワードを受け渡し_>
	<input type="submit" value="書込する" /><!_　書込ボタン作成_>
	<hr>
</form>

<?php/******************************* 削除フォーム *******************************/?>
<form action = "cat's_SNS.php" method = "post"><!_ テキスト削除を行うためのファイルを呼び出し_>
	削除する投稿番号：<input type="text"name="delete_number"/><!_　削除対象番号_>
	パスワード：<input type="password"name="password_d"placeholder="コメント投稿時のパスワード"/><!_ パスワード確認_>
	<input type="submit" value="削除する" /><!_　削除ボタン作成_>
	<hr>
</form>

<?php/******************************* 編集フォーム *******************************/?>
<form action = "cat's_SNS.php" method = "post"><!_ テキスト編集を行うためのファイルを呼び出し_>
	編集する投稿番号：<input type="text"name="rewrite_number"/><!_　編集対象番号_>
	パスワード：<input type="password"name="password_r"placeholder="コメント投稿時のパスワード"/><!_ パスワード確認_>
	<input type="submit" value="編集する" /><!_　編集ボタン作成_>
	<hr>
</form>

<?php
/******************************* 投稿履歴表示 *******************************/
//入力したデータをselectによって表示する
$sql = 'SELECT * FROM community ORDER BY id ASC';//idの昇順で取得([DESC]で降順)

$results = $pdo -> query($sql);
foreach ($results as $row)//$rowの中にはテーブルのカラム名が入る
{
    echo '['.$row['id'].'] name:[';
    echo $row['name'].'] time:[';
   	echo $row['time']."](^・・^)＜";
    echo $row['comment'].'<br>';
}

?>

</html>