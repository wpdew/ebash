<?php

$classMap = [
    'Ebash\Man\Man' => 'EbashMan',
];

foreach ($classMap as $class => $alias) {
    class_alias($class, $alias);
}

/**
 * This class needs to be defined explicitly as scripts must be recognized by
 * the autoloader.
 */


if (\false) {
    class EbashMan extends \Ebash\Man\Man
    {
    }
}