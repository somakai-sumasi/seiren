---
name: vitest
category: test-framework
description: Vitestフレームワークの規約
---

## Vitest フレームワーク規約

### 基本構造

```typescript
import { describe, it, expect, vi } from 'vitest';

describe('UserService', () => {
    it('有効なデータでユーザーを作成できる', () => {
        const service = new UserService(mockRepository);
        const user = service.create({ name: 'Alice', email: 'alice@example.com' });

        expect(user.name).toBe('Alice');
        expect(user.email).toBe('alice@example.com');
    });

    it('不正なメールアドレスでエラーになる', () => {
        const service = new UserService(mockRepository);

        expect(() => service.create({ name: 'Alice', email: 'invalid' }))
            .toThrowError('不正なメールアドレス');
    });
});
```

### モック

```typescript
import { vi } from 'vitest';

const mockRepository = {
    findById: vi.fn(),
    save: vi.fn(),
};

// モジュールモック
vi.mock('./api', () => ({
    fetchUser: vi.fn().mockResolvedValue({ id: '1', name: 'Alice' }),
}));
```

### ファイル命名規則

- テストファイル: `xxx.test.ts` または `xxx.spec.ts`

### 実行コマンド

```bash
vitest
vitest run
vitest --coverage
vitest --ui
```
