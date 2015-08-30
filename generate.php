<?php
/*
 * Playlist generator
 *
 * @version 2015-08-30
 * @author François LASSERRE <choiz@me.com>
 * @license GNU GPL {@link http://www.gnu.org/licenses/gpl.html}
 */

require 'station.php';
require 'playlist.php';

$station = new Station('lgr-rock', 'La Grosse Radio Rock', 'http://www.lagrosseradio.com/');
$station->addServer('http://server1.lagrosseradio.com:8500');
$station->addServer('http://server2.lagrosseradio.com:8500');
$station->addServer('http://server3.lagrosseradio.com:8500');

$playlist = new Playlist($station, 'm3u');
$playlist->generate();
