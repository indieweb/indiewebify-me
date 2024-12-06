<?php

namespace IndieWeb;

const TESTING = true;

require __DIR__ . '/../vendor/autoload.php';

/**
 * Mock follow one redirect
 *
 * @param array $responses Responses
 *
 * @return \Closure
 */
function mockFollowOneRedirect(array $responses)
{
    $i = 0;
    $responses = array_values($responses);

    /**
     * Return the next response in the list
  *
     * @return mixed
     */
    return function () use (&$i, $responses) {
        $out = array_key_exists($i, $responses) ? $responses[$i] : null;
        $i = $i + 1;
        return $out;
    };
}
