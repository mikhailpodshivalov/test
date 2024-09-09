<?php

declare(strict_types=1);

namespace App\Service\TestResultService;

use App\Entity\Question;
use Doctrine\Common\Collections\ArrayCollection;

interface TestResultsServiceInterface
{
    /**
     * @param Question $question
     * @param array $numbers
     * @return ArrayCollection
     */
    public function getTestResultsAnswer(Question $question, array $numbers): ArrayCollection;
}