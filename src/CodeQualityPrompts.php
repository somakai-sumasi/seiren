<?php

declare(strict_types=1);

namespace App;

use App\Prompts\Functions\DebtAnalysis;
use App\Prompts\Functions\RefactoringSuggestion;
use App\Prompts\Functions\TestCodeGeneration;
use Mcp\Capability\Attribute\McpTool;

class CodeQualityPrompts
{
    /**
     * 技術的負債分析プロンプト
     *
     * @param string $code 分析対象のコード
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
        string $code,
        string $language = '',
        string $perspective = '',
        string $focus = ''
    ): string {
        // focusをパース（カンマ区切り）
        $focuses = $focus !== ''
            ? array_map('trim', explode(',', $focus))
            : [];

        return DebtAnalysis::generate(
            $code,
            $perspective !== '' ? $perspective : null,
            $language !== '' ? $language : null,
            $focuses
        );
    }

    /**
     * リファクタリング提案プロンプト
     *
     * @param string $code 対象のコード
     * @param string $context 追加コンテキスト
     * @param string $perspective 設計観点（ddd, laravel, clean）
     */
    #[McpTool(
        name: 'suggest_refactoring',
        description: '設計改善案をテーブル形式とMermaidクラス図で提案。DDD、Laravel、Clean Architecture等の観点を選択可能。'
    )]
    public function suggestRefactoring(
        string $code,
        string $context = '',
        string $perspective = ''
    ): string {
        return RefactoringSuggestion::generate(
            $code,
            $context,
            $perspective !== '' ? $perspective : null
        );
    }

    /**
     * テストコード生成プロンプト
     *
     * @param string $code 対象のコード
     * @param string $language 言語（php, typescript）
     * @param string $testFramework テストフレームワーク（PHPUnit, Jest, Vitest, pytest, go）
     */
    #[McpTool(
        name: 'generate_test_code',
        description: '高品質なテストコードを生成。PHPUnit、Jest、Vitest、pytest、Go testing等のフレームワークに対応。'
    )]
    public function generateTestCode(
        string $code,
        string $language = '',
        string $testFramework = 'PHPUnit'
    ): string {
        return TestCodeGeneration::generate(
            $code,
            $testFramework,
            $language !== '' ? $language : null
        );
    }
}
