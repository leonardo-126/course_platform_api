<?php

namespace App\Actions\LessonQuestion;

use App\Models\LessonQuestion;
use App\Models\LessonQuestionOption;
use Illuminate\Support\Facades\DB;

class UpdateLessonQuestionAction
{
    public function execute(int $questionId, array $data): LessonQuestion
    {
        return DB::transaction(function () use ($questionId, $data) {
            $question = LessonQuestion::findOrFail($questionId);

            $question->update(array_filter([
                'question_text' => $data['question_text'] ?? null,
                'question_type' => $data['question_type'] ?? null,
                'points'        => $data['points'] ?? null,
                'sort_order'    => $data['sort_order'] ?? null,
            ], fn($v) => $v !== null));

            if (isset($data['options'])) {
                $this->syncOptions($question, $data['options']);
            }

            return $question->load('options');
        });
    }

    private function syncOptions(LessonQuestion $question, array $options): void
    {
        $existingIds = $question->options()->pluck('id')->toArray();
        $payloadIds  = collect($options)->pluck('id')->filter()->toArray();

        // Apaga as que sumiram do payload
        $toDelete = array_diff($existingIds, $payloadIds);
        if (! empty($toDelete)) {
            LessonQuestionOption::whereIn('id', $toDelete)->delete();
        }

        // Criar ou atualizar opções
        foreach ($options as $index => $opt) {
            $payload = [
                'option_text' => $opt['option_text'],
                'is_correct'  => $opt['is_correct'],
                'sort_order'  => $opt['sort_order'] ?? $index,
            ];

            if (isset($opt['id']) && in_array($opt['id'], $existingIds, true)) {
                // Atualizar opção existente
                LessonQuestionOption::where('id', $opt['id'])->update($payload);
            } else {
                // Criar nova opção
                $question->options()->create($payload);
            }
        }
    }
}
