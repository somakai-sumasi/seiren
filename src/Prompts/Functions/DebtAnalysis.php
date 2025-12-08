<?php

declare(strict_types=1);

namespace App\Prompts\Functions;

use App\PromptLoader;
use App\Prompts\AnalysisFocus;
use App\Prompts\Core\OutputFormats;
use App\Prompts\Enums\Focus;
use App\Prompts\Enums\Language;
use App\Prompts\Enums\Perspective;

/**
 * 技術的負債分析プロンプト
 */
final class DebtAnalysis
{
    /**
     * 技術的負債分析プロンプトを生成
     *
     * @param string $code 分析対象のコード
     * @param string|null $perspective 設計観点（ddd, laravel, clean）
     * @param string|null $language プログラミング言語（php, typescript）
     * @param list<string> $focuses 分析観点のリスト（グループまたは個別観点）
     */
    public static function generate(
        string $code,
        ?string $perspective = null,
        ?string $language = null,
        array $focuses = []
    ): string {
        $loader = PromptLoader::getInstance();

        // focusesが空の場合はデフォルト
        if ($focuses === []) {
            $focuses = AnalysisFocus::defaults();
        }

        // 観点を解決
        $resolved = AnalysisFocus::resolve($focuses);

        // coreプロンプトを構築
        $corePrompt = self::buildPromptFromFocuses($resolved['core']);

        // antipatternsプロンプトを構築
        $antipatternsPrompt = self::buildPromptFromFocuses($resolved['antipatterns']);

        $outputFormat = OutputFormats::all();

        // 設計観点（Perspective）
        $perspectivePrompt = '';
        if ($perspective !== null) {
            $perspectiveEnum = Perspective::fromAlias($perspective);
            if ($perspectiveEnum !== null) {
                $perspectivePrompt = $loader->getContent($perspectiveEnum->promptPath());
            }
        }

        // 言語（Language）
        $languagePrompt = '';
        if ($language !== null) {
            $languageEnum = Language::fromAlias($language);
            if ($languageEnum !== null) {
                $languagePrompt = $loader->getContent($languageEnum->promptPath());
            }
        }

        return $loader->renderTemplate('functions/debt-analysis/base', [
            'corePrompt' => $corePrompt,
            'antipatternsPrompt' => $antipatternsPrompt,
            'perspectivePrompt' => $perspectivePrompt,
            'languagePrompt' => $languagePrompt,
            'code' => $code,
            'outputFormat' => $outputFormat,
        ]);
    }

    /**
     * Focusリストからプロンプトを構築
     *
     * @param list<Focus> $focuses
     */
    private static function buildPromptFromFocuses(array $focuses): string
    {
        if ($focuses === []) {
            return '';
        }

        $loader = PromptLoader::getInstance();
        $contents = [];

        foreach ($focuses as $focus) {
            $path = $focus->promptPath();
            if ($loader->exists($path)) {
                $contents[] = $loader->getContent($path);
            }
        }

        return implode("\n\n---\n\n", $contents);
    }
}
