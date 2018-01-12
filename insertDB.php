<?php 
  $o_url = $_POST['final_original_url'];
  $n_url = $_POST['final_new_url'];
  $f_text = $_POST['final_text'];
  $emo = $_POST['emotion'];
  $emo_score = $_POST['score'];

  //access DB
  $db_host = "localhost";
  
  $db_name = "dream";
  $db_user = "ca";
  $db_password = "catcc2017";
  $dsn = "mysql:host=$db_host;dbname=$db_name";
  $db = new PDO($dsn, $db_user, $db_password);

  try{
    $insert_sql = "INSERT INTO `dream`.`pictures` (id, timestamp, diary_content, original_addr, new_addr, mood, score) VALUES (NULL, NULL, :diary_content, :original_addr, :new_addr, :mood, :score)";
    $insert_rs = $db->prepare($insert_sql);
    $insert_rs->bindParam(':diary_content', $f_text);
      $insert_rs->bindParam(':original_addr', $o_url);
      $insert_rs->bindParam(':new_addr', $n_url);
      $insert_rs->bindParam(':mood', $emo);
      $insert_rs->bindParam(':score', $emo_score);
    $insert_rs->execute();
  }
  catch(PDOException $e){
    echo "Errpt: " . $e->getMessage();
  }
?>
