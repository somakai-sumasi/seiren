<?php

declare(strict_types=1);

namespace App\Prompts\Functions;

use App\PromptLoader;

/**
 * テストコード生成プロンプト
 */
final class TestCodeGeneration
{
    private const TEMPLATE_BASE = 'functions/test-code-generation/base';

    private const FRAMEWORK_GUIDE_MAP = [
        'phpunit' => 'functions/test-code-generation/frameworks/phpunit',
        'jest' => 'functions/test-code-generation/frameworks/jest',
        'vitest' => 'functions/test-code-generation/frameworks/vitest',
        'pytest' => 'functions/test-code-generation/frameworks/pytest',
        'go' => 'functions/test-code-generation/frameworks/go-testing',
        'go testing' => 'functions/test-code-generation/frameworks/go-testing',
        'testing' => 'functions/test-code-generation/frameworks/go-testing',
    ];

    private const LANGUAGE_GUIDE_MAP = [
        'php' => 'functions/test-code-generation/languages/php',
        'typescript' => 'functions/test-code-generation/languages/typescript',
        'ts' => 'functions/test-code-generation/languages/typescript',
        'go' => 'functions/test-code-generation/languages/go',
        'golang' => 'functions/test-code-generation/languages/go',
        'python' => 'functions/test-code-generation/languages/python',
        'py' => 'functions/test-code-generation/languages/python',
    ];

    /**
     * Markdownテンプレートをプレースホルダーで置換して返す
     *
     * @param array<string, string> $values
     */
    private static function renderTemplate(string $templatePath, array $values): string
    {
        $template = PromptLoader::getInstance()->getContent($templatePath);

        $replacements = [];
        foreach ($values as $key => $value) {
            $replacements['{{' . $key . '}}'] = $value;
        }

        return strtr($template, $replacements);
    }

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
        $frameworkGuide = self::getFrameworkGuide($testFramework);
        $languageGuide = $language !== null ? self::getLanguageGuide($language) : '';

        return self::renderTemplate(self::TEMPLATE_BASE, [
            'testFramework' => $testFramework,
            'frameworkGuide' => $frameworkGuide,
            'languageGuide' => $languageGuide,
            'code' => $code,
        ]);
    }

    private static function getFrameworkGuide(string $framework): string
    {
        $key = strtolower(trim($framework));
        $path = self::FRAMEWORK_GUIDE_MAP[$key] ?? null;

        if ($path === null) {
            return '';
        }

        $loader = PromptLoader::getInstance();
        return $loader->exists($path) ? $loader->getContent($path) : '';
    }

    private static function getLanguageGuide(string $language): string
    {
        $key = strtolower(trim($language));
        $path = self::LANGUAGE_GUIDE_MAP[$key] ?? null;

        if ($path === null) {
            return '';
        }

        $loader = PromptLoader::getInstance();
        return $loader->exists($path) ? $loader->getContent($path) : '';
    }
}
