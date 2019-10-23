<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title>mission_5b</title>
    <style>
      form {
        margin-bottom: 20px;
      }
    </style>
  </head>
  <body>

    <?php

    //mysqlに接続
    $dsn = "mysql:dbname=tb****66db;host=localhost";
    $user = "tb-****66";
    $password = 'PASSWORD';
    $pdo = new PDO('データベース名', 'ユーザ名', 'パスワード', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    //テーブルを作成する
    $sql = "CREATE TABLE IF NOT EXISTS tb4"
   ." ("
   . "id INT AUTO_INCREMENT PRIMARY KEY,"
   . "name char(32),"
   . "comment TEXT,"
    . "date_time TEXT,"
    . "pass TEXT"
   .");";
   $stmt = $pdo->query($sql);


//投稿機能
    if (!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["pass"])&&empty($_POST["editNO"])) {
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $date_time = date("Y/m/d H:i:s");
    $pass = $_POST["pass"];
    $sql = $pdo -> prepare("INSERT INTO tb4 (name, comment, date_time, pass) VALUES (:name, :comment, :date_time, :pass)");
    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
    $sql -> bindParam(':date_time', $date_time, PDO::PARAM_STR);
    $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);

    $sql -> execute();

  }

  //編集選択機能
  if(!empty($_POST["edit_num"]) && !empty($_POST["editpass"])){

       $sql =$pdo -> prepare('SELECT * FROM tb4 WHERE id=:id AND pass=:pass');
       $sql -> bindParam(':id', $id, PDO::PARAM_INT);
       $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
       $id = $_POST["edit_num"];
       $pass = $_POST["editpass"];
       $sql -> execute();
  	   $results = $sql->fetchAll();
       foreach($results as $row){
  	   $edit_name = $row['name'];
       $edit_comment = $row['comment'];
       $edit_num=$row['id'];
     }
   }

//削除機能
    if(!empty($_POST["delete_num"]) && !empty($_POST["delpass"])){
    $id = $_POST["delete_num"];
    $pass = $_POST["delpass"];
    $sql = 'delete from tb4 where id=:id and pass=:pass';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':pass', $pass, PDO::PARAM_INT);
    $stmt->execute();
    }

  //編集実行機能
  if(!empty($_POST["editNO"])){
    $id = $_POST["editNO"]; //変更する投稿番号
    $pass = $_POST["pass"];
    $edit_name = $_POST["name"];
    $edit_comment = $_POST["comment"]; //変更したい名前、変更したいコメントは自分で決めること
    $sql = 'update tb4 set name=:name,comment=:comment where id=:id and pass=:pass';
    $sql = $pdo->prepare($sql);
    $sql->bindParam(':name', $edit_name, PDO::PARAM_STR);
    $sql->bindParam(':comment', $edit_comment, PDO::PARAM_STR);
    $sql->bindParam(':id', $id, PDO::PARAM_INT);
    $sql->bindParam(':pass', $pass, PDO::PARAM_STR);

    $sql->execute();
  }


    ?>

    <form action="mission_5b.php" method="post">
      <p>【投稿フォーム】</p>
      名前 : <input type="text" name="name" value="<?php if(isset($edit_name)) {echo $edit_name;} ?>"><br>
      コメント : <input type="text" name="comment"  value="<?php if(isset($edit_comment)) {echo $edit_comment;} ?>"><br>
      <input type="hidden" name="editNO" value="<?php if(isset($edit_num)) {echo $edit_num;} ?>">
      パスワード : <input type = "text" name = "pass"><br>
      <input type="submit" name="submit" value="送信">
    </form>

    <form action="mission_5b.php" method="post">
      <p>【削除フォーム】</p>
      削除対象番号 : <input type="text" name="delete_num"><br>
      パスワード : <input type = "text" name = "delpass"><br>
      <input type="submit" name="delete" value="削除">
    </form>

    <form action="mission_5b.php" method="post">
      <p>【編集フォーム】</p>
      編集対象番号 : <input type="text" name="edit_num" ><br>
      パスワード : <input type = "text" name = "editpass"><br>
      <input type="submit" value="編集">
    </form>
    <form>
      <p>------------------------------------------------</p><br>
      <p>【投稿一覧】</p>
    </form>
    <?php
    $sql = 'SELECT * FROM tb4';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
      //$rowの中にはテーブルのカラム名が入る
      echo $row['id'].' ';
      echo $row['name'].' ';
      echo $row['comment'].' ';
      echo $row['date_time'].' ';
      echo $row['pass'].'<br>';
      echo "<hr>";

    }
    ?>

  </body>
</html>
