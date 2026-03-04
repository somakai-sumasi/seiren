package functions

import (
	"seiren/internal/promptloader"
	"seiren/internal/domain"
)

func GenerateRefactoringSuggestion(code, context, perspective string) string {
	loader := promptloader.GetInstance()

	corePrompt := domain.CorePromptsAll(loader)
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
