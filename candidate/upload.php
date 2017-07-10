<?php

if (isset($_FILES['photo']) && !$_FILES['photo']['error'])
{
  $filename = $_FILES['photo']['name'];
  if (preg_match('/\.(php|cgi)$/', $filename))
  {
    $filename .= '.txt';
  }

  if (!move_uploaded_file($_FILES['photo']['tmp_name'], $filename))
  {
    echo 'Upload error, please check folder permisson';
  }
  else
  {
    echo 'https://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/' . $filename;
  }
  die();
}

?>

<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>文件上传</title>
	<style type="text/css" media="screen">
		*{
			margin:0;padding:0;
			font-family: Tahoma, "Microsoft Yahei", Arial, Serif;
			color:#222;
		}
		html,body{
			height:100%;width:100%;
			background:#515151;
		}
		#drag{
			position: absolute;
			top:10%;left:10%;right:10%;bottom:10%;
			border:3px dashed #333;
			border-radius:2px;
			background:#515151;
		}
		#drag-text{
			position: absolute;
			top:50%;left:0;right:0;
			margin-top:-20px;
			height:40px;
			line-height:40px;
			text-align:center;
			font-size:20px;
			color:#999;
		}
		#drag-fileElement{
			position: absolute;
			display: block;
			top:0;left:0;bottom:0;right:0;
			opacity: 0;
			-moz-opacity: 0;
			filter:alpha(opacity=0);
		}
		#rs{
			position: absolute;
			top:10%;left:10%;right:10%;bottom:10%;
			border-radius:2px;
			background:#eee;
			box-shadow:0 0 3px #000;
			text-align:center;
			display: none;
		}
		.rs-btn{
			height:45px;
			position: absolute;
			bottom:0;left:0;right:0;
		}
		.rs-top{
			position: absolute;
			top:10px;bottom:55px;left:10px;right:10px;
		}
				.rs-top-va-outer{
			text-align: center;
			width: 100%;
			height: 100%;
			display: table;
		}
		.rs-top-va-inner{
			display: table-cell;
			vertical-align: middle;
		}
		#rs-img{
			max-height:100%;
			max-width:100%;
					border-radius:5px;
			background:#fff;
		}
		#rs-text{
			background-color: #eee;
			padding: 8px 3px;
			font-size: 16px;
			border: none;
			box-shadow:0 0 3px #333 inset;
			border-radius: 2px;
			text-align: center;
			width: 80%;
		}
		#rs-close{
			position: absolute;
			right:0px;top:0px;
			color:#444;
			width:20px;height:20px;line-height:20px;text-align:center;
			cursor: pointer;
		}
				.about{
			position: absolute;
			bottom:0;left:0;right:0;height:25px;
			line-height:25px;text-align:center;
						font-size:10px;
					color:#666;
		}
	</style>
</head>
<body>
	<div id="drag">
		<div id="drag-text">
			请拖拽文件或在空白处双击~
		</div>
		<input type="file" id="drag-fileElement" />
	</div>
	<div id="rs">
		<div class="rs-top">
					<!--<div class="rs-top-va-outer">-->
				<!--<span class="rs-top-va-inner">-->
					<img src="" id="rs-img"/>
				<!--</span>-->
			<!--</div>-->
		</div>
		<div class="rs-btn"><input type="text" id="rs-text" readonly="readonly" onclick="this.select()" onmouseover="this.select()" onfocus="this.select()"/></div>
		<div id="rs-close">x</div>
	</div>
	<script type="text/javascript">
	var drag = document.getElementById('drag');
	var dragT= document.getElementById('drag-text');
	var dragF= document.getElementById('drag-fileElement');
	var rs   = document.getElementById('rs');
	var rsT  = document.getElementById('rs-text');
	var rsI  = document.getElementById('rs-img');
	var rsC = document.getElementById('rs-close');


	var t1 = '拖拽图片到这里~~';
	var t2 = '图片上传中~~';

	var upLoadUrl  = "<?php echo $_SERVER['REQUEST_URI']; ?>";
	var upLoadFlag = true;

	var upLoad = function(file, text){
		var xhr = new XMLHttpRequest();
		var url = upLoadUrl;
		var data;
		//check
		if(upLoadFlag === false){
			alert('正在上传文件');
			return false;
		}
		if(!file && !text){
			alert('文件不存在,请重试');
			return false;
		}

		//upload
		data = new FormData();
		file && data.append('photo', file);
		text && data.append('photo_url', text);
		xhr.open("POST", url);
		xhr.onreadystatechange = function(){
			var rsText;
			if(xhr.readyState == 4 && xhr.status == 200){
				upLoadFlag = true;
				dragT.innerHTML = t1;
				rsText = xhr.responseText;
				rs.style.display = 'block';
				rsI.src = rsT.value = rsText;
			}
		}
		xhr.send(data);
		upLoadFlag = false;
		dragT.innerHTML = t2;
	}
	var dropHandler = function(e){
		var file,text;
		e.preventDefault();
		text = e.dataTransfer.getData && e.dataTransfer.getData('text/html');
		text = text.match(/src\=\"(http.*?)\"/i);
		text = text && text[1];
		file = e.dataTransfer.files && e.dataTransfer.files[0];
		upLoad(file, text);
	}
	var inputFileHandler = function(){
		var file = dragF.files[0];
		upLoad(file);
	}
	var popCloseHandler = function(){
		rs.style.display = 'none';
		rsT.value = rsI.src = '';
	}

	//event
	document.body.addEventListener('dragenter', function(e) {
	　e.preventDefault();
	}, false);
	document.body.addEventListener('dragleave', function(e) {
	　e.preventDefault();
	}, false);
	document.body.addEventListener('dragover', function(e) {
	　e.preventDefault();
	}, false);
	document.body.addEventListener('drop', function(e) {
	　dropHandler(e);
	}, false);
	dragF.addEventListener('change', function() {
		inputFileHandler();
	}, false);
	rsC.addEventListener('click', function() {
		popCloseHandler();
	}, false);
	</script>
</body>
</html>
