<?php

declare(strict_types=1);

namespace App\Prompts;

use App\PromptLoader;

/**
 * プロンプトパス解決の共通ロジック
 *
 * Perspective、Language、Frameworkなどの文字列をプロンプトパスに解決し、
 * 内容を取得する責務を持つ
 */
final class PromptResolver
{
    /**
     * プロンプトパスを解決して内容を取得
     *
     * @param array<string, string> $mapping キーと相対パスのマッピング
     * @param string $value 解決する値
     * @param string $basePath ベースパス（空文字列の場合は相対パスをそのまま使用）
     * @return string プロンプトの内容（見つからない場合は空文字列）
     */
    public static function resolve(array $mapping, string $value, string $basePath = ''): string
    {
        $key = strtolower(trim($value));
        $relativePath = $mapping[$key] ?? null;

        if ($relativePath === null) {
            return '';
        }

        $path = $basePath !== '' ? $basePath . '/' . $relativePath : $relativePath;
        $loader = PromptLoader::getInstance();

        return $loader->exists($path) ? $loader->getContent($path) : '';
    }
}
