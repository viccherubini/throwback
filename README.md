# Throwback
Throwback is a new testing framework for legacy PHP applications that have never been tested properly in the past and are too old to support PHPUnit.

## Introduction
Like a lot of PHP developers, I frequently work on very outdated code that is not easily testible. Writing actual unit tests is nearly impossible in code like this, and it might be so old that you can't even use PHPUnit.

Throwback is a new simple to use library for testing these applications. You can test your application two ways: through it's command line interface (if it has one) or through your classes and methods (similar to PHPUnit).

If your application makes use of a lot of CLI commands, Throwback can be used to execute a specific command and test the output or test the expected result in a database or on the filesystem.

## Usage
The goal of Throwback is to be small, simple, and easy to implement. Because it is primarily for older software, it does not require Composer, and only requires PHP 5.1.0 or greater (with a minimal amount of core modules, and no outside userland dependencies). If you wish to configure it with a database, you will need the `pdo_pgsql` or `pdo_mysql` extensions installed.

As such, Throwback is implemented as a single executable that can live anywhere on your system. You provide it, by way of your `$HOME` directory, a configuration file that can inject global parameters or any number of database connections into your tests.

### Installation
To install Throwback, place the `throwback` command from this repository anywhere on your system. You can install it in your `$PATH` if you wish so it can be used from anywhere.

After the `throwback` command is installed, create a directory in your `$HOME` directory named `.throwback`. Copy the `config.php.template` file from this repository to the `.throwback` directory as `config.php`. You are required to retain the top level `parameters` and `databases` keys even if they point to empty arrays.

#### Parameters
The `parameters` key in the configuration file is for injecting any key-value pair into your tests. This array is executed once at run-time, so you generally can't put dynamically generated values in it. Each test can get a parameter by calling

```php
$this->getParameter('name')
```

from within the test case.

#### Databases
You can also connect to any number of PostgreSQL or MySQL databases in your tests. The `driver`, `host`, `database`, `username`, and `password` keys are required for each database you include. The database name (each sub-key of the `databases` key) must be unique.

Database connections are lazily instantiated the first time they are called, and are cached to limit the number open connections. Open connections are automatically closed when a test class is done executing.

You can get an active database connection by calling

```php
$this->getDatabase('name')
```

from within the test case.

### Running Tests
You run your tests by running the `throwback` command with an optional argument of the directory you wish to test. If no directory is specified, the current working directory is used. 

* `throwback`
* `throwback /path/to/directory/to/test`

## Writing Tests
Throwback borrows from Go's testing philosophy in that every file suffixed with `_test.php` in a specific directory is considered a test and executed as so. Each file ending in `_test.php` should have a single class in it that extends the `ThrowbackTestCase` class that Throwback provides (you can have multiple classes in a single file, but organizing your tests like that makes them harder to manage). Each test class name must be unique within a specific directory.

All public, non-static methods that begin with `test` in the class are considered testable.

Because Throwback is aimed at testing legacy applications, you have several methods that make it easy to run a command and test the output.

### Testing Commands
Use the `runCmd()` method to test a command.

#### runCmd()
`runCmd($command, $argv = array())`
Runs a system command specified by `$command` optionally passing all of the parameters of `$argv` to it. Each element of `$argv` should be the full parameter passed in. For example, if a command requires the path to a file, `$argv` might look like this:

```php
$argv = array('--file-path=/path/to/file');
```

The `runCmd()` method also has a helper method named `getCmd()`. The `getCmd()` method makes it easy to generate an absolute path to a command so that it can be tested from anywhere.

```php
$command = $this->getCmd(__FILE__, 'import_data.php');
$output = $this->runCmd('php', array($command, 'arg2', 'arg3'));
```

The above example would produce a value in `$command` similar to:

```shell
/var/apps/my-app/commands/import_data.php
```

The complete command executed would look like this:

```shell
php '/var/apps/my-app/commands/import_data.php' 'arg2' 'arg3'
```

Obviously, `runCmd()` returns the output of the command. What you do with the output is up to you. You can assert that it matches some expected value, or test the database that the command did what it said it would, or both!

### Assertions
Throwback offers a small amount of assertions you can use to assert your tests are correct.

#### assertEmpty()
`assertEmpty($actual)`

Asserts that `$actual` is an empty value as tested by PHP's `empty()` method.

#### assertNotEmpty()
`assertNotEmpty($actual)`

Asserts that `$actual` is not an empty value as tested by PHP's `empty()` method.

#### assertEquals()
`assertEquals($expected, $actual)`

Asserts that `$expected` is exactly equal to `$actual` using PHP's `===` operator.

#### assertNotEquals()
`assertNotEquals($expected, $actual)`

Asserts that `$expected` is exactly not equal to `$actual` using PHP's `!==` operator.

#### assertTrue()
`assertTrue($actual)`

Asserts that `$actual` is exactly equal to `true` using PHP's `===` operator.

#### assertFalse()
`assertFalse($actual)`

Asserts that `$actual` is exactly equal to `false` using PHP's `===` operator.

#### assertContains()
`assertContains($haystack, $needle)`

Asserts that `$haystack` contains `$needle` using PHP's `strpos()` method. Matching is case sensitive.

## License
The MIT License (MIT)

Copyright (c) 2014 Vic Cherubini
