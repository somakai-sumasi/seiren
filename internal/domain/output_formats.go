package domain

import (
	"seiren/internal/promptloader"
	"strings"
)

func OutputFormatsAll(loader *promptloader.Loader) string {
	return strings.Join([]string{
		loader.GetContent("output-formats/separation-of-concerns-table"),
		loader.GetContent("output-formats/encapsulation-table"),
		loader.GetContent("output-formats/domain-model-table"),
		loader.GetContent("output-formats/layer-violation-table"),
		loader.GetContent("output-formats/score-summary"),
	}, "\n\n---\n\n")
}

func OutputFormatsForRefactoring(loader *promptloader.Loader) string {
	return strings.Join([]string{
		loader.GetContent("output-formats/refactoring-suggestion-table"),
		loader.GetContent("output-formats/mermaid-class-diagram"),
	}, "\n\n---\n\n")
}
