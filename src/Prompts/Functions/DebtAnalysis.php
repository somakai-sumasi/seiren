<?php

declare(strict_types=1);

namespace App\Prompts\Functions;

use App\PromptLoader;
use App\Prompts\Core\Antipatterns;
use App\Prompts\Core\CorePrompts;
use App\Prompts\Core\OutputFormats;
use App\Prompts\PromptResolver;

/**
 * 技術的負債分析プロンプト
 */
final class DebtAnalysis
{
    /** @var array<string, string> */
    private const PERSPECTIVE_MAPPING = [
        'ddd' => 'ddd',
        'laravel' => 'laravel',
        'clean' => 'clean-architecture',
    ];

    /** @var array<string, string> */
    private const LANGUAGE_MAPPING = [
        'php' => 'php',
        'typescript' => 'typescript',
        'ts' => 'typescript',
    ];

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
        $antipatternsPrompt = Antipatterns::all();
        $outputFormat = OutputFormats::all();

        $perspectivePrompt = '';
        if ($perspective !== null) {
            $perspectivePrompt = PromptResolver::resolve(
                self::PERSPECTIVE_MAPPING,
                $perspective,
                'perspectives'
            );
        }

        $languagePrompt = '';
        if ($language !== null) {
            $languagePrompt = PromptResolver::resolve(
                self::LANGUAGE_MAPPING,
                $language,
                'languages'
            );
        }

        return PromptLoader::getInstance()->renderTemplate('functions/debt-analysis/base', [
            'corePrompt' => $corePrompt,
            'antipatternsPrompt' => $antipatternsPrompt,
            'perspectivePrompt' => $perspectivePrompt,
            'languagePrompt' => $languagePrompt,
            'code' => $code,
            'outputFormat' => $outputFormat,
        ]);
    }
}
