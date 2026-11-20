<?php

namespace App\Agents;

use Laravel\Ai\Attributes\UseCheapestModel;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Promptable;

/**
 * Reads a free-text task description and answers with a single priority word.
 * Everything about it is deterministic except the model itself — which is
 * exactly why it needs evals rather than an ordinary assertion.
 */
#[UseCheapestModel]
final class TaskTriageAgent implements Agent
{
    use Promptable;

    public function instructions(): string
    {
        return 'You triage tasks for a to-do app. Given a task description, reply with '
            .'exactly one word — "low", "medium" or "high" — describing its priority. '
            .'Anything involving money, safety, deadlines today, or a customer waiting '
            .'is "high". Reply with the single word and nothing else.';
    }
}
