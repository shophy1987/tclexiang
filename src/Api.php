<?php

namespace shophy\tclexiang;

abstract class Api
{
    abstract protected function getCache($key);

    abstract protected function setCache($key, $value, $expire);
}