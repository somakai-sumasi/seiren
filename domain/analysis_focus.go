package domain

type ResolvedFocuses struct {
	Core         []Focus
	Antipatterns []Focus
}

func ResolveFocuses(inputs []string) ResolvedFocuses {
	resolved := ResolvedFocuses{
		Core:         []Focus{FocusDefectScoring},
		Antipatterns: []Focus{},
	}

	for _, input := range inputs {
		if group, ok := FocusGroupFromString(input); ok {
			for _, f := range group.Focuses() {
				addFocusToCategory(f, &resolved)
			}
			continue
		}
		if f, ok := FocusFromString(input); ok {
			addFocusToCategory(f, &resolved)
		}
	}

	resolved.Core = uniqueFocuses(resolved.Core)
	resolved.Antipatterns = uniqueFocuses(resolved.Antipatterns)

	return resolved
}

func DefaultFocuses() []string {
	return []string{string(FocusGroupBasic)}
}

func addFocusToCategory(f Focus, resolved *ResolvedFocuses) {
	if f.IsCore() {
		resolved.Core = append(resolved.Core, f)
	} else {
		resolved.Antipatterns = append(resolved.Antipatterns, f)
	}
}

func uniqueFocuses(focuses []Focus) []Focus {
	seen := make(map[Focus]bool)
	result := make([]Focus, 0, len(focuses))
	for _, f := range focuses {
		if !seen[f] {
			seen[f] = true
			result = append(result, f)
		}
	}
	return result
}
