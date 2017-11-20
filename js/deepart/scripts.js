var imageBinary;
var styles;
var resultCheck;
var submissionId;
var maxImageSize;
var imgur_url;

var deepArtEffectsClient = apigClientFactory.newClient({
	apiKey: 'vqbG65WVXd1AyNBuqwUiV3Fjj6vhxWtj1kkrZgTY',
	accessKey: 'AKIAJVAKJHW53ORDTMAA',
    secretKey: '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'
});

/*$(document).ready(function(){
	//$("#result").hide();
	//$("#progress-wrapper").hide();
	deepArtEffectsClient.stylesGet()
    .then(function(result){
    	console.log("Successfully loaded styles");
        //This is where you would put a success callback
        var ol = $('<ol id="selectable">');
        ol.appendTo('#style-list')
		styles = result.data;
        for (var i = 0, length = styles.length; i < length; i++) {	
  			var li = $("<li>");
  			li.attr('onClick',"uploadImage('"+styles[i].id+"')")
  			var div = $('<div class="style">');
			div.attr('style', "background-image: url("+styles[i].url+")");
			li.append(div);
			li.appendTo('#selectable');
			console.log(styles[i].id);
			console.log(styles[i].url);
		}
    }).catch(function(result){
        //This is where you would put an error callback
        console.log("Error loading styles");
    });
})*/
function uploadImage(styleId) {
	if(imageBinary==null) {
		alert('Please choose a picture first')
		return;
	}

	//$("#styles").hide();
	//$("#progress-wrapper").show();
	//maxImageSize = $("#qualitySelect").val();
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
			//var a = $('<a data-fancybox="gallery">');
			//a.attr('href', result.data.url);
			/*var img = $('<img class="result-image">');
			img.attr('src', result.data.url);
			a.append(img);*/

			//Show original picture
			//var img2 = document.createElement('img');
			//img2.src = 'data:image/jpeg;base64,' + imageBinary;
			//a.append(img2);

			//a.appendTo('#artwork');
			
			

			//clearInterval(resultCheck);
			//$("#result").show();
			//$("#styles").show();
			//$("#progress-wrapper").hide();
			console.log("Image uploaded done:"+result.data.url);

			
			/* Request the image and Put image to Imgur*/
			console.log("Uploading file to Imgur..");

		    // Replace ctrlq with your own API key
		    var apiUrl = 'https://api.imgur.com/3/image';
		    var apiKey = 'fc37366558b0be2';

		    /*var settings = {
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
		        data: {
		        	'image': result.data.url, 'type': 'base64'
		        }
		    };*/

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

		    // Response contains stringified JSON
		    // Image URL available at response.data.link
		    /*$.ajax(settings).done(function(response) {
		      console.log(response);
		      var r = JSON.parse(response);
		      console.log(r);
		      new_imgur_url = r.data.link;
		      console.log(new_imgur_url);
		      document.getElementById("n_url").value = new_imgur_url;

		      
		    });*/

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
