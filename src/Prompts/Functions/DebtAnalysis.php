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
    private static ?PromptLoader $loader = null;

    private static function getLoader(): PromptLoader
    {
        if (self::$loader === null) {
            self::$loader = new PromptLoader();
        }
        return self::$loader;
    }

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

        return <<<PROMPT
あなたは変更容易性を専門とするコードレビュアーです。
以下のコードを分析し、技術的負債を特定してください。

# 分析の基盤知識

{$corePrompt}

{$perspectivePrompt}

{$languagePrompt}

# 対象コード

行番号を参照できるよう、以下のコードを分析してください：

```
{$code}
```

# 分析手順

1. コードを読み、各行が何をしているか理解する
2. カプセル化の観点から問題を特定する
3. 関心の分離の観点から問題を特定する
4. ドメインモデル完全性の観点から問題を特定する
5. レイヤ違反の観点から問題を特定する
6. 各欠陥の行番号と欠陥スコアを計算する

# 出力形式

{$outputFormat}

# 重要な注意事項

- 必ず具体的な行番号を指摘すること（例: 13行目、24-26行目）
- 欠陥スコアは「欠陥行数 × 重み係数」で計算すること
- 推測ではなく、コードから読み取れる事実に基づいて分析すること
- 欠陥がない場合は「検出されませんでした」と明記すること
PROMPT;
    }

    private static function getPerspectivePrompt(string $perspective): string
    {
        $loader = self::getLoader();
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
        $loader = self::getLoader();
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
