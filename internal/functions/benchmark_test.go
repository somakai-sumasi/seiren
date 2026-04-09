package functions

import (
	"fmt"
	"strings"
	"testing"

	"seiren/internal/domain"
	"seiren/internal/promptloader"
	"seiren/prompts"
)

func init() {
	promptloader.Init(prompts.FS)
}

// サンプルコード生成（指定バイト数に近いPHPコードを生成）
func generateSampleCode(sizeBytes int) string {
	line := "    $this->repository->findById($id);\n"
	repeat := sizeBytes / len(line)
	if repeat < 1 {
		repeat = 1
	}
	return "<?php\nclass SampleService {\n  public function execute($id) {\n" +
		strings.Repeat(line, repeat) +
		"  }\n}\n"
}

// --- プロンプト生成のベンチマーク ---

func BenchmarkDebtAnalysis_Basic_1KB(b *testing.B) {
	code := generateSampleCode(1024)
	focuses := domain.ResolveFocuses([]string{"basic"}).Core
	focusNames := make([]string, len(focuses))
	for i, f := range focuses {
		focusNames[i] = string(f)
	}
	b.ResetTimer()
	for i := 0; i < b.N; i++ {
		GenerateDebtAnalysis(code, "", "php", focusNames)
	}
}

func BenchmarkDebtAnalysis_Basic_10KB(b *testing.B) {
	code := generateSampleCode(10 * 1024)
	b.ResetTimer()
	for i := 0; i < b.N; i++ {
		GenerateDebtAnalysis(code, "", "php", []string{"basic"})
	}
}

func BenchmarkDebtAnalysis_Basic_50KB(b *testing.B) {
	code := generateSampleCode(50 * 1024)
	b.ResetTimer()
	for i := 0; i < b.N; i++ {
		GenerateDebtAnalysis(code, "", "php", []string{"basic"})
	}
}

func BenchmarkDebtAnalysis_Basic_100KB(b *testing.B) {
	code := generateSampleCode(100 * 1024)
	b.ResetTimer()
	for i := 0; i < b.N; i++ {
		GenerateDebtAnalysis(code, "", "php", []string{"basic"})
	}
}

func BenchmarkDebtAnalysis_All_1KB(b *testing.B) {
	code := generateSampleCode(1024)
	b.ResetTimer()
	for i := 0; i < b.N; i++ {
		GenerateDebtAnalysis(code, "ddd", "php", []string{"all"})
	}
}

func BenchmarkDebtAnalysis_All_100KB(b *testing.B) {
	code := generateSampleCode(100 * 1024)
	b.ResetTimer()
	for i := 0; i < b.N; i++ {
		GenerateDebtAnalysis(code, "ddd", "php", []string{"all"})
	}
}

func BenchmarkRefactoring_Basic_1KB(b *testing.B) {
	code := generateSampleCode(1024)
	b.ResetTimer()
	for i := 0; i < b.N; i++ {
		GenerateRefactoringSuggestion(code, "", "ddd", []string{"basic"})
	}
}

func BenchmarkRefactoring_Basic_100KB(b *testing.B) {
	code := generateSampleCode(100 * 1024)
	b.ResetTimer()
	for i := 0; i < b.N; i++ {
		GenerateRefactoringSuggestion(code, "", "ddd", []string{"basic"})
	}
}

func BenchmarkRefactoring_All_1KB(b *testing.B) {
	code := generateSampleCode(1024)
	b.ResetTimer()
	for i := 0; i < b.N; i++ {
		GenerateRefactoringSuggestion(code, "", "ddd", []string{"all"})
	}
}

func BenchmarkTestCode_1KB(b *testing.B) {
	code := generateSampleCode(1024)
	b.ResetTimer()
	for i := 0; i < b.N; i++ {
		GenerateTestCode(code, "PHPUnit", "php")
	}
}

func BenchmarkTestCode_100KB(b *testing.B) {
	code := generateSampleCode(100 * 1024)
	b.ResetTimer()
	for i := 0; i < b.N; i++ {
		GenerateTestCode(code, "PHPUnit", "php")
	}
}

// --- プロンプトサイズ計測テスト ---

func TestPromptSizes(t *testing.T) {
	codeSizes := []struct {
		name string
		size int
	}{
		{"no_code", 0},
		{"1KB", 1024},
		{"5KB", 5 * 1024},
		{"10KB", 10 * 1024},
		{"50KB", 50 * 1024},
		{"100KB", 100 * 1024},
	}

	for _, cs := range codeSizes {
		code := ""
		if cs.size > 0 {
			code = generateSampleCode(cs.size)
		}

		t.Run(fmt.Sprintf("code=%s", cs.name), func(t *testing.T) {
			// Debt Analysis (basic)
			resultBasic := GenerateDebtAnalysis(code, "", "php", []string{"basic"})
			t.Logf("debt_basic:  %6d bytes (%5d chars)", len(resultBasic), len([]rune(resultBasic)))

			// Debt Analysis (all + ddd + php)
			resultAll := GenerateDebtAnalysis(code, "ddd", "php", []string{"all"})
			t.Logf("debt_all:    %6d bytes (%5d chars)", len(resultAll), len([]rune(resultAll)))

			// Refactoring (basic)
			resultRefactorBasic := GenerateRefactoringSuggestion(code, "", "ddd", []string{"basic"})
			t.Logf("refactor_basic: %6d bytes (%5d chars)", len(resultRefactorBasic), len([]rune(resultRefactorBasic)))

			// Refactoring (all) - 旧動作相当
			resultRefactorAll := GenerateRefactoringSuggestion(code, "", "ddd", []string{"all"})
			t.Logf("refactor_all:   %6d bytes (%5d chars)", len(resultRefactorAll), len([]rune(resultRefactorAll)))

			// Test Code
			resultTest := GenerateTestCode(code, "PHPUnit", "php")
			t.Logf("test_code:   %6d bytes (%5d chars)", len(resultTest), len([]rune(resultTest)))
		})
	}
}

// --- MCP JSON レスポンスサイズ計測 ---
// MCP は JSON-RPC over stdio なので、大きなテキストは JSON エスケープでさらに膨らむ

func TestMCPResponseOverhead(t *testing.T) {
	code := generateSampleCode(50 * 1024)
	result := GenerateDebtAnalysis(code, "ddd", "php", []string{"all"})

	// JSON文字列としてエスケープした場合のサイズ増加を概算
	escaped := strings.ReplaceAll(result, `\`, `\\`)
	escaped = strings.ReplaceAll(escaped, `"`, `\"`)
	escaped = strings.ReplaceAll(escaped, "\n", `\n`)
	escaped = strings.ReplaceAll(escaped, "\t", `\t`)

	overhead := float64(len(escaped)) / float64(len(result)) * 100

	t.Logf("raw size:     %d bytes", len(result))
	t.Logf("escaped size: %d bytes", len(escaped))
	t.Logf("JSON overhead: %.1f%%", overhead-100)
}
