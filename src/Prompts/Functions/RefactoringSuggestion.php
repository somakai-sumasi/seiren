<?php

declare(strict_types=1);

namespace App\Prompts\Functions;

use App\PromptLoader;
use App\Prompts\Core\CorePrompts;
use App\Prompts\Core\OutputFormats;

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
     * @param string $code 対象のコード
     * @param string $context 追加コンテキスト
     * @param string|null $perspective 設計観点
     */
    public static function generate(
        string $code,
        string $context = '',
        ?string $perspective = null
    ): string {
        $corePrompt = CorePrompts::all();
        $outputFormat = OutputFormats::forRefactoring();

        $contextSection = self::renderContext($context);

        $perspectivePrompt = '';
        if ($perspective !== null) {
            $perspectivePrompt = self::getPerspectivePrompt($perspective);
        }

        return PromptLoader::getInstance()->renderTemplate('functions/refactoring-suggestion/base', [
            'corePrompt' => $corePrompt,
            'perspectivePrompt' => $perspectivePrompt,
            'contextSection' => $contextSection,
            'code' => $code,
            'outputFormat' => $outputFormat,
        ]);
    }

    private static function getPerspectivePrompt(string $perspective): string
    {
        $path = match ($perspective) {
            'ddd' => 'functions/refactoring-suggestion/perspectives/ddd',
            'laravel' => 'functions/refactoring-suggestion/perspectives/laravel',
            'clean' => 'functions/refactoring-suggestion/perspectives/clean-architecture',
            default => null,
        };

        if ($path === null) {
            return '';
        }

        $loader = PromptLoader::getInstance();
        return $loader->exists($path) ? $loader->getContent($path) : '';
    }
}
