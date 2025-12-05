---
name: test_language_go
category: functions
description: Goでテストを書く際の注意点
---

## Go テストの注意点

- テーブル駆動テストを基本とする
- インターフェースを使ったDIでモック化を容易に
- t.Parallel() で並列実行可能なテストを明示
- testify/assert を使うとアサーションが書きやすい
