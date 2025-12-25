---
name: php
category: language
description: PHP 言語固有の観点
---

# 言語固有の観点: PHP

## PHP特有の欠陥パターン

### ファイル末尾の `?>`
PHPの終了タグ `?>` 以降の空白や改行は出力されてしまう。HTTPヘッダー送信後の出力エラーやJSONパース失敗の原因となる。

```php
// 悪い例
<?php
class Foo {}
?>
 ← この空白が出力される

// 良い例: 終了タグを省略
<?php
class Foo {}
```

### `array()` 構文の使用
PHP 5.4以降は短縮構文 `[]` が使用可能。冗長な `array()` は可読性を下げる。

```php
// 悪い例
$data = array('key' => array('nested' => 'value'));

// 良い例
$data = ['key' => ['nested' => 'value']];
```

### リファレンス `&` の多用
「パフォーマンス向上のため」という理由でリファレンスを多用するのは誤り。PHPはCopy-on-Writeを採用しており、不要なリファレンスは可読性と安全性を低下させる。

```php
// 悪い例: 不要なリファレンス
function process(&$data) {
    return $data['value'] * 2;
}

// 良い例: 値渡しで十分
function process(array $data): int {
    return $data['value'] * 2;
}
```

### `namespace` を利用しない
グローバル名前空間にクラスを配置すると、名前衝突のリスクが高まる。Composerオートローダーとの連携も困難になる。

### `require`/`include` の直接記述
ファイルごとに依存関係を直接記述すると保守性が低下する。Composerのオートローダー（PSR-4）を活用すべき。

### `error_reporting(~E_NOTICE)` によるエラー抑制
未定義変数へのアクセスを許容するとバグの温床になる。null合体演算子 `??` を使用して明示的に処理する。

```php
// 悪い例
error_reporting(~E_NOTICE);
$x = $ary['x'];  // 未定義でもエラーにならない

// 良い例
$x = $ary['x'] ?? null;
```

### その他の欠陥パターン
- 型宣言の欠如（mixed型の乱用）
- publicプロパティの乱用
- staticメソッドの過剰使用
- 配列の代わりにオブジェクトを使うべき箇所
- Null許容型の不適切な使用
