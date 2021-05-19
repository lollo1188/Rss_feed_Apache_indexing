<!--Assuming your web server is 
- 1) using apache 2.4 with default indexing for files and sub-directories (I've tested it on Raspberry Pi 3 with Raspbian on it), 
- 2) NOT using a self-signed SSL/TLS certificate for your web server
you can create a file for rss feed in XML (e.g. rss.xml) thanks to this very basic php file.
It will contain as many files and subdirectories as you want, which are contained in your web directory.
-->

<?php
header('Content-type: text/xml');
$feedName = "Feed RSS Name"; //put here your content name
$feedDesc = "Feed Description"; //put here a short description
$feedURL = "https://www.example.com"; //here the hostname OR path of the files on your web server
$feedBaseURL = "https://www.example.com/"; // must end in trailing forward slash (/).
?>


<<?= '?'; ?>xml version="1.0" <?= '?'; ?>>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
        <channel>
                <title><?=$feedName?></title>
                <link><?=$feedURL?></link>
                <description><?=$feedDesc?></description>
                <atom:link href="https://www.example.com/" rel="self" type="application/rss+xml" />


<?php
//$allowed_ext = ".mp4,.MP4,.mp3,.MP3";

function find_all_files($dir){

    $root = scandir($dir);
    $result = array();
    global $files;

    foreach($root as $value){
        //echo $dir/$value;
        if($value === '.' || $value === '..' || $value === "rss.php" || $value === "rss.xml"){ //put in here as many files as you want to exclude from rss feed indexing
                //echo "$dir/$value";
                continue;
        }
        if(is_file("$dir/$value")) {
                $result[]="$dir/$value";
                //$ext = strtoupper(pathinfo($sub.$value, PATHINFO_EXTENSION));
                continue;
        }

        foreach(find_all_files("$dir/$value") as $value){
                //$value = str_replace('&', '&amp;', $value);
                $prova = basename(dirname("$value"),"/");
                //echo $prova;
                $prova2 = rawurlencode($prova)."/".basename($value);
                $item['name'] = $prova2;

                $prova3 = "$prova/".basename($value);
                $item['timestamp'] = filectime($prova3);
                $item['size'] = filesize($prova3);

                $files[] = str_replace('', '', $item);
                $result[]="$prova/$value";
                
    }
    return $result;
}


$files = array();
$sub = "";

$dir= getcwd(); //put here one directory (i.e. directory with apache indexed files and subdirectories you are interested in)
$prova2 = find_all_files($dir);

//print_r($prova2); //Print both files and sub-directories

$result = "";

foreach($files as $item) {
        if($item['name'] != "index.php") {
          if (!empty($item['name'])) {

                $result .= "<item>\n";
                //echo $result;

                $result .= "<title>".$item['name']."</title>\n";
                //echo $result;

                $ret = '"';
                $result .= "<link href=". $ret . $feedBaseURL . $item['name'] .$ret. "/>\n";
                //echo $result;

                $result .= "<guid>". $feedBaseURL . $item['name'] . "</guid>\n";
                //echo $result;

                $result .= "<pubDate>". date('r', $item['timestamp']) ."</pubDate>\n";
                //echo $result;

                $result .= "<description><![CDATA[And here's the description of the entry.]]></description>\n";
                //echo $result;

                $result .= "</item>\n";
                //echo $result;
          }
        }
}

//echo $result;

$xmlstr1 = <<<XML
<?xml version='1.0'?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">


<channel>
                <title>Feed name</title>
                <link>https://www.example.com</link>
                <description>Feed description</description>
                <atom:link href="https://www.example.com/rss.xml" rel="self" type="application/rss+xml" />

XML;

$xmlstr2 = <<<XML
</channel>
</rss>
XML;

$xml = fopen("rss.xml",'w'); //name the file as whatever you want. Here, it will be created in the current directory

fwrite($xml, $xmlstr1);
fwrite($xml, $result);

fwrite($xml, $xmlstr2);
fclose($xml);

?>
        </channel>
</rss>
