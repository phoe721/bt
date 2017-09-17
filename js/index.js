reload();

var time = 20000;
var check = setInterval(function(){ reload(); }, time);
	
$("#startRtorrent").click(function(){
	$.post("script/index.php",{start:"yes"},function(result){
		$("#output").html(result.status).show();
	}, "json");
});
	
$("#stopRtorrent").click(function(){
	$.post("script/index.php",{stop:"yes"},function(result){
		$("#output").html(result.status).show();
	}, "json");
});
	
$("#refresh").click(function(){
	location.reload();
});

$('#upload').click(function() {
	$f1 = $('#file');
	var formData = new FormData();
	if($f1.val()) formData.append('file', $f1.get(0).files[0]);
	
	$.ajax({
		url: 'script/index.php',
		data: formData,
		type: 'POST',
		contentType: false,
		processData: false,
		dataType: 'json',
		success: function(result) {
			if (result.error) {
				$('#output').html(result.error).show(); 
			} else {
				$('#output').html(result.message).show(); 
			}
		}
	});
});

$(document).on("click", ".startTorrent", function(){
	var hash = $(this).siblings(":hidden").val();
	$.post("script/index.php",{startTorrent:"yes",torrentHash:hash},function(result){
		$("#output").html(result.message).show();
	}, "json");
});

$(document).on("click", ".stopTorrent", function(){
	var hash = $(this).siblings(":hidden").val();
	$.post("script/index.php",{stopTorrent:"yes",torrentHash:hash},function(result){
		$("#output").html(result.message).show();
	}, "json");
});

$(document).on("click", ".deleteTorrent", function(){
	var hash = $(this).siblings(":hidden").val();
	$.post("script/index.php",{deleteTorrent:"yes",torrentHash:hash},function(result){
		$("#output").html(result.message).show();
	}, "json");
});

$(document).on("click", ".purgeTorrent", function(){
	var hash = $(this).siblings(":hidden").val();
	$.post("script/index.php",{purgeTorrent:"yes",torrentHash:hash},function(result){
		$("#output").html(result.message).show();
	}, "json");
});

function reload() {
	$.post('script/index.php', {reload:"yes"}, function(result) { 
		if (result.status) {
			$("#output").html(result.status).show();
			$("#field").empty().hide();
		} else if (result.display) {
			$("#output").html(result.status).hide();
			$("#field").empty().show();
			setDisplay(result.display);
		}
	}, "json");
}

function setDisplay(display) {
	for (i = 0; i < display.length; i++) {
		$("#field").append("<span class='name'>" + display[i].fileName + "</span><span class='control'><input type='hidden' class='torrentHash' name='torrentHash' value='" + display[i].torrentHash + "'><button type='button' class='startTorrent'>></button><button type='button' class='stopTorrent'>||</button><button type='button' class='deleteTorrent'>V</button><button type='button' class='purgeTorrent'>X</button></span><span class='info'>Up: " + display[i].uploadSize + "</span><span class='info'>Down: " + display[i].downloadSize + "</span><span class='info'>Size: " + display[i].size + "</span><span class='info'>Percent: " + display[i].percent + "</span><span class='info'>Ratio: " + display[i].ratio + "</span><span class='info'>Status: " + display[i].status + "</span>");
	}
}
