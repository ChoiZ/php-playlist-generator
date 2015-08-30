<?php
/*
 * Playlist generator
 *
 * @version 2015-08-30
 * @author François LASSERRE <choiz@me.com>
 * @license GNU GPL {@link http://www.gnu.org/licenses/gpl.html}
 */

class Station
{
    private $name = '';
    private $description = '';
    private $url = '';
    private $servers = [];

    public function __construct($name, $description, $url)
    {
        $this->name = $name;
        $this->description = $description;
        $this->url = $url;
    }

    public function addServer($server)
    {
        if (is_string($server)) {
            $this->servers[] = $server;
        }

        if (is_array($server)) {
            $this->servers = array_merge($this->servers, $server);
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getServers()
    {
        return $this->servers;
    }
}
