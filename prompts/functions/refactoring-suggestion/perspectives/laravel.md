---
name: refactoring_perspective_laravel
category: functions
description: Laravel観点のリファクタリングガイド
---

# Laravel観点でのリファクタリングガイド

## 改善の方向性
- Controllerのスリム化（UseCaseへの委譲）
- Eloquent ModelからDomain Modelの分離
- FormRequestでのバリデーション集約
- Repositoryパターンの導入

## 推奨構成
```
app/
├── Http/Controllers/     # HTTPハンドリングのみ
├── Http/Requests/        # バリデーション
├── UseCases/             # アプリケーションロジック
├── Domain/               # ドメインモデル
│   ├── Models/           # エンティティ/値オブジェクト
│   └── Services/         # ドメインサービス
└── Infrastructure/       # 永続化・外部連携
    └── Repositories/
```
