<?php
// データベースへ接続するために必要な情報
// ホストはDBコンテナ
$host = 'mysql';
// mysql接続用のユーザー
$username = 'data_user';
$password = 'data';
//データベース
$database = 'data_master';

// データベースへ接続するためのクラス生成
$mysql = new mysqli($host, $username, $password, $database);

// 接続エラーの確認
if ($mysql->connect_error) {
    die("データベース接続エラー: " . $mysql->connect_error);
}

$sql = "SELECT * FROM classes";
//URLパラメータを受け取り
$id = 0;
if(isset($_GET['class_id'])){
    $id = $_GET['class_id'];
}

// クエリの実行
if($id){
    $sql = "SELECT * FROM classes WHERE class_id = " . $id;
}
else{
    $sql = "SELECT * FROM classes";
}

$result = $mysql->query($sql);

// 結果の処理
if ($result) {
    if ($result->num_rows > 0) {
        // 結果セットから行を取得して処理
        while ($row = $result->fetch_assoc()) {
            // 各行のデータを表示
            echo "class_id: " . $row["class_id"] . ", class_name: " . $row["class_name"] ."<br>";
        }
    } else {
        echo "該当するデータはありません。";
    }
    // 結果セットを解放
    // $result->free();
} else {
    echo "クエリの実行に失敗しました: " . $mysql->error;
}

// データベース接続を閉じる
$mysql->close();

?>
