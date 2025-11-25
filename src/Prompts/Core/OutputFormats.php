<?php

declare(strict_types=1);

namespace App\Prompts\Core;

use App\PromptLoader;

/**
 * 出力フォーマット定義 - 分析結果の統一的な出力形式を定義
 */
final class OutputFormats
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
     * 関心の分離の欠陥テーブル形式
     */
    public static function separationOfConcernsTable(): string
    {
        return self::getLoader()->getContent('output-formats/separation-of-concerns-table');
    }

    /**
     * カプセル化の欠陥テーブル形式
     */
    public static function encapsulationTable(): string
    {
        return self::getLoader()->getContent('output-formats/encapsulation-table');
    }

    /**
     * ドメインモデル完全性の欠陥テーブル形式
     */
    public static function domainModelTable(): string
    {
        return self::getLoader()->getContent('output-formats/domain-model-table');
    }

    /**
     * レイヤ違反の欠陥テーブル形式
     */
    public static function layerViolationTable(): string
    {
        return self::getLoader()->getContent('output-formats/layer-violation-table');
    }

    /**
     * 設計改善提案テーブル形式
     */
    public static function refactoringSuggestionTable(): string
    {
        return self::getLoader()->getContent('output-formats/refactoring-suggestion-table');
    }

    /**
     * Mermaidクラス図の出力形式
     */
    public static function mermaidClassDiagram(): string
    {
        return self::getLoader()->getContent('output-formats/mermaid-class-diagram');
    }

    /**
     * スコアサマリー形式
     */
    public static function scoreSummary(): string
    {
        return self::getLoader()->getContent('output-formats/score-summary');
    }

    /**
     * 全ての出力フォーマットを結合
     */
    public static function all(): string
    {
        return implode("\n\n---\n\n", [
            self::separationOfConcernsTable(),
            self::encapsulationTable(),
            self::domainModelTable(),
            self::layerViolationTable(),
            self::scoreSummary(),
        ]);
    }

    /**
     * リファクタリング提案用の出力フォーマット
     */
    public static function forRefactoring(): string
    {
        return implode("\n\n---\n\n", [
            self::refactoringSuggestionTable(),
            self::mermaidClassDiagram(),
        ]);
    }
}
