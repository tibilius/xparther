<?php

namespace App\Model;


class ParseRequest
{
    /**
     * @var string
     */
    private $url = '';
    /**
     * @var string
     */
    private $script;
    /**
     * @var array
     */
    private $clicks = [];
    /**
     * @var array|ParseRequestItem[]
     */
    private $items = [];
    /**
     * @var boolean
     */
    private $debug = false;

    /**
     * @return string
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return ParseRequest
     */
    public function setUrl(string $url): ParseRequest
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getScript(): ?string
    {
        return $this->script;
    }

    /**
     * @param string $script
     * @return ParseRequest
     */
    public function setScript(string $script): ParseRequest
    {
        $this->script = $script;

        return $this;
    }


    /**
     * @return array
     */
    public function getClicks(): ?array
    {
        return $this->clicks;
    }

    /**
     * @param array $clicks
     * @return ParseRequest
     */
    public function setClicks(array $clicks): ParseRequest
    {
        $this->clicks = $clicks;

        return $this;
    }

    /**
     * @return array|ParseRequestItem[]
     */
    public function getItems(): ?array
    {
        return $this->items;
    }

    /**
     * @param array $items
     * @return ParseRequest
     */
    public function setItems(array $items): ParseRequest
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDebug(): bool
    {
        return $this->debug;
    }

    /**
     * @param bool $debug
     * @return ParseRequest
     */
    public function setDebug(bool $debug): ParseRequest
    {
        $this->debug = $debug;

        return $this;
    }




}