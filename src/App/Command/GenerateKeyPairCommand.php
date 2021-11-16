<?php

declare(strict_types=1);

namespace FC\App\Command;

use JetBrains\PhpStorm\ArrayShape;
use Lcobucci\JWT\Signer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

class GenerateKeyPairCommand extends Command
{
    protected const ACCEPTED_ALGORITHMS = [
        'RS256',
        'RS384',
        'RS512',
        'HS256',
        'HS384',
        'HS512',
        'ES256',
        'ES384',
        'ES512',
    ];

    protected static $defaultName = 'jwt:generate-keypair';
    protected static $defaultDescription = 'Generate public/private keys for an application.';

    /**
     * @param Filesystem $filesystem
     * @param Signer $signer
     * @param string $secretKey
     * @param string $publicKey
     * @param string $passphrase
     */
    public function __construct(
        protected Filesystem $filesystem,
        protected Signer $signer,
        protected string $secretKey,
        protected string $publicKey,
        protected string $passphrase,
    ) {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setDefinition([
                new InputOption('dry-run', null, InputOption::VALUE_NONE, 'Do not update key files.'),
                new InputOption(
                    'overwrite', null, InputOption::VALUE_NONE, 'Overwrite key files if they already exist.'
                ),
            ])
            ->setDescription(self::$defaultDescription)
            ->setHelp(
                <<<'EOF'
The <info>%command.name%</info> generate public/private keys:
  <info>php %command.full_name%</info>
EOF
            );
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException When route does not exist
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if (!\in_array($this->signer->algorithmId(), static::ACCEPTED_ALGORITHMS, true)) {
            $io->error(
                \sprintf(
                    'Cannot generate key pair with the provided algorithm `%s`.',
                    $this->signer->algorithmId(),
                )
            );

            return static::FAILURE;
        }

        [$secretKey, $publicKey] = $this->generateKeyPair($this->passphrase);

        if (true === $input->getOption('dry-run')) {
            $io->success('Your keys have been generated!');
            $io->newLine();
            $io->writeln(\sprintf('Update your private key in <info>%s</info>:', $this->secretKey));
            $io->writeln($secretKey);
            $io->newLine();
            $io->writeln(\sprintf('Update your public key in <info>%s</info>:', $this->publicKey));
            $io->writeln($publicKey);

            return static::SUCCESS;
        }

        if (!\is_dir(\dirname($this->secretKey)) || !\is_dir(\dirname($this->publicKey))) {
            $io->error('Cannot generate key pair.');

            return static::FAILURE;
        }

        $alreadyExists = $this->filesystem->exists($this->secretKey) || $this->filesystem->exists($this->publicKey);

        if ($alreadyExists) {
            if (false === $input->getOption('overwrite')) {
                $io->error('Your keys already exist. Use the `--overwrite` option to force regeneration.');

                return static::FAILURE;
            }

            if (!$io->confirm('You are about to replace your existing keys. Are you sure you wish to continue?')) {
                $io->comment('Your action was canceled.');

                return static::SUCCESS;
            }
        }

        $this->filesystem->dumpFile($this->secretKey, $secretKey);
        $this->filesystem->dumpFile($this->publicKey, $publicKey);

        $io->success('Done!');

        return static::SUCCESS;
    }

    /**
     * @param string $passphrase
     * @return string[]
     */
    protected function generateKeyPair(string $passphrase): array
    {
        $config = $this->buildOpenSSLConfiguration();

        $resource = \openssl_pkey_new($config);
        if (false === $resource) {
            throw new \RuntimeException(\openssl_error_string());
        }

        $success = \openssl_pkey_export($resource, $privateKey, $passphrase);

        if (false === $success) {
            throw new \RuntimeException(\openssl_error_string());
        }

        $publicKeyData = \openssl_pkey_get_details($resource);

        if (false === $publicKeyData) {
            throw new \RuntimeException(\openssl_error_string());
        }

        $publicKey = $publicKeyData['key'];

        return [$privateKey, $publicKey];
    }

    /**
     * @return array<string, mixed>
     */
    #[ArrayShape([
        'digest_alg' => "string",
        'private_key_type' => "mixed",
        'private_key_bits' => "int",
        'curve_name' => "string"
    ])]
    protected function buildOpenSSLConfiguration(): array
    {
        $digestAlgorithms = [
            'RS256' => 'sha256',
            'RS384' => 'sha384',
            'RS512' => 'sha512',
            'HS256' => 'sha256',
            'HS384' => 'sha384',
            'HS512' => 'sha512',
            'ES256' => 'sha256',
            'ES384' => 'sha384',
            'ES512' => 'sha512',
        ];
        $privateKeyBits = [
            'RS256' => 2048,
            'RS384' => 2048,
            'RS512' => 4096,
            'HS256' => 384,
            'HS384' => 384,
            'HS512' => 512,
            'ES256' => 384,
            'ES384' => 512,
            'ES512' => 1024,
        ];
        $privateKeyTypes = [
            'RS256' => \OPENSSL_KEYTYPE_RSA,
            'RS384' => \OPENSSL_KEYTYPE_RSA,
            'RS512' => \OPENSSL_KEYTYPE_RSA,
            'HS256' => \OPENSSL_KEYTYPE_DH,
            'HS384' => \OPENSSL_KEYTYPE_DH,
            'HS512' => \OPENSSL_KEYTYPE_DH,
            'ES256' => \OPENSSL_KEYTYPE_EC,
            'ES384' => \OPENSSL_KEYTYPE_EC,
            'ES512' => \OPENSSL_KEYTYPE_EC,
        ];

        $curves = [
            'ES256' => 'secp256k1',
            'ES384' => 'secp384r1',
            'ES512' => 'secp521r1',
        ];

        $config = [
            'digest_alg' => $digestAlgorithms[$this->signer->algorithmId()],
            'private_key_type' => $privateKeyTypes[$this->signer->algorithmId()],
            'private_key_bits' => $privateKeyBits[$this->signer->algorithmId()],
        ];

        if (isset($curves[$this->signer->algorithmId()])) {
            $config['curve_name'] = $curves[$this->signer->algorithmId()];
        }

        return $config;
    }
}
