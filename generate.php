<?php
/*
 * Playlist generator
 * Author: François LASSERRE - http://www.choiz.fr
 * Licence: GPL - http://www.gnu.org/copyleft/gpl.html
 */

$website_url = 'http://www.addictradio.net/';
$stream_title = 'Addict Radio Rock';

$stream_url = array(
    "stream1.addictradio.net/addictrock.mp3",
    "stream2.addictradio.net/addictrock.mp3",
    "stream3.addictradio.net/addictrock.mp3",
    "stream4.addictradio.net/addictrock.mp3",
    "stream5.addictradio.net/addictrock.mp3",
    "stream6.addictradio.net/addictrock.mp3"
);

$filename = "addictrock";
$format = array('asx','m3u','pls','qtl','wax');

//default format m3u
$ext = $format[1];

if(isset($_GET['ext']) && $_GET['ext'] && in_array(strtolower($_GET['ext']),$format)) {
    $ext = strtolower($_GET['ext']);
}

switch($ext) {
    case 'asx':
        header("Content-Type: audio/x-ms-asf");
        header("Content-Disposition: attachment; filename=$filename.$ext");
        echo "<ASX Version=\"3.0\">\n<PARAM name=\"HTMLView\" value=\"$website_url\" />\n";
        foreach($stream_url as $url) {
            echo "<ENTRY>\n<REF HREF=\"http://$url\" />\n</ENTRY>\n";
        }
        echo "<Title>$stream_title</Title>\n</ASX>";
        break;
    case 'm3u':
        header("Content-Type: audio/x-mpegurl");
        header("Content-Disposition: attachment; filename=$filename.$ext");
        echo "#EXTM3U";
        foreach($stream_url as $url) {
            echo "\n#EXTINF:-1, $stream_title\n$url";
        }
        break;
    case 'pls':
        header("Content-Type: audio/x-scpls");
        header("Content-Disposition: attachment; filename=$filename.$ext");
        echo "[playlist]\nNumberOfEntries=".count($stream_url)."\n";
        $i=0;
        foreach($stream_url as $url) {
            $i++;
            echo "\nFile$i=http://$url\nTitle$i=$stream_title\nLength$i=-1\n";
        }
        echo "\nVersion=2";
        break;
    case 'qtl':
        header("Content-Type: application/x-quicktimeplayer");
        header("Content-Disposition: attachment; filename=$filename.$ext");
        echo "<?xml version=\"1.0\"?>\n<?quicktime type=\"application/x-quicktime-media-link\"?>";
        foreach($stream_url as $url) {
            echo "\n<embed src=\"icy://$url\" autoplay=\"true\" />";
        }
        break;
    case 'wax':
        header("Content-Type: audio/x-ms-wax");
        header("Content-Disposition: attachment; filename=$filename.$ext");
        foreach($stream_url as $url) {
            echo "$url\n";
        }
        break;
    default:
        break;
}
?>
