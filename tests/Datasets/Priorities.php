<?php

use App\Enums\TaskPriority;

dataset('priorities', [
    'low' => TaskPriority::Low,
    'medium' => TaskPriority::Medium,
    'high' => TaskPriority::High,
]);

dataset('urgent priorities', [
    'high' => TaskPriority::High,
]);

dataset('invalid titles', [
    'empty' => '',
    'one character' => 'a',
    'two characters' => 'ab',
]);
