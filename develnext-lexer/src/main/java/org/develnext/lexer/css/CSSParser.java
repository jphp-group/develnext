// Generated from org\develnext\lexer\css\CSS.g4 by ANTLR 4.5.3
package org.develnext.lexer.css;

import org.antlr.v4.runtime.*;
import org.antlr.v4.runtime.atn.ATN;
import org.antlr.v4.runtime.atn.ATNDeserializer;
import org.antlr.v4.runtime.atn.ParserATNSimulator;
import org.antlr.v4.runtime.atn.PredictionContextCache;
import org.antlr.v4.runtime.dfa.DFA;
import org.antlr.v4.runtime.tree.ParseTreeListener;
import org.antlr.v4.runtime.tree.ParseTreeVisitor;
import org.antlr.v4.runtime.tree.TerminalNode;

import java.util.ArrayList;
import java.util.List;

@SuppressWarnings({"all", "warnings", "unchecked", "unused", "cast"})
public class CSSParser extends Parser {
	static { RuntimeMetaData.checkVersion("4.5.3", RuntimeMetaData.VERSION); }

	protected static final DFA[] _decisionToDFA;
	protected static final PredictionContextCache _sharedContextCache =
		new PredictionContextCache();
	public static final int
		T__0=1, T__1=2, T__2=3, T__3=4, T__4=5, T__5=6, T__6=7, T__7=8, T__8=9, 
		T__9=10, T__10=11, T__11=12, T__12=13, T__13=14, T__14=15, T__15=16, T__16=17, 
		T__17=18, T__18=19, T__19=20, T__20=21, T__21=22, T__22=23, T__23=24, 
		T__24=25, NUMBER=26, NAMESPACE=27, IMPORTANT=28, IMPORT=29, CHARSET=30, 
		FONT_FACE=31, MEDIA=32, PAGE=33, ONLY=34, NOT=35, AND=36, URL=37, CLASS=38, 
		IDENT=39, HEX_COLOR=40, HASH=41, STRING=42, XML_COMMENT=43, COMMENT=44, 
		WS=45;
	public static final int
		RULE_styleSheet = 0, RULE_charSet = 1, RULE_importDecl = 2, RULE_namespace = 3, 
		RULE_statement = 4, RULE_fontFace = 5, RULE_media = 6, RULE_mediaQueryList = 7, 
		RULE_mediaQuery = 8, RULE_mediaExpression = 9, RULE_page = 10, RULE_pseudoPage = 11, 
		RULE_ruleSet = 12, RULE_selectorGroup = 13, RULE_selector = 14, RULE_combinator = 15, 
		RULE_selectorType = 16, RULE_attribute = 17, RULE_pseudo = 18, RULE_functionalPseudo = 19, 
		RULE_negation = 20, RULE_block = 21, RULE_declaration = 22, RULE_priority = 23, 
		RULE_expression = 24, RULE_term = 25, RULE_calc = 26, RULE_sum = 27, RULE_product = 28, 
		RULE_attributeReference = 29, RULE_unit = 30, RULE_function = 31, RULE_number = 32;
	public static final String[] ruleNames = {
		"styleSheet", "charSet", "importDecl", "namespace", "statement", "fontFace", 
		"media", "mediaQueryList", "mediaQuery", "mediaExpression", "page", "pseudoPage", 
		"ruleSet", "selectorGroup", "selector", "combinator", "selectorType", 
		"attribute", "pseudo", "functionalPseudo", "negation", "block", "declaration", 
		"priority", "expression", "term", "calc", "sum", "product", "attributeReference", 
		"unit", "function", "number"
	};

	private static final String[] _LITERAL_NAMES = {
		null, "';'", "'{'", "'}'", "','", "'('", "':'", "')'", "'+'", "'>'", "'~'", 
		"'*'", "'|'", "'['", "'^='", "'$='", "'*='", "'='", "'~='", "'|='", "']'", 
		"'!'", "'/'", "'calc'", "'-'", "'attr'"
	};
	private static final String[] _SYMBOLIC_NAMES = {
		null, null, null, null, null, null, null, null, null, null, null, null, 
		null, null, null, null, null, null, null, null, null, null, null, null, 
		null, null, "NUMBER", "NAMESPACE", "IMPORTANT", "IMPORT", "CHARSET", "FONT_FACE", 
		"MEDIA", "PAGE", "ONLY", "NOT", "AND", "URL", "CLASS", "IDENT", "HEX_COLOR", 
		"HASH", "STRING", "XML_COMMENT", "COMMENT", "WS"
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
	public String getGrammarFileName() { return "CSS.g4"; }

	@Override
	public String[] getRuleNames() { return ruleNames; }

	@Override
	public String getSerializedATN() { return _serializedATN; }

	@Override
	public ATN getATN() { return _ATN; }

	public CSSParser(TokenStream input) {
		super(input);
		_interp = new ParserATNSimulator(this,_ATN,_decisionToDFA,_sharedContextCache);
	}
	public static class StyleSheetContext extends ParserRuleContext {
		public TerminalNode EOF() { return getToken(CSSParser.EOF, 0); }
		public CharSetContext charSet() {
			return getRuleContext(CharSetContext.class,0);
		}
		public List<ImportDeclContext> importDecl() {
			return getRuleContexts(ImportDeclContext.class);
		}
		public ImportDeclContext importDecl(int i) {
			return getRuleContext(ImportDeclContext.class,i);
		}
		public List<NamespaceContext> namespace() {
			return getRuleContexts(NamespaceContext.class);
		}
		public NamespaceContext namespace(int i) {
			return getRuleContext(NamespaceContext.class,i);
		}
		public List<StatementContext> statement() {
			return getRuleContexts(StatementContext.class);
		}
		public StatementContext statement(int i) {
			return getRuleContext(StatementContext.class,i);
		}
		public StyleSheetContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_styleSheet; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterStyleSheet(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitStyleSheet(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitStyleSheet(this);
			else return visitor.visitChildren(this);
		}
	}

	public final StyleSheetContext styleSheet() throws RecognitionException {
		StyleSheetContext _localctx = new StyleSheetContext(_ctx, getState());
		enterRule(_localctx, 0, RULE_styleSheet);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(67);
			_la = _input.LA(1);
			if (_la==CHARSET) {
				{
				setState(66);
				charSet();
				}
			}

			setState(72);
			_errHandler.sync(this);
			_la = _input.LA(1);
			while (_la==IMPORT) {
				{
				{
				setState(69);
				importDecl();
				}
				}
				setState(74);
				_errHandler.sync(this);
				_la = _input.LA(1);
			}
			setState(78);
			_errHandler.sync(this);
			_la = _input.LA(1);
			while (_la==NAMESPACE) {
				{
				{
				setState(75);
				namespace();
				}
				}
				setState(80);
				_errHandler.sync(this);
				_la = _input.LA(1);
			}
			setState(84);
			_errHandler.sync(this);
			_la = _input.LA(1);
			while ((((_la) & ~0x3f) == 0 && ((1L << _la) & ((1L << T__5) | (1L << T__10) | (1L << T__11) | (1L << T__12) | (1L << FONT_FACE) | (1L << MEDIA) | (1L << PAGE) | (1L << CLASS) | (1L << IDENT) | (1L << HASH))) != 0)) {
				{
				{
				setState(81);
				statement();
				}
				}
				setState(86);
				_errHandler.sync(this);
				_la = _input.LA(1);
			}
			setState(87);
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

	public static class CharSetContext extends ParserRuleContext {
		public TerminalNode CHARSET() { return getToken(CSSParser.CHARSET, 0); }
		public TerminalNode STRING() { return getToken(CSSParser.STRING, 0); }
		public CharSetContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_charSet; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterCharSet(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitCharSet(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitCharSet(this);
			else return visitor.visitChildren(this);
		}
	}

	public final CharSetContext charSet() throws RecognitionException {
		CharSetContext _localctx = new CharSetContext(_ctx, getState());
		enterRule(_localctx, 2, RULE_charSet);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(89);
			match(CHARSET);
			setState(90);
			match(STRING);
			setState(91);
			match(T__0);
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

	public static class ImportDeclContext extends ParserRuleContext {
		public TerminalNode IMPORT() { return getToken(CSSParser.IMPORT, 0); }
		public TerminalNode STRING() { return getToken(CSSParser.STRING, 0); }
		public TerminalNode URL() { return getToken(CSSParser.URL, 0); }
		public MediaQueryListContext mediaQueryList() {
			return getRuleContext(MediaQueryListContext.class,0);
		}
		public ImportDeclContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_importDecl; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterImportDecl(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitImportDecl(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitImportDecl(this);
			else return visitor.visitChildren(this);
		}
	}

	public final ImportDeclContext importDecl() throws RecognitionException {
		ImportDeclContext _localctx = new ImportDeclContext(_ctx, getState());
		enterRule(_localctx, 4, RULE_importDecl);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(93);
			match(IMPORT);
			setState(94);
			_la = _input.LA(1);
			if ( !(_la==URL || _la==STRING) ) {
			_errHandler.recoverInline(this);
			} else {
				consume();
			}
			setState(96);
			_errHandler.sync(this);
			switch ( getInterpreter().adaptivePredict(_input,4,_ctx) ) {
			case 1:
				{
				setState(95);
				mediaQueryList();
				}
				break;
			}
			setState(98);
			match(T__0);
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

	public static class NamespaceContext extends ParserRuleContext {
		public TerminalNode NAMESPACE() { return getToken(CSSParser.NAMESPACE, 0); }
		public TerminalNode STRING() { return getToken(CSSParser.STRING, 0); }
		public TerminalNode URL() { return getToken(CSSParser.URL, 0); }
		public TerminalNode IDENT() { return getToken(CSSParser.IDENT, 0); }
		public NamespaceContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_namespace; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterNamespace(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitNamespace(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitNamespace(this);
			else return visitor.visitChildren(this);
		}
	}

	public final NamespaceContext namespace() throws RecognitionException {
		NamespaceContext _localctx = new NamespaceContext(_ctx, getState());
		enterRule(_localctx, 6, RULE_namespace);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(100);
			match(NAMESPACE);
			setState(102);
			_la = _input.LA(1);
			if (_la==IDENT) {
				{
				setState(101);
				match(IDENT);
				}
			}

			setState(104);
			_la = _input.LA(1);
			if ( !(_la==URL || _la==STRING) ) {
			_errHandler.recoverInline(this);
			} else {
				consume();
			}
			setState(105);
			match(T__0);
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
		public StatementContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_statement; }
	 
		public StatementContext() { }
		public void copyFrom(StatementContext ctx) {
			super.copyFrom(ctx);
		}
	}
	public static class MediaDeclContext extends StatementContext {
		public MediaContext media() {
			return getRuleContext(MediaContext.class,0);
		}
		public MediaDeclContext(StatementContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterMediaDecl(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitMediaDecl(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitMediaDecl(this);
			else return visitor.visitChildren(this);
		}
	}
	public static class FontFaceDeclContext extends StatementContext {
		public FontFaceContext fontFace() {
			return getRuleContext(FontFaceContext.class,0);
		}
		public FontFaceDeclContext(StatementContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterFontFaceDecl(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitFontFaceDecl(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitFontFaceDecl(this);
			else return visitor.visitChildren(this);
		}
	}
	public static class PageDeclContext extends StatementContext {
		public PageContext page() {
			return getRuleContext(PageContext.class,0);
		}
		public PageDeclContext(StatementContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterPageDecl(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitPageDecl(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitPageDecl(this);
			else return visitor.visitChildren(this);
		}
	}
	public static class RuleDeclContext extends StatementContext {
		public RuleSetContext ruleSet() {
			return getRuleContext(RuleSetContext.class,0);
		}
		public RuleDeclContext(StatementContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterRuleDecl(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitRuleDecl(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitRuleDecl(this);
			else return visitor.visitChildren(this);
		}
	}

	public final StatementContext statement() throws RecognitionException {
		StatementContext _localctx = new StatementContext(_ctx, getState());
		enterRule(_localctx, 8, RULE_statement);
		try {
			setState(111);
			switch (_input.LA(1)) {
			case T__5:
			case T__10:
			case T__11:
			case T__12:
			case CLASS:
			case IDENT:
			case HASH:
				_localctx = new RuleDeclContext(_localctx);
				enterOuterAlt(_localctx, 1);
				{
				setState(107);
				ruleSet();
				}
				break;
			case MEDIA:
				_localctx = new MediaDeclContext(_localctx);
				enterOuterAlt(_localctx, 2);
				{
				setState(108);
				media();
				}
				break;
			case PAGE:
				_localctx = new PageDeclContext(_localctx);
				enterOuterAlt(_localctx, 3);
				{
				setState(109);
				page();
				}
				break;
			case FONT_FACE:
				_localctx = new FontFaceDeclContext(_localctx);
				enterOuterAlt(_localctx, 4);
				{
				setState(110);
				fontFace();
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

	public static class FontFaceContext extends ParserRuleContext {
		public TerminalNode FONT_FACE() { return getToken(CSSParser.FONT_FACE, 0); }
		public BlockContext block() {
			return getRuleContext(BlockContext.class,0);
		}
		public FontFaceContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_fontFace; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterFontFace(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitFontFace(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitFontFace(this);
			else return visitor.visitChildren(this);
		}
	}

	public final FontFaceContext fontFace() throws RecognitionException {
		FontFaceContext _localctx = new FontFaceContext(_ctx, getState());
		enterRule(_localctx, 10, RULE_fontFace);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(113);
			match(FONT_FACE);
			setState(114);
			block();
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

	public static class MediaContext extends ParserRuleContext {
		public TerminalNode MEDIA() { return getToken(CSSParser.MEDIA, 0); }
		public MediaQueryListContext mediaQueryList() {
			return getRuleContext(MediaQueryListContext.class,0);
		}
		public List<RuleSetContext> ruleSet() {
			return getRuleContexts(RuleSetContext.class);
		}
		public RuleSetContext ruleSet(int i) {
			return getRuleContext(RuleSetContext.class,i);
		}
		public MediaContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_media; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterMedia(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitMedia(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitMedia(this);
			else return visitor.visitChildren(this);
		}
	}

	public final MediaContext media() throws RecognitionException {
		MediaContext _localctx = new MediaContext(_ctx, getState());
		enterRule(_localctx, 12, RULE_media);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(116);
			match(MEDIA);
			setState(117);
			mediaQueryList();
			setState(118);
			match(T__1);
			setState(122);
			_errHandler.sync(this);
			_la = _input.LA(1);
			while ((((_la) & ~0x3f) == 0 && ((1L << _la) & ((1L << T__5) | (1L << T__10) | (1L << T__11) | (1L << T__12) | (1L << CLASS) | (1L << IDENT) | (1L << HASH))) != 0)) {
				{
				{
				setState(119);
				ruleSet();
				}
				}
				setState(124);
				_errHandler.sync(this);
				_la = _input.LA(1);
			}
			setState(125);
			match(T__2);
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

	public static class MediaQueryListContext extends ParserRuleContext {
		public List<MediaQueryContext> mediaQuery() {
			return getRuleContexts(MediaQueryContext.class);
		}
		public MediaQueryContext mediaQuery(int i) {
			return getRuleContext(MediaQueryContext.class,i);
		}
		public MediaQueryListContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_mediaQueryList; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterMediaQueryList(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitMediaQueryList(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitMediaQueryList(this);
			else return visitor.visitChildren(this);
		}
	}

	public final MediaQueryListContext mediaQueryList() throws RecognitionException {
		MediaQueryListContext _localctx = new MediaQueryListContext(_ctx, getState());
		enterRule(_localctx, 14, RULE_mediaQueryList);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(135);
			_la = _input.LA(1);
			if ((((_la) & ~0x3f) == 0 && ((1L << _la) & ((1L << T__4) | (1L << ONLY) | (1L << NOT) | (1L << IDENT))) != 0)) {
				{
				setState(127);
				mediaQuery();
				setState(132);
				_errHandler.sync(this);
				_la = _input.LA(1);
				while (_la==T__3) {
					{
					{
					setState(128);
					match(T__3);
					setState(129);
					mediaQuery();
					}
					}
					setState(134);
					_errHandler.sync(this);
					_la = _input.LA(1);
				}
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

	public static class MediaQueryContext extends ParserRuleContext {
		public TerminalNode IDENT() { return getToken(CSSParser.IDENT, 0); }
		public List<TerminalNode> AND() { return getTokens(CSSParser.AND); }
		public TerminalNode AND(int i) {
			return getToken(CSSParser.AND, i);
		}
		public List<MediaExpressionContext> mediaExpression() {
			return getRuleContexts(MediaExpressionContext.class);
		}
		public MediaExpressionContext mediaExpression(int i) {
			return getRuleContext(MediaExpressionContext.class,i);
		}
		public TerminalNode ONLY() { return getToken(CSSParser.ONLY, 0); }
		public TerminalNode NOT() { return getToken(CSSParser.NOT, 0); }
		public MediaQueryContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_mediaQuery; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterMediaQuery(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitMediaQuery(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitMediaQuery(this);
			else return visitor.visitChildren(this);
		}
	}

	public final MediaQueryContext mediaQuery() throws RecognitionException {
		MediaQueryContext _localctx = new MediaQueryContext(_ctx, getState());
		enterRule(_localctx, 16, RULE_mediaQuery);
		int _la;
		try {
			setState(156);
			switch (_input.LA(1)) {
			case ONLY:
			case NOT:
			case IDENT:
				enterOuterAlt(_localctx, 1);
				{
				setState(138);
				_la = _input.LA(1);
				if (_la==ONLY || _la==NOT) {
					{
					setState(137);
					_la = _input.LA(1);
					if ( !(_la==ONLY || _la==NOT) ) {
					_errHandler.recoverInline(this);
					} else {
						consume();
					}
					}
				}

				setState(140);
				match(IDENT);
				setState(145);
				_errHandler.sync(this);
				_la = _input.LA(1);
				while (_la==AND) {
					{
					{
					setState(141);
					match(AND);
					setState(142);
					mediaExpression();
					}
					}
					setState(147);
					_errHandler.sync(this);
					_la = _input.LA(1);
				}
				}
				break;
			case T__4:
				enterOuterAlt(_localctx, 2);
				{
				setState(148);
				mediaExpression();
				setState(153);
				_errHandler.sync(this);
				_la = _input.LA(1);
				while (_la==AND) {
					{
					{
					setState(149);
					match(AND);
					setState(150);
					mediaExpression();
					}
					}
					setState(155);
					_errHandler.sync(this);
					_la = _input.LA(1);
				}
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

	public static class MediaExpressionContext extends ParserRuleContext {
		public TerminalNode IDENT() { return getToken(CSSParser.IDENT, 0); }
		public ExpressionContext expression() {
			return getRuleContext(ExpressionContext.class,0);
		}
		public MediaExpressionContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_mediaExpression; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterMediaExpression(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitMediaExpression(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitMediaExpression(this);
			else return visitor.visitChildren(this);
		}
	}

	public final MediaExpressionContext mediaExpression() throws RecognitionException {
		MediaExpressionContext _localctx = new MediaExpressionContext(_ctx, getState());
		enterRule(_localctx, 18, RULE_mediaExpression);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(158);
			match(T__4);
			setState(159);
			match(IDENT);
			setState(162);
			_la = _input.LA(1);
			if (_la==T__5) {
				{
				setState(160);
				match(T__5);
				setState(161);
				expression();
				}
			}

			setState(164);
			match(T__6);
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

	public static class PageContext extends ParserRuleContext {
		public TerminalNode PAGE() { return getToken(CSSParser.PAGE, 0); }
		public BlockContext block() {
			return getRuleContext(BlockContext.class,0);
		}
		public PseudoPageContext pseudoPage() {
			return getRuleContext(PseudoPageContext.class,0);
		}
		public PageContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_page; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterPage(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitPage(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitPage(this);
			else return visitor.visitChildren(this);
		}
	}

	public final PageContext page() throws RecognitionException {
		PageContext _localctx = new PageContext(_ctx, getState());
		enterRule(_localctx, 20, RULE_page);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(166);
			match(PAGE);
			setState(168);
			_la = _input.LA(1);
			if (_la==T__5) {
				{
				setState(167);
				pseudoPage();
				}
			}

			setState(170);
			block();
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

	public static class PseudoPageContext extends ParserRuleContext {
		public TerminalNode IDENT() { return getToken(CSSParser.IDENT, 0); }
		public PseudoPageContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_pseudoPage; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterPseudoPage(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitPseudoPage(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitPseudoPage(this);
			else return visitor.visitChildren(this);
		}
	}

	public final PseudoPageContext pseudoPage() throws RecognitionException {
		PseudoPageContext _localctx = new PseudoPageContext(_ctx, getState());
		enterRule(_localctx, 22, RULE_pseudoPage);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(172);
			match(T__5);
			setState(173);
			match(IDENT);
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

	public static class RuleSetContext extends ParserRuleContext {
		public SelectorGroupContext selectorGroup() {
			return getRuleContext(SelectorGroupContext.class,0);
		}
		public BlockContext block() {
			return getRuleContext(BlockContext.class,0);
		}
		public RuleSetContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_ruleSet; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterRuleSet(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitRuleSet(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitRuleSet(this);
			else return visitor.visitChildren(this);
		}
	}

	public final RuleSetContext ruleSet() throws RecognitionException {
		RuleSetContext _localctx = new RuleSetContext(_ctx, getState());
		enterRule(_localctx, 24, RULE_ruleSet);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(175);
			selectorGroup();
			setState(176);
			block();
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

	public static class SelectorGroupContext extends ParserRuleContext {
		public List<SelectorContext> selector() {
			return getRuleContexts(SelectorContext.class);
		}
		public SelectorContext selector(int i) {
			return getRuleContext(SelectorContext.class,i);
		}
		public SelectorGroupContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_selectorGroup; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterSelectorGroup(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitSelectorGroup(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitSelectorGroup(this);
			else return visitor.visitChildren(this);
		}
	}

	public final SelectorGroupContext selectorGroup() throws RecognitionException {
		SelectorGroupContext _localctx = new SelectorGroupContext(_ctx, getState());
		enterRule(_localctx, 26, RULE_selectorGroup);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(178);
			selector();
			setState(183);
			_errHandler.sync(this);
			_la = _input.LA(1);
			while (_la==T__3) {
				{
				{
				setState(179);
				match(T__3);
				setState(180);
				selector();
				}
				}
				setState(185);
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

	public static class SelectorContext extends ParserRuleContext {
		public List<SelectorTypeContext> selectorType() {
			return getRuleContexts(SelectorTypeContext.class);
		}
		public SelectorTypeContext selectorType(int i) {
			return getRuleContext(SelectorTypeContext.class,i);
		}
		public List<CombinatorContext> combinator() {
			return getRuleContexts(CombinatorContext.class);
		}
		public CombinatorContext combinator(int i) {
			return getRuleContext(CombinatorContext.class,i);
		}
		public SelectorContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_selector; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterSelector(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitSelector(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitSelector(this);
			else return visitor.visitChildren(this);
		}
	}

	public final SelectorContext selector() throws RecognitionException {
		SelectorContext _localctx = new SelectorContext(_ctx, getState());
		enterRule(_localctx, 28, RULE_selector);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(187); 
			_errHandler.sync(this);
			_la = _input.LA(1);
			do {
				{
				{
				setState(186);
				selectorType();
				}
				}
				setState(189); 
				_errHandler.sync(this);
				_la = _input.LA(1);
			} while ( (((_la) & ~0x3f) == 0 && ((1L << _la) & ((1L << T__5) | (1L << T__10) | (1L << T__11) | (1L << T__12) | (1L << CLASS) | (1L << IDENT) | (1L << HASH))) != 0) );
			setState(194);
			_errHandler.sync(this);
			_la = _input.LA(1);
			while ((((_la) & ~0x3f) == 0 && ((1L << _la) & ((1L << T__7) | (1L << T__8) | (1L << T__9))) != 0)) {
				{
				{
				setState(191);
				combinator();
				}
				}
				setState(196);
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

	public static class CombinatorContext extends ParserRuleContext {
		public Token COMBINATOR;
		public List<SelectorTypeContext> selectorType() {
			return getRuleContexts(SelectorTypeContext.class);
		}
		public SelectorTypeContext selectorType(int i) {
			return getRuleContext(SelectorTypeContext.class,i);
		}
		public CombinatorContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_combinator; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterCombinator(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitCombinator(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitCombinator(this);
			else return visitor.visitChildren(this);
		}
	}

	public final CombinatorContext combinator() throws RecognitionException {
		CombinatorContext _localctx = new CombinatorContext(_ctx, getState());
		enterRule(_localctx, 30, RULE_combinator);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(197);
			((CombinatorContext)_localctx).COMBINATOR = _input.LT(1);
			_la = _input.LA(1);
			if ( !((((_la) & ~0x3f) == 0 && ((1L << _la) & ((1L << T__7) | (1L << T__8) | (1L << T__9))) != 0)) ) {
				((CombinatorContext)_localctx).COMBINATOR = (Token)_errHandler.recoverInline(this);
			} else {
				consume();
			}
			setState(199); 
			_errHandler.sync(this);
			_la = _input.LA(1);
			do {
				{
				{
				setState(198);
				selectorType();
				}
				}
				setState(201); 
				_errHandler.sync(this);
				_la = _input.LA(1);
			} while ( (((_la) & ~0x3f) == 0 && ((1L << _la) & ((1L << T__5) | (1L << T__10) | (1L << T__11) | (1L << T__12) | (1L << CLASS) | (1L << IDENT) | (1L << HASH))) != 0) );
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

	public static class SelectorTypeContext extends ParserRuleContext {
		public SelectorTypeContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_selectorType; }
	 
		public SelectorTypeContext() { }
		public void copyFrom(SelectorTypeContext ctx) {
			super.copyFrom(ctx);
		}
	}
	public static class TypeSelectorContext extends SelectorTypeContext {
		public TerminalNode IDENT() { return getToken(CSSParser.IDENT, 0); }
		public TypeSelectorContext(SelectorTypeContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterTypeSelector(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitTypeSelector(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitTypeSelector(this);
			else return visitor.visitChildren(this);
		}
	}
	public static class IdSelectorContext extends SelectorTypeContext {
		public TerminalNode HASH() { return getToken(CSSParser.HASH, 0); }
		public IdSelectorContext(SelectorTypeContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterIdSelector(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitIdSelector(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitIdSelector(this);
			else return visitor.visitChildren(this);
		}
	}
	public static class PseudoSelectorContext extends SelectorTypeContext {
		public PseudoContext pseudo() {
			return getRuleContext(PseudoContext.class,0);
		}
		public PseudoSelectorContext(SelectorTypeContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterPseudoSelector(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitPseudoSelector(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitPseudoSelector(this);
			else return visitor.visitChildren(this);
		}
	}
	public static class UnivesalNamespaceTypeSelectorContext extends SelectorTypeContext {
		public TerminalNode IDENT() { return getToken(CSSParser.IDENT, 0); }
		public UnivesalNamespaceTypeSelectorContext(SelectorTypeContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterUnivesalNamespaceTypeSelector(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitUnivesalNamespaceTypeSelector(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitUnivesalNamespaceTypeSelector(this);
			else return visitor.visitChildren(this);
		}
	}
	public static class AttributeSelectorContext extends SelectorTypeContext {
		public AttributeContext attribute() {
			return getRuleContext(AttributeContext.class,0);
		}
		public AttributeSelectorContext(SelectorTypeContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterAttributeSelector(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitAttributeSelector(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitAttributeSelector(this);
			else return visitor.visitChildren(this);
		}
	}
	public static class NotSelectorContext extends SelectorTypeContext {
		public NegationContext negation() {
			return getRuleContext(NegationContext.class,0);
		}
		public NotSelectorContext(SelectorTypeContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterNotSelector(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitNotSelector(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitNotSelector(this);
			else return visitor.visitChildren(this);
		}
	}
	public static class IdentNamespaceTypeSelectorContext extends SelectorTypeContext {
		public Token prefix;
		public Token id;
		public List<TerminalNode> IDENT() { return getTokens(CSSParser.IDENT); }
		public TerminalNode IDENT(int i) {
			return getToken(CSSParser.IDENT, i);
		}
		public IdentNamespaceTypeSelectorContext(SelectorTypeContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterIdentNamespaceTypeSelector(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitIdentNamespaceTypeSelector(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitIdentNamespaceTypeSelector(this);
			else return visitor.visitChildren(this);
		}
	}
	public static class UniversalSelectorContext extends SelectorTypeContext {
		public UniversalSelectorContext(SelectorTypeContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterUniversalSelector(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitUniversalSelector(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitUniversalSelector(this);
			else return visitor.visitChildren(this);
		}
	}
	public static class IdentNamespaceUniversalSelectorContext extends SelectorTypeContext {
		public Token prefix;
		public TerminalNode IDENT() { return getToken(CSSParser.IDENT, 0); }
		public IdentNamespaceUniversalSelectorContext(SelectorTypeContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterIdentNamespaceUniversalSelector(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitIdentNamespaceUniversalSelector(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitIdentNamespaceUniversalSelector(this);
			else return visitor.visitChildren(this);
		}
	}
	public static class UniversalNamepaceUniversalSelectorContext extends SelectorTypeContext {
		public UniversalNamepaceUniversalSelectorContext(SelectorTypeContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterUniversalNamepaceUniversalSelector(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitUniversalNamepaceUniversalSelector(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitUniversalNamepaceUniversalSelector(this);
			else return visitor.visitChildren(this);
		}
	}
	public static class ClassSelectorContext extends SelectorTypeContext {
		public TerminalNode CLASS() { return getToken(CSSParser.CLASS, 0); }
		public ClassSelectorContext(SelectorTypeContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterClassSelector(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitClassSelector(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitClassSelector(this);
			else return visitor.visitChildren(this);
		}
	}
	public static class NonamespaceTypeSelectorContext extends SelectorTypeContext {
		public TerminalNode IDENT() { return getToken(CSSParser.IDENT, 0); }
		public NonamespaceTypeSelectorContext(SelectorTypeContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterNonamespaceTypeSelector(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitNonamespaceTypeSelector(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitNonamespaceTypeSelector(this);
			else return visitor.visitChildren(this);
		}
	}
	public static class NonamespaceUniversalSelectorContext extends SelectorTypeContext {
		public NonamespaceUniversalSelectorContext(SelectorTypeContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterNonamespaceUniversalSelector(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitNonamespaceUniversalSelector(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitNonamespaceUniversalSelector(this);
			else return visitor.visitChildren(this);
		}
	}

	public final SelectorTypeContext selectorType() throws RecognitionException {
		SelectorTypeContext _localctx = new SelectorTypeContext(_ctx, getState());
		enterRule(_localctx, 32, RULE_selectorType);
		try {
			setState(226);
			_errHandler.sync(this);
			switch ( getInterpreter().adaptivePredict(_input,20,_ctx) ) {
			case 1:
				_localctx = new UniversalNamepaceUniversalSelectorContext(_localctx);
				enterOuterAlt(_localctx, 1);
				{
				setState(203);
				match(T__10);
				setState(204);
				match(T__11);
				setState(205);
				match(T__10);
				}
				break;
			case 2:
				_localctx = new IdentNamespaceUniversalSelectorContext(_localctx);
				enterOuterAlt(_localctx, 2);
				{
				setState(206);
				((IdentNamespaceUniversalSelectorContext)_localctx).prefix = match(IDENT);
				setState(207);
				match(T__11);
				setState(208);
				match(T__10);
				}
				break;
			case 3:
				_localctx = new NonamespaceUniversalSelectorContext(_localctx);
				enterOuterAlt(_localctx, 3);
				{
				setState(209);
				match(T__11);
				setState(210);
				match(T__10);
				}
				break;
			case 4:
				_localctx = new UnivesalNamespaceTypeSelectorContext(_localctx);
				enterOuterAlt(_localctx, 4);
				{
				setState(211);
				match(T__10);
				setState(212);
				match(T__11);
				setState(213);
				match(IDENT);
				}
				break;
			case 5:
				_localctx = new IdentNamespaceTypeSelectorContext(_localctx);
				enterOuterAlt(_localctx, 5);
				{
				setState(214);
				((IdentNamespaceTypeSelectorContext)_localctx).prefix = match(IDENT);
				setState(215);
				match(T__11);
				setState(216);
				((IdentNamespaceTypeSelectorContext)_localctx).id = match(IDENT);
				}
				break;
			case 6:
				_localctx = new NonamespaceTypeSelectorContext(_localctx);
				enterOuterAlt(_localctx, 6);
				{
				setState(217);
				match(T__11);
				setState(218);
				match(IDENT);
				}
				break;
			case 7:
				_localctx = new UniversalSelectorContext(_localctx);
				enterOuterAlt(_localctx, 7);
				{
				setState(219);
				match(T__10);
				}
				break;
			case 8:
				_localctx = new TypeSelectorContext(_localctx);
				enterOuterAlt(_localctx, 8);
				{
				setState(220);
				match(IDENT);
				}
				break;
			case 9:
				_localctx = new IdSelectorContext(_localctx);
				enterOuterAlt(_localctx, 9);
				{
				setState(221);
				match(HASH);
				}
				break;
			case 10:
				_localctx = new ClassSelectorContext(_localctx);
				enterOuterAlt(_localctx, 10);
				{
				setState(222);
				match(CLASS);
				}
				break;
			case 11:
				_localctx = new AttributeSelectorContext(_localctx);
				enterOuterAlt(_localctx, 11);
				{
				setState(223);
				attribute();
				}
				break;
			case 12:
				_localctx = new PseudoSelectorContext(_localctx);
				enterOuterAlt(_localctx, 12);
				{
				setState(224);
				pseudo();
				}
				break;
			case 13:
				_localctx = new NotSelectorContext(_localctx);
				enterOuterAlt(_localctx, 13);
				{
				setState(225);
				negation();
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

	public static class AttributeContext extends ParserRuleContext {
		public Token prefix;
		public Token name;
		public Token operator;
		public Token value;
		public List<TerminalNode> IDENT() { return getTokens(CSSParser.IDENT); }
		public TerminalNode IDENT(int i) {
			return getToken(CSSParser.IDENT, i);
		}
		public TerminalNode STRING() { return getToken(CSSParser.STRING, 0); }
		public AttributeContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_attribute; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterAttribute(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitAttribute(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitAttribute(this);
			else return visitor.visitChildren(this);
		}
	}

	public final AttributeContext attribute() throws RecognitionException {
		AttributeContext _localctx = new AttributeContext(_ctx, getState());
		enterRule(_localctx, 34, RULE_attribute);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(228);
			match(T__12);
			setState(234);
			_errHandler.sync(this);
			switch ( getInterpreter().adaptivePredict(_input,22,_ctx) ) {
			case 1:
				{
				setState(231);
				switch (_input.LA(1)) {
				case IDENT:
					{
					setState(229);
					((AttributeContext)_localctx).prefix = match(IDENT);
					}
					break;
				case T__10:
					{
					setState(230);
					((AttributeContext)_localctx).prefix = match(T__10);
					}
					break;
				case T__11:
					break;
				default:
					throw new NoViableAltException(this);
				}
				setState(233);
				match(T__11);
				}
				break;
			}
			setState(236);
			((AttributeContext)_localctx).name = match(IDENT);
			setState(242);
			_la = _input.LA(1);
			if ((((_la) & ~0x3f) == 0 && ((1L << _la) & ((1L << T__13) | (1L << T__14) | (1L << T__15) | (1L << T__16) | (1L << T__17) | (1L << T__18))) != 0)) {
				{
				setState(237);
				((AttributeContext)_localctx).operator = _input.LT(1);
				_la = _input.LA(1);
				if ( !((((_la) & ~0x3f) == 0 && ((1L << _la) & ((1L << T__13) | (1L << T__14) | (1L << T__15) | (1L << T__16) | (1L << T__17) | (1L << T__18))) != 0)) ) {
					((AttributeContext)_localctx).operator = (Token)_errHandler.recoverInline(this);
				} else {
					consume();
				}
				setState(240);
				switch (_input.LA(1)) {
				case IDENT:
					{
					setState(238);
					((AttributeContext)_localctx).value = match(IDENT);
					}
					break;
				case STRING:
					{
					setState(239);
					((AttributeContext)_localctx).value = match(STRING);
					}
					break;
				default:
					throw new NoViableAltException(this);
				}
				}
			}

			setState(244);
			match(T__19);
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

	public static class PseudoContext extends ParserRuleContext {
		public Token twoColon;
		public Token id;
		public FunctionalPseudoContext functionalPseudo() {
			return getRuleContext(FunctionalPseudoContext.class,0);
		}
		public TerminalNode IDENT() { return getToken(CSSParser.IDENT, 0); }
		public PseudoContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_pseudo; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterPseudo(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitPseudo(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitPseudo(this);
			else return visitor.visitChildren(this);
		}
	}

	public final PseudoContext pseudo() throws RecognitionException {
		PseudoContext _localctx = new PseudoContext(_ctx, getState());
		enterRule(_localctx, 36, RULE_pseudo);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(246);
			match(T__5);
			setState(248);
			_la = _input.LA(1);
			if (_la==T__5) {
				{
				setState(247);
				((PseudoContext)_localctx).twoColon = match(T__5);
				}
			}

			setState(252);
			_errHandler.sync(this);
			switch ( getInterpreter().adaptivePredict(_input,26,_ctx) ) {
			case 1:
				{
				setState(250);
				((PseudoContext)_localctx).id = match(IDENT);
				}
				break;
			case 2:
				{
				setState(251);
				functionalPseudo();
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

	public static class FunctionalPseudoContext extends ParserRuleContext {
		public TerminalNode IDENT() { return getToken(CSSParser.IDENT, 0); }
		public ExpressionContext expression() {
			return getRuleContext(ExpressionContext.class,0);
		}
		public FunctionalPseudoContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_functionalPseudo; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterFunctionalPseudo(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitFunctionalPseudo(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitFunctionalPseudo(this);
			else return visitor.visitChildren(this);
		}
	}

	public final FunctionalPseudoContext functionalPseudo() throws RecognitionException {
		FunctionalPseudoContext _localctx = new FunctionalPseudoContext(_ctx, getState());
		enterRule(_localctx, 38, RULE_functionalPseudo);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(254);
			match(IDENT);
			setState(255);
			match(T__4);
			setState(256);
			expression();
			setState(257);
			match(T__6);
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

	public static class NegationContext extends ParserRuleContext {
		public TerminalNode NOT() { return getToken(CSSParser.NOT, 0); }
		public SelectorTypeContext selectorType() {
			return getRuleContext(SelectorTypeContext.class,0);
		}
		public NegationContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_negation; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterNegation(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitNegation(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitNegation(this);
			else return visitor.visitChildren(this);
		}
	}

	public final NegationContext negation() throws RecognitionException {
		NegationContext _localctx = new NegationContext(_ctx, getState());
		enterRule(_localctx, 40, RULE_negation);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(259);
			match(T__5);
			setState(260);
			match(NOT);
			setState(261);
			match(T__4);
			setState(262);
			selectorType();
			setState(263);
			match(T__6);
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

	public static class BlockContext extends ParserRuleContext {
		public List<DeclarationContext> declaration() {
			return getRuleContexts(DeclarationContext.class);
		}
		public DeclarationContext declaration(int i) {
			return getRuleContext(DeclarationContext.class,i);
		}
		public BlockContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_block; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterBlock(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitBlock(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitBlock(this);
			else return visitor.visitChildren(this);
		}
	}

	public final BlockContext block() throws RecognitionException {
		BlockContext _localctx = new BlockContext(_ctx, getState());
		enterRule(_localctx, 42, RULE_block);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(265);
			match(T__1);
			setState(267);
			_la = _input.LA(1);
			if (_la==IDENT) {
				{
				setState(266);
				declaration();
				}
			}

			setState(275);
			_errHandler.sync(this);
			_la = _input.LA(1);
			while (_la==T__0) {
				{
				{
				setState(269);
				match(T__0);
				setState(271);
				_la = _input.LA(1);
				if (_la==IDENT) {
					{
					setState(270);
					declaration();
					}
				}

				}
				}
				setState(277);
				_errHandler.sync(this);
				_la = _input.LA(1);
			}
			setState(278);
			match(T__2);
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

	public static class DeclarationContext extends ParserRuleContext {
		public TerminalNode IDENT() { return getToken(CSSParser.IDENT, 0); }
		public ExpressionContext expression() {
			return getRuleContext(ExpressionContext.class,0);
		}
		public PriorityContext priority() {
			return getRuleContext(PriorityContext.class,0);
		}
		public DeclarationContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_declaration; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterDeclaration(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitDeclaration(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitDeclaration(this);
			else return visitor.visitChildren(this);
		}
	}

	public final DeclarationContext declaration() throws RecognitionException {
		DeclarationContext _localctx = new DeclarationContext(_ctx, getState());
		enterRule(_localctx, 44, RULE_declaration);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(280);
			match(IDENT);
			setState(281);
			match(T__5);
			setState(282);
			expression();
			setState(284);
			_la = _input.LA(1);
			if (_la==T__20) {
				{
				setState(283);
				priority();
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

	public static class PriorityContext extends ParserRuleContext {
		public TerminalNode IMPORTANT() { return getToken(CSSParser.IMPORTANT, 0); }
		public PriorityContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_priority; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterPriority(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitPriority(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitPriority(this);
			else return visitor.visitChildren(this);
		}
	}

	public final PriorityContext priority() throws RecognitionException {
		PriorityContext _localctx = new PriorityContext(_ctx, getState());
		enterRule(_localctx, 46, RULE_priority);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(286);
			match(T__20);
			setState(287);
			match(IMPORTANT);
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
		public TermContext left;
		public TermContext term;
		public List<TermContext> right = new ArrayList<TermContext>();
		public List<TermContext> term() {
			return getRuleContexts(TermContext.class);
		}
		public TermContext term(int i) {
			return getRuleContext(TermContext.class,i);
		}
		public ExpressionContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_expression; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterExpression(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitExpression(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitExpression(this);
			else return visitor.visitChildren(this);
		}
	}

	public final ExpressionContext expression() throws RecognitionException {
		ExpressionContext _localctx = new ExpressionContext(_ctx, getState());
		enterRule(_localctx, 48, RULE_expression);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(289);
			((ExpressionContext)_localctx).left = term();
			setState(296);
			_errHandler.sync(this);
			_la = _input.LA(1);
			while ((((_la) & ~0x3f) == 0 && ((1L << _la) & ((1L << T__3) | (1L << T__7) | (1L << T__21) | (1L << T__22) | (1L << T__23) | (1L << NUMBER) | (1L << URL) | (1L << IDENT) | (1L << HEX_COLOR) | (1L << STRING))) != 0)) {
				{
				{
				setState(291);
				_la = _input.LA(1);
				if (_la==T__3 || _la==T__21) {
					{
					setState(290);
					_la = _input.LA(1);
					if ( !(_la==T__3 || _la==T__21) ) {
					_errHandler.recoverInline(this);
					} else {
						consume();
					}
					}
				}

				setState(293);
				((ExpressionContext)_localctx).term = term();
				((ExpressionContext)_localctx).right.add(((ExpressionContext)_localctx).term);
				}
				}
				setState(298);
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

	public static class TermContext extends ParserRuleContext {
		public TermContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_term; }
	 
		public TermContext() { }
		public void copyFrom(TermContext ctx) {
			super.copyFrom(ctx);
		}
	}
	public static class StringExprContext extends TermContext {
		public TerminalNode STRING() { return getToken(CSSParser.STRING, 0); }
		public StringExprContext(TermContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterStringExpr(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitStringExpr(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitStringExpr(this);
			else return visitor.visitChildren(this);
		}
	}
	public static class UrlExprContext extends TermContext {
		public TerminalNode URL() { return getToken(CSSParser.URL, 0); }
		public UrlExprContext(TermContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterUrlExpr(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitUrlExpr(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitUrlExpr(this);
			else return visitor.visitChildren(this);
		}
	}
	public static class NumberExprContext extends TermContext {
		public NumberContext number() {
			return getRuleContext(NumberContext.class,0);
		}
		public NumberExprContext(TermContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterNumberExpr(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitNumberExpr(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitNumberExpr(this);
			else return visitor.visitChildren(this);
		}
	}
	public static class CalcExprContext extends TermContext {
		public CalcContext calc() {
			return getRuleContext(CalcContext.class,0);
		}
		public CalcExprContext(TermContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterCalcExpr(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitCalcExpr(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitCalcExpr(this);
			else return visitor.visitChildren(this);
		}
	}
	public static class HexColorExprContext extends TermContext {
		public TerminalNode HEX_COLOR() { return getToken(CSSParser.HEX_COLOR, 0); }
		public HexColorExprContext(TermContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterHexColorExpr(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitHexColorExpr(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitHexColorExpr(this);
			else return visitor.visitChildren(this);
		}
	}
	public static class IdExprContext extends TermContext {
		public TerminalNode IDENT() { return getToken(CSSParser.IDENT, 0); }
		public IdExprContext(TermContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterIdExpr(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitIdExpr(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitIdExpr(this);
			else return visitor.visitChildren(this);
		}
	}
	public static class FunctionExprContext extends TermContext {
		public FunctionContext function() {
			return getRuleContext(FunctionContext.class,0);
		}
		public FunctionExprContext(TermContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterFunctionExpr(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitFunctionExpr(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitFunctionExpr(this);
			else return visitor.visitChildren(this);
		}
	}

	public final TermContext term() throws RecognitionException {
		TermContext _localctx = new TermContext(_ctx, getState());
		enterRule(_localctx, 50, RULE_term);
		try {
			setState(306);
			_errHandler.sync(this);
			switch ( getInterpreter().adaptivePredict(_input,33,_ctx) ) {
			case 1:
				_localctx = new NumberExprContext(_localctx);
				enterOuterAlt(_localctx, 1);
				{
				setState(299);
				number();
				}
				break;
			case 2:
				_localctx = new StringExprContext(_localctx);
				enterOuterAlt(_localctx, 2);
				{
				setState(300);
				match(STRING);
				}
				break;
			case 3:
				_localctx = new IdExprContext(_localctx);
				enterOuterAlt(_localctx, 3);
				{
				setState(301);
				match(IDENT);
				}
				break;
			case 4:
				_localctx = new UrlExprContext(_localctx);
				enterOuterAlt(_localctx, 4);
				{
				setState(302);
				match(URL);
				}
				break;
			case 5:
				_localctx = new HexColorExprContext(_localctx);
				enterOuterAlt(_localctx, 5);
				{
				setState(303);
				match(HEX_COLOR);
				}
				break;
			case 6:
				_localctx = new CalcExprContext(_localctx);
				enterOuterAlt(_localctx, 6);
				{
				setState(304);
				calc();
				}
				break;
			case 7:
				_localctx = new FunctionExprContext(_localctx);
				enterOuterAlt(_localctx, 7);
				{
				setState(305);
				function();
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

	public static class CalcContext extends ParserRuleContext {
		public SumContext sum() {
			return getRuleContext(SumContext.class,0);
		}
		public CalcContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_calc; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterCalc(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitCalc(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitCalc(this);
			else return visitor.visitChildren(this);
		}
	}

	public final CalcContext calc() throws RecognitionException {
		CalcContext _localctx = new CalcContext(_ctx, getState());
		enterRule(_localctx, 52, RULE_calc);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(308);
			match(T__22);
			setState(309);
			match(T__4);
			setState(310);
			sum();
			setState(311);
			match(T__6);
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

	public static class SumContext extends ParserRuleContext {
		public List<ProductContext> product() {
			return getRuleContexts(ProductContext.class);
		}
		public ProductContext product(int i) {
			return getRuleContext(ProductContext.class,i);
		}
		public SumContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_sum; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterSum(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitSum(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitSum(this);
			else return visitor.visitChildren(this);
		}
	}

	public final SumContext sum() throws RecognitionException {
		SumContext _localctx = new SumContext(_ctx, getState());
		enterRule(_localctx, 54, RULE_sum);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(313);
			product();
			setState(318);
			_errHandler.sync(this);
			_la = _input.LA(1);
			while (_la==T__7 || _la==T__23) {
				{
				{
				setState(314);
				_la = _input.LA(1);
				if ( !(_la==T__7 || _la==T__23) ) {
				_errHandler.recoverInline(this);
				} else {
					consume();
				}
				setState(315);
				product();
				}
				}
				setState(320);
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

	public static class ProductContext extends ParserRuleContext {
		public List<UnitContext> unit() {
			return getRuleContexts(UnitContext.class);
		}
		public UnitContext unit(int i) {
			return getRuleContext(UnitContext.class,i);
		}
		public List<TerminalNode> NUMBER() { return getTokens(CSSParser.NUMBER); }
		public TerminalNode NUMBER(int i) {
			return getToken(CSSParser.NUMBER, i);
		}
		public ProductContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_product; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterProduct(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitProduct(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitProduct(this);
			else return visitor.visitChildren(this);
		}
	}

	public final ProductContext product() throws RecognitionException {
		ProductContext _localctx = new ProductContext(_ctx, getState());
		enterRule(_localctx, 56, RULE_product);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(321);
			unit();
			setState(330);
			_errHandler.sync(this);
			_la = _input.LA(1);
			while (_la==T__10 || _la==T__21) {
				{
				{
				setState(326);
				switch (_input.LA(1)) {
				case T__10:
					{
					setState(322);
					match(T__10);
					setState(323);
					unit();
					}
					break;
				case T__21:
					{
					setState(324);
					match(T__21);
					setState(325);
					match(NUMBER);
					}
					break;
				default:
					throw new NoViableAltException(this);
				}
				}
				}
				setState(332);
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

	public static class AttributeReferenceContext extends ParserRuleContext {
		public Token name;
		public Token type;
		public List<TerminalNode> IDENT() { return getTokens(CSSParser.IDENT); }
		public TerminalNode IDENT(int i) {
			return getToken(CSSParser.IDENT, i);
		}
		public UnitContext unit() {
			return getRuleContext(UnitContext.class,0);
		}
		public CalcContext calc() {
			return getRuleContext(CalcContext.class,0);
		}
		public AttributeReferenceContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_attributeReference; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterAttributeReference(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitAttributeReference(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitAttributeReference(this);
			else return visitor.visitChildren(this);
		}
	}

	public final AttributeReferenceContext attributeReference() throws RecognitionException {
		AttributeReferenceContext _localctx = new AttributeReferenceContext(_ctx, getState());
		enterRule(_localctx, 58, RULE_attributeReference);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(334); 
			_errHandler.sync(this);
			_la = _input.LA(1);
			do {
				{
				{
				setState(333);
				match(T__24);
				}
				}
				setState(336); 
				_errHandler.sync(this);
				_la = _input.LA(1);
			} while ( _la==T__24 );
			setState(338);
			match(T__4);
			setState(339);
			((AttributeReferenceContext)_localctx).name = match(IDENT);
			setState(341);
			_la = _input.LA(1);
			if (_la==IDENT) {
				{
				setState(340);
				((AttributeReferenceContext)_localctx).type = match(IDENT);
				}
			}

			setState(348);
			_la = _input.LA(1);
			if (_la==T__3) {
				{
				setState(343);
				match(T__3);
				setState(346);
				_errHandler.sync(this);
				switch ( getInterpreter().adaptivePredict(_input,39,_ctx) ) {
				case 1:
					{
					setState(344);
					unit();
					}
					break;
				case 2:
					{
					setState(345);
					calc();
					}
					break;
				}
				}
			}

			setState(350);
			match(T__6);
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

	public static class UnitContext extends ParserRuleContext {
		public UnitContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_unit; }
	 
		public UnitContext() { }
		public void copyFrom(UnitContext ctx) {
			super.copyFrom(ctx);
		}
	}
	public static class CalcDeclContext extends UnitContext {
		public CalcContext calc() {
			return getRuleContext(CalcContext.class,0);
		}
		public CalcDeclContext(UnitContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterCalcDecl(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitCalcDecl(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitCalcDecl(this);
			else return visitor.visitChildren(this);
		}
	}
	public static class CalcSumDeclContext extends UnitContext {
		public SumContext sum() {
			return getRuleContext(SumContext.class,0);
		}
		public CalcSumDeclContext(UnitContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterCalcSumDecl(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitCalcSumDecl(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitCalcSumDecl(this);
			else return visitor.visitChildren(this);
		}
	}
	public static class AttributeReferenceDeclContext extends UnitContext {
		public AttributeReferenceContext attributeReference() {
			return getRuleContext(AttributeReferenceContext.class,0);
		}
		public AttributeReferenceDeclContext(UnitContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterAttributeReferenceDecl(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitAttributeReferenceDecl(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitAttributeReferenceDecl(this);
			else return visitor.visitChildren(this);
		}
	}
	public static class CalcNumberDeclContext extends UnitContext {
		public TerminalNode NUMBER() { return getToken(CSSParser.NUMBER, 0); }
		public CalcNumberDeclContext(UnitContext ctx) { copyFrom(ctx); }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterCalcNumberDecl(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitCalcNumberDecl(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitCalcNumberDecl(this);
			else return visitor.visitChildren(this);
		}
	}

	public final UnitContext unit() throws RecognitionException {
		UnitContext _localctx = new UnitContext(_ctx, getState());
		enterRule(_localctx, 60, RULE_unit);
		try {
			setState(359);
			switch (_input.LA(1)) {
			case NUMBER:
				_localctx = new CalcNumberDeclContext(_localctx);
				enterOuterAlt(_localctx, 1);
				{
				setState(352);
				match(NUMBER);
				}
				break;
			case T__4:
				_localctx = new CalcSumDeclContext(_localctx);
				enterOuterAlt(_localctx, 2);
				{
				setState(353);
				match(T__4);
				setState(354);
				sum();
				setState(355);
				match(T__6);
				}
				break;
			case T__22:
				_localctx = new CalcDeclContext(_localctx);
				enterOuterAlt(_localctx, 3);
				{
				setState(357);
				calc();
				}
				break;
			case T__24:
				_localctx = new AttributeReferenceDeclContext(_localctx);
				enterOuterAlt(_localctx, 4);
				{
				setState(358);
				attributeReference();
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

	public static class FunctionContext extends ParserRuleContext {
		public TerminalNode IDENT() { return getToken(CSSParser.IDENT, 0); }
		public ExpressionContext expression() {
			return getRuleContext(ExpressionContext.class,0);
		}
		public FunctionContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_function; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterFunction(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitFunction(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitFunction(this);
			else return visitor.visitChildren(this);
		}
	}

	public final FunctionContext function() throws RecognitionException {
		FunctionContext _localctx = new FunctionContext(_ctx, getState());
		enterRule(_localctx, 62, RULE_function);
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(361);
			match(IDENT);
			setState(362);
			match(T__4);
			setState(363);
			expression();
			setState(364);
			match(T__6);
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

	public static class NumberContext extends ParserRuleContext {
		public TerminalNode NUMBER() { return getToken(CSSParser.NUMBER, 0); }
		public NumberContext(ParserRuleContext parent, int invokingState) {
			super(parent, invokingState);
		}
		@Override public int getRuleIndex() { return RULE_number; }
		@Override
		public void enterRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).enterNumber(this);
		}
		@Override
		public void exitRule(ParseTreeListener listener) {
			if ( listener instanceof CSSListener ) ((CSSListener)listener).exitNumber(this);
		}
		@Override
		public <T> T accept(ParseTreeVisitor<? extends T> visitor) {
			if ( visitor instanceof CSSVisitor ) return ((CSSVisitor<? extends T>)visitor).visitNumber(this);
			else return visitor.visitChildren(this);
		}
	}

	public final NumberContext number() throws RecognitionException {
		NumberContext _localctx = new NumberContext(_ctx, getState());
		enterRule(_localctx, 64, RULE_number);
		int _la;
		try {
			enterOuterAlt(_localctx, 1);
			{
			setState(367);
			_la = _input.LA(1);
			if (_la==T__7 || _la==T__23) {
				{
				setState(366);
				_la = _input.LA(1);
				if ( !(_la==T__7 || _la==T__23) ) {
				_errHandler.recoverInline(this);
				} else {
					consume();
				}
				}
			}

			setState(369);
			match(NUMBER);
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

	public static final String _serializedATN =
		"\3\u0430\ud6d1\u8206\uad2d\u4417\uaef1\u8d80\uaadd\3/\u0176\4\2\t\2\4"+
		"\3\t\3\4\4\t\4\4\5\t\5\4\6\t\6\4\7\t\7\4\b\t\b\4\t\t\t\4\n\t\n\4\13\t"+
		"\13\4\f\t\f\4\r\t\r\4\16\t\16\4\17\t\17\4\20\t\20\4\21\t\21\4\22\t\22"+
		"\4\23\t\23\4\24\t\24\4\25\t\25\4\26\t\26\4\27\t\27\4\30\t\30\4\31\t\31"+
		"\4\32\t\32\4\33\t\33\4\34\t\34\4\35\t\35\4\36\t\36\4\37\t\37\4 \t \4!"+
		"\t!\4\"\t\"\3\2\5\2F\n\2\3\2\7\2I\n\2\f\2\16\2L\13\2\3\2\7\2O\n\2\f\2"+
		"\16\2R\13\2\3\2\7\2U\n\2\f\2\16\2X\13\2\3\2\3\2\3\3\3\3\3\3\3\3\3\4\3"+
		"\4\3\4\5\4c\n\4\3\4\3\4\3\5\3\5\5\5i\n\5\3\5\3\5\3\5\3\6\3\6\3\6\3\6\5"+
		"\6r\n\6\3\7\3\7\3\7\3\b\3\b\3\b\3\b\7\b{\n\b\f\b\16\b~\13\b\3\b\3\b\3"+
		"\t\3\t\3\t\7\t\u0085\n\t\f\t\16\t\u0088\13\t\5\t\u008a\n\t\3\n\5\n\u008d"+
		"\n\n\3\n\3\n\3\n\7\n\u0092\n\n\f\n\16\n\u0095\13\n\3\n\3\n\3\n\7\n\u009a"+
		"\n\n\f\n\16\n\u009d\13\n\5\n\u009f\n\n\3\13\3\13\3\13\3\13\5\13\u00a5"+
		"\n\13\3\13\3\13\3\f\3\f\5\f\u00ab\n\f\3\f\3\f\3\r\3\r\3\r\3\16\3\16\3"+
		"\16\3\17\3\17\3\17\7\17\u00b8\n\17\f\17\16\17\u00bb\13\17\3\20\6\20\u00be"+
		"\n\20\r\20\16\20\u00bf\3\20\7\20\u00c3\n\20\f\20\16\20\u00c6\13\20\3\21"+
		"\3\21\6\21\u00ca\n\21\r\21\16\21\u00cb\3\22\3\22\3\22\3\22\3\22\3\22\3"+
		"\22\3\22\3\22\3\22\3\22\3\22\3\22\3\22\3\22\3\22\3\22\3\22\3\22\3\22\3"+
		"\22\3\22\3\22\5\22\u00e5\n\22\3\23\3\23\3\23\5\23\u00ea\n\23\3\23\5\23"+
		"\u00ed\n\23\3\23\3\23\3\23\3\23\5\23\u00f3\n\23\5\23\u00f5\n\23\3\23\3"+
		"\23\3\24\3\24\5\24\u00fb\n\24\3\24\3\24\5\24\u00ff\n\24\3\25\3\25\3\25"+
		"\3\25\3\25\3\26\3\26\3\26\3\26\3\26\3\26\3\27\3\27\5\27\u010e\n\27\3\27"+
		"\3\27\5\27\u0112\n\27\7\27\u0114\n\27\f\27\16\27\u0117\13\27\3\27\3\27"+
		"\3\30\3\30\3\30\3\30\5\30\u011f\n\30\3\31\3\31\3\31\3\32\3\32\5\32\u0126"+
		"\n\32\3\32\7\32\u0129\n\32\f\32\16\32\u012c\13\32\3\33\3\33\3\33\3\33"+
		"\3\33\3\33\3\33\5\33\u0135\n\33\3\34\3\34\3\34\3\34\3\34\3\35\3\35\3\35"+
		"\7\35\u013f\n\35\f\35\16\35\u0142\13\35\3\36\3\36\3\36\3\36\3\36\5\36"+
		"\u0149\n\36\7\36\u014b\n\36\f\36\16\36\u014e\13\36\3\37\6\37\u0151\n\37"+
		"\r\37\16\37\u0152\3\37\3\37\3\37\5\37\u0158\n\37\3\37\3\37\3\37\5\37\u015d"+
		"\n\37\5\37\u015f\n\37\3\37\3\37\3 \3 \3 \3 \3 \3 \3 \5 \u016a\n \3!\3"+
		"!\3!\3!\3!\3\"\5\"\u0172\n\"\3\"\3\"\3\"\2\2#\2\4\6\b\n\f\16\20\22\24"+
		"\26\30\32\34\36 \"$&(*,.\60\62\64\668:<>@B\2\b\4\2\'\',,\3\2$%\3\2\n\f"+
		"\3\2\20\25\4\2\6\6\30\30\4\2\n\n\32\32\u0194\2E\3\2\2\2\4[\3\2\2\2\6_"+
		"\3\2\2\2\bf\3\2\2\2\nq\3\2\2\2\fs\3\2\2\2\16v\3\2\2\2\20\u0089\3\2\2\2"+
		"\22\u009e\3\2\2\2\24\u00a0\3\2\2\2\26\u00a8\3\2\2\2\30\u00ae\3\2\2\2\32"+
		"\u00b1\3\2\2\2\34\u00b4\3\2\2\2\36\u00bd\3\2\2\2 \u00c7\3\2\2\2\"\u00e4"+
		"\3\2\2\2$\u00e6\3\2\2\2&\u00f8\3\2\2\2(\u0100\3\2\2\2*\u0105\3\2\2\2,"+
		"\u010b\3\2\2\2.\u011a\3\2\2\2\60\u0120\3\2\2\2\62\u0123\3\2\2\2\64\u0134"+
		"\3\2\2\2\66\u0136\3\2\2\28\u013b\3\2\2\2:\u0143\3\2\2\2<\u0150\3\2\2\2"+
		">\u0169\3\2\2\2@\u016b\3\2\2\2B\u0171\3\2\2\2DF\5\4\3\2ED\3\2\2\2EF\3"+
		"\2\2\2FJ\3\2\2\2GI\5\6\4\2HG\3\2\2\2IL\3\2\2\2JH\3\2\2\2JK\3\2\2\2KP\3"+
		"\2\2\2LJ\3\2\2\2MO\5\b\5\2NM\3\2\2\2OR\3\2\2\2PN\3\2\2\2PQ\3\2\2\2QV\3"+
		"\2\2\2RP\3\2\2\2SU\5\n\6\2TS\3\2\2\2UX\3\2\2\2VT\3\2\2\2VW\3\2\2\2WY\3"+
		"\2\2\2XV\3\2\2\2YZ\7\2\2\3Z\3\3\2\2\2[\\\7 \2\2\\]\7,\2\2]^\7\3\2\2^\5"+
		"\3\2\2\2_`\7\37\2\2`b\t\2\2\2ac\5\20\t\2ba\3\2\2\2bc\3\2\2\2cd\3\2\2\2"+
		"de\7\3\2\2e\7\3\2\2\2fh\7\35\2\2gi\7)\2\2hg\3\2\2\2hi\3\2\2\2ij\3\2\2"+
		"\2jk\t\2\2\2kl\7\3\2\2l\t\3\2\2\2mr\5\32\16\2nr\5\16\b\2or\5\26\f\2pr"+
		"\5\f\7\2qm\3\2\2\2qn\3\2\2\2qo\3\2\2\2qp\3\2\2\2r\13\3\2\2\2st\7!\2\2"+
		"tu\5,\27\2u\r\3\2\2\2vw\7\"\2\2wx\5\20\t\2x|\7\4\2\2y{\5\32\16\2zy\3\2"+
		"\2\2{~\3\2\2\2|z\3\2\2\2|}\3\2\2\2}\177\3\2\2\2~|\3\2\2\2\177\u0080\7"+
		"\5\2\2\u0080\17\3\2\2\2\u0081\u0086\5\22\n\2\u0082\u0083\7\6\2\2\u0083"+
		"\u0085\5\22\n\2\u0084\u0082\3\2\2\2\u0085\u0088\3\2\2\2\u0086\u0084\3"+
		"\2\2\2\u0086\u0087\3\2\2\2\u0087\u008a\3\2\2\2\u0088\u0086\3\2\2\2\u0089"+
		"\u0081\3\2\2\2\u0089\u008a\3\2\2\2\u008a\21\3\2\2\2\u008b\u008d\t\3\2"+
		"\2\u008c\u008b\3\2\2\2\u008c\u008d\3\2\2\2\u008d\u008e\3\2\2\2\u008e\u0093"+
		"\7)\2\2\u008f\u0090\7&\2\2\u0090\u0092\5\24\13\2\u0091\u008f\3\2\2\2\u0092"+
		"\u0095\3\2\2\2\u0093\u0091\3\2\2\2\u0093\u0094\3\2\2\2\u0094\u009f\3\2"+
		"\2\2\u0095\u0093\3\2\2\2\u0096\u009b\5\24\13\2\u0097\u0098\7&\2\2\u0098"+
		"\u009a\5\24\13\2\u0099\u0097\3\2\2\2\u009a\u009d\3\2\2\2\u009b\u0099\3"+
		"\2\2\2\u009b\u009c\3\2\2\2\u009c\u009f\3\2\2\2\u009d\u009b\3\2\2\2\u009e"+
		"\u008c\3\2\2\2\u009e\u0096\3\2\2\2\u009f\23\3\2\2\2\u00a0\u00a1\7\7\2"+
		"\2\u00a1\u00a4\7)\2\2\u00a2\u00a3\7\b\2\2\u00a3\u00a5\5\62\32\2\u00a4"+
		"\u00a2\3\2\2\2\u00a4\u00a5\3\2\2\2\u00a5\u00a6\3\2\2\2\u00a6\u00a7\7\t"+
		"\2\2\u00a7\25\3\2\2\2\u00a8\u00aa\7#\2\2\u00a9\u00ab\5\30\r\2\u00aa\u00a9"+
		"\3\2\2\2\u00aa\u00ab\3\2\2\2\u00ab\u00ac\3\2\2\2\u00ac\u00ad\5,\27\2\u00ad"+
		"\27\3\2\2\2\u00ae\u00af\7\b\2\2\u00af\u00b0\7)\2\2\u00b0\31\3\2\2\2\u00b1"+
		"\u00b2\5\34\17\2\u00b2\u00b3\5,\27\2\u00b3\33\3\2\2\2\u00b4\u00b9\5\36"+
		"\20\2\u00b5\u00b6\7\6\2\2\u00b6\u00b8\5\36\20\2\u00b7\u00b5\3\2\2\2\u00b8"+
		"\u00bb\3\2\2\2\u00b9\u00b7\3\2\2\2\u00b9\u00ba\3\2\2\2\u00ba\35\3\2\2"+
		"\2\u00bb\u00b9\3\2\2\2\u00bc\u00be\5\"\22\2\u00bd\u00bc\3\2\2\2\u00be"+
		"\u00bf\3\2\2\2\u00bf\u00bd\3\2\2\2\u00bf\u00c0\3\2\2\2\u00c0\u00c4\3\2"+
		"\2\2\u00c1\u00c3\5 \21\2\u00c2\u00c1\3\2\2\2\u00c3\u00c6\3\2\2\2\u00c4"+
		"\u00c2\3\2\2\2\u00c4\u00c5\3\2\2\2\u00c5\37\3\2\2\2\u00c6\u00c4\3\2\2"+
		"\2\u00c7\u00c9\t\4\2\2\u00c8\u00ca\5\"\22\2\u00c9\u00c8\3\2\2\2\u00ca"+
		"\u00cb\3\2\2\2\u00cb\u00c9\3\2\2\2\u00cb\u00cc\3\2\2\2\u00cc!\3\2\2\2"+
		"\u00cd\u00ce\7\r\2\2\u00ce\u00cf\7\16\2\2\u00cf\u00e5\7\r\2\2\u00d0\u00d1"+
		"\7)\2\2\u00d1\u00d2\7\16\2\2\u00d2\u00e5\7\r\2\2\u00d3\u00d4\7\16\2\2"+
		"\u00d4\u00e5\7\r\2\2\u00d5\u00d6\7\r\2\2\u00d6\u00d7\7\16\2\2\u00d7\u00e5"+
		"\7)\2\2\u00d8\u00d9\7)\2\2\u00d9\u00da\7\16\2\2\u00da\u00e5\7)\2\2\u00db"+
		"\u00dc\7\16\2\2\u00dc\u00e5\7)\2\2\u00dd\u00e5\7\r\2\2\u00de\u00e5\7)"+
		"\2\2\u00df\u00e5\7+\2\2\u00e0\u00e5\7(\2\2\u00e1\u00e5\5$\23\2\u00e2\u00e5"+
		"\5&\24\2\u00e3\u00e5\5*\26\2\u00e4\u00cd\3\2\2\2\u00e4\u00d0\3\2\2\2\u00e4"+
		"\u00d3\3\2\2\2\u00e4\u00d5\3\2\2\2\u00e4\u00d8\3\2\2\2\u00e4\u00db\3\2"+
		"\2\2\u00e4\u00dd\3\2\2\2\u00e4\u00de\3\2\2\2\u00e4\u00df\3\2\2\2\u00e4"+
		"\u00e0\3\2\2\2\u00e4\u00e1\3\2\2\2\u00e4\u00e2\3\2\2\2\u00e4\u00e3\3\2"+
		"\2\2\u00e5#\3\2\2\2\u00e6\u00ec\7\17\2\2\u00e7\u00ea\7)\2\2\u00e8\u00ea"+
		"\7\r\2\2\u00e9\u00e7\3\2\2\2\u00e9\u00e8\3\2\2\2\u00e9\u00ea\3\2\2\2\u00ea"+
		"\u00eb\3\2\2\2\u00eb\u00ed\7\16\2\2\u00ec\u00e9\3\2\2\2\u00ec\u00ed\3"+
		"\2\2\2\u00ed\u00ee\3\2\2\2\u00ee\u00f4\7)\2\2\u00ef\u00f2\t\5\2\2\u00f0"+
		"\u00f3\7)\2\2\u00f1\u00f3\7,\2\2\u00f2\u00f0\3\2\2\2\u00f2\u00f1\3\2\2"+
		"\2\u00f3\u00f5\3\2\2\2\u00f4\u00ef\3\2\2\2\u00f4\u00f5\3\2\2\2\u00f5\u00f6"+
		"\3\2\2\2\u00f6\u00f7\7\26\2\2\u00f7%\3\2\2\2\u00f8\u00fa\7\b\2\2\u00f9"+
		"\u00fb\7\b\2\2\u00fa\u00f9\3\2\2\2\u00fa\u00fb\3\2\2\2\u00fb\u00fe\3\2"+
		"\2\2\u00fc\u00ff\7)\2\2\u00fd\u00ff\5(\25\2\u00fe\u00fc\3\2\2\2\u00fe"+
		"\u00fd\3\2\2\2\u00ff\'\3\2\2\2\u0100\u0101\7)\2\2\u0101\u0102\7\7\2\2"+
		"\u0102\u0103\5\62\32\2\u0103\u0104\7\t\2\2\u0104)\3\2\2\2\u0105\u0106"+
		"\7\b\2\2\u0106\u0107\7%\2\2\u0107\u0108\7\7\2\2\u0108\u0109\5\"\22\2\u0109"+
		"\u010a\7\t\2\2\u010a+\3\2\2\2\u010b\u010d\7\4\2\2\u010c\u010e\5.\30\2"+
		"\u010d\u010c\3\2\2\2\u010d\u010e\3\2\2\2\u010e\u0115\3\2\2\2\u010f\u0111"+
		"\7\3\2\2\u0110\u0112\5.\30\2\u0111\u0110\3\2\2\2\u0111\u0112\3\2\2\2\u0112"+
		"\u0114\3\2\2\2\u0113\u010f\3\2\2\2\u0114\u0117\3\2\2\2\u0115\u0113\3\2"+
		"\2\2\u0115\u0116\3\2\2\2\u0116\u0118\3\2\2\2\u0117\u0115\3\2\2\2\u0118"+
		"\u0119\7\5\2\2\u0119-\3\2\2\2\u011a\u011b\7)\2\2\u011b\u011c\7\b\2\2\u011c"+
		"\u011e\5\62\32\2\u011d\u011f\5\60\31\2\u011e\u011d\3\2\2\2\u011e\u011f"+
		"\3\2\2\2\u011f/\3\2\2\2\u0120\u0121\7\27\2\2\u0121\u0122\7\36\2\2\u0122"+
		"\61\3\2\2\2\u0123\u012a\5\64\33\2\u0124\u0126\t\6\2\2\u0125\u0124\3\2"+
		"\2\2\u0125\u0126\3\2\2\2\u0126\u0127\3\2\2\2\u0127\u0129\5\64\33\2\u0128"+
		"\u0125\3\2\2\2\u0129\u012c\3\2\2\2\u012a\u0128\3\2\2\2\u012a\u012b\3\2"+
		"\2\2\u012b\63\3\2\2\2\u012c\u012a\3\2\2\2\u012d\u0135\5B\"\2\u012e\u0135"+
		"\7,\2\2\u012f\u0135\7)\2\2\u0130\u0135\7\'\2\2\u0131\u0135\7*\2\2\u0132"+
		"\u0135\5\66\34\2\u0133\u0135\5@!\2\u0134\u012d\3\2\2\2\u0134\u012e\3\2"+
		"\2\2\u0134\u012f\3\2\2\2\u0134\u0130\3\2\2\2\u0134\u0131\3\2\2\2\u0134"+
		"\u0132\3\2\2\2\u0134\u0133\3\2\2\2\u0135\65\3\2\2\2\u0136\u0137\7\31\2"+
		"\2\u0137\u0138\7\7\2\2\u0138\u0139\58\35\2\u0139\u013a\7\t\2\2\u013a\67"+
		"\3\2\2\2\u013b\u0140\5:\36\2\u013c\u013d\t\7\2\2\u013d\u013f\5:\36\2\u013e"+
		"\u013c\3\2\2\2\u013f\u0142\3\2\2\2\u0140\u013e\3\2\2\2\u0140\u0141\3\2"+
		"\2\2\u01419\3\2\2\2\u0142\u0140\3\2\2\2\u0143\u014c\5> \2\u0144\u0145"+
		"\7\r\2\2\u0145\u0149\5> \2\u0146\u0147\7\30\2\2\u0147\u0149\7\34\2\2\u0148"+
		"\u0144\3\2\2\2\u0148\u0146\3\2\2\2\u0149\u014b\3\2\2\2\u014a\u0148\3\2"+
		"\2\2\u014b\u014e\3\2\2\2\u014c\u014a\3\2\2\2\u014c\u014d\3\2\2\2\u014d"+
		";\3\2\2\2\u014e\u014c\3\2\2\2\u014f\u0151\7\33\2\2\u0150\u014f\3\2\2\2"+
		"\u0151\u0152\3\2\2\2\u0152\u0150\3\2\2\2\u0152\u0153\3\2\2\2\u0153\u0154"+
		"\3\2\2\2\u0154\u0155\7\7\2\2\u0155\u0157\7)\2\2\u0156\u0158\7)\2\2\u0157"+
		"\u0156\3\2\2\2\u0157\u0158\3\2\2\2\u0158\u015e\3\2\2\2\u0159\u015c\7\6"+
		"\2\2\u015a\u015d\5> \2\u015b\u015d\5\66\34\2\u015c\u015a\3\2\2\2\u015c"+
		"\u015b\3\2\2\2\u015d\u015f\3\2\2\2\u015e\u0159\3\2\2\2\u015e\u015f\3\2"+
		"\2\2\u015f\u0160\3\2\2\2\u0160\u0161\7\t\2\2\u0161=\3\2\2\2\u0162\u016a"+
		"\7\34\2\2\u0163\u0164\7\7\2\2\u0164\u0165\58\35\2\u0165\u0166\7\t\2\2"+
		"\u0166\u016a\3\2\2\2\u0167\u016a\5\66\34\2\u0168\u016a\5<\37\2\u0169\u0162"+
		"\3\2\2\2\u0169\u0163\3\2\2\2\u0169\u0167\3\2\2\2\u0169\u0168\3\2\2\2\u016a"+
		"?\3\2\2\2\u016b\u016c\7)\2\2\u016c\u016d\7\7\2\2\u016d\u016e\5\62\32\2"+
		"\u016e\u016f\7\t\2\2\u016fA\3\2\2\2\u0170\u0172\t\7\2\2\u0171\u0170\3"+
		"\2\2\2\u0171\u0172\3\2\2\2\u0172\u0173\3\2\2\2\u0173\u0174\7\34\2\2\u0174"+
		"C\3\2\2\2-EJPVbhq|\u0086\u0089\u008c\u0093\u009b\u009e\u00a4\u00aa\u00b9"+
		"\u00bf\u00c4\u00cb\u00e4\u00e9\u00ec\u00f2\u00f4\u00fa\u00fe\u010d\u0111"+
		"\u0115\u011e\u0125\u012a\u0134\u0140\u0148\u014c\u0152\u0157\u015c\u015e"+
		"\u0169\u0171";
	public static final ATN _ATN =
		new ATNDeserializer().deserialize(_serializedATN.toCharArray());
	static {
		_decisionToDFA = new DFA[_ATN.getNumberOfDecisions()];
		for (int i = 0; i < _ATN.getNumberOfDecisions(); i++) {
			_decisionToDFA[i] = new DFA(_ATN.getDecisionState(i), i);
		}
	}
}