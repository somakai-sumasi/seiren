<?php

declare(strict_types=1);

namespace App\Prompts;

use App\Prompts\Enums\Focus;
use App\Prompts\Enums\FocusGroup;

/**
 * 分析観点の解決
 */
final class AnalysisFocus
{
    /**
     * 指定された観点/グループから実際の観点リストを解決
     *
     * @param list<string> $inputs 観点またはグループのリスト
     * @return array{core: list<Focus>, antipatterns: list<Focus>}
     */
    public static function resolve(array $inputs): array
    {
        $resolved = [
            'core' => [Focus::DEFECT_SCORING], // スコアリングは常に含める
            'antipatterns' => [],
        ];

        foreach ($inputs as $input) {
            // グループかチェック
            $group = FocusGroup::tryFrom($input);
            if ($group !== null) {
                foreach ($group->focuses() as $focus) {
                    if ($focus->isCore()) {
                        $resolved['core'][] = $focus;
                    } else {
                        $resolved['antipatterns'][] = $focus;
                    }
                }
                continue;
            }

            // 個別の観点かチェック
            $focus = Focus::fromString($input);
            if ($focus !== null) {
                if ($focus->isCore()) {
                    $resolved['core'][] = $focus;
                } else {
                    $resolved['antipatterns'][] = $focus;
                }
            }
        }

        // 重複を除去
        $resolved['core'] = array_values(array_unique($resolved['core'], SORT_REGULAR));
        $resolved['antipatterns'] = array_values(array_unique($resolved['antipatterns'], SORT_REGULAR));

        return $resolved;
    }

    /**
     * デフォルトの観点を返す
     *
     * @return list<string>
     */
    public static function defaults(): array
    {
        return [FocusGroup::BASIC->value];
    }
}
