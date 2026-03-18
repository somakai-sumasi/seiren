package domain

import "strings"

type Language string

const (
	LanguagePHP        Language = "php"
	LanguageTypeScript Language = "typescript"
	LanguageGo         Language = "go"
	LanguagePython     Language = "python"
)

func LanguageFromAlias(alias string) (Language, bool) {
	switch strings.ToLower(alias) {
	case "php":
		return LanguagePHP, true
	case "typescript", "ts":
		return LanguageTypeScript, true
	case "go", "golang":
		return LanguageGo, true
	case "python", "py":
		return LanguagePython, true
	default:
		return "", false
	}
}

func (l Language) PromptPath() string {
	return "languages/" + string(l)
}
