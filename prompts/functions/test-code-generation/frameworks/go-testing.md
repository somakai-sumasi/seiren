---
name: test_framework_go_testing
category: functions
description: Go testingでのテストガイド
---

## Go testing ガイド

### 基本構造
```go
func TestMethod_Condition_Expected(t *testing.T) {
    // Arrange
    sut := NewTargetStruct()

    // Act
    result := sut.Method()

    // Assert
    if result != expected {
        t.Errorf("got %v, want %v", result, expected)
    }
}
```

### テーブル駆動テスト
```go
func TestMethod(t *testing.T) {
    tests := []struct {
        name     string
        input    string
        expected string
    }{
        {"case1", "input1", "expected1"},
        {"case2", "input2", "expected2"},
    }

    for _, tt := range tests {
        t.Run(tt.name, func(t *testing.T) {
            result := Method(tt.input)
            if result != tt.expected {
                t.Errorf("got %v, want %v", result, tt.expected)
            }
        })
    }
}
```

### モック（インターフェース利用）
```go
type MockDependency struct{}

func (m *MockDependency) Method() string {
    return "mocked"
}
```
