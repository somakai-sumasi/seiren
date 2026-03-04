package main

import (
	"fmt"
	"os"
	"path/filepath"

	"github.com/mark3labs/mcp-go/server"

	"seiren/internal/mcpserver"
	"seiren/internal/promptloader"
)

func main() {
	// prompts/ ディレクトリのパスを解決
	exec, err := os.Executable()
	var promptsPath string
	if err == nil {
		promptsPath = filepath.Join(filepath.Dir(exec), "prompts")
	} else {
		promptsPath = filepath.Join(".", "prompts")
	}

	// PromptLoaderを初期化
	promptloader.GetInstance(promptsPath)

	// MCPサーバーを作成
	s := server.NewMCPServer(
		"Code Quality Prompts Server",
		"0.1.0",
		server.WithToolCapabilities(true),
	)

	// ツールを登録
	mcpserver.RegisterTools(s)

	// Stdio transport で起動
	if err := server.ServeStdio(s); err != nil {
		fmt.Fprintf(os.Stderr, "Server error: %v\n", err)
		os.Exit(1)
	}
}
