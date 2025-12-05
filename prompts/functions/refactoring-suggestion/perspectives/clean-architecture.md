---
name: refactoring_perspective_clean_architecture
category: functions
description: Clean Architecture観点のリファクタリングガイド
---

# Clean Architecture観点でのリファクタリングガイド

## 改善の方向性
- 依存の方向を内側に向ける
- フレームワーク依存の分離
- インターフェースによる依存性逆転
- UseCaseの単一責任化

## 推奨構成
```
src/
├── Domain/              # Enterprise Business Rules
│   ├── Entities/
│   └── ValueObjects/
├── Application/         # Application Business Rules
│   ├── UseCases/
│   └── Interfaces/      # Ports (Input/Output)
├── Adapter/             # Interface Adapters
│   ├── Controllers/
│   ├── Presenters/
│   └── Gateways/
└── Infrastructure/      # Frameworks & Drivers
    ├── Persistence/
    └── External/
```
