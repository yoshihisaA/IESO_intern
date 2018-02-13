<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<html>
	<head>
		<title>猫のかわいい瞬間をみつけよう(^・・^)</title>
	</head>
<form action = "mission_2-6_action.php" method = "post"><!_ テキスト保存を行うためのファイルを呼び出し_>
	<font size="5">猫のかわいい瞬間を共有する掲示板(^・・^)</font><br/></br>

	名前　 ：<input type="text"name="name"/></br><!_ 名前入力を求める_>
	コメント：<input type="text"name="comment"/><!_ コメント入力を求める_>
	パスワード：<input type="text"name="password_w"placeholder="削除編集時に必要です"/><!_ パスワード初期設定_>
	<input type="submit" value="書込する" /><!_　送信ボタン(送信すると表示)作成_>
</form>
<form action = "mission_2-6_action.php" method = "post"><!_ テキスト削除を行うためのファイルを呼び出し_>
	</br>
	削除する投稿番号：<input type="number"name="delete_number"/><!_　削除対象番号_>
	パスワード：<input type="text"name="password_d"placeholder="コメント投稿時のパスワード"/><!_ パスワード確認_>
	<input type="submit" value="削除する" /><!_　削除ボタン作成_>
</form>
<form action = "mission_2-6_action.php" method = "post"><!_ テキスト編集を行うためのファイルを呼び出し_>
	</br>
	編集する投稿番号：<input type="number"name="rewrite_number"/><!_　編集対象番号_>
	パスワード：<input type="text"name="password_r"placeholder="コメント投稿時のパスワード"/><!_ パスワード確認_>
	<input type="submit" value="編集する" /><!_　削除ボタン作成_>
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