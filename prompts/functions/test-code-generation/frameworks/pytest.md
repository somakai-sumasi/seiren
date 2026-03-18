---
name: pytest
category: test-framework
description: pytestフレームワークの規約
---

## pytest フレームワーク規約

### 基本構造

```python
import pytest

class TestUserService:
    def test_create_user_with_valid_data(self, user_service: UserService) -> None:
        user = user_service.create(name="Alice", email="alice@example.com")

        assert user.name == "Alice"
        assert user.email == "alice@example.com"

    def test_create_user_with_invalid_email_raises_error(self, user_service: UserService) -> None:
        with pytest.raises(ValueError, match="不正なメールアドレス"):
            user_service.create(name="Alice", email="invalid")
```

### Fixture

```python
import pytest

@pytest.fixture
def user_repository() -> InMemoryUserRepository:
    return InMemoryUserRepository()

@pytest.fixture
def user_service(user_repository: InMemoryUserRepository) -> UserService:
    return UserService(repository=user_repository)
```

### パラメータ化テスト

```python
@pytest.mark.parametrize("input_val,expected", [
    (1, 2),
    (0, 0),
    (-1, -2),
])
def test_double(input_val: int, expected: int) -> None:
    assert double(input_val) == expected
```

### テスト分類

```python
@pytest.mark.unit
def test_calculate_total() -> None:
    ...

@pytest.mark.integration
def test_database_connection() -> None:
    ...
```

### ファイル命名規則

- テストファイル: `test_xxx.py`
- conftest: `conftest.py`（fixtureの共有）

### 実行コマンド

```bash
pytest
pytest -v
pytest --cov=src --cov-report=term-missing
pytest -m unit
pytest -k "test_create"
```
