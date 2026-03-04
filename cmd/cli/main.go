package main

import (
	"fmt"
	"io"
	"os"
	"path/filepath"
	"strings"

	"github.com/spf13/cobra"

	"seiren/internal/functions"
	"seiren/internal/promptloader"
)

func main() {
	rootCmd := &cobra.Command{
		Use:   "seiren",
		Short: "コード品質改善プロンプト生成ツール",
	}

	rootCmd.AddCommand(
		newAnalyzeCmd(),
		newRefactorCmd(),
		newTestCmd(),
	)

	if err := rootCmd.Execute(); err != nil {
		os.Exit(1)
	}
}

func initPromptLoader() {
	exec, err := os.Executable()
	var promptsPath string
	if err == nil {
		p := filepath.Join(filepath.Dir(exec), "prompts")
		if info, statErr := os.Stat(p); statErr == nil && info.IsDir() {
			promptsPath = p
		}
	}
	if promptsPath == "" {
		promptsPath = filepath.Join(".", "prompts")
	}
	promptloader.GetInstance(promptsPath)
}

func readCode(code, file string) (string, error) {
	if code != "" {
		return code, nil
	}
	if file != "" {
		data, err := os.ReadFile(file)
		if err != nil {
			return "", fmt.Errorf("ファイルの読み込みに失敗: %w", err)
		}
		return string(data), nil
	}
	stat, _ := os.Stdin.Stat()
	if (stat.Mode() & os.ModeCharDevice) == 0 {
		data, err := io.ReadAll(os.Stdin)
		if err != nil {
			return "", fmt.Errorf("標準入力の読み込みに失敗: %w", err)
		}
		return string(data), nil
	}
	return "", nil
}

func newAnalyzeCmd() *cobra.Command {
	var code, file, language, perspective, focus string

	cmd := &cobra.Command{
		Use:   "analyze",
		Short: "技術的負債を分析",
		RunE: func(cmd *cobra.Command, args []string) error {
			initPromptLoader()
			c, err := readCode(code, file)
			if err != nil {
				return err
			}
			var focuses []string
			if focus != "" {
				for _, f := range strings.Split(focus, ",") {
					focuses = append(focuses, strings.TrimSpace(f))
				}
			}
			result := functions.GenerateDebtAnalysis(c, perspective, language, focuses)
			fmt.Print(result)
			return nil
		},
	}

	cmd.Flags().StringVar(&code, "code", "", "分析対象コード")
	cmd.Flags().StringVar(&file, "file", "", "分析対象ファイルパス")
	cmd.Flags().StringVar(&language, "language", "", "言語 (php, typescript)")
	cmd.Flags().StringVar(&perspective, "perspective", "", "設計観点 (ddd, laravel, clean)")
	cmd.Flags().StringVar(&focus, "focus", "", "分析観点（カンマ区切り）")

	return cmd
}

func newRefactorCmd() *cobra.Command {
	var code, file, ctx, perspective string

	cmd := &cobra.Command{
		Use:   "refactor",
		Short: "設計改善案を提案",
		RunE: func(cmd *cobra.Command, args []string) error {
			initPromptLoader()
			c, err := readCode(code, file)
			if err != nil {
				return err
			}
			result := functions.GenerateRefactoringSuggestion(c, ctx, perspective)
			fmt.Print(result)
			return nil
		},
	}

	cmd.Flags().StringVar(&code, "code", "", "分析対象コード")
	cmd.Flags().StringVar(&file, "file", "", "分析対象ファイルパス")
	cmd.Flags().StringVar(&ctx, "context", "", "追加コンテキスト")
	cmd.Flags().StringVar(&perspective, "perspective", "", "設計観点 (ddd, laravel, clean)")

	return cmd
}

func newTestCmd() *cobra.Command {
	var code, file, language, testFramework string

	cmd := &cobra.Command{
		Use:   "test",
		Short: "テストコードを生成",
		RunE: func(cmd *cobra.Command, args []string) error {
			initPromptLoader()
			c, err := readCode(code, file)
			if err != nil {
				return err
			}
			result := functions.GenerateTestCode(c, testFramework, language)
			fmt.Print(result)
			return nil
		},
	}

	cmd.Flags().StringVar(&code, "code", "", "テスト対象コード")
	cmd.Flags().StringVar(&file, "file", "", "テスト対象ファイルパス")
	cmd.Flags().StringVar(&language, "language", "", "言語 (php, typescript)")
	cmd.Flags().StringVar(&testFramework, "test-framework", "PHPUnit", "テストフレームワーク")

	return cmd
}
