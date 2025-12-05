---
name: test_framework_phpunit
category: functions
description: PHPUnitでのテストガイド
---

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
