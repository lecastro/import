<?php

declare(strict_types=1);

namespace app\Exceptions;

use Exception;

abstract class CustomRuleExceptions extends Exception
{
    protected $description;

    protected $message;

    protected $help;

    protected $httpCode;

    protected $params;

    abstract public function getShortMessage();

    abstract public function getDescription();

    public function render()
    {
        return response(
            ['error' => $this->getError()],
            $this->getHttpStatus()
        );
    }

    public function getError(): array
    {
        return [
            'shortMessage' => $this->getShortMessage(),
            'message'      => $this->getDescription(),
            'help'         => $this->getHelp(),
        ];
    }

    public function getHelp()
    {
        return $this->help ?? '';
    }

    public function setParams($params)
    {
        $this->params = $params;
    }

    abstract public function getHttpStatus();
}
