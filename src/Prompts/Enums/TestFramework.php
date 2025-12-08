<?php

declare(strict_types=1);

namespace App\Prompts\Enums;

/**
 * テストフレームワーク
 */
enum TestFramework: string
{
    case PHPUNIT = 'phpunit';
    case JEST = 'jest';
    case VITEST = 'vitest';
    case PYTEST = 'pytest';
    case GO = 'go';

    /**
     * エイリアスから解決
     */
    public static function fromAlias(string $alias): ?self
    {
        return match (strtolower($alias)) {
            'phpunit' => self::PHPUNIT,
            'jest' => self::JEST,
            'vitest' => self::VITEST,
            'pytest' => self::PYTEST,
            'go', 'gotest', 'go-test' => self::GO,
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
            self::JEST => 'Jest',
            self::VITEST => 'Vitest',
            self::PYTEST => 'pytest',
            self::GO => 'Go testing',
        };
    }

    /**
     * 利用可能な値の一覧（ヘルプ用）
     *
     * @return list<string>
     */
    public static function availableValues(): array
    {
        return ['PHPUnit', 'Jest', 'Vitest', 'pytest', 'go'];
    }
}
