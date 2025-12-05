<?php

declare(strict_types=1);

namespace App;

/**
 * Markdownファイルからプロンプトを読み込むローダー
 */
final class PromptLoader
{
    private static ?PromptLoader $instance = null;

    private string $basePath;

    /** @var array<string, array{metadata: array<string, string>, content: string}> */
    private array $cache = [];

    private function __construct(?string $basePath = null)
    {
        $this->basePath = $basePath ?? dirname(__DIR__) . '/prompts';
    }

    /**
     * シングルトンインスタンスを取得
     */
    public static function getInstance(?string $basePath = null): PromptLoader
    {
        if (self::$instance === null) {
            self::$instance = new self($basePath);
        }
        return self::$instance;
    }

    /**
     * 指定カテゴリ内の全プロンプトを読み込む
     *
     * @return array<string, array{metadata: array<string, string>, content: string}>
     */
    public function loadCategory(string $category): array
    {
        $dir = $this->basePath . '/' . $category;
        if (!is_dir($dir)) {
            return [];
        }

        $prompts = [];
        $files = glob($dir . '/*.md');
        if ($files === false) {
            return [];
        }

        foreach ($files as $file) {
            $name = basename($file, '.md');
            $prompts[$name] = $this->load($category . '/' . $name);
        }

        return $prompts;
    }

    /**
     * 単一のプロンプトを読み込む
     *
     * @return array{metadata: array<string, string>, content: string}
     */
    public function load(string $path): array
    {
        $cacheKey = $path;
        if (isset($this->cache[$cacheKey])) {
            return $this->cache[$cacheKey];
        }

        $filePath = $this->basePath . '/' . $path . '.md';
        if (!file_exists($filePath)) {
            return ['metadata' => [], 'content' => ''];
        }

        $content = file_get_contents($filePath);
        if ($content === false) {
            return ['metadata' => [], 'content' => ''];
        }

        $parsed = $this->parseMarkdownWithFrontMatter($content);
        $this->cache[$cacheKey] = $parsed;

        return $parsed;
    }

    /**
     * プロンプト本文のみを取得
     */
    public function getContent(string $path): string
    {
        return $this->load($path)['content'];
    }

    /**
     * メタデータのみを取得
     *
     * @return array<string, string>
     */
    public function getMetadata(string $path): array
    {
        return $this->load($path)['metadata'];
    }

    /**
     * カテゴリ内の全プロンプト本文を結合して取得
     */
    public function getCategoryContents(string $category, string $separator = "\n\n---\n\n"): string
    {
        $prompts = $this->loadCategory($category);
        $contents = array_map(fn($p) => $p['content'], $prompts);
        return implode($separator, $contents);
    }

    /**
     * 指定名のプロンプトが存在するか確認
     */
    public function exists(string $path): bool
    {
        $filePath = $this->basePath . '/' . $path . '.md';
        return file_exists($filePath);
    }

    /**
     * Markdownテンプレートをプレースホルダーで置換して返す
     *
     * @param string $templatePath テンプレートのパス
     * @param array<string, string> $values 置換する値（キーがプレースホルダー名）
     * @return string 置換後のテンプレート
     */
    public function renderTemplate(string $templatePath, array $values): string
    {
        $template = $this->getContent($templatePath);

        $replacements = [];
        foreach ($values as $key => $value) {
            $replacements['{{' . $key . '}}'] = $value;
        }

        return strtr($template, $replacements);
    }

    /**
     * Markdown + YAML Front Matterをパース
     *
     * @return array{metadata: array<string, string>, content: string}
     */
    private function parseMarkdownWithFrontMatter(string $content): array
    {
        $metadata = [];
        $body = $content;

        // Front Matterの検出 (--- で囲まれた部分)
        if (preg_match('/^---\r?\n(.+?)\r?\n---\r?\n(.*)$/s', $content, $matches)) {
            $frontMatter = $matches[1];
            $body = trim($matches[2]);

            // 簡易YAMLパーサー（key: value 形式のみ対応）
            $lines = explode("\n", $frontMatter);
            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line) || !str_contains($line, ':')) {
                    continue;
                }
                $colonPos = strpos($line, ':');
                if ($colonPos === false) {
                    continue;
                }
                $key = trim(substr($line, 0, $colonPos));
                $value = trim(substr($line, $colonPos + 1));
                $metadata[$key] = $value;
            }
        }

        return [
            'metadata' => $metadata,
            'content' => $body,
        ];
    }
}
