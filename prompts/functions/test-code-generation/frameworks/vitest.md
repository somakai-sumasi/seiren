---
name: test_framework_vitest
category: functions
description: Vitestでのテストガイド
---

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
