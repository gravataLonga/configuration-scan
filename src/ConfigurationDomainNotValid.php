<?php

namespace Gravatalonga\ConfigurationScan;

use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;

class ConfigurationDomainNotValid extends \Exception
{
    public function __construct(string $domain, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(sprintf('%s has configuration mal formed', $domain), $code, $previous);
    }
}