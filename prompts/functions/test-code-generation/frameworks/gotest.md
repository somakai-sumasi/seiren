---
name: gotest
category: test-framework
description: Go標準テストフレームワークの規約
---

## Go Test フレームワーク規約

### テーブル駆動テスト

```go
func TestAdd(t *testing.T) {
    tests := []struct {
        name     string
        a, b     int
        expected int
    }{
        {"正の数同士", 1, 2, 3},
        {"ゼロを含む", 0, 5, 5},
        {"負の数", -1, -2, -3},
    }

    for _, tt := range tests {
        t.Run(tt.name, func(t *testing.T) {
            got := Add(tt.a, tt.b)
            if got != tt.expected {
                t.Errorf("Add(%d, %d) = %d, want %d", tt.a, tt.b, got, tt.expected)
            }
        })
    }
}
```

### テストヘルパー

```go
func newTestUser(t *testing.T, name string) *User {
    t.Helper()
    user, err := NewUser(name)
    if err != nil {
        t.Fatalf("failed to create test user: %v", err)
    }
    return user
}
```

### ファイル命名規則

- テストファイル: `xxx_test.go`（同一パッケージ）
- ブラックボックステスト: `package xxx_test`（外部パッケージ）

### 実行コマンド

```bash
go test ./...
go test -race ./...
go test -cover ./...
go test -v -run TestSpecific ./pkg/...
```
