<?php

namespace Obadiah\Cli;

use Obadiah\App;
use Obadiah\Cli\Commands\Errors\Argument_Missing;
use Obadiah\Cli\Commands\Errors\Invalid;
use Obadiah\Cli\Commands\Errors\Unknown;
use Obadiah\Helpers\Arr;
use ReflectionClass;
use Throwable;

App::check();

class Cli
{
    /**
     * Map of command strings to objects.
     *
     * @var array<string, class-string<Command>>
     */
    private static array $commands = [];

    /**
     * Map a command string to an implementation class.
     *
     * @template T of Command
     * @param string $command_name                  String to use on the commandline to execute a command.
     * @param class-string<T> $command_class        Implementation class.
     * @return void
     */
    public static function map_command(string $command_name, string $command_class)
    {
        // if the command has already been registered, exit with an error
        if (in_array($command_name, self::$commands)) {
            App::die("The command %s already exists and is mapped to %s.", $command_name, self::$commands[$command_name]);
        }

        // map the command with the class name
        self::$commands[$command_name] = $command_class;
    }

    /**
     * Parse arguments and return a matching command.
     *
     * @param string[] $args                        The arguments to parse (usually $argv).
     * @return Command                              The matching Command object.
     */
    public static function get_command(array $args): Command
    {
        // discard script name
        $_ = array_shift($args);

        // get command name and class
        $command_name = array_shift($args);
        if ($command_name === null) {
            return self::unknown("<not specified>");
        }

        if (($command_class = Arr::get(self::$commands, $command_name)) === null) {
            return self::unknown($command_name);
        }

        // get any argument parameters
        $command_class_info = new ReflectionClass($command_class);
        $command_args = [];
        foreach ($command_class_info->getProperties() as $prop) {
            // get properties with the Argument parameter
            $attrs = $prop->getAttributes(Argument::class);

            // get the first attribute if it exists, and store it with the property name (for setting the value later)
            if (($attr = array_shift($attrs)) != null) {
                $command_args[$prop->name] = $attr->newInstance();
            }
        }

        // if there are no registered arguments, create and return the command using parameterless constructor
        if (count($command_args) == 0) {
            return self::command_without_args($command_class_info);
        }

        // parse arguments into an associative array
        $parsed_args = self::parse_args($args);

        // match command args to parsed args
        $constructor_args = [];
        foreach ($command_args as $prop => $arg) {
            // by default the property name is used as the 'long' argument name,
            // but this can be overridden by defining the 'long' property of the Argument attribute
            $long = sprintf("--%s", $arg->long ?: $prop);
            $short = $arg->short == null ? "" : sprintf("-%s", $arg->short);

            // if an argument is specified twice, the 'long' argument is preferred
            $value = Arr::get($parsed_args, $long, Arr::get($parsed_args, $short ?: ""));

            // return invalid Command object if the argument not found but required
            if ($value === null && $arg->required) {
                return self::argument_missing($command_name, $long, $short);
            }

            // store the argument in an associative array
            $constructor_args[$prop] = $value;
        }

        // create instance of the Command using associative array
        try {
            return self::command_with_args($command_class_info, $constructor_args);
        } catch (Throwable $th) {
            _l_throwable($th);
            return self::invalid($command_class);
        }
    }

    /**
     * Parse and sanitise arguments (e.g. remove duplicates).
     *
     * @param string[] $args                        Array of arguments to sanitise.
     * @return array<string, mixed>                 Sanitised arguments as key => value pair.
     */
    private static function parse_args(array $args): array
    {
        $parsed_args = [];
        while (count($args) > 0) {
            // get the next item
            $next = array_shift($args);

            // trim and treat it as a key
            $key = trim($next);

            // if in the format --arg=value, splice the string to separate key and value
            if (($offset = stripos($next, "=")) !== false) {
                $key = substr($key, 0, $offset - 2);
                $value = substr($next, $offset + 1);
                // if there is no = sign and the next item does not start with a dash, that means the next item is the value
            } else if (count($args) > 0 && ! str_starts_with($args[0], "-")) {
                $value = array_shift($args);
                // otherwise the argument is a switch, so because it is present use 'true' as the value
            } else {
                $value = true;
            }

            // store the argument as a key => value associative array
            $parsed_args[$key] = $value;
        }

        // return parsed arguments
        return $parsed_args;
    }

    /**
     * Return Unknown command.
     *
     * @param string $command_name                  Requested command.
     * @return Command                              Unknown command.
     */
    private static function unknown(string $command_name): Command
    {
        return new Unknown($command_name);
    }

    /**
     * Create Command without any constructor args.
     *
     * @template T of Command
     * @param ReflectionClass<T> $command_class_info   Mapped command class.
     * @return T                              Command object.
     */
    private static function command_without_args(ReflectionClass $command_class_info): Command
    {
        return $command_class_info->newInstance();
    }

    /**
     * Return Argument_Missing command.
     *
     * @param string $command_name                  Requested command.
     * @param string $arg_long                      Long form of the missing argument.
     * @param string|null $arg_short                Optional short form of the missing argument.
     * @return Command                              Argument_Missing command.
     */
    private static function argument_missing(string $command_name, string $arg_long, ?string $arg_short): Command
    {
        return new Argument_Missing($command_name, $arg_long, $arg_short);
    }

    /**
     * Return Class_Not_Found command.
     *
     * @template T of Command
     * @param ReflectionClass<T> $command_class_info   Mapped command class.
     * @param mixed[] $args                         Constructor args.
     * @return T                              Command object.
     */
    private static function command_with_args(ReflectionClass $command_class_info, array $args): Command
    {
        return $command_class_info->newInstance(...$args);
    }

    /**
     * Return Invalid command.
     *
     * @param string $command_class                 Mapped command class.
     * @return Command                              Invalid command.
     */
    private static function invalid(string $command_class): Command
    {
        return new Invalid($command_class);
    }
}
