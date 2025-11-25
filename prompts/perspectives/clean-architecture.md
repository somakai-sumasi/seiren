---
name: clean-architecture
category: perspective
description: Clean Architecture の設計観点
---

# 追加の設計観点: Clean Architecture

以下の観点も考慮して分析してください：

## Clean Architectureの原則
- **依存性のルール**: 外側から内側への依存のみ許可
- **Entity**: ビジネスルールをカプセル化
- **UseCase**: アプリケーション固有のビジネスルール
- **Interface Adapter**: 外部との変換
- **Framework/Driver**: 最も外側の詳細

## Clean Architecture観点での欠陥パターン
- UseCase内でのフレームワーク依存
- Entityからインフラ層への依存
- Interface Adapterでのビジネスロジック実装
- 依存性逆転原則の違反
