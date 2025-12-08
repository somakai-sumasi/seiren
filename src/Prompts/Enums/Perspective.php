<?php

declare(strict_types=1);

namespace App\Prompts\Enums;

/**
 * 設計観点（アーキテクチャ/フレームワーク）
 */
enum Perspective: string
{
    case DDD = 'ddd';
    case LARAVEL = 'laravel';
    case CLEAN_ARCHITECTURE = 'clean-architecture';

    /**
     * エイリアスから解決
     */
    public static function fromAlias(string $alias): ?self
    {
        return match (strtolower($alias)) {
            'ddd' => self::DDD,
            'laravel' => self::LARAVEL,
            'clean', 'clean-architecture' => self::CLEAN_ARCHITECTURE,
            default => null,
        };
    }

    /**
     * プロンプトファイルのパスを取得
     */
    public function promptPath(): string
    {
        return 'perspectives/' . $this->value;
    }

    /**
     * 表示名を取得
     */
    public function label(): string
    {
        return match ($this) {
            self::DDD => 'Domain-Driven Design',
            self::LARAVEL => 'Laravel',
            self::CLEAN_ARCHITECTURE => 'Clean Architecture',
        };
    }

    /**
     * 利用可能な値の一覧（ヘルプ用）
     *
     * @return list<string>
     */
    public static function availableValues(): array
    {
        return ['ddd', 'laravel', 'clean'];
    }
}
