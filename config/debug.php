<?php

function dd(mixed ...$values): never
{
    echo "<pre>";
    array_map(fn ($value) => var_dump($value), $values);
    echo "</pre>";
    exit();
}
