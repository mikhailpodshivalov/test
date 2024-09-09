<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\TestResults;
use App\Repository\QuestionRepository;
use App\Service\TestResultService\TestResultsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

#[AsCommand(
    name: 'app:test',
    description: 'Run test',
)]
final class TestCommand extends Command
{
    public function __construct(
        private readonly QuestionRepository $questionRepository,
        private readonly TestResultsService $testResultsService,
        private readonly EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $testName = md5(uniqid('', true));
        $output->writeln('+++++++++++++++++++++');

        $output->writeln(sprintf('Hello! Starting the test %s. Type the answers separated by a space:', $testName));
        $questions = $this->questionRepository->getAll();
        $questionHelper = new QuestionHelper();
        $result = [];

        foreach ($questions as $question) {
            $choices = $this->getChoicesFromQuestion($question);
            $symfonyQuestion = new ChoiceQuestion($question->getQuestion() . ':', $choices);
            $symfonyQuestion->setMultiselect(true);
            $symfonyQuestion->setValidator(function ($answer) use ($question) {
                if (!preg_match('/^[0-9 ]*$/', (string)$answer)) {
                    throw new \InvalidArgumentException('Please print int value or space.');
                }

                $listAnswer = array_filter(explode(' ', $answer));

                foreach ($listAnswer as $answer) {
                    if ($answer > count($question->getAnswers())) {
                        throw new \InvalidArgumentException(
                            sprintf('the value of the argument should be no more than %s', count($question->getAnswers()))
                        );
                    }
                }

                return $listAnswer;
            });

            $output->writeln('++++++++++++++++++++++++++++++++++++++++++++');
            $customerAnswers = $questionHelper->ask($input, $output, $symfonyQuestion);
            $customerAnswers = is_array($customerAnswers) ? $customerAnswers : [$customerAnswers];
            $answers = $this->testResultsService->getTestResultsAnswer($question, $customerAnswers);

            $testResults = new TestResults();
            $testResults->setName($testName)
                ->addAnswers($answers)
                ->setQuestion($question);
            $this->entityManager->persist($testResults, $output);

            $result[] = $testResults;
        }

        $this->printResults($result, $output);

        $this->entityManager->flush();

        return Command::SUCCESS;
    }

    /**
     * @param Question $question
     * @return array
     */
    private function getChoicesFromQuestion(Question $question): array
    {
        $choices = array_map(fn(Answer $answer) => $answer->getAnswer(), $question->getAnswers()->toArray());
        $choiceNumber = 0;
        $correctChoices = [];
        array_walk($choices, function ($answer) use (&$choiceNumber, &$correctChoices) {
            $choiceNumber++;
            $correctChoices[$choiceNumber] = $answer;
        });

        return $correctChoices;
    }

    /**
     * @param array $result
     * @return void
     */
    private function printResults(array $result, OutputInterface $output): void
    {
        $wrongQuestion = [];
        $rightQuestion = [];
        /** @var TestResults $testResults */
        foreach ($result as $testResults) {
            switch ($testResults->isQuestionRight()) {
                case true:
                    $rightQuestion[] = $testResults;
                    break;
                case false:
                    $wrongQuestion[] = $testResults;
                    break;
            }
        }

        $output->writeln('Right question:');
        $output->writeln(array_map(fn(TestResults $testResults) => $testResults->getQuestion()->getQuestion(), $rightQuestion));

        $output->writeln('Wrong question:');
        $output->writeln(array_map(fn(TestResults $testResults) => $testResults->getQuestion()->getQuestion(), $wrongQuestion));
    }
}