<?php

declare(strict_types=1);

namespace App\Prompts\Functions;

use App\PromptLoader;
use App\Prompts\Enums\Language;
use App\Prompts\Enums\TestFramework;

/**
 * テストコード生成プロンプト
 */
final class TestCodeGeneration
{
    private const TEMPLATE_BASE = 'functions/test-code-generation/base';

    /**
     * テストコード生成プロンプトを生成
     *
     * @param string $code テスト対象コード
     * @param string $testFramework テストフレームワーク（PHPUnit）
     * @param string|null $language プログラミング言語（php, typescript）
     */
    public static function generate(
        string $code = '',
        string $testFramework = 'PHPUnit',
        ?string $language = null
    ): string {
        $loader = PromptLoader::getInstance();

        // テストフレームワーク
        $frameworkGuide = '';
        $frameworkEnum = TestFramework::fromAlias($testFramework);
        if ($frameworkEnum !== null) {
            $path = $frameworkEnum->promptPath();
            if ($loader->exists($path)) {
                $frameworkGuide = $loader->getContent($path);
            }
        }

        // 言語ガイド
        $languageGuide = '';
        if ($language !== null) {
            $languageEnum = Language::fromAlias($language);
            if ($languageEnum !== null) {
                $path = 'functions/test-code-generation/languages/' . $languageEnum->value;
                if ($loader->exists($path)) {
                    $languageGuide = $loader->getContent($path);
                }
            }
        }

        // 対象コードセクション
        $targetCode = self::buildTargetCodeSection($code);

        return $loader->renderTemplate(self::TEMPLATE_BASE, [
            'testFramework' => $testFramework,
            'frameworkGuide' => $frameworkGuide,
            'languageGuide' => $languageGuide,
            'targetCode' => $targetCode,
        ]);
    }

    private static function buildTargetCodeSection(string $code): string
    {
        if ($code === '') {
            return '会話コンテキスト内のテスト対象コードを読み込み、上記の原則に従ってテストコードを生成してください。';
        }

        return "```\n" . $code . "\n```\n\n上記のコードに対してテストコードを生成してください。";
    }
}
