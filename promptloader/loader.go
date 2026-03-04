package promptloader

import (
	"os"
	"path/filepath"
	"regexp"
	"sort"
	"strings"
	"sync"
)

type PromptData struct {
	Metadata map[string]string
	Content  string
}

type Loader struct {
	basePath string
	cache    map[string]PromptData
	mu       sync.RWMutex
}

var (
	instance *Loader
	once     sync.Once
)

func GetInstance(basePath ...string) *Loader {
	once.Do(func() {
		bp := ""
		if len(basePath) > 0 && basePath[0] != "" {
			bp = basePath[0]
		}
		instance = &Loader{
			basePath: bp,
			cache:    make(map[string]PromptData),
		}
	})
	return instance
}

func (l *Loader) Load(path string) PromptData {
	l.mu.RLock()
	if cached, ok := l.cache[path]; ok {
		l.mu.RUnlock()
		return cached
	}
	l.mu.RUnlock()

	filePath := filepath.Join(l.basePath, path+".md")
	content, err := os.ReadFile(filePath)
	if err != nil {
		return PromptData{Metadata: map[string]string{}, Content: ""}
	}

	parsed := parseMarkdownWithFrontMatter(string(content))

	l.mu.Lock()
	l.cache[path] = parsed
	l.mu.Unlock()

	return parsed
}

func (l *Loader) GetContent(path string) string {
	return l.Load(path).Content
}

type namedPrompt struct {
	Name string
	Data PromptData
}

func (l *Loader) LoadCategory(category string) []namedPrompt {
	dir := filepath.Join(l.basePath, category)
	entries, err := os.ReadDir(dir)
	if err != nil {
		return nil
	}

	var results []namedPrompt
	for _, entry := range entries {
		if entry.IsDir() || !strings.HasSuffix(entry.Name(), ".md") {
			continue
		}
		name := strings.TrimSuffix(entry.Name(), ".md")
		results = append(results, namedPrompt{
			Name: name,
			Data: l.Load(category + "/" + name),
		})
	}

	sort.Slice(results, func(i, j int) bool {
		return results[i].Name < results[j].Name
	})

	return results
}

func (l *Loader) GetCategoryContents(category string, separator ...string) string {
	sep := "\n\n---\n\n"
	if len(separator) > 0 {
		sep = separator[0]
	}

	prompts := l.LoadCategory(category)
	contents := make([]string, 0, len(prompts))
	for _, p := range prompts {
		if p.Data.Content != "" {
			contents = append(contents, p.Data.Content)
		}
	}
	return strings.Join(contents, sep)
}

func (l *Loader) Exists(path string) bool {
	filePath := filepath.Join(l.basePath, path+".md")
	_, err := os.Stat(filePath)
	return err == nil
}

func (l *Loader) RenderTemplate(templatePath string, values map[string]string) string {
	template := l.GetContent(templatePath)
	for key, value := range values {
		placeholder := "{{" + key + "}}"
		template = strings.ReplaceAll(template, placeholder, value)
	}
	return template
}

var frontMatterRegex = regexp.MustCompile(`(?s)^---\r?\n(.+?)\r?\n---\r?\n(.*)$`)

func parseMarkdownWithFrontMatter(content string) PromptData {
	metadata := make(map[string]string)
	body := content

	matches := frontMatterRegex.FindStringSubmatch(content)
	if matches != nil {
		frontMatter := matches[1]
		body = strings.TrimSpace(matches[2])

		lines := strings.Split(frontMatter, "\n")
		for _, line := range lines {
			line = strings.TrimSpace(line)
			if line == "" || !strings.Contains(line, ":") {
				continue
			}
			colonPos := strings.Index(line, ":")
			key := strings.TrimSpace(line[:colonPos])
			value := strings.TrimSpace(line[colonPos+1:])
			metadata[key] = value
		}
	}

	return PromptData{
		Metadata: metadata,
		Content:  body,
	}
}
