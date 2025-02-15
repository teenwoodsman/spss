<?php

namespace SPSS\Sav\Record\Info;

use SPSS\Buffer;
use SPSS\Sav\Record\Info;

class LongVariableNames extends Info
{
    const SUBTYPE   = 13;
    const DELIMITER = "\t";

    public $data = [];

    public function read(Buffer $buffer)
    {
        parent::read($buffer);
        $data = rtrim($buffer->readString($this->dataSize * $this->dataCount));

        foreach (explode(self::DELIMITER, $data) as $item) {
            list($key, $value) = explode('=', $item);
            $this->data[$key] = trim($value);
        }
    }

    public function write(Buffer $buffer)
    {
        $tuples = [];
        foreach ($this->data as $key => $value) {
            $tuples[] = sprintf('%s=%s', $key, $value);
        }
        $data = implode(self::DELIMITER, $tuples);

        $this->dataCount = \strlen($data);
        parent::write($buffer);
        $buffer->writeString($data);
    }
}
