# phpcs-custom-standard
A custom standard for [PHP_CodeSniffer](http://https://github.com/squizlabs/PHP_CodeSniffer "PHP_CodeSniffer").
This implements PSR2 with a few exceptions and a few custom sniffs.

The custom sniffs include:
- **DisallowSpacesAroundObjectOperator** Takes care of an odd use case I've encountered where there may be code like `$obj -> prop` (note the spaces before and after the object operator "->"). This sniff takes care of these cases.
- **DisallowSpaceIndent** Basically an inversion of `Generic.WhiteSpace.DisallowTabIndent` this sniff will prefer tabs over spaces.

The exceptions are:
- PSR2.Classes.PropertyDeclaration
- Generic.WhiteSpace.DisallowTabIndent
- Squiz.ControlStructures.ControlSignature
- Squiz.Classes.ValidClassName
- PSR1.Classes.ClassDeclaration

Example usage:
```bash
# Path to phpcs/phpcbf depending on your installation.
# This example is for a one-off use of phpcs installed globally through composer.
# Just specify the custom standard and the file you want to check/fix
~/.composer/vendor/bin/phpcs --standard=/path/to/ruleset.xml /path/to/file.php
~/.composer/vendor/bin/phpcbf --standard=/path/to/ruleset.xml /path/to/file.php
```