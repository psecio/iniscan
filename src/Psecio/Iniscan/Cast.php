<?php

namespace Psecio\Iniscan;

class Cast
{
    /**
     * Cast the values from php.ini to a standard format
     *
     * @param mixed $value php.ini setting value
     * @return mixed "Casted" result
     */
    public function castValue($value)
    {
        if ($value === 'Off' || $value === '' || $value === 0 || $value === '0' || $value === false) {
            $casted = 0;
        } elseif ($value === 'On' || $value === '1' || $value === 1) {
            $casted = 1;
        } else {
            $casted = $value;
        }

        $casted = $this->castPowers($casted);

        return $casted;
    }

    /**
     * Cast the byte values ending with G, M or K to full integer values
     *
     * @param $casted
     * @internal param $value
     * @return mixed "Casted" result
     */
    public function castPowers ($casted) {
        $postfixes = array(
            'K' => 1024,
            'M' => 1024 * 1024,
            'G' => 1024 * 1024 * 1024,
        );
        $matches = array();
        if (preg_match('/^([0-9]+)([' . implode('', array_keys($postfixes)) . '])$/', $casted, $matches)) {
            $casted = $matches[1] * $postfixes[$matches[2]];
        }
        return $casted;
    }
}
