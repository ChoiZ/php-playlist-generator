<?php
/*
 * Playlist generator
 *
 * @version 2012-07-06T21:50:20Z (ISO-8601)
 * @author François LASSERRE <choiz@me.com>·
 * @license GNU GPL {@link http://www.gnu.org/licenses/gpl.html}
 */

$available_format = array('asx','m3u','pls','qtl','wax');

$radio = new stdClass;
$radio->website_url = "http://www.addictradio.net/";
$radio->stream_title = "Addict Radio Rock";
$radio->station = "addictrock";
$radio->stream_url = array(
    "stream1.addictradio.net/".$radio->station.".mp3",
    "stream2.addictradio.net/".$radio->station.".mp3",
    "stream3.addictradio.net/".$radio->station.".mp3",
    "stream4.addictradio.net/".$radio->station.".mp3",
    "stream5.addictradio.net/".$radio->station.".mp3",
    "stream6.addictradio.net/".$radio->station.".mp3",
);

$file = new stdClass;
$file->name = $radio->station;
$file->ext = $available_format[1];

if(isset($_GET['ext']) && $_GET['ext'] && in_array(strtolower($_GET['ext']),$format)) {
    $file->ext = strtolower($_GET['ext']);
}

switch($file->ext) {
    case 'asx':
        header("Content-Type: audio/x-ms-asf");
        header("Content-Disposition: attachment; filename=$file->name.$file->ext");
        echo "<ASX Version=\"3.0\">\n<PARAM name=\"HTMLView\" value=\"$radio->website_url\" />\n";
        foreach($radio->stream_url as $url) {
            echo "<ENTRY>\n<REF HREF=\"http://$url\" />\n</ENTRY>\n";
        }
        echo "<Title>$radio->stream_title</Title>\n</ASX>";
        break;
    case 'm3u':
        header("Content-Type: audio/x-mpegurl");
        header("Content-Disposition: attachment; filename=$file->name.$file->ext");
        echo "#EXTM3U";
        foreach($radio->stream_url as $url) {
            echo "\n#EXTINF:-1, $radio->stream_title\n$url";
        }
        break;
    case 'pls':
        header("Content-Type: audio/x-scpls");
        header("Content-Disposition: attachment; filename=$file->name.$file->ext");
        echo "[playlist]\nNumberOfEntries=".count($radio->stream_url)."\n";
        $i=0;
        foreach($radio->stream_url as $url) {
            $i++;
            echo "\nFile$i=http://$url\nTitle$i=$radio->stream_title\nLength$i=-1\n";
        }
        echo "\nVersion=2";
        break;
    case 'qtl':
        header("Content-Type: application/x-quicktimeplayer");
        header("Content-Disposition: attachment; filename=$file->name.$file->ext");
        echo "<?xml version=\"1.0\"?>\n<?quicktime type=\"application/x-quicktime-media-link\"?>";
        foreach($radio->stream_url as $url) {
            echo "\n<embed src=\"icy://$url\" autoplay=\"true\" />";
        }
        break;
    case 'wax':
        header("Content-Type: audio/x-ms-wax");
        header("Content-Disposition: attachment; filename=$file->name.$file->ext");
        foreach($radio->stream_url as $url) {
            echo "$url\n";
        }
        break;
    default:
        break;
}
?>
