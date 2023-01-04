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
    'strict_param' => true,
    'fully_qualified_strict_types' => true,
    'global_namespace_import' => true,
    'lambda_not_used_import' => true,
    'no_leading_import_slash' => true,
    'no_unused_imports' => true,
    'ordered_imports' => [],
    'array_syntax' => ['syntax' => 'short'],
])
    ->setIndent("    ")
    ->setLineEnding("\n")
    ->setFinder($finder);
