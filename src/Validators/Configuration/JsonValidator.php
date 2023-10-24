<?php

declare(strict_types = 1);

namespace GameOfLife\Validators\Configuration;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorBuilder;

class JsonValidator
{
    public function __construct()
    {
        $validator = new ValidatorBuilder();
    }
}
