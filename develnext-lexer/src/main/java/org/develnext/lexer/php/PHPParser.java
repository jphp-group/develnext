// Generated from org\develnext\lexer\php\PHPParser.g4 by ANTLR 4.5.3
package org.develnext.lexer.php;

import org.antlr.v4.runtime.*;
import org.antlr.v4.runtime.atn.ATN;
import org.antlr.v4.runtime.atn.ATNDeserializer;
import org.antlr.v4.runtime.atn.ParserATNSimulator;
import org.antlr.v4.runtime.atn.PredictionContextCache;
import org.antlr.v4.runtime.dfa.DFA;
import org.antlr.v4.runtime.tree.ParseTreeListener;
import org.antlr.v4.runtime.tree.ParseTreeVisitor;
import org.antlr.v4.runtime.tree.TerminalNode;

import java.util.List;

@SuppressWarnings({"all", "warnings", "unchecked", "unused", "cast"})
public class PHPParser extends Parser {
	static { RuntimeMetaData.checkVersion("4.5.3", RuntimeMetaData.VERSION); }

	protected static final DFA[] _decisionToDFA;
	protected static final PredictionContextCache _sharedContextCache =
		new PredictionContextCache();
	public static final int
		SeaWhitespace=1, HtmlText=2, PHPStart=3, HtmlScriptOpen=4, HtmlStyleOpen=5, 
		HtmlComment=6, HtmlDtd=7, HtmlOpen=8, Shebang=9, PHPStartInside=10, HtmlClose=11, 
		HtmlSlashClose=12, HtmlSlash=13, HtmlEquals=14, HtmlStartQuoteString=15, 
		HtmlStartDoubleQuoteString=16, HtmlHex=17, HtmlDecimal=18, HtmlSpace=19, 
		HtmlName=20, PHPStartInsideQuoteString=21, HtmlEndQuoteString=22, HtmlQuoteString=23, 
		PHPStartDoubleQuoteString=24, HtmlEndDoubleQuoteString=25, HtmlDoubleQuoteString=26, 
		ScriptText=27, ScriptClose=28, PHPStartInsideScript=29, StyleBody=30, 
		PHPEnd=31, Whitespace=32, MultiLineComment=33, SingleLineComment=34, ShellStyleComment=35, 
		Abstract=36, Array=37, As=38, BinaryCast=39, BoolType=40, BooleanConstant=41, 
		Break=42, Callable=43, Case=44, Catch=45, Class=46, Clone=47, Const=48, 
		Continue=49, Declare=50, Default=51, Do=52, DoubleCast=53, DoubleType=54, 
		Echo=55, Else=56, ElseIf=57, Empty=58, EndDeclare=59, EndFor=60, EndForeach=61, 
		EndIf=62, EndSwitch=63, EndWhile=64, Eval=65, Exit=66, Extends=67, Final=68, 
		Finally=69, FloatCast=70, For=71, Foreach=72, Function=73, Global=74, 
		Goto=75, If=76, Implements=77, Import=78, Include=79, IncludeOnce=80, 
		InstanceOf=81, InsteadOf=82, Int16Cast=83, Int64Type=84, Int8Cast=85, 
		Interface=86, IntType=87, IsSet=88, List=89, LogicalAnd=90, LogicalOr=91, 
		LogicalXor=92, Namespace=93, New=94, Null=95, ObjectType=96, Parent_=97, 
		Partial=98, Print=99, Private=100, Protected=101, Public=102, Require=103, 
		RequireOnce=104, Resource=105, Return=106, Static=107, StringType=108, 
		Switch=109, Throw=110, Trait=111, Try=112, Typeof=113, Uint16Cast=114, 
		Uint32Cast=115, Uint64Cast=116, Uint8Cast=117, UnicodeCast=118, Unset=119, 
		Use=120, Var=121, While=122, Yield=123, Get=124, Set=125, Call=126, CallStatic=127, 
		Constructor=128, Destruct=129, Wakeup=130, Sleep=131, Autoload=132, IsSet__=133, 
		Unset__=134, ToString__=135, Invoke=136, SetState=137, Clone__=138, DebugInfo=139, 
		Namespace__=140, Class__=141, Traic__=142, Function__=143, Method__=144, 
		Line__=145, File__=146, Dir__=147, Lgeneric=148, Rgeneric=149, DoubleArrow=150, 
		Inc=151, Dec=152, IsIdentical=153, IsNoidentical=154, IsEqual=155, IsNotEq=156, 
		IsSmallerOrEqual=157, IsGreaterOrEqual=158, PlusEqual=159, MinusEqual=160, 
		MulEqual=161, Pow=162, PowEqual=163, DivEqual=164, Concaequal=165, ModEqual=166, 
		ShiftLeftEqual=167, ShiftRightEqual=168, AndEqual=169, OrEqual=170, XorEqual=171, 
		BooleanOr=172, BooleanAnd=173, ShiftLeft=174, ShiftRight=175, DoubleColon=176, 
		ObjectOperator=177, NamespaceSeparator=178, Ellipsis=179, Less=180, Greater=181, 
		Ampersand=182, Pipe=183, Bang=184, Caret=185, Plus=186, Minus=187, Asterisk=188, 
		Percent=189, Divide=190, Tilde=191, SuppressWarnings=192, Dollar=193, 
		Dot=194, QuestionMark=195, OpenRoundBracket=196, CloseRoundBracket=197, 
		OpenSquareBracket=198, CloseSquareBracket=199, OpenCurlyBracket=200, CloseCurlyBracket=201, 
		Comma=202, Colon=203, SemiColon=204, Eq=205, DoubleQuote=206, Quote=207, 
		BackQuote=208, Label=209, VarName=210, Numeric=211, Real=212, EscapeCharacter=213, 
		BackQuoteString=214, SingleQuoteString=215, DoubleQuoteString=216, StartNowDoc=217, 
		StartHereDoc=218, Comment=219, PHPEndSingleLineComment=220, CommentEnd=221, 
		HereDocText=222;
	public static final int
		RULE_htmlDocument = 0, RULE_htmlElementOrPhpBlock = 1, RULE_htmlElement = 2, 
		RULE_htmlContent = 3, RULE_htmlAttribute = 4, RULE_htmlQuotePhpBlockOrString = 5, 
		RULE_htmlDoubleQuotePhpBlockOrString = 6, RULE_scriptText = 7, RULE_scriptTextPart = 8, 
		RULE_phpBlock = 9, RULE_importStatement = 10, RULE_topStatement = 11, 
		RULE_useDeclaration = 12, RULE_useDeclarationContentList = 13, RULE_useDeclarationContent = 14, 
		RULE_namespaceDeclaration = 15, RULE_namespaceStatement = 16, RULE_functionDeclaration = 17, 
		RULE_classDeclaration = 18, RULE_classEntryType = 19, RULE_interfaceList = 20, 
		RULE_typeParameterListInBrackets = 21, RULE_typeParameterList = 22, RULE_typeParameterWithDefaultsList = 23, 
		RULE_typeParameterDecl = 24, RULE_typeParameterWithDefaultDecl = 25, RULE_genericDynamicArgs = 26, 
		RULE_attributes = 27, RULE_attributesGroup = 28, RULE_attribute = 29, 
		RULE_attributeArgList = 30, RULE_attributeNamedArgList = 31, RULE_attributeNamedArg = 32, 
		RULE_innerStatementList = 33, RULE_innerStatement = 34, RULE_statement = 35, 
		RULE_emptyStatement = 36, RULE_nonEmptyStatement = 37, RULE_blockStatement = 38, 
		RULE_ifStatement = 39, RULE_elseIfStatement = 40, RULE_elseIfColonStatement = 41, 
		RULE_elseStatement = 42, RULE_elseColonStatement = 43, RULE_whileStatement = 44, 
		RULE_doWhileStatement = 45, RULE_forStatement = 46, RULE_forInit = 47, 
		RULE_forUpdate = 48, RULE_switchStatement = 49, RULE_switchBlock = 50, 
		RULE_breakStatement = 51, RULE_continueStatement = 52, RULE_returnStatement = 53, 
		RULE_expressionStatement = 54, RULE_unsetStatement = 55, RULE_foreachStatement = 56, 
		RULE_tryCatchFinally = 57, RULE_catchClause = 58, RULE_finallyStatement = 59, 
		RULE_throwStatement = 60, RULE_gotoStatement = 61, RULE_declareStatement = 62, 
		RULE_inlineHtml = 63, RULE_declareList = 64, RULE_formalParameterList = 65, 
		RULE_formalParameter = 66, RULE_typeHint = 67, RULE_globalStatement = 68, 
		RULE_globalVar = 69, RULE_echoStatement = 70, RULE_staticVariableStatement = 71, 
		RULE_classStatement = 72, RULE_traitAdaptations = 73, RULE_traitAdaptationStatement = 74, 
		RULE_traitPrecedence = 75, RULE_traitAlias = 76, RULE_traitMethodReference = 77, 
		RULE_baseCtorCall = 78, RULE_methodBody = 79, RULE_propertyModifiers = 80, 
		RULE_memberModifiers = 81, RULE_variableInitializer = 82, RULE_identifierInititalizer = 83, 
		RULE_globalConstantDeclaration = 84, RULE_expressionList = 85, RULE_parenthesis = 86, 
		RULE_expression = 87, RULE_newExpr = 88, RULE_assignmentOperator = 89, 
		RULE_yieldExpression = 90, RULE_arrayItemList = 91, RULE_arrayItem = 92, 
		RULE_lambdaFunctionUseVars = 93, RULE_lambdaFunctionUseVar = 94, RULE_qualifiedStaticTypeRef = 95, 
		RULE_typeRef = 96, RULE_indirectTypeRef = 97, RULE_qualifiedNamespaceName = 98, 
		RULE_namespaceNameList = 99, RULE_qualifiedNamespaceNameList = 100, RULE_arguments = 101, 
		RULE_actualArgument = 102, RULE_constantInititalizer = 103, RULE_constantArrayItemList = 104, 
		RULE_constantArrayItem = 105, RULE_constant = 106, RULE_literalConstant = 107, 
		RULE_classConstant = 108, RULE_stringConstant = 109, RULE_string = 110, 
		RULE_chainList = 111, RULE_chain = 112, RULE_memberAccess = 113, RULE_functionCall = 114, 
		RULE_functionCallName = 115, RULE_actualArguments = 116, RULE_chainBase = 117, 
		RULE_keyedFieldName = 118, RULE_keyedSimpleFieldName = 119, RULE_keyedVariable = 120, 
		RULE_squareCurlyExpression = 121, RULE_assignmentList = 122, RULE_assignmentListElement = 123, 
		RULE_modifier = 124, RULE_identifier = 125, RULE_memberModifier = 126, 
		RULE_magicConstant = 127, RULE_magicMethod = 128, RULE_primitiveType = 129, 
		RULE_castOperation = 130;
	public static final String[] ruleNames = {
		"htmlDocument", "htmlElementOrPhpBlock", "htmlElement", "htmlContent", 
		"htmlAttribute", "htmlQuotePhpBlockOrString", "htmlDoubleQuotePhpBlockOrString", 
		"scriptText", "scriptTextPart", "phpBlock", "importStatement", "topStatement", 
		"useDeclaration", "useDeclarationContentList", "useDeclarationContent", 
		"namespaceDeclaration", "namespaceStatement", "functionDeclaration", "classDeclaration", 
		"classEntryType", "interfaceList", "typeParameterListInBrackets", "typeParameterList", 
		"typeParameterWithDefaultsList", "typeParameterDecl", "typeParameterWithDefaultDecl", 
		"genericDynamicArgs", "attributes", "attributesGroup", "attribute", "attributeArgList", 
		"attributeNamedArgList", "attributeNamedArg", "innerStatementList", "innerStatement", 
		"statement", "emptyStatement", "nonEmptyStatement", "blockStatement", 
		"ifStatement", "elseIfStatement", "elseIfColonStatement", "elseStatement", 
		"elseColonStatement", "whileStatement", "doWhileStatement", "forStatement", 
		"forInit", "forUpdate", "switchStatement", "switchBlock", "breakStatement", 
		"continueStatement", "returnStatement", "expressionStatement", "unsetStatement", 
		"foreachStatement", "tryCatchFinally", "catchClause", "finallyStatement", 
		"throwStatement", "gotoStatement", "declareStatement", "inlineHtml", "declareList", 
		"formalParameterList", "formalParameter", "typeHint", "globalStatement", 
		"globalVar", "echoStatement", "staticVariableStatement", "classStatement", 
		"traitAdaptations", "traitAdaptationStatement", "traitPrecedence", "traitAlias", 
		"traitMethodReference", "baseCtorCall", "methodBody", "propertyModifiers", 
		"memberModifiers", "variableInitializer", "identifierInititalizer", "globalConstantDeclaration", 
		"expressionList", "parenthesis", "expression", "newExpr", "assignmentOperator", 
		"yieldExpression", "arrayItemList", "arrayItem", "lambdaFunctionUseVars", 
		"lambdaFunctionUseVar", "qualifiedStaticTypeRef", "typeRef", "indirectTypeRef", 
		"qualifiedNamespaceName", "namespaceNameList", "qualifiedNamespaceNameList", 
		"arguments", "actualArgument", "constantInititalizer", "constantArrayItemList", 
		"constantArrayItem", "constant", "literalConstant", "classConstant", "stringConstant", 
		"string", "chainList", "chain", "memberAccess", "functionCall", "functionCallName", 
		"actualArguments", "chainBase", "keyedFieldName", "keyedSimpleFieldName", 
		"keyedVariable", "squareCurlyExpression", "assignmentList", "assignmentListElement", 
		"modifier", "identifier", "memberModifier", "magicConstant", "magicMethod", 
		"primitiveType", "castOperation"
	};

	private static final String[] _LITERAL_NAMES = {
		null, null, null, null, null, null, null, null, null, null, null, null, 
		"'/>'", null, null, null, null, null, null, null, null, null, null, null, 
		null, null, null, null, null, null, null, null, null, null, "'//'", "'#'", 
		null, null, null, null, null, null, null, null, null, null, null, null, 
		null, null, null, null, null, null, null, null, null, null, null, null, 
		null, null, null, null, null, null, null, null, null, null, null, null, 
		null, null, null, null, null, null, null, null, null, null, null, null, 
		null, null, null, null, null, null, null, null, null, null, null, null, 
		null, null, null, null, null, null, null, null, null, null, null, null, 
		null, null, null, null, null, null, null, null, null, null, null, null, 
		null, null, null, null, null, null, null, null, null, null, null, null, 
		null, null, null, null, null, null, null, null, null, null, null, null, 
		null, null, null, null, "'<:'", "':>'", "'=>'", "'++'", "'--'", "'==='", 
		"'!=='", "'=='", null, "'<='", "'>='", "'+='", "'-='", "'*='", "'**'", 
		"'**='", "'/='", "'.='", "'%='", "'<<='", "'>>='", "'&='", "'|='", "'^='", 
		"'||'", "'&&'", "'<<'", "'>>'", "'::'", "'->'", "'\\'", "'...'", null, 
		null, "'&'", "'|'", "'!'", "'^'", "'+'", "'-'", "'*'", "'%'", null, "'~'", 
		"'@'", "'$'", "'.'", "'?'", "'('", "')'", "'['", "']'", "'{'", "'}'", 
		"','", "':'", "';'", null, null, null, "'`'"
	};
	private static final String[] _SYMBOLIC_NAMES = {
		null, "SeaWhitespace", "HtmlText", "PHPStart", "HtmlScriptOpen", "HtmlStyleOpen", 
		"HtmlComment", "HtmlDtd", "HtmlOpen", "Shebang", "PHPStartInside", "HtmlClose", 
		"HtmlSlashClose", "HtmlSlash", "HtmlEquals", "HtmlStartQuoteString", "HtmlStartDoubleQuoteString", 
		"HtmlHex", "HtmlDecimal", "HtmlSpace", "HtmlName", "PHPStartInsideQuoteString", 
		"HtmlEndQuoteString", "HtmlQuoteString", "PHPStartDoubleQuoteString", 
		"HtmlEndDoubleQuoteString", "HtmlDoubleQuoteString", "ScriptText", "ScriptClose", 
		"PHPStartInsideScript", "StyleBody", "PHPEnd", "Whitespace", "MultiLineComment", 
		"SingleLineComment", "ShellStyleComment", "Abstract", "Array", "As", "BinaryCast", 
		"BoolType", "BooleanConstant", "Break", "Callable", "Case", "Catch", "Class", 
		"Clone", "Const", "Continue", "Declare", "Default", "Do", "DoubleCast", 
		"DoubleType", "Echo", "Else", "ElseIf", "Empty", "EndDeclare", "EndFor", 
		"EndForeach", "EndIf", "EndSwitch", "EndWhile", "Eval", "Exit", "Extends", 
		"Final", "Finally", "FloatCast", "For", "Foreach", "Function", "Global", 
		"Goto", "If", "Implements", "Import", "Include", "IncludeOnce", "InstanceOf", 
		"InsteadOf", "Int16Cast", "Int64Type", "Int8Cast", "Interface", "IntType", 
		"IsSet", "List", "LogicalAnd", "LogicalOr", "LogicalXor", "Namespace", 
		"New", "Null", "ObjectType", "Parent_", "Partial", "Print", "Private", 
		"Protected", "Public", "Require", "RequireOnce", "Resource", "Return", 
		"Static", "StringType", "Switch", "Throw", "Trait", "Try", "Typeof", "Uint16Cast", 
		"Uint32Cast", "Uint64Cast", "Uint8Cast", "UnicodeCast", "Unset", "Use", 
		"Var", "While", "Yield", "Get", "Set", "Call", "CallStatic", "Constructor", 
		"Destruct", "Wakeup", "Sleep", "Autoload", "IsSet__", "Unset__", "ToString__", 
		"Invoke", "SetState", "Clone__", "DebugInfo", "Namespace__", "Class__", 
		"Traic__", "Function__", "Method__", "Line__", "File__", "Dir__", "Lgeneric", 
		"Rgeneric", "DoubleArrow", "Inc", "Dec", "IsIdentical", "IsNoidentical", 
		"IsEqual", "IsNotEq", "IsSmallerOrEqual", "IsGreaterOrEqual", "PlusEqual", 
		"MinusEqual", "MulEqual", "Pow", "PowEqual", "DivEqual", "Concaequal", 
		"ModEqual", "ShiftLeftEqual", "ShiftRightEqual", "AndEqual", "OrEqual", 
		"XorEqual", "BooleanOr", "BooleanAnd", "ShiftLeft", "ShiftRight", "DoubleColon", 
		"ObjectOperator", "NamespaceSeparator", "Ellipsis", "Less", "Greater", 
		"Ampersand", "Pipe", "Bang", "Caret", "Plus", "Minus", "Asterisk", "Percent", 
		"Divide", "Tilde", "SuppressWarnings", "Dollar", "Dot", "QuestionMark", 
		"OpenRoundBracket", "CloseRoundBracket", "OpenSquareBracket", "CloseSquareBracket", 
		"OpenCurlyBracket", "CloseCurlyBracket", "Comma", "Colon", "SemiColon", 
		"Eq", "DoubleQuote", "Quote", "BackQuote", "Label", "VarName", "Numeric", 
		"Real", "EscapeCharacter", "BackQuoteString", "SingleQuoteString", "DoubleQuoteString", 
		"StartNowDoc", "StartHereDoc", "Comment", "PHPEndSingleLineComment", "CommentEnd", 
		"HereDocText"
	};
	public static final Vocabulary VOCABULARY = new VocabularyImpl(_LITERAL_NAMES, _SYMBOLIC_NAMES);

	/**
	 * @deprecated Use {@link #VOCABULARY} instead.
	 */
	@Deprecated
	public static final String[] tokenNames;
	static {
		tokenNames = new String[_SYMBOLIC_NAMES.length];
		for (int i = 0; i < tokenNames.length; i++) {
			tokenNames[i] = VOCABULARY.getLiteralName(i);
			if (tokenNames[i] == null) {
				tokenNames[i] = VOCABULARY.getSymbolicName(i);
			}

			if (tokenNames[i] == null) {
				tokenNames[i] = "<INVALID>";
			}
		}
	}

	@Override
	@Deprecated
	public String[] getTokenNames() {
		return tokenNames;
	}

	@Override

	public Vocabulary getVocabulary() {
		return VOCABULARY;
	}

	@Override
	public String getGrammarFileName() { return "PHPParser.g4"; }

	@Override
	public String[] getRuleNames() { return ruleNames; }

	@Override
	public String getSerializedATN() { return _serializedATN; }

	@Override
	public ATN getATN() { return _ATN; }

	public PHPParser(TokenStream input) {
		super(input);
		_interp = new ParserATNSimulator(this,_ATN,_decisionToDFA,_sharedContextCache);
	}
	public static class HtmlDocumentContext extends ParserRuleContext {
		public TerminalNode EOF() { return getToken(PHPParser.EOF, 0); }
		public TerminalNode Shebang() { return getToken(PHPParser.Shebang, 0); }
		public List<TerminalNode> HtmlComment() { return getTokens(PHPParser.HtmlComment); }
		public TerminalNode HtmlComment(int i) {
			return getToken(PHPParser.HtmlComment, i);
		}
		public List<HtmlElementOrPhpBlockContext> htmlElementOrPhpBlock() {
			return getRuleContexts(HtmlElementOrPhpBlockContext.class);
		}
		public HtmlElementOrPhpBlockContext htmlElementOrPhpBlock(int i) {
			return getRuleContext(HtmlElementOrPhpBlockContext.class,i);
		}
		public HtmlDocumentContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_htmlDocument; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterHtmlDocument(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitHtmlDocument(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitHtmlDocument(this);
			else return visitor.visitChildren(this);
		}
	}

	public final HtmlDocumentContext htmlDocument() throws RecognitionException {
		HtmlDocumentContext _localctx = new HtmlDocumentContext(_ctx, getState());
		enterRule(_localctx, 0, RULE_htmlDocument);
		int _la;
		try {
			int _alt;
			enterOuterAlt(_localctx, 1);
			{
			setState(263);
			_la = _input.LA(1);
			if (_la==Shebang) {
				{
				setState(262);
				match(Shebang);
				}
			}

			setState(268);
			_errHandler.sync(this);
			_alt = getInterpreter().adaptivePredict(_input,1,_ctx);
			while ( _alt!=2 && _alt!= ATN.INVALID_ALT_NUMBER ) {
				if ( _alt==1 ) {
					{
					{
					setState(265);
					match(HtmlComment);
					}
					}
				}
				setState(270);
				_errHandler.sync(this);
				_alt = getInterpreter().adaptivePredict(_input,1,_ctx);
			}
			setState(274);
			_errHandler.sync(this);
			_la = _input.LA(1);
			while ((((_la) & ~0x3f) == 0 && ((1L << _la) & ((1L << HtmlScriptOpen) | (1L << HtmlStyleOpen) | (1L << HtmlComment) | (1L << HtmlDtd) | (1L << HtmlOpen) | (1L << Abstract) | (1L << Array) | (1L << BinaryCast) | (1L << BoolType) | (1L << BooleanConstant) | (1L << Break) | (1L << Callable) | (1L << Case) | (1L << Class) | (1L << Clone) | (1L << Const) | (1L << Continue) | (1L << Declare) | (1L << Default) | (1L << Do) | (1L << DoubleCast) | (1L << DoubleType) | (1L << Echo) | (1L << Empty))) != 0) || ((((_la - 65)) & ~0x3f) == 0 && ((1L << (_la - 65)) & ((1L << (Eval - 65)) | (1L << (Exit - 65)) | (1L << (Final - 65)) | (1L << (FloatCast - 65)) | (1L << (For - 65)) | (1L << (Foreach - 65)) | (1L << (Function - 65)) | (1L << (Global - 65)) | (1L << (Goto - 65)) | (1L << (If - 65)) | (1L << (Import - 65)) | (1L << (Include - 65)) | (1L << (IncludeOnce - 65)) | (1L << (Int16Cast - 65)) | (1L << (Int64Type - 65)) | (1L << (Int8Cast - 65)) | (1L << (Interface - 65)) | (1L << (IntType - 65)) | (1L << (IsSet - 65)) | (1L << (List - 65)) | (1L << (LogicalAnd - 65)) | (1L << (LogicalOr - 65)) | (1L << (LogicalXor - 65)) | (1L << (Namespace - 65)) | (1L << (New - 65)) | (1L << (Null - 65)) | (1L << (ObjectType - 65)) | (1L << (Parent_ - 65)) | (1L << (Partial - 65)) | (1L << (Print - 65)) | (1L << (Private - 65)) | (1L << (Protected - 65)) | (1L << (Public - 65)) | (1L << (Require - 65)) | (1L << (RequireOnce - 65)) | (1L << (Resource - 65)) | (1L << (Return - 65)) | (1L << (Static - 65)) | (1L << (StringType - 65)) | (1L << (Switch - 65)) | (1L << (Throw - 65)) | (1L << (Trait - 65)) | (1L << (Try - 65)) | (1L << (Uint16Cast - 65)) | (1L << (Uint32Cast - 65)) | (1L << (Uint64Cast - 65)) | (1L << (Uint8Cast - 65)) | (1L << (UnicodeCast - 65)) | (1L << (Unset - 65)) | (1L << (Use - 65)) | (1L << (Var - 65)) | (1L << (While - 65)) | (1L << (Yield - 65)) | (1L << (Get - 65)) | (1L << (Set - 65)) | (1L << (Call - 65)) | (1L << (CallStatic - 65)) | (1L << (Constructor - 65)))) != 0) || ((((_la - 129)) & ~0x3f) == 0 && ((1L << (_la - 129)) & ((1L << (Destruct - 129)) | (1L << (Wakeup - 129)) | (1L << (Sleep - 129)) | (1L << (Autoload - 129)) | (1L << (IsSet__ - 129)) | (1L << (Unset__ - 129)) | (1L << (ToString__ - 129)) | (1L << (Invoke - 129)) | (1L << (SetState - 129)) | (1L << (Clone__ - 129)) | (1L << (DebugInfo - 129)) | (1L << (Namespace__ - 129)) | (1L << (Class__ - 129)) | (1L << (Traic__ - 129)) | (1L << (Function__ - 129)) | (1L << (Method__ - 129)) | (1L << (Line__ - 129)) | (1L << (File__ - 129)) | (1L << (Dir__ - 129)) | (1L << (Inc - 129)) | (1L << (Dec - 129)) | (1L << (NamespaceSeparator - 129)) | (1L << (Bang - 129)) | (1L << (Plus - 129)) | (1L << (Minus - 129)) | (1L << (Tilde - 129)) | (1L << (SuppressWarnings - 129)))) != 0) || ((((_la - 193)) & ~0x3f) == 0 && ((1L << (_la - 193)) & ((1L << (Dollar - 193)) | (1L << (OpenRoundBracket - 193)) | (1L << (OpenSquareBracket - 193)) | (1L << (OpenCurlyBracket - 193)) | (1L << (SemiColon - 193)) | (1L << (Label - 193)) | (1L << (VarName - 193)) | (1L << (Numeric - 193)) | (1L << (Real - 193)) | (1L << (BackQuoteString - 193)) | (1L << (SingleQuoteString - 193)) | (1L << (DoubleQuoteString - 193)) | (1L << (StartNowDoc - 193)) | (1L << (StartHereDoc - 193)))) != 0)) {
				{
				{
				setState(271);
				htmlElementOrPhpBlock();
				}
				}
				setState(276);
				_errHandler.sync(this);
				_la = _input.LA(1);
			}
			setState(277);
			match(EOF);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class HtmlElementOrPhpBlockContext extends ParserRuleContext {
		public TerminalNode HtmlDtd() { return getToken(PHPParser.HtmlDtd, 0); }
		public HtmlElementContext htmlElement() {
			return getRuleContext(HtmlElementContext.class,0);
		}
		public PhpBlockContext phpBlock() {
			return getRuleContext(PhpBlockContext.class,0);
		}
		public List<TerminalNode> HtmlComment() { return getTokens(PHPParser.HtmlComment); }
		public TerminalNode HtmlComment(int i) {
			return getToken(PHPParser.HtmlComment, i);
		}
		public HtmlElementOrPhpBlockContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_htmlElementOrPhpBlock; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterHtmlElementOrPhpBlock(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitHtmlElementOrPhpBlock(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitHtmlElementOrPhpBlock(this);
			else return visitor.visitChildren(this);
		}
	}

	public final HtmlElementOrPhpBlockContext htmlElementOrPhpBlock() throws RecognitionException {
		HtmlElementOrPhpBlockContext _localctx = new HtmlElementOrPhpBlockContext(_ctx, getState());
		enterRule(_localctx, 2, RULE_htmlElementOrPhpBlock);
		try {
			int _alt;
			enterOuterAlt(_localctx, 1);
			{
			setState(282);
			_errHandler.sync(this);
			switch ( getInterpreter().adaptivePredict(_input,3,_ctx) ) {
			case 1:
				{
				setState(279);
				match(HtmlDtd);
				}
				break;
			case 2:
				{
				setState(280);
				htmlElement();
				}
				break;
			case 3:
				{
				setState(281);
				phpBlock();
				}
				break;
			}
			setState(287);
			_errHandler.sync(this);
			_alt = getInterpreter().adaptivePredict(_input,4,_ctx);
			while ( _alt!=2 && _alt!= ATN.INVALID_ALT_NUMBER ) {
				if ( _alt==1 ) {
					{
					{
					setState(284);
					match(HtmlComment);
					}
					}
				}
				setState(289);
				_errHandler.sync(this);
				_alt = getInterpreter().adaptivePredict(_input,4,_ctx);
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class HtmlElementContext extends ParserRuleContext {
		public TerminalNode HtmlScriptOpen() { return getToken(PHPParser.HtmlScriptOpen, 0); }
		public List<TerminalNode> HtmlClose() { return getTokens(PHPParser.HtmlClose); }
		public TerminalNode HtmlClose(int i) {
			return getToken(PHPParser.HtmlClose, i);
		}
		public ScriptTextContext scriptText() {
			return getRuleContext(ScriptTextContext.class,0);
		}
		public TerminalNode ScriptClose() { return getToken(PHPParser.ScriptClose, 0); }
		public List<HtmlAttributeContext> htmlAttribute() {
			return getRuleContexts(HtmlAttributeContext.class);
		}
		public HtmlAttributeContext htmlAttribute(int i) {
			return getRuleContext(HtmlAttributeContext.class,i);
		}
		public TerminalNode HtmlStyleOpen() { return getToken(PHPParser.HtmlStyleOpen, 0); }
		public TerminalNode StyleBody() { return getToken(PHPParser.StyleBody, 0); }
		public List<TerminalNode> HtmlOpen() { return getTokens(PHPParser.HtmlOpen); }
		public TerminalNode HtmlOpen(int i) {
			return getToken(PHPParser.HtmlOpen, i);
		}
		public List<TerminalNode> HtmlName() { return getTokens(PHPParser.HtmlName); }
		public TerminalNode HtmlName(int i) {
			return getToken(PHPParser.HtmlName, i);
		}
		public HtmlContentContext htmlContent() {
			return getRuleContext(HtmlContentContext.class,0);
		}
		public TerminalNode HtmlSlash() { return getToken(PHPParser.HtmlSlash, 0); }
		public HtmlElementContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_htmlElement; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterHtmlElement(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitHtmlElement(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitHtmlElement(this);
			else return visitor.visitChildren(this);
		}
	}

	public final HtmlElementContext htmlElement() throws RecognitionException {
		HtmlElementContext _localctx = new HtmlElementContext(_ctx, getState());
		enterRule(_localctx, 4, RULE_htmlElement);
		int _la;
		try {
			setState(336);
			_errHandler.sync(this);
			switch ( getInterpreter().adaptivePredict(_input,10,_ctx) ) {
			case 1:
				enterOuterAlt(_localctx, 1);
				{
				setState(290);
				match(HtmlScriptOpen);
				setState(294);
				_errHandler.sync(this);
				_la = _input.LA(1);
				while ((((_la) & ~0x3f) == 0 && ((1L << _la) & ((1L << HtmlScriptOpen) | (1L << HtmlStyleOpen) | (1L << HtmlComment) | (1L << HtmlDtd) | (1L << HtmlOpen) | (1L << HtmlName) | (1L << Abstract) | (1L << Array) | (1L << BinaryCast) | (1L << BoolType) | (1L << BooleanConstant) | (1L << Break) | (1L << Callable) | (1L << Case) | (1L << Class) | (1L << Clone) | (1L << Const) | (1L << Continue) | (1L << Declare) | (1L << Default) | (1L << Do) | (1L << DoubleCast) | (1L << DoubleType) | (1L << Echo) | (1L << Empty))) != 0) || ((((_la - 65)) & ~0x3f) == 0 && ((1L << (_la - 65)) & ((1L << (Eval - 65)) | (1L << (Exit - 65)) | (1L << (Final - 65)) | (1L << (FloatCast - 65)) | (1L << (For - 65)) | (1L << (Foreach - 65)) | (1L << (Function - 65)) | (1L << (Global - 65)) | (1L << (Goto - 65)) | (1L << (If - 65)) | (1L << (Import - 65)) | (1L << (Include - 65)) | (1L << (IncludeOnce - 65)) | (1L << (Int16Cast - 65)) | (1L << (Int64Type - 65)) | (1L << (Int8Cast - 65)) | (1L << (Interface - 65)) | (1L << (IntType - 65)) | (1L << (IsSet - 65)) | (1L << (List - 65)) | (1L << (LogicalAnd - 65)) | (1L << (LogicalOr - 65)) | (1L << (LogicalXor - 65)) | (1L << (Namespace - 65)) | (1L << (New - 65)) | (1L << (Null - 65)) | (1L << (ObjectType - 65)) | (1L << (Parent_ - 65)) | (1L << (Partial - 65)) | (1L << (Print - 65)) | (1L << (Private - 65)) | (1L << (Protected - 65)) | (1L << (Public - 65)) | (1L << (Require - 65)) | (1L << (RequireOnce - 65)) | (1L << (Resource - 65)) | (1L << (Return - 65)) | (1L << (Static - 65)) | (1L << (StringType - 65)) | (1L << (Switch - 65)) | (1L << (Throw - 65)) | (1L << (Trait - 65)) | (1L << (Try - 65)) | (1L << (Uint16Cast - 65)) | (1L << (Uint32Cast - 65)) | (1L << (Uint64Cast - 65)) | (1L << (Uint8Cast - 65)) | (1L << (UnicodeCast - 65)) | (1L << (Unset - 65)) | (1L << (Use - 65)) | (1L << (Var - 65)) | (1L << (While - 65)) | (1L << (Yield - 65)) | (1L << (Get - 65)) | (1L << (Set - 65)) | (1L << (Call - 65)) | (1L << (CallStatic - 65)) | (1L << (Constructor - 65)))) != 0) || ((((_la - 129)) & ~0x3f) == 0 && ((1L << (_la - 129)) & ((1L << (Destruct - 129)) | (1L << (Wakeup - 129)) | (1L << (Sleep - 129)) | (1L << (Autoload - 129)) | (1L << (IsSet__ - 129)) | (1L << (Unset__ - 129)) | (1L << (ToString__ - 129)) | (1L << (Invoke - 129)) | (1L << (SetState - 129)) | (1L << (Clone__ - 129)) | (1L << (DebugInfo - 129)) | (1L << (Namespace__ - 129)) | (1L << (Class__ - 129)) | (1L << (Traic__ - 129)) | (1L << (Function__ - 129)) | (1L << (Method__ - 129)) | (1L << (Line__ - 129)) | (1L << (File__ - 129)) | (1L << (Dir__ - 129)) | (1L << (Inc - 129)) | (1L << (Dec - 129)) | (1L << (NamespaceSeparator - 129)) | (1L << (Bang - 129)) | (1L << (Plus - 129)) | (1L << (Minus - 129)) | (1L << (Tilde - 129)) | (1L << (SuppressWarnings - 129)))) != 0) || ((((_la - 193)) & ~0x3f) == 0 && ((1L << (_la - 193)) & ((1L << (Dollar - 193)) | (1L << (OpenRoundBracket - 193)) | (1L << (OpenSquareBracket - 193)) | (1L << (OpenCurlyBracket - 193)) | (1L << (SemiColon - 193)) | (1L << (Label - 193)) | (1L << (VarName - 193)) | (1L << (Numeric - 193)) | (1L << (Real - 193)) | (1L << (BackQuoteString - 193)) | (1L << (SingleQuoteString - 193)) | (1L << (DoubleQuoteString - 193)) | (1L << (StartNowDoc - 193)) | (1L << (StartHereDoc - 193)))) != 0)) {
					{
					{
					setState(291);
					htmlAttribute();
					}
					}
					setState(296);
					_errHandler.sync(this);
					_la = _input.LA(1);
				}
				setState(297);
				match(HtmlClose);
				setState(298);
				scriptText();
				setState(299);
				match(ScriptClose);
				}
				break;
			case 2:
				enterOuterAlt(_localctx, 2);
				{
				setState(301);
				match(HtmlStyleOpen);
				setState(305);
				_errHandler.sync(this);
				_la = _input.LA(1);
				while ((((_la) & ~0x3f) == 0 && ((1L << _la) & ((1L << HtmlScriptOpen) | (1L << HtmlStyleOpen) | (1L << HtmlComment) | (1L << HtmlDtd) | (1L << HtmlOpen) | (1L << HtmlName) | (1L << Abstract) | (1L << Array) | (1L << BinaryCast) | (1L << BoolType) | (1L << BooleanConstant) | (1L << Break) | (1L << Callable) | (1L << Case) | (1L << Class) | (1L << Clone) | (1L << Const) | (1L << Continue) | (1L << Declare) | (1L << Default) | (1L << Do) | (1L << DoubleCast) | (1L << DoubleType) | (1L << Echo) | (1L << Empty))) != 0) || ((((_la - 65)) & ~0x3f) == 0 && ((1L << (_la - 65)) & ((1L << (Eval - 65)) | (1L << (Exit - 65)) | (1L << (Final - 65)) | (1L << (FloatCast - 65)) | (1L << (For - 65)) | (1L << (Foreach - 65)) | (1L << (Function - 65)) | (1L << (Global - 65)) | (1L << (Goto - 65)) | (1L << (If - 65)) | (1L << (Import - 65)) | (1L << (Include - 65)) | (1L << (IncludeOnce - 65)) | (1L << (Int16Cast - 65)) | (1L << (Int64Type - 65)) | (1L << (Int8Cast - 65)) | (1L << (Interface - 65)) | (1L << (IntType - 65)) | (1L << (IsSet - 65)) | (1L << (List - 65)) | (1L << (LogicalAnd - 65)) | (1L << (LogicalOr - 65)) | (1L << (LogicalXor - 65)) | (1L << (Namespace - 65)) | (1L << (New - 65)) | (1L << (Null - 65)) | (1L << (ObjectType - 65)) | (1L << (Parent_ - 65)) | (1L << (Partial - 65)) | (1L << (Print - 65)) | (1L << (Private - 65)) | (1L << (Protected - 65)) | (1L << (Public - 65)) | (1L << (Require - 65)) | (1L << (RequireOnce - 65)) | (1L << (Resource - 65)) | (1L << (Return - 65)) | (1L << (Static - 65)) | (1L << (StringType - 65)) | (1L << (Switch - 65)) | (1L << (Throw - 65)) | (1L << (Trait - 65)) | (1L << (Try - 65)) | (1L << (Uint16Cast - 65)) | (1L << (Uint32Cast - 65)) | (1L << (Uint64Cast - 65)) | (1L << (Uint8Cast - 65)) | (1L << (UnicodeCast - 65)) | (1L << (Unset - 65)) | (1L << (Use - 65)) | (1L << (Var - 65)) | (1L << (While - 65)) | (1L << (Yield - 65)) | (1L << (Get - 65)) | (1L << (Set - 65)) | (1L << (Call - 65)) | (1L << (CallStatic - 65)) | (1L << (Constructor - 65)))) != 0) || ((((_la - 129)) & ~0x3f) == 0 && ((1L << (_la - 129)) & ((1L << (Destruct - 129)) | (1L << (Wakeup - 129)) | (1L << (Sleep - 129)) | (1L << (Autoload - 129)) | (1L << (IsSet__ - 129)) | (1L << (Unset__ - 129)) | (1L << (ToString__ - 129)) | (1L << (Invoke - 129)) | (1L << (SetState - 129)) | (1L << (Clone__ - 129)) | (1L << (DebugInfo - 129)) | (1L << (Namespace__ - 129)) | (1L << (Class__ - 129)) | (1L << (Traic__ - 129)) | (1L << (Function__ - 129)) | (1L << (Method__ - 129)) | (1L << (Line__ - 129)) | (1L << (File__ - 129)) | (1L << (Dir__ - 129)) | (1L << (Inc - 129)) | (1L << (Dec - 129)) | (1L << (NamespaceSeparator - 129)) | (1L << (Bang - 129)) | (1L << (Plus - 129)) | (1L << (Minus - 129)) | (1L << (Tilde - 129)) | (1L << (SuppressWarnings - 129)))) != 0) || ((((_la - 193)) & ~0x3f) == 0 && ((1L << (_la - 193)) & ((1L << (Dollar - 193)) | (1L << (OpenRoundBracket - 193)) | (1L << (OpenSquareBracket - 193)) | (1L << (OpenCurlyBracket - 193)) | (1L << (SemiColon - 193)) | (1L << (Label - 193)) | (1L << (VarName - 193)) | (1L << (Numeric - 193)) | (1L << (Real - 193)) | (1L << (BackQuoteString - 193)) | (1L << (SingleQuoteString - 193)) | (1L << (DoubleQuoteString - 193)) | (1L << (StartNowDoc - 193)) | (1L << (StartHereDoc - 193)))) != 0)) {
					{
					{
					setState(302);
					htmlAttribute();
					}
					}
					setState(307);
					_errHandler.sync(this);
					_la = _input.LA(1);
				}
				setState(308);
				match(HtmlClose);
				setState(309);
				match(StyleBody);
				}
				break;
			case 3:
				enterOuterAlt(_localctx, 3);
				{
				setState(310);
				match(HtmlOpen);
				setState(311);
				match(HtmlName);
				setState(315);
				_errHandler.sync(this);
				_la = _input.LA(1);
				while ((((_la) & ~0x3f) == 0 && ((1L << _la) & ((1L << HtmlScriptOpen) | (1L << HtmlStyleOpen) | (1L << HtmlComment) | (1L << HtmlDtd) | (1L << HtmlOpen) | (1L << HtmlName) | (1L << Abstract) | (1L << Array) | (1L << BinaryCast) | (1L << BoolType) | (1L << BooleanConstant) | (1L << Break) | (1L << Callable) | (1L << Case) | (1L << Class) | (1L << Clone) | (1L << Const) | (1L << Continue) | (1L << Declare) | (1L << Default) | (1L << Do) | (1L << DoubleCast) | (1L << DoubleType) | (1L << Echo) | (1L << Empty))) != 0) || ((((_la - 65)) & ~0x3f) == 0 && ((1L << (_la - 65)) & ((1L << (Eval - 65)) | (1L << (Exit - 65)) | (1L << (Final - 65)) | (1L << (FloatCast - 65)) | (1L << (For - 65)) | (1L << (Foreach - 65)) | (1L << (Function - 65)) | (1L << (Global - 65)) | (1L << (Goto - 65)) | (1L << (If - 65)) | (1L << (Import - 65)) | (1L << (Include - 65)) | (1L << (IncludeOnce - 65)) | (1L << (Int16Cast - 65)) | (1L << (Int64Type - 65)) | (1L << (Int8Cast - 65)) | (1L << (Interface - 65)) | (1L << (IntType - 65)) | (1L << (IsSet - 65)) | (1L << (List - 65)) | (1L << (LogicalAnd - 65)) | (1L << (LogicalOr - 65)) | (1L << (LogicalXor - 65)) | (1L << (Namespace - 65)) | (1L << (New - 65)) | (1L << (Null - 65)) | (1L << (ObjectType - 65)) | (1L << (Parent_ - 65)) | (1L << (Partial - 65)) | (1L << (Print - 65)) | (1L << (Private - 65)) | (1L << (Protected - 65)) | (1L << (Public - 65)) | (1L << (Require - 65)) | (1L << (RequireOnce - 65)) | (1L << (Resource - 65)) | (1L << (Return - 65)) | (1L << (Static - 65)) | (1L << (StringType - 65)) | (1L << (Switch - 65)) | (1L << (Throw - 65)) | (1L << (Trait - 65)) | (1L << (Try - 65)) | (1L << (Uint16Cast - 65)) | (1L << (Uint32Cast - 65)) | (1L << (Uint64Cast - 65)) | (1L << (Uint8Cast - 65)) | (1L << (UnicodeCast - 65)) | (1L << (Unset - 65)) | (1L << (Use - 65)) | (1L << (Var - 65)) | (1L << (While - 65)) | (1L << (Yield - 65)) | (1L << (Get - 65)) | (1L << (Set - 65)) | (1L << (Call - 65)) | (1L << (CallStatic - 65)) | (1L << (Constructor - 65)))) != 0) || ((((_la - 129)) & ~0x3f) == 0 && ((1L << (_la - 129)) & ((1L << (Destruct - 129)) | (1L << (Wakeup - 129)) | (1L << (Sleep - 129)) | (1L << (Autoload - 129)) | (1L << (IsSet__ - 129)) | (1L << (Unset__ - 129)) | (1L << (ToString__ - 129)) | (1L << (Invoke - 129)) | (1L << (SetState - 129)) | (1L << (Clone__ - 129)) | (1L << (DebugInfo - 129)) | (1L << (Namespace__ - 129)) | (1L << (Class__ - 129)) | (1L << (Traic__ - 129)) | (1L << (Function__ - 129)) | (1L << (Method__ - 129)) | (1L << (Line__ - 129)) | (1L << (File__ - 129)) | (1L << (Dir__ - 129)) | (1L << (Inc - 129)) | (1L << (Dec - 129)) | (1L << (NamespaceSeparator - 129)) | (1L << (Bang - 129)) | (1L << (Plus - 129)) | (1L << (Minus - 129)) | (1L << (Tilde - 129)) | (1L << (SuppressWarnings - 129)))) != 0) || ((((_la - 193)) & ~0x3f) == 0 && ((1L << (_la - 193)) & ((1L << (Dollar - 193)) | (1L << (OpenRoundBracket - 193)) | (1L << (OpenSquareBracket - 193)) | (1L << (OpenCurlyBracket - 193)) | (1L << (SemiColon - 193)) | (1L << (Label - 193)) | (1L << (VarName - 193)) | (1L << (Numeric - 193)) | (1L << (Real - 193)) | (1L << (BackQuoteString - 193)) | (1L << (SingleQuoteString - 193)) | (1L << (DoubleQuoteString - 193)) | (1L << (StartNowDoc - 193)) | (1L << (StartHereDoc - 193)))) != 0)) {
					{
					{
					setState(312);
					htmlAttribute();
					}
					}
					setState(317);
					_errHandler.sync(this);
					_la = _input.LA(1);
				}
				setState(318);
				match(HtmlClose);
				setState(325);
				_errHandler.sync(this);
				switch ( getInterpreter().adaptivePredict(_input,8,_ctx) ) {
				case 1:
					{
					setState(319);
					htmlContent();
					setState(320);
					match(HtmlOpen);
					setState(321);
					match(HtmlSlash);
					setState(322);
					match(HtmlName);
					setState(323);
					match(HtmlClose);
					}
					break;
				}
				}
				break;
			case 4:
				enterOuterAlt(_localctx, 4);
				{
				setState(327);
				match(HtmlOpen);
				setState(328);
				match(HtmlName);
				setState(332);
				_errHandler.sync(this);
				_la = _input.LA(1);
				while ((((_la) & ~0x3f) == 0 && ((1L << _la) & ((1L << HtmlScriptOpen) | (1L << HtmlStyleOpen) | (1L << HtmlComment) | (1L << HtmlDtd) | (1L << HtmlOpen) | (1L << HtmlName) | (1L << Abstract) | (1L << Array) | (1L << BinaryCast) | (1L << BoolType) | (1L << BooleanConstant) | (1L << Break) | (1L << Callable) | (1L << Case) | (1L << Class) | (1L << Clone) | (1L << Const) | (1L << Continue) | (1L << Declare) | (1L << Default) | (1L << Do) | (1L << DoubleCast) | (1L << DoubleType) | (1L << Echo) | (1L << Empty))) != 0) || ((((_la - 65)) & ~0x3f) == 0 && ((1L << (_la - 65)) & ((1L << (Eval - 65)) | (1L << (Exit - 65)) | (1L << (Final - 65)) | (1L << (FloatCast - 65)) | (1L << (For - 65)) | (1L << (Foreach - 65)) | (1L << (Function - 65)) | (1L << (Global - 65)) | (1L << (Goto - 65)) | (1L << (If - 65)) | (1L << (Import - 65)) | (1L << (Include - 65)) | (1L << (IncludeOnce - 65)) | (1L << (Int16Cast - 65)) | (1L << (Int64Type - 65)) | (1L << (Int8Cast - 65)) | (1L << (Interface - 65)) | (1L << (IntType - 65)) | (1L << (IsSet - 65)) | (1L << (List - 65)) | (1L << (LogicalAnd - 65)) | (1L << (LogicalOr - 65)) | (1L << (LogicalXor - 65)) | (1L << (Namespace - 65)) | (1L << (New - 65)) | (1L << (Null - 65)) | (1L << (ObjectType - 65)) | (1L << (Parent_ - 65)) | (1L << (Partial - 65)) | (1L << (Print - 65)) | (1L << (Private - 65)) | (1L << (Protected - 65)) | (1L << (Public - 65)) | (1L << (Require - 65)) | (1L << (RequireOnce - 65)) | (1L << (Resource - 65)) | (1L << (Return - 65)) | (1L << (Static - 65)) | (1L << (StringType - 65)) | (1L << (Switch - 65)) | (1L << (Throw - 65)) | (1L << (Trait - 65)) | (1L << (Try - 65)) | (1L << (Uint16Cast - 65)) | (1L << (Uint32Cast - 65)) | (1L << (Uint64Cast - 65)) | (1L << (Uint8Cast - 65)) | (1L << (UnicodeCast - 65)) | (1L << (Unset - 65)) | (1L << (Use - 65)) | (1L << (Var - 65)) | (1L << (While - 65)) | (1L << (Yield - 65)) | (1L << (Get - 65)) | (1L << (Set - 65)) | (1L << (Call - 65)) | (1L << (CallStatic - 65)) | (1L << (Constructor - 65)))) != 0) || ((((_la - 129)) & ~0x3f) == 0 && ((1L << (_la - 129)) & ((1L << (Destruct - 129)) | (1L << (Wakeup - 129)) | (1L << (Sleep - 129)) | (1L << (Autoload - 129)) | (1L << (IsSet__ - 129)) | (1L << (Unset__ - 129)) | (1L << (ToString__ - 129)) | (1L << (Invoke - 129)) | (1L << (SetState - 129)) | (1L << (Clone__ - 129)) | (1L << (DebugInfo - 129)) | (1L << (Namespace__ - 129)) | (1L << (Class__ - 129)) | (1L << (Traic__ - 129)) | (1L << (Function__ - 129)) | (1L << (Method__ - 129)) | (1L << (Line__ - 129)) | (1L << (File__ - 129)) | (1L << (Dir__ - 129)) | (1L << (Inc - 129)) | (1L << (Dec - 129)) | (1L << (NamespaceSeparator - 129)) | (1L << (Bang - 129)) | (1L << (Plus - 129)) | (1L << (Minus - 129)) | (1L << (Tilde - 129)) | (1L << (SuppressWarnings - 129)))) != 0) || ((((_la - 193)) & ~0x3f) == 0 && ((1L << (_la - 193)) & ((1L << (Dollar - 193)) | (1L << (OpenRoundBracket - 193)) | (1L << (OpenSquareBracket - 193)) | (1L << (OpenCurlyBracket - 193)) | (1L << (SemiColon - 193)) | (1L << (Label - 193)) | (1L << (VarName - 193)) | (1L << (Numeric - 193)) | (1L << (Real - 193)) | (1L << (BackQuoteString - 193)) | (1L << (SingleQuoteString - 193)) | (1L << (DoubleQuoteString - 193)) | (1L << (StartNowDoc - 193)) | (1L << (StartHereDoc - 193)))) != 0)) {
					{
					{
					setState(329);
					htmlAttribute();
					}
					}
					setState(334);
					_errHandler.sync(this);
					_la = _input.LA(1);
				}
				setState(335);
				match(HtmlSlashClose);
				}
				break;
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class HtmlContentContext extends ParserRuleContext {
		public List<TerminalNode> HtmlText() { return getTokens(PHPParser.HtmlText); }
		public TerminalNode HtmlText(int i) {
			return getToken(PHPParser.HtmlText, i);
		}
		public List<HtmlElementContext> htmlElement() {
			return getRuleContexts(HtmlElementContext.class);
		}
		public HtmlElementContext htmlElement(int i) {
			return getRuleContext(HtmlElementContext.class,i);
		}
		public List<TerminalNode> HtmlComment() { return getTokens(PHPParser.HtmlComment); }
		public TerminalNode HtmlComment(int i) {
			return getToken(PHPParser.HtmlComment, i);
		}
		public List<PhpBlockContext> phpBlock() {
			return getRuleContexts(PhpBlockContext.class);
		}
		public PhpBlockContext phpBlock(int i) {
			return getRuleContext(PhpBlockContext.class,i);
		}
		public HtmlContentContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_htmlContent; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterHtmlContent(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitHtmlContent(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitHtmlContent(this);
			else return visitor.visitChildren(this);
		}
	}

	public final HtmlContentContext htmlContent() throws RecognitionException {
		HtmlContentContext _localctx = new HtmlContentContext(_ctx, getState());
		enterRule(_localctx, 6, RULE_htmlContent);
		int _la;
		try {
			int _alt;
			enterOuterAlt(_localctx, 1);
			{
			setState(339);
			_la = _input.LA(1);
			if (_la==HtmlText) {
				{
				setState(338);
				match(HtmlText);
				}
			}

			setState(351);
			_errHandler.sync(this);
			_alt = getInterpreter().adaptivePredict(_input,14,_ctx);
			while ( _alt!=2 && _alt!= ATN.INVALID_ALT_NUMBER ) {
				if ( _alt==1 ) {
					{
					{
					setState(344);
					_errHandler.sync(this);
					switch ( getInterpreter().adaptivePredict(_input,12,_ctx) ) {
					case 1:
						{
						setState(341);
						htmlElement();
						}
						break;
					case 2:
						{
						setState(342);
						match(HtmlComment);
						}
						break;
					case 3:
						{
						setState(343);
						phpBlock();
						}
						break;
					}
					setState(347);
					_la = _input.LA(1);
					if (_la==HtmlText) {
						{
						setState(346);
						match(HtmlText);
						}
					}

					}
					}
				}
				setState(353);
				_errHandler.sync(this);
				_alt = getInterpreter().adaptivePredict(_input,14,_ctx);
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class HtmlAttributeContext extends ParserRuleContext {
		public PhpBlockContext phpBlock() {
			return getRuleContext(PhpBlockContext.class,0);
		}
		public TerminalNode HtmlName() { return getToken(PHPParser.HtmlName, 0); }
		public TerminalNode HtmlEquals() { return getToken(PHPParser.HtmlEquals, 0); }
		public TerminalNode HtmlStartQuoteString() { return getToken(PHPParser.HtmlStartQuoteString, 0); }
		public TerminalNode HtmlEndQuoteString() { return getToken(PHPParser.HtmlEndQuoteString, 0); }
		public List<HtmlQuotePhpBlockOrStringContext> htmlQuotePhpBlockOrString() {
			return getRuleContexts(HtmlQuotePhpBlockOrStringContext.class);
		}
		public HtmlQuotePhpBlockOrStringContext htmlQuotePhpBlockOrString(int i) {
			return getRuleContext(HtmlQuotePhpBlockOrStringContext.class,i);
		}
		public TerminalNode HtmlStartDoubleQuoteString() { return getToken(PHPParser.HtmlStartDoubleQuoteString, 0); }
		public TerminalNode HtmlEndDoubleQuoteString() { return getToken(PHPParser.HtmlEndDoubleQuoteString, 0); }
		public List<HtmlDoubleQuotePhpBlockOrStringContext> htmlDoubleQuotePhpBlockOrString() {
			return getRuleContexts(HtmlDoubleQuotePhpBlockOrStringContext.class);
		}
		public HtmlDoubleQuotePhpBlockOrStringContext htmlDoubleQuotePhpBlockOrString(int i) {
			return getRuleContext(HtmlDoubleQuotePhpBlockOrStringContext.class,i);
		}
		public TerminalNode HtmlHex() { return getToken(PHPParser.HtmlHex, 0); }
		public TerminalNode HtmlDecimal() { return getToken(PHPParser.HtmlDecimal, 0); }
		public HtmlAttributeContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_htmlAttribute; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterHtmlAttribute(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitHtmlAttribute(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitHtmlAttribute(this);
			else return visitor.visitChildren(this);
		}
	}

	public final HtmlAttributeContext htmlAttribute() throws RecognitionException {
		HtmlAttributeContext _localctx = new HtmlAttributeContext(_ctx, getState());
		enterRule(_localctx, 8, RULE_htmlAttribute);
		int _la;
		try {
			setState(379);
			_errHandler.sync(this);
			switch ( getInterpreter().adaptivePredict(_input,17,_ctx) ) {
			case 1:
				enterOuterAlt(_localctx, 1);
				{
				setState(354);
				phpBlock();
				}
				break;
			case 2:
				enterOuterAlt(_localctx, 2);
				{
				setState(355);
				match(HtmlName);
				setState(356);
				match(HtmlEquals);
				setState(357);
				match(HtmlStartQuoteString);
				setState(361);
				_errHandler.sync(this);
				_la = _input.LA(1);
				while ((((_la) & ~0x3f) == 0 && ((1L << _la) & ((1L << HtmlScriptOpen) | (1L << HtmlStyleOpen) | (1L << HtmlComment) | (1L << HtmlDtd) | (1L << HtmlOpen) | (1L << HtmlQuoteString) | (1L << Abstract) | (1L << Array) | (1L << BinaryCast) | (1L << BoolType) | (1L << BooleanConstant) | (1L << Break) | (1L << Callable) | (1L << Case) | (1L << Class) | (1L << Clone) | (1L << Const) | (1L << Continue) | (1L << Declare) | (1L << Default) | (1L << Do) | (1L << DoubleCast) | (1L << DoubleType) | (1L << Echo) | (1L << Empty))) != 0) || ((((_la - 65)) & ~0x3f) == 0 && ((1L << (_la - 65)) & ((1L << (Eval - 65)) | (1L << (Exit - 65)) | (1L << (Final - 65)) | (1L << (FloatCast - 65)) | (1L << (For - 65)) | (1L << (Foreach - 65)) | (1L << (Function - 65)) | (1L << (Global - 65)) | (1L << (Goto - 65)) | (1L << (If - 65)) | (1L << (Import - 65)) | (1L << (Include - 65)) | (1L << (IncludeOnce - 65)) | (1L << (Int16Cast - 65)) | (1L << (Int64Type - 65)) | (1L << (Int8Cast - 65)) | (1L << (Interface - 65)) | (1L << (IntType - 65)) | (1L << (IsSet - 65)) | (1L << (List - 65)) | (1L << (LogicalAnd - 65)) | (1L << (LogicalOr - 65)) | (1L << (LogicalXor - 65)) | (1L << (Namespace - 65)) | (1L << (New - 65)) | (1L << (Null - 65)) | (1L << (ObjectType - 65)) | (1L << (Parent_ - 65)) | (1L << (Partial - 65)) | (1L << (Print - 65)) | (1L << (Private - 65)) | (1L << (Protected - 65)) | (1L << (Public - 65)) | (1L << (Require - 65)) | (1L << (RequireOnce - 65)) | (1L << (Resource - 65)) | (1L << (Return - 65)) | (1L << (Static - 65)) | (1L << (StringType - 65)) | (1L << (Switch - 65)) | (1L << (Throw - 65)) | (1L << (Trait - 65)) | (1L << (Try - 65)) | (1L << (Uint16Cast - 65)) | (1L << (Uint32Cast - 65)) | (1L << (Uint64Cast - 65)) | (1L << (Uint8Cast - 65)) | (1L << (UnicodeCast - 65)) | (1L << (Unset - 65)) | (1L << (Use - 65)) | (1L << (Var - 65)) | (1L << (While - 65)) | (1L << (Yield - 65)) | (1L << (Get - 65)) | (1L << (Set - 65)) | (1L << (Call - 65)) | (1L << (CallStatic - 65)) | (1L << (Constructor - 65)))) != 0) || ((((_la - 129)) & ~0x3f) == 0 && ((1L << (_la - 129)) & ((1L << (Destruct - 129)) | (1L << (Wakeup - 129)) | (1L << (Sleep - 129)) | (1L << (Autoload - 129)) | (1L << (IsSet__ - 129)) | (1L << (Unset__ - 129)) | (1L << (ToString__ - 129)) | (1L << (Invoke - 129)) | (1L << (SetState - 129)) | (1L << (Clone__ - 129)) | (1L << (DebugInfo - 129)) | (1L << (Namespace__ - 129)) | (1L << (Class__ - 129)) | (1L << (Traic__ - 129)) | (1L << (Function__ - 129)) | (1L << (Method__ - 129)) | (1L << (Line__ - 129)) | (1L << (File__ - 129)) | (1L << (Dir__ - 129)) | (1L << (Inc - 129)) | (1L << (Dec - 129)) | (1L << (NamespaceSeparator - 129)) | (1L << (Bang - 129)) | (1L << (Plus - 129)) | (1L << (Minus - 129)) | (1L << (Tilde - 129)) | (1L << (SuppressWarnings - 129)))) != 0) || ((((_la - 193)) & ~0x3f) == 0 && ((1L << (_la - 193)) & ((1L << (Dollar - 193)) | (1L << (OpenRoundBracket - 193)) | (1L << (OpenSquareBracket - 193)) | (1L << (OpenCurlyBracket - 193)) | (1L << (SemiColon - 193)) | (1L << (Label - 193)) | (1L << (VarName - 193)) | (1L << (Numeric - 193)) | (1L << (Real - 193)) | (1L << (BackQuoteString - 193)) | (1L << (SingleQuoteString - 193)) | (1L << (DoubleQuoteString - 193)) | (1L << (StartNowDoc - 193)) | (1L << (StartHereDoc - 193)))) != 0)) {
					{
					{
					setState(358);
					htmlQuotePhpBlockOrString();
					}
					}
					setState(363);
					_errHandler.sync(this);
					_la = _input.LA(1);
				}
				setState(364);
				match(HtmlEndQuoteString);
				}
				break;
			case 3:
				enterOuterAlt(_localctx, 3);
				{
				setState(365);
				match(HtmlName);
				setState(366);
				match(HtmlEquals);
				setState(367);
				match(HtmlStartDoubleQuoteString);
				setState(371);
				_errHandler.sync(this);
				_la = _input.LA(1);
				while ((((_la) & ~0x3f) == 0 && ((1L << _la) & ((1L << HtmlScriptOpen) | (1L << HtmlStyleOpen) | (1L << HtmlComment) | (1L << HtmlDtd) | (1L << HtmlOpen) | (1L << HtmlDoubleQuoteString) | (1L << Abstract) | (1L << Array) | (1L << BinaryCast) | (1L << BoolType) | (1L << BooleanConstant) | (1L << Break) | (1L << Callable) | (1L << Case) | (1L << Class) | (1L << Clone) | (1L << Const) | (1L << Continue) | (1L << Declare) | (1L << Default) | (1L << Do) | (1L << DoubleCast) | (1L << DoubleType) | (1L << Echo) | (1L << Empty))) != 0) || ((((_la - 65)) & ~0x3f) == 0 && ((1L << (_la - 65)) & ((1L << (Eval - 65)) | (1L << (Exit - 65)) | (1L << (Final - 65)) | (1L << (FloatCast - 65)) | (1L << (For - 65)) | (1L << (Foreach - 65)) | (1L << (Function - 65)) | (1L << (Global - 65)) | (1L << (Goto - 65)) | (1L << (If - 65)) | (1L << (Import - 65)) | (1L << (Include - 65)) | (1L << (IncludeOnce - 65)) | (1L << (Int16Cast - 65)) | (1L << (Int64Type - 65)) | (1L << (Int8Cast - 65)) | (1L << (Interface - 65)) | (1L << (IntType - 65)) | (1L << (IsSet - 65)) | (1L << (List - 65)) | (1L << (LogicalAnd - 65)) | (1L << (LogicalOr - 65)) | (1L << (LogicalXor - 65)) | (1L << (Namespace - 65)) | (1L << (New - 65)) | (1L << (Null - 65)) | (1L << (ObjectType - 65)) | (1L << (Parent_ - 65)) | (1L << (Partial - 65)) | (1L << (Print - 65)) | (1L << (Private - 65)) | (1L << (Protected - 65)) | (1L << (Public - 65)) | (1L << (Require - 65)) | (1L << (RequireOnce - 65)) | (1L << (Resource - 65)) | (1L << (Return - 65)) | (1L << (Static - 65)) | (1L << (StringType - 65)) | (1L << (Switch - 65)) | (1L << (Throw - 65)) | (1L << (Trait - 65)) | (1L << (Try - 65)) | (1L << (Uint16Cast - 65)) | (1L << (Uint32Cast - 65)) | (1L << (Uint64Cast - 65)) | (1L << (Uint8Cast - 65)) | (1L << (UnicodeCast - 65)) | (1L << (Unset - 65)) | (1L << (Use - 65)) | (1L << (Var - 65)) | (1L << (While - 65)) | (1L << (Yield - 65)) | (1L << (Get - 65)) | (1L << (Set - 65)) | (1L << (Call - 65)) | (1L << (CallStatic - 65)) | (1L << (Constructor - 65)))) != 0) || ((((_la - 129)) & ~0x3f) == 0 && ((1L << (_la - 129)) & ((1L << (Destruct - 129)) | (1L << (Wakeup - 129)) | (1L << (Sleep - 129)) | (1L << (Autoload - 129)) | (1L << (IsSet__ - 129)) | (1L << (Unset__ - 129)) | (1L << (ToString__ - 129)) | (1L << (Invoke - 129)) | (1L << (SetState - 129)) | (1L << (Clone__ - 129)) | (1L << (DebugInfo - 129)) | (1L << (Namespace__ - 129)) | (1L << (Class__ - 129)) | (1L << (Traic__ - 129)) | (1L << (Function__ - 129)) | (1L << (Method__ - 129)) | (1L << (Line__ - 129)) | (1L << (File__ - 129)) | (1L << (Dir__ - 129)) | (1L << (Inc - 129)) | (1L << (Dec - 129)) | (1L << (NamespaceSeparator - 129)) | (1L << (Bang - 129)) | (1L << (Plus - 129)) | (1L << (Minus - 129)) | (1L << (Tilde - 129)) | (1L << (SuppressWarnings - 129)))) != 0) || ((((_la - 193)) & ~0x3f) == 0 && ((1L << (_la - 193)) & ((1L << (Dollar - 193)) | (1L << (OpenRoundBracket - 193)) | (1L << (OpenSquareBracket - 193)) | (1L << (OpenCurlyBracket - 193)) | (1L << (SemiColon - 193)) | (1L << (Label - 193)) | (1L << (VarName - 193)) | (1L << (Numeric - 193)) | (1L << (Real - 193)) | (1L << (BackQuoteString - 193)) | (1L << (SingleQuoteString - 193)) | (1L << (DoubleQuoteString - 193)) | (1L << (StartNowDoc - 193)) | (1L << (StartHereDoc - 193)))) != 0)) {
					{
					{
					setState(368);
					htmlDoubleQuotePhpBlockOrString();
					}
					}
					setState(373);
					_errHandler.sync(this);
					_la = _input.LA(1);
				}
				setState(374);
				match(HtmlEndDoubleQuoteString);
				}
				break;
			case 4:
				enterOuterAlt(_localctx, 4);
				{
				setState(375);
				match(HtmlName);
				setState(376);
				match(HtmlEquals);
				setState(377);
				_la = _input.LA(1);
				if ( !(_la==HtmlHex || _la==HtmlDecimal) ) {
				_errHandler.recoverInline(this);
				} else {
					consume();
				}
				}
				break;
			case 5:
				enterOuterAlt(_localctx, 5);
				{
				setState(378);
				match(HtmlName);
				}
				break;
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class HtmlQuotePhpBlockOrStringContext extends ParserRuleContext {
		public PhpBlockContext phpBlock() {
			return getRuleContext(PhpBlockContext.class,0);
		}
		public TerminalNode HtmlQuoteString() { return getToken(PHPParser.HtmlQuoteString, 0); }
		public HtmlQuotePhpBlockOrStringContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_htmlQuotePhpBlockOrString; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterHtmlQuotePhpBlockOrString(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitHtmlQuotePhpBlockOrString(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitHtmlQuotePhpBlockOrString(this);
			else return visitor.visitChildren(this);
		}
	}

	public final HtmlQuotePhpBlockOrStringContext htmlQuotePhpBlockOrString() throws RecognitionException {
		HtmlQuotePhpBlockOrStringContext _localctx = new HtmlQuotePhpBlockOrStringContext(_ctx, getState());
		enterRule(_localctx, 10, RULE_htmlQuotePhpBlockOrString);
		try {
			setState(383);
			switch (_input.LA(1)) {
			case HtmlScriptOpen:
			case HtmlStyleOpen:
			case HtmlComment:
			case HtmlDtd:
			case HtmlOpen:
			case Abstract:
			case Array:
			case BinaryCast:
			case BoolType:
			case BooleanConstant:
			case Break:
			case Callable:
			case Case:
			case Class:
			case Clone:
			case Const:
			case Continue:
			case Declare:
			case Default:
			case Do:
			case DoubleCast:
			case DoubleType:
			case Echo:
			case Empty:
			case Eval:
			case Exit:
			case Final:
			case FloatCast:
			case For:
			case Foreach:
			case Function:
			case Global:
			case Goto:
			case If:
			case Import:
			case Include:
			case IncludeOnce:
			case Int16Cast:
			case Int64Type:
			case Int8Cast:
			case Interface:
			case IntType:
			case IsSet:
			case List:
			case LogicalAnd:
			case LogicalOr:
			case LogicalXor:
			case Namespace:
			case New:
			case Null:
			case ObjectType:
			case Parent_:
			case Partial:
			case Print:
			case Private:
			case Protected:
			case Public:
			case Require:
			case RequireOnce:
			case Resource:
			case Return:
			case Static:
			case StringType:
			case Switch:
			case Throw:
			case Trait:
			case Try:
			case Uint16Cast:
			case Uint32Cast:
			case Uint64Cast:
			case Uint8Cast:
			case UnicodeCast:
			case Unset:
			case Use:
			case Var:
			case While:
			case Yield:
			case Get:
			case Set:
			case Call:
			case CallStatic:
			case Constructor:
			case Destruct:
			case Wakeup:
			case Sleep:
			case Autoload:
			case IsSet__:
			case Unset__:
			case ToString__:
			case Invoke:
			case SetState:
			case Clone__:
			case DebugInfo:
			case Namespace__:
			case Class__:
			case Traic__:
			case Function__:
			case Method__:
			case Line__:
			case File__:
			case Dir__:
			case Inc:
			case Dec:
			case NamespaceSeparator:
			case Bang:
			case Plus:
			case Minus:
			case Tilde:
			case SuppressWarnings:
			case Dollar:
			case OpenRoundBracket:
			case OpenSquareBracket:
			case OpenCurlyBracket:
			case SemiColon:
			case Label:
			case VarName:
			case Numeric:
			case Real:
			case BackQuoteString:
			case SingleQuoteString:
			case DoubleQuoteString:
			case StartNowDoc:
			case StartHereDoc:
				enterOuterAlt(_localctx, 1);
				{
				setState(381);
				phpBlock();
				}
				break;
			case HtmlQuoteString:
				enterOuterAlt(_localctx, 2);
				{
				setState(382);
				match(HtmlQuoteString);
				}
				break;
			default:
				throw new NoViableAltException(this);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class HtmlDoubleQuotePhpBlockOrStringContext extends ParserRuleContext {
		public PhpBlockContext phpBlock() {
			return getRuleContext(PhpBlockContext.class,0);
		}
		public TerminalNode HtmlDoubleQuoteString() { return getToken(PHPParser.HtmlDoubleQuoteString, 0); }
		public HtmlDoubleQuotePhpBlockOrStringContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_htmlDoubleQuotePhpBlockOrString; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterHtmlDoubleQuotePhpBlockOrString(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitHtmlDoubleQuotePhpBlockOrString(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitHtmlDoubleQuotePhpBlockOrString(this);
			else return visitor.visitChildren(this);
		}
	}

	public final HtmlDoubleQuotePhpBlockOrStringContext htmlDoubleQuotePhpBlockOrString() throws RecognitionException {
		HtmlDoubleQuotePhpBlockOrStringContext _localctx = new HtmlDoubleQuotePhpBlockOrStringContext(_ctx, getState());
		enterRule(_localctx, 12, RULE_htmlDoubleQuotePhpBlockOrString);
		try {
			setState(387);
			switch (_input.LA(1)) {
			case HtmlScriptOpen:
			case HtmlStyleOpen:
			case HtmlComment:
			case HtmlDtd:
			case HtmlOpen:
			case Abstract:
			case Array:
			case BinaryCast:
			case BoolType:
			case BooleanConstant:
			case Break:
			case Callable:
			case Case:
			case Class:
			case Clone:
			case Const:
			case Continue:
			case Declare:
			case Default:
			case Do:
			case DoubleCast:
			case DoubleType:
			case Echo:
			case Empty:
			case Eval:
			case Exit:
			case Final:
			case FloatCast:
			case For:
			case Foreach:
			case Function:
			case Global:
			case Goto:
			case If:
			case Import:
			case Include:
			case IncludeOnce:
			case Int16Cast:
			case Int64Type:
			case Int8Cast:
			case Interface:
			case IntType:
			case IsSet:
			case List:
			case LogicalAnd:
			case LogicalOr:
			case LogicalXor:
			case Namespace:
			case New:
			case Null:
			case ObjectType:
			case Parent_:
			case Partial:
			case Print:
			case Private:
			case Protected:
			case Public:
			case Require:
			case RequireOnce:
			case Resource:
			case Return:
			case Static:
			case StringType:
			case Switch:
			case Throw:
			case Trait:
			case Try:
			case Uint16Cast:
			case Uint32Cast:
			case Uint64Cast:
			case Uint8Cast:
			case UnicodeCast:
			case Unset:
			case Use:
			case Var:
			case While:
			case Yield:
			case Get:
			case Set:
			case Call:
			case CallStatic:
			case Constructor:
			case Destruct:
			case Wakeup:
			case Sleep:
			case Autoload:
			case IsSet__:
			case Unset__:
			case ToString__:
			case Invoke:
			case SetState:
			case Clone__:
			case DebugInfo:
			case Namespace__:
			case Class__:
			case Traic__:
			case Function__:
			case Method__:
			case Line__:
			case File__:
			case Dir__:
			case Inc:
			case Dec:
			case NamespaceSeparator:
			case Bang:
			case Plus:
			case Minus:
			case Tilde:
			case SuppressWarnings:
			case Dollar:
			case OpenRoundBracket:
			case OpenSquareBracket:
			case OpenCurlyBracket:
			case SemiColon:
			case Label:
			case VarName:
			case Numeric:
			case Real:
			case BackQuoteString:
			case SingleQuoteString:
			case DoubleQuoteString:
			case StartNowDoc:
			case StartHereDoc:
				enterOuterAlt(_localctx, 1);
				{
				setState(385);
				phpBlock();
				}
				break;
			case HtmlDoubleQuoteString:
				enterOuterAlt(_localctx, 2);
				{
				setState(386);
				match(HtmlDoubleQuoteString);
				}
				break;
			default:
				throw new NoViableAltException(this);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class ScriptTextContext extends ParserRuleContext {
		public List<ScriptTextPartContext> scriptTextPart() {
			return getRuleContexts(ScriptTextPartContext.class);
		}
		public ScriptTextPartContext scriptTextPart(int i) {
			return getRuleContext(ScriptTextPartContext.class,i);
		}
		public ScriptTextContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_scriptText; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterScriptText(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitScriptText(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitScriptText(this);
			else return visitor.visitChildren(this);
		}
	}

	public final ScriptTextContext scriptText() throws RecognitionException {
		ScriptTextContext _localctx = new ScriptTextContext(_ctx, getState());
		enterRule(_localctx, 14, RULE_scriptText);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(392);
			_errHandler.sync(this);
			_la = _input.LA(1);
			while ((((_la) & ~0x3f) == 0 && ((1L << _la) & ((1L << HtmlScriptOpen) | (1L << HtmlStyleOpen) | (1L << HtmlComment) | (1L << HtmlDtd) | (1L << HtmlOpen) | (1L << ScriptText) | (1L << Abstract) | (1L << Array) | (1L << BinaryCast) | (1L << BoolType) | (1L << BooleanConstant) | (1L << Break) | (1L << Callable) | (1L << Case) | (1L << Class) | (1L << Clone) | (1L << Const) | (1L << Continue) | (1L << Declare) | (1L << Default) | (1L << Do) | (1L << DoubleCast) | (1L << DoubleType) | (1L << Echo) | (1L << Empty))) != 0) || ((((_la - 65)) & ~0x3f) == 0 && ((1L << (_la - 65)) & ((1L << (Eval - 65)) | (1L << (Exit - 65)) | (1L << (Final - 65)) | (1L << (FloatCast - 65)) | (1L << (For - 65)) | (1L << (Foreach - 65)) | (1L << (Function - 65)) | (1L << (Global - 65)) | (1L << (Goto - 65)) | (1L << (If - 65)) | (1L << (Import - 65)) | (1L << (Include - 65)) | (1L << (IncludeOnce - 65)) | (1L << (Int16Cast - 65)) | (1L << (Int64Type - 65)) | (1L << (Int8Cast - 65)) | (1L << (Interface - 65)) | (1L << (IntType - 65)) | (1L << (IsSet - 65)) | (1L << (List - 65)) | (1L << (LogicalAnd - 65)) | (1L << (LogicalOr - 65)) | (1L << (LogicalXor - 65)) | (1L << (Namespace - 65)) | (1L << (New - 65)) | (1L << (Null - 65)) | (1L << (ObjectType - 65)) | (1L << (Parent_ - 65)) | (1L << (Partial - 65)) | (1L << (Print - 65)) | (1L << (Private - 65)) | (1L << (Protected - 65)) | (1L << (Public - 65)) | (1L << (Require - 65)) | (1L << (RequireOnce - 65)) | (1L << (Resource - 65)) | (1L << (Return - 65)) | (1L << (Static - 65)) | (1L << (StringType - 65)) | (1L << (Switch - 65)) | (1L << (Throw - 65)) | (1L << (Trait - 65)) | (1L << (Try - 65)) | (1L << (Uint16Cast - 65)) | (1L << (Uint32Cast - 65)) | (1L << (Uint64Cast - 65)) | (1L << (Uint8Cast - 65)) | (1L << (UnicodeCast - 65)) | (1L << (Unset - 65)) | (1L << (Use - 65)) | (1L << (Var - 65)) | (1L << (While - 65)) | (1L << (Yield - 65)) | (1L << (Get - 65)) | (1L << (Set - 65)) | (1L << (Call - 65)) | (1L << (CallStatic - 65)) | (1L << (Constructor - 65)))) != 0) || ((((_la - 129)) & ~0x3f) == 0 && ((1L << (_la - 129)) & ((1L << (Destruct - 129)) | (1L << (Wakeup - 129)) | (1L << (Sleep - 129)) | (1L << (Autoload - 129)) | (1L << (IsSet__ - 129)) | (1L << (Unset__ - 129)) | (1L << (ToString__ - 129)) | (1L << (Invoke - 129)) | (1L << (SetState - 129)) | (1L << (Clone__ - 129)) | (1L << (DebugInfo - 129)) | (1L << (Namespace__ - 129)) | (1L << (Class__ - 129)) | (1L << (Traic__ - 129)) | (1L << (Function__ - 129)) | (1L << (Method__ - 129)) | (1L << (Line__ - 129)) | (1L << (File__ - 129)) | (1L << (Dir__ - 129)) | (1L << (Inc - 129)) | (1L << (Dec - 129)) | (1L << (NamespaceSeparator - 129)) | (1L << (Bang - 129)) | (1L << (Plus - 129)) | (1L << (Minus - 129)) | (1L << (Tilde - 129)) | (1L << (SuppressWarnings - 129)))) != 0) || ((((_la - 193)) & ~0x3f) == 0 && ((1L << (_la - 193)) & ((1L << (Dollar - 193)) | (1L << (OpenRoundBracket - 193)) | (1L << (OpenSquareBracket - 193)) | (1L << (OpenCurlyBracket - 193)) | (1L << (SemiColon - 193)) | (1L << (Label - 193)) | (1L << (VarName - 193)) | (1L << (Numeric - 193)) | (1L << (Real - 193)) | (1L << (BackQuoteString - 193)) | (1L << (SingleQuoteString - 193)) | (1L << (DoubleQuoteString - 193)) | (1L << (StartNowDoc - 193)) | (1L << (StartHereDoc - 193)))) != 0)) {
				{
				{
				setState(389);
				scriptTextPart();
				}
				}
				setState(394);
				_errHandler.sync(this);
				_la = _input.LA(1);
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class ScriptTextPartContext extends ParserRuleContext {
		public PhpBlockContext phpBlock() {
			return getRuleContext(PhpBlockContext.class,0);
		}
		public List<TerminalNode> ScriptText() { return getTokens(PHPParser.ScriptText); }
		public TerminalNode ScriptText(int i) {
			return getToken(PHPParser.ScriptText, i);
		}
		public ScriptTextPartContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_scriptTextPart; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterScriptTextPart(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitScriptTextPart(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitScriptTextPart(this);
			else return visitor.visitChildren(this);
		}
	}

	public final ScriptTextPartContext scriptTextPart() throws RecognitionException {
		ScriptTextPartContext _localctx = new ScriptTextPartContext(_ctx, getState());
		enterRule(_localctx, 16, RULE_scriptTextPart);
		try {
			int _alt;
			setState(401);
			switch (_input.LA(1)) {
			case HtmlScriptOpen:
			case HtmlStyleOpen:
			case HtmlComment:
			case HtmlDtd:
			case HtmlOpen:
			case Abstract:
			case Array:
			case BinaryCast:
			case BoolType:
			case BooleanConstant:
			case Break:
			case Callable:
			case Case:
			case Class:
			case Clone:
			case Const:
			case Continue:
			case Declare:
			case Default:
			case Do:
			case DoubleCast:
			case DoubleType:
			case Echo:
			case Empty:
			case Eval:
			case Exit:
			case Final:
			case FloatCast:
			case For:
			case Foreach:
			case Function:
			case Global:
			case Goto:
			case If:
			case Import:
			case Include:
			case IncludeOnce:
			case Int16Cast:
			case Int64Type:
			case Int8Cast:
			case Interface:
			case IntType:
			case IsSet:
			case List:
			case LogicalAnd:
			case LogicalOr:
			case LogicalXor:
			case Namespace:
			case New:
			case Null:
			case ObjectType:
			case Parent_:
			case Partial:
			case Print:
			case Private:
			case Protected:
			case Public:
			case Require:
			case RequireOnce:
			case Resource:
			case Return:
			case Static:
			case StringType:
			case Switch:
			case Throw:
			case Trait:
			case Try:
			case Uint16Cast:
			case Uint32Cast:
			case Uint64Cast:
			case Uint8Cast:
			case UnicodeCast:
			case Unset:
			case Use:
			case Var:
			case While:
			case Yield:
			case Get:
			case Set:
			case Call:
			case CallStatic:
			case Constructor:
			case Destruct:
			case Wakeup:
			case Sleep:
			case Autoload:
			case IsSet__:
			case Unset__:
			case ToString__:
			case Invoke:
			case SetState:
			case Clone__:
			case DebugInfo:
			case Namespace__:
			case Class__:
			case Traic__:
			case Function__:
			case Method__:
			case Line__:
			case File__:
			case Dir__:
			case Inc:
			case Dec:
			case NamespaceSeparator:
			case Bang:
			case Plus:
			case Minus:
			case Tilde:
			case SuppressWarnings:
			case Dollar:
			case OpenRoundBracket:
			case OpenSquareBracket:
			case OpenCurlyBracket:
			case SemiColon:
			case Label:
			case VarName:
			case Numeric:
			case Real:
			case BackQuoteString:
			case SingleQuoteString:
			case DoubleQuoteString:
			case StartNowDoc:
			case StartHereDoc:
				enterOuterAlt(_localctx, 1);
				{
				setState(395);
				phpBlock();
				}
				break;
			case ScriptText:
				enterOuterAlt(_localctx, 2);
				{
				setState(397);
				_errHandler.sync(this);
				_alt = 1;
				do {
					switch (_alt) {
					case 1:
						{
						{
						setState(396);
						match(ScriptText);
						}
						}
						break;
					default:
						throw new NoViableAltException(this);
					}
					setState(399);
					_errHandler.sync(this);
					_alt = getInterpreter().adaptivePredict(_input,21,_ctx);
				} while ( _alt!=2 && _alt!= ATN.INVALID_ALT_NUMBER );
				}
				break;
			default:
				throw new NoViableAltException(this);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class PhpBlockContext extends ParserRuleContext {
		public List<ImportStatementContext> importStatement() {
			return getRuleContexts(ImportStatementContext.class);
		}
		public ImportStatementContext importStatement(int i) {
			return getRuleContext(ImportStatementContext.class,i);
		}
		public List<TopStatementContext> topStatement() {
			return getRuleContexts(TopStatementContext.class);
		}
		public TopStatementContext topStatement(int i) {
			return getRuleContext(TopStatementContext.class,i);
		}
		public PhpBlockContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_phpBlock; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterPhpBlock(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitPhpBlock(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitPhpBlock(this);
			else return visitor.visitChildren(this);
		}
	}

	public final PhpBlockContext phpBlock() throws RecognitionException {
		PhpBlockContext _localctx = new PhpBlockContext(_ctx, getState());
		enterRule(_localctx, 18, RULE_phpBlock);
		try {
			int _alt;
			enterOuterAlt(_localctx, 1);
			{
			setState(406);
			_errHandler.sync(this);
			_alt = getInterpreter().adaptivePredict(_input,23,_ctx);
			while ( _alt!=2 && _alt!= ATN.INVALID_ALT_NUMBER ) {
				if ( _alt==1 ) {
					{
					{
					setState(403);
					importStatement();
					}
					}
				}
				setState(408);
				_errHandler.sync(this);
				_alt = getInterpreter().adaptivePredict(_input,23,_ctx);
			}
			setState(410);
			_errHandler.sync(this);
			_alt = 1;
			do {
				switch (_alt) {
				case 1:
					{
					{
					setState(409);
					topStatement();
					}
					}
					break;
				default:
					throw new NoViableAltException(this);
				}
				setState(412);
				_errHandler.sync(this);
				_alt = getInterpreter().adaptivePredict(_input,24,_ctx);
			} while ( _alt!=2 && _alt!= ATN.INVALID_ALT_NUMBER );
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class ImportStatementContext extends ParserRuleContext {
		public TerminalNode Import() { return getToken(PHPParser.Import, 0); }
		public TerminalNode Namespace() { return getToken(PHPParser.Namespace, 0); }
		public NamespaceNameListContext namespaceNameList() {
			return getRuleContext(NamespaceNameListContext.class,0);
		}
		public ImportStatementContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_importStatement; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterImportStatement(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitImportStatement(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitImportStatement(this);
			else return visitor.visitChildren(this);
		}
	}

	public final ImportStatementContext importStatement() throws RecognitionException {
		ImportStatementContext _localctx = new ImportStatementContext(_ctx, getState());
		enterRule(_localctx, 20, RULE_importStatement);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(414);
			match(Import);
			setState(415);
			match(Namespace);
			setState(416);
			namespaceNameList();
			setState(417);
			match(SemiColon);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class TopStatementContext extends ParserRuleContext {
		public EmptyStatementContext emptyStatement() {
			return getRuleContext(EmptyStatementContext.class,0);
		}
		public NonEmptyStatementContext nonEmptyStatement() {
			return getRuleContext(NonEmptyStatementContext.class,0);
		}
		public UseDeclarationContext useDeclaration() {
			return getRuleContext(UseDeclarationContext.class,0);
		}
		public NamespaceDeclarationContext namespaceDeclaration() {
			return getRuleContext(NamespaceDeclarationContext.class,0);
		}
		public FunctionDeclarationContext functionDeclaration() {
			return getRuleContext(FunctionDeclarationContext.class,0);
		}
		public ClassDeclarationContext classDeclaration() {
			return getRuleContext(ClassDeclarationContext.class,0);
		}
		public GlobalConstantDeclarationContext globalConstantDeclaration() {
			return getRuleContext(GlobalConstantDeclarationContext.class,0);
		}
		public TopStatementContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_topStatement; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterTopStatement(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitTopStatement(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitTopStatement(this);
			else return visitor.visitChildren(this);
		}
	}

	public final TopStatementContext topStatement() throws RecognitionException {
		TopStatementContext _localctx = new TopStatementContext(_ctx, getState());
		enterRule(_localctx, 22, RULE_topStatement);
		try {
			setState(426);
			_errHandler.sync(this);
			switch ( getInterpreter().adaptivePredict(_input,25,_ctx) ) {
			case 1:
				enterOuterAlt(_localctx, 1);
				{
				setState(419);
				emptyStatement();
				}
				break;
			case 2:
				enterOuterAlt(_localctx, 2);
				{
				setState(420);
				nonEmptyStatement();
				}
				break;
			case 3:
				enterOuterAlt(_localctx, 3);
				{
				setState(421);
				useDeclaration();
				}
				break;
			case 4:
				enterOuterAlt(_localctx, 4);
				{
				setState(422);
				namespaceDeclaration();
				}
				break;
			case 5:
				enterOuterAlt(_localctx, 5);
				{
				setState(423);
				functionDeclaration();
				}
				break;
			case 6:
				enterOuterAlt(_localctx, 6);
				{
				setState(424);
				classDeclaration();
				}
				break;
			case 7:
				enterOuterAlt(_localctx, 7);
				{
				setState(425);
				globalConstantDeclaration();
				}
				break;
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class UseDeclarationContext extends ParserRuleContext {
		public TerminalNode Use() { return getToken(PHPParser.Use, 0); }
		public UseDeclarationContentListContext useDeclarationContentList() {
			return getRuleContext(UseDeclarationContentListContext.class,0);
		}
		public TerminalNode Function() { return getToken(PHPParser.Function, 0); }
		public TerminalNode Const() { return getToken(PHPParser.Const, 0); }
		public UseDeclarationContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_useDeclaration; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterUseDeclaration(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitUseDeclaration(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitUseDeclaration(this);
			else return visitor.visitChildren(this);
		}
	}

	public final UseDeclarationContext useDeclaration() throws RecognitionException {
		UseDeclarationContext _localctx = new UseDeclarationContext(_ctx, getState());
		enterRule(_localctx, 24, RULE_useDeclaration);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(428);
			match(Use);
			setState(430);
			_errHandler.sync(this);
			switch ( getInterpreter().adaptivePredict(_input,26,_ctx) ) {
			case 1:
				{
				setState(429);
				_la = _input.LA(1);
				if ( !(_la==Const || _la==Function) ) {
				_errHandler.recoverInline(this);
				} else {
					consume();
				}
				}
				break;
			}
			setState(432);
			useDeclarationContentList();
			setState(433);
			match(SemiColon);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class UseDeclarationContentListContext extends ParserRuleContext {
		public List<UseDeclarationContentContext> useDeclarationContent() {
			return getRuleContexts(UseDeclarationContentContext.class);
		}
		public UseDeclarationContentContext useDeclarationContent(int i) {
			return getRuleContext(UseDeclarationContentContext.class,i);
		}
		public UseDeclarationContentListContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_useDeclarationContentList; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterUseDeclarationContentList(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitUseDeclarationContentList(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitUseDeclarationContentList(this);
			else return visitor.visitChildren(this);
		}
	}

	public final UseDeclarationContentListContext useDeclarationContentList() throws RecognitionException {
		UseDeclarationContentListContext _localctx = new UseDeclarationContentListContext(_ctx, getState());
		enterRule(_localctx, 26, RULE_useDeclarationContentList);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(436);
			_la = _input.LA(1);
			if (_la==NamespaceSeparator) {
				{
				setState(435);
				match(NamespaceSeparator);
				}
			}

			setState(438);
			useDeclarationContent();
			setState(446);
			_errHandler.sync(this);
			_la = _input.LA(1);
			while (_la==Comma) {
				{
				{
				setState(439);
				match(Comma);
				setState(441);
				_la = _input.LA(1);
				if (_la==NamespaceSeparator) {
					{
					setState(440);
					match(NamespaceSeparator);
					}
				}

				setState(443);
				useDeclarationContent();
				}
				}
				setState(448);
				_errHandler.sync(this);
				_la = _input.LA(1);
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class UseDeclarationContentContext extends ParserRuleContext {
		public NamespaceNameListContext namespaceNameList() {
			return getRuleContext(NamespaceNameListContext.class,0);
		}
		public TerminalNode As() { return getToken(PHPParser.As, 0); }
		public IdentifierContext identifier() {
			return getRuleContext(IdentifierContext.class,0);
		}
		public UseDeclarationContentContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_useDeclarationContent; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterUseDeclarationContent(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitUseDeclarationContent(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitUseDeclarationContent(this);
			else return visitor.visitChildren(this);
		}
	}

	public final UseDeclarationContentContext useDeclarationContent() throws RecognitionException {
		UseDeclarationContentContext _localctx = new UseDeclarationContentContext(_ctx, getState());
		enterRule(_localctx, 28, RULE_useDeclarationContent);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(449);
			namespaceNameList();
			setState(452);
			_la = _input.LA(1);
			if (_la==As) {
				{
				setState(450);
				match(As);
				setState(451);
				identifier();
				}
			}

			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class NamespaceDeclarationContext extends ParserRuleContext {
		public TerminalNode Namespace() { return getToken(PHPParser.Namespace, 0); }
		public NamespaceNameListContext namespaceNameList() {
			return getRuleContext(NamespaceNameListContext.class,0);
		}
		public List<NamespaceStatementContext> namespaceStatement() {
			return getRuleContexts(NamespaceStatementContext.class);
		}
		public NamespaceStatementContext namespaceStatement(int i) {
			return getRuleContext(NamespaceStatementContext.class,i);
		}
		public NamespaceDeclarationContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_namespaceDeclaration; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterNamespaceDeclaration(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitNamespaceDeclaration(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitNamespaceDeclaration(this);
			else return visitor.visitChildren(this);
		}
	}

	public final NamespaceDeclarationContext namespaceDeclaration() throws RecognitionException {
		NamespaceDeclarationContext _localctx = new NamespaceDeclarationContext(_ctx, getState());
		enterRule(_localctx, 30, RULE_namespaceDeclaration);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(454);
			match(Namespace);
			setState(469);
			_errHandler.sync(this);
			switch ( getInterpreter().adaptivePredict(_input,33,_ctx) ) {
			case 1:
				{
				setState(456);
				_la = _input.LA(1);
				if (((((_la - 36)) & ~0x3f) == 0 && ((1L << (_la - 36)) & ((1L << (Abstract - 36)) | (1L << (Array - 36)) | (1L << (BinaryCast - 36)) | (1L << (BoolType - 36)) | (1L << (Break - 36)) | (1L << (Callable - 36)) | (1L << (Case - 36)) | (1L << (Class - 36)) | (1L << (Clone - 36)) | (1L << (Continue - 36)) | (1L << (Default - 36)) | (1L << (DoubleCast - 36)) | (1L << (DoubleType - 36)) | (1L << (Echo - 36)) | (1L << (Empty - 36)) | (1L << (Eval - 36)) | (1L << (Final - 36)) | (1L << (FloatCast - 36)) | (1L << (Function - 36)) | (1L << (Global - 36)) | (1L << (Import - 36)) | (1L << (Int16Cast - 36)) | (1L << (Int64Type - 36)) | (1L << (Int8Cast - 36)) | (1L << (Interface - 36)) | (1L << (IntType - 36)) | (1L << (IsSet - 36)) | (1L << (List - 36)) | (1L << (LogicalAnd - 36)) | (1L << (LogicalOr - 36)) | (1L << (LogicalXor - 36)) | (1L << (Namespace - 36)) | (1L << (New - 36)) | (1L << (Null - 36)) | (1L << (ObjectType - 36)) | (1L << (Parent_ - 36)) | (1L << (Partial - 36)))) != 0) || ((((_la - 100)) & ~0x3f) == 0 && ((1L << (_la - 100)) & ((1L << (Private - 100)) | (1L << (Protected - 100)) | (1L << (Public - 100)) | (1L << (Resource - 100)) | (1L << (Return - 100)) | (1L << (Static - 100)) | (1L << (StringType - 100)) | (1L << (Uint16Cast - 100)) | (1L << (Uint32Cast - 100)) | (1L << (Uint64Cast - 100)) | (1L << (Uint8Cast - 100)) | (1L << (UnicodeCast - 100)) | (1L << (Unset - 100)) | (1L << (Use - 100)) | (1L << (Var - 100)) | (1L << (Get - 100)) | (1L << (Set - 100)) | (1L << (Call - 100)) | (1L << (CallStatic - 100)) | (1L << (Constructor - 100)) | (1L << (Destruct - 100)) | (1L << (Wakeup - 100)) | (1L << (Sleep - 100)) | (1L << (Autoload - 100)) | (1L << (IsSet__ - 100)) | (1L << (Unset__ - 100)) | (1L << (ToString__ - 100)) | (1L << (Invoke - 100)) | (1L << (SetState - 100)) | (1L << (Clone__ - 100)) | (1L << (DebugInfo - 100)) | (1L << (Namespace__ - 100)) | (1L << (Class__ - 100)) | (1L << (Traic__ - 100)) | (1L << (Function__ - 100)) | (1L << (Method__ - 100)) | (1L << (Line__ - 100)) | (1L << (File__ - 100)) | (1L << (Dir__ - 100)))) != 0) || _la==Label) {
					{
					setState(455);
					namespaceNameList();
					}
				}

				setState(458);
				match(OpenCurlyBracket);
				setState(462);
				_errHandler.sync(this);
				_la = _input.LA(1);
				while ((((_la) & ~0x3f) == 0 && ((1L << _la) & ((1L << HtmlScriptOpen) | (1L << HtmlStyleOpen) | (1L << HtmlComment) | (1L << HtmlDtd) | (1L << HtmlOpen) | (1L << Abstract) | (1L << Array) | (1L << BinaryCast) | (1L << BoolType) | (1L << BooleanConstant) | (1L << Break) | (1L << Callable) | (1L << Case) | (1L << Class) | (1L << Clone) | (1L << Const) | (1L << Continue) | (1L << Declare) | (1L << Default) | (1L << Do) | (1L << DoubleCast) | (1L << DoubleType) | (1L << Echo) | (1L << Empty))) != 0) || ((((_la - 65)) & ~0x3f) == 0 && ((1L << (_la - 65)) & ((1L << (Eval - 65)) | (1L << (Exit - 65)) | (1L << (Final - 65)) | (1L << (FloatCast - 65)) | (1L << (For - 65)) | (1L << (Foreach - 65)) | (1L << (Function - 65)) | (1L << (Global - 65)) | (1L << (Goto - 65)) | (1L << (If - 65)) | (1L << (Import - 65)) | (1L << (Include - 65)) | (1L << (IncludeOnce - 65)) | (1L << (Int16Cast - 65)) | (1L << (Int64Type - 65)) | (1L << (Int8Cast - 65)) | (1L << (Interface - 65)) | (1L << (IntType - 65)) | (1L << (IsSet - 65)) | (1L << (List - 65)) | (1L << (LogicalAnd - 65)) | (1L << (LogicalOr - 65)) | (1L << (LogicalXor - 65)) | (1L << (Namespace - 65)) | (1L << (New - 65)) | (1L << (Null - 65)) | (1L << (ObjectType - 65)) | (1L << (Parent_ - 65)) | (1L << (Partial - 65)) | (1L << (Print - 65)) | (1L << (Private - 65)) | (1L << (Protected - 65)) | (1L << (Public - 65)) | (1L << (Require - 65)) | (1L << (RequireOnce - 65)) | (1L << (Resource - 65)) | (1L << (Return - 65)) | (1L << (Static - 65)) | (1L << (StringType - 65)) | (1L << (Switch - 65)) | (1L << (Throw - 65)) | (1L << (Trait - 65)) | (1L << (Try - 65)) | (1L << (Uint16Cast - 65)) | (1L << (Uint32Cast - 65)) | (1L << (Uint64Cast - 65)) | (1L << (Uint8Cast - 65)) | (1L << (UnicodeCast - 65)) | (1L << (Unset - 65)) | (1L << (Use - 65)) | (1L << (Var - 65)) | (1L << (While - 65)) | (1L << (Yield - 65)) | (1L << (Get - 65)) | (1L << (Set - 65)) | (1L << (Call - 65)) | (1L << (CallStatic - 65)) | (1L << (Constructor - 65)))) != 0) || ((((_la - 129)) & ~0x3f) == 0 && ((1L << (_la - 129)) & ((1L << (Destruct - 129)) | (1L << (Wakeup - 129)) | (1L << (Sleep - 129)) | (1L << (Autoload - 129)) | (1L << (IsSet__ - 129)) | (1L << (Unset__ - 129)) | (1L << (ToString__ - 129)) | (1L << (Invoke - 129)) | (1L << (SetState - 129)) | (1L << (Clone__ - 129)) | (1L << (DebugInfo - 129)) | (1L << (Namespace__ - 129)) | (1L << (Class__ - 129)) | (1L << (Traic__ - 129)) | (1L << (Function__ - 129)) | (1L << (Method__ - 129)) | (1L << (Line__ - 129)) | (1L << (File__ - 129)) | (1L << (Dir__ - 129)) | (1L << (Inc - 129)) | (1L << (Dec - 129)) | (1L << (NamespaceSeparator - 129)) | (1L << (Bang - 129)) | (1L << (Plus - 129)) | (1L << (Minus - 129)) | (1L << (Tilde - 129)) | (1L << (SuppressWarnings - 129)))) != 0) || ((((_la - 193)) & ~0x3f) == 0 && ((1L << (_la - 193)) & ((1L << (Dollar - 193)) | (1L << (OpenRoundBracket - 193)) | (1L << (OpenSquareBracket - 193)) | (1L << (OpenCurlyBracket - 193)) | (1L << (Label - 193)) | (1L << (VarName - 193)) | (1L << (Numeric - 193)) | (1L << (Real - 193)) | (1L << (BackQuoteString - 193)) | (1L << (SingleQuoteString - 193)) | (1L << (DoubleQuoteString - 193)) | (1L << (StartNowDoc - 193)) | (1L << (StartHereDoc - 193)))) != 0)) {
					{
					{
					setState(459);
					namespaceStatement();
					}
					}
					setState(464);
					_errHandler.sync(this);
					_la = _input.LA(1);
				}
				setState(465);
				match(CloseCurlyBracket);
				}
				break;
			case 2:
				{
				setState(466);
				namespaceNameList();
				setState(467);
				match(SemiColon);
				}
				break;
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class NamespaceStatementContext extends ParserRuleContext {
		public NonEmptyStatementContext nonEmptyStatement() {
			return getRuleContext(NonEmptyStatementContext.class,0);
		}
		public UseDeclarationContext useDeclaration() {
			return getRuleContext(UseDeclarationContext.class,0);
		}
		public FunctionDeclarationContext functionDeclaration() {
			return getRuleContext(FunctionDeclarationContext.class,0);
		}
		public ClassDeclarationContext classDeclaration() {
			return getRuleContext(ClassDeclarationContext.class,0);
		}
		public GlobalConstantDeclarationContext globalConstantDeclaration() {
			return getRuleContext(GlobalConstantDeclarationContext.class,0);
		}
		public NamespaceStatementContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_namespaceStatement; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterNamespaceStatement(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitNamespaceStatement(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitNamespaceStatement(this);
			else return visitor.visitChildren(this);
		}
	}

	public final NamespaceStatementContext namespaceStatement() throws RecognitionException {
		NamespaceStatementContext _localctx = new NamespaceStatementContext(_ctx, getState());
		enterRule(_localctx, 32, RULE_namespaceStatement);
		try {
			setState(476);
			_errHandler.sync(this);
			switch ( getInterpreter().adaptivePredict(_input,34,_ctx) ) {
			case 1:
				enterOuterAlt(_localctx, 1);
				{
				setState(471);
				nonEmptyStatement();
				}
				break;
			case 2:
				enterOuterAlt(_localctx, 2);
				{
				setState(472);
				useDeclaration();
				}
				break;
			case 3:
				enterOuterAlt(_localctx, 3);
				{
				setState(473);
				functionDeclaration();
				}
				break;
			case 4:
				enterOuterAlt(_localctx, 4);
				{
				setState(474);
				classDeclaration();
				}
				break;
			case 5:
				enterOuterAlt(_localctx, 5);
				{
				setState(475);
				globalConstantDeclaration();
				}
				break;
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class FunctionDeclarationContext extends ParserRuleContext {
		public AttributesContext attributes() {
			return getRuleContext(AttributesContext.class,0);
		}
		public TerminalNode Function() { return getToken(PHPParser.Function, 0); }
		public IdentifierContext identifier() {
			return getRuleContext(IdentifierContext.class,0);
		}
		public FormalParameterListContext formalParameterList() {
			return getRuleContext(FormalParameterListContext.class,0);
		}
		public BlockStatementContext blockStatement() {
			return getRuleContext(BlockStatementContext.class,0);
		}
		public TypeParameterListInBracketsContext typeParameterListInBrackets() {
			return getRuleContext(TypeParameterListInBracketsContext.class,0);
		}
		public FunctionDeclarationContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_functionDeclaration; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterFunctionDeclaration(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitFunctionDeclaration(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitFunctionDeclaration(this);
			else return visitor.visitChildren(this);
		}
	}

	public final FunctionDeclarationContext functionDeclaration() throws RecognitionException {
		FunctionDeclarationContext _localctx = new FunctionDeclarationContext(_ctx, getState());
		enterRule(_localctx, 34, RULE_functionDeclaration);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(478);
			attributes();
			setState(479);
			match(Function);
			setState(481);
			_la = _input.LA(1);
			if (_la==Ampersand) {
				{
				setState(480);
				match(Ampersand);
				}
			}

			setState(483);
			identifier();
			setState(485);
			_la = _input.LA(1);
			if (_la==Lgeneric) {
				{
				setState(484);
				typeParameterListInBrackets();
				}
			}

			setState(487);
			match(OpenRoundBracket);
			setState(488);
			formalParameterList();
			setState(489);
			match(CloseRoundBracket);
			setState(490);
			blockStatement();
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class ClassDeclarationContext extends ParserRuleContext {
		public AttributesContext attributes() {
			return getRuleContext(AttributesContext.class,0);
		}
		public ClassEntryTypeContext classEntryType() {
			return getRuleContext(ClassEntryTypeContext.class,0);
		}
		public IdentifierContext identifier() {
			return getRuleContext(IdentifierContext.class,0);
		}
		public TerminalNode Interface() { return getToken(PHPParser.Interface, 0); }
		public TerminalNode Private() { return getToken(PHPParser.Private, 0); }
		public ModifierContext modifier() {
			return getRuleContext(ModifierContext.class,0);
		}
		public TerminalNode Partial() { return getToken(PHPParser.Partial, 0); }
		public List<ClassStatementContext> classStatement() {
			return getRuleContexts(ClassStatementContext.class);
		}
		public ClassStatementContext classStatement(int i) {
			return getRuleContext(ClassStatementContext.class,i);
		}
		public TypeParameterListInBracketsContext typeParameterListInBrackets() {
			return getRuleContext(TypeParameterListInBracketsContext.class,0);
		}
		public TerminalNode Extends() { return getToken(PHPParser.Extends, 0); }
		public QualifiedStaticTypeRefContext qualifiedStaticTypeRef() {
			return getRuleContext(QualifiedStaticTypeRefContext.class,0);
		}
		public TerminalNode Implements() { return getToken(PHPParser.Implements, 0); }
		public InterfaceListContext interfaceList() {
			return getRuleContext(InterfaceListContext.class,0);
		}
		public ClassDeclarationContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_classDeclaration; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterClassDeclaration(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitClassDeclaration(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitClassDeclaration(this);
			else return visitor.visitChildren(this);
		}
	}

	public final ClassDeclarationContext classDeclaration() throws RecognitionException {
		ClassDeclarationContext _localctx = new ClassDeclarationContext(_ctx, getState());
		enterRule(_localctx, 36, RULE_classDeclaration);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(492);
			attributes();
			setState(494);
			_la = _input.LA(1);
			if (_la==Private) {
				{
				setState(493);
				match(Private);
				}
			}

			setState(497);
			_la = _input.LA(1);
			if (_la==Abstract || _la==Final) {
				{
				setState(496);
				modifier();
				}
			}

			setState(500);
			_la = _input.LA(1);
			if (_la==Partial) {
				{
				setState(499);
				match(Partial);
				}
			}

			setState(524);
			switch (_input.LA(1)) {
			case Class:
			case Trait:
				{
				setState(502);
				classEntryType();
				setState(503);
				identifier();
				setState(505);
				_la = _input.LA(1);
				if (_la==Lgeneric) {
					{
					setState(504);
					typeParameterListInBrackets();
					}
				}

				setState(509);
				_la = _input.LA(1);
				if (_la==Extends) {
					{
					setState(507);
					match(Extends);
					setState(508);
					qualifiedStaticTypeRef();
					}
				}

				setState(513);
				_la = _input.LA(1);
				if (_la==Implements) {
					{
					setState(511);
					match(Implements);
					setState(512);
					interfaceList();
					}
				}

				}
				break;
			case Interface:
				{
				setState(515);
				match(Interface);
				setState(516);
				identifier();
				setState(518);
				_la = _input.LA(1);
				if (_la==Lgeneric) {
					{
					setState(517);
					typeParameterListInBrackets();
					}
				}

				setState(522);
				_la = _input.LA(1);
				if (_la==Extends) {
					{
					setState(520);
					match(Extends);
					setState(521);
					interfaceList();
					}
				}

				}
				break;
			default:
				throw new NoViableAltException(this);
			}
			setState(526);
			match(OpenCurlyBracket);
			setState(530);
			_errHandler.sync(this);
			_la = _input.LA(1);
			while (_la==Abstract || _la==Const || ((((_la - 68)) & ~0x3f) == 0 && ((1L << (_la - 68)) & ((1L << (Final - 68)) | (1L << (Function - 68)) | (1L << (Private - 68)) | (1L << (Protected - 68)) | (1L << (Public - 68)) | (1L << (Static - 68)) | (1L << (Use - 68)) | (1L << (Var - 68)))) != 0) || _la==OpenSquareBracket) {
				{
				{
				setState(527);
				classStatement();
				}
				}
				setState(532);
				_errHandler.sync(this);
				_la = _input.LA(1);
			}
			setState(533);
			match(CloseCurlyBracket);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class ClassEntryTypeContext extends ParserRuleContext {
		public TerminalNode Class() { return getToken(PHPParser.Class, 0); }
		public TerminalNode Trait() { return getToken(PHPParser.Trait, 0); }
		public ClassEntryTypeContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_classEntryType; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterClassEntryType(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitClassEntryType(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitClassEntryType(this);
			else return visitor.visitChildren(this);
		}
	}

	public final ClassEntryTypeContext classEntryType() throws RecognitionException {
		ClassEntryTypeContext _localctx = new ClassEntryTypeContext(_ctx, getState());
		enterRule(_localctx, 38, RULE_classEntryType);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(535);
			_la = _input.LA(1);
			if ( !(_la==Class || _la==Trait) ) {
			_errHandler.recoverInline(this);
			} else {
				consume();
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class InterfaceListContext extends ParserRuleContext {
		public List<QualifiedStaticTypeRefContext> qualifiedStaticTypeRef() {
			return getRuleContexts(QualifiedStaticTypeRefContext.class);
		}
		public QualifiedStaticTypeRefContext qualifiedStaticTypeRef(int i) {
			return getRuleContext(QualifiedStaticTypeRefContext.class,i);
		}
		public InterfaceListContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_interfaceList; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterInterfaceList(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitInterfaceList(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitInterfaceList(this);
			else return visitor.visitChildren(this);
		}
	}

	public final InterfaceListContext interfaceList() throws RecognitionException {
		InterfaceListContext _localctx = new InterfaceListContext(_ctx, getState());
		enterRule(_localctx, 40, RULE_interfaceList);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(537);
			qualifiedStaticTypeRef();
			setState(542);
			_errHandler.sync(this);
			_la = _input.LA(1);
			while (_la==Comma) {
				{
				{
				setState(538);
				match(Comma);
				setState(539);
				qualifiedStaticTypeRef();
				}
				}
				setState(544);
				_errHandler.sync(this);
				_la = _input.LA(1);
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class TypeParameterListInBracketsContext extends ParserRuleContext {
		public TypeParameterListContext typeParameterList() {
			return getRuleContext(TypeParameterListContext.class,0);
		}
		public TypeParameterWithDefaultsListContext typeParameterWithDefaultsList() {
			return getRuleContext(TypeParameterWithDefaultsListContext.class,0);
		}
		public TypeParameterListInBracketsContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_typeParameterListInBrackets; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterTypeParameterListInBrackets(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitTypeParameterListInBrackets(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitTypeParameterListInBrackets(this);
			else return visitor.visitChildren(this);
		}
	}

	public final TypeParameterListInBracketsContext typeParameterListInBrackets() throws RecognitionException {
		TypeParameterListInBracketsContext _localctx = new TypeParameterListInBracketsContext(_ctx, getState());
		enterRule(_localctx, 42, RULE_typeParameterListInBrackets);
		try {
			setState(559);
			_errHandler.sync(this);
			switch ( getInterpreter().adaptivePredict(_input,48,_ctx) ) {
			case 1:
				enterOuterAlt(_localctx, 1);
				{
				setState(545);
				match(Lgeneric);
				setState(546);
				typeParameterList();
				setState(547);
				match(Rgeneric);
				}
				break;
			case 2:
				enterOuterAlt(_localctx, 2);
				{
				setState(549);
				match(Lgeneric);
				setState(550);
				typeParameterWithDefaultsList();
				setState(551);
				match(Rgeneric);
				}
				break;
			case 3:
				enterOuterAlt(_localctx, 3);
				{
				setState(553);
				match(Lgeneric);
				setState(554);
				typeParameterList();
				setState(555);
				match(Comma);
				setState(556);
				typeParameterWithDefaultsList();
				setState(557);
				match(Rgeneric);
				}
				break;
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class TypeParameterListContext extends ParserRuleContext {
		public List<TypeParameterDeclContext> typeParameterDecl() {
			return getRuleContexts(TypeParameterDeclContext.class);
		}
		public TypeParameterDeclContext typeParameterDecl(int i) {
			return getRuleContext(TypeParameterDeclContext.class,i);
		}
		public TypeParameterListContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_typeParameterList; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterTypeParameterList(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitTypeParameterList(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitTypeParameterList(this);
			else return visitor.visitChildren(this);
		}
	}

	public final TypeParameterListContext typeParameterList() throws RecognitionException {
		TypeParameterListContext _localctx = new TypeParameterListContext(_ctx, getState());
		enterRule(_localctx, 44, RULE_typeParameterList);
		try {
			int _alt;
			enterOuterAlt(_localctx, 1);
			{
			setState(561);
			typeParameterDecl();
			setState(566);
			_errHandler.sync(this);
			_alt = getInterpreter().adaptivePredict(_input,49,_ctx);
			while ( _alt!=2 && _alt!= ATN.INVALID_ALT_NUMBER ) {
				if ( _alt==1 ) {
					{
					{
					setState(562);
					match(Comma);
					setState(563);
					typeParameterDecl();
					}
					}
				}
				setState(568);
				_errHandler.sync(this);
				_alt = getInterpreter().adaptivePredict(_input,49,_ctx);
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class TypeParameterWithDefaultsListContext extends ParserRuleContext {
		public List<TypeParameterWithDefaultDeclContext> typeParameterWithDefaultDecl() {
			return getRuleContexts(TypeParameterWithDefaultDeclContext.class);
		}
		public TypeParameterWithDefaultDeclContext typeParameterWithDefaultDecl(int i) {
			return getRuleContext(TypeParameterWithDefaultDeclContext.class,i);
		}
		public TypeParameterWithDefaultsListContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_typeParameterWithDefaultsList; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterTypeParameterWithDefaultsList(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitTypeParameterWithDefaultsList(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitTypeParameterWithDefaultsList(this);
			else return visitor.visitChildren(this);
		}
	}

	public final TypeParameterWithDefaultsListContext typeParameterWithDefaultsList() throws RecognitionException {
		TypeParameterWithDefaultsListContext _localctx = new TypeParameterWithDefaultsListContext(_ctx, getState());
		enterRule(_localctx, 46, RULE_typeParameterWithDefaultsList);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(569);
			typeParameterWithDefaultDecl();
			setState(574);
			_errHandler.sync(this);
			_la = _input.LA(1);
			while (_la==Comma) {
				{
				{
				setState(570);
				match(Comma);
				setState(571);
				typeParameterWithDefaultDecl();
				}
				}
				setState(576);
				_errHandler.sync(this);
				_la = _input.LA(1);
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class TypeParameterDeclContext extends ParserRuleContext {
		public AttributesContext attributes() {
			return getRuleContext(AttributesContext.class,0);
		}
		public IdentifierContext identifier() {
			return getRuleContext(IdentifierContext.class,0);
		}
		public TypeParameterDeclContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_typeParameterDecl; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterTypeParameterDecl(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitTypeParameterDecl(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitTypeParameterDecl(this);
			else return visitor.visitChildren(this);
		}
	}

	public final TypeParameterDeclContext typeParameterDecl() throws RecognitionException {
		TypeParameterDeclContext _localctx = new TypeParameterDeclContext(_ctx, getState());
		enterRule(_localctx, 48, RULE_typeParameterDecl);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(577);
			attributes();
			setState(578);
			identifier();
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class TypeParameterWithDefaultDeclContext extends ParserRuleContext {
		public AttributesContext attributes() {
			return getRuleContext(AttributesContext.class,0);
		}
		public IdentifierContext identifier() {
			return getRuleContext(IdentifierContext.class,0);
		}
		public TerminalNode Eq() { return getToken(PHPParser.Eq, 0); }
		public QualifiedStaticTypeRefContext qualifiedStaticTypeRef() {
			return getRuleContext(QualifiedStaticTypeRefContext.class,0);
		}
		public PrimitiveTypeContext primitiveType() {
			return getRuleContext(PrimitiveTypeContext.class,0);
		}
		public TypeParameterWithDefaultDeclContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_typeParameterWithDefaultDecl; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterTypeParameterWithDefaultDecl(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitTypeParameterWithDefaultDecl(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitTypeParameterWithDefaultDecl(this);
			else return visitor.visitChildren(this);
		}
	}

	public final TypeParameterWithDefaultDeclContext typeParameterWithDefaultDecl() throws RecognitionException {
		TypeParameterWithDefaultDeclContext _localctx = new TypeParameterWithDefaultDeclContext(_ctx, getState());
		enterRule(_localctx, 50, RULE_typeParameterWithDefaultDecl);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(580);
			attributes();
			setState(581);
			identifier();
			setState(582);
			match(Eq);
			setState(585);
			_errHandler.sync(this);
			switch ( getInterpreter().adaptivePredict(_input,51,_ctx) ) {
			case 1:
				{
				setState(583);
				qualifiedStaticTypeRef();
				}
				break;
			case 2:
				{
				setState(584);
				primitiveType();
				}
				break;
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class GenericDynamicArgsContext extends ParserRuleContext {
		public List<TypeRefContext> typeRef() {
			return getRuleContexts(TypeRefContext.class);
		}
		public TypeRefContext typeRef(int i) {
			return getRuleContext(TypeRefContext.class,i);
		}
		public GenericDynamicArgsContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_genericDynamicArgs; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterGenericDynamicArgs(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitGenericDynamicArgs(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitGenericDynamicArgs(this);
			else return visitor.visitChildren(this);
		}
	}

	public final GenericDynamicArgsContext genericDynamicArgs() throws RecognitionException {
		GenericDynamicArgsContext _localctx = new GenericDynamicArgsContext(_ctx, getState());
		enterRule(_localctx, 52, RULE_genericDynamicArgs);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(587);
			match(Lgeneric);
			setState(588);
			typeRef();
			setState(593);
			_errHandler.sync(this);
			_la = _input.LA(1);
			while (_la==Comma) {
				{
				{
				setState(589);
				match(Comma);
				setState(590);
				typeRef();
				}
				}
				setState(595);
				_errHandler.sync(this);
				_la = _input.LA(1);
			}
			setState(596);
			match(Rgeneric);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class AttributesContext extends ParserRuleContext {
		public List<AttributesGroupContext> attributesGroup() {
			return getRuleContexts(AttributesGroupContext.class);
		}
		public AttributesGroupContext attributesGroup(int i) {
			return getRuleContext(AttributesGroupContext.class,i);
		}
		public AttributesContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_attributes; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterAttributes(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitAttributes(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitAttributes(this);
			else return visitor.visitChildren(this);
		}
	}

	public final AttributesContext attributes() throws RecognitionException {
		AttributesContext _localctx = new AttributesContext(_ctx, getState());
		enterRule(_localctx, 54, RULE_attributes);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(601);
			_errHandler.sync(this);
			_la = _input.LA(1);
			while (_la==OpenSquareBracket) {
				{
				{
				setState(598);
				attributesGroup();
				}
				}
				setState(603);
				_errHandler.sync(this);
				_la = _input.LA(1);
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class AttributesGroupContext extends ParserRuleContext {
		public List<AttributeContext> attribute() {
			return getRuleContexts(AttributeContext.class);
		}
		public AttributeContext attribute(int i) {
			return getRuleContext(AttributeContext.class,i);
		}
		public IdentifierContext identifier() {
			return getRuleContext(IdentifierContext.class,0);
		}
		public AttributesGroupContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_attributesGroup; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterAttributesGroup(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitAttributesGroup(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitAttributesGroup(this);
			else return visitor.visitChildren(this);
		}
	}

	public final AttributesGroupContext attributesGroup() throws RecognitionException {
		AttributesGroupContext _localctx = new AttributesGroupContext(_ctx, getState());
		enterRule(_localctx, 56, RULE_attributesGroup);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(604);
			match(OpenSquareBracket);
			setState(608);
			_errHandler.sync(this);
			switch ( getInterpreter().adaptivePredict(_input,54,_ctx) ) {
			case 1:
				{
				setState(605);
				identifier();
				setState(606);
				match(Colon);
				}
				break;
			}
			setState(610);
			attribute();
			setState(615);
			_errHandler.sync(this);
			_la = _input.LA(1);
			while (_la==Comma) {
				{
				{
				setState(611);
				match(Comma);
				setState(612);
				attribute();
				}
				}
				setState(617);
				_errHandler.sync(this);
				_la = _input.LA(1);
			}
			setState(618);
			match(CloseSquareBracket);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class AttributeContext extends ParserRuleContext {
		public QualifiedNamespaceNameContext qualifiedNamespaceName() {
			return getRuleContext(QualifiedNamespaceNameContext.class,0);
		}
		public AttributeArgListContext attributeArgList() {
			return getRuleContext(AttributeArgListContext.class,0);
		}
		public AttributeNamedArgListContext attributeNamedArgList() {
			return getRuleContext(AttributeNamedArgListContext.class,0);
		}
		public AttributeContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_attribute; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterAttribute(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitAttribute(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitAttribute(this);
			else return visitor.visitChildren(this);
		}
	}

	public final AttributeContext attribute() throws RecognitionException {
		AttributeContext _localctx = new AttributeContext(_ctx, getState());
		enterRule(_localctx, 58, RULE_attribute);
		try {
			setState(638);
			_errHandler.sync(this);
			switch ( getInterpreter().adaptivePredict(_input,56,_ctx) ) {
			case 1:
				enterOuterAlt(_localctx, 1);
				{
				setState(620);
				qualifiedNamespaceName();
				}
				break;
			case 2:
				enterOuterAlt(_localctx, 2);
				{
				setState(621);
				qualifiedNamespaceName();
				setState(622);
				match(OpenRoundBracket);
				setState(623);
				attributeArgList();
				setState(624);
				match(CloseRoundBracket);
				}
				break;
			case 3:
				enterOuterAlt(_localctx, 3);
				{
				setState(626);
				qualifiedNamespaceName();
				setState(627);
				match(OpenRoundBracket);
				setState(628);
				attributeNamedArgList();
				setState(629);
				match(CloseRoundBracket);
				}
				break;
			case 4:
				enterOuterAlt(_localctx, 4);
				{
				setState(631);
				qualifiedNamespaceName();
				setState(632);
				match(OpenRoundBracket);
				setState(633);
				attributeArgList();
				setState(634);
				match(Comma);
				setState(635);
				attributeNamedArgList();
				setState(636);
				match(CloseRoundBracket);
				}
				break;
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class AttributeArgListContext extends ParserRuleContext {
		public List<ExpressionContext> expression() {
			return getRuleContexts(ExpressionContext.class);
		}
		public ExpressionContext expression(int i) {
			return getRuleContext(ExpressionContext.class,i);
		}
		public AttributeArgListContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_attributeArgList; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterAttributeArgList(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitAttributeArgList(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitAttributeArgList(this);
			else return visitor.visitChildren(this);
		}
	}

	public final AttributeArgListContext attributeArgList() throws RecognitionException {
		AttributeArgListContext _localctx = new AttributeArgListContext(_ctx, getState());
		enterRule(_localctx, 60, RULE_attributeArgList);
		try {
			int _alt;
			enterOuterAlt(_localctx, 1);
			{
			setState(640);
			expression(0);
			setState(645);
			_errHandler.sync(this);
			_alt = getInterpreter().adaptivePredict(_input,57,_ctx);
			while ( _alt!=2 && _alt!= ATN.INVALID_ALT_NUMBER ) {
				if ( _alt==1 ) {
					{
					{
					setState(641);
					match(Comma);
					setState(642);
					expression(0);
					}
					}
				}
				setState(647);
				_errHandler.sync(this);
				_alt = getInterpreter().adaptivePredict(_input,57,_ctx);
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class AttributeNamedArgListContext extends ParserRuleContext {
		public List<AttributeNamedArgContext> attributeNamedArg() {
			return getRuleContexts(AttributeNamedArgContext.class);
		}
		public AttributeNamedArgContext attributeNamedArg(int i) {
			return getRuleContext(AttributeNamedArgContext.class,i);
		}
		public AttributeNamedArgListContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_attributeNamedArgList; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterAttributeNamedArgList(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitAttributeNamedArgList(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitAttributeNamedArgList(this);
			else return visitor.visitChildren(this);
		}
	}

	public final AttributeNamedArgListContext attributeNamedArgList() throws RecognitionException {
		AttributeNamedArgListContext _localctx = new AttributeNamedArgListContext(_ctx, getState());
		enterRule(_localctx, 62, RULE_attributeNamedArgList);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(648);
			attributeNamedArg();
			setState(653);
			_errHandler.sync(this);
			_la = _input.LA(1);
			while (_la==Comma) {
				{
				{
				setState(649);
				match(Comma);
				setState(650);
				attributeNamedArg();
				}
				}
				setState(655);
				_errHandler.sync(this);
				_la = _input.LA(1);
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class AttributeNamedArgContext extends ParserRuleContext {
		public TerminalNode VarName() { return getToken(PHPParser.VarName, 0); }
		public ExpressionContext expression() {
			return getRuleContext(ExpressionContext.class,0);
		}
		public AttributeNamedArgContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_attributeNamedArg; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterAttributeNamedArg(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitAttributeNamedArg(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitAttributeNamedArg(this);
			else return visitor.visitChildren(this);
		}
	}

	public final AttributeNamedArgContext attributeNamedArg() throws RecognitionException {
		AttributeNamedArgContext _localctx = new AttributeNamedArgContext(_ctx, getState());
		enterRule(_localctx, 64, RULE_attributeNamedArg);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(656);
			match(VarName);
			setState(657);
			match(DoubleArrow);
			setState(658);
			expression(0);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class InnerStatementListContext extends ParserRuleContext {
		public List<InnerStatementContext> innerStatement() {
			return getRuleContexts(InnerStatementContext.class);
		}
		public InnerStatementContext innerStatement(int i) {
			return getRuleContext(InnerStatementContext.class,i);
		}
		public InnerStatementListContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_innerStatementList; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterInnerStatementList(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitInnerStatementList(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitInnerStatementList(this);
			else return visitor.visitChildren(this);
		}
	}

	public final InnerStatementListContext innerStatementList() throws RecognitionException {
		InnerStatementListContext _localctx = new InnerStatementListContext(_ctx, getState());
		enterRule(_localctx, 66, RULE_innerStatementList);
		try {
			int _alt;
			enterOuterAlt(_localctx, 1);
			{
			setState(663);
			_errHandler.sync(this);
			_alt = getInterpreter().adaptivePredict(_input,59,_ctx);
			while ( _alt!=2 && _alt!= ATN.INVALID_ALT_NUMBER ) {
				if ( _alt==1 ) {
					{
					{
					setState(660);
					innerStatement();
					}
					}
				}
				setState(665);
				_errHandler.sync(this);
				_alt = getInterpreter().adaptivePredict(_input,59,_ctx);
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class InnerStatementContext extends ParserRuleContext {
		public StatementContext statement() {
			return getRuleContext(StatementContext.class,0);
		}
		public FunctionDeclarationContext functionDeclaration() {
			return getRuleContext(FunctionDeclarationContext.class,0);
		}
		public ClassDeclarationContext classDeclaration() {
			return getRuleContext(ClassDeclarationContext.class,0);
		}
		public InnerStatementContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_innerStatement; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterInnerStatement(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitInnerStatement(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitInnerStatement(this);
			else return visitor.visitChildren(this);
		}
	}

	public final InnerStatementContext innerStatement() throws RecognitionException {
		InnerStatementContext _localctx = new InnerStatementContext(_ctx, getState());
		enterRule(_localctx, 68, RULE_innerStatement);
		try {
			setState(669);
			_errHandler.sync(this);
			switch ( getInterpreter().adaptivePredict(_input,60,_ctx) ) {
			case 1:
				enterOuterAlt(_localctx, 1);
				{
				setState(666);
				statement();
				}
				break;
			case 2:
				enterOuterAlt(_localctx, 2);
				{
				setState(667);
				functionDeclaration();
				}
				break;
			case 3:
				enterOuterAlt(_localctx, 3);
				{
				setState(668);
				classDeclaration();
				}
				break;
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class StatementContext extends ParserRuleContext {
		public NonEmptyStatementContext nonEmptyStatement() {
			return getRuleContext(NonEmptyStatementContext.class,0);
		}
		public EmptyStatementContext emptyStatement() {
			return getRuleContext(EmptyStatementContext.class,0);
		}
		public StatementContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_statement; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterStatement(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitStatement(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitStatement(this);
			else return visitor.visitChildren(this);
		}
	}

	public final StatementContext statement() throws RecognitionException {
		StatementContext _localctx = new StatementContext(_ctx, getState());
		enterRule(_localctx, 70, RULE_statement);
		try {
			setState(673);
			switch (_input.LA(1)) {
			case HtmlScriptOpen:
			case HtmlStyleOpen:
			case HtmlComment:
			case HtmlDtd:
			case HtmlOpen:
			case Abstract:
			case Array:
			case BinaryCast:
			case BoolType:
			case BooleanConstant:
			case Break:
			case Callable:
			case Case:
			case Class:
			case Clone:
			case Continue:
			case Declare:
			case Default:
			case Do:
			case DoubleCast:
			case DoubleType:
			case Echo:
			case Empty:
			case Eval:
			case Exit:
			case Final:
			case FloatCast:
			case For:
			case Foreach:
			case Function:
			case Global:
			case Goto:
			case If:
			case Import:
			case Include:
			case IncludeOnce:
			case Int16Cast:
			case Int64Type:
			case Int8Cast:
			case Interface:
			case IntType:
			case IsSet:
			case List:
			case LogicalAnd:
			case LogicalOr:
			case LogicalXor:
			case Namespace:
			case New:
			case Null:
			case ObjectType:
			case Parent_:
			case Partial:
			case Print:
			case Private:
			case Protected:
			case Public:
			case Require:
			case RequireOnce:
			case Resource:
			case Return:
			case Static:
			case StringType:
			case Switch:
			case Throw:
			case Try:
			case Uint16Cast:
			case Uint32Cast:
			case Uint64Cast:
			case Uint8Cast:
			case UnicodeCast:
			case Unset:
			case Use:
			case Var:
			case While:
			case Yield:
			case Get:
			case Set:
			case Call:
			case CallStatic:
			case Constructor:
			case Destruct:
			case Wakeup:
			case Sleep:
			case Autoload:
			case IsSet__:
			case Unset__:
			case ToString__:
			case Invoke:
			case SetState:
			case Clone__:
			case DebugInfo:
			case Namespace__:
			case Class__:
			case Traic__:
			case Function__:
			case Method__:
			case Line__:
			case File__:
			case Dir__:
			case Inc:
			case Dec:
			case NamespaceSeparator:
			case Bang:
			case Plus:
			case Minus:
			case Tilde:
			case SuppressWarnings:
			case Dollar:
			case OpenRoundBracket:
			case OpenSquareBracket:
			case OpenCurlyBracket:
			case Label:
			case VarName:
			case Numeric:
			case Real:
			case BackQuoteString:
			case SingleQuoteString:
			case DoubleQuoteString:
			case StartNowDoc:
			case StartHereDoc:
				enterOuterAlt(_localctx, 1);
				{
				setState(671);
				nonEmptyStatement();
				}
				break;
			case SemiColon:
				enterOuterAlt(_localctx, 2);
				{
				setState(672);
				emptyStatement();
				}
				break;
			default:
				throw new NoViableAltException(this);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class EmptyStatementContext extends ParserRuleContext {
		public EmptyStatementContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_emptyStatement; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterEmptyStatement(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitEmptyStatement(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitEmptyStatement(this);
			else return visitor.visitChildren(this);
		}
	}

	public final EmptyStatementContext emptyStatement() throws RecognitionException {
		EmptyStatementContext _localctx = new EmptyStatementContext(_ctx, getState());
		enterRule(_localctx, 72, RULE_emptyStatement);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(675);
			match(SemiColon);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class NonEmptyStatementContext extends ParserRuleContext {
		public IdentifierContext identifier() {
			return getRuleContext(IdentifierContext.class,0);
		}
		public BlockStatementContext blockStatement() {
			return getRuleContext(BlockStatementContext.class,0);
		}
		public IfStatementContext ifStatement() {
			return getRuleContext(IfStatementContext.class,0);
		}
		public WhileStatementContext whileStatement() {
			return getRuleContext(WhileStatementContext.class,0);
		}
		public DoWhileStatementContext doWhileStatement() {
			return getRuleContext(DoWhileStatementContext.class,0);
		}
		public ForStatementContext forStatement() {
			return getRuleContext(ForStatementContext.class,0);
		}
		public SwitchStatementContext switchStatement() {
			return getRuleContext(SwitchStatementContext.class,0);
		}
		public BreakStatementContext breakStatement() {
			return getRuleContext(BreakStatementContext.class,0);
		}
		public ContinueStatementContext continueStatement() {
			return getRuleContext(ContinueStatementContext.class,0);
		}
		public ReturnStatementContext returnStatement() {
			return getRuleContext(ReturnStatementContext.class,0);
		}
		public YieldExpressionContext yieldExpression() {
			return getRuleContext(YieldExpressionContext.class,0);
		}
		public GlobalStatementContext globalStatement() {
			return getRuleContext(GlobalStatementContext.class,0);
		}
		public StaticVariableStatementContext staticVariableStatement() {
			return getRuleContext(StaticVariableStatementContext.class,0);
		}
		public EchoStatementContext echoStatement() {
			return getRuleContext(EchoStatementContext.class,0);
		}
		public ExpressionStatementContext expressionStatement() {
			return getRuleContext(ExpressionStatementContext.class,0);
		}
		public UnsetStatementContext unsetStatement() {
			return getRuleContext(UnsetStatementContext.class,0);
		}
		public ForeachStatementContext foreachStatement() {
			return getRuleContext(ForeachStatementContext.class,0);
		}
		public TryCatchFinallyContext tryCatchFinally() {
			return getRuleContext(TryCatchFinallyContext.class,0);
		}
		public ThrowStatementContext throwStatement() {
			return getRuleContext(ThrowStatementContext.class,0);
		}
		public GotoStatementContext gotoStatement() {
			return getRuleContext(GotoStatementContext.class,0);
		}
		public DeclareStatementContext declareStatement() {
			return getRuleContext(DeclareStatementContext.class,0);
		}
		public InlineHtmlContext inlineHtml() {
			return getRuleContext(InlineHtmlContext.class,0);
		}
		public NonEmptyStatementContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_nonEmptyStatement; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterNonEmptyStatement(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitNonEmptyStatement(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitNonEmptyStatement(this);
			else return visitor.visitChildren(this);
		}
	}

	public final NonEmptyStatementContext nonEmptyStatement() throws RecognitionException {
		NonEmptyStatementContext _localctx = new NonEmptyStatementContext(_ctx, getState());
		enterRule(_localctx, 74, RULE_nonEmptyStatement);
		try {
			setState(703);
			_errHandler.sync(this);
			switch ( getInterpreter().adaptivePredict(_input,62,_ctx) ) {
			case 1:
				enterOuterAlt(_localctx, 1);
				{
				setState(677);
				identifier();
				setState(678);
				match(Colon);
				}
				break;
			case 2:
				enterOuterAlt(_localctx, 2);
				{
				setState(680);
				blockStatement();
				}
				break;
			case 3:
				enterOuterAlt(_localctx, 3);
				{
				setState(681);
				ifStatement();
				}
				break;
			case 4:
				enterOuterAlt(_localctx, 4);
				{
				setState(682);
				whileStatement();
				}
				break;
			case 5:
				enterOuterAlt(_localctx, 5);
				{
				setState(683);
				doWhileStatement();
				}
				break;
			case 6:
				enterOuterAlt(_localctx, 6);
				{
				setState(684);
				forStatement();
				}
				break;
			case 7:
				enterOuterAlt(_localctx, 7);
				{
				setState(685);
				switchStatement();
				}
				break;
			case 8:
				enterOuterAlt(_localctx, 8);
				{
				setState(686);
				breakStatement();
				}
				break;
			case 9:
				enterOuterAlt(_localctx, 9);
				{
				setState(687);
				continueStatement();
				}
				break;
			case 10:
				enterOuterAlt(_localctx, 10);
				{
				setState(688);
				returnStatement();
				}
				break;
			case 11:
				enterOuterAlt(_localctx, 11);
				{
				setState(689);
				yieldExpression();
				setState(690);
				match(SemiColon);
				}
				break;
			case 12:
				enterOuterAlt(_localctx, 12);
				{
				setState(692);
				globalStatement();
				}
				break;
			case 13:
				enterOuterAlt(_localctx, 13);
				{
				setState(693);
				staticVariableStatement();
				}
				break;
			case 14:
				enterOuterAlt(_localctx, 14);
				{
				setState(694);
				echoStatement();
				}
				break;
			case 15:
				enterOuterAlt(_localctx, 15);
				{
				setState(695);
				expressionStatement();
				}
				break;
			case 16:
				enterOuterAlt(_localctx, 16);
				{
				setState(696);
				unsetStatement();
				}
				break;
			case 17:
				enterOuterAlt(_localctx, 17);
				{
				setState(697);
				foreachStatement();
				}
				break;
			case 18:
				enterOuterAlt(_localctx, 18);
				{
				setState(698);
				tryCatchFinally();
				}
				break;
			case 19:
				enterOuterAlt(_localctx, 19);
				{
				setState(699);
				throwStatement();
				}
				break;
			case 20:
				enterOuterAlt(_localctx, 20);
				{
				setState(700);
				gotoStatement();
				}
				break;
			case 21:
				enterOuterAlt(_localctx, 21);
				{
				setState(701);
				declareStatement();
				}
				break;
			case 22:
				enterOuterAlt(_localctx, 22);
				{
				setState(702);
				inlineHtml();
				}
				break;
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class BlockStatementContext extends ParserRuleContext {
		public InnerStatementListContext innerStatementList() {
			return getRuleContext(InnerStatementListContext.class,0);
		}
		public BlockStatementContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_blockStatement; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterBlockStatement(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitBlockStatement(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitBlockStatement(this);
			else return visitor.visitChildren(this);
		}
	}

	public final BlockStatementContext blockStatement() throws RecognitionException {
		BlockStatementContext _localctx = new BlockStatementContext(_ctx, getState());
		enterRule(_localctx, 76, RULE_blockStatement);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(705);
			match(OpenCurlyBracket);
			setState(706);
			innerStatementList();
			setState(707);
			match(CloseCurlyBracket);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class IfStatementContext extends ParserRuleContext {
		public TerminalNode If() { return getToken(PHPParser.If, 0); }
		public ParenthesisContext parenthesis() {
			return getRuleContext(ParenthesisContext.class,0);
		}
		public StatementContext statement() {
			return getRuleContext(StatementContext.class,0);
		}
		public List<ElseIfStatementContext> elseIfStatement() {
			return getRuleContexts(ElseIfStatementContext.class);
		}
		public ElseIfStatementContext elseIfStatement(int i) {
			return getRuleContext(ElseIfStatementContext.class,i);
		}
		public ElseStatementContext elseStatement() {
			return getRuleContext(ElseStatementContext.class,0);
		}
		public InnerStatementListContext innerStatementList() {
			return getRuleContext(InnerStatementListContext.class,0);
		}
		public TerminalNode EndIf() { return getToken(PHPParser.EndIf, 0); }
		public List<ElseIfColonStatementContext> elseIfColonStatement() {
			return getRuleContexts(ElseIfColonStatementContext.class);
		}
		public ElseIfColonStatementContext elseIfColonStatement(int i) {
			return getRuleContext(ElseIfColonStatementContext.class,i);
		}
		public ElseColonStatementContext elseColonStatement() {
			return getRuleContext(ElseColonStatementContext.class,0);
		}
		public IfStatementContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_ifStatement; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterIfStatement(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitIfStatement(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitIfStatement(this);
			else return visitor.visitChildren(this);
		}
	}

	public final IfStatementContext ifStatement() throws RecognitionException {
		IfStatementContext _localctx = new IfStatementContext(_ctx, getState());
		enterRule(_localctx, 78, RULE_ifStatement);
		int _la;
		try {
			int _alt;
			setState(737);
			_errHandler.sync(this);
			switch ( getInterpreter().adaptivePredict(_input,67,_ctx) ) {
			case 1:
				enterOuterAlt(_localctx, 1);
				{
				setState(709);
				match(If);
				setState(710);
				parenthesis();
				setState(711);
				statement();
				setState(715);
				_errHandler.sync(this);
				_alt = getInterpreter().adaptivePredict(_input,63,_ctx);
				while ( _alt!=2 && _alt!= ATN.INVALID_ALT_NUMBER ) {
					if ( _alt==1 ) {
						{
						{
						setState(712);
						elseIfStatement();
						}
						}
					}
					setState(717);
					_errHandler.sync(this);
					_alt = getInterpreter().adaptivePredict(_input,63,_ctx);
				}
				setState(719);
				_errHandler.sync(this);
				switch ( getInterpreter().adaptivePredict(_input,64,_ctx) ) {
				case 1:
					{
					setState(718);
					elseStatement();
					}
					break;
				}
				}
				break;
			case 2:
				enterOuterAlt(_localctx, 2);
				{
				setState(721);
				match(If);
				setState(722);
				parenthesis();
				setState(723);
				match(Colon);
				setState(724);
				innerStatementList();
				setState(728);
				_errHandler.sync(this);
				_la = _input.LA(1);
				while (_la==ElseIf) {
					{
					{
					setState(725);
					elseIfColonStatement();
					}
					}
					setState(730);
					_errHandler.sync(this);
					_la = _input.LA(1);
				}
				setState(732);
				_la = _input.LA(1);
				if (_la==Else) {
					{
					setState(731);
					elseColonStatement();
					}
				}

				setState(734);
				match(EndIf);
				setState(735);
				match(SemiColon);
				}
				break;
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class ElseIfStatementContext extends ParserRuleContext {
		public TerminalNode ElseIf() { return getToken(PHPParser.ElseIf, 0); }
		public ParenthesisContext parenthesis() {
			return getRuleContext(ParenthesisContext.class,0);
		}
		public StatementContext statement() {
			return getRuleContext(StatementContext.class,0);
		}
		public ElseIfStatementContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_elseIfStatement; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterElseIfStatement(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitElseIfStatement(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitElseIfStatement(this);
			else return visitor.visitChildren(this);
		}
	}

	public final ElseIfStatementContext elseIfStatement() throws RecognitionException {
		ElseIfStatementContext _localctx = new ElseIfStatementContext(_ctx, getState());
		enterRule(_localctx, 80, RULE_elseIfStatement);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(739);
			match(ElseIf);
			setState(740);
			parenthesis();
			setState(741);
			statement();
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class ElseIfColonStatementContext extends ParserRuleContext {
		public TerminalNode ElseIf() { return getToken(PHPParser.ElseIf, 0); }
		public ParenthesisContext parenthesis() {
			return getRuleContext(ParenthesisContext.class,0);
		}
		public InnerStatementListContext innerStatementList() {
			return getRuleContext(InnerStatementListContext.class,0);
		}
		public ElseIfColonStatementContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_elseIfColonStatement; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterElseIfColonStatement(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitElseIfColonStatement(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitElseIfColonStatement(this);
			else return visitor.visitChildren(this);
		}
	}

	public final ElseIfColonStatementContext elseIfColonStatement() throws RecognitionException {
		ElseIfColonStatementContext _localctx = new ElseIfColonStatementContext(_ctx, getState());
		enterRule(_localctx, 82, RULE_elseIfColonStatement);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(743);
			match(ElseIf);
			setState(744);
			parenthesis();
			setState(745);
			match(Colon);
			setState(746);
			innerStatementList();
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class ElseStatementContext extends ParserRuleContext {
		public TerminalNode Else() { return getToken(PHPParser.Else, 0); }
		public StatementContext statement() {
			return getRuleContext(StatementContext.class,0);
		}
		public ElseStatementContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_elseStatement; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterElseStatement(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitElseStatement(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitElseStatement(this);
			else return visitor.visitChildren(this);
		}
	}

	public final ElseStatementContext elseStatement() throws RecognitionException {
		ElseStatementContext _localctx = new ElseStatementContext(_ctx, getState());
		enterRule(_localctx, 84, RULE_elseStatement);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(748);
			match(Else);
			setState(749);
			statement();
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class ElseColonStatementContext extends ParserRuleContext {
		public TerminalNode Else() { return getToken(PHPParser.Else, 0); }
		public InnerStatementListContext innerStatementList() {
			return getRuleContext(InnerStatementListContext.class,0);
		}
		public ElseColonStatementContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_elseColonStatement; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterElseColonStatement(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitElseColonStatement(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitElseColonStatement(this);
			else return visitor.visitChildren(this);
		}
	}

	public final ElseColonStatementContext elseColonStatement() throws RecognitionException {
		ElseColonStatementContext _localctx = new ElseColonStatementContext(_ctx, getState());
		enterRule(_localctx, 86, RULE_elseColonStatement);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(751);
			match(Else);
			setState(752);
			match(Colon);
			setState(753);
			innerStatementList();
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class WhileStatementContext extends ParserRuleContext {
		public TerminalNode While() { return getToken(PHPParser.While, 0); }
		public ParenthesisContext parenthesis() {
			return getRuleContext(ParenthesisContext.class,0);
		}
		public StatementContext statement() {
			return getRuleContext(StatementContext.class,0);
		}
		public InnerStatementListContext innerStatementList() {
			return getRuleContext(InnerStatementListContext.class,0);
		}
		public TerminalNode EndWhile() { return getToken(PHPParser.EndWhile, 0); }
		public WhileStatementContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_whileStatement; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterWhileStatement(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitWhileStatement(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitWhileStatement(this);
			else return visitor.visitChildren(this);
		}
	}

	public final WhileStatementContext whileStatement() throws RecognitionException {
		WhileStatementContext _localctx = new WhileStatementContext(_ctx, getState());
		enterRule(_localctx, 88, RULE_whileStatement);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(755);
			match(While);
			setState(756);
			parenthesis();
			setState(763);
			switch (_input.LA(1)) {
			case HtmlScriptOpen:
			case HtmlStyleOpen:
			case HtmlComment:
			case HtmlDtd:
			case HtmlOpen:
			case Abstract:
			case Array:
			case BinaryCast:
			case BoolType:
			case BooleanConstant:
			case Break:
			case Callable:
			case Case:
			case Class:
			case Clone:
			case Continue:
			case Declare:
			case Default:
			case Do:
			case DoubleCast:
			case DoubleType:
			case Echo:
			case Empty:
			case Eval:
			case Exit:
			case Final:
			case FloatCast:
			case For:
			case Foreach:
			case Function:
			case Global:
			case Goto:
			case If:
			case Import:
			case Include:
			case IncludeOnce:
			case Int16Cast:
			case Int64Type:
			case Int8Cast:
			case Interface:
			case IntType:
			case IsSet:
			case List:
			case LogicalAnd:
			case LogicalOr:
			case LogicalXor:
			case Namespace:
			case New:
			case Null:
			case ObjectType:
			case Parent_:
			case Partial:
			case Print:
			case Private:
			case Protected:
			case Public:
			case Require:
			case RequireOnce:
			case Resource:
			case Return:
			case Static:
			case StringType:
			case Switch:
			case Throw:
			case Try:
			case Uint16Cast:
			case Uint32Cast:
			case Uint64Cast:
			case Uint8Cast:
			case UnicodeCast:
			case Unset:
			case Use:
			case Var:
			case While:
			case Yield:
			case Get:
			case Set:
			case Call:
			case CallStatic:
			case Constructor:
			case Destruct:
			case Wakeup:
			case Sleep:
			case Autoload:
			case IsSet__:
			case Unset__:
			case ToString__:
			case Invoke:
			case SetState:
			case Clone__:
			case DebugInfo:
			case Namespace__:
			case Class__:
			case Traic__:
			case Function__:
			case Method__:
			case Line__:
			case File__:
			case Dir__:
			case Inc:
			case Dec:
			case NamespaceSeparator:
			case Bang:
			case Plus:
			case Minus:
			case Tilde:
			case SuppressWarnings:
			case Dollar:
			case OpenRoundBracket:
			case OpenSquareBracket:
			case OpenCurlyBracket:
			case SemiColon:
			case Label:
			case VarName:
			case Numeric:
			case Real:
			case BackQuoteString:
			case SingleQuoteString:
			case DoubleQuoteString:
			case StartNowDoc:
			case StartHereDoc:
				{
				setState(757);
				statement();
				}
				break;
			case Colon:
				{
				setState(758);
				match(Colon);
				setState(759);
				innerStatementList();
				setState(760);
				match(EndWhile);
				setState(761);
				match(SemiColon);
				}
				break;
			default:
				throw new NoViableAltException(this);
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class DoWhileStatementContext extends ParserRuleContext {
		public TerminalNode Do() { return getToken(PHPParser.Do, 0); }
		public StatementContext statement() {
			return getRuleContext(StatementContext.class,0);
		}
		public TerminalNode While() { return getToken(PHPParser.While, 0); }
		public ParenthesisContext parenthesis() {
			return getRuleContext(ParenthesisContext.class,0);
		}
		public DoWhileStatementContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_doWhileStatement; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterDoWhileStatement(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitDoWhileStatement(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitDoWhileStatement(this);
			else return visitor.visitChildren(this);
		}
	}

	public final DoWhileStatementContext doWhileStatement() throws RecognitionException {
		DoWhileStatementContext _localctx = new DoWhileStatementContext(_ctx, getState());
		enterRule(_localctx, 90, RULE_doWhileStatement);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(765);
			match(Do);
			setState(766);
			statement();
			setState(767);
			match(While);
			setState(768);
			parenthesis();
			setState(769);
			match(SemiColon);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class ForStatementContext extends ParserRuleContext {
		public TerminalNode For() { return getToken(PHPParser.For, 0); }
		public StatementContext statement() {
			return getRuleContext(StatementContext.class,0);
		}
		public InnerStatementListContext innerStatementList() {
			return getRuleContext(InnerStatementListContext.class,0);
		}
		public TerminalNode EndFor() { return getToken(PHPParser.EndFor, 0); }
		public ForInitContext forInit() {
			return getRuleContext(ForInitContext.class,0);
		}
		public ExpressionListContext expressionList() {
			return getRuleContext(ExpressionListContext.class,0);
		}
		public ForUpdateContext forUpdate() {
			return getRuleContext(ForUpdateContext.class,0);
		}
		public ForStatementContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_forStatement; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterForStatement(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitForStatement(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitForStatement(this);
			else return visitor.visitChildren(this);
		}
	}

	public final ForStatementContext forStatement() throws RecognitionException {
		ForStatementContext _localctx = new ForStatementContext(_ctx, getState());
		enterRule(_localctx, 92, RULE_forStatement);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(771);
			match(For);
			setState(772);
			match(OpenRoundBracket);
			setState(774);
			_la = _input.LA(1);
			if (((((_la - 36)) & ~0x3f) == 0 && ((1L << (_la - 36)) & ((1L << (Abstract - 36)) | (1L << (Array - 36)) | (1L << (BinaryCast - 36)) | (1L << (BoolType - 36)) | (1L << (BooleanConstant - 36)) | (1L << (Break - 36)) | (1L << (Callable - 36)) | (1L << (Case - 36)) | (1L << (Class - 36)) | (1L << (Clone - 36)) | (1L << (Continue - 36)) | (1L << (Default - 36)) | (1L << (DoubleCast - 36)) | (1L << (DoubleType - 36)) | (1L << (Echo - 36)) | (1L << (Empty - 36)) | (1L << (Eval - 36)) | (1L << (Exit - 36)) | (1L << (Final - 36)) | (1L << (FloatCast - 36)) | (1L << (Function - 36)) | (1L << (Global - 36)) | (1L << (Import - 36)) | (1L << (Include - 36)) | (1L << (IncludeOnce - 36)) | (1L << (Int16Cast - 36)) | (1L << (Int64Type - 36)) | (1L << (Int8Cast - 36)) | (1L << (Interface - 36)) | (1L << (IntType - 36)) | (1L << (IsSet - 36)) | (1L << (List - 36)) | (1L << (LogicalAnd - 36)) | (1L << (LogicalOr - 36)) | (1L << (LogicalXor - 36)) | (1L << (Namespace - 36)) | (1L << (New - 36)) | (1L << (Null - 36)) | (1L << (ObjectType - 36)) | (1L << (Parent_ - 36)) | (1L << (Partial - 36)) | (1L << (Print - 36)))) != 0) || ((((_la - 100)) & ~0x3f) == 0 && ((1L << (_la - 100)) & ((1L << (Private - 100)) | (1L << (Protected - 100)) | (1L << (Public - 100)) | (1L << (Require - 100)) | (1L << (RequireOnce - 100)) | (1L << (Resource - 100)) | (1L << (Return - 100)) | (1L << (Static - 100)) | (1L << (StringType - 100)) | (1L << (Uint16Cast - 100)) | (1L << (Uint32Cast - 100)) | (1L << (Uint64Cast - 100)) | (1L << (Uint8Cast - 100)) | (1L << (UnicodeCast - 100)) | (1L << (Unset - 100)) | (1L << (Use - 100)) | (1L << (Var - 100)) | (1L << (Yield - 100)) | (1L << (Get - 100)) | (1L << (Set - 100)) | (1L << (Call - 100)) | (1L << (CallStatic - 100)) | (1L << (Constructor - 100)) | (1L << (Destruct - 100)) | (1L << (Wakeup - 100)) | (1L << (Sleep - 100)) | (1L << (Autoload - 100)) | (1L << (IsSet__ - 100)) | (1L << (Unset__ - 100)) | (1L << (ToString__ - 100)) | (1L << (Invoke - 100)) | (1L << (SetState - 100)) | (1L << (Clone__ - 100)) | (1L << (DebugInfo - 100)) | (1L << (Namespace__ - 100)) | (1L << (Class__ - 100)) | (1L << (Traic__ - 100)) | (1L << (Function__ - 100)) | (1L << (Method__ - 100)) | (1L << (Line__ - 100)) | (1L << (File__ - 100)) | (1L << (Dir__ - 100)) | (1L << (Inc - 100)) | (1L << (Dec - 100)))) != 0) || ((((_la - 178)) & ~0x3f) == 0 && ((1L << (_la - 178)) & ((1L << (NamespaceSeparator - 178)) | (1L << (Bang - 178)) | (1L << (Plus - 178)) | (1L << (Minus - 178)) | (1L << (Tilde - 178)) | (1L << (SuppressWarnings - 178)) | (1L << (Dollar - 178)) | (1L << (OpenRoundBracket - 178)) | (1L << (OpenSquareBracket - 178)) | (1L << (Label - 178)) | (1L << (VarName - 178)) | (1L << (Numeric - 178)) | (1L << (Real - 178)) | (1L << (BackQuoteString - 178)) | (1L << (SingleQuoteString - 178)) | (1L << (DoubleQuoteString - 178)) | (1L << (StartNowDoc - 178)) | (1L << (StartHereDoc - 178)))) != 0)) {
				{
				setState(773);
				forInit();
				}
			}

			setState(776);
			match(SemiColon);
			setState(778);
			_la = _input.LA(1);
			if (((((_la - 36)) & ~0x3f) == 0 && ((1L << (_la - 36)) & ((1L << (Abstract - 36)) | (1L << (Array - 36)) | (1L << (BinaryCast - 36)) | (1L << (BoolType - 36)) | (1L << (BooleanConstant - 36)) | (1L << (Break - 36)) | (1L << (Callable - 36)) | (1L << (Case - 36)) | (1L << (Class - 36)) | (1L << (Clone - 36)) | (1L << (Continue - 36)) | (1L << (Default - 36)) | (1L << (DoubleCast - 36)) | (1L << (DoubleType - 36)) | (1L << (Echo - 36)) | (1L << (Empty - 36)) | (1L << (Eval - 36)) | (1L << (Exit - 36)) | (1L << (Final - 36)) | (1L << (FloatCast - 36)) | (1L << (Function - 36)) | (1L << (Global - 36)) | (1L << (Import - 36)) | (1L << (Include - 36)) | (1L << (IncludeOnce - 36)) | (1L << (Int16Cast - 36)) | (1L << (Int64Type - 36)) | (1L << (Int8Cast - 36)) | (1L << (Interface - 36)) | (1L << (IntType - 36)) | (1L << (IsSet - 36)) | (1L << (List - 36)) | (1L << (LogicalAnd - 36)) | (1L << (LogicalOr - 36)) | (1L << (LogicalXor - 36)) | (1L << (Namespace - 36)) | (1L << (New - 36)) | (1L << (Null - 36)) | (1L << (ObjectType - 36)) | (1L << (Parent_ - 36)) | (1L << (Partial - 36)) | (1L << (Print - 36)))) != 0) || ((((_la - 100)) & ~0x3f) == 0 && ((1L << (_la - 100)) & ((1L << (Private - 100)) | (1L << (Protected - 100)) | (1L << (Public - 100)) | (1L << (Require - 100)) | (1L << (RequireOnce - 100)) | (1L << (Resource - 100)) | (1L << (Return - 100)) | (1L << (Static - 100)) | (1L << (StringType - 100)) | (1L << (Uint16Cast - 100)) | (1L << (Uint32Cast - 100)) | (1L << (Uint64Cast - 100)) | (1L << (Uint8Cast - 100)) | (1L << (UnicodeCast - 100)) | (1L << (Unset - 100)) | (1L << (Use - 100)) | (1L << (Var - 100)) | (1L << (Yield - 100)) | (1L << (Get - 100)) | (1L << (Set - 100)) | (1L << (Call - 100)) | (1L << (CallStatic - 100)) | (1L << (Constructor - 100)) | (1L << (Destruct - 100)) | (1L << (Wakeup - 100)) | (1L << (Sleep - 100)) | (1L << (Autoload - 100)) | (1L << (IsSet__ - 100)) | (1L << (Unset__ - 100)) | (1L << (ToString__ - 100)) | (1L << (Invoke - 100)) | (1L << (SetState - 100)) | (1L << (Clone__ - 100)) | (1L << (DebugInfo - 100)) | (1L << (Namespace__ - 100)) | (1L << (Class__ - 100)) | (1L << (Traic__ - 100)) | (1L << (Function__ - 100)) | (1L << (Method__ - 100)) | (1L << (Line__ - 100)) | (1L << (File__ - 100)) | (1L << (Dir__ - 100)) | (1L << (Inc - 100)) | (1L << (Dec - 100)))) != 0) || ((((_la - 178)) & ~0x3f) == 0 && ((1L << (_la - 178)) & ((1L << (NamespaceSeparator - 178)) | (1L << (Bang - 178)) | (1L << (Plus - 178)) | (1L << (Minus - 178)) | (1L << (Tilde - 178)) | (1L << (SuppressWarnings - 178)) | (1L << (Dollar - 178)) | (1L << (OpenRoundBracket - 178)) | (1L << (OpenSquareBracket - 178)) | (1L << (Label - 178)) | (1L << (VarName - 178)) | (1L << (Numeric - 178)) | (1L << (Real - 178)) | (1L << (BackQuoteString - 178)) | (1L << (SingleQuoteString - 178)) | (1L << (DoubleQuoteString - 178)) | (1L << (StartNowDoc - 178)) | (1L << (StartHereDoc - 178)))) != 0)) {
				{
				setState(777);
				expressionList();
				}
			}

			setState(780);
			match(SemiColon);
			setState(782);
			_la = _input.LA(1);
			if (((((_la - 36)) & ~0x3f) == 0 && ((1L << (_la - 36)) & ((1L << (Abstract - 36)) | (1L << (Array - 36)) | (1L << (BinaryCast - 36)) | (1L << (BoolType - 36)) | (1L << (BooleanConstant - 36)) | (1L << (Break - 36)) | (1L << (Callable - 36)) | (1L << (Case - 36)) | (1L << (Class - 36)) | (1L << (Clone - 36)) | (1L << (Continue - 36)) | (1L << (Default - 36)) | (1L << (DoubleCast - 36)) | (1L << (DoubleType - 36)) | (1L << (Echo - 36)) | (1L << (Empty - 36)) | (1L << (Eval - 36)) | (1L << (Exit - 36)) | (1L << (Final - 36)) | (1L << (FloatCast - 36)) | (1L << (Function - 36)) | (1L << (Global - 36)) | (1L << (Import - 36)) | (1L << (Include - 36)) | (1L << (IncludeOnce - 36)) | (1L << (Int16Cast - 36)) | (1L << (Int64Type - 36)) | (1L << (Int8Cast - 36)) | (1L << (Interface - 36)) | (1L << (IntType - 36)) | (1L << (IsSet - 36)) | (1L << (List - 36)) | (1L << (LogicalAnd - 36)) | (1L << (LogicalOr - 36)) | (1L << (LogicalXor - 36)) | (1L << (Namespace - 36)) | (1L << (New - 36)) | (1L << (Null - 36)) | (1L << (ObjectType - 36)) | (1L << (Parent_ - 36)) | (1L << (Partial - 36)) | (1L << (Print - 36)))) != 0) || ((((_la - 100)) & ~0x3f) == 0 && ((1L << (_la - 100)) & ((1L << (Private - 100)) | (1L << (Protected - 100)) | (1L << (Public - 100)) | (1L << (Require - 100)) | (1L << (RequireOnce - 100)) | (1L << (Resource - 100)) | (1L << (Return - 100)) | (1L << (Static - 100)) | (1L << (StringType - 100)) | (1L << (Uint16Cast - 100)) | (1L << (Uint32Cast - 100)) | (1L << (Uint64Cast - 100)) | (1L << (Uint8Cast - 100)) | (1L << (UnicodeCast - 100)) | (1L << (Unset - 100)) | (1L << (Use - 100)) | (1L << (Var - 100)) | (1L << (Yield - 100)) | (1L << (Get - 100)) | (1L << (Set - 100)) | (1L << (Call - 100)) | (1L << (CallStatic - 100)) | (1L << (Constructor - 100)) | (1L << (Destruct - 100)) | (1L << (Wakeup - 100)) | (1L << (Sleep - 100)) | (1L << (Autoload - 100)) | (1L << (IsSet__ - 100)) | (1L << (Unset__ - 100)) | (1L << (ToString__ - 100)) | (1L << (Invoke - 100)) | (1L << (SetState - 100)) | (1L << (Clone__ - 100)) | (1L << (DebugInfo - 100)) | (1L << (Namespace__ - 100)) | (1L << (Class__ - 100)) | (1L << (Traic__ - 100)) | (1L << (Function__ - 100)) | (1L << (Method__ - 100)) | (1L << (Line__ - 100)) | (1L << (File__ - 100)) | (1L << (Dir__ - 100)) | (1L << (Inc - 100)) | (1L << (Dec - 100)))) != 0) || ((((_la - 178)) & ~0x3f) == 0 && ((1L << (_la - 178)) & ((1L << (NamespaceSeparator - 178)) | (1L << (Bang - 178)) | (1L << (Plus - 178)) | (1L << (Minus - 178)) | (1L << (Tilde - 178)) | (1L << (SuppressWarnings - 178)) | (1L << (Dollar - 178)) | (1L << (OpenRoundBracket - 178)) | (1L << (OpenSquareBracket - 178)) | (1L << (Label - 178)) | (1L << (VarName - 178)) | (1L << (Numeric - 178)) | (1L << (Real - 178)) | (1L << (BackQuoteString - 178)) | (1L << (SingleQuoteString - 178)) | (1L << (DoubleQuoteString - 178)) | (1L << (StartNowDoc - 178)) | (1L << (StartHereDoc - 178)))) != 0)) {
				{
				setState(781);
				forUpdate();
				}
			}

			setState(784);
			match(CloseRoundBracket);
			setState(791);
			switch (_input.LA(1)) {
			case HtmlScriptOpen:
			case HtmlStyleOpen:
			case HtmlComment:
			case HtmlDtd:
			case HtmlOpen:
			case Abstract:
			case Array:
			case BinaryCast:
			case BoolType:
			case BooleanConstant:
			case Break:
			case Callable:
			case Case:
			case Class:
			case Clone:
			case Continue:
			case Declare:
			case Default:
			case Do:
			case DoubleCast:
			case DoubleType:
			case Echo:
			case Empty:
			case Eval:
			case Exit:
			case Final:
			case FloatCast:
			case For:
			case Foreach:
			case Function:
			case Global:
			case Goto:
			case If:
			case Import:
			case Include:
			case IncludeOnce:
			case Int16Cast:
			case Int64Type:
			case Int8Cast:
			case Interface:
			case IntType:
			case IsSet:
			case List:
			case LogicalAnd:
			case LogicalOr:
			case LogicalXor:
			case Namespace:
			case New:
			case Null:
			case ObjectType:
			case Parent_:
			case Partial:
			case Print:
			case Private:
			case Protected:
			case Public:
			case Require:
			case RequireOnce:
			case Resource:
			case Return:
			case Static:
			case StringType:
			case Switch:
			case Throw:
			case Try:
			case Uint16Cast:
			case Uint32Cast:
			case Uint64Cast:
			case Uint8Cast:
			case UnicodeCast:
			case Unset:
			case Use:
			case Var:
			case While:
			case Yield:
			case Get:
			case Set:
			case Call:
			case CallStatic:
			case Constructor:
			case Destruct:
			case Wakeup:
			case Sleep:
			case Autoload:
			case IsSet__:
			case Unset__:
			case ToString__:
			case Invoke:
			case SetState:
			case Clone__:
			case DebugInfo:
			case Namespace__:
			case Class__:
			case Traic__:
			case Function__:
			case Method__:
			case Line__:
			case File__:
			case Dir__:
			case Inc:
			case Dec:
			case NamespaceSeparator:
			case Bang:
			case Plus:
			case Minus:
			case Tilde:
			case SuppressWarnings:
			case Dollar:
			case OpenRoundBracket:
			case OpenSquareBracket:
			case OpenCurlyBracket:
			case SemiColon:
			case Label:
			case VarName:
			case Numeric:
			case Real:
			case BackQuoteString:
			case SingleQuoteString:
			case DoubleQuoteString:
			case StartNowDoc:
			case StartHereDoc:
				{
				setState(785);
				statement();
				}
				break;
			case Colon:
				{
				setState(786);
				match(Colon);
				setState(787);
				innerStatementList();
				setState(788);
				match(EndFor);
				setState(789);
				match(SemiColon);
				}
				break;
			default:
				throw new NoViableAltException(this);
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class ForInitContext extends ParserRuleContext {
		public ExpressionListContext expressionList() {
			return getRuleContext(ExpressionListContext.class,0);
		}
		public ForInitContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_forInit; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterForInit(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitForInit(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitForInit(this);
			else return visitor.visitChildren(this);
		}
	}

	public final ForInitContext forInit() throws RecognitionException {
		ForInitContext _localctx = new ForInitContext(_ctx, getState());
		enterRule(_localctx, 94, RULE_forInit);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(793);
			expressionList();
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class ForUpdateContext extends ParserRuleContext {
		public ExpressionListContext expressionList() {
			return getRuleContext(ExpressionListContext.class,0);
		}
		public ForUpdateContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_forUpdate; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterForUpdate(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitForUpdate(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitForUpdate(this);
			else return visitor.visitChildren(this);
		}
	}

	public final ForUpdateContext forUpdate() throws RecognitionException {
		ForUpdateContext _localctx = new ForUpdateContext(_ctx, getState());
		enterRule(_localctx, 96, RULE_forUpdate);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(795);
			expressionList();
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class SwitchStatementContext extends ParserRuleContext {
		public TerminalNode Switch() { return getToken(PHPParser.Switch, 0); }
		public ParenthesisContext parenthesis() {
			return getRuleContext(ParenthesisContext.class,0);
		}
		public TerminalNode EndSwitch() { return getToken(PHPParser.EndSwitch, 0); }
		public List<SwitchBlockContext> switchBlock() {
			return getRuleContexts(SwitchBlockContext.class);
		}
		public SwitchBlockContext switchBlock(int i) {
			return getRuleContext(SwitchBlockContext.class,i);
		}
		public SwitchStatementContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_switchStatement; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterSwitchStatement(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitSwitchStatement(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitSwitchStatement(this);
			else return visitor.visitChildren(this);
		}
	}

	public final SwitchStatementContext switchStatement() throws RecognitionException {
		SwitchStatementContext _localctx = new SwitchStatementContext(_ctx, getState());
		enterRule(_localctx, 98, RULE_switchStatement);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(797);
			match(Switch);
			setState(798);
			parenthesis();
			setState(822);
			switch (_input.LA(1)) {
			case OpenCurlyBracket:
				{
				setState(799);
				match(OpenCurlyBracket);
				setState(801);
				_la = _input.LA(1);
				if (_la==SemiColon) {
					{
					setState(800);
					match(SemiColon);
					}
				}

				setState(806);
				_errHandler.sync(this);
				_la = _input.LA(1);
				while (_la==Case || _la==Default) {
					{
					{
					setState(803);
					switchBlock();
					}
					}
					setState(808);
					_errHandler.sync(this);
					_la = _input.LA(1);
				}
				setState(809);
				match(CloseCurlyBracket);
				}
				break;
			case Colon:
				{
				setState(810);
				match(Colon);
				setState(812);
				_la = _input.LA(1);
				if (_la==SemiColon) {
					{
					setState(811);
					match(SemiColon);
					}
				}

				setState(817);
				_errHandler.sync(this);
				_la = _input.LA(1);
				while (_la==Case || _la==Default) {
					{
					{
					setState(814);
					switchBlock();
					}
					}
					setState(819);
					_errHandler.sync(this);
					_la = _input.LA(1);
				}
				setState(820);
				match(EndSwitch);
				setState(821);
				match(SemiColon);
				}
				break;
			default:
				throw new NoViableAltException(this);
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class SwitchBlockContext extends ParserRuleContext {
		public InnerStatementListContext innerStatementList() {
			return getRuleContext(InnerStatementListContext.class,0);
		}
		public List<TerminalNode> Case() { return getTokens(PHPParser.Case); }
		public TerminalNode Case(int i) {
			return getToken(PHPParser.Case, i);
		}
		public List<ExpressionContext> expression() {
			return getRuleContexts(ExpressionContext.class);
		}
		public ExpressionContext expression(int i) {
			return getRuleContext(ExpressionContext.class,i);
		}
		public List<TerminalNode> Default() { return getTokens(PHPParser.Default); }
		public TerminalNode Default(int i) {
			return getToken(PHPParser.Default, i);
		}
		public SwitchBlockContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_switchBlock; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterSwitchBlock(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitSwitchBlock(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitSwitchBlock(this);
			else return visitor.visitChildren(this);
		}
	}

	public final SwitchBlockContext switchBlock() throws RecognitionException {
		SwitchBlockContext _localctx = new SwitchBlockContext(_ctx, getState());
		enterRule(_localctx, 100, RULE_switchBlock);
		int _la;
		try {
			int _alt;
			enterOuterAlt(_localctx, 1);
			{
			setState(830);
			_errHandler.sync(this);
			_alt = 1;
			do {
				switch (_alt) {
				case 1:
					{
					{
					setState(827);
					switch (_input.LA(1)) {
					case Case:
						{
						setState(824);
						match(Case);
						setState(825);
						expression(0);
						}
						break;
					case Default:
						{
						setState(826);
						match(Default);
						}
						break;
					default:
						throw new NoViableAltException(this);
					}
					setState(829);
					_la = _input.LA(1);
					if ( !(_la==Colon || _la==SemiColon) ) {
					_errHandler.recoverInline(this);
					} else {
						consume();
					}
					}
					}
					break;
				default:
					throw new NoViableAltException(this);
				}
				setState(832);
				_errHandler.sync(this);
				_alt = getInterpreter().adaptivePredict(_input,79,_ctx);
			} while ( _alt!=2 && _alt!= ATN.INVALID_ALT_NUMBER );
			setState(834);
			innerStatementList();
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class BreakStatementContext extends ParserRuleContext {
		public TerminalNode Break() { return getToken(PHPParser.Break, 0); }
		public ExpressionContext expression() {
			return getRuleContext(ExpressionContext.class,0);
		}
		public BreakStatementContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_breakStatement; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterBreakStatement(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitBreakStatement(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitBreakStatement(this);
			else return visitor.visitChildren(this);
		}
	}

	public final BreakStatementContext breakStatement() throws RecognitionException {
		BreakStatementContext _localctx = new BreakStatementContext(_ctx, getState());
		enterRule(_localctx, 102, RULE_breakStatement);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(836);
			match(Break);
			setState(838);
			_la = _input.LA(1);
			if (((((_la - 36)) & ~0x3f) == 0 && ((1L << (_la - 36)) & ((1L << (Abstract - 36)) | (1L << (Array - 36)) | (1L << (BinaryCast - 36)) | (1L << (BoolType - 36)) | (1L << (BooleanConstant - 36)) | (1L << (Break - 36)) | (1L << (Callable - 36)) | (1L << (Case - 36)) | (1L << (Class - 36)) | (1L << (Clone - 36)) | (1L << (Continue - 36)) | (1L << (Default - 36)) | (1L << (DoubleCast - 36)) | (1L << (DoubleType - 36)) | (1L << (Echo - 36)) | (1L << (Empty - 36)) | (1L << (Eval - 36)) | (1L << (Exit - 36)) | (1L << (Final - 36)) | (1L << (FloatCast - 36)) | (1L << (Function - 36)) | (1L << (Global - 36)) | (1L << (Import - 36)) | (1L << (Include - 36)) | (1L << (IncludeOnce - 36)) | (1L << (Int16Cast - 36)) | (1L << (Int64Type - 36)) | (1L << (Int8Cast - 36)) | (1L << (Interface - 36)) | (1L << (IntType - 36)) | (1L << (IsSet - 36)) | (1L << (List - 36)) | (1L << (LogicalAnd - 36)) | (1L << (LogicalOr - 36)) | (1L << (LogicalXor - 36)) | (1L << (Namespace - 36)) | (1L << (New - 36)) | (1L << (Null - 36)) | (1L << (ObjectType - 36)) | (1L << (Parent_ - 36)) | (1L << (Partial - 36)) | (1L << (Print - 36)))) != 0) || ((((_la - 100)) & ~0x3f) == 0 && ((1L << (_la - 100)) & ((1L << (Private - 100)) | (1L << (Protected - 100)) | (1L << (Public - 100)) | (1L << (Require - 100)) | (1L << (RequireOnce - 100)) | (1L << (Resource - 100)) | (1L << (Return - 100)) | (1L << (Static - 100)) | (1L << (StringType - 100)) | (1L << (Uint16Cast - 100)) | (1L << (Uint32Cast - 100)) | (1L << (Uint64Cast - 100)) | (1L << (Uint8Cast - 100)) | (1L << (UnicodeCast - 100)) | (1L << (Unset - 100)) | (1L << (Use - 100)) | (1L << (Var - 100)) | (1L << (Yield - 100)) | (1L << (Get - 100)) | (1L << (Set - 100)) | (1L << (Call - 100)) | (1L << (CallStatic - 100)) | (1L << (Constructor - 100)) | (1L << (Destruct - 100)) | (1L << (Wakeup - 100)) | (1L << (Sleep - 100)) | (1L << (Autoload - 100)) | (1L << (IsSet__ - 100)) | (1L << (Unset__ - 100)) | (1L << (ToString__ - 100)) | (1L << (Invoke - 100)) | (1L << (SetState - 100)) | (1L << (Clone__ - 100)) | (1L << (DebugInfo - 100)) | (1L << (Namespace__ - 100)) | (1L << (Class__ - 100)) | (1L << (Traic__ - 100)) | (1L << (Function__ - 100)) | (1L << (Method__ - 100)) | (1L << (Line__ - 100)) | (1L << (File__ - 100)) | (1L << (Dir__ - 100)) | (1L << (Inc - 100)) | (1L << (Dec - 100)))) != 0) || ((((_la - 178)) & ~0x3f) == 0 && ((1L << (_la - 178)) & ((1L << (NamespaceSeparator - 178)) | (1L << (Bang - 178)) | (1L << (Plus - 178)) | (1L << (Minus - 178)) | (1L << (Tilde - 178)) | (1L << (SuppressWarnings - 178)) | (1L << (Dollar - 178)) | (1L << (OpenRoundBracket - 178)) | (1L << (OpenSquareBracket - 178)) | (1L << (Label - 178)) | (1L << (VarName - 178)) | (1L << (Numeric - 178)) | (1L << (Real - 178)) | (1L << (BackQuoteString - 178)) | (1L << (SingleQuoteString - 178)) | (1L << (DoubleQuoteString - 178)) | (1L << (StartNowDoc - 178)) | (1L << (StartHereDoc - 178)))) != 0)) {
				{
				setState(837);
				expression(0);
				}
			}

			setState(840);
			match(SemiColon);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class ContinueStatementContext extends ParserRuleContext {
		public TerminalNode Continue() { return getToken(PHPParser.Continue, 0); }
		public ExpressionContext expression() {
			return getRuleContext(ExpressionContext.class,0);
		}
		public ContinueStatementContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_continueStatement; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterContinueStatement(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitContinueStatement(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitContinueStatement(this);
			else return visitor.visitChildren(this);
		}
	}

	public final ContinueStatementContext continueStatement() throws RecognitionException {
		ContinueStatementContext _localctx = new ContinueStatementContext(_ctx, getState());
		enterRule(_localctx, 104, RULE_continueStatement);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(842);
			match(Continue);
			setState(844);
			_la = _input.LA(1);
			if (((((_la - 36)) & ~0x3f) == 0 && ((1L << (_la - 36)) & ((1L << (Abstract - 36)) | (1L << (Array - 36)) | (1L << (BinaryCast - 36)) | (1L << (BoolType - 36)) | (1L << (BooleanConstant - 36)) | (1L << (Break - 36)) | (1L << (Callable - 36)) | (1L << (Case - 36)) | (1L << (Class - 36)) | (1L << (Clone - 36)) | (1L << (Continue - 36)) | (1L << (Default - 36)) | (1L << (DoubleCast - 36)) | (1L << (DoubleType - 36)) | (1L << (Echo - 36)) | (1L << (Empty - 36)) | (1L << (Eval - 36)) | (1L << (Exit - 36)) | (1L << (Final - 36)) | (1L << (FloatCast - 36)) | (1L << (Function - 36)) | (1L << (Global - 36)) | (1L << (Import - 36)) | (1L << (Include - 36)) | (1L << (IncludeOnce - 36)) | (1L << (Int16Cast - 36)) | (1L << (Int64Type - 36)) | (1L << (Int8Cast - 36)) | (1L << (Interface - 36)) | (1L << (IntType - 36)) | (1L << (IsSet - 36)) | (1L << (List - 36)) | (1L << (LogicalAnd - 36)) | (1L << (LogicalOr - 36)) | (1L << (LogicalXor - 36)) | (1L << (Namespace - 36)) | (1L << (New - 36)) | (1L << (Null - 36)) | (1L << (ObjectType - 36)) | (1L << (Parent_ - 36)) | (1L << (Partial - 36)) | (1L << (Print - 36)))) != 0) || ((((_la - 100)) & ~0x3f) == 0 && ((1L << (_la - 100)) & ((1L << (Private - 100)) | (1L << (Protected - 100)) | (1L << (Public - 100)) | (1L << (Require - 100)) | (1L << (RequireOnce - 100)) | (1L << (Resource - 100)) | (1L << (Return - 100)) | (1L << (Static - 100)) | (1L << (StringType - 100)) | (1L << (Uint16Cast - 100)) | (1L << (Uint32Cast - 100)) | (1L << (Uint64Cast - 100)) | (1L << (Uint8Cast - 100)) | (1L << (UnicodeCast - 100)) | (1L << (Unset - 100)) | (1L << (Use - 100)) | (1L << (Var - 100)) | (1L << (Yield - 100)) | (1L << (Get - 100)) | (1L << (Set - 100)) | (1L << (Call - 100)) | (1L << (CallStatic - 100)) | (1L << (Constructor - 100)) | (1L << (Destruct - 100)) | (1L << (Wakeup - 100)) | (1L << (Sleep - 100)) | (1L << (Autoload - 100)) | (1L << (IsSet__ - 100)) | (1L << (Unset__ - 100)) | (1L << (ToString__ - 100)) | (1L << (Invoke - 100)) | (1L << (SetState - 100)) | (1L << (Clone__ - 100)) | (1L << (DebugInfo - 100)) | (1L << (Namespace__ - 100)) | (1L << (Class__ - 100)) | (1L << (Traic__ - 100)) | (1L << (Function__ - 100)) | (1L << (Method__ - 100)) | (1L << (Line__ - 100)) | (1L << (File__ - 100)) | (1L << (Dir__ - 100)) | (1L << (Inc - 100)) | (1L << (Dec - 100)))) != 0) || ((((_la - 178)) & ~0x3f) == 0 && ((1L << (_la - 178)) & ((1L << (NamespaceSeparator - 178)) | (1L << (Bang - 178)) | (1L << (Plus - 178)) | (1L << (Minus - 178)) | (1L << (Tilde - 178)) | (1L << (SuppressWarnings - 178)) | (1L << (Dollar - 178)) | (1L << (OpenRoundBracket - 178)) | (1L << (OpenSquareBracket - 178)) | (1L << (Label - 178)) | (1L << (VarName - 178)) | (1L << (Numeric - 178)) | (1L << (Real - 178)) | (1L << (BackQuoteString - 178)) | (1L << (SingleQuoteString - 178)) | (1L << (DoubleQuoteString - 178)) | (1L << (StartNowDoc - 178)) | (1L << (StartHereDoc - 178)))) != 0)) {
				{
				setState(843);
				expression(0);
				}
			}

			setState(846);
			match(SemiColon);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class ReturnStatementContext extends ParserRuleContext {
		public TerminalNode Return() { return getToken(PHPParser.Return, 0); }
		public ExpressionContext expression() {
			return getRuleContext(ExpressionContext.class,0);
		}
		public ReturnStatementContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_returnStatement; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterReturnStatement(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitReturnStatement(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitReturnStatement(this);
			else return visitor.visitChildren(this);
		}
	}

	public final ReturnStatementContext returnStatement() throws RecognitionException {
		ReturnStatementContext _localctx = new ReturnStatementContext(_ctx, getState());
		enterRule(_localctx, 106, RULE_returnStatement);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(848);
			match(Return);
			setState(850);
			_la = _input.LA(1);
			if (((((_la - 36)) & ~0x3f) == 0 && ((1L << (_la - 36)) & ((1L << (Abstract - 36)) | (1L << (Array - 36)) | (1L << (BinaryCast - 36)) | (1L << (BoolType - 36)) | (1L << (BooleanConstant - 36)) | (1L << (Break - 36)) | (1L << (Callable - 36)) | (1L << (Case - 36)) | (1L << (Class - 36)) | (1L << (Clone - 36)) | (1L << (Continue - 36)) | (1L << (Default - 36)) | (1L << (DoubleCast - 36)) | (1L << (DoubleType - 36)) | (1L << (Echo - 36)) | (1L << (Empty - 36)) | (1L << (Eval - 36)) | (1L << (Exit - 36)) | (1L << (Final - 36)) | (1L << (FloatCast - 36)) | (1L << (Function - 36)) | (1L << (Global - 36)) | (1L << (Import - 36)) | (1L << (Include - 36)) | (1L << (IncludeOnce - 36)) | (1L << (Int16Cast - 36)) | (1L << (Int64Type - 36)) | (1L << (Int8Cast - 36)) | (1L << (Interface - 36)) | (1L << (IntType - 36)) | (1L << (IsSet - 36)) | (1L << (List - 36)) | (1L << (LogicalAnd - 36)) | (1L << (LogicalOr - 36)) | (1L << (LogicalXor - 36)) | (1L << (Namespace - 36)) | (1L << (New - 36)) | (1L << (Null - 36)) | (1L << (ObjectType - 36)) | (1L << (Parent_ - 36)) | (1L << (Partial - 36)) | (1L << (Print - 36)))) != 0) || ((((_la - 100)) & ~0x3f) == 0 && ((1L << (_la - 100)) & ((1L << (Private - 100)) | (1L << (Protected - 100)) | (1L << (Public - 100)) | (1L << (Require - 100)) | (1L << (RequireOnce - 100)) | (1L << (Resource - 100)) | (1L << (Return - 100)) | (1L << (Static - 100)) | (1L << (StringType - 100)) | (1L << (Uint16Cast - 100)) | (1L << (Uint32Cast - 100)) | (1L << (Uint64Cast - 100)) | (1L << (Uint8Cast - 100)) | (1L << (UnicodeCast - 100)) | (1L << (Unset - 100)) | (1L << (Use - 100)) | (1L << (Var - 100)) | (1L << (Yield - 100)) | (1L << (Get - 100)) | (1L << (Set - 100)) | (1L << (Call - 100)) | (1L << (CallStatic - 100)) | (1L << (Constructor - 100)) | (1L << (Destruct - 100)) | (1L << (Wakeup - 100)) | (1L << (Sleep - 100)) | (1L << (Autoload - 100)) | (1L << (IsSet__ - 100)) | (1L << (Unset__ - 100)) | (1L << (ToString__ - 100)) | (1L << (Invoke - 100)) | (1L << (SetState - 100)) | (1L << (Clone__ - 100)) | (1L << (DebugInfo - 100)) | (1L << (Namespace__ - 100)) | (1L << (Class__ - 100)) | (1L << (Traic__ - 100)) | (1L << (Function__ - 100)) | (1L << (Method__ - 100)) | (1L << (Line__ - 100)) | (1L << (File__ - 100)) | (1L << (Dir__ - 100)) | (1L << (Inc - 100)) | (1L << (Dec - 100)))) != 0) || ((((_la - 178)) & ~0x3f) == 0 && ((1L << (_la - 178)) & ((1L << (NamespaceSeparator - 178)) | (1L << (Bang - 178)) | (1L << (Plus - 178)) | (1L << (Minus - 178)) | (1L << (Tilde - 178)) | (1L << (SuppressWarnings - 178)) | (1L << (Dollar - 178)) | (1L << (OpenRoundBracket - 178)) | (1L << (OpenSquareBracket - 178)) | (1L << (Label - 178)) | (1L << (VarName - 178)) | (1L << (Numeric - 178)) | (1L << (Real - 178)) | (1L << (BackQuoteString - 178)) | (1L << (SingleQuoteString - 178)) | (1L << (DoubleQuoteString - 178)) | (1L << (StartNowDoc - 178)) | (1L << (StartHereDoc - 178)))) != 0)) {
				{
				setState(849);
				expression(0);
				}
			}

			setState(852);
			match(SemiColon);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class ExpressionStatementContext extends ParserRuleContext {
		public ExpressionContext expression() {
			return getRuleContext(ExpressionContext.class,0);
		}
		public ExpressionStatementContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_expressionStatement; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterExpressionStatement(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitExpressionStatement(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitExpressionStatement(this);
			else return visitor.visitChildren(this);
		}
	}

	public final ExpressionStatementContext expressionStatement() throws RecognitionException {
		ExpressionStatementContext _localctx = new ExpressionStatementContext(_ctx, getState());
		enterRule(_localctx, 108, RULE_expressionStatement);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(854);
			expression(0);
			setState(855);
			match(SemiColon);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class UnsetStatementContext extends ParserRuleContext {
		public TerminalNode Unset() { return getToken(PHPParser.Unset, 0); }
		public ChainListContext chainList() {
			return getRuleContext(ChainListContext.class,0);
		}
		public UnsetStatementContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_unsetStatement; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterUnsetStatement(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitUnsetStatement(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitUnsetStatement(this);
			else return visitor.visitChildren(this);
		}
	}

	public final UnsetStatementContext unsetStatement() throws RecognitionException {
		UnsetStatementContext _localctx = new UnsetStatementContext(_ctx, getState());
		enterRule(_localctx, 110, RULE_unsetStatement);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(857);
			match(Unset);
			setState(858);
			match(OpenRoundBracket);
			setState(859);
			chainList();
			setState(860);
			match(CloseRoundBracket);
			setState(861);
			match(SemiColon);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class ForeachStatementContext extends ParserRuleContext {
		public TerminalNode Foreach() { return getToken(PHPParser.Foreach, 0); }
		public List<ChainContext> chain() {
			return getRuleContexts(ChainContext.class);
		}
		public ChainContext chain(int i) {
			return getRuleContext(ChainContext.class,i);
		}
		public TerminalNode As() { return getToken(PHPParser.As, 0); }
		public ExpressionContext expression() {
			return getRuleContext(ExpressionContext.class,0);
		}
		public TerminalNode List() { return getToken(PHPParser.List, 0); }
		public AssignmentListContext assignmentList() {
			return getRuleContext(AssignmentListContext.class,0);
		}
		public StatementContext statement() {
			return getRuleContext(StatementContext.class,0);
		}
		public InnerStatementListContext innerStatementList() {
			return getRuleContext(InnerStatementListContext.class,0);
		}
		public TerminalNode EndForeach() { return getToken(PHPParser.EndForeach, 0); }
		public ForeachStatementContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_foreachStatement; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterForeachStatement(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitForeachStatement(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitForeachStatement(this);
			else return visitor.visitChildren(this);
		}
	}

	public final ForeachStatementContext foreachStatement() throws RecognitionException {
		ForeachStatementContext _localctx = new ForeachStatementContext(_ctx, getState());
		enterRule(_localctx, 112, RULE_foreachStatement);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(863);
			match(Foreach);
			setState(902);
			_errHandler.sync(this);
			switch ( getInterpreter().adaptivePredict(_input,88,_ctx) ) {
			case 1:
				{
				setState(864);
				match(OpenRoundBracket);
				setState(865);
				chain();
				setState(866);
				match(As);
				setState(868);
				_la = _input.LA(1);
				if (_la==Ampersand) {
					{
					setState(867);
					match(Ampersand);
					}
				}

				setState(870);
				chain();
				setState(876);
				_la = _input.LA(1);
				if (_la==DoubleArrow) {
					{
					setState(871);
					match(DoubleArrow);
					setState(873);
					_la = _input.LA(1);
					if (_la==Ampersand) {
						{
						setState(872);
						match(Ampersand);
						}
					}

					setState(875);
					chain();
					}
				}

				setState(878);
				match(CloseRoundBracket);
				}
				break;
			case 2:
				{
				setState(880);
				match(OpenRoundBracket);
				setState(881);
				expression(0);
				setState(882);
				match(As);
				setState(883);
				chain();
				setState(889);
				_la = _input.LA(1);
				if (_la==DoubleArrow) {
					{
					setState(884);
					match(DoubleArrow);
					setState(886);
					_la = _input.LA(1);
					if (_la==Ampersand) {
						{
						setState(885);
						match(Ampersand);
						}
					}

					setState(888);
					chain();
					}
				}

				setState(891);
				match(CloseRoundBracket);
				}
				break;
			case 3:
				{
				setState(893);
				match(OpenRoundBracket);
				setState(894);
				chain();
				setState(895);
				match(As);
				setState(896);
				match(List);
				setState(897);
				match(OpenRoundBracket);
				setState(898);
				assignmentList();
				setState(899);
				match(CloseRoundBracket);
				setState(900);
				match(CloseRoundBracket);
				}
				break;
			}
			setState(910);
			switch (_input.LA(1)) {
			case HtmlScriptOpen:
			case HtmlStyleOpen:
			case HtmlComment:
			case HtmlDtd:
			case HtmlOpen:
			case Abstract:
			case Array:
			case BinaryCast:
			case BoolType:
			case BooleanConstant:
			case Break:
			case Callable:
			case Case:
			case Class:
			case Clone:
			case Continue:
			case Declare:
			case Default:
			case Do:
			case DoubleCast:
			case DoubleType:
			case Echo:
			case Empty:
			case Eval:
			case Exit:
			case Final:
			case FloatCast:
			case For:
			case Foreach:
			case Function:
			case Global:
			case Goto:
			case If:
			case Import:
			case Include:
			case IncludeOnce:
			case Int16Cast:
			case Int64Type:
			case Int8Cast:
			case Interface:
			case IntType:
			case IsSet:
			case List:
			case LogicalAnd:
			case LogicalOr:
			case LogicalXor:
			case Namespace:
			case New:
			case Null:
			case ObjectType:
			case Parent_:
			case Partial:
			case Print:
			case Private:
			case Protected:
			case Public:
			case Require:
			case RequireOnce:
			case Resource:
			case Return:
			case Static:
			case StringType:
			case Switch:
			case Throw:
			case Try:
			case Uint16Cast:
			case Uint32Cast:
			case Uint64Cast:
			case Uint8Cast:
			case UnicodeCast:
			case Unset:
			case Use:
			case Var:
			case While:
			case Yield:
			case Get:
			case Set:
			case Call:
			case CallStatic:
			case Constructor:
			case Destruct:
			case Wakeup:
			case Sleep:
			case Autoload:
			case IsSet__:
			case Unset__:
			case ToString__:
			case Invoke:
			case SetState:
			case Clone__:
			case DebugInfo:
			case Namespace__:
			case Class__:
			case Traic__:
			case Function__:
			case Method__:
			case Line__:
			case File__:
			case Dir__:
			case Inc:
			case Dec:
			case NamespaceSeparator:
			case Bang:
			case Plus:
			case Minus:
			case Tilde:
			case SuppressWarnings:
			case Dollar:
			case OpenRoundBracket:
			case OpenSquareBracket:
			case OpenCurlyBracket:
			case SemiColon:
			case Label:
			case VarName:
			case Numeric:
			case Real:
			case BackQuoteString:
			case SingleQuoteString:
			case DoubleQuoteString:
			case StartNowDoc:
			case StartHereDoc:
				{
				setState(904);
				statement();
				}
				break;
			case Colon:
				{
				setState(905);
				match(Colon);
				setState(906);
				innerStatementList();
				setState(907);
				match(EndForeach);
				setState(908);
				match(SemiColon);
				}
				break;
			default:
				throw new NoViableAltException(this);
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class TryCatchFinallyContext extends ParserRuleContext {
		public TerminalNode Try() { return getToken(PHPParser.Try, 0); }
		public BlockStatementContext blockStatement() {
			return getRuleContext(BlockStatementContext.class,0);
		}
		public FinallyStatementContext finallyStatement() {
			return getRuleContext(FinallyStatementContext.class,0);
		}
		public List<CatchClauseContext> catchClause() {
			return getRuleContexts(CatchClauseContext.class);
		}
		public CatchClauseContext catchClause(int i) {
			return getRuleContext(CatchClauseContext.class,i);
		}
		public TryCatchFinallyContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_tryCatchFinally; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterTryCatchFinally(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitTryCatchFinally(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitTryCatchFinally(this);
			else return visitor.visitChildren(this);
		}
	}

	public final TryCatchFinallyContext tryCatchFinally() throws RecognitionException {
		TryCatchFinallyContext _localctx = new TryCatchFinallyContext(_ctx, getState());
		enterRule(_localctx, 114, RULE_tryCatchFinally);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(912);
			match(Try);
			setState(913);
			blockStatement();
			setState(929);
			_errHandler.sync(this);
			switch ( getInterpreter().adaptivePredict(_input,93,_ctx) ) {
			case 1:
				{
				setState(915);
				_errHandler.sync(this);
				_la = _input.LA(1);
				do {
					{
					{
					setState(914);
					catchClause();
					}
					}
					setState(917);
					_errHandler.sync(this);
					_la = _input.LA(1);
				} while ( _la==Catch );
				setState(920);
				_la = _input.LA(1);
				if (_la==Finally) {
					{
					setState(919);
					finallyStatement();
					}
				}

				}
				break;
			case 2:
				{
				setState(925);
				_errHandler.sync(this);
				_la = _input.LA(1);
				while (_la==Catch) {
					{
					{
					setState(922);
					catchClause();
					}
					}
					setState(927);
					_errHandler.sync(this);
					_la = _input.LA(1);
				}
				setState(928);
				finallyStatement();
				}
				break;
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class CatchClauseContext extends ParserRuleContext {
		public TerminalNode Catch() { return getToken(PHPParser.Catch, 0); }
		public QualifiedStaticTypeRefContext qualifiedStaticTypeRef() {
			return getRuleContext(QualifiedStaticTypeRefContext.class,0);
		}
		public TerminalNode VarName() { return getToken(PHPParser.VarName, 0); }
		public BlockStatementContext blockStatement() {
			return getRuleContext(BlockStatementContext.class,0);
		}
		public CatchClauseContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_catchClause; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterCatchClause(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitCatchClause(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitCatchClause(this);
			else return visitor.visitChildren(this);
		}
	}

	public final CatchClauseContext catchClause() throws RecognitionException {
		CatchClauseContext _localctx = new CatchClauseContext(_ctx, getState());
		enterRule(_localctx, 116, RULE_catchClause);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(931);
			match(Catch);
			setState(932);
			match(OpenRoundBracket);
			setState(933);
			qualifiedStaticTypeRef();
			setState(934);
			match(VarName);
			setState(935);
			match(CloseRoundBracket);
			setState(936);
			blockStatement();
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class FinallyStatementContext extends ParserRuleContext {
		public TerminalNode Finally() { return getToken(PHPParser.Finally, 0); }
		public BlockStatementContext blockStatement() {
			return getRuleContext(BlockStatementContext.class,0);
		}
		public FinallyStatementContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_finallyStatement; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterFinallyStatement(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitFinallyStatement(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitFinallyStatement(this);
			else return visitor.visitChildren(this);
		}
	}

	public final FinallyStatementContext finallyStatement() throws RecognitionException {
		FinallyStatementContext _localctx = new FinallyStatementContext(_ctx, getState());
		enterRule(_localctx, 118, RULE_finallyStatement);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(938);
			match(Finally);
			setState(939);
			blockStatement();
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class ThrowStatementContext extends ParserRuleContext {
		public TerminalNode Throw() { return getToken(PHPParser.Throw, 0); }
		public ExpressionContext expression() {
			return getRuleContext(ExpressionContext.class,0);
		}
		public ThrowStatementContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_throwStatement; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterThrowStatement(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitThrowStatement(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitThrowStatement(this);
			else return visitor.visitChildren(this);
		}
	}

	public final ThrowStatementContext throwStatement() throws RecognitionException {
		ThrowStatementContext _localctx = new ThrowStatementContext(_ctx, getState());
		enterRule(_localctx, 120, RULE_throwStatement);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(941);
			match(Throw);
			setState(942);
			expression(0);
			setState(943);
			match(SemiColon);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class GotoStatementContext extends ParserRuleContext {
		public TerminalNode Goto() { return getToken(PHPParser.Goto, 0); }
		public IdentifierContext identifier() {
			return getRuleContext(IdentifierContext.class,0);
		}
		public GotoStatementContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_gotoStatement; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterGotoStatement(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitGotoStatement(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitGotoStatement(this);
			else return visitor.visitChildren(this);
		}
	}

	public final GotoStatementContext gotoStatement() throws RecognitionException {
		GotoStatementContext _localctx = new GotoStatementContext(_ctx, getState());
		enterRule(_localctx, 122, RULE_gotoStatement);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(945);
			match(Goto);
			setState(946);
			identifier();
			setState(947);
			match(SemiColon);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class DeclareStatementContext extends ParserRuleContext {
		public TerminalNode Declare() { return getToken(PHPParser.Declare, 0); }
		public DeclareListContext declareList() {
			return getRuleContext(DeclareListContext.class,0);
		}
		public StatementContext statement() {
			return getRuleContext(StatementContext.class,0);
		}
		public InnerStatementListContext innerStatementList() {
			return getRuleContext(InnerStatementListContext.class,0);
		}
		public TerminalNode EndDeclare() { return getToken(PHPParser.EndDeclare, 0); }
		public DeclareStatementContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_declareStatement; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterDeclareStatement(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitDeclareStatement(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitDeclareStatement(this);
			else return visitor.visitChildren(this);
		}
	}

	public final DeclareStatementContext declareStatement() throws RecognitionException {
		DeclareStatementContext _localctx = new DeclareStatementContext(_ctx, getState());
		enterRule(_localctx, 124, RULE_declareStatement);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(949);
			match(Declare);
			setState(950);
			match(OpenRoundBracket);
			setState(951);
			declareList();
			setState(952);
			match(CloseRoundBracket);
			setState(959);
			switch (_input.LA(1)) {
			case HtmlScriptOpen:
			case HtmlStyleOpen:
			case HtmlComment:
			case HtmlDtd:
			case HtmlOpen:
			case Abstract:
			case Array:
			case BinaryCast:
			case BoolType:
			case BooleanConstant:
			case Break:
			case Callable:
			case Case:
			case Class:
			case Clone:
			case Continue:
			case Declare:
			case Default:
			case Do:
			case DoubleCast:
			case DoubleType:
			case Echo:
			case Empty:
			case Eval:
			case Exit:
			case Final:
			case FloatCast:
			case For:
			case Foreach:
			case Function:
			case Global:
			case Goto:
			case If:
			case Import:
			case Include:
			case IncludeOnce:
			case Int16Cast:
			case Int64Type:
			case Int8Cast:
			case Interface:
			case IntType:
			case IsSet:
			case List:
			case LogicalAnd:
			case LogicalOr:
			case LogicalXor:
			case Namespace:
			case New:
			case Null:
			case ObjectType:
			case Parent_:
			case Partial:
			case Print:
			case Private:
			case Protected:
			case Public:
			case Require:
			case RequireOnce:
			case Resource:
			case Return:
			case Static:
			case StringType:
			case Switch:
			case Throw:
			case Try:
			case Uint16Cast:
			case Uint32Cast:
			case Uint64Cast:
			case Uint8Cast:
			case UnicodeCast:
			case Unset:
			case Use:
			case Var:
			case While:
			case Yield:
			case Get:
			case Set:
			case Call:
			case CallStatic:
			case Constructor:
			case Destruct:
			case Wakeup:
			case Sleep:
			case Autoload:
			case IsSet__:
			case Unset__:
			case ToString__:
			case Invoke:
			case SetState:
			case Clone__:
			case DebugInfo:
			case Namespace__:
			case Class__:
			case Traic__:
			case Function__:
			case Method__:
			case Line__:
			case File__:
			case Dir__:
			case Inc:
			case Dec:
			case NamespaceSeparator:
			case Bang:
			case Plus:
			case Minus:
			case Tilde:
			case SuppressWarnings:
			case Dollar:
			case OpenRoundBracket:
			case OpenSquareBracket:
			case OpenCurlyBracket:
			case SemiColon:
			case Label:
			case VarName:
			case Numeric:
			case Real:
			case BackQuoteString:
			case SingleQuoteString:
			case DoubleQuoteString:
			case StartNowDoc:
			case StartHereDoc:
				{
				setState(953);
				statement();
				}
				break;
			case Colon:
				{
				setState(954);
				match(Colon);
				setState(955);
				innerStatementList();
				setState(956);
				match(EndDeclare);
				setState(957);
				match(SemiColon);
				}
				break;
			default:
				throw new NoViableAltException(this);
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class InlineHtmlContext extends ParserRuleContext {
		public List<TerminalNode> HtmlComment() { return getTokens(PHPParser.HtmlComment); }
		public TerminalNode HtmlComment(int i) {
			return getToken(PHPParser.HtmlComment, i);
		}
		public List<TerminalNode> HtmlDtd() { return getTokens(PHPParser.HtmlDtd); }
		public TerminalNode HtmlDtd(int i) {
			return getToken(PHPParser.HtmlDtd, i);
		}
		public List<HtmlElementContext> htmlElement() {
			return getRuleContexts(HtmlElementContext.class);
		}
		public HtmlElementContext htmlElement(int i) {
			return getRuleContext(HtmlElementContext.class,i);
		}
		public InlineHtmlContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_inlineHtml; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterInlineHtml(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitInlineHtml(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitInlineHtml(this);
			else return visitor.visitChildren(this);
		}
	}

	public final InlineHtmlContext inlineHtml() throws RecognitionException {
		InlineHtmlContext _localctx = new InlineHtmlContext(_ctx, getState());
		enterRule(_localctx, 126, RULE_inlineHtml);
		int _la;
		try {
			int _alt;
			enterOuterAlt(_localctx, 1);
			{
			setState(964);
			_errHandler.sync(this);
			_la = _input.LA(1);
			while (_la==HtmlComment) {
				{
				{
				setState(961);
				match(HtmlComment);
				}
				}
				setState(966);
				_errHandler.sync(this);
				_la = _input.LA(1);
			}
			setState(977);
			_errHandler.sync(this);
			_alt = 1;
			do {
				switch (_alt) {
				case 1:
					{
					{
					setState(969);
					switch (_input.LA(1)) {
					case HtmlDtd:
						{
						setState(967);
						match(HtmlDtd);
						}
						break;
					case HtmlScriptOpen:
					case HtmlStyleOpen:
					case HtmlOpen:
						{
						setState(968);
						htmlElement();
						}
						break;
					default:
						throw new NoViableAltException(this);
					}
					setState(974);
					_errHandler.sync(this);
					_alt = getInterpreter().adaptivePredict(_input,97,_ctx);
					while ( _alt!=2 && _alt!= ATN.INVALID_ALT_NUMBER ) {
						if ( _alt==1 ) {
							{
							{
							setState(971);
							match(HtmlComment);
							}
							}
						}
						setState(976);
						_errHandler.sync(this);
						_alt = getInterpreter().adaptivePredict(_input,97,_ctx);
					}
					}
					}
					break;
				default:
					throw new NoViableAltException(this);
				}
				setState(979);
				_errHandler.sync(this);
				_alt = getInterpreter().adaptivePredict(_input,98,_ctx);
			} while ( _alt!=2 && _alt!= ATN.INVALID_ALT_NUMBER );
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class DeclareListContext extends ParserRuleContext {
		public List<IdentifierInititalizerContext> identifierInititalizer() {
			return getRuleContexts(IdentifierInititalizerContext.class);
		}
		public IdentifierInititalizerContext identifierInititalizer(int i) {
			return getRuleContext(IdentifierInititalizerContext.class,i);
		}
		public DeclareListContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_declareList; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterDeclareList(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitDeclareList(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitDeclareList(this);
			else return visitor.visitChildren(this);
		}
	}

	public final DeclareListContext declareList() throws RecognitionException {
		DeclareListContext _localctx = new DeclareListContext(_ctx, getState());
		enterRule(_localctx, 128, RULE_declareList);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(981);
			identifierInititalizer();
			setState(986);
			_errHandler.sync(this);
			_la = _input.LA(1);
			while (_la==Comma) {
				{
				{
				setState(982);
				match(Comma);
				setState(983);
				identifierInititalizer();
				}
				}
				setState(988);
				_errHandler.sync(this);
				_la = _input.LA(1);
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class FormalParameterListContext extends ParserRuleContext {
		public List<FormalParameterContext> formalParameter() {
			return getRuleContexts(FormalParameterContext.class);
		}
		public FormalParameterContext formalParameter(int i) {
			return getRuleContext(FormalParameterContext.class,i);
		}
		public FormalParameterListContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_formalParameterList; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterFormalParameterList(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitFormalParameterList(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitFormalParameterList(this);
			else return visitor.visitChildren(this);
		}
	}

	public final FormalParameterListContext formalParameterList() throws RecognitionException {
		FormalParameterListContext _localctx = new FormalParameterListContext(_ctx, getState());
		enterRule(_localctx, 130, RULE_formalParameterList);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(990);
			_la = _input.LA(1);
			if (((((_la - 36)) & ~0x3f) == 0 && ((1L << (_la - 36)) & ((1L << (Abstract - 36)) | (1L << (Array - 36)) | (1L << (BinaryCast - 36)) | (1L << (BoolType - 36)) | (1L << (Break - 36)) | (1L << (Callable - 36)) | (1L << (Case - 36)) | (1L << (Class - 36)) | (1L << (Clone - 36)) | (1L << (Continue - 36)) | (1L << (Default - 36)) | (1L << (DoubleCast - 36)) | (1L << (DoubleType - 36)) | (1L << (Echo - 36)) | (1L << (Empty - 36)) | (1L << (Eval - 36)) | (1L << (Final - 36)) | (1L << (FloatCast - 36)) | (1L << (Function - 36)) | (1L << (Global - 36)) | (1L << (Import - 36)) | (1L << (Int16Cast - 36)) | (1L << (Int64Type - 36)) | (1L << (Int8Cast - 36)) | (1L << (Interface - 36)) | (1L << (IntType - 36)) | (1L << (IsSet - 36)) | (1L << (List - 36)) | (1L << (LogicalAnd - 36)) | (1L << (LogicalOr - 36)) | (1L << (LogicalXor - 36)) | (1L << (Namespace - 36)) | (1L << (New - 36)) | (1L << (Null - 36)) | (1L << (ObjectType - 36)) | (1L << (Parent_ - 36)) | (1L << (Partial - 36)))) != 0) || ((((_la - 100)) & ~0x3f) == 0 && ((1L << (_la - 100)) & ((1L << (Private - 100)) | (1L << (Protected - 100)) | (1L << (Public - 100)) | (1L << (Resource - 100)) | (1L << (Return - 100)) | (1L << (Static - 100)) | (1L << (StringType - 100)) | (1L << (Uint16Cast - 100)) | (1L << (Uint32Cast - 100)) | (1L << (Uint64Cast - 100)) | (1L << (Uint8Cast - 100)) | (1L << (UnicodeCast - 100)) | (1L << (Unset - 100)) | (1L << (Use - 100)) | (1L << (Var - 100)) | (1L << (Get - 100)) | (1L << (Set - 100)) | (1L << (Call - 100)) | (1L << (CallStatic - 100)) | (1L << (Constructor - 100)) | (1L << (Destruct - 100)) | (1L << (Wakeup - 100)) | (1L << (Sleep - 100)) | (1L << (Autoload - 100)) | (1L << (IsSet__ - 100)) | (1L << (Unset__ - 100)) | (1L << (ToString__ - 100)) | (1L << (Invoke - 100)) | (1L << (SetState - 100)) | (1L << (Clone__ - 100)) | (1L << (DebugInfo - 100)) | (1L << (Namespace__ - 100)) | (1L << (Class__ - 100)) | (1L << (Traic__ - 100)) | (1L << (Function__ - 100)) | (1L << (Method__ - 100)) | (1L << (Line__ - 100)) | (1L << (File__ - 100)) | (1L << (Dir__ - 100)))) != 0) || ((((_la - 178)) & ~0x3f) == 0 && ((1L << (_la - 178)) & ((1L << (NamespaceSeparator - 178)) | (1L << (Ellipsis - 178)) | (1L << (Ampersand - 178)) | (1L << (OpenSquareBracket - 178)) | (1L << (Label - 178)) | (1L << (VarName - 178)))) != 0)) {
				{
				setState(989);
				formalParameter();
				}
			}

			setState(996);
			_errHandler.sync(this);
			_la = _input.LA(1);
			while (_la==Comma) {
				{
				{
				setState(992);
				match(Comma);
				setState(993);
				formalParameter();
				}
				}
				setState(998);
				_errHandler.sync(this);
				_la = _input.LA(1);
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class FormalParameterContext extends ParserRuleContext {
		public AttributesContext attributes() {
			return getRuleContext(AttributesContext.class,0);
		}
		public VariableInitializerContext variableInitializer() {
			return getRuleContext(VariableInitializerContext.class,0);
		}
		public TypeHintContext typeHint() {
			return getRuleContext(TypeHintContext.class,0);
		}
		public FormalParameterContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_formalParameter; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterFormalParameter(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitFormalParameter(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitFormalParameter(this);
			else return visitor.visitChildren(this);
		}
	}

	public final FormalParameterContext formalParameter() throws RecognitionException {
		FormalParameterContext _localctx = new FormalParameterContext(_ctx, getState());
		enterRule(_localctx, 132, RULE_formalParameter);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(999);
			attributes();
			setState(1001);
			_la = _input.LA(1);
			if (((((_la - 36)) & ~0x3f) == 0 && ((1L << (_la - 36)) & ((1L << (Abstract - 36)) | (1L << (Array - 36)) | (1L << (BinaryCast - 36)) | (1L << (BoolType - 36)) | (1L << (Break - 36)) | (1L << (Callable - 36)) | (1L << (Case - 36)) | (1L << (Class - 36)) | (1L << (Clone - 36)) | (1L << (Continue - 36)) | (1L << (Default - 36)) | (1L << (DoubleCast - 36)) | (1L << (DoubleType - 36)) | (1L << (Echo - 36)) | (1L << (Empty - 36)) | (1L << (Eval - 36)) | (1L << (Final - 36)) | (1L << (FloatCast - 36)) | (1L << (Function - 36)) | (1L << (Global - 36)) | (1L << (Import - 36)) | (1L << (Int16Cast - 36)) | (1L << (Int64Type - 36)) | (1L << (Int8Cast - 36)) | (1L << (Interface - 36)) | (1L << (IntType - 36)) | (1L << (IsSet - 36)) | (1L << (List - 36)) | (1L << (LogicalAnd - 36)) | (1L << (LogicalOr - 36)) | (1L << (LogicalXor - 36)) | (1L << (Namespace - 36)) | (1L << (New - 36)) | (1L << (Null - 36)) | (1L << (ObjectType - 36)) | (1L << (Parent_ - 36)) | (1L << (Partial - 36)))) != 0) || ((((_la - 100)) & ~0x3f) == 0 && ((1L << (_la - 100)) & ((1L << (Private - 100)) | (1L << (Protected - 100)) | (1L << (Public - 100)) | (1L << (Resource - 100)) | (1L << (Return - 100)) | (1L << (Static - 100)) | (1L << (StringType - 100)) | (1L << (Uint16Cast - 100)) | (1L << (Uint32Cast - 100)) | (1L << (Uint64Cast - 100)) | (1L << (Uint8Cast - 100)) | (1L << (UnicodeCast - 100)) | (1L << (Unset - 100)) | (1L << (Use - 100)) | (1L << (Var - 100)) | (1L << (Get - 100)) | (1L << (Set - 100)) | (1L << (Call - 100)) | (1L << (CallStatic - 100)) | (1L << (Constructor - 100)) | (1L << (Destruct - 100)) | (1L << (Wakeup - 100)) | (1L << (Sleep - 100)) | (1L << (Autoload - 100)) | (1L << (IsSet__ - 100)) | (1L << (Unset__ - 100)) | (1L << (ToString__ - 100)) | (1L << (Invoke - 100)) | (1L << (SetState - 100)) | (1L << (Clone__ - 100)) | (1L << (DebugInfo - 100)) | (1L << (Namespace__ - 100)) | (1L << (Class__ - 100)) | (1L << (Traic__ - 100)) | (1L << (Function__ - 100)) | (1L << (Method__ - 100)) | (1L << (Line__ - 100)) | (1L << (File__ - 100)) | (1L << (Dir__ - 100)))) != 0) || _la==NamespaceSeparator || _la==Label) {
				{
				setState(1000);
				typeHint();
				}
			}

			setState(1004);
			_la = _input.LA(1);
			if (_la==Ampersand) {
				{
				setState(1003);
				match(Ampersand);
				}
			}

			setState(1007);
			_la = _input.LA(1);
			if (_la==Ellipsis) {
				{
				setState(1006);
				match(Ellipsis);
				}
			}

			setState(1009);
			variableInitializer();
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class TypeHintContext extends ParserRuleContext {
		public QualifiedStaticTypeRefContext qualifiedStaticTypeRef() {
			return getRuleContext(QualifiedStaticTypeRefContext.class,0);
		}
		public TerminalNode Callable() { return getToken(PHPParser.Callable, 0); }
		public PrimitiveTypeContext primitiveType() {
			return getRuleContext(PrimitiveTypeContext.class,0);
		}
		public TypeHintContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_typeHint; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterTypeHint(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitTypeHint(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitTypeHint(this);
			else return visitor.visitChildren(this);
		}
	}

	public final TypeHintContext typeHint() throws RecognitionException {
		TypeHintContext _localctx = new TypeHintContext(_ctx, getState());
		enterRule(_localctx, 134, RULE_typeHint);
		try {
			setState(1014);
			_errHandler.sync(this);
			switch ( getInterpreter().adaptivePredict(_input,105,_ctx) ) {
			case 1:
				enterOuterAlt(_localctx, 1);
				{
				setState(1011);
				qualifiedStaticTypeRef();
				}
				break;
			case 2:
				enterOuterAlt(_localctx, 2);
				{
				setState(1012);
				match(Callable);
				}
				break;
			case 3:
				enterOuterAlt(_localctx, 3);
				{
				setState(1013);
				primitiveType();
				}
				break;
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class GlobalStatementContext extends ParserRuleContext {
		public TerminalNode Global() { return getToken(PHPParser.Global, 0); }
		public List<GlobalVarContext> globalVar() {
			return getRuleContexts(GlobalVarContext.class);
		}
		public GlobalVarContext globalVar(int i) {
			return getRuleContext(GlobalVarContext.class,i);
		}
		public GlobalStatementContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_globalStatement; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterGlobalStatement(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitGlobalStatement(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitGlobalStatement(this);
			else return visitor.visitChildren(this);
		}
	}

	public final GlobalStatementContext globalStatement() throws RecognitionException {
		GlobalStatementContext _localctx = new GlobalStatementContext(_ctx, getState());
		enterRule(_localctx, 136, RULE_globalStatement);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(1016);
			match(Global);
			setState(1017);
			globalVar();
			setState(1022);
			_errHandler.sync(this);
			_la = _input.LA(1);
			while (_la==Comma) {
				{
				{
				setState(1018);
				match(Comma);
				setState(1019);
				globalVar();
				}
				}
				setState(1024);
				_errHandler.sync(this);
				_la = _input.LA(1);
			}
			setState(1025);
			match(SemiColon);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class GlobalVarContext extends ParserRuleContext {
		public TerminalNode VarName() { return getToken(PHPParser.VarName, 0); }
		public ChainContext chain() {
			return getRuleContext(ChainContext.class,0);
		}
		public ExpressionContext expression() {
			return getRuleContext(ExpressionContext.class,0);
		}
		public GlobalVarContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_globalVar; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterGlobalVar(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitGlobalVar(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitGlobalVar(this);
			else return visitor.visitChildren(this);
		}
	}

	public final GlobalVarContext globalVar() throws RecognitionException {
		GlobalVarContext _localctx = new GlobalVarContext(_ctx, getState());
		enterRule(_localctx, 138, RULE_globalVar);
		try {
			setState(1035);
			_errHandler.sync(this);
			switch ( getInterpreter().adaptivePredict(_input,107,_ctx) ) {
			case 1:
				enterOuterAlt(_localctx, 1);
				{
				setState(1027);
				match(VarName);
				}
				break;
			case 2:
				enterOuterAlt(_localctx, 2);
				{
				setState(1028);
				match(Dollar);
				setState(1029);
				chain();
				}
				break;
			case 3:
				enterOuterAlt(_localctx, 3);
				{
				setState(1030);
				match(Dollar);
				setState(1031);
				match(OpenCurlyBracket);
				setState(1032);
				expression(0);
				setState(1033);
				match(CloseCurlyBracket);
				}
				break;
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class EchoStatementContext extends ParserRuleContext {
		public TerminalNode Echo() { return getToken(PHPParser.Echo, 0); }
		public ExpressionListContext expressionList() {
			return getRuleContext(ExpressionListContext.class,0);
		}
		public EchoStatementContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_echoStatement; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterEchoStatement(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitEchoStatement(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitEchoStatement(this);
			else return visitor.visitChildren(this);
		}
	}

	public final EchoStatementContext echoStatement() throws RecognitionException {
		EchoStatementContext _localctx = new EchoStatementContext(_ctx, getState());
		enterRule(_localctx, 140, RULE_echoStatement);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(1037);
			match(Echo);
			setState(1038);
			expressionList();
			setState(1039);
			match(SemiColon);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class StaticVariableStatementContext extends ParserRuleContext {
		public TerminalNode Static() { return getToken(PHPParser.Static, 0); }
		public List<VariableInitializerContext> variableInitializer() {
			return getRuleContexts(VariableInitializerContext.class);
		}
		public VariableInitializerContext variableInitializer(int i) {
			return getRuleContext(VariableInitializerContext.class,i);
		}
		public StaticVariableStatementContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_staticVariableStatement; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterStaticVariableStatement(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitStaticVariableStatement(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitStaticVariableStatement(this);
			else return visitor.visitChildren(this);
		}
	}

	public final StaticVariableStatementContext staticVariableStatement() throws RecognitionException {
		StaticVariableStatementContext _localctx = new StaticVariableStatementContext(_ctx, getState());
		enterRule(_localctx, 142, RULE_staticVariableStatement);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(1041);
			match(Static);
			setState(1042);
			variableInitializer();
			setState(1047);
			_errHandler.sync(this);
			_la = _input.LA(1);
			while (_la==Comma) {
				{
				{
				setState(1043);
				match(Comma);
				setState(1044);
				variableInitializer();
				}
				}
				setState(1049);
				_errHandler.sync(this);
				_la = _input.LA(1);
			}
			setState(1050);
			match(SemiColon);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class ClassStatementContext extends ParserRuleContext {
		public AttributesContext attributes() {
			return getRuleContext(AttributesContext.class,0);
		}
		public PropertyModifiersContext propertyModifiers() {
			return getRuleContext(PropertyModifiersContext.class,0);
		}
		public List<VariableInitializerContext> variableInitializer() {
			return getRuleContexts(VariableInitializerContext.class);
		}
		public VariableInitializerContext variableInitializer(int i) {
			return getRuleContext(VariableInitializerContext.class,i);
		}
		public TerminalNode Const() { return getToken(PHPParser.Const, 0); }
		public List<IdentifierInititalizerContext> identifierInititalizer() {
			return getRuleContexts(IdentifierInititalizerContext.class);
		}
		public IdentifierInititalizerContext identifierInititalizer(int i) {
			return getRuleContext(IdentifierInititalizerContext.class,i);
		}
		public TerminalNode Function() { return getToken(PHPParser.Function, 0); }
		public IdentifierContext identifier() {
			return getRuleContext(IdentifierContext.class,0);
		}
		public FormalParameterListContext formalParameterList() {
			return getRuleContext(FormalParameterListContext.class,0);
		}
		public MethodBodyContext methodBody() {
			return getRuleContext(MethodBodyContext.class,0);
		}
		public MemberModifiersContext memberModifiers() {
			return getRuleContext(MemberModifiersContext.class,0);
		}
		public TypeParameterListInBracketsContext typeParameterListInBrackets() {
			return getRuleContext(TypeParameterListInBracketsContext.class,0);
		}
		public BaseCtorCallContext baseCtorCall() {
			return getRuleContext(BaseCtorCallContext.class,0);
		}
		public TerminalNode Use() { return getToken(PHPParser.Use, 0); }
		public QualifiedNamespaceNameListContext qualifiedNamespaceNameList() {
			return getRuleContext(QualifiedNamespaceNameListContext.class,0);
		}
		public TraitAdaptationsContext traitAdaptations() {
			return getRuleContext(TraitAdaptationsContext.class,0);
		}
		public ClassStatementContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_classStatement; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterClassStatement(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitClassStatement(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitClassStatement(this);
			else return visitor.visitChildren(this);
		}
	}

	public final ClassStatementContext classStatement() throws RecognitionException {
		ClassStatementContext _localctx = new ClassStatementContext(_ctx, getState());
		enterRule(_localctx, 144, RULE_classStatement);
		int _la;
		try {
			setState(1100);
			_errHandler.sync(this);
			switch ( getInterpreter().adaptivePredict(_input,115,_ctx) ) {
			case 1:
				enterOuterAlt(_localctx, 1);
				{
				setState(1052);
				attributes();
				setState(1053);
				propertyModifiers();
				setState(1054);
				variableInitializer();
				setState(1059);
				_errHandler.sync(this);
				_la = _input.LA(1);
				while (_la==Comma) {
					{
					{
					setState(1055);
					match(Comma);
					setState(1056);
					variableInitializer();
					}
					}
					setState(1061);
					_errHandler.sync(this);
					_la = _input.LA(1);
				}
				setState(1062);
				match(SemiColon);
				}
				break;
			case 2:
				enterOuterAlt(_localctx, 2);
				{
				setState(1064);
				attributes();
				setState(1065);
				match(Const);
				setState(1066);
				identifierInititalizer();
				setState(1071);
				_errHandler.sync(this);
				_la = _input.LA(1);
				while (_la==Comma) {
					{
					{
					setState(1067);
					match(Comma);
					setState(1068);
					identifierInititalizer();
					}
					}
					setState(1073);
					_errHandler.sync(this);
					_la = _input.LA(1);
				}
				setState(1074);
				match(SemiColon);
				}
				break;
			case 3:
				enterOuterAlt(_localctx, 3);
				{
				setState(1076);
				attributes();
				setState(1078);
				_la = _input.LA(1);
				if (_la==Abstract || ((((_la - 68)) & ~0x3f) == 0 && ((1L << (_la - 68)) & ((1L << (Final - 68)) | (1L << (Private - 68)) | (1L << (Protected - 68)) | (1L << (Public - 68)) | (1L << (Static - 68)))) != 0)) {
					{
					setState(1077);
					memberModifiers();
					}
				}

				setState(1080);
				match(Function);
				setState(1082);
				_la = _input.LA(1);
				if (_la==Ampersand) {
					{
					setState(1081);
					match(Ampersand);
					}
				}

				setState(1084);
				identifier();
				setState(1086);
				_la = _input.LA(1);
				if (_la==Lgeneric) {
					{
					setState(1085);
					typeParameterListInBrackets();
					}
				}

				setState(1088);
				match(OpenRoundBracket);
				setState(1089);
				formalParameterList();
				setState(1090);
				match(CloseRoundBracket);
				setState(1092);
				_la = _input.LA(1);
				if (_la==Colon) {
					{
					setState(1091);
					baseCtorCall();
					}
				}

				setState(1094);
				methodBody();
				}
				break;
			case 4:
				enterOuterAlt(_localctx, 4);
				{
				setState(1096);
				match(Use);
				setState(1097);
				qualifiedNamespaceNameList();
				setState(1098);
				traitAdaptations();
				}
				break;
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class TraitAdaptationsContext extends ParserRuleContext {
		public List<TraitAdaptationStatementContext> traitAdaptationStatement() {
			return getRuleContexts(TraitAdaptationStatementContext.class);
		}
		public TraitAdaptationStatementContext traitAdaptationStatement(int i) {
			return getRuleContext(TraitAdaptationStatementContext.class,i);
		}
		public TraitAdaptationsContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_traitAdaptations; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterTraitAdaptations(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitTraitAdaptations(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitTraitAdaptations(this);
			else return visitor.visitChildren(this);
		}
	}

	public final TraitAdaptationsContext traitAdaptations() throws RecognitionException {
		TraitAdaptationsContext _localctx = new TraitAdaptationsContext(_ctx, getState());
		enterRule(_localctx, 146, RULE_traitAdaptations);
		int _la;
		try {
			setState(1111);
			switch (_input.LA(1)) {
			case SemiColon:
				enterOuterAlt(_localctx, 1);
				{
				setState(1102);
				match(SemiColon);
				}
				break;
			case OpenCurlyBracket:
				enterOuterAlt(_localctx, 2);
				{
				setState(1103);
				match(OpenCurlyBracket);
				setState(1107);
				_errHandler.sync(this);
				_la = _input.LA(1);
				while (((((_la - 36)) & ~0x3f) == 0 && ((1L << (_la - 36)) & ((1L << (Abstract - 36)) | (1L << (Array - 36)) | (1L << (BinaryCast - 36)) | (1L << (BoolType - 36)) | (1L << (Break - 36)) | (1L << (Callable - 36)) | (1L << (Case - 36)) | (1L << (Class - 36)) | (1L << (Clone - 36)) | (1L << (Continue - 36)) | (1L << (Default - 36)) | (1L << (DoubleCast - 36)) | (1L << (DoubleType - 36)) | (1L << (Echo - 36)) | (1L << (Empty - 36)) | (1L << (Eval - 36)) | (1L << (Final - 36)) | (1L << (FloatCast - 36)) | (1L << (Function - 36)) | (1L << (Global - 36)) | (1L << (Import - 36)) | (1L << (Int16Cast - 36)) | (1L << (Int64Type - 36)) | (1L << (Int8Cast - 36)) | (1L << (Interface - 36)) | (1L << (IntType - 36)) | (1L << (IsSet - 36)) | (1L << (List - 36)) | (1L << (LogicalAnd - 36)) | (1L << (LogicalOr - 36)) | (1L << (LogicalXor - 36)) | (1L << (Namespace - 36)) | (1L << (New - 36)) | (1L << (Null - 36)) | (1L << (ObjectType - 36)) | (1L << (Parent_ - 36)) | (1L << (Partial - 36)))) != 0) || ((((_la - 100)) & ~0x3f) == 0 && ((1L << (_la - 100)) & ((1L << (Private - 100)) | (1L << (Protected - 100)) | (1L << (Public - 100)) | (1L << (Resource - 100)) | (1L << (Return - 100)) | (1L << (Static - 100)) | (1L << (StringType - 100)) | (1L << (Uint16Cast - 100)) | (1L << (Uint32Cast - 100)) | (1L << (Uint64Cast - 100)) | (1L << (Uint8Cast - 100)) | (1L << (UnicodeCast - 100)) | (1L << (Unset - 100)) | (1L << (Use - 100)) | (1L << (Var - 100)) | (1L << (Get - 100)) | (1L << (Set - 100)) | (1L << (Call - 100)) | (1L << (CallStatic - 100)) | (1L << (Constructor - 100)) | (1L << (Destruct - 100)) | (1L << (Wakeup - 100)) | (1L << (Sleep - 100)) | (1L << (Autoload - 100)) | (1L << (IsSet__ - 100)) | (1L << (Unset__ - 100)) | (1L << (ToString__ - 100)) | (1L << (Invoke - 100)) | (1L << (SetState - 100)) | (1L << (Clone__ - 100)) | (1L << (DebugInfo - 100)) | (1L << (Namespace__ - 100)) | (1L << (Class__ - 100)) | (1L << (Traic__ - 100)) | (1L << (Function__ - 100)) | (1L << (Method__ - 100)) | (1L << (Line__ - 100)) | (1L << (File__ - 100)) | (1L << (Dir__ - 100)))) != 0) || _la==NamespaceSeparator || _la==Label) {
					{
					{
					setState(1104);
					traitAdaptationStatement();
					}
					}
					setState(1109);
					_errHandler.sync(this);
					_la = _input.LA(1);
				}
				setState(1110);
				match(CloseCurlyBracket);
				}
				break;
			default:
				throw new NoViableAltException(this);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class TraitAdaptationStatementContext extends ParserRuleContext {
		public TraitPrecedenceContext traitPrecedence() {
			return getRuleContext(TraitPrecedenceContext.class,0);
		}
		public TraitAliasContext traitAlias() {
			return getRuleContext(TraitAliasContext.class,0);
		}
		public TraitAdaptationStatementContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_traitAdaptationStatement; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterTraitAdaptationStatement(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitTraitAdaptationStatement(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitTraitAdaptationStatement(this);
			else return visitor.visitChildren(this);
		}
	}

	public final TraitAdaptationStatementContext traitAdaptationStatement() throws RecognitionException {
		TraitAdaptationStatementContext _localctx = new TraitAdaptationStatementContext(_ctx, getState());
		enterRule(_localctx, 148, RULE_traitAdaptationStatement);
		try {
			setState(1115);
			_errHandler.sync(this);
			switch ( getInterpreter().adaptivePredict(_input,118,_ctx) ) {
			case 1:
				enterOuterAlt(_localctx, 1);
				{
				setState(1113);
				traitPrecedence();
				}
				break;
			case 2:
				enterOuterAlt(_localctx, 2);
				{
				setState(1114);
				traitAlias();
				}
				break;
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class TraitPrecedenceContext extends ParserRuleContext {
		public QualifiedNamespaceNameContext qualifiedNamespaceName() {
			return getRuleContext(QualifiedNamespaceNameContext.class,0);
		}
		public IdentifierContext identifier() {
			return getRuleContext(IdentifierContext.class,0);
		}
		public TerminalNode InsteadOf() { return getToken(PHPParser.InsteadOf, 0); }
		public QualifiedNamespaceNameListContext qualifiedNamespaceNameList() {
			return getRuleContext(QualifiedNamespaceNameListContext.class,0);
		}
		public TraitPrecedenceContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_traitPrecedence; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterTraitPrecedence(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitTraitPrecedence(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitTraitPrecedence(this);
			else return visitor.visitChildren(this);
		}
	}

	public final TraitPrecedenceContext traitPrecedence() throws RecognitionException {
		TraitPrecedenceContext _localctx = new TraitPrecedenceContext(_ctx, getState());
		enterRule(_localctx, 150, RULE_traitPrecedence);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(1117);
			qualifiedNamespaceName();
			setState(1118);
			match(DoubleColon);
			setState(1119);
			identifier();
			setState(1120);
			match(InsteadOf);
			setState(1121);
			qualifiedNamespaceNameList();
			setState(1122);
			match(SemiColon);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class TraitAliasContext extends ParserRuleContext {
		public TraitMethodReferenceContext traitMethodReference() {
			return getRuleContext(TraitMethodReferenceContext.class,0);
		}
		public TerminalNode As() { return getToken(PHPParser.As, 0); }
		public MemberModifierContext memberModifier() {
			return getRuleContext(MemberModifierContext.class,0);
		}
		public IdentifierContext identifier() {
			return getRuleContext(IdentifierContext.class,0);
		}
		public TraitAliasContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_traitAlias; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterTraitAlias(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitTraitAlias(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitTraitAlias(this);
			else return visitor.visitChildren(this);
		}
	}

	public final TraitAliasContext traitAlias() throws RecognitionException {
		TraitAliasContext _localctx = new TraitAliasContext(_ctx, getState());
		enterRule(_localctx, 152, RULE_traitAlias);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(1124);
			traitMethodReference();
			setState(1125);
			match(As);
			setState(1131);
			_errHandler.sync(this);
			switch ( getInterpreter().adaptivePredict(_input,120,_ctx) ) {
			case 1:
				{
				setState(1126);
				memberModifier();
				}
				break;
			case 2:
				{
				setState(1128);
				_errHandler.sync(this);
				switch ( getInterpreter().adaptivePredict(_input,119,_ctx) ) {
				case 1:
					{
					setState(1127);
					memberModifier();
					}
					break;
				}
				setState(1130);
				identifier();
				}
				break;
			}
			setState(1133);
			match(SemiColon);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class TraitMethodReferenceContext extends ParserRuleContext {
		public IdentifierContext identifier() {
			return getRuleContext(IdentifierContext.class,0);
		}
		public QualifiedNamespaceNameContext qualifiedNamespaceName() {
			return getRuleContext(QualifiedNamespaceNameContext.class,0);
		}
		public TraitMethodReferenceContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_traitMethodReference; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterTraitMethodReference(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitTraitMethodReference(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitTraitMethodReference(this);
			else return visitor.visitChildren(this);
		}
	}

	public final TraitMethodReferenceContext traitMethodReference() throws RecognitionException {
		TraitMethodReferenceContext _localctx = new TraitMethodReferenceContext(_ctx, getState());
		enterRule(_localctx, 154, RULE_traitMethodReference);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(1138);
			_errHandler.sync(this);
			switch ( getInterpreter().adaptivePredict(_input,121,_ctx) ) {
			case 1:
				{
				setState(1135);
				qualifiedNamespaceName();
				setState(1136);
				match(DoubleColon);
				}
				break;
			}
			setState(1140);
			identifier();
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class BaseCtorCallContext extends ParserRuleContext {
		public IdentifierContext identifier() {
			return getRuleContext(IdentifierContext.class,0);
		}
		public ArgumentsContext arguments() {
			return getRuleContext(ArgumentsContext.class,0);
		}
		public BaseCtorCallContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_baseCtorCall; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterBaseCtorCall(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitBaseCtorCall(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitBaseCtorCall(this);
			else return visitor.visitChildren(this);
		}
	}

	public final BaseCtorCallContext baseCtorCall() throws RecognitionException {
		BaseCtorCallContext _localctx = new BaseCtorCallContext(_ctx, getState());
		enterRule(_localctx, 156, RULE_baseCtorCall);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(1142);
			match(Colon);
			setState(1143);
			identifier();
			setState(1144);
			arguments();
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class MethodBodyContext extends ParserRuleContext {
		public BlockStatementContext blockStatement() {
			return getRuleContext(BlockStatementContext.class,0);
		}
		public MethodBodyContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_methodBody; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterMethodBody(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitMethodBody(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitMethodBody(this);
			else return visitor.visitChildren(this);
		}
	}

	public final MethodBodyContext methodBody() throws RecognitionException {
		MethodBodyContext _localctx = new MethodBodyContext(_ctx, getState());
		enterRule(_localctx, 158, RULE_methodBody);
		try {
			setState(1148);
			switch (_input.LA(1)) {
			case SemiColon:
				enterOuterAlt(_localctx, 1);
				{
				setState(1146);
				match(SemiColon);
				}
				break;
			case OpenCurlyBracket:
				enterOuterAlt(_localctx, 2);
				{
				setState(1147);
				blockStatement();
				}
				break;
			default:
				throw new NoViableAltException(this);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class PropertyModifiersContext extends ParserRuleContext {
		public MemberModifiersContext memberModifiers() {
			return getRuleContext(MemberModifiersContext.class,0);
		}
		public TerminalNode Var() { return getToken(PHPParser.Var, 0); }
		public PropertyModifiersContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_propertyModifiers; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterPropertyModifiers(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitPropertyModifiers(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitPropertyModifiers(this);
			else return visitor.visitChildren(this);
		}
	}

	public final PropertyModifiersContext propertyModifiers() throws RecognitionException {
		PropertyModifiersContext _localctx = new PropertyModifiersContext(_ctx, getState());
		enterRule(_localctx, 160, RULE_propertyModifiers);
		try {
			setState(1152);
			switch (_input.LA(1)) {
			case Abstract:
			case Final:
			case Private:
			case Protected:
			case Public:
			case Static:
				enterOuterAlt(_localctx, 1);
				{
				setState(1150);
				memberModifiers();
				}
				break;
			case Var:
				enterOuterAlt(_localctx, 2);
				{
				setState(1151);
				match(Var);
				}
				break;
			default:
				throw new NoViableAltException(this);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class MemberModifiersContext extends ParserRuleContext {
		public List<MemberModifierContext> memberModifier() {
			return getRuleContexts(MemberModifierContext.class);
		}
		public MemberModifierContext memberModifier(int i) {
			return getRuleContext(MemberModifierContext.class,i);
		}
		public MemberModifiersContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_memberModifiers; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterMemberModifiers(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitMemberModifiers(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitMemberModifiers(this);
			else return visitor.visitChildren(this);
		}
	}

	public final MemberModifiersContext memberModifiers() throws RecognitionException {
		MemberModifiersContext _localctx = new MemberModifiersContext(_ctx, getState());
		enterRule(_localctx, 162, RULE_memberModifiers);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(1155);
			_errHandler.sync(this);
			_la = _input.LA(1);
			do {
				{
				{
				setState(1154);
				memberModifier();
				}
				}
				setState(1157);
				_errHandler.sync(this);
				_la = _input.LA(1);
			} while ( _la==Abstract || ((((_la - 68)) & ~0x3f) == 0 && ((1L << (_la - 68)) & ((1L << (Final - 68)) | (1L << (Private - 68)) | (1L << (Protected - 68)) | (1L << (Public - 68)) | (1L << (Static - 68)))) != 0) );
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class VariableInitializerContext extends ParserRuleContext {
		public TerminalNode VarName() { return getToken(PHPParser.VarName, 0); }
		public TerminalNode Eq() { return getToken(PHPParser.Eq, 0); }
		public ConstantInititalizerContext constantInititalizer() {
			return getRuleContext(ConstantInititalizerContext.class,0);
		}
		public VariableInitializerContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_variableInitializer; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterVariableInitializer(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitVariableInitializer(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitVariableInitializer(this);
			else return visitor.visitChildren(this);
		}
	}

	public final VariableInitializerContext variableInitializer() throws RecognitionException {
		VariableInitializerContext _localctx = new VariableInitializerContext(_ctx, getState());
		enterRule(_localctx, 164, RULE_variableInitializer);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(1159);
			match(VarName);
			setState(1162);
			_la = _input.LA(1);
			if (_la==Eq) {
				{
				setState(1160);
				match(Eq);
				setState(1161);
				constantInititalizer();
				}
			}

			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class IdentifierInititalizerContext extends ParserRuleContext {
		public IdentifierContext identifier() {
			return getRuleContext(IdentifierContext.class,0);
		}
		public TerminalNode Eq() { return getToken(PHPParser.Eq, 0); }
		public ConstantInititalizerContext constantInititalizer() {
			return getRuleContext(ConstantInititalizerContext.class,0);
		}
		public IdentifierInititalizerContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_identifierInititalizer; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterIdentifierInititalizer(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitIdentifierInititalizer(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitIdentifierInititalizer(this);
			else return visitor.visitChildren(this);
		}
	}

	public final IdentifierInititalizerContext identifierInititalizer() throws RecognitionException {
		IdentifierInititalizerContext _localctx = new IdentifierInititalizerContext(_ctx, getState());
		enterRule(_localctx, 166, RULE_identifierInititalizer);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(1164);
			identifier();
			setState(1165);
			match(Eq);
			setState(1166);
			constantInititalizer();
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class GlobalConstantDeclarationContext extends ParserRuleContext {
		public AttributesContext attributes() {
			return getRuleContext(AttributesContext.class,0);
		}
		public TerminalNode Const() { return getToken(PHPParser.Const, 0); }
		public List<IdentifierInititalizerContext> identifierInititalizer() {
			return getRuleContexts(IdentifierInititalizerContext.class);
		}
		public IdentifierInititalizerContext identifierInititalizer(int i) {
			return getRuleContext(IdentifierInititalizerContext.class,i);
		}
		public GlobalConstantDeclarationContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_globalConstantDeclaration; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterGlobalConstantDeclaration(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitGlobalConstantDeclaration(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitGlobalConstantDeclaration(this);
			else return visitor.visitChildren(this);
		}
	}

	public final GlobalConstantDeclarationContext globalConstantDeclaration() throws RecognitionException {
		GlobalConstantDeclarationContext _localctx = new GlobalConstantDeclarationContext(_ctx, getState());
		enterRule(_localctx, 168, RULE_globalConstantDeclaration);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(1168);
			attributes();
			setState(1169);
			match(Const);
			setState(1170);
			identifierInititalizer();
			setState(1175);
			_errHandler.sync(this);
			_la = _input.LA(1);
			while (_la==Comma) {
				{
				{
				setState(1171);
				match(Comma);
				setState(1172);
				identifierInititalizer();
				}
				}
				setState(1177);
				_errHandler.sync(this);
				_la = _input.LA(1);
			}
			setState(1178);
			match(SemiColon);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class ExpressionListContext extends ParserRuleContext {
		public List<ExpressionContext> expression() {
			return getRuleContexts(ExpressionContext.class);
		}
		public ExpressionContext expression(int i) {
			return getRuleContext(ExpressionContext.class,i);
		}
		public ExpressionListContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_expressionList; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterExpressionList(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitExpressionList(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitExpressionList(this);
			else return visitor.visitChildren(this);
		}
	}

	public final ExpressionListContext expressionList() throws RecognitionException {
		ExpressionListContext _localctx = new ExpressionListContext(_ctx, getState());
		enterRule(_localctx, 170, RULE_expressionList);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(1180);
			expression(0);
			setState(1185);
			_errHandler.sync(this);
			_la = _input.LA(1);
			while (_la==Comma) {
				{
				{
				setState(1181);
				match(Comma);
				setState(1182);
				expression(0);
				}
				}
				setState(1187);
				_errHandler.sync(this);
				_la = _input.LA(1);
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class ParenthesisContext extends ParserRuleContext {
		public ExpressionContext expression() {
			return getRuleContext(ExpressionContext.class,0);
		}
		public YieldExpressionContext yieldExpression() {
			return getRuleContext(YieldExpressionContext.class,0);
		}
		public ParenthesisContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_parenthesis; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterParenthesis(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitParenthesis(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitParenthesis(this);
			else return visitor.visitChildren(this);
		}
	}

	public final ParenthesisContext parenthesis() throws RecognitionException {
		ParenthesisContext _localctx = new ParenthesisContext(_ctx, getState());
		enterRule(_localctx, 172, RULE_parenthesis);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(1188);
			match(OpenRoundBracket);
			setState(1191);
			_errHandler.sync(this);
			switch ( getInterpreter().adaptivePredict(_input,128,_ctx) ) {
			case 1:
				{
				setState(1189);
				expression(0);
				}
				break;
			case 2:
				{
				setState(1190);
				yieldExpression();
				}
				break;
			}
			setState(1193);
			match(CloseRoundBracket);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class ExpressionContext extends ParserRuleContext {
		public ExpressionContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_expression; }

		public ExpressionContext() { }
		public void copyFrom(ExpressionContext ctx) {
			super.copyFrom(ctx);
		}
	}
	public static class ChainExpressionContext extends ExpressionContext {
		public ChainContext chain() {
			return getRuleContext(ChainContext.class,0);
		}
		public ChainExpressionContext(ExpressionContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterChainExpression(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitChainExpression(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitChainExpression(this);
			else return visitor.visitChildren(this);
		}
	}
	public static class UnaryOperatorExpressionContext extends ExpressionContext {
		public ExpressionContext expression() {
			return getRuleContext(ExpressionContext.class,0);
		}
		public UnaryOperatorExpressionContext(ExpressionContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterUnaryOperatorExpression(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitUnaryOperatorExpression(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitUnaryOperatorExpression(this);
			else return visitor.visitChildren(this);
		}
	}
	public static class SpecialWordExpressionContext extends ExpressionContext {
		public TerminalNode Yield() { return getToken(PHPParser.Yield, 0); }
		public TerminalNode List() { return getToken(PHPParser.List, 0); }
		public AssignmentListContext assignmentList() {
			return getRuleContext(AssignmentListContext.class,0);
		}
		public TerminalNode Eq() { return getToken(PHPParser.Eq, 0); }
		public ExpressionContext expression() {
			return getRuleContext(ExpressionContext.class,0);
		}
		public TerminalNode IsSet() { return getToken(PHPParser.IsSet, 0); }
		public ChainListContext chainList() {
			return getRuleContext(ChainListContext.class,0);
		}
		public TerminalNode Empty() { return getToken(PHPParser.Empty, 0); }
		public ChainContext chain() {
			return getRuleContext(ChainContext.class,0);
		}
		public TerminalNode Eval() { return getToken(PHPParser.Eval, 0); }
		public TerminalNode Exit() { return getToken(PHPParser.Exit, 0); }
		public ParenthesisContext parenthesis() {
			return getRuleContext(ParenthesisContext.class,0);
		}
		public TerminalNode Include() { return getToken(PHPParser.Include, 0); }
		public TerminalNode IncludeOnce() { return getToken(PHPParser.IncludeOnce, 0); }
		public TerminalNode Require() { return getToken(PHPParser.Require, 0); }
		public TerminalNode RequireOnce() { return getToken(PHPParser.RequireOnce, 0); }
		public SpecialWordExpressionContext(ExpressionContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterSpecialWordExpression(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitSpecialWordExpression(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitSpecialWordExpression(this);
			else return visitor.visitChildren(this);
		}
	}
	public static class ArrayCreationExpressionContext extends ExpressionContext {
		public TerminalNode Array() { return getToken(PHPParser.Array, 0); }
		public ExpressionContext expression() {
			return getRuleContext(ExpressionContext.class,0);
		}
		public ArrayItemListContext arrayItemList() {
			return getRuleContext(ArrayItemListContext.class,0);
		}
		public ArrayCreationExpressionContext(ExpressionContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterArrayCreationExpression(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitArrayCreationExpression(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitArrayCreationExpression(this);
			else return visitor.visitChildren(this);
		}
	}
	public static class NewExpressionContext extends ExpressionContext {
		public NewExprContext newExpr() {
			return getRuleContext(NewExprContext.class,0);
		}
		public NewExpressionContext(ExpressionContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterNewExpression(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitNewExpression(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitNewExpression(this);
			else return visitor.visitChildren(this);
		}
	}
	public static class ParenthesisExpressionContext extends ExpressionContext {
		public ParenthesisContext parenthesis() {
			return getRuleContext(ParenthesisContext.class,0);
		}
		public ParenthesisExpressionContext(ExpressionContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterParenthesisExpression(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitParenthesisExpression(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitParenthesisExpression(this);
			else return visitor.visitChildren(this);
		}
	}
	public static class BackQuoteStringExpressionContext extends ExpressionContext {
		public TerminalNode BackQuoteString() { return getToken(PHPParser.BackQuoteString, 0); }
		public BackQuoteStringExpressionContext(ExpressionContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterBackQuoteStringExpression(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitBackQuoteStringExpression(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitBackQuoteStringExpression(this);
			else return visitor.visitChildren(this);
		}
	}
	public static class ConditionalExpressionContext extends ExpressionContext {
		public List<ExpressionContext> expression() {
			return getRuleContexts(ExpressionContext.class);
		}
		public ExpressionContext expression(int i) {
			return getRuleContext(ExpressionContext.class,i);
		}
		public TerminalNode QuestionMark() { return getToken(PHPParser.QuestionMark, 0); }
		public ConditionalExpressionContext(ExpressionContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterConditionalExpression(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitConditionalExpression(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitConditionalExpression(this);
			else return visitor.visitChildren(this);
		}
	}
	public static class IndexerExpressionContext extends ExpressionContext {
		public StringConstantContext stringConstant() {
			return getRuleContext(StringConstantContext.class,0);
		}
		public ExpressionContext expression() {
			return getRuleContext(ExpressionContext.class,0);
		}
		public IndexerExpressionContext(ExpressionContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterIndexerExpression(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitIndexerExpression(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitIndexerExpression(this);
			else return visitor.visitChildren(this);
		}
	}
	public static class ScalarExpressionContext extends ExpressionContext {
		public ConstantContext constant() {
			return getRuleContext(ConstantContext.class,0);
		}
		public StringContext string() {
			return getRuleContext(StringContext.class,0);
		}
		public TerminalNode Label() { return getToken(PHPParser.Label, 0); }
		public ScalarExpressionContext(ExpressionContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterScalarExpression(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitScalarExpression(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitScalarExpression(this);
			else return visitor.visitChildren(this);
		}
	}
	public static class PrefixIncDecExpressionContext extends ExpressionContext {
		public ChainContext chain() {
			return getRuleContext(ChainContext.class,0);
		}
		public PrefixIncDecExpressionContext(ExpressionContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterPrefixIncDecExpression(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitPrefixIncDecExpression(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitPrefixIncDecExpression(this);
			else return visitor.visitChildren(this);
		}
	}
	public static class BinaryOperatorExpressionContext extends ExpressionContext {
		public List<ExpressionContext> expression() {
			return getRuleContexts(ExpressionContext.class);
		}
		public ExpressionContext expression(int i) {
			return getRuleContext(ExpressionContext.class,i);
		}
		public TerminalNode Divide() { return getToken(PHPParser.Divide, 0); }
		public TerminalNode Less() { return getToken(PHPParser.Less, 0); }
		public TerminalNode Greater() { return getToken(PHPParser.Greater, 0); }
		public TerminalNode IsNotEq() { return getToken(PHPParser.IsNotEq, 0); }
		public TerminalNode LogicalAnd() { return getToken(PHPParser.LogicalAnd, 0); }
		public TerminalNode LogicalXor() { return getToken(PHPParser.LogicalXor, 0); }
		public TerminalNode LogicalOr() { return getToken(PHPParser.LogicalOr, 0); }
		public BinaryOperatorExpressionContext(ExpressionContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterBinaryOperatorExpression(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitBinaryOperatorExpression(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitBinaryOperatorExpression(this);
			else return visitor.visitChildren(this);
		}
	}
	public static class PrintExpressionContext extends ExpressionContext {
		public TerminalNode Print() { return getToken(PHPParser.Print, 0); }
		public ExpressionContext expression() {
			return getRuleContext(ExpressionContext.class,0);
		}
		public PrintExpressionContext(ExpressionContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterPrintExpression(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitPrintExpression(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitPrintExpression(this);
			else return visitor.visitChildren(this);
		}
	}
	public static class AssignmentExpressionContext extends ExpressionContext {
		public List<ChainContext> chain() {
			return getRuleContexts(ChainContext.class);
		}
		public ChainContext chain(int i) {
			return getRuleContext(ChainContext.class,i);
		}
		public AssignmentOperatorContext assignmentOperator() {
			return getRuleContext(AssignmentOperatorContext.class,0);
		}
		public ExpressionContext expression() {
			return getRuleContext(ExpressionContext.class,0);
		}
		public TerminalNode Eq() { return getToken(PHPParser.Eq, 0); }
		public NewExprContext newExpr() {
			return getRuleContext(NewExprContext.class,0);
		}
		public AssignmentExpressionContext(ExpressionContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterAssignmentExpression(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitAssignmentExpression(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitAssignmentExpression(this);
			else return visitor.visitChildren(this);
		}
	}
	public static class PostfixIncDecExpressionContext extends ExpressionContext {
		public ChainContext chain() {
			return getRuleContext(ChainContext.class,0);
		}
		public PostfixIncDecExpressionContext(ExpressionContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterPostfixIncDecExpression(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitPostfixIncDecExpression(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitPostfixIncDecExpression(this);
			else return visitor.visitChildren(this);
		}
	}
	public static class CastExpressionContext extends ExpressionContext {
		public CastOperationContext castOperation() {
			return getRuleContext(CastOperationContext.class,0);
		}
		public ExpressionContext expression() {
			return getRuleContext(ExpressionContext.class,0);
		}
		public CastExpressionContext(ExpressionContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterCastExpression(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitCastExpression(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitCastExpression(this);
			else return visitor.visitChildren(this);
		}
	}
	public static class InstanceOfExpressionContext extends ExpressionContext {
		public ExpressionContext expression() {
			return getRuleContext(ExpressionContext.class,0);
		}
		public TerminalNode InstanceOf() { return getToken(PHPParser.InstanceOf, 0); }
		public TypeRefContext typeRef() {
			return getRuleContext(TypeRefContext.class,0);
		}
		public InstanceOfExpressionContext(ExpressionContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterInstanceOfExpression(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitInstanceOfExpression(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitInstanceOfExpression(this);
			else return visitor.visitChildren(this);
		}
	}
	public static class LambdaFunctionExpressionContext extends ExpressionContext {
		public TerminalNode Function() { return getToken(PHPParser.Function, 0); }
		public FormalParameterListContext formalParameterList() {
			return getRuleContext(FormalParameterListContext.class,0);
		}
		public BlockStatementContext blockStatement() {
			return getRuleContext(BlockStatementContext.class,0);
		}
		public TerminalNode Static() { return getToken(PHPParser.Static, 0); }
		public LambdaFunctionUseVarsContext lambdaFunctionUseVars() {
			return getRuleContext(LambdaFunctionUseVarsContext.class,0);
		}
		public LambdaFunctionExpressionContext(ExpressionContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterLambdaFunctionExpression(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitLambdaFunctionExpression(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitLambdaFunctionExpression(this);
			else return visitor.visitChildren(this);
		}
	}
	public static class CloneExpressionContext extends ExpressionContext {
		public TerminalNode Clone() { return getToken(PHPParser.Clone, 0); }
		public ExpressionContext expression() {
			return getRuleContext(ExpressionContext.class,0);
		}
		public CloneExpressionContext(ExpressionContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterCloneExpression(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitCloneExpression(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitCloneExpression(this);
			else return visitor.visitChildren(this);
		}
	}

	public final ExpressionContext expression() throws RecognitionException {
		return expression(0);
	}

	private ExpressionContext expression(int _p) throws RecognitionException {
		ParserRuleContext _parentctx = _ctx;
		int _parentState = getState();
		ExpressionContext _localctx = new ExpressionContext(_ctx, _parentState);
		ExpressionContext _prevctx = _localctx;
		int _startState = 174;
		enterRecursionRule(_localctx, 174, RULE_expression, _p);
		int _la;
		try {
			int _alt;
			enterOuterAlt(_localctx, 1);
			{
			setState(1308);
			_errHandler.sync(this);
			switch ( getInterpreter().adaptivePredict(_input,138,_ctx) ) {
			case 1:
				{
				_localctx = new CloneExpressionContext(_localctx);
				_ctx = _localctx;
				_prevctx = _localctx;

				setState(1196);
				match(Clone);
				setState(1197);
				expression(45);
				}
				break;
			case 2:
				{
				_localctx = new NewExpressionContext(_localctx);
				_ctx = _localctx;
				_prevctx = _localctx;
				setState(1198);
				newExpr();
				}
				break;
			case 3:
				{
				_localctx = new IndexerExpressionContext(_localctx);
				_ctx = _localctx;
				_prevctx = _localctx;
				setState(1199);
				stringConstant();
				setState(1200);
				match(OpenSquareBracket);
				setState(1201);
				expression(0);
				setState(1202);
				match(CloseSquareBracket);
				}
				break;
			case 4:
				{
				_localctx = new PrefixIncDecExpressionContext(_localctx);
				_ctx = _localctx;
				_prevctx = _localctx;
				setState(1204);
				_la = _input.LA(1);
				if ( !(_la==Inc || _la==Dec) ) {
				_errHandler.recoverInline(this);
				} else {
					consume();
				}
				setState(1205);
				chain();
				}
				break;
			case 5:
				{
				_localctx = new PostfixIncDecExpressionContext(_localctx);
				_ctx = _localctx;
				_prevctx = _localctx;
				setState(1206);
				chain();
				setState(1207);
				_la = _input.LA(1);
				if ( !(_la==Inc || _la==Dec) ) {
				_errHandler.recoverInline(this);
				} else {
					consume();
				}
				}
				break;
			case 6:
				{
				_localctx = new CastExpressionContext(_localctx);
				_ctx = _localctx;
				_prevctx = _localctx;
				setState(1209);
				match(OpenRoundBracket);
				setState(1210);
				castOperation();
				setState(1211);
				match(CloseRoundBracket);
				setState(1212);
				expression(39);
				}
				break;
			case 7:
				{
				_localctx = new UnaryOperatorExpressionContext(_localctx);
				_ctx = _localctx;
				_prevctx = _localctx;
				setState(1214);
				_la = _input.LA(1);
				if ( !(_la==Tilde || _la==SuppressWarnings) ) {
				_errHandler.recoverInline(this);
				} else {
					consume();
				}
				setState(1215);
				expression(38);
				}
				break;
			case 8:
				{
				_localctx = new UnaryOperatorExpressionContext(_localctx);
				_ctx = _localctx;
				_prevctx = _localctx;
				setState(1216);
				_la = _input.LA(1);
				if ( !(((((_la - 184)) & ~0x3f) == 0 && ((1L << (_la - 184)) & ((1L << (Bang - 184)) | (1L << (Plus - 184)) | (1L << (Minus - 184)))) != 0)) ) {
				_errHandler.recoverInline(this);
				} else {
					consume();
				}
				setState(1217);
				expression(36);
				}
				break;
			case 9:
				{
				_localctx = new AssignmentExpressionContext(_localctx);
				_ctx = _localctx;
				_prevctx = _localctx;
				setState(1218);
				chain();
				setState(1219);
				assignmentOperator();
				setState(1220);
				expression(24);
				}
				break;
			case 10:
				{
				_localctx = new AssignmentExpressionContext(_localctx);
				_ctx = _localctx;
				_prevctx = _localctx;
				setState(1222);
				chain();
				setState(1223);
				match(Eq);
				setState(1224);
				match(Ampersand);
				setState(1227);
				_errHandler.sync(this);
				switch ( getInterpreter().adaptivePredict(_input,129,_ctx) ) {
				case 1:
					{
					setState(1225);
					chain();
					}
					break;
				case 2:
					{
					setState(1226);
					newExpr();
					}
					break;
				}
				}
				break;
			case 11:
				{
				_localctx = new PrintExpressionContext(_localctx);
				_ctx = _localctx;
				_prevctx = _localctx;
				setState(1229);
				match(Print);
				setState(1230);
				expression(22);
				}
				break;
			case 12:
				{
				_localctx = new ChainExpressionContext(_localctx);
				_ctx = _localctx;
				_prevctx = _localctx;
				setState(1231);
				chain();
				}
				break;
			case 13:
				{
				_localctx = new ScalarExpressionContext(_localctx);
				_ctx = _localctx;
				_prevctx = _localctx;
				setState(1232);
				constant();
				}
				break;
			case 14:
				{
				_localctx = new ScalarExpressionContext(_localctx);
				_ctx = _localctx;
				_prevctx = _localctx;
				setState(1233);
				string();
				}
				break;
			case 15:
				{
				_localctx = new ScalarExpressionContext(_localctx);
				_ctx = _localctx;
				_prevctx = _localctx;
				setState(1234);
				match(Label);
				}
				break;
			case 16:
				{
				_localctx = new BackQuoteStringExpressionContext(_localctx);
				_ctx = _localctx;
				_prevctx = _localctx;
				setState(1235);
				match(BackQuoteString);
				}
				break;
			case 17:
				{
				_localctx = new ParenthesisExpressionContext(_localctx);
				_ctx = _localctx;
				_prevctx = _localctx;
				setState(1236);
				parenthesis();
				}
				break;
			case 18:
				{
				_localctx = new ArrayCreationExpressionContext(_localctx);
				_ctx = _localctx;
				_prevctx = _localctx;
				setState(1248);
				switch (_input.LA(1)) {
				case Array:
					{
					setState(1237);
					match(Array);
					setState(1238);
					match(OpenRoundBracket);
					setState(1240);
					_la = _input.LA(1);
					if (((((_la - 36)) & ~0x3f) == 0 && ((1L << (_la - 36)) & ((1L << (Abstract - 36)) | (1L << (Array - 36)) | (1L << (BinaryCast - 36)) | (1L << (BoolType - 36)) | (1L << (BooleanConstant - 36)) | (1L << (Break - 36)) | (1L << (Callable - 36)) | (1L << (Case - 36)) | (1L << (Class - 36)) | (1L << (Clone - 36)) | (1L << (Continue - 36)) | (1L << (Default - 36)) | (1L << (DoubleCast - 36)) | (1L << (DoubleType - 36)) | (1L << (Echo - 36)) | (1L << (Empty - 36)) | (1L << (Eval - 36)) | (1L << (Exit - 36)) | (1L << (Final - 36)) | (1L << (FloatCast - 36)) | (1L << (Function - 36)) | (1L << (Global - 36)) | (1L << (Import - 36)) | (1L << (Include - 36)) | (1L << (IncludeOnce - 36)) | (1L << (Int16Cast - 36)) | (1L << (Int64Type - 36)) | (1L << (Int8Cast - 36)) | (1L << (Interface - 36)) | (1L << (IntType - 36)) | (1L << (IsSet - 36)) | (1L << (List - 36)) | (1L << (LogicalAnd - 36)) | (1L << (LogicalOr - 36)) | (1L << (LogicalXor - 36)) | (1L << (Namespace - 36)) | (1L << (New - 36)) | (1L << (Null - 36)) | (1L << (ObjectType - 36)) | (1L << (Parent_ - 36)) | (1L << (Partial - 36)) | (1L << (Print - 36)))) != 0) || ((((_la - 100)) & ~0x3f) == 0 && ((1L << (_la - 100)) & ((1L << (Private - 100)) | (1L << (Protected - 100)) | (1L << (Public - 100)) | (1L << (Require - 100)) | (1L << (RequireOnce - 100)) | (1L << (Resource - 100)) | (1L << (Return - 100)) | (1L << (Static - 100)) | (1L << (StringType - 100)) | (1L << (Uint16Cast - 100)) | (1L << (Uint32Cast - 100)) | (1L << (Uint64Cast - 100)) | (1L << (Uint8Cast - 100)) | (1L << (UnicodeCast - 100)) | (1L << (Unset - 100)) | (1L << (Use - 100)) | (1L << (Var - 100)) | (1L << (Yield - 100)) | (1L << (Get - 100)) | (1L << (Set - 100)) | (1L << (Call - 100)) | (1L << (CallStatic - 100)) | (1L << (Constructor - 100)) | (1L << (Destruct - 100)) | (1L << (Wakeup - 100)) | (1L << (Sleep - 100)) | (1L << (Autoload - 100)) | (1L << (IsSet__ - 100)) | (1L << (Unset__ - 100)) | (1L << (ToString__ - 100)) | (1L << (Invoke - 100)) | (1L << (SetState - 100)) | (1L << (Clone__ - 100)) | (1L << (DebugInfo - 100)) | (1L << (Namespace__ - 100)) | (1L << (Class__ - 100)) | (1L << (Traic__ - 100)) | (1L << (Function__ - 100)) | (1L << (Method__ - 100)) | (1L << (Line__ - 100)) | (1L << (File__ - 100)) | (1L << (Dir__ - 100)) | (1L << (Inc - 100)) | (1L << (Dec - 100)))) != 0) || ((((_la - 178)) & ~0x3f) == 0 && ((1L << (_la - 178)) & ((1L << (NamespaceSeparator - 178)) | (1L << (Ampersand - 178)) | (1L << (Bang - 178)) | (1L << (Plus - 178)) | (1L << (Minus - 178)) | (1L << (Tilde - 178)) | (1L << (SuppressWarnings - 178)) | (1L << (Dollar - 178)) | (1L << (OpenRoundBracket - 178)) | (1L << (OpenSquareBracket - 178)) | (1L << (Label - 178)) | (1L << (VarName - 178)) | (1L << (Numeric - 178)) | (1L << (Real - 178)) | (1L << (BackQuoteString - 178)) | (1L << (SingleQuoteString - 178)) | (1L << (DoubleQuoteString - 178)) | (1L << (StartNowDoc - 178)) | (1L << (StartHereDoc - 178)))) != 0)) {
						{
						setState(1239);
						arrayItemList();
						}
					}

					setState(1242);
					match(CloseRoundBracket);
					}
					break;
				case OpenSquareBracket:
					{
					setState(1243);
					match(OpenSquareBracket);
					setState(1245);
					_la = _input.LA(1);
					if (((((_la - 36)) & ~0x3f) == 0 && ((1L << (_la - 36)) & ((1L << (Abstract - 36)) | (1L << (Array - 36)) | (1L << (BinaryCast - 36)) | (1L << (BoolType - 36)) | (1L << (BooleanConstant - 36)) | (1L << (Break - 36)) | (1L << (Callable - 36)) | (1L << (Case - 36)) | (1L << (Class - 36)) | (1L << (Clone - 36)) | (1L << (Continue - 36)) | (1L << (Default - 36)) | (1L << (DoubleCast - 36)) | (1L << (DoubleType - 36)) | (1L << (Echo - 36)) | (1L << (Empty - 36)) | (1L << (Eval - 36)) | (1L << (Exit - 36)) | (1L << (Final - 36)) | (1L << (FloatCast - 36)) | (1L << (Function - 36)) | (1L << (Global - 36)) | (1L << (Import - 36)) | (1L << (Include - 36)) | (1L << (IncludeOnce - 36)) | (1L << (Int16Cast - 36)) | (1L << (Int64Type - 36)) | (1L << (Int8Cast - 36)) | (1L << (Interface - 36)) | (1L << (IntType - 36)) | (1L << (IsSet - 36)) | (1L << (List - 36)) | (1L << (LogicalAnd - 36)) | (1L << (LogicalOr - 36)) | (1L << (LogicalXor - 36)) | (1L << (Namespace - 36)) | (1L << (New - 36)) | (1L << (Null - 36)) | (1L << (ObjectType - 36)) | (1L << (Parent_ - 36)) | (1L << (Partial - 36)) | (1L << (Print - 36)))) != 0) || ((((_la - 100)) & ~0x3f) == 0 && ((1L << (_la - 100)) & ((1L << (Private - 100)) | (1L << (Protected - 100)) | (1L << (Public - 100)) | (1L << (Require - 100)) | (1L << (RequireOnce - 100)) | (1L << (Resource - 100)) | (1L << (Return - 100)) | (1L << (Static - 100)) | (1L << (StringType - 100)) | (1L << (Uint16Cast - 100)) | (1L << (Uint32Cast - 100)) | (1L << (Uint64Cast - 100)) | (1L << (Uint8Cast - 100)) | (1L << (UnicodeCast - 100)) | (1L << (Unset - 100)) | (1L << (Use - 100)) | (1L << (Var - 100)) | (1L << (Yield - 100)) | (1L << (Get - 100)) | (1L << (Set - 100)) | (1L << (Call - 100)) | (1L << (CallStatic - 100)) | (1L << (Constructor - 100)) | (1L << (Destruct - 100)) | (1L << (Wakeup - 100)) | (1L << (Sleep - 100)) | (1L << (Autoload - 100)) | (1L << (IsSet__ - 100)) | (1L << (Unset__ - 100)) | (1L << (ToString__ - 100)) | (1L << (Invoke - 100)) | (1L << (SetState - 100)) | (1L << (Clone__ - 100)) | (1L << (DebugInfo - 100)) | (1L << (Namespace__ - 100)) | (1L << (Class__ - 100)) | (1L << (Traic__ - 100)) | (1L << (Function__ - 100)) | (1L << (Method__ - 100)) | (1L << (Line__ - 100)) | (1L << (File__ - 100)) | (1L << (Dir__ - 100)) | (1L << (Inc - 100)) | (1L << (Dec - 100)))) != 0) || ((((_la - 178)) & ~0x3f) == 0 && ((1L << (_la - 178)) & ((1L << (NamespaceSeparator - 178)) | (1L << (Ampersand - 178)) | (1L << (Bang - 178)) | (1L << (Plus - 178)) | (1L << (Minus - 178)) | (1L << (Tilde - 178)) | (1L << (SuppressWarnings - 178)) | (1L << (Dollar - 178)) | (1L << (OpenRoundBracket - 178)) | (1L << (OpenSquareBracket - 178)) | (1L << (Label - 178)) | (1L << (VarName - 178)) | (1L << (Numeric - 178)) | (1L << (Real - 178)) | (1L << (BackQuoteString - 178)) | (1L << (SingleQuoteString - 178)) | (1L << (DoubleQuoteString - 178)) | (1L << (StartNowDoc - 178)) | (1L << (StartHereDoc - 178)))) != 0)) {
						{
						setState(1244);
						arrayItemList();
						}
					}

					setState(1247);
					match(CloseSquareBracket);
					}
					break;
				default:
					throw new NoViableAltException(this);
				}
				setState(1254);
				_errHandler.sync(this);
				switch ( getInterpreter().adaptivePredict(_input,133,_ctx) ) {
				case 1:
					{
					setState(1250);
					match(OpenSquareBracket);
					setState(1251);
					expression(0);
					setState(1252);
					match(CloseSquareBracket);
					}
					break;
				}
				}
				break;
			case 19:
				{
				_localctx = new SpecialWordExpressionContext(_localctx);
				_ctx = _localctx;
				_prevctx = _localctx;
				setState(1256);
				match(Yield);
				}
				break;
			case 20:
				{
				_localctx = new SpecialWordExpressionContext(_localctx);
				_ctx = _localctx;
				_prevctx = _localctx;
				setState(1257);
				match(List);
				setState(1258);
				match(OpenRoundBracket);
				setState(1259);
				assignmentList();
				setState(1260);
				match(CloseRoundBracket);
				setState(1261);
				match(Eq);
				setState(1262);
				expression(10);
				}
				break;
			case 21:
				{
				_localctx = new SpecialWordExpressionContext(_localctx);
				_ctx = _localctx;
				_prevctx = _localctx;
				setState(1264);
				match(IsSet);
				setState(1265);
				match(OpenRoundBracket);
				setState(1266);
				chainList();
				setState(1267);
				match(CloseRoundBracket);
				}
				break;
			case 22:
				{
				_localctx = new SpecialWordExpressionContext(_localctx);
				_ctx = _localctx;
				_prevctx = _localctx;
				setState(1269);
				match(Empty);
				setState(1270);
				match(OpenRoundBracket);
				setState(1271);
				chain();
				setState(1272);
				match(CloseRoundBracket);
				}
				break;
			case 23:
				{
				_localctx = new SpecialWordExpressionContext(_localctx);
				_ctx = _localctx;
				_prevctx = _localctx;
				setState(1274);
				match(Eval);
				setState(1275);
				match(OpenRoundBracket);
				setState(1276);
				expression(0);
				setState(1277);
				match(CloseRoundBracket);
				}
				break;
			case 24:
				{
				_localctx = new SpecialWordExpressionContext(_localctx);
				_ctx = _localctx;
				_prevctx = _localctx;
				setState(1279);
				match(Exit);
				setState(1283);
				_errHandler.sync(this);
				switch ( getInterpreter().adaptivePredict(_input,134,_ctx) ) {
				case 1:
					{
					setState(1280);
					match(OpenRoundBracket);
					setState(1281);
					match(CloseRoundBracket);
					}
					break;
				case 2:
					{
					setState(1282);
					parenthesis();
					}
					break;
				}
				}
				break;
			case 25:
				{
				_localctx = new SpecialWordExpressionContext(_localctx);
				_ctx = _localctx;
				_prevctx = _localctx;
				setState(1285);
				match(Include);
				setState(1286);
				expression(5);
				}
				break;
			case 26:
				{
				_localctx = new SpecialWordExpressionContext(_localctx);
				_ctx = _localctx;
				_prevctx = _localctx;
				setState(1287);
				match(IncludeOnce);
				setState(1288);
				expression(4);
				}
				break;
			case 27:
				{
				_localctx = new SpecialWordExpressionContext(_localctx);
				_ctx = _localctx;
				_prevctx = _localctx;
				setState(1289);
				match(Require);
				setState(1290);
				expression(3);
				}
				break;
			case 28:
				{
				_localctx = new SpecialWordExpressionContext(_localctx);
				_ctx = _localctx;
				_prevctx = _localctx;
				setState(1291);
				match(RequireOnce);
				setState(1292);
				expression(2);
				}
				break;
			case 29:
				{
				_localctx = new LambdaFunctionExpressionContext(_localctx);
				_ctx = _localctx;
				_prevctx = _localctx;
				setState(1294);
				_la = _input.LA(1);
				if (_la==Static) {
					{
					setState(1293);
					match(Static);
					}
				}

				setState(1296);
				match(Function);
				setState(1298);
				_la = _input.LA(1);
				if (_la==Ampersand) {
					{
					setState(1297);
					match(Ampersand);
					}
				}

				setState(1300);
				match(OpenRoundBracket);
				setState(1301);
				formalParameterList();
				setState(1302);
				match(CloseRoundBracket);
				setState(1304);
				_la = _input.LA(1);
				if (_la==Use) {
					{
					setState(1303);
					lambdaFunctionUseVars();
					}
				}

				setState(1306);
				blockStatement();
				}
				break;
			}
			_ctx.stop = _input.LT(-1);
			setState(1364);
			_errHandler.sync(this);
			_alt = getInterpreter().adaptivePredict(_input,141,_ctx);
			while ( _alt!=2 && _alt!= ATN.INVALID_ALT_NUMBER ) {
				if ( _alt==1 ) {
					if ( _parseListeners!=null ) triggerExitRuleEvent();
					_prevctx = _localctx;
					{
					setState(1362);
					_errHandler.sync(this);
					switch ( getInterpreter().adaptivePredict(_input,140,_ctx) ) {
					case 1:
						{
						_localctx = new BinaryOperatorExpressionContext(new ExpressionContext(_parentctx, _parentState));
						pushNewRecursionContext(_localctx, _startState, RULE_expression);
						setState(1310);
						if (!(precpred(_ctx, 42))) throw new FailedPredicateException(this, "precpred(_ctx, 42)");
						setState(1311);
						match(Pow);
						setState(1312);
						expression(42);
						}
						break;
					case 2:
						{
						_localctx = new BinaryOperatorExpressionContext(new ExpressionContext(_parentctx, _parentState));
						pushNewRecursionContext(_localctx, _startState, RULE_expression);
						setState(1313);
						if (!(precpred(_ctx, 35))) throw new FailedPredicateException(this, "precpred(_ctx, 35)");
						setState(1314);
						_la = _input.LA(1);
						if ( !(((((_la - 188)) & ~0x3f) == 0 && ((1L << (_la - 188)) & ((1L << (Asterisk - 188)) | (1L << (Percent - 188)) | (1L << (Divide - 188)))) != 0)) ) {
						_errHandler.recoverInline(this);
						} else {
							consume();
						}
						setState(1315);
						expression(36);
						}
						break;
					case 3:
						{
						_localctx = new BinaryOperatorExpressionContext(new ExpressionContext(_parentctx, _parentState));
						pushNewRecursionContext(_localctx, _startState, RULE_expression);
						setState(1316);
						if (!(precpred(_ctx, 34))) throw new FailedPredicateException(this, "precpred(_ctx, 34)");
						setState(1317);
						_la = _input.LA(1);
						if ( !(((((_la - 186)) & ~0x3f) == 0 && ((1L << (_la - 186)) & ((1L << (Plus - 186)) | (1L << (Minus - 186)) | (1L << (Dot - 186)))) != 0)) ) {
						_errHandler.recoverInline(this);
						} else {
							consume();
						}
						setState(1318);
						expression(35);
						}
						break;
					case 4:
						{
						_localctx = new BinaryOperatorExpressionContext(new ExpressionContext(_parentctx, _parentState));
						pushNewRecursionContext(_localctx, _startState, RULE_expression);
						setState(1319);
						if (!(precpred(_ctx, 33))) throw new FailedPredicateException(this, "precpred(_ctx, 33)");
						setState(1320);
						_la = _input.LA(1);
						if ( !(_la==ShiftLeft || _la==ShiftRight) ) {
						_errHandler.recoverInline(this);
						} else {
							consume();
						}
						setState(1321);
						expression(34);
						}
						break;
					case 5:
						{
						_localctx = new BinaryOperatorExpressionContext(new ExpressionContext(_parentctx, _parentState));
						pushNewRecursionContext(_localctx, _startState, RULE_expression);
						setState(1322);
						if (!(precpred(_ctx, 32))) throw new FailedPredicateException(this, "precpred(_ctx, 32)");
						setState(1323);
						_la = _input.LA(1);
						if ( !(((((_la - 157)) & ~0x3f) == 0 && ((1L << (_la - 157)) & ((1L << (IsSmallerOrEqual - 157)) | (1L << (IsGreaterOrEqual - 157)) | (1L << (Less - 157)) | (1L << (Greater - 157)))) != 0)) ) {
						_errHandler.recoverInline(this);
						} else {
							consume();
						}
						setState(1324);
						expression(33);
						}
						break;
					case 6:
						{
						_localctx = new BinaryOperatorExpressionContext(new ExpressionContext(_parentctx, _parentState));
						pushNewRecursionContext(_localctx, _startState, RULE_expression);
						setState(1325);
						if (!(precpred(_ctx, 31))) throw new FailedPredicateException(this, "precpred(_ctx, 31)");
						setState(1326);
						_la = _input.LA(1);
						if ( !(((((_la - 153)) & ~0x3f) == 0 && ((1L << (_la - 153)) & ((1L << (IsIdentical - 153)) | (1L << (IsNoidentical - 153)) | (1L << (IsEqual - 153)) | (1L << (IsNotEq - 153)))) != 0)) ) {
						_errHandler.recoverInline(this);
						} else {
							consume();
						}
						setState(1327);
						expression(32);
						}
						break;
					case 7:
						{
						_localctx = new BinaryOperatorExpressionContext(new ExpressionContext(_parentctx, _parentState));
						pushNewRecursionContext(_localctx, _startState, RULE_expression);
						setState(1328);
						if (!(precpred(_ctx, 30))) throw new FailedPredicateException(this, "precpred(_ctx, 30)");
						setState(1329);
						match(Ampersand);
						setState(1330);
						expression(31);
						}
						break;
					case 8:
						{
						_localctx = new BinaryOperatorExpressionContext(new ExpressionContext(_parentctx, _parentState));
						pushNewRecursionContext(_localctx, _startState, RULE_expression);
						setState(1331);
						if (!(precpred(_ctx, 29))) throw new FailedPredicateException(this, "precpred(_ctx, 29)");
						setState(1332);
						match(Caret);
						setState(1333);
						expression(30);
						}
						break;
					case 9:
						{
						_localctx = new BinaryOperatorExpressionContext(new ExpressionContext(_parentctx, _parentState));
						pushNewRecursionContext(_localctx, _startState, RULE_expression);
						setState(1334);
						if (!(precpred(_ctx, 28))) throw new FailedPredicateException(this, "precpred(_ctx, 28)");
						setState(1335);
						match(Pipe);
						setState(1336);
						expression(29);
						}
						break;
					case 10:
						{
						_localctx = new BinaryOperatorExpressionContext(new ExpressionContext(_parentctx, _parentState));
						pushNewRecursionContext(_localctx, _startState, RULE_expression);
						setState(1337);
						if (!(precpred(_ctx, 27))) throw new FailedPredicateException(this, "precpred(_ctx, 27)");
						setState(1338);
						match(BooleanAnd);
						setState(1339);
						expression(28);
						}
						break;
					case 11:
						{
						_localctx = new BinaryOperatorExpressionContext(new ExpressionContext(_parentctx, _parentState));
						pushNewRecursionContext(_localctx, _startState, RULE_expression);
						setState(1340);
						if (!(precpred(_ctx, 26))) throw new FailedPredicateException(this, "precpred(_ctx, 26)");
						setState(1341);
						match(BooleanOr);
						setState(1342);
						expression(27);
						}
						break;
					case 12:
						{
						_localctx = new ConditionalExpressionContext(new ExpressionContext(_parentctx, _parentState));
						pushNewRecursionContext(_localctx, _startState, RULE_expression);
						setState(1343);
						if (!(precpred(_ctx, 25))) throw new FailedPredicateException(this, "precpred(_ctx, 25)");
						setState(1344);
						match(QuestionMark);
						setState(1346);
						_la = _input.LA(1);
						if (((((_la - 36)) & ~0x3f) == 0 && ((1L << (_la - 36)) & ((1L << (Abstract - 36)) | (1L << (Array - 36)) | (1L << (BinaryCast - 36)) | (1L << (BoolType - 36)) | (1L << (BooleanConstant - 36)) | (1L << (Break - 36)) | (1L << (Callable - 36)) | (1L << (Case - 36)) | (1L << (Class - 36)) | (1L << (Clone - 36)) | (1L << (Continue - 36)) | (1L << (Default - 36)) | (1L << (DoubleCast - 36)) | (1L << (DoubleType - 36)) | (1L << (Echo - 36)) | (1L << (Empty - 36)) | (1L << (Eval - 36)) | (1L << (Exit - 36)) | (1L << (Final - 36)) | (1L << (FloatCast - 36)) | (1L << (Function - 36)) | (1L << (Global - 36)) | (1L << (Import - 36)) | (1L << (Include - 36)) | (1L << (IncludeOnce - 36)) | (1L << (Int16Cast - 36)) | (1L << (Int64Type - 36)) | (1L << (Int8Cast - 36)) | (1L << (Interface - 36)) | (1L << (IntType - 36)) | (1L << (IsSet - 36)) | (1L << (List - 36)) | (1L << (LogicalAnd - 36)) | (1L << (LogicalOr - 36)) | (1L << (LogicalXor - 36)) | (1L << (Namespace - 36)) | (1L << (New - 36)) | (1L << (Null - 36)) | (1L << (ObjectType - 36)) | (1L << (Parent_ - 36)) | (1L << (Partial - 36)) | (1L << (Print - 36)))) != 0) || ((((_la - 100)) & ~0x3f) == 0 && ((1L << (_la - 100)) & ((1L << (Private - 100)) | (1L << (Protected - 100)) | (1L << (Public - 100)) | (1L << (Require - 100)) | (1L << (RequireOnce - 100)) | (1L << (Resource - 100)) | (1L << (Return - 100)) | (1L << (Static - 100)) | (1L << (StringType - 100)) | (1L << (Uint16Cast - 100)) | (1L << (Uint32Cast - 100)) | (1L << (Uint64Cast - 100)) | (1L << (Uint8Cast - 100)) | (1L << (UnicodeCast - 100)) | (1L << (Unset - 100)) | (1L << (Use - 100)) | (1L << (Var - 100)) | (1L << (Yield - 100)) | (1L << (Get - 100)) | (1L << (Set - 100)) | (1L << (Call - 100)) | (1L << (CallStatic - 100)) | (1L << (Constructor - 100)) | (1L << (Destruct - 100)) | (1L << (Wakeup - 100)) | (1L << (Sleep - 100)) | (1L << (Autoload - 100)) | (1L << (IsSet__ - 100)) | (1L << (Unset__ - 100)) | (1L << (ToString__ - 100)) | (1L << (Invoke - 100)) | (1L << (SetState - 100)) | (1L << (Clone__ - 100)) | (1L << (DebugInfo - 100)) | (1L << (Namespace__ - 100)) | (1L << (Class__ - 100)) | (1L << (Traic__ - 100)) | (1L << (Function__ - 100)) | (1L << (Method__ - 100)) | (1L << (Line__ - 100)) | (1L << (File__ - 100)) | (1L << (Dir__ - 100)) | (1L << (Inc - 100)) | (1L << (Dec - 100)))) != 0) || ((((_la - 178)) & ~0x3f) == 0 && ((1L << (_la - 178)) & ((1L << (NamespaceSeparator - 178)) | (1L << (Bang - 178)) | (1L << (Plus - 178)) | (1L << (Minus - 178)) | (1L << (Tilde - 178)) | (1L << (SuppressWarnings - 178)) | (1L << (Dollar - 178)) | (1L << (OpenRoundBracket - 178)) | (1L << (OpenSquareBracket - 178)) | (1L << (Label - 178)) | (1L << (VarName - 178)) | (1L << (Numeric - 178)) | (1L << (Real - 178)) | (1L << (BackQuoteString - 178)) | (1L << (SingleQuoteString - 178)) | (1L << (DoubleQuoteString - 178)) | (1L << (StartNowDoc - 178)) | (1L << (StartHereDoc - 178)))) != 0)) {
							{
							setState(1345);
							expression(0);
							}
						}

						setState(1348);
						match(Colon);
						setState(1349);
						expression(26);
						}
						break;
					case 13:
						{
						_localctx = new BinaryOperatorExpressionContext(new ExpressionContext(_parentctx, _parentState));
						pushNewRecursionContext(_localctx, _startState, RULE_expression);
						setState(1350);
						if (!(precpred(_ctx, 21))) throw new FailedPredicateException(this, "precpred(_ctx, 21)");
						setState(1351);
						match(LogicalAnd);
						setState(1352);
						expression(22);
						}
						break;
					case 14:
						{
						_localctx = new BinaryOperatorExpressionContext(new ExpressionContext(_parentctx, _parentState));
						pushNewRecursionContext(_localctx, _startState, RULE_expression);
						setState(1353);
						if (!(precpred(_ctx, 20))) throw new FailedPredicateException(this, "precpred(_ctx, 20)");
						setState(1354);
						match(LogicalXor);
						setState(1355);
						expression(21);
						}
						break;
					case 15:
						{
						_localctx = new BinaryOperatorExpressionContext(new ExpressionContext(_parentctx, _parentState));
						pushNewRecursionContext(_localctx, _startState, RULE_expression);
						setState(1356);
						if (!(precpred(_ctx, 19))) throw new FailedPredicateException(this, "precpred(_ctx, 19)");
						setState(1357);
						match(LogicalOr);
						setState(1358);
						expression(20);
						}
						break;
					case 16:
						{
						_localctx = new InstanceOfExpressionContext(new ExpressionContext(_parentctx, _parentState));
						pushNewRecursionContext(_localctx, _startState, RULE_expression);
						setState(1359);
						if (!(precpred(_ctx, 37))) throw new FailedPredicateException(this, "precpred(_ctx, 37)");
						setState(1360);
						match(InstanceOf);
						setState(1361);
						typeRef();
						}
						break;
					}
					}
				}
				setState(1366);
				_errHandler.sync(this);
				_alt = getInterpreter().adaptivePredict(_input,141,_ctx);
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			unrollRecursionContexts(_parentctx);
		}
		return _localctx;
	}

	public static class NewExprContext extends ParserRuleContext {
		public TerminalNode New() { return getToken(PHPParser.New, 0); }
		public TypeRefContext typeRef() {
			return getRuleContext(TypeRefContext.class,0);
		}
		public ArgumentsContext arguments() {
			return getRuleContext(ArgumentsContext.class,0);
		}
		public NewExprContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_newExpr; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterNewExpr(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitNewExpr(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitNewExpr(this);
			else return visitor.visitChildren(this);
		}
	}

	public final NewExprContext newExpr() throws RecognitionException {
		NewExprContext _localctx = new NewExprContext(_ctx, getState());
		enterRule(_localctx, 176, RULE_newExpr);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(1367);
			match(New);
			setState(1368);
			typeRef();
			setState(1370);
			_errHandler.sync(this);
			switch ( getInterpreter().adaptivePredict(_input,142,_ctx) ) {
			case 1:
				{
				setState(1369);
				arguments();
				}
				break;
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class AssignmentOperatorContext extends ParserRuleContext {
		public TerminalNode Eq() { return getToken(PHPParser.Eq, 0); }
		public AssignmentOperatorContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_assignmentOperator; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterAssignmentOperator(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitAssignmentOperator(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitAssignmentOperator(this);
			else return visitor.visitChildren(this);
		}
	}

	public final AssignmentOperatorContext assignmentOperator() throws RecognitionException {
		AssignmentOperatorContext _localctx = new AssignmentOperatorContext(_ctx, getState());
		enterRule(_localctx, 178, RULE_assignmentOperator);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(1372);
			_la = _input.LA(1);
			if ( !(((((_la - 159)) & ~0x3f) == 0 && ((1L << (_la - 159)) & ((1L << (PlusEqual - 159)) | (1L << (MinusEqual - 159)) | (1L << (MulEqual - 159)) | (1L << (PowEqual - 159)) | (1L << (DivEqual - 159)) | (1L << (Concaequal - 159)) | (1L << (ModEqual - 159)) | (1L << (ShiftLeftEqual - 159)) | (1L << (ShiftRightEqual - 159)) | (1L << (AndEqual - 159)) | (1L << (OrEqual - 159)) | (1L << (XorEqual - 159)) | (1L << (Eq - 159)))) != 0)) ) {
			_errHandler.recoverInline(this);
			} else {
				consume();
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class YieldExpressionContext extends ParserRuleContext {
		public TerminalNode Yield() { return getToken(PHPParser.Yield, 0); }
		public List<ExpressionContext> expression() {
			return getRuleContexts(ExpressionContext.class);
		}
		public ExpressionContext expression(int i) {
			return getRuleContext(ExpressionContext.class,i);
		}
		public YieldExpressionContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_yieldExpression; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterYieldExpression(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitYieldExpression(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitYieldExpression(this);
			else return visitor.visitChildren(this);
		}
	}

	public final YieldExpressionContext yieldExpression() throws RecognitionException {
		YieldExpressionContext _localctx = new YieldExpressionContext(_ctx, getState());
		enterRule(_localctx, 180, RULE_yieldExpression);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(1374);
			match(Yield);
			setState(1375);
			expression(0);
			setState(1378);
			_la = _input.LA(1);
			if (_la==DoubleArrow) {
				{
				setState(1376);
				match(DoubleArrow);
				setState(1377);
				expression(0);
				}
			}

			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class ArrayItemListContext extends ParserRuleContext {
		public List<ArrayItemContext> arrayItem() {
			return getRuleContexts(ArrayItemContext.class);
		}
		public ArrayItemContext arrayItem(int i) {
			return getRuleContext(ArrayItemContext.class,i);
		}
		public ArrayItemListContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_arrayItemList; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterArrayItemList(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitArrayItemList(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitArrayItemList(this);
			else return visitor.visitChildren(this);
		}
	}

	public final ArrayItemListContext arrayItemList() throws RecognitionException {
		ArrayItemListContext _localctx = new ArrayItemListContext(_ctx, getState());
		enterRule(_localctx, 182, RULE_arrayItemList);
		int _la;
		try {
			int _alt;
			enterOuterAlt(_localctx, 1);
			{
			setState(1380);
			arrayItem();
			setState(1385);
			_errHandler.sync(this);
			_alt = getInterpreter().adaptivePredict(_input,144,_ctx);
			while ( _alt!=2 && _alt!= ATN.INVALID_ALT_NUMBER ) {
				if ( _alt==1 ) {
					{
					{
					setState(1381);
					match(Comma);
					setState(1382);
					arrayItem();
					}
					}
				}
				setState(1387);
				_errHandler.sync(this);
				_alt = getInterpreter().adaptivePredict(_input,144,_ctx);
			}
			setState(1389);
			_la = _input.LA(1);
			if (_la==Comma) {
				{
				setState(1388);
				match(Comma);
				}
			}

			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class ArrayItemContext extends ParserRuleContext {
		public List<ExpressionContext> expression() {
			return getRuleContexts(ExpressionContext.class);
		}
		public ExpressionContext expression(int i) {
			return getRuleContext(ExpressionContext.class,i);
		}
		public ChainContext chain() {
			return getRuleContext(ChainContext.class,0);
		}
		public ArrayItemContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_arrayItem; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterArrayItem(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitArrayItem(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitArrayItem(this);
			else return visitor.visitChildren(this);
		}
	}

	public final ArrayItemContext arrayItem() throws RecognitionException {
		ArrayItemContext _localctx = new ArrayItemContext(_ctx, getState());
		enterRule(_localctx, 184, RULE_arrayItem);
		int _la;
		try {
			setState(1403);
			_errHandler.sync(this);
			switch ( getInterpreter().adaptivePredict(_input,148,_ctx) ) {
			case 1:
				enterOuterAlt(_localctx, 1);
				{
				setState(1391);
				expression(0);
				setState(1394);
				_la = _input.LA(1);
				if (_la==DoubleArrow) {
					{
					setState(1392);
					match(DoubleArrow);
					setState(1393);
					expression(0);
					}
				}

				}
				break;
			case 2:
				enterOuterAlt(_localctx, 2);
				{
				setState(1399);
				_la = _input.LA(1);
				if (((((_la - 36)) & ~0x3f) == 0 && ((1L << (_la - 36)) & ((1L << (Abstract - 36)) | (1L << (Array - 36)) | (1L << (BinaryCast - 36)) | (1L << (BoolType - 36)) | (1L << (BooleanConstant - 36)) | (1L << (Break - 36)) | (1L << (Callable - 36)) | (1L << (Case - 36)) | (1L << (Class - 36)) | (1L << (Clone - 36)) | (1L << (Continue - 36)) | (1L << (Default - 36)) | (1L << (DoubleCast - 36)) | (1L << (DoubleType - 36)) | (1L << (Echo - 36)) | (1L << (Empty - 36)) | (1L << (Eval - 36)) | (1L << (Exit - 36)) | (1L << (Final - 36)) | (1L << (FloatCast - 36)) | (1L << (Function - 36)) | (1L << (Global - 36)) | (1L << (Import - 36)) | (1L << (Include - 36)) | (1L << (IncludeOnce - 36)) | (1L << (Int16Cast - 36)) | (1L << (Int64Type - 36)) | (1L << (Int8Cast - 36)) | (1L << (Interface - 36)) | (1L << (IntType - 36)) | (1L << (IsSet - 36)) | (1L << (List - 36)) | (1L << (LogicalAnd - 36)) | (1L << (LogicalOr - 36)) | (1L << (LogicalXor - 36)) | (1L << (Namespace - 36)) | (1L << (New - 36)) | (1L << (Null - 36)) | (1L << (ObjectType - 36)) | (1L << (Parent_ - 36)) | (1L << (Partial - 36)) | (1L << (Print - 36)))) != 0) || ((((_la - 100)) & ~0x3f) == 0 && ((1L << (_la - 100)) & ((1L << (Private - 100)) | (1L << (Protected - 100)) | (1L << (Public - 100)) | (1L << (Require - 100)) | (1L << (RequireOnce - 100)) | (1L << (Resource - 100)) | (1L << (Return - 100)) | (1L << (Static - 100)) | (1L << (StringType - 100)) | (1L << (Uint16Cast - 100)) | (1L << (Uint32Cast - 100)) | (1L << (Uint64Cast - 100)) | (1L << (Uint8Cast - 100)) | (1L << (UnicodeCast - 100)) | (1L << (Unset - 100)) | (1L << (Use - 100)) | (1L << (Var - 100)) | (1L << (Yield - 100)) | (1L << (Get - 100)) | (1L << (Set - 100)) | (1L << (Call - 100)) | (1L << (CallStatic - 100)) | (1L << (Constructor - 100)) | (1L << (Destruct - 100)) | (1L << (Wakeup - 100)) | (1L << (Sleep - 100)) | (1L << (Autoload - 100)) | (1L << (IsSet__ - 100)) | (1L << (Unset__ - 100)) | (1L << (ToString__ - 100)) | (1L << (Invoke - 100)) | (1L << (SetState - 100)) | (1L << (Clone__ - 100)) | (1L << (DebugInfo - 100)) | (1L << (Namespace__ - 100)) | (1L << (Class__ - 100)) | (1L << (Traic__ - 100)) | (1L << (Function__ - 100)) | (1L << (Method__ - 100)) | (1L << (Line__ - 100)) | (1L << (File__ - 100)) | (1L << (Dir__ - 100)) | (1L << (Inc - 100)) | (1L << (Dec - 100)))) != 0) || ((((_la - 178)) & ~0x3f) == 0 && ((1L << (_la - 178)) & ((1L << (NamespaceSeparator - 178)) | (1L << (Bang - 178)) | (1L << (Plus - 178)) | (1L << (Minus - 178)) | (1L << (Tilde - 178)) | (1L << (SuppressWarnings - 178)) | (1L << (Dollar - 178)) | (1L << (OpenRoundBracket - 178)) | (1L << (OpenSquareBracket - 178)) | (1L << (Label - 178)) | (1L << (VarName - 178)) | (1L << (Numeric - 178)) | (1L << (Real - 178)) | (1L << (BackQuoteString - 178)) | (1L << (SingleQuoteString - 178)) | (1L << (DoubleQuoteString - 178)) | (1L << (StartNowDoc - 178)) | (1L << (StartHereDoc - 178)))) != 0)) {
					{
					setState(1396);
					expression(0);
					setState(1397);
					match(DoubleArrow);
					}
				}

				setState(1401);
				match(Ampersand);
				setState(1402);
				chain();
				}
				break;
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class LambdaFunctionUseVarsContext extends ParserRuleContext {
		public TerminalNode Use() { return getToken(PHPParser.Use, 0); }
		public List<LambdaFunctionUseVarContext> lambdaFunctionUseVar() {
			return getRuleContexts(LambdaFunctionUseVarContext.class);
		}
		public LambdaFunctionUseVarContext lambdaFunctionUseVar(int i) {
			return getRuleContext(LambdaFunctionUseVarContext.class,i);
		}
		public LambdaFunctionUseVarsContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_lambdaFunctionUseVars; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterLambdaFunctionUseVars(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitLambdaFunctionUseVars(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitLambdaFunctionUseVars(this);
			else return visitor.visitChildren(this);
		}
	}

	public final LambdaFunctionUseVarsContext lambdaFunctionUseVars() throws RecognitionException {
		LambdaFunctionUseVarsContext _localctx = new LambdaFunctionUseVarsContext(_ctx, getState());
		enterRule(_localctx, 186, RULE_lambdaFunctionUseVars);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(1405);
			match(Use);
			setState(1406);
			match(OpenRoundBracket);
			setState(1407);
			lambdaFunctionUseVar();
			setState(1412);
			_errHandler.sync(this);
			_la = _input.LA(1);
			while (_la==Comma) {
				{
				{
				setState(1408);
				match(Comma);
				setState(1409);
				lambdaFunctionUseVar();
				}
				}
				setState(1414);
				_errHandler.sync(this);
				_la = _input.LA(1);
			}
			setState(1415);
			match(CloseRoundBracket);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class LambdaFunctionUseVarContext extends ParserRuleContext {
		public TerminalNode VarName() { return getToken(PHPParser.VarName, 0); }
		public LambdaFunctionUseVarContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_lambdaFunctionUseVar; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterLambdaFunctionUseVar(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitLambdaFunctionUseVar(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitLambdaFunctionUseVar(this);
			else return visitor.visitChildren(this);
		}
	}

	public final LambdaFunctionUseVarContext lambdaFunctionUseVar() throws RecognitionException {
		LambdaFunctionUseVarContext _localctx = new LambdaFunctionUseVarContext(_ctx, getState());
		enterRule(_localctx, 188, RULE_lambdaFunctionUseVar);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(1418);
			_la = _input.LA(1);
			if (_la==Ampersand) {
				{
				setState(1417);
				match(Ampersand);
				}
			}

			setState(1420);
			match(VarName);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class QualifiedStaticTypeRefContext extends ParserRuleContext {
		public QualifiedNamespaceNameContext qualifiedNamespaceName() {
			return getRuleContext(QualifiedNamespaceNameContext.class,0);
		}
		public GenericDynamicArgsContext genericDynamicArgs() {
			return getRuleContext(GenericDynamicArgsContext.class,0);
		}
		public TerminalNode Static() { return getToken(PHPParser.Static, 0); }
		public QualifiedStaticTypeRefContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_qualifiedStaticTypeRef; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterQualifiedStaticTypeRef(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitQualifiedStaticTypeRef(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitQualifiedStaticTypeRef(this);
			else return visitor.visitChildren(this);
		}
	}

	public final QualifiedStaticTypeRefContext qualifiedStaticTypeRef() throws RecognitionException {
		QualifiedStaticTypeRefContext _localctx = new QualifiedStaticTypeRefContext(_ctx, getState());
		enterRule(_localctx, 190, RULE_qualifiedStaticTypeRef);
		int _la;
		try {
			setState(1427);
			_errHandler.sync(this);
			switch ( getInterpreter().adaptivePredict(_input,152,_ctx) ) {
			case 1:
				enterOuterAlt(_localctx, 1);
				{
				setState(1422);
				qualifiedNamespaceName();
				setState(1424);
				_la = _input.LA(1);
				if (_la==Lgeneric) {
					{
					setState(1423);
					genericDynamicArgs();
					}
				}

				}
				break;
			case 2:
				enterOuterAlt(_localctx, 2);
				{
				setState(1426);
				match(Static);
				}
				break;
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class TypeRefContext extends ParserRuleContext {
		public QualifiedNamespaceNameContext qualifiedNamespaceName() {
			return getRuleContext(QualifiedNamespaceNameContext.class,0);
		}
		public IndirectTypeRefContext indirectTypeRef() {
			return getRuleContext(IndirectTypeRefContext.class,0);
		}
		public GenericDynamicArgsContext genericDynamicArgs() {
			return getRuleContext(GenericDynamicArgsContext.class,0);
		}
		public PrimitiveTypeContext primitiveType() {
			return getRuleContext(PrimitiveTypeContext.class,0);
		}
		public TerminalNode Static() { return getToken(PHPParser.Static, 0); }
		public TypeRefContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_typeRef; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterTypeRef(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitTypeRef(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitTypeRef(this);
			else return visitor.visitChildren(this);
		}
	}

	public final TypeRefContext typeRef() throws RecognitionException {
		TypeRefContext _localctx = new TypeRefContext(_ctx, getState());
		enterRule(_localctx, 192, RULE_typeRef);
		try {
			setState(1438);
			_errHandler.sync(this);
			switch ( getInterpreter().adaptivePredict(_input,155,_ctx) ) {
			case 1:
				enterOuterAlt(_localctx, 1);
				{
				setState(1431);
				_errHandler.sync(this);
				switch ( getInterpreter().adaptivePredict(_input,153,_ctx) ) {
				case 1:
					{
					setState(1429);
					qualifiedNamespaceName();
					}
					break;
				case 2:
					{
					setState(1430);
					indirectTypeRef();
					}
					break;
				}
				setState(1434);
				_errHandler.sync(this);
				switch ( getInterpreter().adaptivePredict(_input,154,_ctx) ) {
				case 1:
					{
					setState(1433);
					genericDynamicArgs();
					}
					break;
				}
				}
				break;
			case 2:
				enterOuterAlt(_localctx, 2);
				{
				setState(1436);
				primitiveType();
				}
				break;
			case 3:
				enterOuterAlt(_localctx, 3);
				{
				setState(1437);
				match(Static);
				}
				break;
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class IndirectTypeRefContext extends ParserRuleContext {
		public ChainBaseContext chainBase() {
			return getRuleContext(ChainBaseContext.class,0);
		}
		public List<KeyedFieldNameContext> keyedFieldName() {
			return getRuleContexts(KeyedFieldNameContext.class);
		}
		public KeyedFieldNameContext keyedFieldName(int i) {
			return getRuleContext(KeyedFieldNameContext.class,i);
		}
		public IndirectTypeRefContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_indirectTypeRef; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterIndirectTypeRef(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitIndirectTypeRef(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitIndirectTypeRef(this);
			else return visitor.visitChildren(this);
		}
	}

	public final IndirectTypeRefContext indirectTypeRef() throws RecognitionException {
		IndirectTypeRefContext _localctx = new IndirectTypeRefContext(_ctx, getState());
		enterRule(_localctx, 194, RULE_indirectTypeRef);
		try {
			int _alt;
			enterOuterAlt(_localctx, 1);
			{
			setState(1440);
			chainBase();
			setState(1445);
			_errHandler.sync(this);
			_alt = getInterpreter().adaptivePredict(_input,156,_ctx);
			while ( _alt!=2 && _alt!= ATN.INVALID_ALT_NUMBER ) {
				if ( _alt==1 ) {
					{
					{
					setState(1441);
					match(ObjectOperator);
					setState(1442);
					keyedFieldName();
					}
					}
				}
				setState(1447);
				_errHandler.sync(this);
				_alt = getInterpreter().adaptivePredict(_input,156,_ctx);
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class QualifiedNamespaceNameContext extends ParserRuleContext {
		public NamespaceNameListContext namespaceNameList() {
			return getRuleContext(NamespaceNameListContext.class,0);
		}
		public TerminalNode Namespace() { return getToken(PHPParser.Namespace, 0); }
		public QualifiedNamespaceNameContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_qualifiedNamespaceName; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterQualifiedNamespaceName(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitQualifiedNamespaceName(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitQualifiedNamespaceName(this);
			else return visitor.visitChildren(this);
		}
	}

	public final QualifiedNamespaceNameContext qualifiedNamespaceName() throws RecognitionException {
		QualifiedNamespaceNameContext _localctx = new QualifiedNamespaceNameContext(_ctx, getState());
		enterRule(_localctx, 196, RULE_qualifiedNamespaceName);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(1449);
			_errHandler.sync(this);
			switch ( getInterpreter().adaptivePredict(_input,157,_ctx) ) {
			case 1:
				{
				setState(1448);
				match(Namespace);
				}
				break;
			}
			setState(1452);
			_la = _input.LA(1);
			if (_la==NamespaceSeparator) {
				{
				setState(1451);
				match(NamespaceSeparator);
				}
			}

			setState(1454);
			namespaceNameList();
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class NamespaceNameListContext extends ParserRuleContext {
		public List<IdentifierContext> identifier() {
			return getRuleContexts(IdentifierContext.class);
		}
		public IdentifierContext identifier(int i) {
			return getRuleContext(IdentifierContext.class,i);
		}
		public NamespaceNameListContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_namespaceNameList; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterNamespaceNameList(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitNamespaceNameList(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitNamespaceNameList(this);
			else return visitor.visitChildren(this);
		}
	}

	public final NamespaceNameListContext namespaceNameList() throws RecognitionException {
		NamespaceNameListContext _localctx = new NamespaceNameListContext(_ctx, getState());
		enterRule(_localctx, 198, RULE_namespaceNameList);
		try {
			int _alt;
			enterOuterAlt(_localctx, 1);
			{
			setState(1456);
			identifier();
			setState(1461);
			_errHandler.sync(this);
			_alt = getInterpreter().adaptivePredict(_input,159,_ctx);
			while ( _alt!=2 && _alt!= ATN.INVALID_ALT_NUMBER ) {
				if ( _alt==1 ) {
					{
					{
					setState(1457);
					match(NamespaceSeparator);
					setState(1458);
					identifier();
					}
					}
				}
				setState(1463);
				_errHandler.sync(this);
				_alt = getInterpreter().adaptivePredict(_input,159,_ctx);
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class QualifiedNamespaceNameListContext extends ParserRuleContext {
		public List<QualifiedNamespaceNameContext> qualifiedNamespaceName() {
			return getRuleContexts(QualifiedNamespaceNameContext.class);
		}
		public QualifiedNamespaceNameContext qualifiedNamespaceName(int i) {
			return getRuleContext(QualifiedNamespaceNameContext.class,i);
		}
		public QualifiedNamespaceNameListContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_qualifiedNamespaceNameList; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterQualifiedNamespaceNameList(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitQualifiedNamespaceNameList(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitQualifiedNamespaceNameList(this);
			else return visitor.visitChildren(this);
		}
	}

	public final QualifiedNamespaceNameListContext qualifiedNamespaceNameList() throws RecognitionException {
		QualifiedNamespaceNameListContext _localctx = new QualifiedNamespaceNameListContext(_ctx, getState());
		enterRule(_localctx, 200, RULE_qualifiedNamespaceNameList);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(1464);
			qualifiedNamespaceName();
			setState(1469);
			_errHandler.sync(this);
			_la = _input.LA(1);
			while (_la==Comma) {
				{
				{
				setState(1465);
				match(Comma);
				setState(1466);
				qualifiedNamespaceName();
				}
				}
				setState(1471);
				_errHandler.sync(this);
				_la = _input.LA(1);
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class ArgumentsContext extends ParserRuleContext {
		public List<ActualArgumentContext> actualArgument() {
			return getRuleContexts(ActualArgumentContext.class);
		}
		public ActualArgumentContext actualArgument(int i) {
			return getRuleContext(ActualArgumentContext.class,i);
		}
		public YieldExpressionContext yieldExpression() {
			return getRuleContext(YieldExpressionContext.class,0);
		}
		public ArgumentsContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_arguments; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterArguments(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitArguments(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitArguments(this);
			else return visitor.visitChildren(this);
		}
	}

	public final ArgumentsContext arguments() throws RecognitionException {
		ArgumentsContext _localctx = new ArgumentsContext(_ctx, getState());
		enterRule(_localctx, 202, RULE_arguments);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(1472);
			match(OpenRoundBracket);
			setState(1482);
			_errHandler.sync(this);
			switch ( getInterpreter().adaptivePredict(_input,162,_ctx) ) {
			case 1:
				{
				setState(1473);
				actualArgument();
				setState(1478);
				_errHandler.sync(this);
				_la = _input.LA(1);
				while (_la==Comma) {
					{
					{
					setState(1474);
					match(Comma);
					setState(1475);
					actualArgument();
					}
					}
					setState(1480);
					_errHandler.sync(this);
					_la = _input.LA(1);
				}
				}
				break;
			case 2:
				{
				setState(1481);
				yieldExpression();
				}
				break;
			}
			setState(1484);
			match(CloseRoundBracket);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class ActualArgumentContext extends ParserRuleContext {
		public ExpressionContext expression() {
			return getRuleContext(ExpressionContext.class,0);
		}
		public ChainContext chain() {
			return getRuleContext(ChainContext.class,0);
		}
		public ActualArgumentContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_actualArgument; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterActualArgument(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitActualArgument(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitActualArgument(this);
			else return visitor.visitChildren(this);
		}
	}

	public final ActualArgumentContext actualArgument() throws RecognitionException {
		ActualArgumentContext _localctx = new ActualArgumentContext(_ctx, getState());
		enterRule(_localctx, 204, RULE_actualArgument);
		int _la;
		try {
			setState(1492);
			switch (_input.LA(1)) {
			case Abstract:
			case Array:
			case BinaryCast:
			case BoolType:
			case BooleanConstant:
			case Break:
			case Callable:
			case Case:
			case Class:
			case Clone:
			case Continue:
			case Default:
			case DoubleCast:
			case DoubleType:
			case Echo:
			case Empty:
			case Eval:
			case Exit:
			case Final:
			case FloatCast:
			case Function:
			case Global:
			case Import:
			case Include:
			case IncludeOnce:
			case Int16Cast:
			case Int64Type:
			case Int8Cast:
			case Interface:
			case IntType:
			case IsSet:
			case List:
			case LogicalAnd:
			case LogicalOr:
			case LogicalXor:
			case Namespace:
			case New:
			case Null:
			case ObjectType:
			case Parent_:
			case Partial:
			case Print:
			case Private:
			case Protected:
			case Public:
			case Require:
			case RequireOnce:
			case Resource:
			case Return:
			case Static:
			case StringType:
			case Uint16Cast:
			case Uint32Cast:
			case Uint64Cast:
			case Uint8Cast:
			case UnicodeCast:
			case Unset:
			case Use:
			case Var:
			case Yield:
			case Get:
			case Set:
			case Call:
			case CallStatic:
			case Constructor:
			case Destruct:
			case Wakeup:
			case Sleep:
			case Autoload:
			case IsSet__:
			case Unset__:
			case ToString__:
			case Invoke:
			case SetState:
			case Clone__:
			case DebugInfo:
			case Namespace__:
			case Class__:
			case Traic__:
			case Function__:
			case Method__:
			case Line__:
			case File__:
			case Dir__:
			case Inc:
			case Dec:
			case NamespaceSeparator:
			case Ellipsis:
			case Bang:
			case Plus:
			case Minus:
			case Tilde:
			case SuppressWarnings:
			case Dollar:
			case OpenRoundBracket:
			case OpenSquareBracket:
			case Label:
			case VarName:
			case Numeric:
			case Real:
			case BackQuoteString:
			case SingleQuoteString:
			case DoubleQuoteString:
			case StartNowDoc:
			case StartHereDoc:
				enterOuterAlt(_localctx, 1);
				{
				setState(1487);
				_la = _input.LA(1);
				if (_la==Ellipsis) {
					{
					setState(1486);
					match(Ellipsis);
					}
				}

				setState(1489);
				expression(0);
				}
				break;
			case Ampersand:
				enterOuterAlt(_localctx, 2);
				{
				setState(1490);
				match(Ampersand);
				setState(1491);
				chain();
				}
				break;
			default:
				throw new NoViableAltException(this);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class ConstantInititalizerContext extends ParserRuleContext {
		public ConstantContext constant() {
			return getRuleContext(ConstantContext.class,0);
		}
		public StringContext string() {
			return getRuleContext(StringContext.class,0);
		}
		public TerminalNode Array() { return getToken(PHPParser.Array, 0); }
		public ConstantArrayItemListContext constantArrayItemList() {
			return getRuleContext(ConstantArrayItemListContext.class,0);
		}
		public ConstantInititalizerContext constantInititalizer() {
			return getRuleContext(ConstantInititalizerContext.class,0);
		}
		public ConstantInititalizerContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_constantInititalizer; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterConstantInititalizer(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitConstantInititalizer(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitConstantInititalizer(this);
			else return visitor.visitChildren(this);
		}
	}

	public final ConstantInititalizerContext constantInititalizer() throws RecognitionException {
		ConstantInititalizerContext _localctx = new ConstantInititalizerContext(_ctx, getState());
		enterRule(_localctx, 206, RULE_constantInititalizer);
		int _la;
		try {
			setState(1515);
			_errHandler.sync(this);
			switch ( getInterpreter().adaptivePredict(_input,169,_ctx) ) {
			case 1:
				enterOuterAlt(_localctx, 1);
				{
				setState(1494);
				constant();
				}
				break;
			case 2:
				enterOuterAlt(_localctx, 2);
				{
				setState(1495);
				string();
				}
				break;
			case 3:
				enterOuterAlt(_localctx, 3);
				{
				setState(1496);
				match(Array);
				setState(1497);
				match(OpenRoundBracket);
				setState(1502);
				_la = _input.LA(1);
				if (((((_la - 36)) & ~0x3f) == 0 && ((1L << (_la - 36)) & ((1L << (Abstract - 36)) | (1L << (Array - 36)) | (1L << (BinaryCast - 36)) | (1L << (BoolType - 36)) | (1L << (BooleanConstant - 36)) | (1L << (Break - 36)) | (1L << (Callable - 36)) | (1L << (Case - 36)) | (1L << (Class - 36)) | (1L << (Clone - 36)) | (1L << (Continue - 36)) | (1L << (Default - 36)) | (1L << (DoubleCast - 36)) | (1L << (DoubleType - 36)) | (1L << (Echo - 36)) | (1L << (Empty - 36)) | (1L << (Eval - 36)) | (1L << (Final - 36)) | (1L << (FloatCast - 36)) | (1L << (Function - 36)) | (1L << (Global - 36)) | (1L << (Import - 36)) | (1L << (Int16Cast - 36)) | (1L << (Int64Type - 36)) | (1L << (Int8Cast - 36)) | (1L << (Interface - 36)) | (1L << (IntType - 36)) | (1L << (IsSet - 36)) | (1L << (List - 36)) | (1L << (LogicalAnd - 36)) | (1L << (LogicalOr - 36)) | (1L << (LogicalXor - 36)) | (1L << (Namespace - 36)) | (1L << (New - 36)) | (1L << (Null - 36)) | (1L << (ObjectType - 36)) | (1L << (Parent_ - 36)) | (1L << (Partial - 36)))) != 0) || ((((_la - 100)) & ~0x3f) == 0 && ((1L << (_la - 100)) & ((1L << (Private - 100)) | (1L << (Protected - 100)) | (1L << (Public - 100)) | (1L << (Resource - 100)) | (1L << (Return - 100)) | (1L << (Static - 100)) | (1L << (StringType - 100)) | (1L << (Uint16Cast - 100)) | (1L << (Uint32Cast - 100)) | (1L << (Uint64Cast - 100)) | (1L << (Uint8Cast - 100)) | (1L << (UnicodeCast - 100)) | (1L << (Unset - 100)) | (1L << (Use - 100)) | (1L << (Var - 100)) | (1L << (Get - 100)) | (1L << (Set - 100)) | (1L << (Call - 100)) | (1L << (CallStatic - 100)) | (1L << (Constructor - 100)) | (1L << (Destruct - 100)) | (1L << (Wakeup - 100)) | (1L << (Sleep - 100)) | (1L << (Autoload - 100)) | (1L << (IsSet__ - 100)) | (1L << (Unset__ - 100)) | (1L << (ToString__ - 100)) | (1L << (Invoke - 100)) | (1L << (SetState - 100)) | (1L << (Clone__ - 100)) | (1L << (DebugInfo - 100)) | (1L << (Namespace__ - 100)) | (1L << (Class__ - 100)) | (1L << (Traic__ - 100)) | (1L << (Function__ - 100)) | (1L << (Method__ - 100)) | (1L << (Line__ - 100)) | (1L << (File__ - 100)) | (1L << (Dir__ - 100)))) != 0) || ((((_la - 178)) & ~0x3f) == 0 && ((1L << (_la - 178)) & ((1L << (NamespaceSeparator - 178)) | (1L << (Plus - 178)) | (1L << (Minus - 178)) | (1L << (Dollar - 178)) | (1L << (OpenSquareBracket - 178)) | (1L << (Label - 178)) | (1L << (VarName - 178)) | (1L << (Numeric - 178)) | (1L << (Real - 178)) | (1L << (SingleQuoteString - 178)) | (1L << (DoubleQuoteString - 178)) | (1L << (StartNowDoc - 178)) | (1L << (StartHereDoc - 178)))) != 0)) {
					{
					setState(1498);
					constantArrayItemList();
					setState(1500);
					_la = _input.LA(1);
					if (_la==Comma) {
						{
						setState(1499);
						match(Comma);
						}
					}

					}
				}

				setState(1504);
				match(CloseRoundBracket);
				}
				break;
			case 4:
				enterOuterAlt(_localctx, 4);
				{
				setState(1505);
				match(OpenSquareBracket);
				setState(1510);
				_la = _input.LA(1);
				if (((((_la - 36)) & ~0x3f) == 0 && ((1L << (_la - 36)) & ((1L << (Abstract - 36)) | (1L << (Array - 36)) | (1L << (BinaryCast - 36)) | (1L << (BoolType - 36)) | (1L << (BooleanConstant - 36)) | (1L << (Break - 36)) | (1L << (Callable - 36)) | (1L << (Case - 36)) | (1L << (Class - 36)) | (1L << (Clone - 36)) | (1L << (Continue - 36)) | (1L << (Default - 36)) | (1L << (DoubleCast - 36)) | (1L << (DoubleType - 36)) | (1L << (Echo - 36)) | (1L << (Empty - 36)) | (1L << (Eval - 36)) | (1L << (Final - 36)) | (1L << (FloatCast - 36)) | (1L << (Function - 36)) | (1L << (Global - 36)) | (1L << (Import - 36)) | (1L << (Int16Cast - 36)) | (1L << (Int64Type - 36)) | (1L << (Int8Cast - 36)) | (1L << (Interface - 36)) | (1L << (IntType - 36)) | (1L << (IsSet - 36)) | (1L << (List - 36)) | (1L << (LogicalAnd - 36)) | (1L << (LogicalOr - 36)) | (1L << (LogicalXor - 36)) | (1L << (Namespace - 36)) | (1L << (New - 36)) | (1L << (Null - 36)) | (1L << (ObjectType - 36)) | (1L << (Parent_ - 36)) | (1L << (Partial - 36)))) != 0) || ((((_la - 100)) & ~0x3f) == 0 && ((1L << (_la - 100)) & ((1L << (Private - 100)) | (1L << (Protected - 100)) | (1L << (Public - 100)) | (1L << (Resource - 100)) | (1L << (Return - 100)) | (1L << (Static - 100)) | (1L << (StringType - 100)) | (1L << (Uint16Cast - 100)) | (1L << (Uint32Cast - 100)) | (1L << (Uint64Cast - 100)) | (1L << (Uint8Cast - 100)) | (1L << (UnicodeCast - 100)) | (1L << (Unset - 100)) | (1L << (Use - 100)) | (1L << (Var - 100)) | (1L << (Get - 100)) | (1L << (Set - 100)) | (1L << (Call - 100)) | (1L << (CallStatic - 100)) | (1L << (Constructor - 100)) | (1L << (Destruct - 100)) | (1L << (Wakeup - 100)) | (1L << (Sleep - 100)) | (1L << (Autoload - 100)) | (1L << (IsSet__ - 100)) | (1L << (Unset__ - 100)) | (1L << (ToString__ - 100)) | (1L << (Invoke - 100)) | (1L << (SetState - 100)) | (1L << (Clone__ - 100)) | (1L << (DebugInfo - 100)) | (1L << (Namespace__ - 100)) | (1L << (Class__ - 100)) | (1L << (Traic__ - 100)) | (1L << (Function__ - 100)) | (1L << (Method__ - 100)) | (1L << (Line__ - 100)) | (1L << (File__ - 100)) | (1L << (Dir__ - 100)))) != 0) || ((((_la - 178)) & ~0x3f) == 0 && ((1L << (_la - 178)) & ((1L << (NamespaceSeparator - 178)) | (1L << (Plus - 178)) | (1L << (Minus - 178)) | (1L << (Dollar - 178)) | (1L << (OpenSquareBracket - 178)) | (1L << (Label - 178)) | (1L << (VarName - 178)) | (1L << (Numeric - 178)) | (1L << (Real - 178)) | (1L << (SingleQuoteString - 178)) | (1L << (DoubleQuoteString - 178)) | (1L << (StartNowDoc - 178)) | (1L << (StartHereDoc - 178)))) != 0)) {
					{
					setState(1506);
					constantArrayItemList();
					setState(1508);
					_la = _input.LA(1);
					if (_la==Comma) {
						{
						setState(1507);
						match(Comma);
						}
					}

					}
				}

				setState(1512);
				match(CloseSquareBracket);
				}
				break;
			case 5:
				enterOuterAlt(_localctx, 5);
				{
				setState(1513);
				_la = _input.LA(1);
				if ( !(_la==Plus || _la==Minus) ) {
				_errHandler.recoverInline(this);
				} else {
					consume();
				}
				setState(1514);
				constantInititalizer();
				}
				break;
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class ConstantArrayItemListContext extends ParserRuleContext {
		public List<ConstantArrayItemContext> constantArrayItem() {
			return getRuleContexts(ConstantArrayItemContext.class);
		}
		public ConstantArrayItemContext constantArrayItem(int i) {
			return getRuleContext(ConstantArrayItemContext.class,i);
		}
		public ConstantArrayItemListContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_constantArrayItemList; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterConstantArrayItemList(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitConstantArrayItemList(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitConstantArrayItemList(this);
			else return visitor.visitChildren(this);
		}
	}

	public final ConstantArrayItemListContext constantArrayItemList() throws RecognitionException {
		ConstantArrayItemListContext _localctx = new ConstantArrayItemListContext(_ctx, getState());
		enterRule(_localctx, 208, RULE_constantArrayItemList);
		try {
			int _alt;
			enterOuterAlt(_localctx, 1);
			{
			setState(1517);
			constantArrayItem();
			setState(1522);
			_errHandler.sync(this);
			_alt = getInterpreter().adaptivePredict(_input,170,_ctx);
			while ( _alt!=2 && _alt!= ATN.INVALID_ALT_NUMBER ) {
				if ( _alt==1 ) {
					{
					{
					setState(1518);
					match(Comma);
					setState(1519);
					constantArrayItem();
					}
					}
				}
				setState(1524);
				_errHandler.sync(this);
				_alt = getInterpreter().adaptivePredict(_input,170,_ctx);
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class ConstantArrayItemContext extends ParserRuleContext {
		public List<ConstantInititalizerContext> constantInititalizer() {
			return getRuleContexts(ConstantInititalizerContext.class);
		}
		public ConstantInititalizerContext constantInititalizer(int i) {
			return getRuleContext(ConstantInititalizerContext.class,i);
		}
		public ConstantArrayItemContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_constantArrayItem; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterConstantArrayItem(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitConstantArrayItem(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitConstantArrayItem(this);
			else return visitor.visitChildren(this);
		}
	}

	public final ConstantArrayItemContext constantArrayItem() throws RecognitionException {
		ConstantArrayItemContext _localctx = new ConstantArrayItemContext(_ctx, getState());
		enterRule(_localctx, 210, RULE_constantArrayItem);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(1525);
			constantInititalizer();
			setState(1528);
			_la = _input.LA(1);
			if (_la==DoubleArrow) {
				{
				setState(1526);
				match(DoubleArrow);
				setState(1527);
				constantInititalizer();
				}
			}

			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class ConstantContext extends ParserRuleContext {
		public LiteralConstantContext literalConstant() {
			return getRuleContext(LiteralConstantContext.class,0);
		}
		public MagicConstantContext magicConstant() {
			return getRuleContext(MagicConstantContext.class,0);
		}
		public ClassConstantContext classConstant() {
			return getRuleContext(ClassConstantContext.class,0);
		}
		public QualifiedNamespaceNameContext qualifiedNamespaceName() {
			return getRuleContext(QualifiedNamespaceNameContext.class,0);
		}
		public TerminalNode Null() { return getToken(PHPParser.Null, 0); }
		public ConstantContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_constant; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterConstant(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitConstant(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitConstant(this);
			else return visitor.visitChildren(this);
		}
	}

	public final ConstantContext constant() throws RecognitionException {
		ConstantContext _localctx = new ConstantContext(_ctx, getState());
		enterRule(_localctx, 212, RULE_constant);
		try {
			setState(1535);
			_errHandler.sync(this);
			switch ( getInterpreter().adaptivePredict(_input,172,_ctx) ) {
			case 1:
				enterOuterAlt(_localctx, 1);
				{
				setState(1530);
				literalConstant();
				}
				break;
			case 2:
				enterOuterAlt(_localctx, 2);
				{
				setState(1531);
				magicConstant();
				}
				break;
			case 3:
				enterOuterAlt(_localctx, 3);
				{
				setState(1532);
				classConstant();
				}
				break;
			case 4:
				enterOuterAlt(_localctx, 4);
				{
				setState(1533);
				qualifiedNamespaceName();
				}
				break;
			case 5:
				enterOuterAlt(_localctx, 5);
				{
				setState(1534);
				match(Null);
				}
				break;
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class LiteralConstantContext extends ParserRuleContext {
		public TerminalNode Numeric() { return getToken(PHPParser.Numeric, 0); }
		public TerminalNode Real() { return getToken(PHPParser.Real, 0); }
		public TerminalNode BooleanConstant() { return getToken(PHPParser.BooleanConstant, 0); }
		public StringConstantContext stringConstant() {
			return getRuleContext(StringConstantContext.class,0);
		}
		public LiteralConstantContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_literalConstant; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterLiteralConstant(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitLiteralConstant(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitLiteralConstant(this);
			else return visitor.visitChildren(this);
		}
	}

	public final LiteralConstantContext literalConstant() throws RecognitionException {
		LiteralConstantContext _localctx = new LiteralConstantContext(_ctx, getState());
		enterRule(_localctx, 214, RULE_literalConstant);
		try {
			setState(1541);
			switch (_input.LA(1)) {
			case Numeric:
				enterOuterAlt(_localctx, 1);
				{
				setState(1537);
				match(Numeric);
				}
				break;
			case Real:
				enterOuterAlt(_localctx, 2);
				{
				setState(1538);
				match(Real);
				}
				break;
			case BooleanConstant:
				enterOuterAlt(_localctx, 3);
				{
				setState(1539);
				match(BooleanConstant);
				}
				break;
			case Label:
				enterOuterAlt(_localctx, 4);
				{
				setState(1540);
				stringConstant();
				}
				break;
			default:
				throw new NoViableAltException(this);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class ClassConstantContext extends ParserRuleContext {
		public TerminalNode Class() { return getToken(PHPParser.Class, 0); }
		public TerminalNode Parent_() { return getToken(PHPParser.Parent_, 0); }
		public IdentifierContext identifier() {
			return getRuleContext(IdentifierContext.class,0);
		}
		public TerminalNode Constructor() { return getToken(PHPParser.Constructor, 0); }
		public TerminalNode Get() { return getToken(PHPParser.Get, 0); }
		public TerminalNode Set() { return getToken(PHPParser.Set, 0); }
		public QualifiedStaticTypeRefContext qualifiedStaticTypeRef() {
			return getRuleContext(QualifiedStaticTypeRefContext.class,0);
		}
		public KeyedVariableContext keyedVariable() {
			return getRuleContext(KeyedVariableContext.class,0);
		}
		public ClassConstantContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_classConstant; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterClassConstant(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitClassConstant(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitClassConstant(this);
			else return visitor.visitChildren(this);
		}
	}

	public final ClassConstantContext classConstant() throws RecognitionException {
		ClassConstantContext _localctx = new ClassConstantContext(_ctx, getState());
		enterRule(_localctx, 216, RULE_classConstant);
		int _la;
		try {
			setState(1558);
			_errHandler.sync(this);
			switch ( getInterpreter().adaptivePredict(_input,176,_ctx) ) {
			case 1:
				enterOuterAlt(_localctx, 1);
				{
				setState(1543);
				_la = _input.LA(1);
				if ( !(_la==Class || _la==Parent_) ) {
				_errHandler.recoverInline(this);
				} else {
					consume();
				}
				setState(1544);
				match(DoubleColon);
				setState(1549);
				_errHandler.sync(this);
				switch ( getInterpreter().adaptivePredict(_input,174,_ctx) ) {
				case 1:
					{
					setState(1545);
					identifier();
					}
					break;
				case 2:
					{
					setState(1546);
					match(Constructor);
					}
					break;
				case 3:
					{
					setState(1547);
					match(Get);
					}
					break;
				case 4:
					{
					setState(1548);
					match(Set);
					}
					break;
				}
				}
				break;
			case 2:
				enterOuterAlt(_localctx, 2);
				{
				setState(1553);
				switch (_input.LA(1)) {
				case Abstract:
				case Array:
				case BinaryCast:
				case BoolType:
				case Break:
				case Callable:
				case Case:
				case Class:
				case Clone:
				case Continue:
				case Default:
				case DoubleCast:
				case DoubleType:
				case Echo:
				case Empty:
				case Eval:
				case Final:
				case FloatCast:
				case Function:
				case Global:
				case Import:
				case Int16Cast:
				case Int64Type:
				case Int8Cast:
				case Interface:
				case IntType:
				case IsSet:
				case List:
				case LogicalAnd:
				case LogicalOr:
				case LogicalXor:
				case Namespace:
				case New:
				case Null:
				case ObjectType:
				case Parent_:
				case Partial:
				case Private:
				case Protected:
				case Public:
				case Resource:
				case Return:
				case Static:
				case StringType:
				case Uint16Cast:
				case Uint32Cast:
				case Uint64Cast:
				case Uint8Cast:
				case UnicodeCast:
				case Unset:
				case Use:
				case Var:
				case Get:
				case Set:
				case Call:
				case CallStatic:
				case Constructor:
				case Destruct:
				case Wakeup:
				case Sleep:
				case Autoload:
				case IsSet__:
				case Unset__:
				case ToString__:
				case Invoke:
				case SetState:
				case Clone__:
				case DebugInfo:
				case Namespace__:
				case Class__:
				case Traic__:
				case Function__:
				case Method__:
				case Line__:
				case File__:
				case Dir__:
				case NamespaceSeparator:
				case Label:
					{
					setState(1551);
					qualifiedStaticTypeRef();
					}
					break;
				case Dollar:
				case VarName:
					{
					setState(1552);
					keyedVariable();
					}
					break;
				default:
					throw new NoViableAltException(this);
				}
				setState(1555);
				match(DoubleColon);
				setState(1556);
				identifier();
				}
				break;
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class StringConstantContext extends ParserRuleContext {
		public TerminalNode Label() { return getToken(PHPParser.Label, 0); }
		public StringConstantContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_stringConstant; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterStringConstant(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitStringConstant(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitStringConstant(this);
			else return visitor.visitChildren(this);
		}
	}

	public final StringConstantContext stringConstant() throws RecognitionException {
		StringConstantContext _localctx = new StringConstantContext(_ctx, getState());
		enterRule(_localctx, 218, RULE_stringConstant);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(1560);
			match(Label);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class StringContext extends ParserRuleContext {
		public TerminalNode StartHereDoc() { return getToken(PHPParser.StartHereDoc, 0); }
		public List<TerminalNode> HereDocText() { return getTokens(PHPParser.HereDocText); }
		public TerminalNode HereDocText(int i) {
			return getToken(PHPParser.HereDocText, i);
		}
		public TerminalNode StartNowDoc() { return getToken(PHPParser.StartNowDoc, 0); }
		public TerminalNode DoubleQuoteString() { return getToken(PHPParser.DoubleQuoteString, 0); }
		public TerminalNode SingleQuoteString() { return getToken(PHPParser.SingleQuoteString, 0); }
		public StringContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_string; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterString(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitString(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitString(this);
			else return visitor.visitChildren(this);
		}
	}

	public final StringContext string() throws RecognitionException {
		StringContext _localctx = new StringContext(_ctx, getState());
		enterRule(_localctx, 220, RULE_string);
		try {
			int _alt;
			setState(1576);
			switch (_input.LA(1)) {
			case StartHereDoc:
				enterOuterAlt(_localctx, 1);
				{
				setState(1562);
				match(StartHereDoc);
				setState(1564);
				_errHandler.sync(this);
				_alt = 1;
				do {
					switch (_alt) {
					case 1:
						{
						{
						setState(1563);
						match(HereDocText);
						}
						}
						break;
					default:
						throw new NoViableAltException(this);
					}
					setState(1566);
					_errHandler.sync(this);
					_alt = getInterpreter().adaptivePredict(_input,177,_ctx);
				} while ( _alt!=2 && _alt!= ATN.INVALID_ALT_NUMBER );
				}
				break;
			case StartNowDoc:
				enterOuterAlt(_localctx, 2);
				{
				setState(1568);
				match(StartNowDoc);
				setState(1570);
				_errHandler.sync(this);
				_alt = 1;
				do {
					switch (_alt) {
					case 1:
						{
						{
						setState(1569);
						match(HereDocText);
						}
						}
						break;
					default:
						throw new NoViableAltException(this);
					}
					setState(1572);
					_errHandler.sync(this);
					_alt = getInterpreter().adaptivePredict(_input,178,_ctx);
				} while ( _alt!=2 && _alt!= ATN.INVALID_ALT_NUMBER );
				}
				break;
			case DoubleQuoteString:
				enterOuterAlt(_localctx, 3);
				{
				setState(1574);
				match(DoubleQuoteString);
				}
				break;
			case SingleQuoteString:
				enterOuterAlt(_localctx, 4);
				{
				setState(1575);
				match(SingleQuoteString);
				}
				break;
			default:
				throw new NoViableAltException(this);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class ChainListContext extends ParserRuleContext {
		public List<ChainContext> chain() {
			return getRuleContexts(ChainContext.class);
		}
		public ChainContext chain(int i) {
			return getRuleContext(ChainContext.class,i);
		}
		public ChainListContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_chainList; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterChainList(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitChainList(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitChainList(this);
			else return visitor.visitChildren(this);
		}
	}

	public final ChainListContext chainList() throws RecognitionException {
		ChainListContext _localctx = new ChainListContext(_ctx, getState());
		enterRule(_localctx, 222, RULE_chainList);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(1578);
			chain();
			setState(1583);
			_errHandler.sync(this);
			_la = _input.LA(1);
			while (_la==Comma) {
				{
				{
				setState(1579);
				match(Comma);
				setState(1580);
				chain();
				}
				}
				setState(1585);
				_errHandler.sync(this);
				_la = _input.LA(1);
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class ChainContext extends ParserRuleContext {
		public ChainBaseContext chainBase() {
			return getRuleContext(ChainBaseContext.class,0);
		}
		public FunctionCallContext functionCall() {
			return getRuleContext(FunctionCallContext.class,0);
		}
		public NewExprContext newExpr() {
			return getRuleContext(NewExprContext.class,0);
		}
		public List<MemberAccessContext> memberAccess() {
			return getRuleContexts(MemberAccessContext.class);
		}
		public MemberAccessContext memberAccess(int i) {
			return getRuleContext(MemberAccessContext.class,i);
		}
		public ChainContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_chain; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterChain(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitChain(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitChain(this);
			else return visitor.visitChildren(this);
		}
	}

	public final ChainContext chain() throws RecognitionException {
		ChainContext _localctx = new ChainContext(_ctx, getState());
		enterRule(_localctx, 224, RULE_chain);
		try {
			int _alt;
			enterOuterAlt(_localctx, 1);
			{
			setState(1592);
			_errHandler.sync(this);
			switch ( getInterpreter().adaptivePredict(_input,181,_ctx) ) {
			case 1:
				{
				setState(1586);
				chainBase();
				}
				break;
			case 2:
				{
				setState(1587);
				functionCall();
				}
				break;
			case 3:
				{
				setState(1588);
				match(OpenRoundBracket);
				setState(1589);
				newExpr();
				setState(1590);
				match(CloseRoundBracket);
				}
				break;
			}
			setState(1597);
			_errHandler.sync(this);
			_alt = getInterpreter().adaptivePredict(_input,182,_ctx);
			while ( _alt!=2 && _alt!= ATN.INVALID_ALT_NUMBER ) {
				if ( _alt==1 ) {
					{
					{
					setState(1594);
					memberAccess();
					}
					}
				}
				setState(1599);
				_errHandler.sync(this);
				_alt = getInterpreter().adaptivePredict(_input,182,_ctx);
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class MemberAccessContext extends ParserRuleContext {
		public KeyedFieldNameContext keyedFieldName() {
			return getRuleContext(KeyedFieldNameContext.class,0);
		}
		public ActualArgumentsContext actualArguments() {
			return getRuleContext(ActualArgumentsContext.class,0);
		}
		public MemberAccessContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_memberAccess; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterMemberAccess(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitMemberAccess(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitMemberAccess(this);
			else return visitor.visitChildren(this);
		}
	}

	public final MemberAccessContext memberAccess() throws RecognitionException {
		MemberAccessContext _localctx = new MemberAccessContext(_ctx, getState());
		enterRule(_localctx, 226, RULE_memberAccess);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(1600);
			match(ObjectOperator);
			setState(1601);
			keyedFieldName();
			setState(1603);
			_errHandler.sync(this);
			switch ( getInterpreter().adaptivePredict(_input,183,_ctx) ) {
			case 1:
				{
				setState(1602);
				actualArguments();
				}
				break;
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class FunctionCallContext extends ParserRuleContext {
		public FunctionCallNameContext functionCallName() {
			return getRuleContext(FunctionCallNameContext.class,0);
		}
		public ActualArgumentsContext actualArguments() {
			return getRuleContext(ActualArgumentsContext.class,0);
		}
		public FunctionCallContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_functionCall; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterFunctionCall(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitFunctionCall(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitFunctionCall(this);
			else return visitor.visitChildren(this);
		}
	}

	public final FunctionCallContext functionCall() throws RecognitionException {
		FunctionCallContext _localctx = new FunctionCallContext(_ctx, getState());
		enterRule(_localctx, 228, RULE_functionCall);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(1605);
			functionCallName();
			setState(1606);
			actualArguments();
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class FunctionCallNameContext extends ParserRuleContext {
		public QualifiedNamespaceNameContext qualifiedNamespaceName() {
			return getRuleContext(QualifiedNamespaceNameContext.class,0);
		}
		public ClassConstantContext classConstant() {
			return getRuleContext(ClassConstantContext.class,0);
		}
		public ChainBaseContext chainBase() {
			return getRuleContext(ChainBaseContext.class,0);
		}
		public FunctionCallNameContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_functionCallName; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterFunctionCallName(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitFunctionCallName(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitFunctionCallName(this);
			else return visitor.visitChildren(this);
		}
	}

	public final FunctionCallNameContext functionCallName() throws RecognitionException {
		FunctionCallNameContext _localctx = new FunctionCallNameContext(_ctx, getState());
		enterRule(_localctx, 230, RULE_functionCallName);
		try {
			setState(1611);
			_errHandler.sync(this);
			switch ( getInterpreter().adaptivePredict(_input,184,_ctx) ) {
			case 1:
				enterOuterAlt(_localctx, 1);
				{
				setState(1608);
				qualifiedNamespaceName();
				}
				break;
			case 2:
				enterOuterAlt(_localctx, 2);
				{
				setState(1609);
				classConstant();
				}
				break;
			case 3:
				enterOuterAlt(_localctx, 3);
				{
				setState(1610);
				chainBase();
				}
				break;
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class ActualArgumentsContext extends ParserRuleContext {
		public ArgumentsContext arguments() {
			return getRuleContext(ArgumentsContext.class,0);
		}
		public GenericDynamicArgsContext genericDynamicArgs() {
			return getRuleContext(GenericDynamicArgsContext.class,0);
		}
		public List<SquareCurlyExpressionContext> squareCurlyExpression() {
			return getRuleContexts(SquareCurlyExpressionContext.class);
		}
		public SquareCurlyExpressionContext squareCurlyExpression(int i) {
			return getRuleContext(SquareCurlyExpressionContext.class,i);
		}
		public ActualArgumentsContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_actualArguments; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterActualArguments(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitActualArguments(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitActualArguments(this);
			else return visitor.visitChildren(this);
		}
	}

	public final ActualArgumentsContext actualArguments() throws RecognitionException {
		ActualArgumentsContext _localctx = new ActualArgumentsContext(_ctx, getState());
		enterRule(_localctx, 232, RULE_actualArguments);
		int _la;
		try {
			int _alt;
			enterOuterAlt(_localctx, 1);
			{
			setState(1614);
			_la = _input.LA(1);
			if (_la==Lgeneric) {
				{
				setState(1613);
				genericDynamicArgs();
				}
			}

			setState(1616);
			arguments();
			setState(1620);
			_errHandler.sync(this);
			_alt = getInterpreter().adaptivePredict(_input,186,_ctx);
			while ( _alt!=2 && _alt!= ATN.INVALID_ALT_NUMBER ) {
				if ( _alt==1 ) {
					{
					{
					setState(1617);
					squareCurlyExpression();
					}
					}
				}
				setState(1622);
				_errHandler.sync(this);
				_alt = getInterpreter().adaptivePredict(_input,186,_ctx);
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class ChainBaseContext extends ParserRuleContext {
		public List<KeyedVariableContext> keyedVariable() {
			return getRuleContexts(KeyedVariableContext.class);
		}
		public KeyedVariableContext keyedVariable(int i) {
			return getRuleContext(KeyedVariableContext.class,i);
		}
		public QualifiedStaticTypeRefContext qualifiedStaticTypeRef() {
			return getRuleContext(QualifiedStaticTypeRefContext.class,0);
		}
		public ChainBaseContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_chainBase; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterChainBase(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitChainBase(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitChainBase(this);
			else return visitor.visitChildren(this);
		}
	}

	public final ChainBaseContext chainBase() throws RecognitionException {
		ChainBaseContext _localctx = new ChainBaseContext(_ctx, getState());
		enterRule(_localctx, 234, RULE_chainBase);
		try {
			setState(1632);
			switch (_input.LA(1)) {
			case Dollar:
			case VarName:
				enterOuterAlt(_localctx, 1);
				{
				setState(1623);
				keyedVariable();
				setState(1626);
				_errHandler.sync(this);
				switch ( getInterpreter().adaptivePredict(_input,187,_ctx) ) {
				case 1:
					{
					setState(1624);
					match(DoubleColon);
					setState(1625);
					keyedVariable();
					}
					break;
				}
				}
				break;
			case Abstract:
			case Array:
			case BinaryCast:
			case BoolType:
			case Break:
			case Callable:
			case Case:
			case Class:
			case Clone:
			case Continue:
			case Default:
			case DoubleCast:
			case DoubleType:
			case Echo:
			case Empty:
			case Eval:
			case Final:
			case FloatCast:
			case Function:
			case Global:
			case Import:
			case Int16Cast:
			case Int64Type:
			case Int8Cast:
			case Interface:
			case IntType:
			case IsSet:
			case List:
			case LogicalAnd:
			case LogicalOr:
			case LogicalXor:
			case Namespace:
			case New:
			case Null:
			case ObjectType:
			case Parent_:
			case Partial:
			case Private:
			case Protected:
			case Public:
			case Resource:
			case Return:
			case Static:
			case StringType:
			case Uint16Cast:
			case Uint32Cast:
			case Uint64Cast:
			case Uint8Cast:
			case UnicodeCast:
			case Unset:
			case Use:
			case Var:
			case Get:
			case Set:
			case Call:
			case CallStatic:
			case Constructor:
			case Destruct:
			case Wakeup:
			case Sleep:
			case Autoload:
			case IsSet__:
			case Unset__:
			case ToString__:
			case Invoke:
			case SetState:
			case Clone__:
			case DebugInfo:
			case Namespace__:
			case Class__:
			case Traic__:
			case Function__:
			case Method__:
			case Line__:
			case File__:
			case Dir__:
			case NamespaceSeparator:
			case Label:
				enterOuterAlt(_localctx, 2);
				{
				setState(1628);
				qualifiedStaticTypeRef();
				setState(1629);
				match(DoubleColon);
				setState(1630);
				keyedVariable();
				}
				break;
			default:
				throw new NoViableAltException(this);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class KeyedFieldNameContext extends ParserRuleContext {
		public KeyedSimpleFieldNameContext keyedSimpleFieldName() {
			return getRuleContext(KeyedSimpleFieldNameContext.class,0);
		}
		public KeyedVariableContext keyedVariable() {
			return getRuleContext(KeyedVariableContext.class,0);
		}
		public KeyedFieldNameContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_keyedFieldName; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterKeyedFieldName(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitKeyedFieldName(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitKeyedFieldName(this);
			else return visitor.visitChildren(this);
		}
	}

	public final KeyedFieldNameContext keyedFieldName() throws RecognitionException {
		KeyedFieldNameContext _localctx = new KeyedFieldNameContext(_ctx, getState());
		enterRule(_localctx, 236, RULE_keyedFieldName);
		try {
			setState(1636);
			switch (_input.LA(1)) {
			case Abstract:
			case Array:
			case BinaryCast:
			case BoolType:
			case Break:
			case Callable:
			case Case:
			case Class:
			case Clone:
			case Continue:
			case Default:
			case DoubleCast:
			case DoubleType:
			case Echo:
			case Empty:
			case Eval:
			case Final:
			case FloatCast:
			case Function:
			case Global:
			case Import:
			case Int16Cast:
			case Int64Type:
			case Int8Cast:
			case Interface:
			case IntType:
			case IsSet:
			case List:
			case LogicalAnd:
			case LogicalOr:
			case LogicalXor:
			case Namespace:
			case New:
			case Null:
			case ObjectType:
			case Parent_:
			case Partial:
			case Private:
			case Protected:
			case Public:
			case Resource:
			case Return:
			case Static:
			case StringType:
			case Uint16Cast:
			case Uint32Cast:
			case Uint64Cast:
			case Uint8Cast:
			case UnicodeCast:
			case Unset:
			case Use:
			case Var:
			case Get:
			case Set:
			case Call:
			case CallStatic:
			case Constructor:
			case Destruct:
			case Wakeup:
			case Sleep:
			case Autoload:
			case IsSet__:
			case Unset__:
			case ToString__:
			case Invoke:
			case SetState:
			case Clone__:
			case DebugInfo:
			case Namespace__:
			case Class__:
			case Traic__:
			case Function__:
			case Method__:
			case Line__:
			case File__:
			case Dir__:
			case OpenCurlyBracket:
			case Label:
				enterOuterAlt(_localctx, 1);
				{
				setState(1634);
				keyedSimpleFieldName();
				}
				break;
			case Dollar:
			case VarName:
				enterOuterAlt(_localctx, 2);
				{
				setState(1635);
				keyedVariable();
				}
				break;
			default:
				throw new NoViableAltException(this);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class KeyedSimpleFieldNameContext extends ParserRuleContext {
		public IdentifierContext identifier() {
			return getRuleContext(IdentifierContext.class,0);
		}
		public ExpressionContext expression() {
			return getRuleContext(ExpressionContext.class,0);
		}
		public List<SquareCurlyExpressionContext> squareCurlyExpression() {
			return getRuleContexts(SquareCurlyExpressionContext.class);
		}
		public SquareCurlyExpressionContext squareCurlyExpression(int i) {
			return getRuleContext(SquareCurlyExpressionContext.class,i);
		}
		public KeyedSimpleFieldNameContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_keyedSimpleFieldName; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterKeyedSimpleFieldName(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitKeyedSimpleFieldName(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitKeyedSimpleFieldName(this);
			else return visitor.visitChildren(this);
		}
	}

	public final KeyedSimpleFieldNameContext keyedSimpleFieldName() throws RecognitionException {
		KeyedSimpleFieldNameContext _localctx = new KeyedSimpleFieldNameContext(_ctx, getState());
		enterRule(_localctx, 238, RULE_keyedSimpleFieldName);
		try {
			int _alt;
			enterOuterAlt(_localctx, 1);
			{
			setState(1643);
			switch (_input.LA(1)) {
			case Abstract:
			case Array:
			case BinaryCast:
			case BoolType:
			case Break:
			case Callable:
			case Case:
			case Class:
			case Clone:
			case Continue:
			case Default:
			case DoubleCast:
			case DoubleType:
			case Echo:
			case Empty:
			case Eval:
			case Final:
			case FloatCast:
			case Function:
			case Global:
			case Import:
			case Int16Cast:
			case Int64Type:
			case Int8Cast:
			case Interface:
			case IntType:
			case IsSet:
			case List:
			case LogicalAnd:
			case LogicalOr:
			case LogicalXor:
			case Namespace:
			case New:
			case Null:
			case ObjectType:
			case Parent_:
			case Partial:
			case Private:
			case Protected:
			case Public:
			case Resource:
			case Return:
			case Static:
			case StringType:
			case Uint16Cast:
			case Uint32Cast:
			case Uint64Cast:
			case Uint8Cast:
			case UnicodeCast:
			case Unset:
			case Use:
			case Var:
			case Get:
			case Set:
			case Call:
			case CallStatic:
			case Constructor:
			case Destruct:
			case Wakeup:
			case Sleep:
			case Autoload:
			case IsSet__:
			case Unset__:
			case ToString__:
			case Invoke:
			case SetState:
			case Clone__:
			case DebugInfo:
			case Namespace__:
			case Class__:
			case Traic__:
			case Function__:
			case Method__:
			case Line__:
			case File__:
			case Dir__:
			case Label:
				{
				setState(1638);
				identifier();
				}
				break;
			case OpenCurlyBracket:
				{
				setState(1639);
				match(OpenCurlyBracket);
				setState(1640);
				expression(0);
				setState(1641);
				match(CloseCurlyBracket);
				}
				break;
			default:
				throw new NoViableAltException(this);
			}
			setState(1648);
			_errHandler.sync(this);
			_alt = getInterpreter().adaptivePredict(_input,191,_ctx);
			while ( _alt!=2 && _alt!= ATN.INVALID_ALT_NUMBER ) {
				if ( _alt==1 ) {
					{
					{
					setState(1645);
					squareCurlyExpression();
					}
					}
				}
				setState(1650);
				_errHandler.sync(this);
				_alt = getInterpreter().adaptivePredict(_input,191,_ctx);
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class KeyedVariableContext extends ParserRuleContext {
		public TerminalNode VarName() { return getToken(PHPParser.VarName, 0); }
		public ExpressionContext expression() {
			return getRuleContext(ExpressionContext.class,0);
		}
		public List<SquareCurlyExpressionContext> squareCurlyExpression() {
			return getRuleContexts(SquareCurlyExpressionContext.class);
		}
		public SquareCurlyExpressionContext squareCurlyExpression(int i) {
			return getRuleContext(SquareCurlyExpressionContext.class,i);
		}
		public KeyedVariableContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_keyedVariable; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterKeyedVariable(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitKeyedVariable(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitKeyedVariable(this);
			else return visitor.visitChildren(this);
		}
	}

	public final KeyedVariableContext keyedVariable() throws RecognitionException {
		KeyedVariableContext _localctx = new KeyedVariableContext(_ctx, getState());
		enterRule(_localctx, 240, RULE_keyedVariable);
		try {
			int _alt;
			enterOuterAlt(_localctx, 1);
			{
			setState(1654);
			_errHandler.sync(this);
			_alt = getInterpreter().adaptivePredict(_input,192,_ctx);
			while ( _alt!=2 && _alt!= ATN.INVALID_ALT_NUMBER ) {
				if ( _alt==1 ) {
					{
					{
					setState(1651);
					match(Dollar);
					}
					}
				}
				setState(1656);
				_errHandler.sync(this);
				_alt = getInterpreter().adaptivePredict(_input,192,_ctx);
			}
			setState(1663);
			switch (_input.LA(1)) {
			case VarName:
				{
				setState(1657);
				match(VarName);
				}
				break;
			case Dollar:
				{
				setState(1658);
				match(Dollar);
				setState(1659);
				match(OpenCurlyBracket);
				setState(1660);
				expression(0);
				setState(1661);
				match(CloseCurlyBracket);
				}
				break;
			default:
				throw new NoViableAltException(this);
			}
			setState(1668);
			_errHandler.sync(this);
			_alt = getInterpreter().adaptivePredict(_input,194,_ctx);
			while ( _alt!=2 && _alt!= ATN.INVALID_ALT_NUMBER ) {
				if ( _alt==1 ) {
					{
					{
					setState(1665);
					squareCurlyExpression();
					}
					} 
				}
				setState(1670);
				_errHandler.sync(this);
				_alt = getInterpreter().adaptivePredict(_input,194,_ctx);
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class SquareCurlyExpressionContext extends ParserRuleContext {
		public ExpressionContext expression() {
			return getRuleContext(ExpressionContext.class,0);
		}
		public SquareCurlyExpressionContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_squareCurlyExpression; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterSquareCurlyExpression(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitSquareCurlyExpression(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitSquareCurlyExpression(this);
			else return visitor.visitChildren(this);
		}
	}

	public final SquareCurlyExpressionContext squareCurlyExpression() throws RecognitionException {
		SquareCurlyExpressionContext _localctx = new SquareCurlyExpressionContext(_ctx, getState());
		enterRule(_localctx, 242, RULE_squareCurlyExpression);
		int _la;
		try {
			setState(1680);
			switch (_input.LA(1)) {
			case OpenSquareBracket:
				enterOuterAlt(_localctx, 1);
				{
				setState(1671);
				match(OpenSquareBracket);
				setState(1673);
				_la = _input.LA(1);
				if (((((_la - 36)) & ~0x3f) == 0 && ((1L << (_la - 36)) & ((1L << (Abstract - 36)) | (1L << (Array - 36)) | (1L << (BinaryCast - 36)) | (1L << (BoolType - 36)) | (1L << (BooleanConstant - 36)) | (1L << (Break - 36)) | (1L << (Callable - 36)) | (1L << (Case - 36)) | (1L << (Class - 36)) | (1L << (Clone - 36)) | (1L << (Continue - 36)) | (1L << (Default - 36)) | (1L << (DoubleCast - 36)) | (1L << (DoubleType - 36)) | (1L << (Echo - 36)) | (1L << (Empty - 36)) | (1L << (Eval - 36)) | (1L << (Exit - 36)) | (1L << (Final - 36)) | (1L << (FloatCast - 36)) | (1L << (Function - 36)) | (1L << (Global - 36)) | (1L << (Import - 36)) | (1L << (Include - 36)) | (1L << (IncludeOnce - 36)) | (1L << (Int16Cast - 36)) | (1L << (Int64Type - 36)) | (1L << (Int8Cast - 36)) | (1L << (Interface - 36)) | (1L << (IntType - 36)) | (1L << (IsSet - 36)) | (1L << (List - 36)) | (1L << (LogicalAnd - 36)) | (1L << (LogicalOr - 36)) | (1L << (LogicalXor - 36)) | (1L << (Namespace - 36)) | (1L << (New - 36)) | (1L << (Null - 36)) | (1L << (ObjectType - 36)) | (1L << (Parent_ - 36)) | (1L << (Partial - 36)) | (1L << (Print - 36)))) != 0) || ((((_la - 100)) & ~0x3f) == 0 && ((1L << (_la - 100)) & ((1L << (Private - 100)) | (1L << (Protected - 100)) | (1L << (Public - 100)) | (1L << (Require - 100)) | (1L << (RequireOnce - 100)) | (1L << (Resource - 100)) | (1L << (Return - 100)) | (1L << (Static - 100)) | (1L << (StringType - 100)) | (1L << (Uint16Cast - 100)) | (1L << (Uint32Cast - 100)) | (1L << (Uint64Cast - 100)) | (1L << (Uint8Cast - 100)) | (1L << (UnicodeCast - 100)) | (1L << (Unset - 100)) | (1L << (Use - 100)) | (1L << (Var - 100)) | (1L << (Yield - 100)) | (1L << (Get - 100)) | (1L << (Set - 100)) | (1L << (Call - 100)) | (1L << (CallStatic - 100)) | (1L << (Constructor - 100)) | (1L << (Destruct - 100)) | (1L << (Wakeup - 100)) | (1L << (Sleep - 100)) | (1L << (Autoload - 100)) | (1L << (IsSet__ - 100)) | (1L << (Unset__ - 100)) | (1L << (ToString__ - 100)) | (1L << (Invoke - 100)) | (1L << (SetState - 100)) | (1L << (Clone__ - 100)) | (1L << (DebugInfo - 100)) | (1L << (Namespace__ - 100)) | (1L << (Class__ - 100)) | (1L << (Traic__ - 100)) | (1L << (Function__ - 100)) | (1L << (Method__ - 100)) | (1L << (Line__ - 100)) | (1L << (File__ - 100)) | (1L << (Dir__ - 100)) | (1L << (Inc - 100)) | (1L << (Dec - 100)))) != 0) || ((((_la - 178)) & ~0x3f) == 0 && ((1L << (_la - 178)) & ((1L << (NamespaceSeparator - 178)) | (1L << (Bang - 178)) | (1L << (Plus - 178)) | (1L << (Minus - 178)) | (1L << (Tilde - 178)) | (1L << (SuppressWarnings - 178)) | (1L << (Dollar - 178)) | (1L << (OpenRoundBracket - 178)) | (1L << (OpenSquareBracket - 178)) | (1L << (Label - 178)) | (1L << (VarName - 178)) | (1L << (Numeric - 178)) | (1L << (Real - 178)) | (1L << (BackQuoteString - 178)) | (1L << (SingleQuoteString - 178)) | (1L << (DoubleQuoteString - 178)) | (1L << (StartNowDoc - 178)) | (1L << (StartHereDoc - 178)))) != 0)) {
					{
					setState(1672);
					expression(0);
					}
				}

				setState(1675);
				match(CloseSquareBracket);
				}
				break;
			case OpenCurlyBracket:
				enterOuterAlt(_localctx, 2);
				{
				setState(1676);
				match(OpenCurlyBracket);
				setState(1677);
				expression(0);
				setState(1678);
				match(CloseCurlyBracket);
				}
				break;
			default:
				throw new NoViableAltException(this);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class AssignmentListContext extends ParserRuleContext {
		public List<AssignmentListElementContext> assignmentListElement() {
			return getRuleContexts(AssignmentListElementContext.class);
		}
		public AssignmentListElementContext assignmentListElement(int i) {
			return getRuleContext(AssignmentListElementContext.class,i);
		}
		public AssignmentListContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_assignmentList; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterAssignmentList(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitAssignmentList(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitAssignmentList(this);
			else return visitor.visitChildren(this);
		}
	}

	public final AssignmentListContext assignmentList() throws RecognitionException {
		AssignmentListContext _localctx = new AssignmentListContext(_ctx, getState());
		enterRule(_localctx, 244, RULE_assignmentList);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(1683);
			_la = _input.LA(1);
			if (((((_la - 36)) & ~0x3f) == 0 && ((1L << (_la - 36)) & ((1L << (Abstract - 36)) | (1L << (Array - 36)) | (1L << (BinaryCast - 36)) | (1L << (BoolType - 36)) | (1L << (Break - 36)) | (1L << (Callable - 36)) | (1L << (Case - 36)) | (1L << (Class - 36)) | (1L << (Clone - 36)) | (1L << (Continue - 36)) | (1L << (Default - 36)) | (1L << (DoubleCast - 36)) | (1L << (DoubleType - 36)) | (1L << (Echo - 36)) | (1L << (Empty - 36)) | (1L << (Eval - 36)) | (1L << (Final - 36)) | (1L << (FloatCast - 36)) | (1L << (Function - 36)) | (1L << (Global - 36)) | (1L << (Import - 36)) | (1L << (Int16Cast - 36)) | (1L << (Int64Type - 36)) | (1L << (Int8Cast - 36)) | (1L << (Interface - 36)) | (1L << (IntType - 36)) | (1L << (IsSet - 36)) | (1L << (List - 36)) | (1L << (LogicalAnd - 36)) | (1L << (LogicalOr - 36)) | (1L << (LogicalXor - 36)) | (1L << (Namespace - 36)) | (1L << (New - 36)) | (1L << (Null - 36)) | (1L << (ObjectType - 36)) | (1L << (Parent_ - 36)) | (1L << (Partial - 36)))) != 0) || ((((_la - 100)) & ~0x3f) == 0 && ((1L << (_la - 100)) & ((1L << (Private - 100)) | (1L << (Protected - 100)) | (1L << (Public - 100)) | (1L << (Resource - 100)) | (1L << (Return - 100)) | (1L << (Static - 100)) | (1L << (StringType - 100)) | (1L << (Uint16Cast - 100)) | (1L << (Uint32Cast - 100)) | (1L << (Uint64Cast - 100)) | (1L << (Uint8Cast - 100)) | (1L << (UnicodeCast - 100)) | (1L << (Unset - 100)) | (1L << (Use - 100)) | (1L << (Var - 100)) | (1L << (Get - 100)) | (1L << (Set - 100)) | (1L << (Call - 100)) | (1L << (CallStatic - 100)) | (1L << (Constructor - 100)) | (1L << (Destruct - 100)) | (1L << (Wakeup - 100)) | (1L << (Sleep - 100)) | (1L << (Autoload - 100)) | (1L << (IsSet__ - 100)) | (1L << (Unset__ - 100)) | (1L << (ToString__ - 100)) | (1L << (Invoke - 100)) | (1L << (SetState - 100)) | (1L << (Clone__ - 100)) | (1L << (DebugInfo - 100)) | (1L << (Namespace__ - 100)) | (1L << (Class__ - 100)) | (1L << (Traic__ - 100)) | (1L << (Function__ - 100)) | (1L << (Method__ - 100)) | (1L << (Line__ - 100)) | (1L << (File__ - 100)) | (1L << (Dir__ - 100)))) != 0) || ((((_la - 178)) & ~0x3f) == 0 && ((1L << (_la - 178)) & ((1L << (NamespaceSeparator - 178)) | (1L << (Dollar - 178)) | (1L << (OpenRoundBracket - 178)) | (1L << (Label - 178)) | (1L << (VarName - 178)))) != 0)) {
				{
				setState(1682);
				assignmentListElement();
				}
			}

			setState(1691);
			_errHandler.sync(this);
			_la = _input.LA(1);
			while (_la==Comma) {
				{
				{
				setState(1685);
				match(Comma);
				setState(1687);
				_la = _input.LA(1);
				if (((((_la - 36)) & ~0x3f) == 0 && ((1L << (_la - 36)) & ((1L << (Abstract - 36)) | (1L << (Array - 36)) | (1L << (BinaryCast - 36)) | (1L << (BoolType - 36)) | (1L << (Break - 36)) | (1L << (Callable - 36)) | (1L << (Case - 36)) | (1L << (Class - 36)) | (1L << (Clone - 36)) | (1L << (Continue - 36)) | (1L << (Default - 36)) | (1L << (DoubleCast - 36)) | (1L << (DoubleType - 36)) | (1L << (Echo - 36)) | (1L << (Empty - 36)) | (1L << (Eval - 36)) | (1L << (Final - 36)) | (1L << (FloatCast - 36)) | (1L << (Function - 36)) | (1L << (Global - 36)) | (1L << (Import - 36)) | (1L << (Int16Cast - 36)) | (1L << (Int64Type - 36)) | (1L << (Int8Cast - 36)) | (1L << (Interface - 36)) | (1L << (IntType - 36)) | (1L << (IsSet - 36)) | (1L << (List - 36)) | (1L << (LogicalAnd - 36)) | (1L << (LogicalOr - 36)) | (1L << (LogicalXor - 36)) | (1L << (Namespace - 36)) | (1L << (New - 36)) | (1L << (Null - 36)) | (1L << (ObjectType - 36)) | (1L << (Parent_ - 36)) | (1L << (Partial - 36)))) != 0) || ((((_la - 100)) & ~0x3f) == 0 && ((1L << (_la - 100)) & ((1L << (Private - 100)) | (1L << (Protected - 100)) | (1L << (Public - 100)) | (1L << (Resource - 100)) | (1L << (Return - 100)) | (1L << (Static - 100)) | (1L << (StringType - 100)) | (1L << (Uint16Cast - 100)) | (1L << (Uint32Cast - 100)) | (1L << (Uint64Cast - 100)) | (1L << (Uint8Cast - 100)) | (1L << (UnicodeCast - 100)) | (1L << (Unset - 100)) | (1L << (Use - 100)) | (1L << (Var - 100)) | (1L << (Get - 100)) | (1L << (Set - 100)) | (1L << (Call - 100)) | (1L << (CallStatic - 100)) | (1L << (Constructor - 100)) | (1L << (Destruct - 100)) | (1L << (Wakeup - 100)) | (1L << (Sleep - 100)) | (1L << (Autoload - 100)) | (1L << (IsSet__ - 100)) | (1L << (Unset__ - 100)) | (1L << (ToString__ - 100)) | (1L << (Invoke - 100)) | (1L << (SetState - 100)) | (1L << (Clone__ - 100)) | (1L << (DebugInfo - 100)) | (1L << (Namespace__ - 100)) | (1L << (Class__ - 100)) | (1L << (Traic__ - 100)) | (1L << (Function__ - 100)) | (1L << (Method__ - 100)) | (1L << (Line__ - 100)) | (1L << (File__ - 100)) | (1L << (Dir__ - 100)))) != 0) || ((((_la - 178)) & ~0x3f) == 0 && ((1L << (_la - 178)) & ((1L << (NamespaceSeparator - 178)) | (1L << (Dollar - 178)) | (1L << (OpenRoundBracket - 178)) | (1L << (Label - 178)) | (1L << (VarName - 178)))) != 0)) {
					{
					setState(1686);
					assignmentListElement();
					}
				}

				}
				}
				setState(1693);
				_errHandler.sync(this);
				_la = _input.LA(1);
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class AssignmentListElementContext extends ParserRuleContext {
		public ChainContext chain() {
			return getRuleContext(ChainContext.class,0);
		}
		public TerminalNode List() { return getToken(PHPParser.List, 0); }
		public AssignmentListContext assignmentList() {
			return getRuleContext(AssignmentListContext.class,0);
		}
		public AssignmentListElementContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_assignmentListElement; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterAssignmentListElement(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitAssignmentListElement(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitAssignmentListElement(this);
			else return visitor.visitChildren(this);
		}
	}

	public final AssignmentListElementContext assignmentListElement() throws RecognitionException {
		AssignmentListElementContext _localctx = new AssignmentListElementContext(_ctx, getState());
		enterRule(_localctx, 246, RULE_assignmentListElement);
		try {
			setState(1700);
			_errHandler.sync(this);
			switch ( getInterpreter().adaptivePredict(_input,200,_ctx) ) {
			case 1:
				enterOuterAlt(_localctx, 1);
				{
				setState(1694);
				chain();
				}
				break;
			case 2:
				enterOuterAlt(_localctx, 2);
				{
				setState(1695);
				match(List);
				setState(1696);
				match(OpenRoundBracket);
				setState(1697);
				assignmentList();
				setState(1698);
				match(CloseRoundBracket);
				}
				break;
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class ModifierContext extends ParserRuleContext {
		public TerminalNode Abstract() { return getToken(PHPParser.Abstract, 0); }
		public TerminalNode Final() { return getToken(PHPParser.Final, 0); }
		public ModifierContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_modifier; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterModifier(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitModifier(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitModifier(this);
			else return visitor.visitChildren(this);
		}
	}

	public final ModifierContext modifier() throws RecognitionException {
		ModifierContext _localctx = new ModifierContext(_ctx, getState());
		enterRule(_localctx, 248, RULE_modifier);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(1702);
			_la = _input.LA(1);
			if ( !(_la==Abstract || _la==Final) ) {
			_errHandler.recoverInline(this);
			} else {
				consume();
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class IdentifierContext extends ParserRuleContext {
		public MagicConstantContext magicConstant() {
			return getRuleContext(MagicConstantContext.class,0);
		}
		public MagicMethodContext magicMethod() {
			return getRuleContext(MagicMethodContext.class,0);
		}
		public CastOperationContext castOperation() {
			return getRuleContext(CastOperationContext.class,0);
		}
		public MemberModifierContext memberModifier() {
			return getRuleContext(MemberModifierContext.class,0);
		}
		public TerminalNode Label() { return getToken(PHPParser.Label, 0); }
		public TerminalNode Class() { return getToken(PHPParser.Class, 0); }
		public TerminalNode Interface() { return getToken(PHPParser.Interface, 0); }
		public TerminalNode Namespace() { return getToken(PHPParser.Namespace, 0); }
		public TerminalNode Case() { return getToken(PHPParser.Case, 0); }
		public TerminalNode Default() { return getToken(PHPParser.Default, 0); }
		public TerminalNode Return() { return getToken(PHPParser.Return, 0); }
		public TerminalNode Import() { return getToken(PHPParser.Import, 0); }
		public TerminalNode Parent_() { return getToken(PHPParser.Parent_, 0); }
		public TerminalNode List() { return getToken(PHPParser.List, 0); }
		public TerminalNode Function() { return getToken(PHPParser.Function, 0); }
		public TerminalNode Null() { return getToken(PHPParser.Null, 0); }
		public TerminalNode Partial() { return getToken(PHPParser.Partial, 0); }
		public TerminalNode Echo() { return getToken(PHPParser.Echo, 0); }
		public TerminalNode New() { return getToken(PHPParser.New, 0); }
		public TerminalNode Empty() { return getToken(PHPParser.Empty, 0); }
		public TerminalNode Callable() { return getToken(PHPParser.Callable, 0); }
		public TerminalNode Var() { return getToken(PHPParser.Var, 0); }
		public TerminalNode IsSet() { return getToken(PHPParser.IsSet, 0); }
		public TerminalNode Break() { return getToken(PHPParser.Break, 0); }
		public TerminalNode Continue() { return getToken(PHPParser.Continue, 0); }
		public TerminalNode Use() { return getToken(PHPParser.Use, 0); }
		public TerminalNode LogicalAnd() { return getToken(PHPParser.LogicalAnd, 0); }
		public TerminalNode LogicalXor() { return getToken(PHPParser.LogicalXor, 0); }
		public TerminalNode LogicalOr() { return getToken(PHPParser.LogicalOr, 0); }
		public TerminalNode Clone() { return getToken(PHPParser.Clone, 0); }
		public TerminalNode Global() { return getToken(PHPParser.Global, 0); }
		public TerminalNode Eval() { return getToken(PHPParser.Eval, 0); }
		public IdentifierContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_identifier; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterIdentifier(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitIdentifier(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitIdentifier(this);
			else return visitor.visitChildren(this);
		}
	}

	public final IdentifierContext identifier() throws RecognitionException {
		IdentifierContext _localctx = new IdentifierContext(_ctx, getState());
		enterRule(_localctx, 250, RULE_identifier);
		try {
			setState(1736);
			switch (_input.LA(1)) {
			case Namespace__:
			case Class__:
			case Traic__:
			case Function__:
			case Method__:
			case Line__:
			case File__:
			case Dir__:
				enterOuterAlt(_localctx, 1);
				{
				setState(1704);
				magicConstant();
				}
				break;
			case Get:
			case Set:
			case Call:
			case CallStatic:
			case Constructor:
			case Destruct:
			case Wakeup:
			case Sleep:
			case Autoload:
			case IsSet__:
			case Unset__:
			case ToString__:
			case Invoke:
			case SetState:
			case Clone__:
			case DebugInfo:
				enterOuterAlt(_localctx, 2);
				{
				setState(1705);
				magicMethod();
				}
				break;
			case Array:
			case BinaryCast:
			case BoolType:
			case DoubleCast:
			case DoubleType:
			case FloatCast:
			case Int16Cast:
			case Int64Type:
			case Int8Cast:
			case IntType:
			case ObjectType:
			case Resource:
			case StringType:
			case Uint16Cast:
			case Uint32Cast:
			case Uint64Cast:
			case Uint8Cast:
			case UnicodeCast:
			case Unset:
				enterOuterAlt(_localctx, 3);
				{
				setState(1706);
				castOperation();
				}
				break;
			case Abstract:
			case Final:
			case Private:
			case Protected:
			case Public:
			case Static:
				enterOuterAlt(_localctx, 4);
				{
				setState(1707);
				memberModifier();
				}
				break;
			case Label:
				enterOuterAlt(_localctx, 5);
				{
				setState(1708);
				match(Label);
				}
				break;
			case Class:
				enterOuterAlt(_localctx, 6);
				{
				setState(1709);
				match(Class);
				}
				break;
			case Interface:
				enterOuterAlt(_localctx, 7);
				{
				setState(1710);
				match(Interface);
				}
				break;
			case Namespace:
				enterOuterAlt(_localctx, 8);
				{
				setState(1711);
				match(Namespace);
				}
				break;
			case Case:
				enterOuterAlt(_localctx, 9);
				{
				setState(1712);
				match(Case);
				}
				break;
			case Default:
				enterOuterAlt(_localctx, 10);
				{
				setState(1713);
				match(Default);
				}
				break;
			case Return:
				enterOuterAlt(_localctx, 11);
				{
				setState(1714);
				match(Return);
				}
				break;
			case Import:
				enterOuterAlt(_localctx, 12);
				{
				setState(1715);
				match(Import);
				}
				break;
			case Parent_:
				enterOuterAlt(_localctx, 13);
				{
				setState(1716);
				match(Parent_);
				}
				break;
			case List:
				enterOuterAlt(_localctx, 14);
				{
				setState(1717);
				match(List);
				}
				break;
			case Function:
				enterOuterAlt(_localctx, 15);
				{
				setState(1718);
				match(Function);
				}
				break;
			case Null:
				enterOuterAlt(_localctx, 16);
				{
				setState(1719);
				match(Null);
				}
				break;
			case Partial:
				enterOuterAlt(_localctx, 17);
				{
				setState(1720);
				match(Partial);
				}
				break;
			case Echo:
				enterOuterAlt(_localctx, 18);
				{
				setState(1721);
				match(Echo);
				}
				break;
			case New:
				enterOuterAlt(_localctx, 19);
				{
				setState(1722);
				match(New);
				}
				break;
			case Empty:
				enterOuterAlt(_localctx, 20);
				{
				setState(1723);
				match(Empty);
				}
				break;
			case Callable:
				enterOuterAlt(_localctx, 21);
				{
				setState(1724);
				match(Callable);
				}
				break;
			case Var:
				enterOuterAlt(_localctx, 22);
				{
				setState(1725);
				match(Var);
				}
				break;
			case IsSet:
				enterOuterAlt(_localctx, 23);
				{
				setState(1726);
				match(IsSet);
				}
				break;
			case Break:
				enterOuterAlt(_localctx, 24);
				{
				setState(1727);
				match(Break);
				}
				break;
			case Continue:
				enterOuterAlt(_localctx, 25);
				{
				setState(1728);
				match(Continue);
				}
				break;
			case Use:
				enterOuterAlt(_localctx, 26);
				{
				setState(1729);
				match(Use);
				}
				break;
			case LogicalAnd:
				enterOuterAlt(_localctx, 27);
				{
				setState(1730);
				match(LogicalAnd);
				}
				break;
			case LogicalXor:
				enterOuterAlt(_localctx, 28);
				{
				setState(1731);
				match(LogicalXor);
				}
				break;
			case LogicalOr:
				enterOuterAlt(_localctx, 29);
				{
				setState(1732);
				match(LogicalOr);
				}
				break;
			case Clone:
				enterOuterAlt(_localctx, 30);
				{
				setState(1733);
				match(Clone);
				}
				break;
			case Global:
				enterOuterAlt(_localctx, 31);
				{
				setState(1734);
				match(Global);
				}
				break;
			case Eval:
				enterOuterAlt(_localctx, 32);
				{
				setState(1735);
				match(Eval);
				}
				break;
			default:
				throw new NoViableAltException(this);
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class MemberModifierContext extends ParserRuleContext {
		public TerminalNode Public() { return getToken(PHPParser.Public, 0); }
		public TerminalNode Protected() { return getToken(PHPParser.Protected, 0); }
		public TerminalNode Private() { return getToken(PHPParser.Private, 0); }
		public TerminalNode Static() { return getToken(PHPParser.Static, 0); }
		public TerminalNode Abstract() { return getToken(PHPParser.Abstract, 0); }
		public TerminalNode Final() { return getToken(PHPParser.Final, 0); }
		public MemberModifierContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_memberModifier; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterMemberModifier(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitMemberModifier(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitMemberModifier(this);
			else return visitor.visitChildren(this);
		}
	}

	public final MemberModifierContext memberModifier() throws RecognitionException {
		MemberModifierContext _localctx = new MemberModifierContext(_ctx, getState());
		enterRule(_localctx, 252, RULE_memberModifier);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(1738);
			_la = _input.LA(1);
			if ( !(_la==Abstract || ((((_la - 68)) & ~0x3f) == 0 && ((1L << (_la - 68)) & ((1L << (Final - 68)) | (1L << (Private - 68)) | (1L << (Protected - 68)) | (1L << (Public - 68)) | (1L << (Static - 68)))) != 0)) ) {
			_errHandler.recoverInline(this);
			} else {
				consume();
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class MagicConstantContext extends ParserRuleContext {
		public TerminalNode Namespace__() { return getToken(PHPParser.Namespace__, 0); }
		public TerminalNode Class__() { return getToken(PHPParser.Class__, 0); }
		public TerminalNode Traic__() { return getToken(PHPParser.Traic__, 0); }
		public TerminalNode Function__() { return getToken(PHPParser.Function__, 0); }
		public TerminalNode Method__() { return getToken(PHPParser.Method__, 0); }
		public TerminalNode Line__() { return getToken(PHPParser.Line__, 0); }
		public TerminalNode File__() { return getToken(PHPParser.File__, 0); }
		public TerminalNode Dir__() { return getToken(PHPParser.Dir__, 0); }
		public MagicConstantContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_magicConstant; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterMagicConstant(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitMagicConstant(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitMagicConstant(this);
			else return visitor.visitChildren(this);
		}
	}

	public final MagicConstantContext magicConstant() throws RecognitionException {
		MagicConstantContext _localctx = new MagicConstantContext(_ctx, getState());
		enterRule(_localctx, 254, RULE_magicConstant);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(1740);
			_la = _input.LA(1);
			if ( !(((((_la - 140)) & ~0x3f) == 0 && ((1L << (_la - 140)) & ((1L << (Namespace__ - 140)) | (1L << (Class__ - 140)) | (1L << (Traic__ - 140)) | (1L << (Function__ - 140)) | (1L << (Method__ - 140)) | (1L << (Line__ - 140)) | (1L << (File__ - 140)) | (1L << (Dir__ - 140)))) != 0)) ) {
			_errHandler.recoverInline(this);
			} else {
				consume();
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class MagicMethodContext extends ParserRuleContext {
		public TerminalNode Get() { return getToken(PHPParser.Get, 0); }
		public TerminalNode Set() { return getToken(PHPParser.Set, 0); }
		public TerminalNode Call() { return getToken(PHPParser.Call, 0); }
		public TerminalNode CallStatic() { return getToken(PHPParser.CallStatic, 0); }
		public TerminalNode Constructor() { return getToken(PHPParser.Constructor, 0); }
		public TerminalNode Destruct() { return getToken(PHPParser.Destruct, 0); }
		public TerminalNode Wakeup() { return getToken(PHPParser.Wakeup, 0); }
		public TerminalNode Sleep() { return getToken(PHPParser.Sleep, 0); }
		public TerminalNode Autoload() { return getToken(PHPParser.Autoload, 0); }
		public TerminalNode IsSet__() { return getToken(PHPParser.IsSet__, 0); }
		public TerminalNode Unset__() { return getToken(PHPParser.Unset__, 0); }
		public TerminalNode ToString__() { return getToken(PHPParser.ToString__, 0); }
		public TerminalNode Invoke() { return getToken(PHPParser.Invoke, 0); }
		public TerminalNode SetState() { return getToken(PHPParser.SetState, 0); }
		public TerminalNode Clone__() { return getToken(PHPParser.Clone__, 0); }
		public TerminalNode DebugInfo() { return getToken(PHPParser.DebugInfo, 0); }
		public MagicMethodContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_magicMethod; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterMagicMethod(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitMagicMethod(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitMagicMethod(this);
			else return visitor.visitChildren(this);
		}
	}

	public final MagicMethodContext magicMethod() throws RecognitionException {
		MagicMethodContext _localctx = new MagicMethodContext(_ctx, getState());
		enterRule(_localctx, 256, RULE_magicMethod);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(1742);
			_la = _input.LA(1);
			if ( !(((((_la - 124)) & ~0x3f) == 0 && ((1L << (_la - 124)) & ((1L << (Get - 124)) | (1L << (Set - 124)) | (1L << (Call - 124)) | (1L << (CallStatic - 124)) | (1L << (Constructor - 124)) | (1L << (Destruct - 124)) | (1L << (Wakeup - 124)) | (1L << (Sleep - 124)) | (1L << (Autoload - 124)) | (1L << (IsSet__ - 124)) | (1L << (Unset__ - 124)) | (1L << (ToString__ - 124)) | (1L << (Invoke - 124)) | (1L << (SetState - 124)) | (1L << (Clone__ - 124)) | (1L << (DebugInfo - 124)))) != 0)) ) {
			_errHandler.recoverInline(this);
			} else {
				consume();
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class PrimitiveTypeContext extends ParserRuleContext {
		public TerminalNode BoolType() { return getToken(PHPParser.BoolType, 0); }
		public TerminalNode IntType() { return getToken(PHPParser.IntType, 0); }
		public TerminalNode Int64Type() { return getToken(PHPParser.Int64Type, 0); }
		public TerminalNode DoubleType() { return getToken(PHPParser.DoubleType, 0); }
		public TerminalNode StringType() { return getToken(PHPParser.StringType, 0); }
		public TerminalNode Resource() { return getToken(PHPParser.Resource, 0); }
		public TerminalNode ObjectType() { return getToken(PHPParser.ObjectType, 0); }
		public TerminalNode Array() { return getToken(PHPParser.Array, 0); }
		public PrimitiveTypeContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_primitiveType; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterPrimitiveType(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitPrimitiveType(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitPrimitiveType(this);
			else return visitor.visitChildren(this);
		}
	}

	public final PrimitiveTypeContext primitiveType() throws RecognitionException {
		PrimitiveTypeContext _localctx = new PrimitiveTypeContext(_ctx, getState());
		enterRule(_localctx, 258, RULE_primitiveType);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(1744);
			_la = _input.LA(1);
			if ( !((((_la) & ~0x3f) == 0 && ((1L << _la) & ((1L << Array) | (1L << BoolType) | (1L << DoubleType))) != 0) || ((((_la - 84)) & ~0x3f) == 0 && ((1L << (_la - 84)) & ((1L << (Int64Type - 84)) | (1L << (IntType - 84)) | (1L << (ObjectType - 84)) | (1L << (Resource - 84)) | (1L << (StringType - 84)))) != 0)) ) {
			_errHandler.recoverInline(this);
			} else {
				consume();
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public static class CastOperationContext extends ParserRuleContext {
		public TerminalNode BoolType() { return getToken(PHPParser.BoolType, 0); }
		public TerminalNode Int8Cast() { return getToken(PHPParser.Int8Cast, 0); }
		public TerminalNode Int16Cast() { return getToken(PHPParser.Int16Cast, 0); }
		public TerminalNode IntType() { return getToken(PHPParser.IntType, 0); }
		public TerminalNode Int64Type() { return getToken(PHPParser.Int64Type, 0); }
		public TerminalNode Uint8Cast() { return getToken(PHPParser.Uint8Cast, 0); }
		public TerminalNode Uint16Cast() { return getToken(PHPParser.Uint16Cast, 0); }
		public TerminalNode Uint32Cast() { return getToken(PHPParser.Uint32Cast, 0); }
		public TerminalNode Uint64Cast() { return getToken(PHPParser.Uint64Cast, 0); }
		public TerminalNode DoubleCast() { return getToken(PHPParser.DoubleCast, 0); }
		public TerminalNode DoubleType() { return getToken(PHPParser.DoubleType, 0); }
		public TerminalNode FloatCast() { return getToken(PHPParser.FloatCast, 0); }
		public TerminalNode StringType() { return getToken(PHPParser.StringType, 0); }
		public TerminalNode BinaryCast() { return getToken(PHPParser.BinaryCast, 0); }
		public TerminalNode UnicodeCast() { return getToken(PHPParser.UnicodeCast, 0); }
		public TerminalNode Array() { return getToken(PHPParser.Array, 0); }
		public TerminalNode ObjectType() { return getToken(PHPParser.ObjectType, 0); }
		public TerminalNode Resource() { return getToken(PHPParser.Resource, 0); }
		public TerminalNode Unset() { return getToken(PHPParser.Unset, 0); }
		public CastOperationContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_castOperation; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).enterCastOperation(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof PHPParserListener ) ((PHPParserListener)listener).exitCastOperation(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof PHPParserVisitor ) return ((PHPParserVisitor<? extends T>)visitor).visitCastOperation(this);
			else return visitor.visitChildren(this);
		}
	}

	public final CastOperationContext castOperation() throws RecognitionException {
		CastOperationContext _localctx = new CastOperationContext(_ctx, getState());
		enterRule(_localctx, 260, RULE_castOperation);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(1746);
			_la = _input.LA(1);
			if ( !((((_la) & ~0x3f) == 0 && ((1L << _la) & ((1L << Array) | (1L << BinaryCast) | (1L << BoolType) | (1L << DoubleCast) | (1L << DoubleType))) != 0) || ((((_la - 70)) & ~0x3f) == 0 && ((1L << (_la - 70)) & ((1L << (FloatCast - 70)) | (1L << (Int16Cast - 70)) | (1L << (Int64Type - 70)) | (1L << (Int8Cast - 70)) | (1L << (IntType - 70)) | (1L << (ObjectType - 70)) | (1L << (Resource - 70)) | (1L << (StringType - 70)) | (1L << (Uint16Cast - 70)) | (1L << (Uint32Cast - 70)) | (1L << (Uint64Cast - 70)) | (1L << (Uint8Cast - 70)) | (1L << (UnicodeCast - 70)) | (1L << (Unset - 70)))) != 0)) ) {
			_errHandler.recoverInline(this);
			} else {
				consume();
			}
			}
		}
		catch (RecognitionException re) {
			_localctx.exception = re;
			_errHandler.reportError(this, re);
			_errHandler.recover(this, re);
		}
		finally {
			exitRule();
		}
		return _localctx;
	}

	public boolean sempred(RuleContext _localctx, int ruleIndex, int predIndex) {
		switch (ruleIndex) {
		case 87:
			return expression_sempred((ExpressionContext)_localctx, predIndex);
		}
		return true;
	}
	private boolean expression_sempred(ExpressionContext _localctx, int predIndex) {
		switch (predIndex) {
		case 0:
			return precpred(_ctx, 42);
		case 1:
			return precpred(_ctx, 35);
		case 2:
			return precpred(_ctx, 34);
		case 3:
			return precpred(_ctx, 33);
		case 4:
			return precpred(_ctx, 32);
		case 5:
			return precpred(_ctx, 31);
		case 6:
			return precpred(_ctx, 30);
		case 7:
			return precpred(_ctx, 29);
		case 8:
			return precpred(_ctx, 28);
		case 9:
			return precpred(_ctx, 27);
		case 10:
			return precpred(_ctx, 26);
		case 11:
			return precpred(_ctx, 25);
		case 12:
			return precpred(_ctx, 21);
		case 13:
			return precpred(_ctx, 20);
		case 14:
			return precpred(_ctx, 19);
		case 15:
			return precpred(_ctx, 37);
		}
		return true;
	}

	public static final String _serializedATN =
		"\3\u0430\ud6d1\u8206\uad2d\u4417\uaef1\u8d80\uaadd\3\u00e0\u06d7\4\2\t"+
		"\2\4\3\t\3\4\4\t\4\4\5\t\5\4\6\t\6\4\7\t\7\4\b\t\b\4\t\t\t\4\n\t\n\4\13"+
		"\t\13\4\f\t\f\4\r\t\r\4\16\t\16\4\17\t\17\4\20\t\20\4\21\t\21\4\22\t\22"+
		"\4\23\t\23\4\24\t\24\4\25\t\25\4\26\t\26\4\27\t\27\4\30\t\30\4\31\t\31"+
		"\4\32\t\32\4\33\t\33\4\34\t\34\4\35\t\35\4\36\t\36\4\37\t\37\4 \t \4!"+
		"\t!\4\"\t\"\4#\t#\4$\t$\4%\t%\4&\t&\4\'\t\'\4(\t(\4)\t)\4*\t*\4+\t+\4"+
		",\t,\4-\t-\4.\t.\4/\t/\4\60\t\60\4\61\t\61\4\62\t\62\4\63\t\63\4\64\t"+
		"\64\4\65\t\65\4\66\t\66\4\67\t\67\48\t8\49\t9\4:\t:\4;\t;\4<\t<\4=\t="+
		"\4>\t>\4?\t?\4@\t@\4A\tA\4B\tB\4C\tC\4D\tD\4E\tE\4F\tF\4G\tG\4H\tH\4I"+
		"\tI\4J\tJ\4K\tK\4L\tL\4M\tM\4N\tN\4O\tO\4P\tP\4Q\tQ\4R\tR\4S\tS\4T\tT"+
		"\4U\tU\4V\tV\4W\tW\4X\tX\4Y\tY\4Z\tZ\4[\t[\4\\\t\\\4]\t]\4^\t^\4_\t_\4"+
		"`\t`\4a\ta\4b\tb\4c\tc\4d\td\4e\te\4f\tf\4g\tg\4h\th\4i\ti\4j\tj\4k\t"+
		"k\4l\tl\4m\tm\4n\tn\4o\to\4p\tp\4q\tq\4r\tr\4s\ts\4t\tt\4u\tu\4v\tv\4"+
		"w\tw\4x\tx\4y\ty\4z\tz\4{\t{\4|\t|\4}\t}\4~\t~\4\177\t\177\4\u0080\t\u0080"+
		"\4\u0081\t\u0081\4\u0082\t\u0082\4\u0083\t\u0083\4\u0084\t\u0084\3\2\5"+
		"\2\u010a\n\2\3\2\7\2\u010d\n\2\f\2\16\2\u0110\13\2\3\2\7\2\u0113\n\2\f"+
		"\2\16\2\u0116\13\2\3\2\3\2\3\3\3\3\3\3\5\3\u011d\n\3\3\3\7\3\u0120\n\3"+
		"\f\3\16\3\u0123\13\3\3\4\3\4\7\4\u0127\n\4\f\4\16\4\u012a\13\4\3\4\3\4"+
		"\3\4\3\4\3\4\3\4\7\4\u0132\n\4\f\4\16\4\u0135\13\4\3\4\3\4\3\4\3\4\3\4"+
		"\7\4\u013c\n\4\f\4\16\4\u013f\13\4\3\4\3\4\3\4\3\4\3\4\3\4\3\4\5\4\u0148"+
		"\n\4\3\4\3\4\3\4\7\4\u014d\n\4\f\4\16\4\u0150\13\4\3\4\5\4\u0153\n\4\3"+
		"\5\5\5\u0156\n\5\3\5\3\5\3\5\5\5\u015b\n\5\3\5\5\5\u015e\n\5\7\5\u0160"+
		"\n\5\f\5\16\5\u0163\13\5\3\6\3\6\3\6\3\6\3\6\7\6\u016a\n\6\f\6\16\6\u016d"+
		"\13\6\3\6\3\6\3\6\3\6\3\6\7\6\u0174\n\6\f\6\16\6\u0177\13\6\3\6\3\6\3"+
		"\6\3\6\3\6\5\6\u017e\n\6\3\7\3\7\5\7\u0182\n\7\3\b\3\b\5\b\u0186\n\b\3"+
		"\t\7\t\u0189\n\t\f\t\16\t\u018c\13\t\3\n\3\n\6\n\u0190\n\n\r\n\16\n\u0191"+
		"\5\n\u0194\n\n\3\13\7\13\u0197\n\13\f\13\16\13\u019a\13\13\3\13\6\13\u019d"+
		"\n\13\r\13\16\13\u019e\3\f\3\f\3\f\3\f\3\f\3\r\3\r\3\r\3\r\3\r\3\r\3\r"+
		"\5\r\u01ad\n\r\3\16\3\16\5\16\u01b1\n\16\3\16\3\16\3\16\3\17\5\17\u01b7"+
		"\n\17\3\17\3\17\3\17\5\17\u01bc\n\17\3\17\7\17\u01bf\n\17\f\17\16\17\u01c2"+
		"\13\17\3\20\3\20\3\20\5\20\u01c7\n\20\3\21\3\21\5\21\u01cb\n\21\3\21\3"+
		"\21\7\21\u01cf\n\21\f\21\16\21\u01d2\13\21\3\21\3\21\3\21\3\21\5\21\u01d8"+
		"\n\21\3\22\3\22\3\22\3\22\3\22\5\22\u01df\n\22\3\23\3\23\3\23\5\23\u01e4"+
		"\n\23\3\23\3\23\5\23\u01e8\n\23\3\23\3\23\3\23\3\23\3\23\3\24\3\24\5\24"+
		"\u01f1\n\24\3\24\5\24\u01f4\n\24\3\24\5\24\u01f7\n\24\3\24\3\24\3\24\5"+
		"\24\u01fc\n\24\3\24\3\24\5\24\u0200\n\24\3\24\3\24\5\24\u0204\n\24\3\24"+
		"\3\24\3\24\5\24\u0209\n\24\3\24\3\24\5\24\u020d\n\24\5\24\u020f\n\24\3"+
		"\24\3\24\7\24\u0213\n\24\f\24\16\24\u0216\13\24\3\24\3\24\3\25\3\25\3"+
		"\26\3\26\3\26\7\26\u021f\n\26\f\26\16\26\u0222\13\26\3\27\3\27\3\27\3"+
		"\27\3\27\3\27\3\27\3\27\3\27\3\27\3\27\3\27\3\27\3\27\5\27\u0232\n\27"+
		"\3\30\3\30\3\30\7\30\u0237\n\30\f\30\16\30\u023a\13\30\3\31\3\31\3\31"+
		"\7\31\u023f\n\31\f\31\16\31\u0242\13\31\3\32\3\32\3\32\3\33\3\33\3\33"+
		"\3\33\3\33\5\33\u024c\n\33\3\34\3\34\3\34\3\34\7\34\u0252\n\34\f\34\16"+
		"\34\u0255\13\34\3\34\3\34\3\35\7\35\u025a\n\35\f\35\16\35\u025d\13\35"+
		"\3\36\3\36\3\36\3\36\5\36\u0263\n\36\3\36\3\36\3\36\7\36\u0268\n\36\f"+
		"\36\16\36\u026b\13\36\3\36\3\36\3\37\3\37\3\37\3\37\3\37\3\37\3\37\3\37"+
		"\3\37\3\37\3\37\3\37\3\37\3\37\3\37\3\37\3\37\3\37\5\37\u0281\n\37\3 "+
		"\3 \3 \7 \u0286\n \f \16 \u0289\13 \3!\3!\3!\7!\u028e\n!\f!\16!\u0291"+
		"\13!\3\"\3\"\3\"\3\"\3#\7#\u0298\n#\f#\16#\u029b\13#\3$\3$\3$\5$\u02a0"+
		"\n$\3%\3%\5%\u02a4\n%\3&\3&\3\'\3\'\3\'\3\'\3\'\3\'\3\'\3\'\3\'\3\'\3"+
		"\'\3\'\3\'\3\'\3\'\3\'\3\'\3\'\3\'\3\'\3\'\3\'\3\'\3\'\3\'\3\'\5\'\u02c2"+
		"\n\'\3(\3(\3(\3(\3)\3)\3)\3)\7)\u02cc\n)\f)\16)\u02cf\13)\3)\5)\u02d2"+
		"\n)\3)\3)\3)\3)\3)\7)\u02d9\n)\f)\16)\u02dc\13)\3)\5)\u02df\n)\3)\3)\3"+
		")\5)\u02e4\n)\3*\3*\3*\3*\3+\3+\3+\3+\3+\3,\3,\3,\3-\3-\3-\3-\3.\3.\3"+
		".\3.\3.\3.\3.\3.\5.\u02fe\n.\3/\3/\3/\3/\3/\3/\3\60\3\60\3\60\5\60\u0309"+
		"\n\60\3\60\3\60\5\60\u030d\n\60\3\60\3\60\5\60\u0311\n\60\3\60\3\60\3"+
		"\60\3\60\3\60\3\60\3\60\5\60\u031a\n\60\3\61\3\61\3\62\3\62\3\63\3\63"+
		"\3\63\3\63\5\63\u0324\n\63\3\63\7\63\u0327\n\63\f\63\16\63\u032a\13\63"+
		"\3\63\3\63\3\63\5\63\u032f\n\63\3\63\7\63\u0332\n\63\f\63\16\63\u0335"+
		"\13\63\3\63\3\63\5\63\u0339\n\63\3\64\3\64\3\64\5\64\u033e\n\64\3\64\6"+
		"\64\u0341\n\64\r\64\16\64\u0342\3\64\3\64\3\65\3\65\5\65\u0349\n\65\3"+
		"\65\3\65\3\66\3\66\5\66\u034f\n\66\3\66\3\66\3\67\3\67\5\67\u0355\n\67"+
		"\3\67\3\67\38\38\38\39\39\39\39\39\39\3:\3:\3:\3:\3:\5:\u0367\n:\3:\3"+
		":\3:\5:\u036c\n:\3:\5:\u036f\n:\3:\3:\3:\3:\3:\3:\3:\3:\5:\u0379\n:\3"+
		":\5:\u037c\n:\3:\3:\3:\3:\3:\3:\3:\3:\3:\3:\3:\5:\u0389\n:\3:\3:\3:\3"+
		":\3:\3:\5:\u0391\n:\3;\3;\3;\6;\u0396\n;\r;\16;\u0397\3;\5;\u039b\n;\3"+
		";\7;\u039e\n;\f;\16;\u03a1\13;\3;\5;\u03a4\n;\3<\3<\3<\3<\3<\3<\3<\3="+
		"\3=\3=\3>\3>\3>\3>\3?\3?\3?\3?\3@\3@\3@\3@\3@\3@\3@\3@\3@\3@\5@\u03c2"+
		"\n@\3A\7A\u03c5\nA\fA\16A\u03c8\13A\3A\3A\5A\u03cc\nA\3A\7A\u03cf\nA\f"+
		"A\16A\u03d2\13A\6A\u03d4\nA\rA\16A\u03d5\3B\3B\3B\7B\u03db\nB\fB\16B\u03de"+
		"\13B\3C\5C\u03e1\nC\3C\3C\7C\u03e5\nC\fC\16C\u03e8\13C\3D\3D\5D\u03ec"+
		"\nD\3D\5D\u03ef\nD\3D\5D\u03f2\nD\3D\3D\3E\3E\3E\5E\u03f9\nE\3F\3F\3F"+
		"\3F\7F\u03ff\nF\fF\16F\u0402\13F\3F\3F\3G\3G\3G\3G\3G\3G\3G\3G\5G\u040e"+
		"\nG\3H\3H\3H\3H\3I\3I\3I\3I\7I\u0418\nI\fI\16I\u041b\13I\3I\3I\3J\3J\3"+
		"J\3J\3J\7J\u0424\nJ\fJ\16J\u0427\13J\3J\3J\3J\3J\3J\3J\3J\7J\u0430\nJ"+
		"\fJ\16J\u0433\13J\3J\3J\3J\3J\5J\u0439\nJ\3J\3J\5J\u043d\nJ\3J\3J\5J\u0441"+
		"\nJ\3J\3J\3J\3J\5J\u0447\nJ\3J\3J\3J\3J\3J\3J\5J\u044f\nJ\3K\3K\3K\7K"+
		"\u0454\nK\fK\16K\u0457\13K\3K\5K\u045a\nK\3L\3L\5L\u045e\nL\3M\3M\3M\3"+
		"M\3M\3M\3M\3N\3N\3N\3N\5N\u046b\nN\3N\5N\u046e\nN\3N\3N\3O\3O\3O\5O\u0475"+
		"\nO\3O\3O\3P\3P\3P\3P\3Q\3Q\5Q\u047f\nQ\3R\3R\5R\u0483\nR\3S\6S\u0486"+
		"\nS\rS\16S\u0487\3T\3T\3T\5T\u048d\nT\3U\3U\3U\3U\3V\3V\3V\3V\3V\7V\u0498"+
		"\nV\fV\16V\u049b\13V\3V\3V\3W\3W\3W\7W\u04a2\nW\fW\16W\u04a5\13W\3X\3"+
		"X\3X\5X\u04aa\nX\3X\3X\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3"+
		"Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\5Y\u04ce\nY\3Y\3Y\3"+
		"Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\5Y\u04db\nY\3Y\3Y\3Y\5Y\u04e0\nY\3Y\5Y\u04e3"+
		"\nY\3Y\3Y\3Y\3Y\5Y\u04e9\nY\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y"+
		"\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\5Y\u0506\nY\3Y\3Y\3Y\3Y\3Y\3Y"+
		"\3Y\3Y\3Y\5Y\u0511\nY\3Y\3Y\5Y\u0515\nY\3Y\3Y\3Y\3Y\5Y\u051b\nY\3Y\3Y"+
		"\5Y\u051f\nY\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y"+
		"\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\5Y\u0545\nY\3Y\3Y"+
		"\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\3Y\7Y\u0555\nY\fY\16Y\u0558\13Y\3Z\3"+
		"Z\3Z\5Z\u055d\nZ\3[\3[\3\\\3\\\3\\\3\\\5\\\u0565\n\\\3]\3]\3]\7]\u056a"+
		"\n]\f]\16]\u056d\13]\3]\5]\u0570\n]\3^\3^\3^\5^\u0575\n^\3^\3^\3^\5^\u057a"+
		"\n^\3^\3^\5^\u057e\n^\3_\3_\3_\3_\3_\7_\u0585\n_\f_\16_\u0588\13_\3_\3"+
		"_\3`\5`\u058d\n`\3`\3`\3a\3a\5a\u0593\na\3a\5a\u0596\na\3b\3b\5b\u059a"+
		"\nb\3b\5b\u059d\nb\3b\3b\5b\u05a1\nb\3c\3c\3c\7c\u05a6\nc\fc\16c\u05a9"+
		"\13c\3d\5d\u05ac\nd\3d\5d\u05af\nd\3d\3d\3e\3e\3e\7e\u05b6\ne\fe\16e\u05b9"+
		"\13e\3f\3f\3f\7f\u05be\nf\ff\16f\u05c1\13f\3g\3g\3g\3g\7g\u05c7\ng\fg"+
		"\16g\u05ca\13g\3g\5g\u05cd\ng\3g\3g\3h\5h\u05d2\nh\3h\3h\3h\5h\u05d7\n"+
		"h\3i\3i\3i\3i\3i\3i\5i\u05df\ni\5i\u05e1\ni\3i\3i\3i\3i\5i\u05e7\ni\5"+
		"i\u05e9\ni\3i\3i\3i\5i\u05ee\ni\3j\3j\3j\7j\u05f3\nj\fj\16j\u05f6\13j"+
		"\3k\3k\3k\5k\u05fb\nk\3l\3l\3l\3l\3l\5l\u0602\nl\3m\3m\3m\3m\5m\u0608"+
		"\nm\3n\3n\3n\3n\3n\3n\5n\u0610\nn\3n\3n\5n\u0614\nn\3n\3n\3n\5n\u0619"+
		"\nn\3o\3o\3p\3p\6p\u061f\np\rp\16p\u0620\3p\3p\6p\u0625\np\rp\16p\u0626"+
		"\3p\3p\5p\u062b\np\3q\3q\3q\7q\u0630\nq\fq\16q\u0633\13q\3r\3r\3r\3r\3"+
		"r\3r\5r\u063b\nr\3r\7r\u063e\nr\fr\16r\u0641\13r\3s\3s\3s\5s\u0646\ns"+
		"\3t\3t\3t\3u\3u\3u\5u\u064e\nu\3v\5v\u0651\nv\3v\3v\7v\u0655\nv\fv\16"+
		"v\u0658\13v\3w\3w\3w\5w\u065d\nw\3w\3w\3w\3w\5w\u0663\nw\3x\3x\5x\u0667"+
		"\nx\3y\3y\3y\3y\3y\5y\u066e\ny\3y\7y\u0671\ny\fy\16y\u0674\13y\3z\7z\u0677"+
		"\nz\fz\16z\u067a\13z\3z\3z\3z\3z\3z\3z\5z\u0682\nz\3z\7z\u0685\nz\fz\16"+
		"z\u0688\13z\3{\3{\5{\u068c\n{\3{\3{\3{\3{\3{\5{\u0693\n{\3|\5|\u0696\n"+
		"|\3|\3|\5|\u069a\n|\7|\u069c\n|\f|\16|\u069f\13|\3}\3}\3}\3}\3}\3}\5}"+
		"\u06a7\n}\3~\3~\3\177\3\177\3\177\3\177\3\177\3\177\3\177\3\177\3\177"+
		"\3\177\3\177\3\177\3\177\3\177\3\177\3\177\3\177\3\177\3\177\3\177\3\177"+
		"\3\177\3\177\3\177\3\177\3\177\3\177\3\177\3\177\3\177\3\177\3\177\5\177"+
		"\u06cb\n\177\3\u0080\3\u0080\3\u0081\3\u0081\3\u0082\3\u0082\3\u0083\3"+
		"\u0083\3\u0084\3\u0084\3\u0084\2\3\u00b0\u0085\2\4\6\b\n\f\16\20\22\24"+
		"\26\30\32\34\36 \"$&(*,.\60\62\64\668:<>@BDFHJLNPRTVXZ\\^`bdfhjlnprtv"+
		"xz|~\u0080\u0082\u0084\u0086\u0088\u008a\u008c\u008e\u0090\u0092\u0094"+
		"\u0096\u0098\u009a\u009c\u009e\u00a0\u00a2\u00a4\u00a6\u00a8\u00aa\u00ac"+
		"\u00ae\u00b0\u00b2\u00b4\u00b6\u00b8\u00ba\u00bc\u00be\u00c0\u00c2\u00c4"+
		"\u00c6\u00c8\u00ca\u00cc\u00ce\u00d0\u00d2\u00d4\u00d6\u00d8\u00da\u00dc"+
		"\u00de\u00e0\u00e2\u00e4\u00e6\u00e8\u00ea\u00ec\u00ee\u00f0\u00f2\u00f4"+
		"\u00f6\u00f8\u00fa\u00fc\u00fe\u0100\u0102\u0104\u0106\2\27\3\2\23\24"+
		"\4\2\62\62KK\4\2\60\60qq\3\2\u00cd\u00ce\3\2\u0099\u009a\3\2\u00c1\u00c2"+
		"\4\2\u00ba\u00ba\u00bc\u00bd\3\2\u00be\u00c0\4\2\u00bc\u00bd\u00c4\u00c4"+
		"\3\2\u00b0\u00b1\4\2\u009f\u00a0\u00b6\u00b7\3\2\u009b\u009e\5\2\u00a1"+
		"\u00a3\u00a5\u00ad\u00cf\u00cf\3\2\u00bc\u00bd\4\2\60\60cc\4\2&&FF\6\2"+
		"&&FFfhmm\3\2\u008e\u0095\3\2~\u008d\n\2\'\'**88VVYYbbkknn\f\2\'\')*\67"+
		"8HHUWYYbbkknnty\u07a1\2\u0109\3\2\2\2\4\u011c\3\2\2\2\6\u0152\3\2\2\2"+
		"\b\u0155\3\2\2\2\n\u017d\3\2\2\2\f\u0181\3\2\2\2\16\u0185\3\2\2\2\20\u018a"+
		"\3\2\2\2\22\u0193\3\2\2\2\24\u0198\3\2\2\2\26\u01a0\3\2\2\2\30\u01ac\3"+
		"\2\2\2\32\u01ae\3\2\2\2\34\u01b6\3\2\2\2\36\u01c3\3\2\2\2 \u01c8\3\2\2"+
		"\2\"\u01de\3\2\2\2$\u01e0\3\2\2\2&\u01ee\3\2\2\2(\u0219\3\2\2\2*\u021b"+
		"\3\2\2\2,\u0231\3\2\2\2.\u0233\3\2\2\2\60\u023b\3\2\2\2\62\u0243\3\2\2"+
		"\2\64\u0246\3\2\2\2\66\u024d\3\2\2\28\u025b\3\2\2\2:\u025e\3\2\2\2<\u0280"+
		"\3\2\2\2>\u0282\3\2\2\2@\u028a\3\2\2\2B\u0292\3\2\2\2D\u0299\3\2\2\2F"+
		"\u029f\3\2\2\2H\u02a3\3\2\2\2J\u02a5\3\2\2\2L\u02c1\3\2\2\2N\u02c3\3\2"+
		"\2\2P\u02e3\3\2\2\2R\u02e5\3\2\2\2T\u02e9\3\2\2\2V\u02ee\3\2\2\2X\u02f1"+
		"\3\2\2\2Z\u02f5\3\2\2\2\\\u02ff\3\2\2\2^\u0305\3\2\2\2`\u031b\3\2\2\2"+
		"b\u031d\3\2\2\2d\u031f\3\2\2\2f\u0340\3\2\2\2h\u0346\3\2\2\2j\u034c\3"+
		"\2\2\2l\u0352\3\2\2\2n\u0358\3\2\2\2p\u035b\3\2\2\2r\u0361\3\2\2\2t\u0392"+
		"\3\2\2\2v\u03a5\3\2\2\2x\u03ac\3\2\2\2z\u03af\3\2\2\2|\u03b3\3\2\2\2~"+
		"\u03b7\3\2\2\2\u0080\u03c6\3\2\2\2\u0082\u03d7\3\2\2\2\u0084\u03e0\3\2"+
		"\2\2\u0086\u03e9\3\2\2\2\u0088\u03f8\3\2\2\2\u008a\u03fa\3\2\2\2\u008c"+
		"\u040d\3\2\2\2\u008e\u040f\3\2\2\2\u0090\u0413\3\2\2\2\u0092\u044e\3\2"+
		"\2\2\u0094\u0459\3\2\2\2\u0096\u045d\3\2\2\2\u0098\u045f\3\2\2\2\u009a"+
		"\u0466\3\2\2\2\u009c\u0474\3\2\2\2\u009e\u0478\3\2\2\2\u00a0\u047e\3\2"+
		"\2\2\u00a2\u0482\3\2\2\2\u00a4\u0485\3\2\2\2\u00a6\u0489\3\2\2\2\u00a8"+
		"\u048e\3\2\2\2\u00aa\u0492\3\2\2\2\u00ac\u049e\3\2\2\2\u00ae\u04a6\3\2"+
		"\2\2\u00b0\u051e\3\2\2\2\u00b2\u0559\3\2\2\2\u00b4\u055e\3\2\2\2\u00b6"+
		"\u0560\3\2\2\2\u00b8\u0566\3\2\2\2\u00ba\u057d\3\2\2\2\u00bc\u057f\3\2"+
		"\2\2\u00be\u058c\3\2\2\2\u00c0\u0595\3\2\2\2\u00c2\u05a0\3\2\2\2\u00c4"+
		"\u05a2\3\2\2\2\u00c6\u05ab\3\2\2\2\u00c8\u05b2\3\2\2\2\u00ca\u05ba\3\2"+
		"\2\2\u00cc\u05c2\3\2\2\2\u00ce\u05d6\3\2\2\2\u00d0\u05ed\3\2\2\2\u00d2"+
		"\u05ef\3\2\2\2\u00d4\u05f7\3\2\2\2\u00d6\u0601\3\2\2\2\u00d8\u0607\3\2"+
		"\2\2\u00da\u0618\3\2\2\2\u00dc\u061a\3\2\2\2\u00de\u062a\3\2\2\2\u00e0"+
		"\u062c\3\2\2\2\u00e2\u063a\3\2\2\2\u00e4\u0642\3\2\2\2\u00e6\u0647\3\2"+
		"\2\2\u00e8\u064d\3\2\2\2\u00ea\u0650\3\2\2\2\u00ec\u0662\3\2\2\2\u00ee"+
		"\u0666\3\2\2\2\u00f0\u066d\3\2\2\2\u00f2\u0678\3\2\2\2\u00f4\u0692\3\2"+
		"\2\2\u00f6\u0695\3\2\2\2\u00f8\u06a6\3\2\2\2\u00fa\u06a8\3\2\2\2\u00fc"+
		"\u06ca\3\2\2\2\u00fe\u06cc\3\2\2\2\u0100\u06ce\3\2\2\2\u0102\u06d0\3\2"+
		"\2\2\u0104\u06d2\3\2\2\2\u0106\u06d4\3\2\2\2\u0108\u010a\7\13\2\2\u0109"+
		"\u0108\3\2\2\2\u0109\u010a\3\2\2\2\u010a\u010e\3\2\2\2\u010b\u010d\7\b"+
		"\2\2\u010c\u010b\3\2\2\2\u010d\u0110\3\2\2\2\u010e\u010c\3\2\2\2\u010e"+
		"\u010f\3\2\2\2\u010f\u0114\3\2\2\2\u0110\u010e\3\2\2\2\u0111\u0113\5\4"+
		"\3\2\u0112\u0111\3\2\2\2\u0113\u0116\3\2\2\2\u0114\u0112\3\2\2\2\u0114"+
		"\u0115\3\2\2\2\u0115\u0117\3\2\2\2\u0116\u0114\3\2\2\2\u0117\u0118\7\2"+
		"\2\3\u0118\3\3\2\2\2\u0119\u011d\7\t\2\2\u011a\u011d\5\6\4\2\u011b\u011d"+
		"\5\24\13\2\u011c\u0119\3\2\2\2\u011c\u011a\3\2\2\2\u011c\u011b\3\2\2\2"+
		"\u011d\u0121\3\2\2\2\u011e\u0120\7\b\2\2\u011f\u011e\3\2\2\2\u0120\u0123"+
		"\3\2\2\2\u0121\u011f\3\2\2\2\u0121\u0122\3\2\2\2\u0122\5\3\2\2\2\u0123"+
		"\u0121\3\2\2\2\u0124\u0128\7\6\2\2\u0125\u0127\5\n\6\2\u0126\u0125\3\2"+
		"\2\2\u0127\u012a\3\2\2\2\u0128\u0126\3\2\2\2\u0128\u0129\3\2\2\2\u0129"+
		"\u012b\3\2\2\2\u012a\u0128\3\2\2\2\u012b\u012c\7\r\2\2\u012c\u012d\5\20"+
		"\t\2\u012d\u012e\7\36\2\2\u012e\u0153\3\2\2\2\u012f\u0133\7\7\2\2\u0130"+
		"\u0132\5\n\6\2\u0131\u0130\3\2\2\2\u0132\u0135\3\2\2\2\u0133\u0131\3\2"+
		"\2\2\u0133\u0134\3\2\2\2\u0134\u0136\3\2\2\2\u0135\u0133\3\2\2\2\u0136"+
		"\u0137\7\r\2\2\u0137\u0153\7 \2\2\u0138\u0139\7\n\2\2\u0139\u013d\7\26"+
		"\2\2\u013a\u013c\5\n\6\2\u013b\u013a\3\2\2\2\u013c\u013f\3\2\2\2\u013d"+
		"\u013b\3\2\2\2\u013d\u013e\3\2\2\2\u013e\u0140\3\2\2\2\u013f\u013d\3\2"+
		"\2\2\u0140\u0147\7\r\2\2\u0141\u0142\5\b\5\2\u0142\u0143\7\n\2\2\u0143"+
		"\u0144\7\17\2\2\u0144\u0145\7\26\2\2\u0145\u0146\7\r\2\2\u0146\u0148\3"+
		"\2\2\2\u0147\u0141\3\2\2\2\u0147\u0148\3\2\2\2\u0148\u0153\3\2\2\2\u0149"+
		"\u014a\7\n\2\2\u014a\u014e\7\26\2\2\u014b\u014d\5\n\6\2\u014c\u014b\3"+
		"\2\2\2\u014d\u0150\3\2\2\2\u014e\u014c\3\2\2\2\u014e\u014f\3\2\2\2\u014f"+
		"\u0151\3\2\2\2\u0150\u014e\3\2\2\2\u0151\u0153\7\16\2\2\u0152\u0124\3"+
		"\2\2\2\u0152\u012f\3\2\2\2\u0152\u0138\3\2\2\2\u0152\u0149\3\2\2\2\u0153"+
		"\7\3\2\2\2\u0154\u0156\7\4\2\2\u0155\u0154\3\2\2\2\u0155\u0156\3\2\2\2"+
		"\u0156\u0161\3\2\2\2\u0157\u015b\5\6\4\2\u0158\u015b\7\b\2\2\u0159\u015b"+
		"\5\24\13\2\u015a\u0157\3\2\2\2\u015a\u0158\3\2\2\2\u015a\u0159\3\2\2\2"+
		"\u015b\u015d\3\2\2\2\u015c\u015e\7\4\2\2\u015d\u015c\3\2\2\2\u015d\u015e"+
		"\3\2\2\2\u015e\u0160\3\2\2\2\u015f\u015a\3\2\2\2\u0160\u0163\3\2\2\2\u0161"+
		"\u015f\3\2\2\2\u0161\u0162\3\2\2\2\u0162\t\3\2\2\2\u0163\u0161\3\2\2\2"+
		"\u0164\u017e\5\24\13\2\u0165\u0166\7\26\2\2\u0166\u0167\7\20\2\2\u0167"+
		"\u016b\7\21\2\2\u0168\u016a\5\f\7\2\u0169\u0168\3\2\2\2\u016a\u016d\3"+
		"\2\2\2\u016b\u0169\3\2\2\2\u016b\u016c\3\2\2\2\u016c\u016e\3\2\2\2\u016d"+
		"\u016b\3\2\2\2\u016e\u017e\7\30\2\2\u016f\u0170\7\26\2\2\u0170\u0171\7"+
		"\20\2\2\u0171\u0175\7\22\2\2\u0172\u0174\5\16\b\2\u0173\u0172\3\2\2\2"+
		"\u0174\u0177\3\2\2\2\u0175\u0173\3\2\2\2\u0175\u0176\3\2\2\2\u0176\u0178"+
		"\3\2\2\2\u0177\u0175\3\2\2\2\u0178\u017e\7\33\2\2\u0179\u017a\7\26\2\2"+
		"\u017a\u017b\7\20\2\2\u017b\u017e\t\2\2\2\u017c\u017e\7\26\2\2\u017d\u0164"+
		"\3\2\2\2\u017d\u0165\3\2\2\2\u017d\u016f\3\2\2\2\u017d\u0179\3\2\2\2\u017d"+
		"\u017c\3\2\2\2\u017e\13\3\2\2\2\u017f\u0182\5\24\13\2\u0180\u0182\7\31"+
		"\2\2\u0181\u017f\3\2\2\2\u0181\u0180\3\2\2\2\u0182\r\3\2\2\2\u0183\u0186"+
		"\5\24\13\2\u0184\u0186\7\34\2\2\u0185\u0183\3\2\2\2\u0185\u0184\3\2\2"+
		"\2\u0186\17\3\2\2\2\u0187\u0189\5\22\n\2\u0188\u0187\3\2\2\2\u0189\u018c"+
		"\3\2\2\2\u018a\u0188\3\2\2\2\u018a\u018b\3\2\2\2\u018b\21\3\2\2\2\u018c"+
		"\u018a\3\2\2\2\u018d\u0194\5\24\13\2\u018e\u0190\7\35\2\2\u018f\u018e"+
		"\3\2\2\2\u0190\u0191\3\2\2\2\u0191\u018f\3\2\2\2\u0191\u0192\3\2\2\2\u0192"+
		"\u0194\3\2\2\2\u0193\u018d\3\2\2\2\u0193\u018f\3\2\2\2\u0194\23\3\2\2"+
		"\2\u0195\u0197\5\26\f\2\u0196\u0195\3\2\2\2\u0197\u019a\3\2\2\2\u0198"+
		"\u0196\3\2\2\2\u0198\u0199\3\2\2\2\u0199\u019c\3\2\2\2\u019a\u0198\3\2"+
		"\2\2\u019b\u019d\5\30\r\2\u019c\u019b\3\2\2\2\u019d\u019e\3\2\2\2\u019e"+
		"\u019c\3\2\2\2\u019e\u019f\3\2\2\2\u019f\25\3\2\2\2\u01a0\u01a1\7P\2\2"+
		"\u01a1\u01a2\7_\2\2\u01a2\u01a3\5\u00c8e\2\u01a3\u01a4\7\u00ce\2\2\u01a4"+
		"\27\3\2\2\2\u01a5\u01ad\5J&\2\u01a6\u01ad\5L\'\2\u01a7\u01ad\5\32\16\2"+
		"\u01a8\u01ad\5 \21\2\u01a9\u01ad\5$\23\2\u01aa\u01ad\5&\24\2\u01ab\u01ad"+
		"\5\u00aaV\2\u01ac\u01a5\3\2\2\2\u01ac\u01a6\3\2\2\2\u01ac\u01a7\3\2\2"+
		"\2\u01ac\u01a8\3\2\2\2\u01ac\u01a9\3\2\2\2\u01ac\u01aa\3\2\2\2\u01ac\u01ab"+
		"\3\2\2\2\u01ad\31\3\2\2\2\u01ae\u01b0\7z\2\2\u01af\u01b1\t\3\2\2\u01b0"+
		"\u01af\3\2\2\2\u01b0\u01b1\3\2\2\2\u01b1\u01b2\3\2\2\2\u01b2\u01b3\5\34"+
		"\17\2\u01b3\u01b4\7\u00ce\2\2\u01b4\33\3\2\2\2\u01b5\u01b7\7\u00b4\2\2"+
		"\u01b6\u01b5\3\2\2\2\u01b6\u01b7\3\2\2\2\u01b7\u01b8\3\2\2\2\u01b8\u01c0"+
		"\5\36\20\2\u01b9\u01bb\7\u00cc\2\2\u01ba\u01bc\7\u00b4\2\2\u01bb\u01ba"+
		"\3\2\2\2\u01bb\u01bc\3\2\2\2\u01bc\u01bd\3\2\2\2\u01bd\u01bf\5\36\20\2"+
		"\u01be\u01b9\3\2\2\2\u01bf\u01c2\3\2\2\2\u01c0\u01be\3\2\2\2\u01c0\u01c1"+
		"\3\2\2\2\u01c1\35\3\2\2\2\u01c2\u01c0\3\2\2\2\u01c3\u01c6\5\u00c8e\2\u01c4"+
		"\u01c5\7(\2\2\u01c5\u01c7\5\u00fc\177\2\u01c6\u01c4\3\2\2\2\u01c6\u01c7"+
		"\3\2\2\2\u01c7\37\3\2\2\2\u01c8\u01d7\7_\2\2\u01c9\u01cb\5\u00c8e\2\u01ca"+
		"\u01c9\3\2\2\2\u01ca\u01cb\3\2\2\2\u01cb\u01cc\3\2\2\2\u01cc\u01d0\7\u00ca"+
		"\2\2\u01cd\u01cf\5\"\22\2\u01ce\u01cd\3\2\2\2\u01cf\u01d2\3\2\2\2\u01d0"+
		"\u01ce\3\2\2\2\u01d0\u01d1\3\2\2\2\u01d1\u01d3\3\2\2\2\u01d2\u01d0\3\2"+
		"\2\2\u01d3\u01d8\7\u00cb\2\2\u01d4\u01d5\5\u00c8e\2\u01d5\u01d6\7\u00ce"+
		"\2\2\u01d6\u01d8\3\2\2\2\u01d7\u01ca\3\2\2\2\u01d7\u01d4\3\2\2\2\u01d8"+
		"!\3\2\2\2\u01d9\u01df\5L\'\2\u01da\u01df\5\32\16\2\u01db\u01df\5$\23\2"+
		"\u01dc\u01df\5&\24\2\u01dd\u01df\5\u00aaV\2\u01de\u01d9\3\2\2\2\u01de"+
		"\u01da\3\2\2\2\u01de\u01db\3\2\2\2\u01de\u01dc\3\2\2\2\u01de\u01dd\3\2"+
		"\2\2\u01df#\3\2\2\2\u01e0\u01e1\58\35\2\u01e1\u01e3\7K\2\2\u01e2\u01e4"+
		"\7\u00b8\2\2\u01e3\u01e2\3\2\2\2\u01e3\u01e4\3\2\2\2\u01e4\u01e5\3\2\2"+
		"\2\u01e5\u01e7\5\u00fc\177\2\u01e6\u01e8\5,\27\2\u01e7\u01e6\3\2\2\2\u01e7"+
		"\u01e8\3\2\2\2\u01e8\u01e9\3\2\2\2\u01e9\u01ea\7\u00c6\2\2\u01ea\u01eb"+
		"\5\u0084C\2\u01eb\u01ec\7\u00c7\2\2\u01ec\u01ed\5N(\2\u01ed%\3\2\2\2\u01ee"+
		"\u01f0\58\35\2\u01ef\u01f1\7f\2\2\u01f0\u01ef\3\2\2\2\u01f0\u01f1\3\2"+
		"\2\2\u01f1\u01f3\3\2\2\2\u01f2\u01f4\5\u00fa~\2\u01f3\u01f2\3\2\2\2\u01f3"+
		"\u01f4\3\2\2\2\u01f4\u01f6\3\2\2\2\u01f5\u01f7\7d\2\2\u01f6\u01f5\3\2"+
		"\2\2\u01f6\u01f7\3\2\2\2\u01f7\u020e\3\2\2\2\u01f8\u01f9\5(\25\2\u01f9"+
		"\u01fb\5\u00fc\177\2\u01fa\u01fc\5,\27\2\u01fb\u01fa\3\2\2\2\u01fb\u01fc"+
		"\3\2\2\2\u01fc\u01ff\3\2\2\2\u01fd\u01fe\7E\2\2\u01fe\u0200\5\u00c0a\2"+
		"\u01ff\u01fd\3\2\2\2\u01ff\u0200\3\2\2\2\u0200\u0203\3\2\2\2\u0201\u0202"+
		"\7O\2\2\u0202\u0204\5*\26\2\u0203\u0201\3\2\2\2\u0203\u0204\3\2\2\2\u0204"+
		"\u020f\3\2\2\2\u0205\u0206\7X\2\2\u0206\u0208\5\u00fc\177\2\u0207\u0209"+
		"\5,\27\2\u0208\u0207\3\2\2\2\u0208\u0209\3\2\2\2\u0209\u020c\3\2\2\2\u020a"+
		"\u020b\7E\2\2\u020b\u020d\5*\26\2\u020c\u020a\3\2\2\2\u020c\u020d\3\2"+
		"\2\2\u020d\u020f\3\2\2\2\u020e\u01f8\3\2\2\2\u020e\u0205\3\2\2\2\u020f"+
		"\u0210\3\2\2\2\u0210\u0214\7\u00ca\2\2\u0211\u0213\5\u0092J\2\u0212\u0211"+
		"\3\2\2\2\u0213\u0216\3\2\2\2\u0214\u0212\3\2\2\2\u0214\u0215\3\2\2\2\u0215"+
		"\u0217\3\2\2\2\u0216\u0214\3\2\2\2\u0217\u0218\7\u00cb\2\2\u0218\'\3\2"+
		"\2\2\u0219\u021a\t\4\2\2\u021a)\3\2\2\2\u021b\u0220\5\u00c0a\2\u021c\u021d"+
		"\7\u00cc\2\2\u021d\u021f\5\u00c0a\2\u021e\u021c\3\2\2\2\u021f\u0222\3"+
		"\2\2\2\u0220\u021e\3\2\2\2\u0220\u0221\3\2\2\2\u0221+\3\2\2\2\u0222\u0220"+
		"\3\2\2\2\u0223\u0224\7\u0096\2\2\u0224\u0225\5.\30\2\u0225\u0226\7\u0097"+
		"\2\2\u0226\u0232\3\2\2\2\u0227\u0228\7\u0096\2\2\u0228\u0229\5\60\31\2"+
		"\u0229\u022a\7\u0097\2\2\u022a\u0232\3\2\2\2\u022b\u022c\7\u0096\2\2\u022c"+
		"\u022d\5.\30\2\u022d\u022e\7\u00cc\2\2\u022e\u022f\5\60\31\2\u022f\u0230"+
		"\7\u0097\2\2\u0230\u0232\3\2\2\2\u0231\u0223\3\2\2\2\u0231\u0227\3\2\2"+
		"\2\u0231\u022b\3\2\2\2\u0232-\3\2\2\2\u0233\u0238\5\62\32\2\u0234\u0235"+
		"\7\u00cc\2\2\u0235\u0237\5\62\32\2\u0236\u0234\3\2\2\2\u0237\u023a\3\2"+
		"\2\2\u0238\u0236\3\2\2\2\u0238\u0239\3\2\2\2\u0239/\3\2\2\2\u023a\u0238"+
		"\3\2\2\2\u023b\u0240\5\64\33\2\u023c\u023d\7\u00cc\2\2\u023d\u023f\5\64"+
		"\33\2\u023e\u023c\3\2\2\2\u023f\u0242\3\2\2\2\u0240\u023e\3\2\2\2\u0240"+
		"\u0241\3\2\2\2\u0241\61\3\2\2\2\u0242\u0240\3\2\2\2\u0243\u0244\58\35"+
		"\2\u0244\u0245\5\u00fc\177\2\u0245\63\3\2\2\2\u0246\u0247\58\35\2\u0247"+
		"\u0248\5\u00fc\177\2\u0248\u024b\7\u00cf\2\2\u0249\u024c\5\u00c0a\2\u024a"+
		"\u024c\5\u0104\u0083\2\u024b\u0249\3\2\2\2\u024b\u024a\3\2\2\2\u024c\65"+
		"\3\2\2\2\u024d\u024e\7\u0096\2\2\u024e\u0253\5\u00c2b\2\u024f\u0250\7"+
		"\u00cc\2\2\u0250\u0252\5\u00c2b\2\u0251\u024f\3\2\2\2\u0252\u0255\3\2"+
		"\2\2\u0253\u0251\3\2\2\2\u0253\u0254\3\2\2\2\u0254\u0256\3\2\2\2\u0255"+
		"\u0253\3\2\2\2\u0256\u0257\7\u0097\2\2\u0257\67\3\2\2\2\u0258\u025a\5"+
		":\36\2\u0259\u0258\3\2\2\2\u025a\u025d\3\2\2\2\u025b\u0259\3\2\2\2\u025b"+
		"\u025c\3\2\2\2\u025c9\3\2\2\2\u025d\u025b\3\2\2\2\u025e\u0262\7\u00c8"+
		"\2\2\u025f\u0260\5\u00fc\177\2\u0260\u0261\7\u00cd\2\2\u0261\u0263\3\2"+
		"\2\2\u0262\u025f\3\2\2\2\u0262\u0263\3\2\2\2\u0263\u0264\3\2\2\2\u0264"+
		"\u0269\5<\37\2\u0265\u0266\7\u00cc\2\2\u0266\u0268\5<\37\2\u0267\u0265"+
		"\3\2\2\2\u0268\u026b\3\2\2\2\u0269\u0267\3\2\2\2\u0269\u026a\3\2\2\2\u026a"+
		"\u026c\3\2\2\2\u026b\u0269\3\2\2\2\u026c\u026d\7\u00c9\2\2\u026d;\3\2"+
		"\2\2\u026e\u0281\5\u00c6d\2\u026f\u0270\5\u00c6d\2\u0270\u0271\7\u00c6"+
		"\2\2\u0271\u0272\5> \2\u0272\u0273\7\u00c7\2\2\u0273\u0281\3\2\2\2\u0274"+
		"\u0275\5\u00c6d\2\u0275\u0276\7\u00c6\2\2\u0276\u0277\5@!\2\u0277\u0278"+
		"\7\u00c7\2\2\u0278\u0281\3\2\2\2\u0279\u027a\5\u00c6d\2\u027a\u027b\7"+
		"\u00c6\2\2\u027b\u027c\5> \2\u027c\u027d\7\u00cc\2\2\u027d\u027e\5@!\2"+
		"\u027e\u027f\7\u00c7\2\2\u027f\u0281\3\2\2\2\u0280\u026e\3\2\2\2\u0280"+
		"\u026f\3\2\2\2\u0280\u0274\3\2\2\2\u0280\u0279\3\2\2\2\u0281=\3\2\2\2"+
		"\u0282\u0287\5\u00b0Y\2\u0283\u0284\7\u00cc\2\2\u0284\u0286\5\u00b0Y\2"+
		"\u0285\u0283\3\2\2\2\u0286\u0289\3\2\2\2\u0287\u0285\3\2\2\2\u0287\u0288"+
		"\3\2\2\2\u0288?\3\2\2\2\u0289\u0287\3\2\2\2\u028a\u028f\5B\"\2\u028b\u028c"+
		"\7\u00cc\2\2\u028c\u028e\5B\"\2\u028d\u028b\3\2\2\2\u028e\u0291\3\2\2"+
		"\2\u028f\u028d\3\2\2\2\u028f\u0290\3\2\2\2\u0290A\3\2\2\2\u0291\u028f"+
		"\3\2\2\2\u0292\u0293\7\u00d4\2\2\u0293\u0294\7\u0098\2\2\u0294\u0295\5"+
		"\u00b0Y\2\u0295C\3\2\2\2\u0296\u0298\5F$\2\u0297\u0296\3\2\2\2\u0298\u029b"+
		"\3\2\2\2\u0299\u0297\3\2\2\2\u0299\u029a\3\2\2\2\u029aE\3\2\2\2\u029b"+
		"\u0299\3\2\2\2\u029c\u02a0\5H%\2\u029d\u02a0\5$\23\2\u029e\u02a0\5&\24"+
		"\2\u029f\u029c\3\2\2\2\u029f\u029d\3\2\2\2\u029f\u029e\3\2\2\2\u02a0G"+
		"\3\2\2\2\u02a1\u02a4\5L\'\2\u02a2\u02a4\5J&\2\u02a3\u02a1\3\2\2\2\u02a3"+
		"\u02a2\3\2\2\2\u02a4I\3\2\2\2\u02a5\u02a6\7\u00ce\2\2\u02a6K\3\2\2\2\u02a7"+
		"\u02a8\5\u00fc\177\2\u02a8\u02a9\7\u00cd\2\2\u02a9\u02c2\3\2\2\2\u02aa"+
		"\u02c2\5N(\2\u02ab\u02c2\5P)\2\u02ac\u02c2\5Z.\2\u02ad\u02c2\5\\/\2\u02ae"+
		"\u02c2\5^\60\2\u02af\u02c2\5d\63\2\u02b0\u02c2\5h\65\2\u02b1\u02c2\5j"+
		"\66\2\u02b2\u02c2\5l\67\2\u02b3\u02b4\5\u00b6\\\2\u02b4\u02b5\7\u00ce"+
		"\2\2\u02b5\u02c2\3\2\2\2\u02b6\u02c2\5\u008aF\2\u02b7\u02c2\5\u0090I\2"+
		"\u02b8\u02c2\5\u008eH\2\u02b9\u02c2\5n8\2\u02ba\u02c2\5p9\2\u02bb\u02c2"+
		"\5r:\2\u02bc\u02c2\5t;\2\u02bd\u02c2\5z>\2\u02be\u02c2\5|?\2\u02bf\u02c2"+
		"\5~@\2\u02c0\u02c2\5\u0080A\2\u02c1\u02a7\3\2\2\2\u02c1\u02aa\3\2\2\2"+
		"\u02c1\u02ab\3\2\2\2\u02c1\u02ac\3\2\2\2\u02c1\u02ad\3\2\2\2\u02c1\u02ae"+
		"\3\2\2\2\u02c1\u02af\3\2\2\2\u02c1\u02b0\3\2\2\2\u02c1\u02b1\3\2\2\2\u02c1"+
		"\u02b2\3\2\2\2\u02c1\u02b3\3\2\2\2\u02c1\u02b6\3\2\2\2\u02c1\u02b7\3\2"+
		"\2\2\u02c1\u02b8\3\2\2\2\u02c1\u02b9\3\2\2\2\u02c1\u02ba\3\2\2\2\u02c1"+
		"\u02bb\3\2\2\2\u02c1\u02bc\3\2\2\2\u02c1\u02bd\3\2\2\2\u02c1\u02be\3\2"+
		"\2\2\u02c1\u02bf\3\2\2\2\u02c1\u02c0\3\2\2\2\u02c2M\3\2\2\2\u02c3\u02c4"+
		"\7\u00ca\2\2\u02c4\u02c5\5D#\2\u02c5\u02c6\7\u00cb\2\2\u02c6O\3\2\2\2"+
		"\u02c7\u02c8\7N\2\2\u02c8\u02c9\5\u00aeX\2\u02c9\u02cd\5H%\2\u02ca\u02cc"+
		"\5R*\2\u02cb\u02ca\3\2\2\2\u02cc\u02cf\3\2\2\2\u02cd\u02cb\3\2\2\2\u02cd"+
		"\u02ce\3\2\2\2\u02ce\u02d1\3\2\2\2\u02cf\u02cd\3\2\2\2\u02d0\u02d2\5V"+
		",\2\u02d1\u02d0\3\2\2\2\u02d1\u02d2\3\2\2\2\u02d2\u02e4\3\2\2\2\u02d3"+
		"\u02d4\7N\2\2\u02d4\u02d5\5\u00aeX\2\u02d5\u02d6\7\u00cd\2\2\u02d6\u02da"+
		"\5D#\2\u02d7\u02d9\5T+\2\u02d8\u02d7\3\2\2\2\u02d9\u02dc\3\2\2\2\u02da"+
		"\u02d8\3\2\2\2\u02da\u02db\3\2\2\2\u02db\u02de\3\2\2\2\u02dc\u02da\3\2"+
		"\2\2\u02dd\u02df\5X-\2\u02de\u02dd\3\2\2\2\u02de\u02df\3\2\2\2\u02df\u02e0"+
		"\3\2\2\2\u02e0\u02e1\7@\2\2\u02e1\u02e2\7\u00ce\2\2\u02e2\u02e4\3\2\2"+
		"\2\u02e3\u02c7\3\2\2\2\u02e3\u02d3\3\2\2\2\u02e4Q\3\2\2\2\u02e5\u02e6"+
		"\7;\2\2\u02e6\u02e7\5\u00aeX\2\u02e7\u02e8\5H%\2\u02e8S\3\2\2\2\u02e9"+
		"\u02ea\7;\2\2\u02ea\u02eb\5\u00aeX\2\u02eb\u02ec\7\u00cd\2\2\u02ec\u02ed"+
		"\5D#\2\u02edU\3\2\2\2\u02ee\u02ef\7:\2\2\u02ef\u02f0\5H%\2\u02f0W\3\2"+
		"\2\2\u02f1\u02f2\7:\2\2\u02f2\u02f3\7\u00cd\2\2\u02f3\u02f4\5D#\2\u02f4"+
		"Y\3\2\2\2\u02f5\u02f6\7|\2\2\u02f6\u02fd\5\u00aeX\2\u02f7\u02fe\5H%\2"+
		"\u02f8\u02f9\7\u00cd\2\2\u02f9\u02fa\5D#\2\u02fa\u02fb\7B\2\2\u02fb\u02fc"+
		"\7\u00ce\2\2\u02fc\u02fe\3\2\2\2\u02fd\u02f7\3\2\2\2\u02fd\u02f8\3\2\2"+
		"\2\u02fe[\3\2\2\2\u02ff\u0300\7\66\2\2\u0300\u0301\5H%\2\u0301\u0302\7"+
		"|\2\2\u0302\u0303\5\u00aeX\2\u0303\u0304\7\u00ce\2\2\u0304]\3\2\2\2\u0305"+
		"\u0306\7I\2\2\u0306\u0308\7\u00c6\2\2\u0307\u0309\5`\61\2\u0308\u0307"+
		"\3\2\2\2\u0308\u0309\3\2\2\2\u0309\u030a\3\2\2\2\u030a\u030c\7\u00ce\2"+
		"\2\u030b\u030d\5\u00acW\2\u030c\u030b\3\2\2\2\u030c\u030d\3\2\2\2\u030d"+
		"\u030e\3\2\2\2\u030e\u0310\7\u00ce\2\2\u030f\u0311\5b\62\2\u0310\u030f"+
		"\3\2\2\2\u0310\u0311\3\2\2\2\u0311\u0312\3\2\2\2\u0312\u0319\7\u00c7\2"+
		"\2\u0313\u031a\5H%\2\u0314\u0315\7\u00cd\2\2\u0315\u0316\5D#\2\u0316\u0317"+
		"\7>\2\2\u0317\u0318\7\u00ce\2\2\u0318\u031a\3\2\2\2\u0319\u0313\3\2\2"+
		"\2\u0319\u0314\3\2\2\2\u031a_\3\2\2\2\u031b\u031c\5\u00acW\2\u031ca\3"+
		"\2\2\2\u031d\u031e\5\u00acW\2\u031ec\3\2\2\2\u031f\u0320\7o\2\2\u0320"+
		"\u0338\5\u00aeX\2\u0321\u0323\7\u00ca\2\2\u0322\u0324\7\u00ce\2\2\u0323"+
		"\u0322\3\2\2\2\u0323\u0324\3\2\2\2\u0324\u0328\3\2\2\2\u0325\u0327\5f"+
		"\64\2\u0326\u0325\3\2\2\2\u0327\u032a\3\2\2\2\u0328\u0326\3\2\2\2\u0328"+
		"\u0329\3\2\2\2\u0329\u032b\3\2\2\2\u032a\u0328\3\2\2\2\u032b\u0339\7\u00cb"+
		"\2\2\u032c\u032e\7\u00cd\2\2\u032d\u032f\7\u00ce\2\2\u032e\u032d\3\2\2"+
		"\2\u032e\u032f\3\2\2\2\u032f\u0333\3\2\2\2\u0330\u0332\5f\64\2\u0331\u0330"+
		"\3\2\2\2\u0332\u0335\3\2\2\2\u0333\u0331\3\2\2\2\u0333\u0334\3\2\2\2\u0334"+
		"\u0336\3\2\2\2\u0335\u0333\3\2\2\2\u0336\u0337\7A\2\2\u0337\u0339\7\u00ce"+
		"\2\2\u0338\u0321\3\2\2\2\u0338\u032c\3\2\2\2\u0339e\3\2\2\2\u033a\u033b"+
		"\7.\2\2\u033b\u033e\5\u00b0Y\2\u033c\u033e\7\65\2\2\u033d\u033a\3\2\2"+
		"\2\u033d\u033c\3\2\2\2\u033e\u033f\3\2\2\2\u033f\u0341\t\5\2\2\u0340\u033d"+
		"\3\2\2\2\u0341\u0342\3\2\2\2\u0342\u0340\3\2\2\2\u0342\u0343\3\2\2\2\u0343"+
		"\u0344\3\2\2\2\u0344\u0345\5D#\2\u0345g\3\2\2\2\u0346\u0348\7,\2\2\u0347"+
		"\u0349\5\u00b0Y\2\u0348\u0347\3\2\2\2\u0348\u0349\3\2\2\2\u0349\u034a"+
		"\3\2\2\2\u034a\u034b\7\u00ce\2\2\u034bi\3\2\2\2\u034c\u034e\7\63\2\2\u034d"+
		"\u034f\5\u00b0Y\2\u034e\u034d\3\2\2\2\u034e\u034f\3\2\2\2\u034f\u0350"+
		"\3\2\2\2\u0350\u0351\7\u00ce\2\2\u0351k\3\2\2\2\u0352\u0354\7l\2\2\u0353"+
		"\u0355\5\u00b0Y\2\u0354\u0353\3\2\2\2\u0354\u0355\3\2\2\2\u0355\u0356"+
		"\3\2\2\2\u0356\u0357\7\u00ce\2\2\u0357m\3\2\2\2\u0358\u0359\5\u00b0Y\2"+
		"\u0359\u035a\7\u00ce\2\2\u035ao\3\2\2\2\u035b\u035c\7y\2\2\u035c\u035d"+
		"\7\u00c6\2\2\u035d\u035e\5\u00e0q\2\u035e\u035f\7\u00c7\2\2\u035f\u0360"+
		"\7\u00ce\2\2\u0360q\3\2\2\2\u0361\u0388\7J\2\2\u0362\u0363\7\u00c6\2\2"+
		"\u0363\u0364\5\u00e2r\2\u0364\u0366\7(\2\2\u0365\u0367\7\u00b8\2\2\u0366"+
		"\u0365\3\2\2\2\u0366\u0367\3\2\2\2\u0367\u0368\3\2\2\2\u0368\u036e\5\u00e2"+
		"r\2\u0369\u036b\7\u0098\2\2\u036a\u036c\7\u00b8\2\2\u036b\u036a\3\2\2"+
		"\2\u036b\u036c\3\2\2\2\u036c\u036d\3\2\2\2\u036d\u036f\5\u00e2r\2\u036e"+
		"\u0369\3\2\2\2\u036e\u036f\3\2\2\2\u036f\u0370\3\2\2\2\u0370\u0371\7\u00c7"+
		"\2\2\u0371\u0389\3\2\2\2\u0372\u0373\7\u00c6\2\2\u0373\u0374\5\u00b0Y"+
		"\2\u0374\u0375\7(\2\2\u0375\u037b\5\u00e2r\2\u0376\u0378\7\u0098\2\2\u0377"+
		"\u0379\7\u00b8\2\2\u0378\u0377\3\2\2\2\u0378\u0379\3\2\2\2\u0379\u037a"+
		"\3\2\2\2\u037a\u037c\5\u00e2r\2\u037b\u0376\3\2\2\2\u037b\u037c\3\2\2"+
		"\2\u037c\u037d\3\2\2\2\u037d\u037e\7\u00c7\2\2\u037e\u0389\3\2\2\2\u037f"+
		"\u0380\7\u00c6\2\2\u0380\u0381\5\u00e2r\2\u0381\u0382\7(\2\2\u0382\u0383"+
		"\7[\2\2\u0383\u0384\7\u00c6\2\2\u0384\u0385\5\u00f6|\2\u0385\u0386\7\u00c7"+
		"\2\2\u0386\u0387\7\u00c7\2\2\u0387\u0389\3\2\2\2\u0388\u0362\3\2\2\2\u0388"+
		"\u0372\3\2\2\2\u0388\u037f\3\2\2\2\u0389\u0390\3\2\2\2\u038a\u0391\5H"+
		"%\2\u038b\u038c\7\u00cd\2\2\u038c\u038d\5D#\2\u038d\u038e\7?\2\2\u038e"+
		"\u038f\7\u00ce\2\2\u038f\u0391\3\2\2\2\u0390\u038a\3\2\2\2\u0390\u038b"+
		"\3\2\2\2\u0391s\3\2\2\2\u0392\u0393\7r\2\2\u0393\u03a3\5N(\2\u0394\u0396"+
		"\5v<\2\u0395\u0394\3\2\2\2\u0396\u0397\3\2\2\2\u0397\u0395\3\2\2\2\u0397"+
		"\u0398\3\2\2\2\u0398\u039a\3\2\2\2\u0399\u039b\5x=\2\u039a\u0399\3\2\2"+
		"\2\u039a\u039b\3\2\2\2\u039b\u03a4\3\2\2\2\u039c\u039e\5v<\2\u039d\u039c"+
		"\3\2\2\2\u039e\u03a1\3\2\2\2\u039f\u039d\3\2\2\2\u039f\u03a0\3\2\2\2\u03a0"+
		"\u03a2\3\2\2\2\u03a1\u039f\3\2\2\2\u03a2\u03a4\5x=\2\u03a3\u0395\3\2\2"+
		"\2\u03a3\u039f\3\2\2\2\u03a4u\3\2\2\2\u03a5\u03a6\7/\2\2\u03a6\u03a7\7"+
		"\u00c6\2\2\u03a7\u03a8\5\u00c0a\2\u03a8\u03a9\7\u00d4\2\2\u03a9\u03aa"+
		"\7\u00c7\2\2\u03aa\u03ab\5N(\2\u03abw\3\2\2\2\u03ac\u03ad\7G\2\2\u03ad"+
		"\u03ae\5N(\2\u03aey\3\2\2\2\u03af\u03b0\7p\2\2\u03b0\u03b1\5\u00b0Y\2"+
		"\u03b1\u03b2\7\u00ce\2\2\u03b2{\3\2\2\2\u03b3\u03b4\7M\2\2\u03b4\u03b5"+
		"\5\u00fc\177\2\u03b5\u03b6\7\u00ce\2\2\u03b6}\3\2\2\2\u03b7\u03b8\7\64"+
		"\2\2\u03b8\u03b9\7\u00c6\2\2\u03b9\u03ba\5\u0082B\2\u03ba\u03c1\7\u00c7"+
		"\2\2\u03bb\u03c2\5H%\2\u03bc\u03bd\7\u00cd\2\2\u03bd\u03be\5D#\2\u03be"+
		"\u03bf\7=\2\2\u03bf\u03c0\7\u00ce\2\2\u03c0\u03c2\3\2\2\2\u03c1\u03bb"+
		"\3\2\2\2\u03c1\u03bc\3\2\2\2\u03c2\177\3\2\2\2\u03c3\u03c5\7\b\2\2\u03c4"+
		"\u03c3\3\2\2\2\u03c5\u03c8\3\2\2\2\u03c6\u03c4\3\2\2\2\u03c6\u03c7\3\2"+
		"\2\2\u03c7\u03d3\3\2\2\2\u03c8\u03c6\3\2\2\2\u03c9\u03cc\7\t\2\2\u03ca"+
		"\u03cc\5\6\4\2\u03cb\u03c9\3\2\2\2\u03cb\u03ca\3\2\2\2\u03cc\u03d0\3\2"+
		"\2\2\u03cd\u03cf\7\b\2\2\u03ce\u03cd\3\2\2\2\u03cf\u03d2\3\2\2\2\u03d0"+
		"\u03ce\3\2\2\2\u03d0\u03d1\3\2\2\2\u03d1\u03d4\3\2\2\2\u03d2\u03d0\3\2"+
		"\2\2\u03d3\u03cb\3\2\2\2\u03d4\u03d5\3\2\2\2\u03d5\u03d3\3\2\2\2\u03d5"+
		"\u03d6\3\2\2\2\u03d6\u0081\3\2\2\2\u03d7\u03dc\5\u00a8U\2\u03d8\u03d9"+
		"\7\u00cc\2\2\u03d9\u03db\5\u00a8U\2\u03da\u03d8\3\2\2\2\u03db\u03de\3"+
		"\2\2\2\u03dc\u03da\3\2\2\2\u03dc\u03dd\3\2\2\2\u03dd\u0083\3\2\2\2\u03de"+
		"\u03dc\3\2\2\2\u03df\u03e1\5\u0086D\2\u03e0\u03df\3\2\2\2\u03e0\u03e1"+
		"\3\2\2\2\u03e1\u03e6\3\2\2\2\u03e2\u03e3\7\u00cc\2\2\u03e3\u03e5\5\u0086"+
		"D\2\u03e4\u03e2\3\2\2\2\u03e5\u03e8\3\2\2\2\u03e6\u03e4\3\2\2\2\u03e6"+
		"\u03e7\3\2\2\2\u03e7\u0085\3\2\2\2\u03e8\u03e6\3\2\2\2\u03e9\u03eb\58"+
		"\35\2\u03ea\u03ec\5\u0088E\2\u03eb\u03ea\3\2\2\2\u03eb\u03ec\3\2\2\2\u03ec"+
		"\u03ee\3\2\2\2\u03ed\u03ef\7\u00b8\2\2\u03ee\u03ed\3\2\2\2\u03ee\u03ef"+
		"\3\2\2\2\u03ef\u03f1\3\2\2\2\u03f0\u03f2\7\u00b5\2\2\u03f1\u03f0\3\2\2"+
		"\2\u03f1\u03f2\3\2\2\2\u03f2\u03f3\3\2\2\2\u03f3\u03f4\5\u00a6T\2\u03f4"+
		"\u0087\3\2\2\2\u03f5\u03f9\5\u00c0a\2\u03f6\u03f9\7-\2\2\u03f7\u03f9\5"+
		"\u0104\u0083\2\u03f8\u03f5\3\2\2\2\u03f8\u03f6\3\2\2\2\u03f8\u03f7\3\2"+
		"\2\2\u03f9\u0089\3\2\2\2\u03fa\u03fb\7L\2\2\u03fb\u0400\5\u008cG\2\u03fc"+
		"\u03fd\7\u00cc\2\2\u03fd\u03ff\5\u008cG\2\u03fe\u03fc\3\2\2\2\u03ff\u0402"+
		"\3\2\2\2\u0400\u03fe\3\2\2\2\u0400\u0401\3\2\2\2\u0401\u0403\3\2\2\2\u0402"+
		"\u0400\3\2\2\2\u0403\u0404\7\u00ce\2\2\u0404\u008b\3\2\2\2\u0405\u040e"+
		"\7\u00d4\2\2\u0406\u0407\7\u00c3\2\2\u0407\u040e\5\u00e2r\2\u0408\u0409"+
		"\7\u00c3\2\2\u0409\u040a\7\u00ca\2\2\u040a\u040b\5\u00b0Y\2\u040b\u040c"+
		"\7\u00cb\2\2\u040c\u040e\3\2\2\2\u040d\u0405\3\2\2\2\u040d\u0406\3\2\2"+
		"\2\u040d\u0408\3\2\2\2\u040e\u008d\3\2\2\2\u040f\u0410\79\2\2\u0410\u0411"+
		"\5\u00acW\2\u0411\u0412\7\u00ce\2\2\u0412\u008f\3\2\2\2\u0413\u0414\7"+
		"m\2\2\u0414\u0419\5\u00a6T\2\u0415\u0416\7\u00cc\2\2\u0416\u0418\5\u00a6"+
		"T\2\u0417\u0415\3\2\2\2\u0418\u041b\3\2\2\2\u0419\u0417\3\2\2\2\u0419"+
		"\u041a\3\2\2\2\u041a\u041c\3\2\2\2\u041b\u0419\3\2\2\2\u041c\u041d\7\u00ce"+
		"\2\2\u041d\u0091\3\2\2\2\u041e\u041f\58\35\2\u041f\u0420\5\u00a2R\2\u0420"+
		"\u0425\5\u00a6T\2\u0421\u0422\7\u00cc\2\2\u0422\u0424\5\u00a6T\2\u0423"+
		"\u0421\3\2\2\2\u0424\u0427\3\2\2\2\u0425\u0423\3\2\2\2\u0425\u0426\3\2"+
		"\2\2\u0426\u0428\3\2\2\2\u0427\u0425\3\2\2\2\u0428\u0429\7\u00ce\2\2\u0429"+
		"\u044f\3\2\2\2\u042a\u042b\58\35\2\u042b\u042c\7\62\2\2\u042c\u0431\5"+
		"\u00a8U\2\u042d\u042e\7\u00cc\2\2\u042e\u0430\5\u00a8U\2\u042f\u042d\3"+
		"\2\2\2\u0430\u0433\3\2\2\2\u0431\u042f\3\2\2\2\u0431\u0432\3\2\2\2\u0432"+
		"\u0434\3\2\2\2\u0433\u0431\3\2\2\2\u0434\u0435\7\u00ce\2\2\u0435\u044f"+
		"\3\2\2\2\u0436\u0438\58\35\2\u0437\u0439\5\u00a4S\2\u0438\u0437\3\2\2"+
		"\2\u0438\u0439\3\2\2\2\u0439\u043a\3\2\2\2\u043a\u043c\7K\2\2\u043b\u043d"+
		"\7\u00b8\2\2\u043c\u043b\3\2\2\2\u043c\u043d\3\2\2\2\u043d\u043e\3\2\2"+
		"\2\u043e\u0440\5\u00fc\177\2\u043f\u0441\5,\27\2\u0440\u043f\3\2\2\2\u0440"+
		"\u0441\3\2\2\2\u0441\u0442\3\2\2\2\u0442\u0443\7\u00c6\2\2\u0443\u0444"+
		"\5\u0084C\2\u0444\u0446\7\u00c7\2\2\u0445\u0447\5\u009eP\2\u0446\u0445"+
		"\3\2\2\2\u0446\u0447\3\2\2\2\u0447\u0448\3\2\2\2\u0448\u0449\5\u00a0Q"+
		"\2\u0449\u044f\3\2\2\2\u044a\u044b\7z\2\2\u044b\u044c\5\u00caf\2\u044c"+
		"\u044d\5\u0094K\2\u044d\u044f\3\2\2\2\u044e\u041e\3\2\2\2\u044e\u042a"+
		"\3\2\2\2\u044e\u0436\3\2\2\2\u044e\u044a\3\2\2\2\u044f\u0093\3\2\2\2\u0450"+
		"\u045a\7\u00ce\2\2\u0451\u0455\7\u00ca\2\2\u0452\u0454\5\u0096L\2\u0453"+
		"\u0452\3\2\2\2\u0454\u0457\3\2\2\2\u0455\u0453\3\2\2\2\u0455\u0456\3\2"+
		"\2\2\u0456\u0458\3\2\2\2\u0457\u0455\3\2\2\2\u0458\u045a\7\u00cb\2\2\u0459"+
		"\u0450\3\2\2\2\u0459\u0451\3\2\2\2\u045a\u0095\3\2\2\2\u045b\u045e\5\u0098"+
		"M\2\u045c\u045e\5\u009aN\2\u045d\u045b\3\2\2\2\u045d\u045c\3\2\2\2\u045e"+
		"\u0097\3\2\2\2\u045f\u0460\5\u00c6d\2\u0460\u0461\7\u00b2\2\2\u0461\u0462"+
		"\5\u00fc\177\2\u0462\u0463\7T\2\2\u0463\u0464\5\u00caf\2\u0464\u0465\7"+
		"\u00ce\2\2\u0465\u0099\3\2\2\2\u0466\u0467\5\u009cO\2\u0467\u046d\7(\2"+
		"\2\u0468\u046e\5\u00fe\u0080\2\u0469\u046b\5\u00fe\u0080\2\u046a\u0469"+
		"\3\2\2\2\u046a\u046b\3\2\2\2\u046b\u046c\3\2\2\2\u046c\u046e\5\u00fc\177"+
		"\2\u046d\u0468\3\2\2\2\u046d\u046a\3\2\2\2\u046e\u046f\3\2\2\2\u046f\u0470"+
		"\7\u00ce\2\2\u0470\u009b\3\2\2\2\u0471\u0472\5\u00c6d\2\u0472\u0473\7"+
		"\u00b2\2\2\u0473\u0475\3\2\2\2\u0474\u0471\3\2\2\2\u0474\u0475\3\2\2\2"+
		"\u0475\u0476\3\2\2\2\u0476\u0477\5\u00fc\177\2\u0477\u009d\3\2\2\2\u0478"+
		"\u0479\7\u00cd\2\2\u0479\u047a\5\u00fc\177\2\u047a\u047b\5\u00ccg\2\u047b"+
		"\u009f\3\2\2\2\u047c\u047f\7\u00ce\2\2\u047d\u047f\5N(\2\u047e\u047c\3"+
		"\2\2\2\u047e\u047d\3\2\2\2\u047f\u00a1\3\2\2\2\u0480\u0483\5\u00a4S\2"+
		"\u0481\u0483\7{\2\2\u0482\u0480\3\2\2\2\u0482\u0481\3\2\2\2\u0483\u00a3"+
		"\3\2\2\2\u0484\u0486\5\u00fe\u0080\2\u0485\u0484\3\2\2\2\u0486\u0487\3"+
		"\2\2\2\u0487\u0485\3\2\2\2\u0487\u0488\3\2\2\2\u0488\u00a5\3\2\2\2\u0489"+
		"\u048c\7\u00d4\2\2\u048a\u048b\7\u00cf\2\2\u048b\u048d\5\u00d0i\2\u048c"+
		"\u048a\3\2\2\2\u048c\u048d\3\2\2\2\u048d\u00a7\3\2\2\2\u048e\u048f\5\u00fc"+
		"\177\2\u048f\u0490\7\u00cf\2\2\u0490\u0491\5\u00d0i\2\u0491\u00a9\3\2"+
		"\2\2\u0492\u0493\58\35\2\u0493\u0494\7\62\2\2\u0494\u0499\5\u00a8U\2\u0495"+
		"\u0496\7\u00cc\2\2\u0496\u0498\5\u00a8U\2\u0497\u0495\3\2\2\2\u0498\u049b"+
		"\3\2\2\2\u0499\u0497\3\2\2\2\u0499\u049a\3\2\2\2\u049a\u049c\3\2\2\2\u049b"+
		"\u0499\3\2\2\2\u049c\u049d\7\u00ce\2\2\u049d\u00ab\3\2\2\2\u049e\u04a3"+
		"\5\u00b0Y\2\u049f\u04a0\7\u00cc\2\2\u04a0\u04a2\5\u00b0Y\2\u04a1\u049f"+
		"\3\2\2\2\u04a2\u04a5\3\2\2\2\u04a3\u04a1\3\2\2\2\u04a3\u04a4\3\2\2\2\u04a4"+
		"\u00ad\3\2\2\2\u04a5\u04a3\3\2\2\2\u04a6\u04a9\7\u00c6\2\2\u04a7\u04aa"+
		"\5\u00b0Y\2\u04a8\u04aa\5\u00b6\\\2\u04a9\u04a7\3\2\2\2\u04a9\u04a8\3"+
		"\2\2\2\u04aa\u04ab\3\2\2\2\u04ab\u04ac\7\u00c7\2\2\u04ac\u00af\3\2\2\2"+
		"\u04ad\u04ae\bY\1\2\u04ae\u04af\7\61\2\2\u04af\u051f\5\u00b0Y/\u04b0\u051f"+
		"\5\u00b2Z\2\u04b1\u04b2\5\u00dco\2\u04b2\u04b3\7\u00c8\2\2\u04b3\u04b4"+
		"\5\u00b0Y\2\u04b4\u04b5\7\u00c9\2\2\u04b5\u051f\3\2\2\2\u04b6\u04b7\t"+
		"\6\2\2\u04b7\u051f\5\u00e2r\2\u04b8\u04b9\5\u00e2r\2\u04b9\u04ba\t\6\2"+
		"\2\u04ba\u051f\3\2\2\2\u04bb\u04bc\7\u00c6\2\2\u04bc\u04bd\5\u0106\u0084"+
		"\2\u04bd\u04be\7\u00c7\2\2\u04be\u04bf\5\u00b0Y)\u04bf\u051f\3\2\2\2\u04c0"+
		"\u04c1\t\7\2\2\u04c1\u051f\5\u00b0Y(\u04c2\u04c3\t\b\2\2\u04c3\u051f\5"+
		"\u00b0Y&\u04c4\u04c5\5\u00e2r\2\u04c5\u04c6\5\u00b4[\2\u04c6\u04c7\5\u00b0"+
		"Y\32\u04c7\u051f\3\2\2\2\u04c8\u04c9\5\u00e2r\2\u04c9\u04ca\7\u00cf\2"+
		"\2\u04ca\u04cd\7\u00b8\2\2\u04cb\u04ce\5\u00e2r\2\u04cc\u04ce\5\u00b2"+
		"Z\2\u04cd\u04cb\3\2\2\2\u04cd\u04cc\3\2\2\2\u04ce\u051f\3\2\2\2\u04cf"+
		"\u04d0\7e\2\2\u04d0\u051f\5\u00b0Y\30\u04d1\u051f\5\u00e2r\2\u04d2\u051f"+
		"\5\u00d6l\2\u04d3\u051f\5\u00dep\2\u04d4\u051f\7\u00d3\2\2\u04d5\u051f"+
		"\7\u00d8\2\2\u04d6\u051f\5\u00aeX\2\u04d7\u04d8\7\'\2\2\u04d8\u04da\7"+
		"\u00c6\2\2\u04d9\u04db\5\u00b8]\2\u04da\u04d9\3\2\2\2\u04da\u04db\3\2"+
		"\2\2\u04db\u04dc\3\2\2\2\u04dc\u04e3\7\u00c7\2\2\u04dd\u04df\7\u00c8\2"+
		"\2\u04de\u04e0\5\u00b8]\2\u04df\u04de\3\2\2\2\u04df\u04e0\3\2\2\2\u04e0"+
		"\u04e1\3\2\2\2\u04e1\u04e3\7\u00c9\2\2\u04e2\u04d7\3\2\2\2\u04e2\u04dd"+
		"\3\2\2\2\u04e3\u04e8\3\2\2\2\u04e4\u04e5\7\u00c8\2\2\u04e5\u04e6\5\u00b0"+
		"Y\2\u04e6\u04e7\7\u00c9\2\2\u04e7\u04e9\3\2\2\2\u04e8\u04e4\3\2\2\2\u04e8"+
		"\u04e9\3\2\2\2\u04e9\u051f\3\2\2\2\u04ea\u051f\7}\2\2\u04eb\u04ec\7[\2"+
		"\2\u04ec\u04ed\7\u00c6\2\2\u04ed\u04ee\5\u00f6|\2\u04ee\u04ef\7\u00c7"+
		"\2\2\u04ef\u04f0\7\u00cf\2\2\u04f0\u04f1\5\u00b0Y\f\u04f1\u051f\3\2\2"+
		"\2\u04f2\u04f3\7Z\2\2\u04f3\u04f4\7\u00c6\2\2\u04f4\u04f5\5\u00e0q\2\u04f5"+
		"\u04f6\7\u00c7\2\2\u04f6\u051f\3\2\2\2\u04f7\u04f8\7<\2\2\u04f8\u04f9"+
		"\7\u00c6\2\2\u04f9\u04fa\5\u00e2r\2\u04fa\u04fb\7\u00c7\2\2\u04fb\u051f"+
		"\3\2\2\2\u04fc\u04fd\7C\2\2\u04fd\u04fe\7\u00c6\2\2\u04fe\u04ff\5\u00b0"+
		"Y\2\u04ff\u0500\7\u00c7\2\2\u0500\u051f\3\2\2\2\u0501\u0505\7D\2\2\u0502"+
		"\u0503\7\u00c6\2\2\u0503\u0506\7\u00c7\2\2\u0504\u0506\5\u00aeX\2\u0505"+
		"\u0502\3\2\2\2\u0505\u0504\3\2\2\2\u0505\u0506\3\2\2\2\u0506\u051f\3\2"+
		"\2\2\u0507\u0508\7Q\2\2\u0508\u051f\5\u00b0Y\7\u0509\u050a\7R\2\2\u050a"+
		"\u051f\5\u00b0Y\6\u050b\u050c\7i\2\2\u050c\u051f\5\u00b0Y\5\u050d\u050e"+
		"\7j\2\2\u050e\u051f\5\u00b0Y\4\u050f\u0511\7m\2\2\u0510\u050f\3\2\2\2"+
		"\u0510\u0511\3\2\2\2\u0511\u0512\3\2\2\2\u0512\u0514\7K\2\2\u0513\u0515"+
		"\7\u00b8\2\2\u0514\u0513\3\2\2\2\u0514\u0515\3\2\2\2\u0515\u0516\3\2\2"+
		"\2\u0516\u0517\7\u00c6\2\2\u0517\u0518\5\u0084C\2\u0518\u051a\7\u00c7"+
		"\2\2\u0519\u051b\5\u00bc_\2\u051a\u0519\3\2\2\2\u051a\u051b\3\2\2\2\u051b"+
		"\u051c\3\2\2\2\u051c\u051d\5N(\2\u051d\u051f\3\2\2\2\u051e\u04ad\3\2\2"+
		"\2\u051e\u04b0\3\2\2\2\u051e\u04b1\3\2\2\2\u051e\u04b6\3\2\2\2\u051e\u04b8"+
		"\3\2\2\2\u051e\u04bb\3\2\2\2\u051e\u04c0\3\2\2\2\u051e\u04c2\3\2\2\2\u051e"+
		"\u04c4\3\2\2\2\u051e\u04c8\3\2\2\2\u051e\u04cf\3\2\2\2\u051e\u04d1\3\2"+
		"\2\2\u051e\u04d2\3\2\2\2\u051e\u04d3\3\2\2\2\u051e\u04d4\3\2\2\2\u051e"+
		"\u04d5\3\2\2\2\u051e\u04d6\3\2\2\2\u051e\u04e2\3\2\2\2\u051e\u04ea\3\2"+
		"\2\2\u051e\u04eb\3\2\2\2\u051e\u04f2\3\2\2\2\u051e\u04f7\3\2\2\2\u051e"+
		"\u04fc\3\2\2\2\u051e\u0501\3\2\2\2\u051e\u0507\3\2\2\2\u051e\u0509\3\2"+
		"\2\2\u051e\u050b\3\2\2\2\u051e\u050d\3\2\2\2\u051e\u0510\3\2\2\2\u051f"+
		"\u0556\3\2\2\2\u0520\u0521\f,\2\2\u0521\u0522\7\u00a4\2\2\u0522\u0555"+
		"\5\u00b0Y,\u0523\u0524\f%\2\2\u0524\u0525\t\t\2\2\u0525\u0555\5\u00b0"+
		"Y&\u0526\u0527\f$\2\2\u0527\u0528\t\n\2\2\u0528\u0555\5\u00b0Y%\u0529"+
		"\u052a\f#\2\2\u052a\u052b\t\13\2\2\u052b\u0555\5\u00b0Y$\u052c\u052d\f"+
		"\"\2\2\u052d\u052e\t\f\2\2\u052e\u0555\5\u00b0Y#\u052f\u0530\f!\2\2\u0530"+
		"\u0531\t\r\2\2\u0531\u0555\5\u00b0Y\"\u0532\u0533\f \2\2\u0533\u0534\7"+
		"\u00b8\2\2\u0534\u0555\5\u00b0Y!\u0535\u0536\f\37\2\2\u0536\u0537\7\u00bb"+
		"\2\2\u0537\u0555\5\u00b0Y \u0538\u0539\f\36\2\2\u0539\u053a\7\u00b9\2"+
		"\2\u053a\u0555\5\u00b0Y\37\u053b\u053c\f\35\2\2\u053c\u053d\7\u00af\2"+
		"\2\u053d\u0555\5\u00b0Y\36\u053e\u053f\f\34\2\2\u053f\u0540\7\u00ae\2"+
		"\2\u0540\u0555\5\u00b0Y\35\u0541\u0542\f\33\2\2\u0542\u0544\7\u00c5\2"+
		"\2\u0543\u0545\5\u00b0Y\2\u0544\u0543\3\2\2\2\u0544\u0545\3\2\2\2\u0545"+
		"\u0546\3\2\2\2\u0546\u0547\7\u00cd\2\2\u0547\u0555\5\u00b0Y\34\u0548\u0549"+
		"\f\27\2\2\u0549\u054a\7\\\2\2\u054a\u0555\5\u00b0Y\30\u054b\u054c\f\26"+
		"\2\2\u054c\u054d\7^\2\2\u054d\u0555\5\u00b0Y\27\u054e\u054f\f\25\2\2\u054f"+
		"\u0550\7]\2\2\u0550\u0555\5\u00b0Y\26\u0551\u0552\f\'\2\2\u0552\u0553"+
		"\7S\2\2\u0553\u0555\5\u00c2b\2\u0554\u0520\3\2\2\2\u0554\u0523\3\2\2\2"+
		"\u0554\u0526\3\2\2\2\u0554\u0529\3\2\2\2\u0554\u052c\3\2\2\2\u0554\u052f"+
		"\3\2\2\2\u0554\u0532\3\2\2\2\u0554\u0535\3\2\2\2\u0554\u0538\3\2\2\2\u0554"+
		"\u053b\3\2\2\2\u0554\u053e\3\2\2\2\u0554\u0541\3\2\2\2\u0554\u0548\3\2"+
		"\2\2\u0554\u054b\3\2\2\2\u0554\u054e\3\2\2\2\u0554\u0551\3\2\2\2\u0555"+
		"\u0558\3\2\2\2\u0556\u0554\3\2\2\2\u0556\u0557\3\2\2\2\u0557\u00b1\3\2"+
		"\2\2\u0558\u0556\3\2\2\2\u0559\u055a\7`\2\2\u055a\u055c\5\u00c2b\2\u055b"+
		"\u055d\5\u00ccg\2\u055c\u055b\3\2\2\2\u055c\u055d\3\2\2\2\u055d\u00b3"+
		"\3\2\2\2\u055e\u055f\t\16\2\2\u055f\u00b5\3\2\2\2\u0560\u0561\7}\2\2\u0561"+
		"\u0564\5\u00b0Y\2\u0562\u0563\7\u0098\2\2\u0563\u0565\5\u00b0Y\2\u0564"+
		"\u0562\3\2\2\2\u0564\u0565\3\2\2\2\u0565\u00b7\3\2\2\2\u0566\u056b\5\u00ba"+
		"^\2\u0567\u0568\7\u00cc\2\2\u0568\u056a\5\u00ba^\2\u0569\u0567\3\2\2\2"+
		"\u056a\u056d\3\2\2\2\u056b\u0569\3\2\2\2\u056b\u056c\3\2\2\2\u056c\u056f"+
		"\3\2\2\2\u056d\u056b\3\2\2\2\u056e\u0570\7\u00cc\2\2\u056f\u056e\3\2\2"+
		"\2\u056f\u0570\3\2\2\2\u0570\u00b9\3\2\2\2\u0571\u0574\5\u00b0Y\2\u0572"+
		"\u0573\7\u0098\2\2\u0573\u0575\5\u00b0Y\2\u0574\u0572\3\2\2\2\u0574\u0575"+
		"\3\2\2\2\u0575\u057e\3\2\2\2\u0576\u0577\5\u00b0Y\2\u0577\u0578\7\u0098"+
		"\2\2\u0578\u057a\3\2\2\2\u0579\u0576\3\2\2\2\u0579\u057a\3\2\2\2\u057a"+
		"\u057b\3\2\2\2\u057b\u057c\7\u00b8\2\2\u057c\u057e\5\u00e2r\2\u057d\u0571"+
		"\3\2\2\2\u057d\u0579\3\2\2\2\u057e\u00bb\3\2\2\2\u057f\u0580\7z\2\2\u0580"+
		"\u0581\7\u00c6\2\2\u0581\u0586\5\u00be`\2\u0582\u0583\7\u00cc\2\2\u0583"+
		"\u0585\5\u00be`\2\u0584\u0582\3\2\2\2\u0585\u0588\3\2\2\2\u0586\u0584"+
		"\3\2\2\2\u0586\u0587\3\2\2\2\u0587\u0589\3\2\2\2\u0588\u0586\3\2\2\2\u0589"+
		"\u058a\7\u00c7\2\2\u058a\u00bd\3\2\2\2\u058b\u058d\7\u00b8\2\2\u058c\u058b"+
		"\3\2\2\2\u058c\u058d\3\2\2\2\u058d\u058e\3\2\2\2\u058e\u058f\7\u00d4\2"+
		"\2\u058f\u00bf\3\2\2\2\u0590\u0592\5\u00c6d\2\u0591\u0593\5\66\34\2\u0592"+
		"\u0591\3\2\2\2\u0592\u0593\3\2\2\2\u0593\u0596\3\2\2\2\u0594\u0596\7m"+
		"\2\2\u0595\u0590\3\2\2\2\u0595\u0594\3\2\2\2\u0596\u00c1\3\2\2\2\u0597"+
		"\u059a\5\u00c6d\2\u0598\u059a\5\u00c4c\2\u0599\u0597\3\2\2\2\u0599\u0598"+
		"\3\2\2\2\u059a\u059c\3\2\2\2\u059b\u059d\5\66\34\2\u059c\u059b\3\2\2\2"+
		"\u059c\u059d\3\2\2\2\u059d\u05a1\3\2\2\2\u059e\u05a1\5\u0104\u0083\2\u059f"+
		"\u05a1\7m\2\2\u05a0\u0599\3\2\2\2\u05a0\u059e\3\2\2\2\u05a0\u059f\3\2"+
		"\2\2\u05a1\u00c3\3\2\2\2\u05a2\u05a7\5\u00ecw\2\u05a3\u05a4\7\u00b3\2"+
		"\2\u05a4\u05a6\5\u00eex\2\u05a5\u05a3\3\2\2\2\u05a6\u05a9\3\2\2\2\u05a7"+
		"\u05a5\3\2\2\2\u05a7\u05a8\3\2\2\2\u05a8\u00c5\3\2\2\2\u05a9\u05a7\3\2"+
		"\2\2\u05aa\u05ac\7_\2\2\u05ab\u05aa\3\2\2\2\u05ab\u05ac\3\2\2\2\u05ac"+
		"\u05ae\3\2\2\2\u05ad\u05af\7\u00b4\2\2\u05ae\u05ad\3\2\2\2\u05ae\u05af"+
		"\3\2\2\2\u05af\u05b0\3\2\2\2\u05b0\u05b1\5\u00c8e\2\u05b1\u00c7\3\2\2"+
		"\2\u05b2\u05b7\5\u00fc\177\2\u05b3\u05b4\7\u00b4\2\2\u05b4\u05b6\5\u00fc"+
		"\177\2\u05b5\u05b3\3\2\2\2\u05b6\u05b9\3\2\2\2\u05b7\u05b5\3\2\2\2\u05b7"+
		"\u05b8\3\2\2\2\u05b8\u00c9\3\2\2\2\u05b9\u05b7\3\2\2\2\u05ba\u05bf\5\u00c6"+
		"d\2\u05bb\u05bc\7\u00cc\2\2\u05bc\u05be\5\u00c6d\2\u05bd\u05bb\3\2\2\2"+
		"\u05be\u05c1\3\2\2\2\u05bf\u05bd\3\2\2\2\u05bf\u05c0\3\2\2\2\u05c0\u00cb"+
		"\3\2\2\2\u05c1\u05bf\3\2\2\2\u05c2\u05cc\7\u00c6\2\2\u05c3\u05c8\5\u00ce"+
		"h\2\u05c4\u05c5\7\u00cc\2\2\u05c5\u05c7\5\u00ceh\2\u05c6\u05c4\3\2\2\2"+
		"\u05c7\u05ca\3\2\2\2\u05c8\u05c6\3\2\2\2\u05c8\u05c9\3\2\2\2\u05c9\u05cd"+
		"\3\2\2\2\u05ca\u05c8\3\2\2\2\u05cb\u05cd\5\u00b6\\\2\u05cc\u05c3\3\2\2"+
		"\2\u05cc\u05cb\3\2\2\2\u05cc\u05cd\3\2\2\2\u05cd\u05ce\3\2\2\2\u05ce\u05cf"+
		"\7\u00c7\2\2\u05cf\u00cd\3\2\2\2\u05d0\u05d2\7\u00b5\2\2\u05d1\u05d0\3"+
		"\2\2\2\u05d1\u05d2\3\2\2\2\u05d2\u05d3\3\2\2\2\u05d3\u05d7\5\u00b0Y\2"+
		"\u05d4\u05d5\7\u00b8\2\2\u05d5\u05d7\5\u00e2r\2\u05d6\u05d1\3\2\2\2\u05d6"+
		"\u05d4\3\2\2\2\u05d7\u00cf\3\2\2\2\u05d8\u05ee\5\u00d6l\2\u05d9\u05ee"+
		"\5\u00dep\2\u05da\u05db\7\'\2\2\u05db\u05e0\7\u00c6\2\2\u05dc\u05de\5"+
		"\u00d2j\2\u05dd\u05df\7\u00cc\2\2\u05de\u05dd\3\2\2\2\u05de\u05df\3\2"+
		"\2\2\u05df\u05e1\3\2\2\2\u05e0\u05dc\3\2\2\2\u05e0\u05e1\3\2\2\2\u05e1"+
		"\u05e2\3\2\2\2\u05e2\u05ee\7\u00c7\2\2\u05e3\u05e8\7\u00c8\2\2\u05e4\u05e6"+
		"\5\u00d2j\2\u05e5\u05e7\7\u00cc\2\2\u05e6\u05e5\3\2\2\2\u05e6\u05e7\3"+
		"\2\2\2\u05e7\u05e9\3\2\2\2\u05e8\u05e4\3\2\2\2\u05e8\u05e9\3\2\2\2\u05e9"+
		"\u05ea\3\2\2\2\u05ea\u05ee\7\u00c9\2\2\u05eb\u05ec\t\17\2\2\u05ec\u05ee"+
		"\5\u00d0i\2\u05ed\u05d8\3\2\2\2\u05ed\u05d9\3\2\2\2\u05ed\u05da\3\2\2"+
		"\2\u05ed\u05e3\3\2\2\2\u05ed\u05eb\3\2\2\2\u05ee\u00d1\3\2\2\2\u05ef\u05f4"+
		"\5\u00d4k\2\u05f0\u05f1\7\u00cc\2\2\u05f1\u05f3\5\u00d4k\2\u05f2\u05f0"+
		"\3\2\2\2\u05f3\u05f6\3\2\2\2\u05f4\u05f2\3\2\2\2\u05f4\u05f5\3\2\2\2\u05f5"+
		"\u00d3\3\2\2\2\u05f6\u05f4\3\2\2\2\u05f7\u05fa\5\u00d0i\2\u05f8\u05f9"+
		"\7\u0098\2\2\u05f9\u05fb\5\u00d0i\2\u05fa\u05f8\3\2\2\2\u05fa\u05fb\3"+
		"\2\2\2\u05fb\u00d5\3\2\2\2\u05fc\u0602\5\u00d8m\2\u05fd\u0602\5\u0100"+
		"\u0081\2\u05fe\u0602\5\u00dan\2\u05ff\u0602\5\u00c6d\2\u0600\u0602\7a"+
		"\2\2\u0601\u05fc\3\2\2\2\u0601\u05fd\3\2\2\2\u0601\u05fe\3\2\2\2\u0601"+
		"\u05ff\3\2\2\2\u0601\u0600\3\2\2\2\u0602\u00d7\3\2\2\2\u0603\u0608\7\u00d5"+
		"\2\2\u0604\u0608\7\u00d6\2\2\u0605\u0608\7+\2\2\u0606\u0608\5\u00dco\2"+
		"\u0607\u0603\3\2\2\2\u0607\u0604\3\2\2\2\u0607\u0605\3\2\2\2\u0607\u0606"+
		"\3\2\2\2\u0608\u00d9\3\2\2\2\u0609\u060a\t\20\2\2\u060a\u060f\7\u00b2"+
		"\2\2\u060b\u0610\5\u00fc\177\2\u060c\u0610\7\u0082\2\2\u060d\u0610\7~"+
		"\2\2\u060e\u0610\7\177\2\2\u060f\u060b\3\2\2\2\u060f\u060c\3\2\2\2\u060f"+
		"\u060d\3\2\2\2\u060f\u060e\3\2\2\2\u0610\u0619\3\2\2\2\u0611\u0614\5\u00c0"+
		"a\2\u0612\u0614\5\u00f2z\2\u0613\u0611\3\2\2\2\u0613\u0612\3\2\2\2\u0614"+
		"\u0615\3\2\2\2\u0615\u0616\7\u00b2\2\2\u0616\u0617\5\u00fc\177\2\u0617"+
		"\u0619\3\2\2\2\u0618\u0609\3\2\2\2\u0618\u0613\3\2\2\2\u0619\u00db\3\2"+
		"\2\2\u061a\u061b\7\u00d3\2\2\u061b\u00dd\3\2\2\2\u061c\u061e\7\u00dc\2"+
		"\2\u061d\u061f\7\u00e0\2\2\u061e\u061d\3\2\2\2\u061f\u0620\3\2\2\2\u0620"+
		"\u061e\3\2\2\2\u0620\u0621\3\2\2\2\u0621\u062b\3\2\2\2\u0622\u0624\7\u00db"+
		"\2\2\u0623\u0625\7\u00e0\2\2\u0624\u0623\3\2\2\2\u0625\u0626\3\2\2\2\u0626"+
		"\u0624\3\2\2\2\u0626\u0627\3\2\2\2\u0627\u062b\3\2\2\2\u0628\u062b\7\u00da"+
		"\2\2\u0629\u062b\7\u00d9\2\2\u062a\u061c\3\2\2\2\u062a\u0622\3\2\2\2\u062a"+
		"\u0628\3\2\2\2\u062a\u0629\3\2\2\2\u062b\u00df\3\2\2\2\u062c\u0631\5\u00e2"+
		"r\2\u062d\u062e\7\u00cc\2\2\u062e\u0630\5\u00e2r\2\u062f\u062d\3\2\2\2"+
		"\u0630\u0633\3\2\2\2\u0631\u062f\3\2\2\2\u0631\u0632\3\2\2\2\u0632\u00e1"+
		"\3\2\2\2\u0633\u0631\3\2\2\2\u0634\u063b\5\u00ecw\2\u0635\u063b\5\u00e6"+
		"t\2\u0636\u0637\7\u00c6\2\2\u0637\u0638\5\u00b2Z\2\u0638\u0639\7\u00c7"+
		"\2\2\u0639\u063b\3\2\2\2\u063a\u0634\3\2\2\2\u063a\u0635\3\2\2\2\u063a"+
		"\u0636\3\2\2\2\u063b\u063f\3\2\2\2\u063c\u063e\5\u00e4s\2\u063d\u063c"+
		"\3\2\2\2\u063e\u0641\3\2\2\2\u063f\u063d\3\2\2\2\u063f\u0640\3\2\2\2\u0640"+
		"\u00e3\3\2\2\2\u0641\u063f\3\2\2\2\u0642\u0643\7\u00b3\2\2\u0643\u0645"+
		"\5\u00eex\2\u0644\u0646\5\u00eav\2\u0645\u0644\3\2\2\2\u0645\u0646\3\2"+
		"\2\2\u0646\u00e5\3\2\2\2\u0647\u0648\5\u00e8u\2\u0648\u0649\5\u00eav\2"+
		"\u0649\u00e7\3\2\2\2\u064a\u064e\5\u00c6d\2\u064b\u064e\5\u00dan\2\u064c"+
		"\u064e\5\u00ecw\2\u064d\u064a\3\2\2\2\u064d\u064b\3\2\2\2\u064d\u064c"+
		"\3\2\2\2\u064e\u00e9\3\2\2\2\u064f\u0651\5\66\34\2\u0650\u064f\3\2\2\2"+
		"\u0650\u0651\3\2\2\2\u0651\u0652\3\2\2\2\u0652\u0656\5\u00ccg\2\u0653"+
		"\u0655\5\u00f4{\2\u0654\u0653\3\2\2\2\u0655\u0658\3\2\2\2\u0656\u0654"+
		"\3\2\2\2\u0656\u0657\3\2\2\2\u0657\u00eb\3\2\2\2\u0658\u0656\3\2\2\2\u0659"+
		"\u065c\5\u00f2z\2\u065a\u065b\7\u00b2\2\2\u065b\u065d\5\u00f2z\2\u065c"+
		"\u065a\3\2\2\2\u065c\u065d\3\2\2\2\u065d\u0663\3\2\2\2\u065e\u065f\5\u00c0"+
		"a\2\u065f\u0660\7\u00b2\2\2\u0660\u0661\5\u00f2z\2\u0661\u0663\3\2\2\2"+
		"\u0662\u0659\3\2\2\2\u0662\u065e\3\2\2\2\u0663\u00ed\3\2\2\2\u0664\u0667"+
		"\5\u00f0y\2\u0665\u0667\5\u00f2z\2\u0666\u0664\3\2\2\2\u0666\u0665\3\2"+
		"\2\2\u0667\u00ef\3\2\2\2\u0668\u066e\5\u00fc\177\2\u0669\u066a\7\u00ca"+
		"\2\2\u066a\u066b\5\u00b0Y\2\u066b\u066c\7\u00cb\2\2\u066c\u066e\3\2\2"+
		"\2\u066d\u0668\3\2\2\2\u066d\u0669\3\2\2\2\u066e\u0672\3\2\2\2\u066f\u0671"+
		"\5\u00f4{\2\u0670\u066f\3\2\2\2\u0671\u0674\3\2\2\2\u0672\u0670\3\2\2"+
		"\2\u0672\u0673\3\2\2\2\u0673\u00f1\3\2\2\2\u0674\u0672\3\2\2\2\u0675\u0677"+
		"\7\u00c3\2\2\u0676\u0675\3\2\2\2\u0677\u067a\3\2\2\2\u0678\u0676\3\2\2"+
		"\2\u0678\u0679\3\2\2\2\u0679\u0681\3\2\2\2\u067a\u0678\3\2\2\2\u067b\u0682"+
		"\7\u00d4\2\2\u067c\u067d\7\u00c3\2\2\u067d\u067e\7\u00ca\2\2\u067e\u067f"+
		"\5\u00b0Y\2\u067f\u0680\7\u00cb\2\2\u0680\u0682\3\2\2\2\u0681\u067b\3"+
		"\2\2\2\u0681\u067c\3\2\2\2\u0682\u0686\3\2\2\2\u0683\u0685\5\u00f4{\2"+
		"\u0684\u0683\3\2\2\2\u0685\u0688\3\2\2\2\u0686\u0684\3\2\2\2\u0686\u0687"+
		"\3\2\2\2\u0687\u00f3\3\2\2\2\u0688\u0686\3\2\2\2\u0689\u068b\7\u00c8\2"+
		"\2\u068a\u068c\5\u00b0Y\2\u068b\u068a\3\2\2\2\u068b\u068c\3\2\2\2\u068c"+
		"\u068d\3\2\2\2\u068d\u0693\7\u00c9\2\2\u068e\u068f\7\u00ca\2\2\u068f\u0690"+
		"\5\u00b0Y\2\u0690\u0691\7\u00cb\2\2\u0691\u0693\3\2\2\2\u0692\u0689\3"+
		"\2\2\2\u0692\u068e\3\2\2\2\u0693\u00f5\3\2\2\2\u0694\u0696\5\u00f8}\2"+
		"\u0695\u0694\3\2\2\2\u0695\u0696\3\2\2\2\u0696\u069d\3\2\2\2\u0697\u0699"+
		"\7\u00cc\2\2\u0698\u069a\5\u00f8}\2\u0699\u0698\3\2\2\2\u0699\u069a\3"+
		"\2\2\2\u069a\u069c\3\2\2\2\u069b\u0697\3\2\2\2\u069c\u069f\3\2\2\2\u069d"+
		"\u069b\3\2\2\2\u069d\u069e\3\2\2\2\u069e\u00f7\3\2\2\2\u069f\u069d\3\2"+
		"\2\2\u06a0\u06a7\5\u00e2r\2\u06a1\u06a2\7[\2\2\u06a2\u06a3\7\u00c6\2\2"+
		"\u06a3\u06a4\5\u00f6|\2\u06a4\u06a5\7\u00c7\2\2\u06a5\u06a7\3\2\2\2\u06a6"+
		"\u06a0\3\2\2\2\u06a6\u06a1\3\2\2\2\u06a7\u00f9\3\2\2\2\u06a8\u06a9\t\21"+
		"\2\2\u06a9\u00fb\3\2\2\2\u06aa\u06cb\5\u0100\u0081\2\u06ab\u06cb\5\u0102"+
		"\u0082\2\u06ac\u06cb\5\u0106\u0084\2\u06ad\u06cb\5\u00fe\u0080\2\u06ae"+
		"\u06cb\7\u00d3\2\2\u06af\u06cb\7\60\2\2\u06b0\u06cb\7X\2\2\u06b1\u06cb"+
		"\7_\2\2\u06b2\u06cb\7.\2\2\u06b3\u06cb\7\65\2\2\u06b4\u06cb\7l\2\2\u06b5"+
		"\u06cb\7P\2\2\u06b6\u06cb\7c\2\2\u06b7\u06cb\7[\2\2\u06b8\u06cb\7K\2\2"+
		"\u06b9\u06cb\7a\2\2\u06ba\u06cb\7d\2\2\u06bb\u06cb\79\2\2\u06bc\u06cb"+
		"\7`\2\2\u06bd\u06cb\7<\2\2\u06be\u06cb\7-\2\2\u06bf\u06cb\7{\2\2\u06c0"+
		"\u06cb\7Z\2\2\u06c1\u06cb\7,\2\2\u06c2\u06cb\7\63\2\2\u06c3\u06cb\7z\2"+
		"\2\u06c4\u06cb\7\\\2\2\u06c5\u06cb\7^\2\2\u06c6\u06cb\7]\2\2\u06c7\u06cb"+
		"\7\61\2\2\u06c8\u06cb\7L\2\2\u06c9\u06cb\7C\2\2\u06ca\u06aa\3\2\2\2\u06ca"+
		"\u06ab\3\2\2\2\u06ca\u06ac\3\2\2\2\u06ca\u06ad\3\2\2\2\u06ca\u06ae\3\2"+
		"\2\2\u06ca\u06af\3\2\2\2\u06ca\u06b0\3\2\2\2\u06ca\u06b1\3\2\2\2\u06ca"+
		"\u06b2\3\2\2\2\u06ca\u06b3\3\2\2\2\u06ca\u06b4\3\2\2\2\u06ca\u06b5\3\2"+
		"\2\2\u06ca\u06b6\3\2\2\2\u06ca\u06b7\3\2\2\2\u06ca\u06b8\3\2\2\2\u06ca"+
		"\u06b9\3\2\2\2\u06ca\u06ba\3\2\2\2\u06ca\u06bb\3\2\2\2\u06ca\u06bc\3\2"+
		"\2\2\u06ca\u06bd\3\2\2\2\u06ca\u06be\3\2\2\2\u06ca\u06bf\3\2\2\2\u06ca"+
		"\u06c0\3\2\2\2\u06ca\u06c1\3\2\2\2\u06ca\u06c2\3\2\2\2\u06ca\u06c3\3\2"+
		"\2\2\u06ca\u06c4\3\2\2\2\u06ca\u06c5\3\2\2\2\u06ca\u06c6\3\2\2\2\u06ca"+
		"\u06c7\3\2\2\2\u06ca\u06c8\3\2\2\2\u06ca\u06c9\3\2\2\2\u06cb\u00fd\3\2"+
		"\2\2\u06cc\u06cd\t\22\2\2\u06cd\u00ff\3\2\2\2\u06ce\u06cf\t\23\2\2\u06cf"+
		"\u0101\3\2\2\2\u06d0\u06d1\t\24\2\2\u06d1\u0103\3\2\2\2\u06d2\u06d3\t"+
		"\25\2\2\u06d3\u0105\3\2\2\2\u06d4\u06d5\t\26\2\2\u06d5\u0107\3\2\2\2\u00cc"+
		"\u0109\u010e\u0114\u011c\u0121\u0128\u0133\u013d\u0147\u014e\u0152\u0155"+
		"\u015a\u015d\u0161\u016b\u0175\u017d\u0181\u0185\u018a\u0191\u0193\u0198"+
		"\u019e\u01ac\u01b0\u01b6\u01bb\u01c0\u01c6\u01ca\u01d0\u01d7\u01de\u01e3"+
		"\u01e7\u01f0\u01f3\u01f6\u01fb\u01ff\u0203\u0208\u020c\u020e\u0214\u0220"+
		"\u0231\u0238\u0240\u024b\u0253\u025b\u0262\u0269\u0280\u0287\u028f\u0299"+
		"\u029f\u02a3\u02c1\u02cd\u02d1\u02da\u02de\u02e3\u02fd\u0308\u030c\u0310"+
		"\u0319\u0323\u0328\u032e\u0333\u0338\u033d\u0342\u0348\u034e\u0354\u0366"+
		"\u036b\u036e\u0378\u037b\u0388\u0390\u0397\u039a\u039f\u03a3\u03c1\u03c6"+
		"\u03cb\u03d0\u03d5\u03dc\u03e0\u03e6\u03eb\u03ee\u03f1\u03f8\u0400\u040d"+
		"\u0419\u0425\u0431\u0438\u043c\u0440\u0446\u044e\u0455\u0459\u045d\u046a"+
		"\u046d\u0474\u047e\u0482\u0487\u048c\u0499\u04a3\u04a9\u04cd\u04da\u04df"+
		"\u04e2\u04e8\u0505\u0510\u0514\u051a\u051e\u0544\u0554\u0556\u055c\u0564"+
		"\u056b\u056f\u0574\u0579\u057d\u0586\u058c\u0592\u0595\u0599\u059c\u05a0"+
		"\u05a7\u05ab\u05ae\u05b7\u05bf\u05c8\u05cc\u05d1\u05d6\u05de\u05e0\u05e6"+
		"\u05e8\u05ed\u05f4\u05fa\u0601\u0607\u060f\u0613\u0618\u0620\u0626\u062a"+
		"\u0631\u063a\u063f\u0645\u064d\u0650\u0656\u065c\u0662\u0666\u066d\u0672"+
		"\u0678\u0681\u0686\u068b\u0692\u0695\u0699\u069d\u06a6\u06ca";
	public static final ATN _ATN =
		new ATNDeserializer().deserialize(_serializedATN.toCharArray());
	static {
		_decisionToDFA = new DFA[_ATN.getNumberOfDecisions()];
		for (int i = 0; i < _ATN.getNumberOfDecisions(); i++) {
			_decisionToDFA[i] = new DFA(_ATN.getDecisionState(i), i);
		}
	}
}