<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<html>
	<head>
		<title>猫のかわいい瞬間をみつけよう(^・・^)</title>
	</head>
<form>
	<font size="5">猫のかわいい瞬間を共有する掲示板(^・・^)</font><br/></br>
</form>
<?php
	$filename = "kadai2_3.txt";//開くファイル名
	
/****************　　　　　　mission2-5で追記（編集）　　　　　　　　****************/	
//編集時以外は名前とコメント打ち込みボックスには何も表示しない//
	$rewrite_name="";//空の文字列
	$rewrite_comment="";//空の文字列
	$rewrite_sign="not_rewrite";//編集のためのコメントか否かを判断する変数
	$Existance=0;$First_Existance=0;//編集指定された投稿番号の存在を判断する変数
	if(empty ($_POST['rewrite_number'])){}
	else
	{
		$Get_Information=file($filename,FILE_SKIP_EMPTY_LINES);//投稿情報を先読みして配列に保管
		$fp = fopen($filename, 'r');//(読み取りモード)
	//入手した配列の行数分ループ//		
		foreach($Get_Information as $key){
			$Arrays = explode("<>",$key);//仕切り文字を除去し$Arraysに保管
		//投稿番号が編集番号と同じ場合、投稿者名とコメント内容を保存//
			if($Arrays[0]==$_POST['rewrite_number'])
			{
				if($Arrays[4]===$_POST['password_r'])
				{
					$rewrite_name=$Arrays[1];
					$rewrite_comment=$Arrays[2];
					$rewrite_sign="rewrite";//次のコメントが編集によるものだと判断させる		
				}
				else
				{
					echo "パスワードが違います！\n";
				}
			}
		//$Existanceと$First_Existanceは編集指定番号が見つかった際、異なる値を最終的に示す//
			else
			{
				$Existance++;
			}
			$First_Existance++;
		}
		fclose($fp);
	}
?>
<form>
	<font size="3">
	<?php 
		if($First_Existance==$Existance&&$First_Existance!=0)echo "編集指定した投稿番号は存在しません</br>";?>
	</font>
</form>
<?php
/****************　　　　　　以上mission2-5で追記　　　　　　　　****************/
	
/****************　　　　　　mission2-4で追記（削除）　　　　　　　　****************/
	$Existance=0;$First_Existance=0;
	//削除対象番号を受け取った時のみ動作
	if(empty ($_POST['delete_number'])){}
	else
	{
		$Get_Information=file($filename,FILE_SKIP_EMPTY_LINES);//投稿情報を先読みして配列に保管
		$fp = fopen($filename, 'w');//(上書きモード)
	//入手した配列の行数分ループ//		
		foreach($Get_Information as $key){
			$Arrays = explode("<>",$key);//仕切り文字を除去し$Arraysに保管
		//投稿番号が削除番号と同じ場合、削除されたことを示す"deleted<>\n"に更新する//
			if($Arrays[0]==$_POST['delete_number'])
			{
			//パスワード参照//
				if($Arrays[4]===$_POST['password_d'])
				{
					fwrite($fp,"deleted<>\n");
				}
				else
				{
					echo "パスワードが違います！\n";
					fwrite($fp,$key);//内容を保持
				}
			}
		//$Existanceと$First_Existanceは削除指定番号が見つかった際、異なる値を最終的に示す//
			else
			{
				fwrite($fp,$key);
				$Existance++;
			}
			
			$First_Existance++;
		}
		fclose($fp);
	}
?>
<form>
	<font size="3">
	<?php 
		if($First_Existance==$Existance&&$First_Existance!=0)echo "削除指定した投稿番号は存在しません</br>";?>
	</font>
</form>
<?php
/****************　　　　　　以上mission2-4で追記　　　　　　　　****************/

	$Name = htmlspecialchars($_POST['name']);//読み込んだ投稿者名を格納
	$Comment = htmlspecialchars($_POST['comment']);//読み込んだコメントを格納
	
/****************　　　　　　mission2-5で追記　　　　　　　　****************/
	
	//編集のためのコメントであることを示す"rewrite"を受け取った際に動作
	if($_POST['rewrite_sign']=="rewrite")
	{
		$Get_Information=file($filename,FILE_SKIP_EMPTY_LINES);//投稿情報を先読みして配列に保管
		$fp = fopen($filename, 'w');//(上書きモード)
	//入手した配列の行数分ループ//		
		foreach($Get_Information as $key){
			$Arrays = explode("<>",$key);//仕切り文字を除去し$Arraysに保管
		//投稿番号が編集番号と同じ場合、投稿者名とコメントを更新//
			if($Arrays[0]==$_POST['recieved_rewrite_number'])
			{
				fwrite($fp,$Arrays[0]."<>".$_POST['name']."<>".$_POST['comment']."<>".$Arrays[3]."<>".$Arrays[4]."<>\n");
			}
			else
			{
				fwrite($fp,$key);
			}
		}
		fclose($fp);
	}	
	
/****************　　　　　　以上mission2-5で追記　　　　　　　　****************/
	
	//編集でない場合のコメントを受け取った際に動作
	else if(empty($Comment)){}//読み込んだ文字列が空でないことを確認
	else
	{	
		if(empty($Name))//名前入力がなかった場合名無しに設定
		{
			$Name="名無しさん";
		}
	
	//新しいコメントの入る行を把握する//
		$fp = fopen($filename, 'r');//(読み取り追記モード)
		$Number=1;//配列の要素番号	
		while(fgets($fp))//行数をカウント
		{
			$Number++;
		}
		fclose($fp);		
	//新しいコメントを書込み//
		$fp = fopen($filename, 'a');//指定したファイルをオープン(追記モード)
	//コメント情報に加えて、パスワードを最後に保存（2-6で追記）//	
		fwrite($fp, $Number."<>".$Name."<>".$Comment."<>".date('Y/m/d H:i:s')."<>".$_POST['password_w']."<>\n");//改行を語尾に付けて書込み
		fclose($fp);
	}		
?>
	
<form action = "mission_2-6_action.php" method = "post"><!_ テキスト書込を行うためのファイルを呼び出し_>
	名前　 ：<input type="text"name="name"value="<?php echo $rewrite_name;?>"/><!_ 名前入力を求める_>
	</br>
	コメント：<input type="text"name="comment"value="<?php echo $rewrite_comment;?>"<!_ コメント入力を求める_>
	パスワード：<input type="text"name="password_w"placeholder="削除編集時に必要です"/><!_ パスワード初期設定_>
	<input type="hidden"name="rewrite_sign"value="<?php echo $rewrite_sign;?>"/><!_　編集のためのコメントかどうか示す_>
	<input type="hidden"name="recieved_rewrite_number"value="<?php echo $_POST['rewrite_number'];?>"/><!_　編集対象番号を受け渡し_>
	<input type="hidden"name="password_r"value="<?php echo $_POST['password_r'];?>"/><!_　編集時に受け取ったパスワードを受け渡し_>
	<input type="submit" value="書込する" /><!_　書込ボタン作成_>
</form>
<form action = "mission_2-6_action.php" method = "post"><!_ テキスト削除を行うためのファイルを呼び出し_>
	</br>
	削除する投稿番号：<input type="text"name="delete_number"/><!_　削除対象番号_>
	パスワード：<input type="text"name="password_d"placeholder="コメント投稿時のパスワード"/><!_ パスワード確認_>
	<input type="submit" value="削除する" /><!_　削除ボタン作成_>
</form>
<form action = "mission_2-6_action.php" method = "post"><!_ テキスト編集を行うためのファイルを呼び出し_>
	</br>
	編集する投稿番号：<input type="text"name="rewrite_number"/><!_　編集対象番号_>
	パスワード：<input type="text"name="password_r"placeholder="コメント投稿時のパスワード"/><!_ パスワード確認_>
<input type="submit" value="編集する" /><!_　編集ボタン作成_>
</form>
<body>
	<?php
		$Get_Infomation = file('kadai2_3.txt',FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
		foreach($Get_Infomation as $key)
		{
			$Number=0;
			$New_value = explode("<>",$key);
			while($Number<4)
			{
				switch($Number)
				{
					case 0:
						if($New_value[$Number]=="deleted")
						{
							echo "削除された投稿です<hr>";
							$Number=4;
						}
						else
						{
							echo "投稿番号[".$New_value[$Number];
							$Number++;
						}
						break;
					case 1:
						echo "] 投稿者[".$New_value[$Number];
						$Number++;
						break;
					case 2:
						echo "] ".$New_value[$Number];
						$Number++;
						break;
					case 3:
						echo " [".$New_value[$Number]."]<hr>";
						$Number++;
						break;
					}
			}
		}
	?>

</body>
</html>