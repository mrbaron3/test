<?php

function h($s){
	htmlspecialchars($s,ENT_QUOTES,"utf-8");
}

function sampleCsv($results) {

	try {

		//CSV形式で情報をファイルに出力のための準備
		$csvFileName = 'sampaleCsv.csv';
		$res = fopen($csvFileName, 'w');
		if ($res === FALSE) {
			throw new Exception('ファイルの書き込みに失敗しました。');
		}

		// データ一覧。この部分を引数とか動的に渡すようにしましょう

		// ループしながら出力
		foreach($results as $dataInfo) {

			// 文字コード変換。エクセルで開けるようにする
			mb_convert_variables('SJIS', 'UTF-8', $dataInfo);

			// ファイルに書き出しをする
			fputcsv($res, $dataInfo);
		}

		// ハンドル閉じる
		fclose($res);

		// ダウンロード開始
		header('Content-Type: application/octet-stream');

		// ここで渡されるファイルがダウンロード時のファイル名になる
		header('Content-Disposition: attachment; filename=sampaleCsv.csv'); 
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: ' . filesize($csvFileName));
		readfile($csvFileName);

	} catch(Exception $e) {

		// 例外処理をここに書きます
		echo $e->getMessage();

	}
}


//****************DB初期設定*********************************////
$host 	= 'mysql:host=mysql103.phy.lolipop.lan;charset=utf8;';
$dbname = 'LAA0157006-linkplusdb';
$dbuser = 'LAA0157006';
$dbpass = 'takashi2198';

//****************DB初期設定*********************************//

// DBハンドラ
try{
$pdo = new PDO($host."dbname=".$dbname,$dbuser,$dbpass,
    array(
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET `utf8`"
    ));
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
//$pdo>setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

// if($pdo == null){
// 	echo("failed");
// }else{
// 	echo("success");
// }

// クエリの発行
$sql  = "select * from member_data";
$stmt = $pdo->query($sql);

$sql2 = 'select count(*) from member_data';
$stmt2 = $pdo->query($sql2);
$row =  $stmt2->fetchColumn();


for ($i=0; $i < $row; $i++) {
	$results[$i] = $stmt->fetch(PDO::FETCH_ASSOC);
	//DBから情報を抜き出して加工
		if($results[$i]["member_sex"] == "1"){
			$results[$i]["member_sex"] = htmlspecialchars("男",ENT_QUOTES,"utf-8");
		}

		if($results[$i]["member_sex"] == "2"){
			$results[$i]["member_sex"] = htmlspecialchars("女",ENT_QUOTES,"utf-8");
		}

		if($results[$i]["member_course"] == 1){
			$results[$i]["member_course"] = htmlspecialchars("10,000 LPB",ENT_QUOTES,"utf-8");
		}
		if($results[$i]["member_course"] == 2){
			$results[$i]["member_course"] = htmlspecialchars("100,000 LPB",ENT_QUOTES,"utf-8");
		}
		if($results[$i]["member_course"] == 3){
			$results[$i]["member_course"] = htmlspecialchars("300,000 LPB",ENT_QUOTES,"utf-8");
		}
		if($results[$i]["member_course"] == 4){
			$results[$i]["member_course"] = htmlspecialchars("500,000 LPB",ENT_QUOTES,"utf-8");
		}
		if($results[$i]["member_course"] == 5){
			$results[$i]["member_course"] = htmlspecialchars("1,000,000 LPB",ENT_QUOTES,"utf-8");
		}
		if($results[$i]["member_course"] == 6){
			$results[$i]["member_course"] = htmlspecialchars("3,000,000 LPB",ENT_QUOTES,"utf-8");
		}
		if($results[$i]["member_course"] == 7){
			$results[$i]["member_course"] = htmlspecialchars("10,000,000 LPB",ENT_QUOTES,"utf-8");
		}
}
}catch(PDOException $e){
	//error
    print('Connection failed:'.$e->getMessage());
    die();
}



sampleCsv($results);




