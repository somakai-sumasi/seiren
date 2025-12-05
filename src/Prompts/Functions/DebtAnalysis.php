<?php

declare(strict_types=1);

namespace App\Prompts\Functions;

use App\PromptLoader;
use App\Prompts\Core\CorePrompts;
use App\Prompts\Core\OutputFormats;

/**
 * 技術的負債分析プロンプト
 */
final class DebtAnalysis
{
    /**
     * 技術的負債分析プロンプトを生成
     *
     * @param string $code 分析対象のコード
     * @param string|null $perspective 設計観点（ddd, laravel等）
     * @param string|null $language プログラミング言語
     */
    public static function generate(
        string $code,
        ?string $perspective = null,
        ?string $language = null
    ): string {
        $corePrompt = CorePrompts::all();
        $outputFormat = OutputFormats::all();

        $perspectivePrompt = '';
        if ($perspective !== null) {
            $perspectivePrompt = self::getPerspectivePrompt($perspective);
        }

        $languagePrompt = '';
        if ($language !== null) {
            $languagePrompt = self::getLanguagePrompt($language);
        }

        return PromptLoader::getInstance()->renderTemplate('functions/debt-analysis/base', [
            'corePrompt' => $corePrompt,
            'perspectivePrompt' => $perspectivePrompt,
            'languagePrompt' => $languagePrompt,
            'code' => $code,
            'outputFormat' => $outputFormat,
        ]);
    }

    private static function getPerspectivePrompt(string $perspective): string
    {
        $loader = PromptLoader::getInstance();
        $path = 'perspectives/' . match ($perspective) {
            'ddd' => 'ddd',
            'laravel' => 'laravel',
            'clean' => 'clean-architecture',
            default => '',
        };

        if ($path === 'perspectives/') {
            return '';
        }

        return $loader->exists($path) ? $loader->getContent($path) : '';
    }

    private static function getLanguagePrompt(string $language): string
    {
        $loader = PromptLoader::getInstance();
        $path = 'languages/' . match (strtolower($language)) {
            'php' => 'php',
            'typescript', 'ts' => 'typescript',
            'go' => 'go',
            default => '',
        };

        if ($path === 'languages/') {
            return '';
        }

        return $loader->exists($path) ? $loader->getContent($path) : '';
    }
}
