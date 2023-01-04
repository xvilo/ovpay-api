<?php

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
;

$config = new PhpCsFixer\Config();
return $config->setRules([
    '@PSR12' => true,
    '@Symfony' => true,
    'strict_param' => true,
    'fully_qualified_strict_types' => true,
    'global_namespace_import' => [
        'import_constants' => true,
        'import_functions' => true,
    ],
    'lambda_not_used_import' => true,
    'no_leading_import_slash' => true,
    'no_unused_imports' => true,
    'ordered_imports' => [],
    'array_syntax' => ['syntax' => 'short'],
    'yoda_style' => false,
    'concat_space' => [
        'spacing' => 'one'
    ]
])
    ->setIndent("    ")
    ->setLineEnding("\n")
    ->setFinder($finder);
