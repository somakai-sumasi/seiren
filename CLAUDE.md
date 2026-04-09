# Seiren

コード品質改善のためのプロンプトを提供するMCPサーバー

「精錬」- コードを精錬し、より良い設計へ導く

## 参考プロジェクト

- **Modifius** (DMM.com): 変更容易性を高めるプロンプト群を提供するMCPサーバー
  - https://speakerdeck.com/minodriven/modifius
  - https://levtech.jp/media/article/column/detail_759/
  - 著書『良いコード／悪いコードで学ぶ設計入門』の設計原則がベース
- **everything-claude-code**: 言語別ルール・スキルの構造設計を参考
  - https://github.com/affaan-m/everything-claude-code

## 技術スタック

- Go 1.25+
- [mcp-go](https://github.com/mark3labs/mcp-go) v0.44.1
- [cobra](https://github.com/spf13/cobra) (CLI)

## コマンド

```bash
# ビルド
go build -o seiren ./cmd/cli/
go build -o seiren-mcp ./cmd/mcp/

# テスト
go test ./...

# CLI使用例
./seiren analyze --file example.php --language php --perspective ddd
./seiren analyze --file example.php --language php --focus "basic,antipatterns"
./seiren refactor --file example.php --perspective clean --focus structure
cat example.php | ./seiren test --language php

# MCPサーバー起動（テスト用）
./seiren-mcp
```

## MCP サーバー設定

`~/.claude.json` に追加:

```json
{
  "mcpServers": {
    "seiren": {
      "command": "/path/to/seiren/seiren-mcp"
    }
  }
}
```

## 主要機能

| ツール | 説明 |
|-------|------|
| `analyze_technical_debt` | 技術的負債を分析（欠陥スコアリング、行番号指摘） |
| `suggest_refactoring` | 設計改善案をMermaidクラス図とともに提案 |
| `generate_test_code` | 高品質なテストコード生成 |

## 対応言語・設計観点

| カテゴリ | 対応 |
|---------|------|
| 言語 | PHP, TypeScript, Go, Python |
| 設計観点 | DDD, Laravel, Clean Architecture |
| テストFW | PHPUnit, Jest, Vitest, gotest, pytest |

## ディレクトリ構成

```
├── cmd/
│   ├── cli/                    # CLIエントリーポイント → seiren バイナリ
│   │   └── main.go
│   └── mcp/                    # MCPサーバーエントリーポイント → seiren-mcp バイナリ
│       └── main.go
├── internal/
│   ├── domain/                 # ドメイン型定義（Focus, Language等）
│   ├── functions/              # プロンプト生成ロジック（CLI・MCP共有）
│   ├── promptloader/           # Markdownファイル読み込み・キャッシュ
│   └── mcpserver/              # MCPツール定義
├── prompts/                    # プロンプト定義（Markdownファイル）
│   ├── core/                   # コアプロンプト（変更容易性の基盤知識）
│   ├── antipatterns/           # アンチパターン検出
│   ├── output-formats/         # 出力フォーマット定義
│   ├── perspectives/           # 設計観点（DDD, Laravel, Clean Architecture）
│   ├── languages/              # 言語固有（PHP, TypeScript, Go, Python）
│   └── functions/              # 機能別プロンプト
│       ├── debt-analysis/
│       ├── refactoring-suggestion/
│       └── test-code-generation/
│           ├── frameworks/     # PHPUnit, gotest, pytest, vitest, jest
│           └── languages/      # 言語別テスト注意点
└── go.mod
```

## プロンプト設計方針

### 構造

```
機能（debt-analysis / refactoring / test-code）
    │
コアプロンプト（変更容易性の基盤知識）
    │
┌───┴───┐
設計観点        対応言語
├─DDD           ├─PHP
├─Laravel       ├─TypeScript
└─Clean Arch    ├─Go
                └─Python
```

- プロンプトはMarkdown + YAML Front Matter形式で外部ファイル化
- Goコードを編集せずにプロンプト内容を変更可能
- `prompts/` 内の該当Markdownファイルを直接編集する

### トークン効率の方針

[everything-claude-code](https://github.com/affaan-m/everything-claude-code) の Rules/Skills 2層構造を参考に設計。

- **箇条書き中心**: コード例は最小限に抑え、欠陥パターン名+一行説明で記述
- **コード例を入れる場合**: 悪い例/良い例の対比を各3-5行以内に収める
- **観点の数は維持しつつ文字数を削減**: 1言語プロンプトあたり約2,000字以内を目安
- **分析プロンプト全体**: コア+言語+出力形式で約7,000-8,000字を目安

### 言語プロンプトの観点カテゴリ

各言語プロンプトは以下のカテゴリで統一:
- 型安全性の欠陥
- カプセル化の欠陥
- 不変性の欠陥
- 関心の分離の欠陥
- セキュリティの欠陥
- その他の欠陥パターン（言語固有のイディオム）

### Seirenの分析スコープ

静的解析ツール（gofmt, PHPStan, ESLint等）の領域とは明確に分離する:
- **対象**: カプセル化、関心の分離、ドメインモデル完全性、設計パターンの適用等
- **対象外**: コーディングスタイル規約、フォーマッタ設定、CI/CD設定、テストカバレッジ閾値
