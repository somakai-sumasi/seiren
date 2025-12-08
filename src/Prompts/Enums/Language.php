<?php

declare(strict_types=1);

namespace App\Prompts\Enums;

/**
 * 対応言語
 */
enum Language: string
{
    case PHP = 'php';
    case TYPESCRIPT = 'typescript';

    /**
     * エイリアスから解決
     */
    public static function fromAlias(string $alias): ?self
    {
        return match (strtolower($alias)) {
            'php' => self::PHP,
            'typescript', 'ts' => self::TYPESCRIPT,
            default => null,
        };
    }

    /**
     * プロンプトファイルのパスを取得
     */
    public function promptPath(): string
    {
        return 'languages/' . $this->value;
    }

    /**
     * 表示名を取得
     */
    public function label(): string
    {
        return match ($this) {
            self::PHP => 'PHP',
            self::TYPESCRIPT => 'TypeScript',
        };
    }

    /**
     * 利用可能な値の一覧（ヘルプ用）
     *
     * @return list<string>
     */
    public static function availableValues(): array
    {
        return ['php', 'typescript (ts)'];
    }
}
