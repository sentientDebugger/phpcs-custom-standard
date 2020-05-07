# phpcs-custom-standard
A custom standard for [PHP_CodeSniffer](http://https://github.com/squizlabs/PHP_CodeSniffer "PHP_CodeSniffer").
This implements PSR2 with a few exceptions and a few custom sniffs.

The exceptions are:
- PSR2.Classes.PropertyDeclaration
- Generic.WhiteSpace.DisallowTabIndent
- Squiz.ControlStructures.ControlSignature
- Squiz.Classes.ValidClassName
- PSR1.Classes.ClassDeclaration

The custom sniffs include:
- **DisallowSpacesAroundObjectOperator** Takes care of an odd use case I've encountered where there may be code like `$obj -> prop` (note the spaces before and after the object operator "->"). This sniff takes care of these cases.
- **DisallowSpaceIndent** Basically an inversion of `Generic.WhiteSpace.DisallowTabIndent` this sniff will prefer tabs over spaces.
