---
name: refactoring-suggestion-table
category: output-format
description: 設計改善提案テーブル形式
---

### 設計改善提案

以下のテーブル形式で出力してください：

| 欠陥 | 解消方法 |
|-----|--------|
| 欠陥の要約 | 具体的な改善アクション |

例：
| 関心の分離 - 武器の耐久性管理 | `Weapon`クラスに`canAttack()`メソッドと`consumeDurability(Damage)`メソッドを追加し、耐久性に関するロジックを`Weapon`クラス内にカプセル化する |
| カプセル化 - ダメージ計算 | `Damage`クラスを作成し、`Member`、`Weapon`、`Enemy`、`SpecialGauge`から必要な情報を取得してダメージを計算する。計算ロジックを`Damage`クラス内に隠蔽する |
