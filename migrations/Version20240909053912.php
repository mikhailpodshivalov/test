<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240909053912 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE test_results (id INT NOT NULL, question_id INT NOT NULL, name VARCHAR(32) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_43E230DC1E27F6BF ON test_results (question_id)');
        $this->addSql('CREATE TABLE test_results_answer (test_results_id INT NOT NULL, answer_id INT NOT NULL, PRIMARY KEY(test_results_id, answer_id))');
        $this->addSql('CREATE INDEX IDX_9042A0567390BC80 ON test_results_answer (test_results_id)');
        $this->addSql('CREATE INDEX IDX_9042A056AA334807 ON test_results_answer (answer_id)');
        $this->addSql('ALTER TABLE test_results ADD CONSTRAINT FK_43E230DC1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE test_results_answer ADD CONSTRAINT FK_9042A0567390BC80 FOREIGN KEY (test_results_id) REFERENCES test_results (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE test_results_answer ADD CONSTRAINT FK_9042A056AA334807 FOREIGN KEY (answer_id) REFERENCES answer (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE test_results DROP CONSTRAINT FK_43E230DC1E27F6BF');
        $this->addSql('ALTER TABLE test_results_answer DROP CONSTRAINT FK_9042A0567390BC80');
        $this->addSql('ALTER TABLE test_results_answer DROP CONSTRAINT FK_9042A056AA334807');
        $this->addSql('DROP TABLE test_results');
        $this->addSql('DROP TABLE test_results_answer');
    }
}
