<?php

declare(strict_types=1);

namespace App\Prompts\Enums;

/**
 * 個別の分析観点
 */
enum Focus: string
{
    // === Core: Basic ===
    case ENCAPSULATION = 'encapsulation';
    case SEPARATION_OF_CONCERNS = 'separation-of-concerns';
    case NAMING = 'naming';

    // === Core: Structure ===
    case DOMAIN_MODEL = 'domain-model-completeness';
    case LAYER_SEPARATION = 'layer-separation';
    case INTERFACE_DESIGN = 'interface-design';

    // === Core: Quality ===
    case IMMUTABILITY = 'immutability';
    case COHESION = 'cohesion';
    case COUPLING = 'coupling';
    case CONDITIONAL_BRANCHING = 'conditional-branching';

    // === Core: Always included ===
    case DEFECT_SCORING = 'defect-scoring';

    // === Antipatterns ===
    case HALF_BAKED_OBJECT = 'half-baked-object';
    case METHOD_CHAIN = 'method-chain';
    case DEAD_CODE = 'dead-code';
    case MAGIC_NUMBER = 'magic-number';
    case NULL_PROBLEM = 'null-problem';
    case EXCEPTION_ABUSE = 'exception-abuse';
    case GOD_CLASS = 'god-class';

    /**
     * coreカテゴリかどうか
     */
    public function isCore(): bool
    {
        return match ($this) {
            self::HALF_BAKED_OBJECT,
            self::METHOD_CHAIN,
            self::DEAD_CODE,
            self::MAGIC_NUMBER,
            self::NULL_PROBLEM,
            self::EXCEPTION_ABUSE,
            self::GOD_CLASS => false,
            default => true,
        };
    }

    /**
     * antipatternsカテゴリかどうか
     */
    public function isAntipattern(): bool
    {
        return !$this->isCore();
    }

    /**
     * プロンプトファイルのパスを取得
     */
    public function promptPath(): string
    {
        $category = $this->isCore() ? 'core' : 'antipatterns';
        return $category . '/' . $this->value;
    }

    /**
     * 表示名を取得
     */
    public function label(): string
    {
        return match ($this) {
            self::ENCAPSULATION => 'カプセル化',
            self::SEPARATION_OF_CONCERNS => '関心の分離',
            self::NAMING => '命名設計',
            self::DOMAIN_MODEL => 'ドメインモデル完全性',
            self::LAYER_SEPARATION => 'レイヤ分離',
            self::INTERFACE_DESIGN => 'interface設計',
            self::IMMUTABILITY => '不変性',
            self::COHESION => '凝集性',
            self::COUPLING => '結合度',
            self::CONDITIONAL_BRANCHING => '条件分岐',
            self::DEFECT_SCORING => '欠陥スコアリング',
            self::HALF_BAKED_OBJECT => '生焼けオブジェクト',
            self::METHOD_CHAIN => 'メソッドチェイン濫用',
            self::DEAD_CODE => 'デッドコード',
            self::MAGIC_NUMBER => 'マジックナンバー',
            self::NULL_PROBLEM => 'null問題',
            self::EXCEPTION_ABUSE => '例外の濫用',
            self::GOD_CLASS => '神クラス',
        };
    }

    /**
     * 文字列から解決（エイリアス対応）
     */
    public static function fromString(string $value): ?self
    {
        // まず完全一致を試す
        foreach (self::cases() as $case) {
            if ($case->value === $value) {
                return $case;
            }
        }
        return null;
    }

    /**
     * 利用可能な値の一覧（ヘルプ用）
     *
     * @return list<string>
     */
    public static function availableValues(): array
    {
        return array_map(fn(self $f) => $f->value, self::cases());
    }
}
