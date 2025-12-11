<?php

declare(strict_types=1);

namespace App;

use App\Prompts\Functions\DebtAnalysis;
use App\Prompts\Functions\RefactoringSuggestion;
use App\Prompts\Functions\TestCodeGeneration;
use Mcp\Capability\Attribute\McpTool;
use Throwable;

class CodeQualityPrompts
{
    private const LOG_FILE = __DIR__ . '/../logs/seiren.log';

    private function log(string $message): void
    {
        $dir = dirname(self::LOG_FILE);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $timestamp = date('Y-m-d H:i:s');
        $line = "[{$timestamp}] {$message}" . PHP_EOL;
        file_put_contents(self::LOG_FILE, $line, FILE_APPEND | LOCK_EX);
    }
    /**
     * 技術的負債分析プロンプト
     *
     * @param string $language 言語（php, typescript）
     * @param string $perspective 設計観点（ddd, laravel, clean）
     * @param string $focus 分析観点（カンマ区切りで複数指定可）
     */
    #[McpTool(
        name: 'analyze_technical_debt',
        description: <<<'DESC'
技術的負債を分析し、欠陥を特定。行番号と欠陥スコアを含むテーブル形式で出力。

【focus引数 - 分析観点の指定】
グループ指定:
  - basic: カプセル化, 関心の分離, 命名（デフォルト）
  - structure: ドメインモデル, レイヤ分離, interface設計
  - quality: 不変性, 凝集性, 結合度, 条件分岐
  - antipatterns: 生焼けオブジェクト, デッドコード, null問題等
  - all: 全ての観点

個別指定（カンマ区切り）:
  encapsulation, separation-of-concerns, naming,
  domain-model-completeness, layer-separation, interface-design,
  immutability, cohesion, coupling, conditional-branching,
  half-baked-object, method-chain, dead-code, magic-number,
  null-problem, exception-abuse, god-class

例: focus="basic,antipatterns" または focus="encapsulation,naming"
DESC
    )]
    public function analyzeTechnicalDebt(
        string $language = '',
        string $perspective = '',
        string $focus = ''
    ): string {
        try {
            // focusをパース（カンマ区切り）
            $focuses = $focus !== ''
                ? array_map('trim', explode(',', $focus))
                : [];

            return DebtAnalysis::generate(
                $perspective !== '' ? $perspective : null,
                $language !== '' ? $language : null,
                $focuses
            );
        } catch (Throwable $e) {
            $this->log("analyzeTechnicalDebt ERROR - msg:{$e->getMessage()} at {$e->getFile()}:{$e->getLine()}");
            throw $e;
        }
    }

    /**
     * リファクタリング提案プロンプト
     *
     * @param string $context 追加コンテキスト
     * @param string $perspective 設計観点（ddd, laravel, clean）
     */
    #[McpTool(
        name: 'suggest_refactoring',
        description: '設計改善案をテーブル形式とMermaidクラス図で提案。DDD、Laravel、Clean Architecture等の観点を選択可能。'
    )]
    public function suggestRefactoring(
        string $context = '',
        string $perspective = ''
    ): string {
        try {
            return RefactoringSuggestion::generate(
                $context,
                $perspective !== '' ? $perspective : null
            );
        } catch (Throwable $e) {
            $this->log("suggestRefactoring ERROR - msg:{$e->getMessage()} at {$e->getFile()}:{$e->getLine()}");
            throw $e;
        }
    }

    /**
     * テストコード生成プロンプト
     *
     * @param string $language 言語（php, typescript）
     * @param string $testFramework テストフレームワーク（PHPUnit）
     */
    #[McpTool(
        name: 'generate_test_code',
        description: '高品質なテストコードを生成。PHPUnit、等のフレームワークに対応。'
    )]
    public function generateTestCode(
        string $language = '',
        string $testFramework = 'PHPUnit'
    ): string {
        try {
            return TestCodeGeneration::generate(
                $testFramework,
                $language !== '' ? $language : null
            );
        } catch (Throwable $e) {
            $this->log("generateTestCode ERROR - msg:{$e->getMessage()} at {$e->getFile()}:{$e->getLine()}");
            throw $e;
        }
    }
}
