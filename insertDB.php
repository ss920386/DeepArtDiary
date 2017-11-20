<?php 
  $o_url = $_POST['final_original_url'];
  $n_url = $_POST['final_new_url'];
  $f_text = $_POST['final_text'];
  $emo = $_POST['emotion'];

  //access DB
  $db_host = "dbhome.cs.nctu.edu.tw";
  $db_name = "yiting0424_cs_dream";
  $db_user = "yiting0424_cs";
  $db_password = "cscs";
  $dsn = "mysql:host=$db_host;dbname=$db_name";
  $db = new PDO($dsn, $db_user, $db_password);

  try{
    $insert_sql = "INSERT INTO `yiting0424_cs_dream`.`pictures` (id, timestamp, diary_content, original_addr, new_addr, mood) VALUES (NULL, NULL, :diary_content, :original_addr, :new_addr, :mood)";
    $insert_rs = $db->prepare($insert_sql);
    $insert_rs->bindParam(':diary_content', $f_text);
      $insert_rs->bindParam(':original_addr', $o_url);
      $insert_rs->bindParam(':new_addr', $n_url);
      $insert_rs->bindParam(':mood', $emo);
    $insert_rs->execute();
  }
  catch(PDOException $e){
    echo "Errpt: " . $e->getMessage();
  }
?>