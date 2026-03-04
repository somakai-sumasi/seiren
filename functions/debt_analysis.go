package functions

import (
	"seiren/promptloader"
	"seiren/domain"
	"strings"
)

func GenerateDebtAnalysis(code, perspective, language string, focuses []string) string {
	loader := promptloader.GetInstance()

	if len(focuses) == 0 {
		focuses = domain.DefaultFocuses()
	}

	resolved := domain.ResolveFocuses(focuses)

	corePrompt := buildPromptFromFocuses(resolved.Core, loader)
	antipatternsPrompt := buildPromptFromFocuses(resolved.Antipatterns, loader)
	outputFormat := domain.OutputFormatsAll(loader)

	perspectivePrompt := ""
	if perspective != "" {
		if p, ok := domain.PerspectiveFromAlias(perspective); ok {
			perspectivePrompt = loader.GetContent(p.PromptPath())
		}
	}

	languagePrompt := ""
	if language != "" {
		if l, ok := domain.LanguageFromAlias(language); ok {
			languagePrompt = loader.GetContent(l.PromptPath())
		}
	}

	targetCode := buildDebtAnalysisTargetCode(code, loader)

	return loader.RenderTemplate("functions/debt-analysis/base", map[string]string{
		"corePrompt":         corePrompt,
		"antipatternsPrompt": antipatternsPrompt,
		"perspectivePrompt":  perspectivePrompt,
		"languagePrompt":     languagePrompt,
		"outputFormat":       outputFormat,
		"targetCode":         targetCode,
	})
}

func buildDebtAnalysisTargetCode(code string, loader *promptloader.Loader) string {
	if code == "" {
		return loader.GetContent("functions/debt-analysis/target-code-fallback")
	}
	return loader.RenderTemplate("functions/debt-analysis/target-code", map[string]string{
		"code": code,
	})
}

func buildPromptFromFocuses(focuses []domain.Focus, loader *promptloader.Loader) string {
	if len(focuses) == 0 {
		return ""
	}

	contents := make([]string, 0, len(focuses))
	for _, f := range focuses {
		path := f.PromptPath()
		if loader.Exists(path) {
			contents = append(contents, loader.GetContent(path))
		}
	}
	return strings.Join(contents, "\n\n---\n\n")
}
