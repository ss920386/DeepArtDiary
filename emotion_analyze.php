<?php 
    if (isset($_POST['diary'])) {
      //Get input value from html
      $diary = $_POST['diary'];

      $username = '6ea0e84a-afc9-48d3-ba34-3f82b8e21965';
      $password = 'ugApX5POdrfl';
      $url = 'https://gateway.watsonplatform.net/natural-language-understanding/api/v1/analyze?version=2017-02-27&features=emotion';

      // Set post arguments for call
      $post_args = array(
          'text' => $diary
      );

    // Set header arguments for call
      $header_args = array(
          'Content-Type: text/plain',
          'Accept: application/json'
      );

    // Set options for REST call via curl
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);   
      curl_setopt($curl, CURLOPT_POST, true);
      curl_setopt($curl, CURLOPT_USERPWD, "6ea0e84a-afc9-48d3-ba34-3f82b8e21965:ugApX5POdrfl");        
      curl_setopt($curl, CURLOPT_HTTPHEADER, $header_args);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $post_args);

      $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

      // Actual REST call via curl and cleanup (closing) of curl call
      $emotion_result = curl_exec($curl);
      curl_close($curl);

      //Decode the data
      $decoded = json_decode($emotion_result);

      //$sadness = $decoded->emotion->document->emotion->sadness;
      //$joy = $decoded->emotion->document->emotion->joy;
      //$fear = $decoded->emotion->document->emotion->fear;
      //$disgust = $decoded->emotion->document->emotion->disgust;
      //$anger = $decoded->emotion->document->emotion->anger;
      //
      $emotion = $decoded->emotion->document->emotion;
      $sadness = $emotion->sadness;
      $joy = $emotion->joy;
      $fear = $emotion->fear;
      $disgust = $emotion->disgust;
      $anger = $emotion->anger;
      //echo "Sadness: ". $sadness ."";
      //echo "Joy: ". $joy ."";
      //echo "Fear:". $fear ."";
      //echo "Disgust: ". $disgust ."";
      //echo "Anger: ". $anger ."";

      //Begin to do style transfer
      $analyze_result = max($sadness,$joy,$fear,$disgust,$anger);
      //$result_score = sprintf("%f",$analyze_result);
      if($analyze_result==$sadness){
        echo "Sadness ";
      }
      if($analyze_result==$joy){
        echo "Joy ";
      }
      if($analyze_result==$fear){
        echo "Fear ";
      }
      if($analyze_result==$disgust){
        echo "Disgust ";
      }
      if($analyze_result==$anger){
        echo "Anger ";
      }
      
      echo $analyze_result;
      
    }
?>
