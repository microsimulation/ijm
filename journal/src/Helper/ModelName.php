<?php

namespace Microsimulation\Journal\Helper;

use Assert\Assertion;

final class ModelName
{
    private static $types = [
        'correction' => [
            'singular' => 'Correction',
            'plural' => 'Corrections',
        ],
        'editorial' => [
            'singular' => 'Editorial',
            'plural' => 'Editorials',
        ],
        'research-article' => [
            'singular' => 'Research article',
            'plural' => 'Research articles',
        ],
        'research-communication' => [
            'singular' => 'Commentary',
            'plural' => 'Commentary',
        ],
        'registered-report' => [
            'singular' => 'Software review',
            'plural' => 'Software reviews',
        ],
        'scientific-correspondence' => [
            'singular' => 'Book review',
            'plural' => 'Book reviews',
        ],
        'short-report' => [
            'singular' => 'Research note',
            'plural' => 'Research notes',
        ],
        'tools-resources' => [
            'singular' => 'Data watch',
            'plural' => 'Data watch',
        ],
        'collection' => [
            'singular' => 'Issue',
            'plural' => 'Issues',
        ],
    ];

    private function __construct()
    {
    }

    public static function singular(string $id) : string
    {
        return self::getForType($id)['singular'];
    }

    public static function plural(string $id) : string
    {
        return self::getForType($id)['plural'];
    }

    private static function getForType(string $id) : array
    {
        Assertion::keyExists(self::$types, $id);

        return self::$types[$id];
    }
}
