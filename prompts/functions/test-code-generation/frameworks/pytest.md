---
name: test_framework_pytest
category: functions
description: pytestでのテストガイド
---

## pytest ガイド

### 基本構造
```python
import pytest
from target_module import TargetClass

class TestTargetClass:
    def test_method_condition_expected(self):
        # Arrange
        sut = TargetClass()

        # Act
        result = sut.method()

        # Assert
        assert result == expected
```

### 推奨アサーション
- `assert`: 基本的なアサーション
- `pytest.raises`: 例外のテスト

### フィクスチャ
```python
@pytest.fixture
def sample_data():
    return {"key": "value"}

def test_with_fixture(sample_data):
    assert sample_data["key"] == "value"
```

### モック
```python
from unittest.mock import Mock, patch

@patch('module.ClassName')
def test_with_mock(mock_class):
    mock_class.return_value.method.return_value = value
```
