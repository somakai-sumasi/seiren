package functions

import (
	"seiren/internal/promptloader"
	"seiren/internal/domain"
)

func GenerateTestCode(code, testFramework, language string) string {
	loader := promptloader.Get()

	frameworkGuide := ""
	if fw, ok := domain.TestFrameworkFromAlias(testFramework); ok {
		path := fw.PromptPath()
		if loader.Exists(path) {
			frameworkGuide = loader.GetContent(path)
		}
	}

	languageGuide := ""
	if language != "" {
		if l, ok := domain.LanguageFromAlias(language); ok {
			path := "functions/test-code-generation/languages/" + string(l)
			if loader.Exists(path) {
				languageGuide = loader.GetContent(path)
			}
		}
	}

	targetCode := buildTestCodeTargetCode(code, loader)

	return loader.RenderTemplate("functions/test-code-generation/base", map[string]string{
		"testFramework":  testFramework,
		"frameworkGuide": frameworkGuide,
		"languageGuide":  languageGuide,
		"targetCode":     targetCode,
	})
}

func buildTestCodeTargetCode(code string, loader *promptloader.Loader) string {
	if code == "" {
		return loader.GetContent("functions/test-code-generation/target-code-fallback")
	}
	return loader.RenderTemplate("functions/test-code-generation/target-code", map[string]string{
		"code": code,
	})
}
