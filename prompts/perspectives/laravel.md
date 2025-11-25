---
name: laravel
category: perspective
description: Laravel アーキテクチャの設計観点
---

# 追加の設計観点: Laravel

以下の観点も考慮して分析してください：

## Laravelアーキテクチャの原則
- **Controller**: HTTPリクエスト/レスポンスの処理のみ
- **FormRequest**: バリデーションの責務
- **Service/UseCase**: ビジネスロジックの調整
- **Model**: Eloquentは永続化担当、ドメインロジックは別クラスへ
- **Repository**: Eloquent操作の抽象化

## Laravel観点での欠陥パターン
- Controllerにビジネスロジック
- Eloquent Modelにビジネスロジックが過剰に実装
- Facadeの乱用による結合度上昇
- ControllerでのEloquent直接操作
- FormRequest内でのビジネスルール実装
