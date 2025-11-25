<?php

declare(strict_types=1);

namespace App;

use Mcp\Capability\Attribute\McpPrompt;
use Mcp\Schema\Content\PromptMessage;
use Mcp\Schema\Content\TextContent;
use Mcp\Schema\Enum\Role;

class CodeQualityPrompts
{
    /**
     * @return list<PromptMessage>
     */
    #[McpPrompt(
        name: 'analyze_technical_debt',
        description: '技術的負債を分析し、変更容易性の観点から問題点を指摘する'
    )]
    public function analyzeTechnicalDebt(string $code): array
    {
        // TODO: プロンプト内容は今後精査
        $prompt = <<<PROMPT
以下のコードを変更容易性の観点から分析してください。

## 分析観点
- カプセル化の問題
- 関心の分離
- 単一責任原則の違反
- 凝集度と結合度

## 対象コード
```
{$code}
```

## 出力形式
1. 検出された問題点のリスト
2. 各問題の重要度（高/中/低）
3. 改善の方向性
PROMPT;

        return [
            new PromptMessage(
                role: Role::User,
                content: new TextContent(text: $prompt)
            )
        ];
    }

    /**
     * @return list<PromptMessage>
     */
    #[McpPrompt(
        name: 'suggest_refactoring',
        description: '設計改善案をMermaidクラス図とともに提案する'
    )]
    public function suggestRefactoring(string $code, string $context = ''): array
    {
        // TODO: プロンプト内容は今後精査
        $prompt = <<<PROMPT
以下のコードに対して設計改善案を提案してください。

## コンテキスト
{$context}

## 対象コード
```
{$code}
```

## 出力形式
1. 現状の問題点の要約
2. 改善案の概要
3. Mermaidクラス図（改善後の設計）
4. リファクタリング手順
PROMPT;

        return [
            new PromptMessage(
                role: Role::User,
                content: new TextContent(text: $prompt)
            )
        ];
    }

    /**
     * @return list<PromptMessage>
     */
    #[McpPrompt(
        name: 'generate_test_code',
        description: '高品質なテストコードを生成するためのプロンプト'
    )]
    public function generateTestCode(string $code, string $testFramework = 'PHPUnit'): array
    {
        // TODO: プロンプト内容は今後精査
        $prompt = <<<PROMPT
以下のコードに対するテストコードを生成してください。

## テストフレームワーク
{$testFramework}

## 対象コード
```
{$code}
```

## 要件
- 境界値テスト
- 異常系テスト
- モックの適切な使用
- テストの可読性重視
PROMPT;

        return [
            new PromptMessage(
                role: Role::User,
                content: new TextContent(text: $prompt)
            )
        ];
    }
}
