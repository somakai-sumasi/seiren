package domain

import "strings"

type Language string

const (
	LanguagePHP        Language = "php"
	LanguageTypeScript Language = "typescript"
)

func LanguageFromAlias(alias string) (Language, bool) {
	switch strings.ToLower(alias) {
	case "php":
		return LanguagePHP, true
	case "typescript", "ts":
		return LanguageTypeScript, true
	default:
		return "", false
	}
}

func (l Language) PromptPath() string {
	return "languages/" + string(l)
}
