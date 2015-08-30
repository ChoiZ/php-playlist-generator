<?php
/*
 * Playlist generator
 *
 * @version 2015-08-30
 * @author François LASSERRE <choiz@me.com>
 * @license GNU GPL {@link http://www.gnu.org/licenses/gpl.html}
 */

$available_format = array('asx','m3u','pls','qtl','wax');

$station = new stdClass;
$station->website_url = 'http://www.addictradio.net/';
$station->name = 'Addict Radio Rock';
$station->channel = 'addictrock';
$station->stream_url = array(
    'stream1.addictradio.net/'.$station->channel.'.mp3',
    'stream2.addictradio.net/'.$station->channel.'.mp3',
    'stream3.addictradio.net/'.$station->channel.'.mp3',
    'stream4.addictradio.net/'.$station->channel.'.mp3',
    'stream5.addictradio.net/'.$station->channel.'.mp3',
    'stream6.addictradio.net/'.$station->channel.'.mp3',
);

$playlist = new stdClass;
$playlist->name = $station->channel;
$playlist->ext = $available_format[1];

if (!empty($_GET['ext']) && in_array(strtolower($_GET['ext']), $available_format)) {
    $playlist->ext = strtolower($_GET['ext']);
}

if ($playlist->ext === 'asx') {
    header('Content-Type: audio/x-ms-asf');
    header('Content-Disposition: attachment; filename='.$playlist->name.$playlist->ext);
    echo '<ASX Version="3.0">'."\n".'<PARAM name="HTMLView" value="'.$station->website_url.'" />'."\n";
    foreach($station->stream_url as $url) {
        echo '<ENTRY>'."\n".'<REF HREF="http://'.$url.'" />'."\n".'</ENTRY>'."\n";
    }
    echo '<Title>'.$station->name.'</Title>'."\n".'</ASX>';
} else if ($playlist->ext === 'm3u') {
    header('Content-Type: audio/x-mpegurl');
    header('Content-Disposition: attachment; filename='.$playlist->name.$playlist->ext);
    echo '#EXTM3U';
    foreach($station->stream_url as $url) {
        echo "\n".'#EXTINF:-1, '.$station->name."\n".$url;
    }
} else if ($playlist->ext === 'pls') {
    header('Content-Type: audio/x-scpls');
    header('Content-Disposition: attachment; filename='.$playlist->name.$playlist->ext);
    echo '[playlist]'."\n".'NumberOfEntries='.count($station->stream_url)."\n";
    $i=0;
    foreach($station->stream_url as $url) {
        $i++;
        echo "\n".'File'.$i.'=http://'.$url."\n".'Title'.$i.'='.$station->name."\n".'Length'.$i.'=-1'."\n";
    }
    echo "\n".'Version=2';
} else if ($playlist->ext === 'qtl') {
    header('Content-Type: application/x-quicktimeplayer');
    header('Content-Disposition: attachment; filename='.$playlist->name.$playlist->ext);
    echo '<?xml version="1.0"?>'."\n".'<?quicktime type="application/x-quicktime-media-link"?>';
    foreach($station->stream_url as $url) {
        echo "\n".'<embed src="icy://'.$url.'" autoplay="true" />';
    }
} else if ($playlist->ext === 'wax') {
    header('Content-Type: audio/x-ms-wax');
    header('Content-Disposition: attachment; filename='.$playlist->name.$playlist->ext);
    foreach($station->stream_url as $url) {
        echo $url."\n";
    }
}
