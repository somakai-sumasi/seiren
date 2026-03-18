package domain

import "strings"

type TestFramework string

const (
	TestFrameworkPHPUnit  TestFramework = "phpunit"
	TestFrameworkGoTest   TestFramework = "gotest"
	TestFrameworkPytest   TestFramework = "pytest"
	TestFrameworkVitest   TestFramework = "vitest"
	TestFrameworkJest     TestFramework = "jest"
)

func TestFrameworkFromAlias(alias string) (TestFramework, bool) {
	switch strings.ToLower(alias) {
	case "phpunit":
		return TestFrameworkPHPUnit, true
	case "gotest", "go test", "go_test":
		return TestFrameworkGoTest, true
	case "pytest":
		return TestFrameworkPytest, true
	case "vitest":
		return TestFrameworkVitest, true
	case "jest":
		return TestFrameworkJest, true
	default:
		return "", false
	}
}

func (t TestFramework) PromptPath() string {
	return "functions/test-code-generation/frameworks/" + string(t)
}
