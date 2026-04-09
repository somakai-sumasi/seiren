package domain

import (
	"seiren/internal/promptloader"
	"strings"
)

// focusに対応する出力フォーマットのマッピング
var focusOutputFormats = map[Focus][]string{
	FocusEncapsulation:        {"output-formats/encapsulation-table"},
	FocusSeparationOfConcerns: {"output-formats/separation-of-concerns-table"},
	FocusDomainModel:          {"output-formats/domain-model-table"},
	FocusLayerSeparation:      {"output-formats/layer-violation-table"},
	FocusLayerDataModel:       {"output-formats/layer-violation-table"},
	FocusLayerRepository:      {"output-formats/layer-violation-table"},
	FocusLayerService:         {"output-formats/layer-violation-table"},
}

func OutputFormatsForFocuses(focuses []Focus, loader *promptloader.Loader) string {
	seen := make(map[string]bool)
	var paths []string

	for _, f := range focuses {
		for _, path := range focusOutputFormats[f] {
			if !seen[path] {
				seen[path] = true
				paths = append(paths, path)
			}
		}
	}

	// score-summary は常に含める
	paths = append(paths, "output-formats/score-summary")

	contents := make([]string, 0, len(paths))
	for _, path := range paths {
		if c := loader.GetContent(path); c != "" {
			contents = append(contents, c)
		}
	}
	return strings.Join(contents, "\n\n---\n\n")
}

func OutputFormatsForRefactoring(loader *promptloader.Loader) string {
	return strings.Join([]string{
		loader.GetContent("output-formats/refactoring-suggestion-table"),
		loader.GetContent("output-formats/mermaid-class-diagram"),
	}, "\n\n---\n\n")
}
