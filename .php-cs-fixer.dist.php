<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude(['vendor', 'storage', 'bootstrap', 'node_modules', 'var'])
    ->name('*.php')
    ->notName('_*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new PhpCsFixer\Config())
    ->setRules([
        // Base standards
        '@PSR12' => true,

        // PHPDoc handling
        'phpdoc_align' => ['align' => 'vertical'],
        'phpdoc_summary' => false,
        'phpdoc_order' => ['order' => ['param', 'return', 'throws']],
        'phpdoc_trim_consecutive_blank_line_separation' => true,
        'phpdoc_indent' => true,
        'phpdoc_to_comment' => false, 
        'phpdoc_trim' => true,
        'phpdoc_separation' => true,
        'phpdoc_scalar' => true,
        'phpdoc_no_package' => true,
        'phpdoc_var_without_name' => false,

        // Import & namespace
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'no_unused_imports' => true,
        'single_import_per_statement' => true,

        // Spacing & formatting
        'array_syntax' => ['syntax' => 'short'],
        'concat_space' => ['spacing' => 'one'],
        'binary_operator_spaces' => [
            'default' => 'single_space',
        ],
        'single_quote' => true,
        'no_trailing_comma_in_singleline' => true,
        'trailing_comma_in_multiline' => ['elements' => ['arrays']],
        'blank_line_before_statement' => [
            'statements' => ['return', 'if', 'for', 'foreach', 'while', 'do', 'switch', 'try'],
        ],

        // Class structure
        'ordered_class_elements' => [
            'sort_algorithm' => 'alpha',
            'order' => [
                'use_trait',
                'constant_public',
                'constant_protected',
                'constant_private',
                'property_public',
                'property_protected',
                'property_private',
                'construct',
                'destruct',
                'magic',
                'method_public',
                'method_protected',
                'method_private',
            ],
        ],

        // Miscellaneous
        'declare_strict_types' => true,
        'no_superfluous_phpdoc_tags' => false, 
        'no_blank_lines_after_phpdoc' => false,
    ])
    ->setRiskyAllowed(true)
    ->setUsingCache(true)
    ->setCacheFile(__DIR__ . '/storage/cache/php-cs-fixer/.php-cs-fixer.cache')
    ->setFinder($finder);