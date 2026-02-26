<?php

declare(strict_types=1);

namespace App\Prompts\Functions;

use App\PromptLoader;
use App\Prompts\Core\CorePrompts;
use App\Prompts\Core\OutputFormats;
use App\Prompts\Enums\Perspective;

/**
 * リファクタリング提案プロンプト
 */
final class RefactoringSuggestion
{
    private static function renderContext(string $context): string
    {
        if ($context === '') {
            return '';
        }

        return PromptLoader::getInstance()->renderTemplate(
            'functions/refactoring-suggestion/context',
            ['context' => $context]
        );
    }

    /**
     * リファクタリング提案プロンプトを生成
     *
     * @param string $code 分析対象コード
     * @param string $context 追加コンテキスト
     * @param string|null $perspective 設計観点（ddd, laravel, clean）
     */
    public static function generate(
        string $code = '',
        string $context = '',
        ?string $perspective = null
    ): string {
        $loader = PromptLoader::getInstance();

        $corePrompt = CorePrompts::all();
        $outputFormat = OutputFormats::forRefactoring();

        $contextSection = self::renderContext($context);

        // 設計観点（Perspective）
        $perspectivePrompt = '';
        if ($perspective !== null) {
            $perspectiveEnum = Perspective::fromAlias($perspective);
            if ($perspectiveEnum !== null) {
                $perspectivePrompt = $loader->getContent($perspectiveEnum->promptPath());
            }
        }

        // 対象コードセクション
        $targetCode = self::buildTargetCodeSection($code);

        return $loader->renderTemplate('functions/refactoring-suggestion/base', [
            'corePrompt' => $corePrompt,
            'perspectivePrompt' => $perspectivePrompt,
            'contextSection' => $contextSection,
            'outputFormat' => $outputFormat,
            'targetCode' => $targetCode,
        ]);
    }

    private static function buildTargetCodeSection(string $code): string
    {
        if ($code === '') {
            return '会話コンテキスト内の対象コードを分析し、上記の基準に従って設計改善案を提案してください。';
        }

        return "```\n" . $code . "\n```\n\n上記のコードを分析し、設計改善案を提案してください。";
    }
}
