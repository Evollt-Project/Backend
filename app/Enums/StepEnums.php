<?php

namespace App\Enums;

const GENERAL = StepGroupEnums::GENERAL->value;
const TEST = StepGroupEnums::TEST->value;
const ANSWER = StepGroupEnums::ANSWER->value;
const PROGRAMMING = StepGroupEnums::PROGRAMMING->value;

enum StepEnums: int
{
    case TEXT = 1;
    case VIDEO = 2;
    case TEST = 3;
    case SORT_TASK = 4;
    case MATCH_TASK = 5;
    case FILL_GAP = 6;
    case TEXT_TASK = 7;
    case MATH_TASK = 8;
    case NUMERICAL_TASK = 9;
    case SQL = 10;
    case PROGRAMMING = 11;
    case HTML_CSS = 12;

    private function getGroups(): array
    {
        return match ($this) {
            self::TEXT => [GENERAL],
            self::VIDEO => [GENERAL],
            self::TEST => [GENERAL, TEST],
            self::SORT_TASK => [TEST],
            self::MATCH_TASK => [TEST],
            self::FILL_GAP => [ANSWER],
            self::TEXT_TASK => [ANSWER],
            self::MATH_TASK => [ANSWER],
            self::NUMERICAL_TASK => [ANSWER],
            self::PROGRAMMING => [GENERAL, PROGRAMMING],
            self::SQL => [PROGRAMMING],
            self::HTML_CSS => [PROGRAMMING],
            default => [],
        };
    }

    public static function groupedDescriptions(): array
    {
        $result = [];

        foreach (self::cases() as $case) {
            $description = $case->getDescription();

            foreach ($case->getGroups() as $group) {
                $result[$group][$case->value] = $description;
            }
        }

        return $result;
    }


    public function getDescription(): array
    {
        return match ($this) {
            self::TEXT => [
                'title' => 'Текст',
                'icon' => 'mdi-text-box-outline',
                'description' => 'Текст с форматированием, изображениями, формулами'
            ],
            self::VIDEO => [
                'title' => 'Видео',
                'icon' => 'mdi-video-outline',
                'description' => 'Загружайте видео'
            ],
            self::TEST => [
                'title' => 'Тест (Задача)',
                'icon' => 'mdi-order-bool-ascending-variant',
                'description' => 'Выберите все подходящие ответы из списка'
            ],
            self::SORT_TASK => [
                'title' => 'Задача на сортировку',
                'icon' => 'mdi-sort',
                'description' => 'Расположите элементы списка в правильном порядке'
            ],
            self::MATCH_TASK => [
                'title' => 'Задача на сопоставление',
                'icon' => 'mdi-compare-horizontal',
                'description' => 'Сопоставьте значения из двух списков'
            ],
            self::FILL_GAP => [
                'title' => 'Пропуски',
                'icon' => 'mdi-format-letter-matches',
                'description' => 'Заполните пропуски'
            ],
            self::TEXT_TASK => [
                'title' => 'Текстовая задача',
                'icon' => 'mdi-form-textarea',
                'description' => 'Напишите текст',
            ],
            self::MATH_TASK => [
                'title' => 'Математическая задача',
                'icon' => 'mdi-calculator-variant-outline',
                'description' => 'Введите математическую формулу',
            ],
            self::NUMERICAL_TASK => [
                'title' => 'Численная задача',
                'icon' => 'mdi-counter',
                'description' => 'Введите численный ответ',
            ],
            self::SQL => [
                'title' => 'SQL Challenge',
                'icon' => 'mdi-database-search',
                'description' => 'Введите SQL запрос',
            ],
            self::PROGRAMMING => [
                'title' => 'Программирование',
                'icon' => 'mdi-code-braces',
                'description' => 'Напишите программу. Тестируется через stdin → stdout',
            ],
            self::HTML_CSS => [
                'title' => 'HTML и CSS задача',
                'icon' => 'mdi-xml',
                'description' => 'Напишите структуру и стили html документа',
            ],
        };
    }
}
