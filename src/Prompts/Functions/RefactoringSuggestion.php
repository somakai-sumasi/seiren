<?php

declare(strict_types=1);

namespace App\Prompts\Functions;

use App\PromptLoader;
use App\Prompts\Core\CorePrompts;
use App\Prompts\Core\OutputFormats;
use App\Prompts\PromptResolver;

/**
 * リファクタリング提案プロンプト
 */
final class RefactoringSuggestion
{
    /** @var array<string, string> */
    private const PERSPECTIVE_MAPPING = [
        'ddd' => 'ddd',
        'laravel' => 'laravel',
        'clean' => 'clean-architecture',
    ];

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
            $perspectivePrompt = PromptResolver::resolve(
                self::PERSPECTIVE_MAPPING,
                $perspective,
                'functions/refactoring-suggestion/perspectives'
            );
        }

        return PromptLoader::getInstance()->renderTemplate('functions/refactoring-suggestion/base', [
            'corePrompt' => $corePrompt,
            'perspectivePrompt' => $perspectivePrompt,
            'contextSection' => $contextSection,
            'code' => $code,
            'outputFormat' => $outputFormat,
        ]);
    }
}
