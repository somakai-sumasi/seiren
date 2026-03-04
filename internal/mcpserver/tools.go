package mcpserver

import (
	"context"
	"strings"

	"github.com/mark3labs/mcp-go/mcp"
	"github.com/mark3labs/mcp-go/server"

	"seiren/internal/functions"
)

func RegisterTools(s *server.MCPServer) {
	s.AddTool(
		mcp.NewTool("analyze_technical_debt",
			mcp.WithDescription(`技術的負債を分析し、欠陥を特定。行番号と欠陥スコアを含むテーブル形式で出力。

【focus引数 - 分析観点の指定】
グループ指定:
  - basic: カプセル化, 関心の分離, 命名(デフォルト)
  - structure: ドメインモデル, レイヤ分離, データモデル層, Repository層, Service層, interface設計
  - quality: 不変性, 凝集性, 結合度, 条件分岐
  - antipatterns: 生焼けオブジェクト, デッドコード, null問題等
  - all: 全ての観点

個別指定(カンマ区切り):
  encapsulation, separation-of-concerns, naming,
  domain-model-completeness, layer-separation,
  layer-data-model, layer-repository, layer-service, interface-design,
  immutability, cohesion, coupling, conditional-branching,
  half-baked-object, method-chain, dead-code, magic-number,
  null-problem, exception-abuse, god-class

例: focus="basic,antipatterns" または focus="encapsulation,naming"`),
			mcp.WithString("code", mcp.Description("分析対象コード")),
			mcp.WithString("language", mcp.Description("言語（php, typescript）")),
			mcp.WithString("perspective", mcp.Description("設計観点（ddd, laravel, clean）")),
			mcp.WithString("focus", mcp.Description("分析観点（カンマ区切りで複数指定可）")),
		),
		handleAnalyzeTechnicalDebt,
	)

	s.AddTool(
		mcp.NewTool("suggest_refactoring",
			mcp.WithDescription("設計改善案をテーブル形式とMermaidクラス図で提案。DDD、Laravel、Clean Architecture等の観点を選択可能。"),
			mcp.WithString("code", mcp.Description("分析対象コード")),
			mcp.WithString("context", mcp.Description("追加コンテキスト")),
			mcp.WithString("perspective", mcp.Description("設計観点（ddd, laravel, clean）")),
		),
		handleSuggestRefactoring,
	)

	s.AddTool(
		mcp.NewTool("generate_test_code",
			mcp.WithDescription("高品質なテストコードを生成。PHPUnit、等のフレームワークに対応。"),
			mcp.WithString("code", mcp.Description("テスト対象コード")),
			mcp.WithString("language", mcp.Description("言語（php, typescript）")),
			mcp.WithString("testFramework",
				mcp.Description("テストフレームワーク（PHPUnit）"),
				mcp.DefaultString("PHPUnit"),
			),
		),
		handleGenerateTestCode,
	)
}

func handleAnalyzeTechnicalDebt(_ context.Context, request mcp.CallToolRequest) (*mcp.CallToolResult, error) {
	code := request.GetString("code", "")
	language := request.GetString("language", "")
	perspective := request.GetString("perspective", "")
	focus := request.GetString("focus", "")

	var focuses []string
	if focus != "" {
		for _, f := range strings.Split(focus, ",") {
			focuses = append(focuses, strings.TrimSpace(f))
		}
	}

	result := functions.GenerateDebtAnalysis(code, perspective, language, focuses)
	return mcp.NewToolResultText(result), nil
}

func handleSuggestRefactoring(_ context.Context, request mcp.CallToolRequest) (*mcp.CallToolResult, error) {
	code := request.GetString("code", "")
	ctx := request.GetString("context", "")
	perspective := request.GetString("perspective", "")

	result := functions.GenerateRefactoringSuggestion(code, ctx, perspective)
	return mcp.NewToolResultText(result), nil
}

func handleGenerateTestCode(_ context.Context, request mcp.CallToolRequest) (*mcp.CallToolResult, error) {
	code := request.GetString("code", "")
	language := request.GetString("language", "")
	testFramework := request.GetString("testFramework", "PHPUnit")

	result := functions.GenerateTestCode(code, testFramework, language)
	return mcp.NewToolResultText(result), nil
}
