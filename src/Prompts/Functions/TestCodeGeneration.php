<?php

declare(strict_types=1);

namespace App\Prompts\Functions;

/**
 * テストコード生成プロンプト
 */
final class TestCodeGeneration
{
    /**
     * テストコード生成プロンプトを生成
     *
     * @param string $code 対象のコード
     * @param string $testFramework テストフレームワーク
     * @param string|null $language プログラミング言語
     */
    public static function generate(
        string $code,
        string $testFramework = 'PHPUnit',
        ?string $language = null
    ): string {
        $frameworkGuide = self::getFrameworkGuide($testFramework);
        $languageGuide = $language !== null ? self::getLanguageGuide($language) : '';

        return <<<PROMPT
あなたはテスト駆動開発を専門とするソフトウェアエンジニアです。
以下のコードに対して、高品質なテストコードを生成してください。

# テストフレームワーク

{$testFramework}

{$frameworkGuide}

{$languageGuide}

# 対象コード

```
{$code}
```

# テスト設計の原則

## 1. テストの構造（AAA パターン）
- **Arrange**: テストの準備（データ、モック等）
- **Act**: テスト対象の実行
- **Assert**: 結果の検証

## 2. テストすべき観点
- **正常系**: 期待される入力に対する期待される出力
- **境界値**: 境界条件（0、空、最大値等）
- **異常系**: 不正な入力、例外ケース
- **状態遷移**: オブジェクトの状態変化

## 3. テストの品質
- **独立性**: 各テストが他に依存しない
- **再現性**: 何度実行しても同じ結果
- **可読性**: テスト名で何をテストしているか明確
- **保守性**: 実装変更に強い

# 出力形式

以下の形式でテストコードを出力してください：

### テストケース一覧

| テスト名 | 種類 | テスト対象 | 期待結果 |
|--------|-----|----------|---------|
| test_XXX | 正常系/境界値/異常系 | メソッド名 | 期待される振る舞い |

### テストコード

```{$testFramework}
// テストコードをここに出力
```

### モック/スタブの設計

必要なモック/スタブがある場合、その設計意図を説明してください。

# 重要な注意事項

- テスト名は日本語または英語で、テストの意図が明確に分かるようにすること
- 各テストは独立して実行可能であること
- 過度なモック化を避け、テストの信頼性を確保すること
- エッジケースを網羅すること
PROMPT;
    }

    private static function getFrameworkGuide(string $framework): string
    {
        return match (strtolower($framework)) {
            'phpunit' => self::phpUnitGuide(),
            'jest' => self::jestGuide(),
            'vitest' => self::vitestGuide(),
            'pytest' => self::pytestGuide(),
            'go', 'testing' => self::goTestingGuide(),
            default => '',
        };
    }

    private static function phpUnitGuide(): string
    {
        return <<<'PROMPT'
## PHPUnit ガイド

### 基本構造
```php
use PHPUnit\Framework\TestCase;

class SampleTest extends TestCase
{
    public function test_メソッド名_条件_期待結果(): void
    {
        // Arrange
        $sut = new TargetClass();

        // Act
        $result = $sut->method();

        // Assert
        $this->assertEquals($expected, $result);
    }
}
```

### 推奨アサーション
- `assertEquals`: 値の等価性
- `assertSame`: 型と値の同一性
- `assertTrue/assertFalse`: 真偽値
- `assertNull/assertNotNull`: null チェック
- `assertInstanceOf`: 型チェック
- `expectException`: 例外のテスト

### モック
```php
$mock = $this->createMock(DependencyInterface::class);
$mock->method('someMethod')->willReturn($value);
```
PROMPT;
    }

    private static function jestGuide(): string
    {
        return <<<'PROMPT'
## Jest ガイド

### 基本構造
```typescript
describe('TargetClass', () => {
  describe('method', () => {
    it('should return expected when condition', () => {
      // Arrange
      const sut = new TargetClass();

      // Act
      const result = sut.method();

      // Assert
      expect(result).toBe(expected);
    });
  });
});
```

### 推奨アサーション
- `toBe`: プリミティブ値の等価性
- `toEqual`: オブジェクトの深い等価性
- `toBeNull/toBeDefined`: null/undefined チェック
- `toThrow`: 例外のテスト
- `toHaveBeenCalledWith`: モック呼び出しの検証

### モック
```typescript
const mockFn = jest.fn().mockReturnValue(value);
jest.spyOn(object, 'method').mockImplementation(() => value);
```
PROMPT;
    }

    private static function vitestGuide(): string
    {
        return <<<'PROMPT'
## Vitest ガイド

### 基本構造
```typescript
import { describe, it, expect, vi } from 'vitest';

describe('TargetClass', () => {
  it('should return expected when condition', () => {
    // Arrange
    const sut = new TargetClass();

    // Act
    const result = sut.method();

    // Assert
    expect(result).toBe(expected);
  });
});
```

### 推奨アサーション
- Jestと同様のAPI

### モック
```typescript
const mockFn = vi.fn().mockReturnValue(value);
vi.spyOn(object, 'method').mockImplementation(() => value);
```
PROMPT;
    }

    private static function pytestGuide(): string
    {
        return <<<'PROMPT'
## pytest ガイド

### 基本構造
```python
import pytest
from target_module import TargetClass

class TestTargetClass:
    def test_method_condition_expected(self):
        # Arrange
        sut = TargetClass()

        # Act
        result = sut.method()

        # Assert
        assert result == expected
```

### 推奨アサーション
- `assert`: 基本的なアサーション
- `pytest.raises`: 例外のテスト

### フィクスチャ
```python
@pytest.fixture
def sample_data():
    return {"key": "value"}

def test_with_fixture(sample_data):
    assert sample_data["key"] == "value"
```

### モック
```python
from unittest.mock import Mock, patch

@patch('module.ClassName')
def test_with_mock(mock_class):
    mock_class.return_value.method.return_value = value
```
PROMPT;
    }

    private static function goTestingGuide(): string
    {
        return <<<'PROMPT'
## Go testing ガイド

### 基本構造
```go
func TestMethod_Condition_Expected(t *testing.T) {
    // Arrange
    sut := NewTargetStruct()

    // Act
    result := sut.Method()

    // Assert
    if result != expected {
        t.Errorf("got %v, want %v", result, expected)
    }
}
```

### テーブル駆動テスト
```go
func TestMethod(t *testing.T) {
    tests := []struct {
        name     string
        input    string
        expected string
    }{
        {"case1", "input1", "expected1"},
        {"case2", "input2", "expected2"},
    }

    for _, tt := range tests {
        t.Run(tt.name, func(t *testing.T) {
            result := Method(tt.input)
            if result != tt.expected {
                t.Errorf("got %v, want %v", result, tt.expected)
            }
        })
    }
}
```

### モック（インターフェース利用）
```go
type MockDependency struct{}

func (m *MockDependency) Method() string {
    return "mocked"
}
```
PROMPT;
    }

    private static function getLanguageGuide(string $language): string
    {
        return match (strtolower($language)) {
            'php' => self::phpTestingTips(),
            'typescript', 'ts' => self::typescriptTestingTips(),
            'go' => self::goTestingTips(),
            'python' => self::pythonTestingTips(),
            default => '',
        };
    }

    private static function phpTestingTips(): string
    {
        return <<<'PROMPT'
## PHP テストの注意点

- DateTimeのテストでは Clock パターンやモックを使用
- 外部APIはインターフェースでモック化
- データベーステストは RefreshDatabase トレイトを活用
- privateメソッドは直接テストせず、publicメソッド経由でテスト
PROMPT;
    }

    private static function typescriptTestingTips(): string
    {
        return <<<'PROMPT'
## TypeScript テストの注意点

- 型のテストには ts-expect を活用
- 非同期処理は async/await でテスト
- DOM操作は @testing-library を使用
- モジュールモックは jest.mock() の hoisting に注意
PROMPT;
    }

    private static function goTestingTips(): string
    {
        return <<<'PROMPT'
## Go テストの注意点

- テーブル駆動テストを基本とする
- インターフェースを使ったDIでモック化を容易に
- t.Parallel() で並列実行可能なテストを明示
- testify/assert を使うとアサーションが書きやすい
PROMPT;
    }

    private static function pythonTestingTips(): string
    {
        return <<<'PROMPT'
## Python テストの注意点

- フィクスチャでテストデータを共有
- parametrize で複数ケースを簡潔にテスト
- conftest.py でフィクスチャを共有
- freezegun で時刻のテストを制御
PROMPT;
    }
}
