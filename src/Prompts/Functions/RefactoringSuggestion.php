<?php

declare(strict_types=1);

namespace App\Prompts\Functions;

use App\Prompts\Core\CorePrompts;
use App\Prompts\Core\OutputFormats;

/**
 * リファクタリング提案プロンプト
 */
final class RefactoringSuggestion
{
    /**
     * リファクタリング提案プロンプトを生成
     *
     * @param string $code 対象のコード
     * @param string $context 追加コンテキスト
     * @param string|null $perspective 設計観点
     */
    public static function generate(
        string $code,
        string $context = '',
        ?string $perspective = null
    ): string {
        $corePrompt = CorePrompts::all();
        $outputFormat = OutputFormats::forRefactoring();
        $mermaidFormat = OutputFormats::mermaidClassDiagram();

        $contextSection = '';
        if ($context !== '') {
            $contextSection = <<<CONTEXT
# コンテキスト情報

{$context}

CONTEXT;
        }

        $perspectivePrompt = '';
        if ($perspective !== null) {
            $perspectivePrompt = self::getPerspectivePrompt($perspective);
        }

        return <<<PROMPT
あなたは変更容易性を専門とするソフトウェアアーキテクトです。
以下のコードに対して具体的な設計改善案を提案してください。

# 分析の基盤知識

{$corePrompt}

{$perspectivePrompt}

{$contextSection}

# 対象コード

```
{$code}
```

# 提案手順

1. まずコードを分析し、設計上の問題点を特定する
2. 各問題に対して具体的な解消方法を提案する
3. 改善後のクラス設計をMermaidクラス図で示す
4. リファクタリングの実行手順を提示する

# 出力形式

{$outputFormat}

{$mermaidFormat}

### リファクタリング手順

改善を実施する具体的な手順を以下の形式で出力してください：

1. **ステップ1**: [具体的なアクション]
   - 影響範囲: [影響を受けるファイル/クラス]
   - 注意点: [実施時の注意事項]

2. **ステップ2**: [具体的なアクション]
   ...

# 重要な注意事項

- 改善案は具体的なクラス名・メソッド名を含めること
- Mermaidクラス図は改善後の設計を表現すること
- 段階的に適用可能なリファクタリング手順を示すこと
- 過度な抽象化を避け、実用的な提案をすること
PROMPT;
    }

    private static function getPerspectivePrompt(string $perspective): string
    {
        return match ($perspective) {
            'ddd' => self::dddRefactoringGuide(),
            'laravel' => self::laravelRefactoringGuide(),
            'clean' => self::cleanArchitectureRefactoringGuide(),
            default => '',
        };
    }

    private static function dddRefactoringGuide(): string
    {
        return <<<'PROMPT'
# DDD観点でのリファクタリングガイド

## 改善の方向性
- プリミティブ型を値オブジェクトに置き換え
- ビジネスロジックをエンティティ/値オブジェクトに移動
- 集約の境界を明確化
- ユビキタス言語を反映した命名

## 推奨パターン
- Value Object: 不変、等価性で比較
- Entity: 同一性で識別、状態遷移のメソッドを持つ
- Aggregate Root: トランザクション境界、不変条件の保護
- Domain Service: エンティティに属さないドメインロジック
PROMPT;
    }

    private static function laravelRefactoringGuide(): string
    {
        return <<<'PROMPT'
# Laravel観点でのリファクタリングガイド

## 改善の方向性
- Controllerのスリム化（UseCaseへの委譲）
- Eloquent ModelからDomain Modelの分離
- FormRequestでのバリデーション集約
- Repositoryパターンの導入

## 推奨構成
```
app/
├── Http/Controllers/     # HTTPハンドリングのみ
├── Http/Requests/        # バリデーション
├── UseCases/             # アプリケーションロジック
├── Domain/               # ドメインモデル
│   ├── Models/           # エンティティ/値オブジェクト
│   └── Services/         # ドメインサービス
└── Infrastructure/       # 永続化・外部連携
    └── Repositories/
```
PROMPT;
    }

    private static function cleanArchitectureRefactoringGuide(): string
    {
        return <<<'PROMPT'
# Clean Architecture観点でのリファクタリングガイド

## 改善の方向性
- 依存の方向を内側に向ける
- フレームワーク依存の分離
- インターフェースによる依存性逆転
- UseCaseの単一責任化

## 推奨構成
```
src/
├── Domain/              # Enterprise Business Rules
│   ├── Entities/
│   └── ValueObjects/
├── Application/         # Application Business Rules
│   ├── UseCases/
│   └── Interfaces/      # Ports (Input/Output)
├── Adapter/             # Interface Adapters
│   ├── Controllers/
│   ├── Presenters/
│   └── Gateways/
└── Infrastructure/      # Frameworks & Drivers
    ├── Persistence/
    └── External/
```
PROMPT;
    }
}
