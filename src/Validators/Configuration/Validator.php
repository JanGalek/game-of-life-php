<?php

declare(strict_types = 1);

namespace GameOfLife\Validators\Configuration;

interface Validator
{

    public function validate(string $content): void;

}
