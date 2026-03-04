package domain

import "seiren/promptloader"

func CorePromptsAll(loader *promptloader.Loader) string {
	return loader.GetCategoryContents("core")
}
