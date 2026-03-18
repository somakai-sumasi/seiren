---
name: jest
category: test-framework
description: Jestフレームワークの規約
---

## Jest フレームワーク規約

### 基本構造

```typescript
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
            .toThrow('不正なメールアドレス');
    });
});
```

### モック

```typescript
// モジュールモック（ファイルトップでhoistingされる）
jest.mock('./api', () => ({
    fetchUser: jest.fn().mockResolvedValue({ id: '1', name: 'Alice' }),
}));

// 手動モック
const mockRepository = {
    findById: jest.fn(),
    save: jest.fn(),
};
```

### ファイル命名規則

- テストファイル: `xxx.test.ts` または `xxx.spec.ts`
- `jest.mock()` はhoistingされるため、ファイル先頭で呼ぶ

### 実行コマンド

```bash
jest
jest --watch
jest --coverage
jest --testPathPattern="user"
```
