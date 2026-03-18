---
name: test_language_python
category: functions
description: Pythonでテストを書く際の注意点
---

## Python テストの注意点

- pytestを標準のテストフレームワークとして使用する
- fixtureでテストデータやセットアップを共有する
- `pytest.mark` でunit / integrationテストを分類する
- `@dataclass(frozen=True)` のテストでは不変性を検証する
- 外部APIはfixtureまたは `unittest.mock.patch` でモック化する
- `pytest.raises` でエラーケースを検証する
- カバレッジは `pytest --cov` で計測する
