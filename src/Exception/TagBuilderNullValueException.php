<?php
declare(strict_types = 1);

namespace Norsys\SeoBundle\Exception;

/**
 * Class TagBuilderNullValueException
 */
class TagBuilderNullValueException extends \Exception
{
    /**
     * TagBuilderNullValueException constructor.
     *
     * @param string          $name
     * @param integer         $code
     * @param \Exception|null $previous
     */
    public function __construct(string $name, int $code = 0, \Exception $previous = null)
    {
        $message = sprintf('Value for "%s" is null.', $name);
        parent::__construct($message, $code, $previous);
    }
}
