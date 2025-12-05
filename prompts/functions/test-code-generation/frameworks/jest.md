---
name: test_framework_jest
category: functions
description: Jestでのテストガイド
---

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
