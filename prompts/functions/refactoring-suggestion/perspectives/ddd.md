---
name: refactoring_perspective_ddd
category: functions
description: DDD観点のリファクタリングガイド
---

# DDD観点でのリファクタリングガイド

## 改善の方向性
- プリミティブ型を値オブジェクトに置き換え
- ビジネスロジックをエンティティ/値オブジェクトに移動
- 集約の境界を明確化
- ユビキタス言語を反映した命名

## 推奨パターン
- Value Object: 不変、等価性で比較
- Entity: 同一性で識別、状態遷移のメソッドを持つ
- Aggregate Root: トランザクション境界、不変条件の保護
- Domain Service: エンティティに属さないドメインロジック
