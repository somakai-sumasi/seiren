package domain

import "strings"

type TestFramework string

const (
	TestFrameworkPHPUnit TestFramework = "phpunit"
)

func TestFrameworkFromAlias(alias string) (TestFramework, bool) {
	switch strings.ToLower(alias) {
	case "phpunit":
		return TestFrameworkPHPUnit, true
	default:
		return "", false
	}
}

func (t TestFramework) PromptPath() string {
	return "functions/test-code-generation/frameworks/" + string(t)
}
