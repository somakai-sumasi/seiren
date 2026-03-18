---
name: test_language_go
category: functions
description: Goでテストを書く際の注意点
---

## Go テストの注意点

- テーブル駆動テスト（table-driven tests）を基本パターンとする
- `t.Helper()` でヘルパー関数のスタックトレースを見やすくする
- `t.Parallel()` でテストの並行実行を有効化する
- テストフィクスチャは `testdata/` ディレクトリに配置する
- インターフェースを活用してモックを手書きする（外部モックライブラリよりシンプル）
- `-race` フラグで並行処理のデータ競合を検出する
- サブテストは `t.Run()` で構造化する
