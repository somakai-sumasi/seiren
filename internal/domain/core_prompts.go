package domain

import "seiren/internal/promptloader"

func CorePromptsAll(loader *promptloader.Loader) string {
	return loader.GetCategoryContents("core")
}
