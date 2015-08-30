<?php
/*
 * Playlist generator
 *
 * @version 2015-08-30
 * @author François LASSERRE <choiz@me.com>
 * @license GNU GPL {@link http://www.gnu.org/licenses/gpl.html}
 */

class Playlist
{
    private $available_format = array(
        'asx' => 'audio/x-ms-asf',
        'm3u' => 'audio/x-mpegurl',
        'pls' => 'audio/x-scpls',
        'qtl' => 'application/x-quicktimeplayer',
        'wax' => 'audio/x-ms-wax'
    );

    private $station;
    private $extension;

    public function __construct(Station $station, $extension = 'm3u')
    {
        $this->station = $station;
        $this->extension = $extension;

        if (!empty($extension)) {
            $extension = strtolower($extension);
        }

        if (in_array($extension, $this->available_format)) {
            $this->extension = $extension;
        }
    }

    public function getStationName()
    {
        return $this->station->getName();
    }

    public function getStationDescription()
    {
        return $this->station->getDescription();
    }

    public function getStationUrl()
    {
        return $this->station->getUrl();
    }

    public function getStationServers()
    {
        return $this->station->getServers();
    }

    public function generate()
    {
        if (!headers_sent()) {
            header('Content-Type: '.$this->available_format[$this->extension]);
            header('Content-Disposition: attachment; filename='.$this->getStationName().$this->extension);
        }

        if ($this->extension === 'asx') {
            echo '<ASX Version="3.0">'."\n".'<PARAM name="HTMLView" value="'.$this->stationGetUrl().'" />'."\n";
            foreach($this->getStationServers() as $url) {
                echo '<ENTRY>'."\n".'<REF HREF="http://'.$url.'" />'."\n".'</ENTRY>'."\n";
            }
            echo '<Title>'.$this->getStationDescription().'</Title>'."\n".'</ASX>';
        } else if ($this->extension === 'm3u') {
            echo '#EXTM3U';
            foreach($this->getStationServers() as $url) {
                echo "\n".'#EXTINF:-1, '.$this->getStationDescription()."\n".$url;
            }
        } else if ($this->extension === 'pls') {
            echo '[playlist]'."\n".'NumberOfEntries='.count($this->stationGetUrl())."\n";
            $i=0;
            foreach($this->getStationServers() as $url) {
                $i++;
                echo "\n".'File'.$i.'=http://'.$url."\n".'Title'.$i.'='.$this->getStationDescription()."\n".'Length'.$i.'=-1'."\n";
            }
            echo "\n".'Version=2';
        } else if ($this->extension === 'qtl') {
            echo '<?xml version="1.0"?>'."\n".'<?quicktime type="application/x-quicktime-media-link"?>';
            foreach($this->getStationServers() as $url) {
                echo "\n".'<embed src="icy://'.$url.'" autoplay="true" />';
            }
        } else if ($this->extension === 'wax') {
            foreach($this->getStationServers() as $url) {
                echo $url."\n";
            }
        }
    }
}
