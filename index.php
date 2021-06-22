<?php

// this script parses chat records from zoom
// Zoom will generally save such logs to Documents\Zoom\MeetingID\meeting_saved_chat.txt 
// Just add the name a textfile below and place it in the same folder. 
// you will then get  a breakdown by name of comments
// TODO: add an upload interface for multiple text files. 

$textfile = "meeting_saved_chat.txt";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$h = fopen($textfile, "r");
$data=array();
$lines = [];
$i = 0;
$firstTime = true; 
$currentName = 'Unknown';
if ($h){
    while(($line = fgets($h)) !== false ){
        if (preg_match('/^([01][0-9]|2[0-3]):([0-5][0-9])/', $line)  ){
            $data[]='';
        }
        $data[count($data)-1] = $data[count($data)-1].$line;
 
    } 
    
    
    
    foreach ($data as $text){
        $pieces = explode(' From ',$text);
        $pieces2 = explode(':', $pieces[1]);
        
        $name = explode(' to ',$pieces2[0]);
        $name = explode(' To ',$name[0]);
        $name = trim($name[0]);

        unset($pieces2[0]);
        $line = implode($pieces2);

        
        $lines[$name][] = trim($line);
        
        
    }
    foreach ($lines as $name => $comments){
        $chars = 0;
        $wordcount = 0;
        $comments = array_unique($comments); //dedupe
        foreach($comments as $comment){
            $chars+= strlen($comment);
            $wordcount+= str_word_count($comment);
        }
        echo '<h1>'.$name.' '.$chars.'</h1>';
        echo '<p>Comment Count: '.count($comments).'</p>';
        echo '<p>Word Count: '.$wordcount.'</p>';
        echo '<p>Character Count: '.$chars.'</p>';
        echo '<pre>';
        //var_dump($comments);
        echo '</pre>';
    }

    fclose($h); 
}
else{

} ?>
