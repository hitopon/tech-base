<!DOCTYPE html>
<html lang=ja>
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>


<?php

     //DB接続設定
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    //テーブルの作成
    $sql = "CREATE TABLE IF NOT EXISTS tbtest"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "pass char(32),"
    . "date DATETIME"
    .");";
    $stmt = $pdo->query($sql);
    

   //削除機能
   if(!empty($_POST["delete"]) && !empty($_POST["delpass"])) {  
    $id = $_POST["delete"];
    $delpass = $_POST["delpass"];
    $sql = 'DELETE from tbtest WHERE id=:id && pass=:delpass';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id,PDO::PARAM_INT);
    $stmt->bindParam(':delpass', $delpass,PDO::PARAM_STR);
    $stmt->execute();
    
    }

  //編集機能
  if(!empty($_POST["edit"]) && !empty($_POST["editpass"])) {
    $id = $_POST["edit"];
    $editpass = $_POST["editpass"];
    $sql = 'SELECT * FROM tbtest WHERE id=:id && pass=:editpass';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id,PDO::PARAM_INT);
    $stmt->bindParam(':editpass', $editpass,PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll();
    foreach($results as $row){
        $edName = $row['name'];
        $edComment = $row['comment'];
    }
  }

 
 
 //名前とコメントとパスワードがあるなら
 if(!empty($_POST["name"]) && !empty($_POST["comment"])){

  //編集番号が送信されたなら編集モード
  if(!empty($_POST["editnum"])) {
      $id = $_POST["editnum"];
      $name = $_POST["name"];
      $comment = $_POST["comment"];
      $date = date("Y/m/d/ H:i:s");
      $sql = $pdo -> prepare('UPDATE tbtest SET name=:name,comment=:comment,date=:date WHERE id=:id');
    //   $sql = 'UPDATE tbtest SET name=:name,comment=:comment,date=:date WHERE id=:id';
      $sql -> bindParam(':id', $id, PDO::PARAM_INT);
      $sql -> bindParam(':name', $name, PDO::PARAM_STR);
      $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
      $sql -> bindParam(':date', $date, PDO::PARAM_STR);
      $sql -> execute();
    //   $stmt = $pdo->prepare($sql);
    //   $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    //   $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
    //   $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    //   $stmt->execute();

  //編集番号が送信されてないなら追記モード
  }    
  if(!empty($_POST["pass"])){
      $name = $_POST["name"];
      $comment = $_POST["comment"];
      $pass = $_POST["pass"];
      $date = date("Y/m/d/ H:i:s");
      $sql = $pdo -> prepare("INSERT INTO tbtest (id,name, comment, pass, date) VALUES (:id, :name, :comment, :pass, :date)");
      $sql -> bindParam(':id', $id, PDO::PARAM_INT);
      $sql -> bindParam(':name', $name, PDO::PARAM_STR);
      $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
      $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
      $sql -> bindParam(':date', $date, PDO::PARAM_STR);
      $sql -> execute();
 }
}
?>

<form action="" method="post">
  <input type="text" name="name" placeholder="名前" value="<?php if(!empty($edName)){echo $edName;}?>"><br>
  <input type="text" name="comment" placeholder="コメント" value="<?php if(!empty($edComment)){echo $edComment;} ?>"><br>
   <input type="hidden" name="editnum" placeholder="" value="<?php if(!empty($_POST["edit"])){echo $_POST["edit"];} ?>">
   <input type="text" name="pass" placeholder="パスワード">
   <input type="submit" name="submit" value="送信" ><br><br>
    
  <input type="number" name="delete" placeholder="削除対象番号"><br>
   <input type="text" name="delpass" placeholder="パスワード">
   <input type="submit" name="submit" value="削除" > <br><br>
    
   <input type="number" name="edit" placeholder="編集対象番号"><br>
   <input type="text" name="editpass" placeholder="パスワード">
   <input type="submit" name="submit" value="編集">
</form>

  <?php
    $sql = 'SELECT * FROM tbtest';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['date'].'<br>';
        echo "<hr>";
    }
    
  ?>

</body>
</html>
    