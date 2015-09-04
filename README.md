Scanner for PHP.ini
===========================

[![Build Status](https://secure.travis-ci.org/psecio/iniscan.png?branch=master)](http://travis-ci.org/psecio/iniscan)
[![Total Downloads](https://img.shields.io/packagist/dt/psecio/iniscan.svg?style=flat-square)](https://packagist.org/packages/psecio/iniscan)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/262321f3-1522-4e82-abd6-7968e108ee87/small.png)](https://insight.sensiolabs.com/projects/262321f3-1522-4e82-abd6-7968e108ee87)

The Iniscan is a tool designed to scan the given php.ini file for common security practices
and report back results. Currently it is only for use on the command line and reports the
results back to the display for both Pass and Fail on each test.

Installation
------------

### Using Composer

```shell
composer require psecio/iniscan
```


The only current dependency is the Symfony console.

### Global Composer installation
Additionally, you can install it outside of a project with the `global` functionality Composer provides. From
any directory you can use:

```
$ ./composer.phar global require psecio/iniscan
$ ~/.composer/vendor/bin/iniscan
```

### Using a single Phar file

First make sure you run composer.phar install
```
curl -LSs https://box-project.github.io/box2/installer.php | php
php box.phar build
```
This should result in a iniscan.phar file being created in the root folder.
Instead of using `vendor/bin/iniscan` in the examples use `./iniscan.phar` instead.

Example
-------
```
vendor/bin/iniscan scan --path=/path/to/php.ini
```
```
Results for /private/etc/php.ini:
============
Status | Severity | PHP Version | Key                      | Description
----------------------------------------------------------------------
PASS   | ERROR    |             | session.use_cookies      | Accepts cookies to manage sessions
PASS   | ERROR    | 4.3.0       | session.use_only_cookies | Must use cookies to manage sessions, don't accept session-ids in a link

1 passing
2 failure(s)
```

> *NOTE:* When the scan runs, if it cannot find a setting in the `php.ini` given, it will use [ini_get](http://php.net/ini_get) to pull the current setting (possibly the default).

Command line usage
------------------

*Iniscan* offers a few commands for both checking and showing the contents of your php.ini.

### Scan

The `scan` command will be the most used - it runs the rules checks against the given ini file and reports back the results. For example:

```
vendor/bin/iniscan scan --path=/path/to/php.ini
```

If the path is omitted, *iniscan* will try to find it based off the current configuration (a "php -i" call). By default, this reports back both the pass and fail results of the checks. If you'd like to only return the failures, you can use the `fail-only` argument:

```
vendor/bin/iniscan scan --path=/path/to/php.ini --fail-only
```

The `scan` command will return an exit code based on the results:

- 0: No errors
- 1: Failures found


#### Scan Level Threshold
You can request the only scan for rules that are on or above a threshold:

```
vendor/bin/iniscan scan --path=/path/to/php.ini --threshold=ERROR
```

There are 3 levels you can use:
* WARNING
* ERROR
* FATAL (No rules uses that level at the moment)


### Show

The `show` command lists out the contents of your `php.ini` file with a bit of extra formatting.

```
vendor/bin/iniscan show --path=/path/to/php.ini
```

### List

The `list-tests` command shows a listing of the current rules being checked and their related php.ini key.

```
vendor/bin/iniscan list-tests
```

### Output formats

By default *iniscan* will output information directly to the console in a human-readable result. You can also specify other output formats that may be easier to parse programatically (like JSON). Use the `--format` option to change the output:

```
vendor/bin/iniscan show --path=/path/to/php.ini --format=json
```

the `list-tests` command also supports JSON output:

```
vendor/bin/iniscan list-tests --path=/path/to/php.ini --format=json
```

**NOTE:** Currently, only the `scan` command supports alternate output formats - console, JSON, XML and HTML.

The HTML output option requires an `--output` option of the directory to write the file:

```
vendor/bin/iniscan scan --format=html --output=/var/www/output
```

The result will be written to a file named something like `iniscan-output-20131212.html`


Contexts
--------

The scanner also supports the concept of "contexts" - environments you may be executing the scanner in. For example, in your development environment, it may be okay to have `display_errors` on. In production, however, this is a bad idea. The scanner's default assumes you're using it in prod, so it uses the strictest checks unless you tell it otherwise. To do so, use the `context` command line option:

```
vendor/bin/iniscan show --path=/path/to/php.ini --context=dev
```

In this case, we've told it we're running in dev, so anything that specifically mentions "prod" isn't executed.


Deprecated reporting
--------------------

As the scanner runs, it will compare the configuration key to a list of deprecated items. If the version is at or later than the version defined in the rules, an error will be shown in the output. For example, in the console, you'd see:

```
WARNING: deprecated configuration items found:
-> register_globals
It's recommended that these settings be removed as they will be removed from future PHP versions.
```

This is default behavior and does not need to be enabled.



@author Chris Cornutt <ccornutt@phpdeveloper.org>


[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/psecio/iniscan/trend.png)](https://bitdeli.com/free "Bitdeli Badge")

