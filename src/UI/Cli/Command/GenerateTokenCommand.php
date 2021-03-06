<?php

declare(strict_types=1);

namespace FC\UI\Cli\Command;

use FC\Application\Auth\JWT\JWTManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateTokenCommand extends Command
{
    protected static $defaultName = 'jwt:generate-token';
    protected static $defaultDescription = 'Generates a JWT token.';

    public function __construct(private readonly JWTManagerInterface $jwtManager)
    {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setDefinition([
                new InputArgument('identifier', InputArgument::REQUIRED),
            ])
            ->setDescription(self::$defaultDescription)
            ->setHelp(
                <<<'EOF'
The <info>%command.name%</info> generate a JWT token:
  <info>php %command.full_name%</info>
EOF
            );
    }

    /**
     * {@inheritdoc}
     * @throws \Throwable
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $token = $this->jwtManager->create($input->getArgument('identifier'));

        $output->writeln([
            '',
            '<info>'.$token.'</info>',
            '',
        ]);

        return static::SUCCESS;
    }
}
