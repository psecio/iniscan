Scanner for PHP.ini
===========================

The Iniscan is a tool designed to scan the given php.ini file for common security practices
and report back results.

*Example usage:*

`bin/iniscan scan --path=/path/to/pnp.ini`

*Example results:*

```
Results for /private/etc/php.ini:
==========
LEVEL     | Name
--------------------
ERROR     | Cookies for sessions
WARNING   | Cookie domain set

1 passing
1 failure(s)
```