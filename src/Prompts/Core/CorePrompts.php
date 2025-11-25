<?php

declare(strict_types=1);

namespace App\Prompts\Core;

use App\PromptLoader;

/**
 * コアプロンプト - 変更容易性に関する基盤的考え方を定義
 *
 * 参考: 『良いコード／悪いコードで学ぶ設計入門』の設計原則
 */
final class CorePrompts
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
     * カプセル化の定義と判断基準
     */
    public static function encapsulation(): string
    {
        return self::getLoader()->getContent('core/encapsulation');
    }

    /**
     * 関心の分離の定義と判断基準
     */
    public static function separationOfConcerns(): string
    {
        return self::getLoader()->getContent('core/separation-of-concerns');
    }

    /**
     * ドメインモデル完全性の定義
     */
    public static function domainModelCompleteness(): string
    {
        return self::getLoader()->getContent('core/domain-model-completeness');
    }

    /**
     * 技術レイヤ間の関心の分離
     */
    public static function layerSeparation(): string
    {
        return self::getLoader()->getContent('core/layer-separation');
    }

    /**
     * interface設計の原則
     */
    public static function interfaceDesign(): string
    {
        return self::getLoader()->getContent('core/interface-design');
    }

    /**
     * 欠陥スコアリングの基準
     */
    public static function defectScoring(): string
    {
        return self::getLoader()->getContent('core/defect-scoring');
    }

    /**
     * 全てのコアプロンプトを結合して返す
     */
    public static function all(): string
    {
        return self::getLoader()->getCategoryContents('core');
    }
}
