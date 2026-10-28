<?php
namespace DigitalSignage\Mcp\Slides;

class SlideRejected extends \RuntimeException
{
    public function __construct(string $message, public readonly string $instructions = '')
    {
        parent::__construct($message);
    }
}
