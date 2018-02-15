<?php

use PhpCsFixer\Finder;
use PhpCsFixer\Config;

$finder = Finder::create()
    ->name('*.php')
    ->in(__DIR__.'/src')
    ->in(__DIR__.'/tests')
    ->exclude(__DIR__.'/tests/app/bootstrap/cache')
    ->exclude(__DIR__.'/tests/app/storage/framework/views');

return Config::create()
    ->setRules([
        '@Symfony' => true,
        'align_multiline_comment' => true,
        'array_syntax' => ['syntax' => 'short'],
        'combine_consecutive_issets' => true,
        'combine_consecutive_unsets' => true,
        'compact_nullable_typehint' => true,
        'linebreak_after_opening_tag' => true,
        'list_syntax' => ['syntax' => 'short'],
        'mb_str_functions' => true,
        'method_chaining_indentation' => true,
        'no_null_property_initialization' => true,
        'no_short_echo_tag' => true,
        'ordered_class_elements' => [
            'use_trait',
            'constant_public', 'constant_protected', 'constant_private',
            'property_public', 'property_protected', 'property_private',
            'construct',
            'method_public', 'method_protected', 'method_private',
            'magic', 'destruct', 'phpunit'
        ],
        'ordered_imports' => ['sortAlgorithm' => 'length'],
        'strict_comparison' => true,
        'strict_param' => true,
])->setFinder($finder);