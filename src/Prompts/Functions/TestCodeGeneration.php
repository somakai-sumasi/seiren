<?php

declare(strict_types=1);

namespace App\Prompts\Functions;

use App\PromptLoader;
use App\Prompts\PromptResolver;

/**
 * テストコード生成プロンプト
 */
final class TestCodeGeneration
{
    private const TEMPLATE_BASE = 'functions/test-code-generation/base';

    /** @var array<string, string> */
    private const FRAMEWORK_GUIDE_MAP = [
        'phpunit' => 'phpunit',
        'jest' => 'jest',
        'vitest' => 'vitest',
        'pytest' => 'pytest',
        'go' => 'go-testing',
        'go testing' => 'go-testing',
        'testing' => 'go-testing',
    ];

    /** @var array<string, string> */
    private const LANGUAGE_GUIDE_MAP = [
        'php' => 'php',
        'typescript' => 'typescript',
        'ts' => 'typescript',
        'go' => 'go',
        'golang' => 'go',
        'python' => 'python',
        'py' => 'python',
    ];

    /**
     * テストコード生成プロンプトを生成
     *
     * @param string $code 対象のコード
     * @param string $testFramework テストフレームワーク
     * @param string|null $language プログラミング言語
     */
    public static function generate(
        string $code,
        string $testFramework = 'PHPUnit',
        ?string $language = null
    ): string {
        $frameworkGuide = PromptResolver::resolve(
            self::FRAMEWORK_GUIDE_MAP,
            $testFramework,
            'functions/test-code-generation/frameworks'
        );

        $languageGuide = $language !== null
            ? PromptResolver::resolve(
                self::LANGUAGE_GUIDE_MAP,
                $language,
                'functions/test-code-generation/languages'
            )
            : '';

        return PromptLoader::getInstance()->renderTemplate(self::TEMPLATE_BASE, [
            'testFramework' => $testFramework,
            'frameworkGuide' => $frameworkGuide,
            'languageGuide' => $languageGuide,
            'code' => $code,
        ]);
    }
}
