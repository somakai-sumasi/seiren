---
name: test_language_php
category: functions
description: PHPでテストを書く際の注意点
---

## PHP テストの注意点

- DateTimeのテストでは Clock パターンやモックを使用
- 外部APIはインターフェースでモック化
- データベーステストは RefreshDatabase トレイトを活用
- privateメソッドは直接テストせず、publicメソッド経由でテスト
