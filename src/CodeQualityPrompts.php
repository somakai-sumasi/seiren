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
     * @param string $perspective 設計観点（ddd, laravel, clean）
     * @param string $language 言語（php, typescript, go）
     */
    #[McpTool(
        name: 'analyze_technical_debt',
        description: '技術的負債を分析し、カプセル化・関心の分離・ドメインモデル完全性の観点から欠陥を特定。行番号と欠陥スコアを含むテーブル形式で出力。'
    )]
    public function analyzeTechnicalDebt(
        string $code,
        string $perspective = '',
        string $language = ''
    ): string {
        return DebtAnalysis::generate(
            $code,
            $perspective !== '' ? $perspective : null,
            $language !== '' ? $language : null
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
     * @param string $testFramework テストフレームワーク（PHPUnit, Jest, Vitest, pytest, go）
     * @param string $language 言語（php, typescript, go, python）
     */
    #[McpTool(
        name: 'generate_test_code',
        description: '高品質なテストコードを生成。PHPUnit、Jest、Vitest、pytest、Go testing等のフレームワークに対応。'
    )]
    public function generateTestCode(
        string $code,
        string $testFramework = 'PHPUnit',
        string $language = ''
    ): string {
        return TestCodeGeneration::generate(
            $code,
            $testFramework,
            $language !== '' ? $language : null
        );
    }
}
