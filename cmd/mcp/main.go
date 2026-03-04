package main

import (
	"fmt"
	"os"

	"github.com/mark3labs/mcp-go/server"

	"seiren/internal/mcpserver"
	"seiren/internal/promptloader"
	"seiren/prompts"
)

func main() {
	promptloader.Init(prompts.FS)

	s := server.NewMCPServer(
		"Code Quality Prompts Server",
		"0.1.0",
		server.WithToolCapabilities(true),
	)

	mcpserver.RegisterTools(s)

	if err := server.ServeStdio(s); err != nil {
		fmt.Fprintf(os.Stderr, "Server error: %v\n", err)
		os.Exit(1)
	}
}
