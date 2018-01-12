<!DOCTYPE html>
<html>
<head>
<title>DeepArtDairy</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="css/home.css">
<link rel="stylesheet" type="text/css" href="css/modelBox.css">
<link rel="stylesheet" type="text/css" href="css/uploadModalBox.css">

<style>
.blurL1 {
    -webkit-filter: blur(2px); /* Safari 6.0 - 9.0 */
    filter: blur(2px);
}
.blurL2 {
    -webkit-filter: blur(5px); /* Safari 6.0 - 9.0 */
    filter: blur(5px);
}
</style>

</head>
<body>
<script>
  function diary_submit(){
    if($("#diary_text_area").val().length<6){
      alert("Please give more words...");
      return;
    }
    document.getElementById("f_text").value = $("#diary_text_area").val();
    $.ajax({
        url: 'emotion_analyze.php',
        type: 'POST',
        data: {
            diary: $("#diary_text_area").val()
        },
        success: function(msg) {
           var res = msg.split(" ");
           document.getElementById("emo").value = res[0];
           document.getElementById("emo_score").value = res[1];
           console.log(document.getElementById("emo").value);
           console.log(document.getElementById("emo_score").value = res[1]);
        }               
    });
    //document.getElementById('diaryModal_form').submit();
    
    //defined in js/uploadModalBox.js
    ppopUp();
  }
</script>
<script>
  function img_upload(){
    var e =document.getElementById("emo").value;
    if(e==="Sadness"){
      console.log("Sadness Mode");
      uploadImage("ed83fea8-1b90-11e7-afe2-06d95fe194ed");
    }
    else if(e==="Joy"){
      console.log("Joy Mode");
      uploadImage("c7985a74-1560-11e7-afe2-06d95fe194ed");
    }
    else if(e==="Fear"){
      console.log("Fear Mode");
      uploadImage("c79854ee-1560-11e7-afe2-06d95fe194ed");
    }
    else if(e==="Anger"){
      console.log("Anger Mode");
      uploadImage("87b82671-1b84-11e7-afe2-06d95fe194ed");
    }
    else if(e==="Disgust"){
      console.log("Disgust Mode");
      uploadImage("c7985817-1560-11e7-afe2-06d95fe194ed");
    }
  }
</script>


  <?php
    //access DB
    $db_host = "localhost";
    $db_name = "dream";
    $db_user = "ca";
    $db_password = "catcc2017";
    $dsn = "mysql:host=$db_host;dbname=$db_name";
    $db = new PDO($dsn, $db_user, $db_password);
  ?>


<!-- Sidebar 
<nav class="w3-sidebar w3-animate-top w3-xxlarge w3-light-grey" style="display:none;padding-top:150px" id="mySidebar">
  <a href="javascript:void(0)" onclick="w3_close()" class="w3-button w3-black w3-xxlarge w3-padding  w3-display-topright" style="padding:6px 24px">
    <i class="fa fa-remove"></i>
  </a>
  <div class="w3-bar-block w3-center">
    <form class="myForm" method="POST">
	  <textarea name="diary" placeholder="Write down your day"></textarea>
    <div id="upload" >
      <input type="file" name="file" id="file" class="inputfile" onchange="onFileSelected(event)"><br>
      <label for="file" id="uploadLabel" class="uploadLabel">Choose a file</label>
    </div>
	  <input type="submit" value="Submit" class="submitBtn">
	</form>
  </div>
</nav>-->


<!-- !PAGE CONTENT! -->
<div class="w3-content" style="max-width:1500px">

<!-- Header -->
<div class="w3-opacity">
<span class="w3-button w3-xxlarge w3-white w3-right" onclick="dpopUp()"><i class="fa fa-plus"></i></span> 
<div class="w3-clear"></div>
<header class="w3-center w3-margin-bottom" style="padding-bottom:20px">
  <h1><b>Your Heart Decides Your World</b></h1>
  <p><b>"One eye sees, the other feels." – Paul Klee</b></p>
  <p>Uplaod your diary with an image and see how your feelings affect your eyes.</p>
 <!-- <p class="w3-padding-16"><button class="w3-button w3-black" onclick="myFunction()">Toggle Grid Padding</button></p>-->
</header>
</div>

<!--Hidden Form for DB-->
<form class="myForm" method="POST" id="hidden_form">
  <input type="hidden" name="final_original_url" id="o_url" value="">
  <input type="hidden" name="final_new_url" id="n_url" value="">
  <input type="hidden" name="final_text" id="f_text" value="">
  <input type="hidden" name="emotion" id="emo" value="">
  <input type="hidden" name="score" id="emo_score" value="">
</form>

<!-- The Modal for Diary upload -->
<div id="diaryModal" class="modal w3-center">
  <!-- Modal content -->
  <div class="modal-content" id="diaryModal_content">
    <form class="myForm" id="diaryModal_form" method="POST">
      <span id="diaryModal_close" class="close">&times;</span><br>
      <textarea id="diary_text_area" name="diary" placeholder="Jot down your day (at least 5 words)"></textarea>
      <input type="button" onclick="diary_submit()" value="Next" class="submitBtn" id="nextBtn">
    </form>
  </div>
</div>


<!-- The Modal for Picture upload -->
<div id="picModal" class="modal w3-center">
  <!-- Modal content -->
  <div class="modal-content" id="picModal_content">
    <form class="myForm" method="POST">
      <span id="picModal_close" class="close">&times;</span><br>
      <div id="upload" >
        <input type="file" name="file" id="file" class="inputfile" onchange="onFileSelected(event)"><br>
        <label for="file" id="uploadLabel" class="uploadLabel">Choose a picture</label>
      </div>
      <input type="button" onclick="img_upload()" value="Submit" class="submitBtn" id="subBtn">
    </form>
  </div>
</div>

<!-- The Modal for transfered picture-->
<div id="myModal" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
    <span id="Modal_close" class="close">&times;</span>
    <img id='new_pic' class="" src='' style='width:100%;'>
    <b><p><div id='timestamp'></div>&nbsp;&nbsp;
          <div id='diary_mood'></div></p></b>
    <p id='diary_content'></p>
  </div>

</div>

<!-- Photo Grid -->
<div class="w3-row" id="myGrid" style="margin-bottom:128px">
    <?php
      //list others information
        $pictures_sql = "SELECT * FROM pictures order by timestamp desc";
        $people_row = $db->query($pictures_sql);
        $i=0;
        while($pic = $people_row->fetchObject())
        {
            if( $i %3 ==0 )
            {
                echo '<div class"w3-row">';
            }      
            echo '<div class="w3-third">';
            echo "<img src='" . $pic->original_addr . "' style='width:100%;cursor: pointer;' onclick=\"popUp('" . $pic->new_addr . "','" . $pic->timestamp . "','" . $pic->diary_content . "','" . $pic->mood . "','" . $pic->score . "');\">";
            echo "</div>";
            if( $i %3 ==2 )
            {
                echo "</div>";
            }      
          $i=$i+1;
        }
    ?>

</div>

<!-- End Page Content -->
</div>

<!-- Footer -->
<!--<footer class="w3-container w3-padding-64 w3-light-grey w3-center w3-opacity w3-xlarge" style="margin-top:128px"> 
  <i class="fa fa-facebook-official w3-hover-opacity"></i>
  <i class="fa fa-instagram w3-hover-opacity"></i>
  <i class="fa fa-snapchat w3-hover-opacity"></i>
  <i class="fa fa-pinterest-p w3-hover-opacity"></i>
  <i class="fa fa-twitter w3-hover-opacity"></i>
  <i class="fa fa-linkedin w3-hover-opacity"></i>
  <p class="w3-medium">Powered by <a href="https://www.w3schools.com/w3css/default.asp" target="_blank" class="w3-hover-text-green">w3.css</a></p>
</footer>-->
<script src="js/modelBox.js"></script>
<script src="js/add_diary.js"></script>
<script src="js/uploadModalBox.js"></script>

<!-- Deep Art Effects lib -->
  <script type="text/javascript" src="js/deepart/client/axios.standalone.js"></script>
  <script type="text/javascript" src="js/deepart/client/hmac-sha256.js"></script>
  <script type="text/javascript" src="js/deepart/client/sha256.js"></script>
  <script type="text/javascript" src="js/deepart/client/hmac.js"></script>
  <script type="text/javascript" src="js/deepart/client/enc-base64.js"></script>
  <script type="text/javascript" src="js/deepart/client/url-template.js"></script>
  <script type="text/javascript" src="js/deepart/client/sigV4Client.js"></script>
  <script type="text/javascript" src="js/deepart/client/apiGatewayClient.js"></script>
  <script type="text/javascript" src="js/deepart/client/simpleHttpClient.js"></script>
  <script type="text/javascript" src="js/deepart/client/utils.js"></script>
  <script type="text/javascript" src="js/deepart/client/apigClient.js"></script>

<!-- JQuery lib -->
  <script src="js/deepart/jquery.js"></script>
<!-- Fancybox for artwork preview -->
  <script src="js/deepart/fancybox.js"></script>
<!-- Image tools -->
  <script src="js/deepart/imageTools.js"></script>
<!-- Site script-->
  <script src="js/deepart/scripts.js"></script>

</body>
</html>
