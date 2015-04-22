
<?php
include "top.php";
/* Sample code to open a plain text file */

$debug = true;

if(isset($_GET["debug"])){
    $debug = true;
}

$myFileName="people";


$fileExt=".csv";

$filename = $myFileName . $fileExt;


if ($debug) print "\n\n<p>filename is " . $filename;

$file=fopen($filename, "r");

/* the variable $file will be empty or false if the file does not open */
if($file){
    if($debug) print "<p>File Opened</p>\n";
}

?>
<?php
/* the variable $file will be empty or false if the file does not open */
if($file){
    
    if($debug) print "<p>Begin reading data into an array.</p>\n";

    /* This reads the first row, which in our case is the column headers */
    $headers=fgetcsv($file);
    
    if($debug) {
        print "<p>Finished reading headers.</p>\n";
        print "<p>My header array<p><pre> "; print_r($headers); print "</pre></p>";
    }
    /* the while (similar to a for loop) loop keeps executing until we reach 
     * the end of the file at which point it stops. the resulting variable 
     * $records is an array with all our data.
     */
    while(!feof($file)){
        $record[]=fgetcsv($file);
    }
    
    //closes the file
    fclose($file);
    
    if($debug) {
        print "<p>Finished reading data. File closed.</p>\n";
        print "<p>My data array<p><pre> "; print_r($records); print "</pre></p>";
    }
} 


/* display the data */

print "<article>\n";

foreach ($records as $oneRecord) {

  if ($oneRecord[0] != "") {  //the eof would be a "" 
    print "<figure>\n";
    print "<img class=\"thumbnail maxImage\" src = \"images/people/images/" . $oneRecord[0] . "\" alt = \" " . $oneRecord[0] . "\">\n";
    print "<figcaption></figcaption>\n";
    print"</figure>\n";
  }
}

print "</article>\n";

if ($debug){
    print "<p>End of processing.</p>\n";
}
?>                       
