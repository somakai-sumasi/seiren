package functions

import (
	"seiren/internal/domain"
	"seiren/internal/promptloader"
)

func GenerateRefactoringSuggestion(code, context, perspective string, focuses []string) string {
	loader := promptloader.Get()

	if len(focuses) == 0 {
		focuses = domain.DefaultFocuses()
	}

	resolved := domain.ResolveFocuses(focuses)
	corePrompt := buildPromptFromFocuses(resolved.Core, loader)
	outputFormat := domain.OutputFormatsForRefactoring(loader)

	contextSection := ""
	if context != "" {
		contextSection = loader.RenderTemplate("functions/refactoring-suggestion/context", map[string]string{
			"context": context,
		})
	}

	perspectivePrompt := ""
	if perspective != "" {
		if p, ok := domain.PerspectiveFromAlias(perspective); ok {
			perspectivePrompt = loader.GetContent(p.PromptPath())
		}
	}

	targetCode := buildRefactoringTargetCode(code, loader)

	return loader.RenderTemplate("functions/refactoring-suggestion/base", map[string]string{
		"corePrompt":        corePrompt,
		"perspectivePrompt": perspectivePrompt,
		"contextSection":    contextSection,
		"outputFormat":      outputFormat,
		"targetCode":        targetCode,
	})
}

func buildRefactoringTargetCode(code string, loader *promptloader.Loader) string {
	if code == "" {
		return loader.GetContent("functions/refactoring-suggestion/target-code-fallback")
	}
	return loader.RenderTemplate("functions/refactoring-suggestion/target-code", map[string]string{
		"code": code,
	})
}
