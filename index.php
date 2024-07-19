<head>
<!-- 
<meta http-equiv="refresh" content="300">
-->
  <title>Out Front Camera</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
<!--
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.js"></script>
-->
</head>
<body>
<script>
var counter=0;
var when=0;

function add_buttons(when) {
 $("#controlsf").text("forward");
 $("#controlsf").data("when",when);
 
 $("#controlsb").html("back");
 $("#controlsb").data("when",when);
}


function do_ajax(when,direction) {
    $(".clear").html("#pictures");
 //	$("#controlsf").text("forward: " + counter);
 	$("#controlsb").text("back: " + counter);
    if ( direction == "forward" ) {
        counter +=12
    } else {
        counter -=12
    }
    console.log( "when"+when+"count"+counter)
    $.ajax({url: "pager.php?when=" + when +"&count="+counter, success: function(result){
      $("#pictures").html(result);
    }});
}
</script>
<br>

<style>
body {
  background-color: linen;
}
h1 {
  color: maroon;
  margin-left: 40px;
} 
img {
    border-width: 10px;
    border-color: Black;
    border: 5px solid #555;
    margin-bottom: 5px;

}
</style>

<?php

$url="//happytown.local";

$today= date("Ymd");
$counter=0;
//phpinfo(INFO_VARIABLES);
// using proxy server thru fire wall 
// might have different paths
if ($_SERVER["REMOTE_ADDR"] == "192.168.1.11" ) {
	$url ="//robroy.online/happytown/";
}

echo "<a href='$url?when=$today'> Today $today </a><br>";
echo "  &emsp;  <a href='$url'> HOME </a>";
echo "  &emsp;  <a href='$url:8081' target='_blank'> Live </a>";
echo "  &emsp;  <a href='$url:8081/0/motion' target='_blank'> Just Motion </a>";
echo "<br>";

#echo "<br>DEBUG<br>";
$entry=0;

if (empty($_GET["when"])){
	$home = "true";
?>
<div style="float:right">
  <iframe src="http://happytown.local:8081" width=960px height=740px ></iframe>
</div>
<br>
<?php
} else {
	$today=$_GET["when"];
	unset($_GET["when"]);
}
#echo "<br>debug<br>";
#phpinfo(INFO_VARIABLES);

if (isset( $_GET["event"]) ) {
	$pic=$_GET["event"];
	display_pic($pic);
	unset($_GET["event"]);
} else {
display_dirs("motion");
?>
<br> <button id="controlsb"> <h2></button> <button id="controlsf"> <h2></button> 
<br>
<script>
$("#controlsf").on("click", function(){
    when = $("#controlsf").data("when");
    do_ajax(when,"forward");
});

$("#controlsb").on("click", function(){
    when = $("#controlsb").data("when");
    do_ajax(when,"back");
});
</script>
<div id="pictures"><h2>pictures</h2></div> 
<?php
}

function display_pic($event) {
	global $today;
	global $home;
	$dir="motion/$today/";
	echo "display pic";
	$key="$event-*";
	 foreach(glob($dir.$key) as $filename){

		echo "<img  src=\"$filename\" style='display: block; margin-left: auto; margin-right: auto; width: 60%;' />";
	}

}

function display_dirs($it) {
	global $url,$entry;
	/* only dirs */
	$directories = glob($it . '/*' , GLOB_ONLYDIR);
	echo "<ul>";
	foreach ( array_reverse($directories) as $e ) {
		$entry=basename($e);
                $count = count(glob("$e/*"));
		if ($count == 0 )  {
			rmdir ($e);
			continue;
		}
		echo "<button  class='rob' id=button-".$entry. " >$entry<br>images:$count </button>";
?>

<script>
$(document).ready(function(){
  $("#button-<?php echo $entry ?>").click(function(){
    $(".rob").css("background-color", "white");
    $("#button-<?php echo $entry?>").css("background-color", "red");

    add_buttons("<?php echo $entry?>");
    counter=-12;
    do_ajax("<?php echo $entry?>","forward")
  });
});

</script>
<?php
	}
	echo "</ul>";
}
?>

</body>
</html>
