<?php

declare(strict_types=1);

namespace App\Prompts\Enums;

/**
 * テストフレームワーク
 */
enum TestFramework: string
{
    case PHPUNIT = 'phpunit';

    /**
     * エイリアスから解決
     */
    public static function fromAlias(string $alias): ?self
    {
        return match (strtolower($alias)) {
            'phpunit' => self::PHPUNIT,
            default => null,
        };
    }

    /**
     * プロンプトファイルのパスを取得
     */
    public function promptPath(): string
    {
        return 'functions/test-code-generation/frameworks/' . $this->value;
    }

    /**
     * 表示名を取得
     */
    public function label(): string
    {
        return match ($this) {
            self::PHPUNIT => 'PHPUnit',
        };
    }

    /**
     * 利用可能な値の一覧（ヘルプ用）
     *
     * @return list<string>
     */
    public static function availableValues(): array
    {
        return ['PHPUnit'];
    }
}
