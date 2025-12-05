---
name: test_language_typescript
category: functions
description: TypeScriptでテストを書く際の注意点
---

## TypeScript テストの注意点

- 型のテストには ts-expect を活用
- 非同期処理は async/await でテスト
- DOM操作は @testing-library を使用
- モジュールモックは jest.mock() の hoisting に注意
