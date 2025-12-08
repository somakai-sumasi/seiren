<?php

declare(strict_types=1);

namespace App\Prompts\Enums;

/**
 * 分析観点グループ
 */
enum FocusGroup: string
{
    case BASIC = 'basic';
    case STRUCTURE = 'structure';
    case QUALITY = 'quality';
    case ANTIPATTERNS = 'antipatterns';
    case ALL = 'all';

    /**
     * グループに含まれる観点を取得
     *
     * @return list<Focus>
     */
    public function focuses(): array
    {
        return match ($this) {
            self::BASIC => [
                Focus::ENCAPSULATION,
                Focus::SEPARATION_OF_CONCERNS,
                Focus::NAMING,
            ],
            self::STRUCTURE => [
                Focus::DOMAIN_MODEL,
                Focus::LAYER_SEPARATION,
                Focus::INTERFACE_DESIGN,
            ],
            self::QUALITY => [
                Focus::IMMUTABILITY,
                Focus::COHESION,
                Focus::COUPLING,
                Focus::CONDITIONAL_BRANCHING,
            ],
            self::ANTIPATTERNS => [
                Focus::HALF_BAKED_OBJECT,
                Focus::METHOD_CHAIN,
                Focus::DEAD_CODE,
                Focus::MAGIC_NUMBER,
                Focus::NULL_PROBLEM,
                Focus::EXCEPTION_ABUSE,
                Focus::GOD_CLASS,
            ],
            self::ALL => Focus::cases(),
        };
    }

    /**
     * 表示名を取得
     */
    public function label(): string
    {
        return match ($this) {
            self::BASIC => 'basic (カプセル化, 関心の分離, 命名)',
            self::STRUCTURE => 'structure (ドメインモデル, レイヤ分離, interface設計)',
            self::QUALITY => 'quality (不変性, 凝集性, 結合度, 条件分岐)',
            self::ANTIPATTERNS => 'antipatterns (生焼けオブジェクト, デッドコード等)',
            self::ALL => 'all (全ての観点)',
        };
    }

    /**
     * 利用可能な値の一覧（ヘルプ用）
     *
     * @return list<string>
     */
    public static function availableValues(): array
    {
        return array_map(fn(self $g) => $g->label(), self::cases());
    }
}
