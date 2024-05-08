<?php

namespace henrik\sl\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class AsSingleton
{
    public string $id;
}