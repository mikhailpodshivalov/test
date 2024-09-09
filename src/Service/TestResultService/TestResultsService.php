<?php

declare(strict_types=1);

namespace App\Service\TestResultService;

use App\Entity\Question;
use Doctrine\Common\Collections\ArrayCollection;

class TestResultsService implements TestResultsServiceInterface
{
    /**
    * @inheritDoc
    */
    public function getTestResultsAnswer(Question $question, array $numbers): ArrayCollection {
        $results = new ArrayCollection();
        foreach ($question->getAnswers() as $key => $answer) {
            foreach ($numbers as $number) {
                if ($key === $number - 1) {
                    $results->add($answer);
                }
            }
        }

        return $results;
    }
}