# Seiren

コード品質改善のためのプロンプトを提供するMCPサーバー

「精錬」- コードを精錬し、より良い設計へ導く

## 概要

Modifiusを参考に、変更容易性を高めるプロンプト群を提供するMCPサーバーです。
AIエージェント（Cursor、Claude Code等）と連携し、コードの品質改善を支援します。

### 参考プロジェクト

- [Modifius - Speaker Deck](https://speakerdeck.com/minodriven/modifius)
- [Modifius解説記事](https://levtech.jp/media/article/column/detail_759/)

## 主要機能

| プロンプト名             | 説明                                    |
| ------------------------ | --------------------------------------- |
| `analyze_technical_debt` | 技術的負債を分析                        |
| `suggest_refactoring`    | 設計改善案をMermaidクラス図とともに提案 |
| `generate_test_code`     | 高品質なテストコード生成                |

### 特徴

- **設計原則に基づいた分析**: カプセル化、関心の分離、ドメインモデル完全性等
- **欠陥スコアリング**: 独自計算式に基づくスコアリング
- **具体的な行番号の指摘**: 例: `(13, 24-26, 33-35行目)`
- **設計観点の選択**: DDD、Laravel、Clean Architecture
- **複数言語対応**: PHP、TypeScript

## 技術スタック

- Go 1.25+
- [mcp-go](https://github.com/mark3labs/mcp-go) v0.44.1

## インストール

```bash
git clone https://github.com/your-username/seiren.git
cd seiren
go build -o seiren ./
```

## 使い方

### Claude Code での利用

`~/.claude.json` に追加:

```json
{
  "mcpServers": {
    "seiren": {
      "command": "/path/to/seiren/seiren"
    }
  }
}
```

## コマンド

```bash
# ビルド
go build -o seiren ./

# サーバー起動（テスト用）
./seiren
```

## ディレクトリ構成

```
├── main.go                     # エントリーポイント
├── go.mod
├── prompts/                    # プロンプト定義（Markdownファイル）
│   ├── core/                   # コアプロンプト
│   ├── antipatterns/           # アンチパターン検出
│   ├── output-formats/         # 出力フォーマット
│   ├── perspectives/           # 設計観点（DDD, Laravel等）
│   ├── languages/              # 言語固有（PHP, TypeScript等）
│   └── functions/              # 機能別プロンプト
├── domain/                     # ドメイン型定義（Focus, Language等）
├── functions/                  # プロンプト生成ロジック
├── promptloader/               # Markdownファイル読み込み・キャッシュ
└── tools/                      # MCPツール定義
```

## プロンプトのカスタマイズ

プロンプトはMarkdown + YAML Front Matter形式で外部ファイル化されています。
Goコードを編集せずにプロンプトの内容を変更可能です。

### ファイル形式

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

### 編集方法

1. `prompts/` 内の該当Markdownファイルを直接編集
2. Goコードの変更は不要
3. Front Matter（`---`で囲まれた部分）はメタデータ、それ以下が実際のプロンプト内容
