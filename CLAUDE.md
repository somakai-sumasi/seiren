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
├─DDD           ├─Go
├─Spring Boot   ├─Java
├─Container     ├─Kotlin
│ Presentation  ├─PHP
├─Laravel       ├─Python
├─React         ├─TypeScript
└─Vue           └─C#
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

現在の実装に足りない点:

1. **欠陥スコアリング機能**
   - 独自計算式に基づくスコアリング
   - 欠陥行数カウント

2. **具体的な行番号の指摘**
   - 例: `(13, 24-26, 33-35行目)`

3. **表形式での出力フォーマット**
   - 関心の分離の欠陥テーブル
   - カプセル化の欠陥テーブル

4. **設計観点のモジュール化**
   - コアプロンプト（基盤）
   - DDD、Laravel等を選択可能に
   - 言語別プロンプト（PHP、Go等）

5. **Mermaidクラス図の出力形式指定**
   - 改善後のドメインモデル図

### 設計方針

- **モジュール化**: 柔軟な拡張性を持たせる
- **複数AIエージェント対応**: CursorやClaude Code等で利用可能
- **言語・アーキテクチャの追加容易性**: 将来の拡張を見据えた設計

### 将来の拡張計画

```
src/
├── Prompts/
│   ├── Core/                    # コアプロンプト
│   │   └── CorePrompts.php
│   ├── Functions/               # 機能別
│   │   ├── DebtAnalysis.php
│   │   ├── RefactoringSuggestion.php
│   │   └── TestCodeGeneration.php
│   ├── Perspectives/            # 設計観点別
│   │   ├── DDD.php
│   │   ├── Laravel.php
│   │   └── ContainerPresentation.php
│   └── Languages/               # 言語別
│       ├── PHP.php
│       ├── Go.php
│       └── TypeScript.php
└── CodeQualityPrompts.php       # 現在の統合クラス
```

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
├── server.php              # エントリーポイント
├── src/
│   └── CodeQualityPrompts.php  # プロンプト定義
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
      "args": ["/Users/ueki/for_study/mcp-prompts-server/server.php"]
    }
  }
}
```
