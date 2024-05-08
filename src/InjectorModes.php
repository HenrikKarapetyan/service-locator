<?php

namespace henrik\sl;

enum InjectorModes: string
{
    case AUTO_REGISTER = 'AUTO_REGISTER';

    case CONFIG_FILE = 'CONFIG_FILE';
}