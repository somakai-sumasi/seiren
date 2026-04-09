package mcpserver

import (
	"context"

	"github.com/mark3labs/mcp-go/mcp"
	"github.com/mark3labs/mcp-go/server"

	"seiren/internal/domain"
	"seiren/internal/functions"
)

func RegisterTools(s *server.MCPServer) {
	s.AddTool(
		mcp.NewTool("analyze_technical_debt",
			mcp.WithDescription(`技術的負債の分析プロンプトを返す。返されたプロンプトに従い対象コードを分析すること。focus: basic(デフォルト), structure, quality, antipatterns, all。カンマ区切りで複数指定可。`),
			mcp.WithString("code", mcp.Description("分析対象コード")),
			mcp.WithString("language", mcp.Description("言語（php, typescript, go, python）")),
			mcp.WithString("perspective", mcp.Description("設計観点（ddd, laravel, clean）")),
			mcp.WithString("focus", mcp.Description("分析観点（カンマ区切りで複数指定可）")),
		),
		handleAnalyzeTechnicalDebt,
	)

	s.AddTool(
		mcp.NewTool("suggest_refactoring",
			mcp.WithDescription(`リファクタリング提案プロンプトを返す。返されたプロンプトに従いMermaidクラス図付きで改善案を出力すること。focus: basic(デフォルト), structure, quality, all。`),
			mcp.WithString("code", mcp.Description("分析対象コード")),
			mcp.WithString("context", mcp.Description("追加コンテキスト")),
			mcp.WithString("perspective", mcp.Description("設計観点（ddd, laravel, clean）")),
			mcp.WithString("focus", mcp.Description("分析観点（カンマ区切りで複数指定可）")),
		),
		handleSuggestRefactoring,
	)

	s.AddTool(
		mcp.NewTool("generate_test_code",
			mcp.WithDescription(`テストコード生成プロンプトを返す。返されたプロンプトに従いテストコードを生成すること。PHPUnit, gotest, pytest, vitest, jest対応。`),
			mcp.WithString("code", mcp.Description("テスト対象コード")),
			mcp.WithString("language", mcp.Description("言語（php, typescript, go, python）")),
			mcp.WithString("testFramework",
				mcp.Description("テストフレームワーク（PHPUnit, gotest, pytest, vitest, jest）"),
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

	focuses := domain.ParseFocusInput(focus)

	result := functions.GenerateDebtAnalysis(code, perspective, language, focuses)
	return mcp.NewToolResultText(result), nil
}

func handleSuggestRefactoring(_ context.Context, request mcp.CallToolRequest) (*mcp.CallToolResult, error) {
	code := request.GetString("code", "")
	ctx := request.GetString("context", "")
	perspective := request.GetString("perspective", "")
	focus := request.GetString("focus", "")

	focuses := domain.ParseFocusInput(focus)

	result := functions.GenerateRefactoringSuggestion(code, ctx, perspective, focuses)
	return mcp.NewToolResultText(result), nil
}

func handleGenerateTestCode(_ context.Context, request mcp.CallToolRequest) (*mcp.CallToolResult, error) {
	code := request.GetString("code", "")
	language := request.GetString("language", "")
	testFramework := request.GetString("testFramework", "PHPUnit")

	result := functions.GenerateTestCode(code, testFramework, language)
	return mcp.NewToolResultText(result), nil
}
