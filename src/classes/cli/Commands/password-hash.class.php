<?php

namespace Obadiah\Cli\Commands;

use Obadiah\App;
use Obadiah\Cli\Argument;
use Obadiah\Cli\Command;
use Obadiah\Crypto\Crypto;
use SensitiveParameter;

App::check();

class Password_Hash extends Command
{
    /**
     * Create command.
     *
     * @param string $password              The password to hash.
     * @return void
     */
    public function __construct(
        #[Argument("The password to hash.", short: "p", required: true)]
        #[SensitiveParameter] public readonly string $password
    ) {}

    /**
     * Output the hashed password.
     *
     * @return void
     */
    public function execute(): void
    {
        echo(Crypto::hash_password($this->password));
        exit;
    }
}
