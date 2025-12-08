<?php

declare(strict_types=1);

namespace App\Prompts\Core;

use App\PromptLoader;

/**
 * アンチパターンプロンプト - 具体的な悪いパターンの検出基準を定義
 */
final class Antipatterns
{
    /**
     * 生焼けオブジェクト
     */
    public static function halfBakedObject(): string
    {
        return PromptLoader::getInstance()->getContent('antipatterns/half-baked-object');
    }

    /**
     * メソッドチェイン濫用
     */
    public static function methodChain(): string
    {
        return PromptLoader::getInstance()->getContent('antipatterns/method-chain');
    }

    /**
     * デッドコード
     */
    public static function deadCode(): string
    {
        return PromptLoader::getInstance()->getContent('antipatterns/dead-code');
    }

    /**
     * マジックナンバー
     */
    public static function magicNumber(): string
    {
        return PromptLoader::getInstance()->getContent('antipatterns/magic-number');
    }

    /**
     * null問題
     */
    public static function nullProblem(): string
    {
        return PromptLoader::getInstance()->getContent('antipatterns/null-problem');
    }

    /**
     * 例外の濫用
     */
    public static function exceptionAbuse(): string
    {
        return PromptLoader::getInstance()->getContent('antipatterns/exception-abuse');
    }

    /**
     * 神クラス
     */
    public static function godClass(): string
    {
        return PromptLoader::getInstance()->getContent('antipatterns/god-class');
    }

    /**
     * 全てのアンチパターンプロンプトを結合して返す
     */
    public static function all(): string
    {
        return PromptLoader::getInstance()->getCategoryContents('antipatterns');
    }
}
