grammar CSS;

@header {package org.develnext.lexer.css;}

styleSheet
  :
    charSet?
    importDecl*
    namespace*
    statement*
    EOF
  ;

charSet
  :
    CHARSET STRING ';'
  ;

importDecl
  :
    IMPORT (STRING | URL) mediaQueryList? ';'
  ;

namespace
  :
    NAMESPACE IDENT? (STRING | URL) ';'
  ;

statement
  :
    ruleSet  #ruleDecl
  | media    #mediaDecl
  | page     #pageDecl
  | fontFace #fontFaceDecl
  ;

fontFace
  :
    FONT_FACE block
  ;

media
  :
    MEDIA mediaQueryList '{' ruleSet* '}'
  ;

mediaQueryList
  :
    ( mediaQuery (',' mediaQuery)* )?
  ;

mediaQuery
  :
    (ONLY | NOT)? IDENT (AND mediaExpression)*
  | mediaExpression (AND mediaExpression)*
  ;

mediaExpression
  :
    '(' IDENT (':' expression)? ')'
  ;

page
  :
    PAGE pseudoPage? block
  ;

pseudoPage
  :
    ':' IDENT
  ;

ruleSet
  :
    selectorGroup block
  ;

selectorGroup
  :
    selector (',' selector)*
  ;

selector
  :
    selectorType+ combinator*
  ;

combinator
  :
    COMBINATOR=('+' | '>' | '~') selectorType+
  ;

selectorType
  :
    '*' '|' '*'                #universalNamepaceUniversalSelector
  | prefix=IDENT '|' '*'       #identNamespaceUniversalSelector
  | '|' '*'                    #nonamespaceUniversalSelector
  | '*' '|' IDENT              #univesalNamespaceTypeSelector
  | prefix=IDENT '|' id=IDENT  #identNamespaceTypeSelector
  | '|' IDENT                  #nonamespaceTypeSelector
  | '*'                        #universalSelector
  | IDENT                      #typeSelector
  | HASH                       #idSelector
  | CLASS                      #classSelector
  | attribute                  #attributeSelector
  | pseudo                     #pseudoSelector
  | negation                   #notSelector
  ;

attribute
  :
    '['
      ((prefix=IDENT | prefix='*')? '|')? name=IDENT
        (
          operator=('^=' | '$=' | '*=' | '=' | '~=' | '|=') (value=IDENT | value=STRING)
        )?
    ']'
  ;

pseudo
  :
    ':' twoColon=':'? (id=IDENT | functionalPseudo)
  ;

functionalPseudo
  :
    IDENT '(' expression ')'
  ;

negation
  :
    ':' NOT '(' selectorType ')'
  ;

block
  :
    '{' declaration? (';' declaration?)* '}'
  ;

declaration
  :
    IDENT ':' expression priority?
  ;

priority
  :
    '!' IMPORTANT
  ;

expression
  :
    left=term ((',' | '/')? right+=term)*
  ;

term
  :
    number    #numberExpr
  | STRING    #stringExpr
  | IDENT     #idExpr
  | URL       #urlExpr
  | HEX_COLOR #hexColorExpr
  | calc      #calcExpr
  | function  #functionExpr
  ;

calc
  :
    'calc' '(' sum ')'
  ;

sum
  :
    product (('+' | '-') product)*
  ;

product
  :
    unit (('*' unit | '/' NUMBER))*
  ;

attributeReference
  :
    'attr' + '(' name=IDENT (type=IDENT)? (',' (unit | calc))? ')'
  ;

unit
  :
    NUMBER      #calcNumberDecl
  | '(' sum ')' #calcSumDecl
  | calc        #calcDecl
  | attributeReference #attributeReferenceDecl
  ;

function
  :
   IDENT '(' expression ')'
  ;

number
  :
    ('+' | '-')? NUMBER
  ;

NUMBER
  :
    NUM UNIT?
  ;

fragment
UNIT
  :
    PERCENTAGE
  | DISTANCE
  | RESOLUTION
  | ANGLE
  | TIME
  | FREQUENCY
  ;

fragment
PERCENTAGE
  :
    '%'
  ;

fragment
DISTANCE
  :
  // relative length
    [eE][mM]
  | [eE][xX]
  | [cC][hH]
  | [rR][eE][mM]
  | [vV][wW]
  | [vV][hH]
  | [vV][mM][iI][nN]
  | [vV][mM][aA][xX]
  // absolute length
  | [cC][mM]
  | [mM][mM]
  | [iI][nN]
  | [pP][xX]
  | [pP][tT]
  | [pP][cC]
  ;

fragment
ANGLE
  :
    [dD][eE][gG]
  | [gG][rR][aA][dD]
  | [rR][aA][dD]
  | [tT][uU][rR][nN]
  ;

fragment
TIME
  :
    [sS]
  | [mM][sS]
  ;

fragment
FREQUENCY
  :
    [hH][zZ]
  | [kK][hH][zZ]
  ;

fragment
RESOLUTION
  :
    [dD][pP][iI]
  | [dD][pP][cC][mM]
  | [dD][pP][pP][xX]
  ;

fragment
NUM
  :
    INT
  | FLOAT
  ;

NAMESPACE
  :
    '@'[nN][aA][mM][eE][sS][pP][aA][cC][eE]
  ;

IMPORTANT
  :
    [iI][mM][pP][oO][rR][tT][aA][nN][tT]
  ;

IMPORT
  :
    '@' [iI][mM][pP][oO][rR][tT]
  ;

CHARSET
  :
    '@'[cC][hH][aA][rR][sS][eE][tT]
  ;

FONT_FACE
  :
    '@'[fF][oO][nN][tT]'-'[fF][aA][cC][eE]
  ;

MEDIA
  :
    '@' [mM][eE][dD][iI][aA]
  ;

PAGE
  :
    '@' [pP][aA][gG][eE]
  ;

ONLY
  :
    [oO][nN][lL][yY]
  ;

NOT
  :
    [nN][oO][tT]
  ;

AND
  :
    [aA][nN][dD]
  ;

URL
  :
    [uU][rR][lL] '(' STRING ')'
  | [uU][rR][lL] '(' .*? ')'
  ;

CLASS
  :
    '.' IDENT
  ;

IDENT
  :
    '-'? NMSTART NMCHAR*
  ;

fragment
FLOAT
  :
    DIGIT* '.' DIGIT+
  ;

fragment
INT
  :
    DIGIT+
  ;

HEX_COLOR
  :
    '#' HEX HEX HEX
  | '#' HEX HEX HEX HEX HEX HEX
  ;

HASH
  :
    '#' NAME
  ;

fragment
NAME
  :
    NMCHAR+
  ;

fragment
NMSTART
  :
    [a-zA-Z_]
  | NONASCII
  | ESCAPE
  ;

fragment
NMCHAR
  :
    [a-zA-Z_\-]
  | DIGIT
  | NONASCII
  | ESCAPE
  ;

fragment
NONASCII
  :
    [\u0100-\uFFFE]
  ;

fragment
ESCAPE
  :
    '\\' (["\\/bfnrt] | UNICODE)
  ;

fragment
UNICODE
  :
    'u' HEX HEX HEX HEX
  ;

fragment
HEX
  :
    [a-fA-F]
  | DIGIT
  ;

fragment
DIGIT
  :
    [0-9]
  ;

STRING
  :
    '"'  (ESC | (~[\r\n]))*? '"'
  | '\'' (ESC | (~[\r\n]))*? '\''
  ;

fragment
ESC
  :
    '\\' ('"' | '\'' | '\n')
  ;

XML_COMMENT
  :
    '<!--' .*? '-->'
  ;

COMMENT
  :
    '/*' .*? '*/'
  ;

fragment
SPACE
  :
    [ \t\r\n\f]
  ;

WS
  :
    SPACE+ -> skip
  ;
