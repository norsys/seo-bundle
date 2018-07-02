<?php
declare(strict_types = 1);

namespace Norsys\SeoBundle\Services\TagBuilder;

/**
 * Class Tag
 */
class Tag implements TagInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var boolean
     */
    private $selfClosing;

    /**
     * Tag constructor.
     *
     * @param string  $name
     * @param boolean $selfClosing
     */
    public function __construct(string $name, bool $selfClosing)
    {
        $this->name = $name;
        $this->selfClosing = $selfClosing;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @return boolean
     */
    public function isSelfClosing() : bool
    {
        return $this->selfClosing;
    }
}
