# MCP Prompts Server

コード品質改善のためのプロンプトを提供するMCPサーバー

## 参考プロジェクト: Modifius

- https://speakerdeck.com/minodriven/modifius
- https://levtech.jp/media/article/column/detail_759/

### Modifiusとは

DMM.comが開発したMCPサーバー。「変更容易性（modifiability）を司る者」という意味。
AIエージェント（Cursor、Claude Code等）と連携し、コードの品質改善を支援する。

### Modifiusの本質

**「変更容易性を高めるプロンプト群」**

- AIは学習データの平均に収斂する性質があり、ネット上の低品質コードも学習している
- 高精度な回答を引き出すには「適切なプロンプト」が必須
- 著書『良いコード／悪いコードで学ぶ設計入門』の設計原則がベース
- MCPサーバーを選択した理由: 特定のAIエージェントに依存せず横断的に機能提供するため

### 分析観点（静的解析ツールとの違い）

| 一般的な静的解析ツール | Modifiusの分析観点 |
|----------------------|-------------------|
| コード行数 | カプセル化 |
| サイクロマチック複雑度 | 関心の分離 |
| コード重複 | ドメインモデル完全性 |
| 引数の数 etc.. | 技術レイヤ間の関心の分離 |
| | 各設計パターン毎の設計要件 |
| | interface設計 etc.. |

静的解析ツールは「コードの意図」がわからない。生成AIは意図を推論できる。

### 負債分析の出力形式

**関心の分離の欠陥:**

| 欠陥モジュール | 分離すべき関心（目的） | 欠陥の理由 | 欠陥行数カウント | 欠陥スコア |
|--------------|---------------------|----------|---------------|----------|
| AttackUseCaseクラス | 武器の耐久性管理 | 攻撃処理の目的を持つUseCaseに、武器の耐久性という別の関心事が混在している (13, 24-26, 33-35行目) | 6 | 1 |

**カプセル化の欠陥:**

| 目的 | カプセル化すべきコード | 欠陥の理由 | 欠陥行数カウント | 欠陥スコア |
|-----|---------------------|----------|---------------|----------|
| ダメージ計算 | member.armPower、weapon.power、enemy.defenceのフィールド直接アクセス (14行目) | ダメージ計算に必要なデータとロジックが各クラスに散在。計算式がUseCaseに露出している | 9 | 1 |

### 設計改善提案の出力形式

| 欠陥 | 解消方法 |
|-----|--------|
| 関心の分離 - 武器の耐久性管理 | `Weapon`クラスに`canAttack()`メソッドと`consumeDurability(Damage)`メソッドを追加し、耐久性に関するロジックを`Weapon`クラス内にカプセル化する |
| カプセル化 - ダメージ計算 | `Damage`クラスを作成し、`Member`、`Weapon`、`Enemy`、`SpecialGauge`から必要な情報を取得してダメージを計算する。計算ロジックを`Damage`クラス内に隠蔽する |

※ Mermaidクラス図も出力して設計改善を提案

### モジュール化されたプロンプト構造

```
機能
├── 負債分析
├── 設計改善提案
└── テストコード実装
        │
    コアプロンプト（心臓部分）
    ※ 変更容易性に関する基盤的考え方を定義
        │
    ┌───┴───┐
設計観点        対応言語
├─DDD           ├─PHP
├─Laravel       └─TypeScript
└─Clean Architecture
```

### 主要機能

1. **技術的負債分析**
   - 静的解析ツールとは異なり、コードの意図を推論できる
   - カプセル化や関心の分離といった設計原則に基づいた分析
   - 独自計算式に基づく欠陥スコアリング
   - 具体的な行番号の指摘
   - 約400行のコード分析が20秒程度（目視では15〜30分）

2. **設計改善提案**
   - Mermaidクラス図を含む改善案を提示
   - ビジネスロジックの適切な配置
   - ドメインモデルの構築支援
   - 具体的なクラス/メソッド名の提案

3. **テストコード実装支援**
   - 高品質なテストコード生成

### 実績

- 分析速度: 目視の40倍以上
- リファクタリング結果: 645行 → 345行へスリム化

### 将来計画（参考）

- **自動リファクタリング機能**（目玉機能）
- ドメインモデリング支援
- 高品質コード実装
- 複数プログラミング言語対応
- AIエージェント化による一般公開

## このプロジェクトの目標

Modifiusを参考に、変更容易性を高めるプロンプト群を提供するMCPサーバーを構築する。

### 実装予定のプロンプト

| プロンプト名 | 状態 | 説明 |
|-------------|------|------|
| `analyze_technical_debt` | 実装済(精査中) | 技術的負債を分析 |
| `suggest_refactoring` | 実装済(精査中) | 設計改善案をMermaidクラス図とともに提案 |
| `generate_test_code` | 実装済(精査中) | 高品質なテストコード生成 |

### 改善目標（Modifiusとの差分）

| 機能 | 状態 | 説明 |
|------|------|------|
| 欠陥スコアリング機能 | 実装済 | 独自計算式に基づくスコアリング、欠陥行数カウント |
| 具体的な行番号の指摘 | 実装済 | 例: `(13, 24-26, 33-35行目)` |
| 表形式での出力フォーマット | 実装済 | 関心の分離、カプセル化等の欠陥テーブル |
| 設計観点のモジュール化 | 実装済 | DDD、Laravel、Clean Architecture選択可能 |
| 言語別プロンプト | 実装済 | PHP、TypeScript対応 |
| Mermaidクラス図の出力形式 | 実装済 | 改善後のドメインモデル図 |
| プロンプト外部ファイル化 | 実装済 | Markdown形式で編集可能 |

### 設計方針

- **モジュール化**: 柔軟な拡張性を持たせる
- **複数AIエージェント対応**: CursorやClaude Code等で利用可能
- **言語・アーキテクチャの追加容易性**: 将来の拡張を見据えた設計

### プロンプト外部ファイル化（実装済み）

プロンプトはMarkdown + YAML Front Matter形式で外部ファイル化されている。
PHPコードを編集せずにプロンプトの内容を変更可能。

```
prompts/
├── core/                        # コアプロンプト（変更容易性の基盤知識）
│   ├── encapsulation.md         # カプセル化
│   ├── separation-of-concerns.md # 関心の分離
│   ├── domain-model-completeness.md # ドメインモデル完全性
│   ├── layer-separation.md      # レイヤ分離
│   ├── interface-design.md      # interface設計
│   ├── defect-scoring.md        # 欠陥スコアリング基準
│   ├── cohesion.md              # 凝集度
│   ├── coupling.md              # 結合度
│   ├── conditional-branching.md # 条件分岐
│   ├── immutability.md          # 不変性
│   └── naming.md                # 命名
├── antipatterns/                # アンチパターン検出
│   ├── dead-code.md             # デッドコード
│   ├── exception-abuse.md       # 例外の乱用
│   ├── god-class.md             # 神クラス
│   ├── half-baked-object.md     # 半端なオブジェクト
│   ├── magic-number.md          # マジックナンバー
│   ├── method-chain.md          # メソッドチェーン
│   └── null-problem.md          # null問題
├── output-formats/              # 出力フォーマット定義
│   ├── separation-of-concerns-table.md
│   ├── encapsulation-table.md
│   ├── domain-model-table.md
│   ├── layer-violation-table.md
│   ├── refactoring-suggestion-table.md
│   ├── mermaid-class-diagram.md
│   └── score-summary.md
├── perspectives/                # 設計観点別プロンプト
│   ├── ddd.md                   # Domain-Driven Design
│   ├── laravel.md               # Laravel
│   └── clean-architecture.md    # Clean Architecture
├── languages/                   # 言語固有の観点
│   ├── php.md
│   └── typescript.md
└── functions/                   # 機能別プロンプト
    ├── debt-analysis/
    │   └── base.md              # 負債分析ベース
    ├── refactoring-suggestion/
    │   ├── base.md              # リファクタリング提案ベース
    │   ├── context.md           # コンテキスト定義
    │   └── perspectives/        # 観点別
    │       ├── ddd.md
    │       ├── laravel.md
    │       └── clean-architecture.md
    └── test-code-generation/
        ├── base.md              # テストコード生成ベース
        ├── frameworks/
        │   └── phpunit.md       # PHPUnit用
        └── languages/
            ├── php.md
            └── typescript.md
```

### プロンプトファイル形式

```markdown
---
name: encapsulation
category: core
description: カプセル化の定義と判断基準
---

## カプセル化の定義と判断基準

### 定義
カプセル化とは、関連するデータとそれを操作するロジックを...
```

### プロンプトの編集方法

1. `prompts/` 内の該当Markdownファイルを直接編集
2. PHPコードの変更は不要
3. Front Matter（`---`で囲まれた部分）はメタデータ、それ以下が実際のプロンプト内容

## 技術スタック

- PHP 8.2+
- mcp/sdk v0.1
- PHPStan (静的解析)

## コマンド

```bash
# 依存関係インストール
composer install

# 静的解析
php -d memory_limit=512M ./vendor/bin/phpstan analyse src server.php --level=max

# サーバー起動（テスト用）
php server.php
```

## ディレクトリ構成

```
├── server.php                  # エントリーポイント
├── prompts/                    # プロンプト定義（Markdownファイル）
│   ├── core/                   # コアプロンプト
│   ├── antipatterns/           # アンチパターン検出
│   ├── output-formats/         # 出力フォーマット
│   ├── perspectives/           # 設計観点（DDD, Laravel等）
│   ├── languages/              # 言語固有（PHP, TypeScript等）
│   └── functions/              # 機能別プロンプト
├── src/
│   ├── PromptLoader.php        # Markdownプロンプト読み込み
│   ├── CodeQualityPrompts.php  # MCPプロンプト定義
│   └── Prompts/
│       ├── AnalysisFocus.php       # 分析フォーカス定義
│       ├── Core/
│       │   ├── CorePrompts.php     # コアプロンプト呼び出し
│       │   └── OutputFormats.php   # 出力フォーマット呼び出し
│       ├── Enums/                  # 列挙型定義
│       │   ├── Focus.php           # フォーカス種別
│       │   ├── FocusGroup.php      # フォーカスグループ
│       │   ├── Language.php        # 対応言語
│       │   ├── Perspective.php     # 設計観点
│       │   └── TestFramework.php   # テストフレームワーク
│       └── Functions/
│           ├── DebtAnalysis.php           # 技術的負債分析
│           ├── RefactoringSuggestion.php  # リファクタリング提案
│           └── TestCodeGeneration.php     # テストコード生成
├── composer.json
└── vendor/
```

## MCP SDK の使い方

### プロンプト定義

```php
use Mcp\Capability\Attribute\McpPrompt;
use Mcp\Schema\Content\PromptMessage;
use Mcp\Schema\Content\TextContent;
use Mcp\Schema\Enum\Role;

/**
 * @return list<PromptMessage>
 */
#[McpPrompt(name: 'prompt_name', description: '説明')]
public function myPrompt(string $arg): array
{
    return [
        new PromptMessage(
            role: Role::User,
            content: new TextContent(text: 'プロンプト内容')
        )
    ];
}
```

### 名前空間

- サーバー: `Mcp\Server`
- トランスポート: `Mcp\Server\Transport\StdioTransport`
- 属性: `Mcp\Capability\Attribute\McpPrompt`
- スキーマ: `Mcp\Schema\Content\*`, `Mcp\Schema\Enum\*`

## Claude Code での利用

`~/.claude.json` に追加:

```json
{
  "mcpServers": {
    "code-quality": {
      "command": "php",
      "args": ["/Users/hoge/mcp-prompts-server/server.php"]
    }
  }
}
```
