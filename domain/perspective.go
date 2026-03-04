package domain

import "strings"

type Perspective string

const (
	PerspectiveDDD               Perspective = "ddd"
	PerspectiveLaravel           Perspective = "laravel"
	PerspectiveCleanArchitecture Perspective = "clean-architecture"
)

func PerspectiveFromAlias(alias string) (Perspective, bool) {
	switch strings.ToLower(alias) {
	case "ddd":
		return PerspectiveDDD, true
	case "laravel":
		return PerspectiveLaravel, true
	case "clean", "clean-architecture":
		return PerspectiveCleanArchitecture, true
	default:
		return "", false
	}
}

func (p Perspective) PromptPath() string {
	return "perspectives/" + string(p)
}
