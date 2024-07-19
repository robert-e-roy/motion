<?php

$url="//happytown.local";

if (!empty($_GET["when"])){
        $when=$_GET["when"];
        unset($_GET["when"]);
}

if ( isset($_GET["count"]) ) {
	$count = $_GET["count"];
}

if ( $dir ==  "motion/timelapse" ) {
	display_movies();
}	
	else {
	display_captures($when,$count);
}

function display_movies() {

	echo "hi movie";
        $dir="motion/timelapse/";
        global $url;
        $stack=array();
        $start=$count;
        $files = glob($dir.'*.mpg');
	natsort($files);
        foreach($files  as $filename){
             echo "<a href='$filename' target=_blank>";
	}
}

function display_captures($which_day,$count) {

        $dir="motion/$which_day/";
        global $url;
        $stack=array();
        $start=$count;
	$end=$count+12;
	$counter = 0;
	$last = "";
	$item=0;
         //echo "rob $count $dir last: $last";

        $files = glob($dir.'*.jpg');
	//print_r($files);
	natsort($files);
        foreach($files  as $filename){
		if ( $which_day == "snapshots") {
                     if ($counter++ < $start ) 
                                continue;
                     if ($counter > $end ) 
                                return;
                    echo "<a href='$filename' target=_blank>";
                    echo "<img width=\"256px\" height=\"192px\" src=\"$filename \"/>";
                    echo "</a>";

		 } else {
		
// not snapshot
                preg_match("/(\d+)-(\d+)-(\d+).+/",$filename,$item);
	        //print ("in loop, $counter, $start,$end");
		//print_r($item);
		//print("<br> fname $filename");
                $len=array_push($stack,$filename);
                if ($item[1] == $last) {
                        }
                else {
                    $last=$item[1];
		    //print "<br>last $last,start $start> counter  $counter  < $end <br>";
                     if ($counter++ < $start )  {
                                continue;
			}
                     if ($counter > $end ) 
                                return;
                    echo "<div style=\"display:inline-table; font-size:10px; font-family:'Tahoma'; margin:5px;\">";
                    echo "<a href='$url?when=$which_day&event=$item[1]'>";
                    echo "<img width=\"256px\" height=\"192px\" src=\"$filename \"/>";
                    echo "</a>";
                    echo "</div>";
                    }
		}
        }
        echo "<br>";
}
?>
