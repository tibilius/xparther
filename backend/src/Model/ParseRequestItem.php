<?php

namespace App\Model;

class ParseRequestItem
{
    /**
     * @var string
     */
    private $xpath;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $attribute;

    /**
     * @return string
     */
    public function getXpath(): ?string
    {
        return $this->xpath;
    }

    /**
     * @param string $xpath
     * @return ParseRequestItem
     */
    public function setXpath(string $xpath): ParseRequestItem
    {
        $this->xpath = $xpath;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return ParseRequestItem
     */
    public function setName(string $name): ParseRequestItem
    {
        $this->name = $name;

        return $this;
    }


    /**
     * @return string
     */
    public function getAttribute(): ?string
    {
        return $this->attribute;
    }

    /**
     * @param string $attribute
     * @return ParseRequestItem
     */
    public function setAttribute(string $attribute): ParseRequestItem
    {
        $this->attribute = $attribute;

        return $this;
    }

}