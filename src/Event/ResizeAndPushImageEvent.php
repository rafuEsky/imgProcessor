<?php
namespace App\Event;

class ResizeAndPushImageEvent
{
    private string $sourceFileName;
    private string $transportName;

    public function __construct(string $sourceFileName, string $transportName)
    {
        $this->sourceFileName = $sourceFileName;
        $this->transportName = $transportName;
    }

    /**
     * @return string
     */
    public function getSourceFileName(): string
    {
        return $this->sourceFileName;
    }

    /**
     * @return string
     */
    public function getTransportName(): string
    {
        return $this->transportName;
    }
}

