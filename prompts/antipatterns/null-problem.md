---
name: null-problem
category: antipatterns
description: null問題の定義と検出基準
---

## null問題

### 定義
null問題とは、nullの不適切な使用により、NullPointerExceptionや
予期せぬ動作を引き起こすパターン。

### 検出基準
1. **nullの返却**
   - メソッドがnullを返す可能性がある
   - 「見つからない」をnullで表現

2. **nullの伝播**
   - nullチェックなしでメソッドチェイン
   - nullが複数のレイヤを通過

3. **nullチェックの散在**
   - 同じ変数のnullチェックが複数箇所に存在
   - 防御的なnullチェックの乱用

4. **nullの意味の曖昧さ**
   - nullが「未設定」「存在しない」「エラー」など複数の意味を持つ

### 改善方針
- Null Objectパターンの適用
- Optional/Maybe型の使用
- 例外による異常系の表現
- 空コレクションの返却（コレクションの場合）

### 欠陥の影響
- 実行時エラーの発生
- 防御的コードの増加
- 意図の不明確化
