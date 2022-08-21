<?php

namespace ZloyNick\StrictPhp\types\exemplar\number;

use ZloyNick\StrictPhp\types\exemplar\TExemplar;

abstract class TNumber extends TExemplar
{

    public function __toString():string{
        return get_called_class();
    }

    public function __toInt():int{
        return (int)$this->value;
    }

    public function __toFloat():int{
        return (float)$this->value;
    }

}