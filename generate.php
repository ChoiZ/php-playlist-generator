<?php
/*
 * Playlist generator
 *
 * @version 2014-07-23
 * @author François LASSERRE <choiz@me.com>
 * @license GNU GPL {@link http://www.gnu.org/licenses/gpl.html}
 */

$available_format = array('asx','m3u','pls','qtl','wax');

$radio = new stdClass;
$radio->website_url = 'http://www.addictradio.net/';
$radio->stream_title = 'Addict Radio Rock';
$radio->station = 'addictrock';
$radio->stream_url = array(
    'stream1.addictradio.net/'.$radio->station.'.mp3',
    'stream2.addictradio.net/'.$radio->station.'.mp3',
    'stream3.addictradio.net/'.$radio->station.'.mp3',
    'stream4.addictradio.net/'.$radio->station.'.mp3',
    'stream5.addictradio.net/'.$radio->station.'.mp3',
    'stream6.addictradio.net/'.$radio->station.'.mp3',
);

$file = new stdClass;
$file->name = $radio->station;
$file->ext = $available_format[1];

if (isset($_GET['ext']) && $_GET['ext'] && in_array(strtolower($_GET['ext']),$format)) {
    $file->ext = strtolower($_GET['ext']);
}

if ($file->ext === 'asx') {
    header('Content-Type: audio/x-ms-asf');
    header('Content-Disposition: attachment; filename='.$file->name.$file->ext);
    echo '<ASX Version="3.0">'."\n".'<PARAM name="HTMLView" value="'.$radio->website_url.'" />'."\n";
    foreach($radio->stream_url as $url) {
        echo '<ENTRY>'."\n".'<REF HREF="http://'.$url.'" />'."\n".'</ENTRY>'."\n";
    }
    echo '<Title>'.$radio->stream_title.'</Title>'."\n".'</ASX>';
} else if ($file->ext === 'm3u') {
    header('Content-Type: audio/x-mpegurl');
    header('Content-Disposition: attachment; filename='.$file->name.$file->ext);
    echo '#EXTM3U';
    foreach($radio->stream_url as $url) {
        echo "\n".'#EXTINF:-1, '.$radio->stream_title."\n".$url;
    }
} else if ($file->ext === 'pls') {
    header('Content-Type: audio/x-scpls');
    header('Content-Disposition: attachment; filename='.$file->name.$file->ext);
    echo '[playlist]'."\n".'NumberOfEntries='.count($radio->stream_url)."\n";
    $i=0;
    foreach($radio->stream_url as $url) {
        $i++;
        echo "\n".'File'.$i.'=http://'.$url."\n".'Title'.$i.'='.$radio->stream_title."\n".'Length'.$i.'=-1'."\n";
    }
    echo "\n".'Version=2';
} else if ($file->ext === 'qtl') {
    header('Content-Type: application/x-quicktimeplayer');
    header('Content-Disposition: attachment; filename='.$file->name.$file->ext);
    echo '<?xml version="1.0"?>'."\n".'<?quicktime type="application/x-quicktime-media-link"?>';
    foreach($radio->stream_url as $url) {
        echo "\n".'<embed src="icy://'.$url.'" autoplay="true" />';
    }
} else if ($file->ext === 'wax') {
    header('Content-Type: audio/x-ms-wax');
    header('Content-Disposition: attachment; filename='.$file->name.$file->ext);
    foreach($radio->stream_url as $url) {
        echo $url."\n";
    }
}
