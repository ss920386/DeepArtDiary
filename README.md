# DeepArtDiary

使用IBM Natural Language Understanding api 以及 DeepArt api 製作的照片日記牆。

## 小組成員
資工大三 [學號] 陳奕安
資工大三 0416201 曾怡婷
資工大三 [學號] 鄭婷安


## 作品簡述
使用工具：
+ IBM Watson Natural Language Understanding API
+ Deep Art Effects API
+ Imgur API
+ 網頁前後端技術(php, html, javascript, css)

我們建立一個網站讓使用者上傳自己的日記和相關照片，用IBM Watson Tone Analyzer分析日記文字的情緒，再用分析結果搭配Deep Art Effects將上傳照片轉換成不同風格，讓使用者對自己的圖文能有全新的感受和意識。

## 創作概念
**"One eye sees, the other feels." -- Paul Klee**

我們的眼睛就像戴了一副隱形的眼鏡，所看到的不單單只是眼前的景象，還有我們疊加上去的內心感受。隨著我們的感受不同，看到的東西也會不一樣。

我們的作品分析使用者的情緒，將照片轉換成不同的風格，就是想要用這份作品傳達：
**你的心情決定你的全世界。**

而照片隨著情緒不同轉換成各種風格，
或柔和恬淡、或悲傷抑鬱，
或詭譎怪誕、或狂野不羈，
都有其獨特的風味和美麗。

這就如同**人生的酸甜苦辣、喜怒哀樂，
不論滋味是好是壞、是喜是悲，
都值得我們細細流連品味。**
這便是我們作品想要傳達的另一個重點。

## 網站運作流程
1. 進入[Your Heart Decides Your World](http://people.cs.nctu.edu.tw/~yiting0424/Dream/home.php)
    ![](https://i.imgur.com/G0XasWh.png)
    + 後端從資料庫讀取照片資料並以三欄顯示

2. 按右上角的「+」開啟彈出視窗，輸入完日記內容後按下"Next"
    ![](https://i.imgur.com/M0vj7mg.png)
    + 後端會使用IBM Natural Language Understanding的api將資料做情緒分析
    + 情緒有5種：Joy, Sadness, Fear, Disgust, Anger
    + 情緒分析的結果(取5種情緒裡分數最高的)會被送到下一關用來決定圖片的轉換風格

3. 按下Next後彈出上傳照片的視窗，案Choose a picture選擇上傳的照片
    ![](https://i.imgur.com/FXG4KY2.png)
    + 為了將照片存在網上，有使用Imgur的api將上傳的照片存到imgur裡
    + 上傳完畢，照片網址將取代原本文字顯示在按鈕上(一定要等網址顯示後才能按Submit進行下一步)
        ![](https://i.imgur.com/FqKvCTJ.png)

4. 按下Submit按鈕並等待後端處理，處理完頁面會自動重載
    + 按下Submit按鈕會讓後端將包括照片網址以及使用的style id(由3.分析出來的情緒決定)打包送到Deep Art的api做處理
    + Deep Art Effects api收到Request處理完後會回傳風格轉換後的照片網址
    + 由於Deep Art Effects端存的照片只能保存很少天，後端將回傳的照片網址藉由api再次上傳到Imgur
5. 頁面重載後會在照片牆上顯示新上傳的照片
    ![](https://i.imgur.com/dhYCcXj.png)
6. 點選新上傳的照片，就會看到妳的文字被分析出來的情緒以及用那個情緒轉換過風格的照片
    ![](https://i.imgur.com/OXrwTf9.jpg)
    ![](https://i.imgur.com/xARHt0X.jpg)
    + 如果點照片沒有視窗彈出的話請參考[Bug](#bug)

## 實作紀錄
(可能要包含我們怎麼/為甚麼決定哪種情緒要用哪種風格/ API使用紀錄)
### 使用IBM Watson Natural Language Understanding API
+ [官方Demo](https://natural-language-understanding-demo.mybluemix.net/?cm_mc_uid=48916234844515071243680&cm_mc_sid_50200000=1511157280&cm_mc_sid_52640000=1511247266)
+ [官方API Document](https://www.ibm.com/watson/developercloud/natural-language-understanding/api/v1/#get-analyze)
+ [申請帳號(免費使用30天)](https://console.bluemix.net/registration/?target=/catalog/services/natural-language-understanding/&cm_mmc=Earned--Watson+Core+-+Platform--WW_WW-_-intercom&cm_mmca1=000000OF&cm_mmca2=10000409&)

我參考官方API的Curl做法在php檔案裡將純文字做分析。
```php
<?php 
if (isset($_POST['diary'])) {
      //Get input value from html
      $content = $_POST['diary'];

      $url = 'https://gateway.watsonplatform.net/natural-language-understanding/api/v1/analyze?version=2017-02-27&features=emotion';

      // Set post arguments for call
      $post_args = array(
          'text' => $content
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
    //CHANGE YOUR　USERNAME AND PASSWORD HERE
      curl_setopt($curl, CURLOPT_USERPWD, "USERNAME:PASSWORD");        
      curl_setopt($curl, CURLOPT_HTTPHEADER, $header_args);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $post_args);

      $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

      // Actual REST call via curl and cleanup (closing) of curl call
      $emotion_result = curl_exec($curl);
      curl_close($curl);

      //Decode the data
      $decoded = json_decode($emotion_result);

      $sadness = $decoded->emotion->document->emotion->sadness;
      $joy = $decoded->emotion->document->emotion->joy;
      $fear = $decoded->emotion->document->emotion->fear;
      $disgust = $decoded->emotion->document->emotion->disgust;
      $anger = $decoded->emotion->document->emotion->anger;
      
      //echo the score of each emotion
      echo "Sadness: ". $sadness ."";
      echo "Joy: ". $joy ."";
      echo "Fear:". $fear ."";
      echo "Disgust: ". $disgust ."";
      echo "Anger: ". $anger ."";
        
      $analyze_result = max($sadness,$joy,$fear,$disgust,$anger);
      //echo $analyze_result;
      if($analyze_result==$sadness){
        echo "Sadness";
      }
      if($analyze_result==$joy){
        echo "Joy";
      }
      if($analyze_result==$fear){
        echo "Fear";
      }
      if($analyze_result==$disgust){
        echo "Disgust";
      }
      if($analyze_result==$anger){
        echo "Anger";
      }
      
    }
?>
```
在html裡加入javascript腳本傳送文字並取得結果。
```javascript
$.ajax({
        url: 'emotion_analyze.php',
        type: 'POST',
        data: {
            diary: $("#diary_text_area").val()
        },
        success: function(msg) {
           console.log(msg);
        }               
    });
```

### 使用Deep Art Effects API
+ [官方網站](https://www.deeparteffects.com/)
+ [註冊申請API Key(免費會員14天且有request限制)](https://www.deeparteffects.com/user/registration)
+ [我參考的Github專案](https://github.com/deeparteffects/deepart-api-html-js-example)

參考專案的主角是script.js，整個向API發Request的流程大概都寫在裡面了。我參考這個專案，將程式碼改成按下按鈕後會上傳照片到Imgur(上傳照片後呼叫onFileSelected函式)，之後如果user再按下Submit則會用分析後的結果當參數呼叫uploadImage，最後再將API的回傳結果網址上傳到Imgur。

```javascript
var imageBinary;
var styles;
var resultCheck;
var submissionId;
var maxImageSize;
var imgur_url;

var deepArtEffectsClient = apigClientFactory.newClient({
	apiKey: 'APIKEY',
	accessKey: 'ACCESSKEY',
    secretKey: 'SECRETKEY'
});


function uploadImage(styleId) {
	if(imageBinary==null) {
		alert('Please choose a picture first')
		return;
	}

	maxImageSize = 1980; //use high quality

	optimizeForPrint = false;
	useOriginalColors = false;

	var body = { 
		'styleId': styleId,
		'imageBase64Encoded': imageBinary,
		'optimizeForPrint': optimizeForPrint,
		'useOriginalColors': useOriginalColors,
		'imageSize': maxImageSize
	};

	deepArtEffectsClient.uploadPost(null, body)
	.then(function(result) {
		console.log("Successfully uploaded image");
		submissionId = result.data.submissionId
		resultCheck = setInterval(imageReadyCheck, 2500);
	}).catch(function(result){
        //This is where you would put an error callback
        console.log("Error uploading image");
    });
}
function imageReadyCheck() {
	var params = {
    	submissionId: submissionId,
	};
	deepArtEffectsClient.resultGet(params)
	.then(function(result) {
		console.log("Successfully status check");
		if(result.data.status=="finished") {
			console.log("Image uploaded done:"+result.data.url);

			
			/* Request the image and Put image to Imgur*/
			console.log("Uploading file to Imgur..");

		    // Replace ctrlq with your own API key
		    var apiUrl = 'https://api.imgur.com/3/image';
		    var apiKey = 'fc37366558b0be2';


		    $.ajax({ 
			    url: 'https://api.imgur.com/3/image',
			    async: false,
			    headers: {
			        Authorization: 'Client-ID ' + apiKey,
			    },
			    type: 'POST',
			    data: {
			        'image': result.data.url
			    },
			    success: function(response) { 
			    	//console.log(response);
				    //var r = JSON.parse(response);
				    //console.log(r);
				    new_imgur_url = response.data.link;
				    document.getElementById("n_url").value = new_imgur_url; 
				    console.log(new_imgur_url);
				    console.log(document.getElementById("n_url").value);
			    }
			});

			//Send data to insertDB.php
			$.ajax({
		        url: 'insertDB.php',
		        type: 'POST',
		        data: {
		        	final_original_url: document.getElementById("o_url").value,
		            final_new_url: document.getElementById("n_url").value,
		            final_text: document.getElementById("f_text").value,
		            emotion: document.getElementById("emo").value
		        },
		        success: function(msg) {
		        	document.getElementById('diaryModal_form').submit();
		        }               
		    });

			//Reload Page
    		//
		}
	}).catch(function(result){
        console.log("Error checking status");
    });
}

function onFileSelected(event) {
	// Begin file upload
    console.log("Uploading file to Imgur..");
    var label = document.getElementById('uploadLabel');
	label.innerHTML = "Uploading...";

    var files = event.target.files;
    var file = files[0];

    // Replace ctrlq with your own API key
    var apiUrl = 'https://api.imgur.com/3/image';
    var apiKey = 'IMGURKEY';

    var settings = {
        async: false,
        crossDomain: true,
        processData: false,
        contentType: false,
        type: 'POST',
        url: apiUrl,
        headers: {
          Authorization: 'Client-ID ' + apiKey,
          Accept: 'application/json'
        },
        mimeType: 'multipart/form-data'
    };

    var formData = new FormData();
    formData.append("image", files[0]);
    settings.data = formData;

    console.log(formData);

    // Response contains stringified JSON
    // Image URL available at response.data.link
    $.ajax(settings).done(function(response) {
      console.log(response);
      var r = JSON.parse(response);
      console.log(r);
      imgur_url = r.data.link;
      console.log(imgur_url);
      label.innerHTML =imgur_url;
    //get imgur uploaded yrl
      document.getElementById("o_url").value = imgur_url;

      
    });

	

	maxImageSize = $("#qualitySelect").val();

    if (files && file) {
	    ImageTools.resize(file, {width: maxImageSize, height: maxImageSize}, 
	    	function(blob, didItResize) {
				var reader = new FileReader();
				reader.onload = function(readerEvt) {
	            	imageBinary = btoa(readerEvt.target.result);
	        	};
        		reader.readAsBinaryString(blob);
	    	}
	    );
	}
}
```

在html裡呼叫Jacascript腳本裡的uploadImage(styleId)，其中styleID是由list.txt裡的所有filter照片選出來的。
```php 
    var e =document.getElementById("emo").value;
    if(e==="Sadness"){
      console.log("Sadness Mode");
      uploadImage("ed5fc005-1b90-11e7-afe2-06d95fe194ed");
    }
    else if(e==="Joy"){
      console.log("Joy Mode");
      uploadImage("ed90e183-1b90-11e7-afe2-06d95fe194ed");
    }
    else if(e==="Fear"){
      console.log("Fear Mode");
      uploadImage("edc63c6e-1b90-11e7-afe2-06d95fe194ed");
    }
    else if(e==="Anger"){
      console.log("Anger Mode");
      uploadImage("87b82671-1b84-11e7-afe2-06d95fe194ed");
    }
    else if(e==="Disgust"){
      console.log("Disgust Mode");
      uploadImage("dd87e344-e283-4c2f-8708-19c5f820eac3");
    }
```

### Imgur API
用上傳的照片l：
```javascript
    var files = event.target.files;
    var file = files[0];

    // Replace ctrlq with your own API key
    var apiUrl = 'https://api.imgur.com/3/image';
    var apiKey = 'fc37366558b0be2';

    var settings = {
        async: false,
        crossDomain: true,
        processData: false,
        contentType: false,
        type: 'POST',
        url: apiUrl,
        headers: {
          Authorization: 'Client-ID ' + apiKey,
          Accept: 'application/json'
        },
        mimeType: 'multipart/form-data'
    };

    var formData = new FormData();
    formData.append("image", files[0]);
    settings.data = formData;

    console.log(formData);

    // Response contains stringified JSON
    // Image URL available at response.data.link
    $.ajax(settings).done(function(response) {
      console.log(response);
      var r = JSON.parse(response);
      console.log(r);
      imgur_url = r.data.link;
      console.log(imgur_url);
      label.innerHTML =imgur_url;
      document.getElementById("o_url").value = imgur_url;

      
    });
```
用其他圖片的網址：
```javascript
/* Request the image and Put image to Imgur*/
console.log("Uploading file to Imgur..");

// Replace ctrlq with your own API key
var apiUrl = 'https://api.imgur.com/3/image';
var apiKey = 'APIKEY';


$.ajax({ 
	url: 'https://api.imgur.com/3/image',
	async: false,
	headers: {
	    Authorization: 'Client-ID ' + apiKey,
	},
	type: 'POST',
	data: {
		'image': result.data.url
	},
	success: function(response) { 
		new_imgur_url = response.data.link;
		document.getElementById("n_url").value = new_imgur_url; 
		console.log(new_imgur_url);
	}
});
```

## 結語
(過程+瓶頸+未來發展)

## Bug
1. Diary內文輸入不能包含單引號(')、雙引號(")以及換行，否則點按上傳完的照片將沒有彈出視窗
