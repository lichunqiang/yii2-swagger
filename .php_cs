<?php

$header = <<<EOF
This file is part of the light/yii2-swagger.

(c) lichunqiang <light-li@hotmail.com>

This source file is subject to the MIT license that is bundled
with this source code in the file LICENSE.
EOF;

Symfony\CS\Fixer\Contrib\HeaderCommentFixer::setHeader($header);

return Symfony\CS\Config\Config::create()
    // use default SYMFONY_LEVEL and extra fixers:
    ->fixers(array(
        '@PSR2',
        'header_comment',
        'short_array_syntax',
        'ordered_use',
        'php_unit_construct',
        'php_unit_strict',
        'phpdoc_order',
        // 'strict_param',
        'align_double_arrow' => false,
        'align_equals' => false,
        'concat_with_spaces',
        // 'concat_without_spaces' => false,
        'phpdoc_no_package' => false,
        'empty_return' => false,
    ))
    ->finder(
        Symfony\CS\Finder\DefaultFinder::create()
            ->in(__DIR__ . '/src')
            ->notName('index.php')
    )
;
