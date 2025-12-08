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
     * @param string $code 対象のコード
     * @param string $testFramework テストフレームワーク（PHPUnit, Jest, Vitest, pytest, go）
     * @param string|null $language プログラミング言語（php, typescript）
     */
    public static function generate(
        string $code,
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

        return $loader->renderTemplate(self::TEMPLATE_BASE, [
            'testFramework' => $testFramework,
            'frameworkGuide' => $frameworkGuide,
            'languageGuide' => $languageGuide,
            'code' => $code,
        ]);
    }
}
