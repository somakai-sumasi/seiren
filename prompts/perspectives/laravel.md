---
name: laravel
category: perspective
description: Laravel アーキテクチャの設計観点
---

# 追加の設計観点: Laravel

以下の観点も考慮して分析してください。

## Laravelアーキテクチャの原則

### レイヤー構造

```
Controller → Service/UseCase → Domain Model
    ↓              ↓               ↓
FormRequest    Repository      Value Object
(バリデーション)  (永続化)        (ビジネスルール)
```

- **Controller**: HTTPリクエスト/レスポンスの処理のみ。ビジネスロジックを含まない
- **FormRequest**: バリデーションの責務。`toDto()` メソッドでDTOへ変換
- **Service/UseCase**: ビジネスロジックの調整。単一目的のActionクラスを推奨
- **Model**: Eloquentは永続化担当。ドメインロジックは別クラスへ分離
- **Repository**: Eloquent操作の抽象化。テスト容易性の確保

## Laravel観点での欠陥パターン

- **Fat Controller**: Controllerにビジネスルール、条件分岐、直接的なDB操作が混在。Action/UseCaseに委譲すべき
- **God Model**: Eloquent Modelにビジネスメソッドが肥大化し関心の分離が崩壊
- **Facadeの乱用**: 暗黙的な依存関係を生む。コンストラクタインジェクションを優先する
- **N+1クエリ問題**: リレーションのEager Loading (`with()`) 欠如によるループ内追加クエリ
- **ControllerでのEloquent直接操作**: Repositoryを介さず直接操作するとテスト容易性と変更容易性が低下
- **FormRequest内のビジネスルール**: バリデーションを超えた処理（在庫チェック、権限判定など）は関心の分離を崩す
- **Route Model Binding のスコープ欠如**: ネストルートで `scopeBindings()` を使わないとクロステナントアクセスや認可バイパスのリスク
- **Query Object パターンの欠如**: 複雑なクエリフィルタがControllerやServiceに散在すると再利用困難で変更容易性が低下。Query Objectクラスにフィルタロジックを集約する

## 改善の方向性

- Controllerのスリム化（Action/UseCaseへの委譲）
- Eloquent ModelからDomain Modelの分離
- FormRequestでのバリデーション集約とDTO変換
- Repositoryパターンの導入でEloquent操作を抽象化
- サービスコンテナのインターフェースバインディングで依存を逆転
