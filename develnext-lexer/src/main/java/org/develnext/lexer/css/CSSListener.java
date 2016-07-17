// Generated from org\develnext\lexer\css\CSS.g4 by ANTLR 4.5.3
package org.develnext.lexer.css;
import org.antlr.v4.runtime.tree.ParseTreeListener;

/**
 * This interface defines a complete listener for a parse tree produced by
 * {@link CSSParser}.
 */
public interface CSSListener extends ParseTreeListener {
	/**
	 * Enter a parse tree produced by {@link CSSParser#styleSheet}.
	 * @param ctx the parse tree
	 */
	void enterStyleSheet(CSSParser.StyleSheetContext ctx);
	/**
	 * Exit a parse tree produced by {@link CSSParser#styleSheet}.
	 * @param ctx the parse tree
	 */
	void exitStyleSheet(CSSParser.StyleSheetContext ctx);
	/**
	 * Enter a parse tree produced by {@link CSSParser#charSet}.
	 * @param ctx the parse tree
	 */
	void enterCharSet(CSSParser.CharSetContext ctx);
	/**
	 * Exit a parse tree produced by {@link CSSParser#charSet}.
	 * @param ctx the parse tree
	 */
	void exitCharSet(CSSParser.CharSetContext ctx);
	/**
	 * Enter a parse tree produced by {@link CSSParser#importDecl}.
	 * @param ctx the parse tree
	 */
	void enterImportDecl(CSSParser.ImportDeclContext ctx);
	/**
	 * Exit a parse tree produced by {@link CSSParser#importDecl}.
	 * @param ctx the parse tree
	 */
	void exitImportDecl(CSSParser.ImportDeclContext ctx);
	/**
	 * Enter a parse tree produced by {@link CSSParser#namespace}.
	 * @param ctx the parse tree
	 */
	void enterNamespace(CSSParser.NamespaceContext ctx);
	/**
	 * Exit a parse tree produced by {@link CSSParser#namespace}.
	 * @param ctx the parse tree
	 */
	void exitNamespace(CSSParser.NamespaceContext ctx);
	/**
	 * Enter a parse tree produced by the {@code ruleDecl}
	 * labeled alternative in {@link CSSParser#statement}.
	 * @param ctx the parse tree
	 */
	void enterRuleDecl(CSSParser.RuleDeclContext ctx);
	/**
	 * Exit a parse tree produced by the {@code ruleDecl}
	 * labeled alternative in {@link CSSParser#statement}.
	 * @param ctx the parse tree
	 */
	void exitRuleDecl(CSSParser.RuleDeclContext ctx);
	/**
	 * Enter a parse tree produced by the {@code mediaDecl}
	 * labeled alternative in {@link CSSParser#statement}.
	 * @param ctx the parse tree
	 */
	void enterMediaDecl(CSSParser.MediaDeclContext ctx);
	/**
	 * Exit a parse tree produced by the {@code mediaDecl}
	 * labeled alternative in {@link CSSParser#statement}.
	 * @param ctx the parse tree
	 */
	void exitMediaDecl(CSSParser.MediaDeclContext ctx);
	/**
	 * Enter a parse tree produced by the {@code pageDecl}
	 * labeled alternative in {@link CSSParser#statement}.
	 * @param ctx the parse tree
	 */
	void enterPageDecl(CSSParser.PageDeclContext ctx);
	/**
	 * Exit a parse tree produced by the {@code pageDecl}
	 * labeled alternative in {@link CSSParser#statement}.
	 * @param ctx the parse tree
	 */
	void exitPageDecl(CSSParser.PageDeclContext ctx);
	/**
	 * Enter a parse tree produced by the {@code fontFaceDecl}
	 * labeled alternative in {@link CSSParser#statement}.
	 * @param ctx the parse tree
	 */
	void enterFontFaceDecl(CSSParser.FontFaceDeclContext ctx);
	/**
	 * Exit a parse tree produced by the {@code fontFaceDecl}
	 * labeled alternative in {@link CSSParser#statement}.
	 * @param ctx the parse tree
	 */
	void exitFontFaceDecl(CSSParser.FontFaceDeclContext ctx);
	/**
	 * Enter a parse tree produced by {@link CSSParser#fontFace}.
	 * @param ctx the parse tree
	 */
	void enterFontFace(CSSParser.FontFaceContext ctx);
	/**
	 * Exit a parse tree produced by {@link CSSParser#fontFace}.
	 * @param ctx the parse tree
	 */
	void exitFontFace(CSSParser.FontFaceContext ctx);
	/**
	 * Enter a parse tree produced by {@link CSSParser#media}.
	 * @param ctx the parse tree
	 */
	void enterMedia(CSSParser.MediaContext ctx);
	/**
	 * Exit a parse tree produced by {@link CSSParser#media}.
	 * @param ctx the parse tree
	 */
	void exitMedia(CSSParser.MediaContext ctx);
	/**
	 * Enter a parse tree produced by {@link CSSParser#mediaQueryList}.
	 * @param ctx the parse tree
	 */
	void enterMediaQueryList(CSSParser.MediaQueryListContext ctx);
	/**
	 * Exit a parse tree produced by {@link CSSParser#mediaQueryList}.
	 * @param ctx the parse tree
	 */
	void exitMediaQueryList(CSSParser.MediaQueryListContext ctx);
	/**
	 * Enter a parse tree produced by {@link CSSParser#mediaQuery}.
	 * @param ctx the parse tree
	 */
	void enterMediaQuery(CSSParser.MediaQueryContext ctx);
	/**
	 * Exit a parse tree produced by {@link CSSParser#mediaQuery}.
	 * @param ctx the parse tree
	 */
	void exitMediaQuery(CSSParser.MediaQueryContext ctx);
	/**
	 * Enter a parse tree produced by {@link CSSParser#mediaExpression}.
	 * @param ctx the parse tree
	 */
	void enterMediaExpression(CSSParser.MediaExpressionContext ctx);
	/**
	 * Exit a parse tree produced by {@link CSSParser#mediaExpression}.
	 * @param ctx the parse tree
	 */
	void exitMediaExpression(CSSParser.MediaExpressionContext ctx);
	/**
	 * Enter a parse tree produced by {@link CSSParser#page}.
	 * @param ctx the parse tree
	 */
	void enterPage(CSSParser.PageContext ctx);
	/**
	 * Exit a parse tree produced by {@link CSSParser#page}.
	 * @param ctx the parse tree
	 */
	void exitPage(CSSParser.PageContext ctx);
	/**
	 * Enter a parse tree produced by {@link CSSParser#pseudoPage}.
	 * @param ctx the parse tree
	 */
	void enterPseudoPage(CSSParser.PseudoPageContext ctx);
	/**
	 * Exit a parse tree produced by {@link CSSParser#pseudoPage}.
	 * @param ctx the parse tree
	 */
	void exitPseudoPage(CSSParser.PseudoPageContext ctx);
	/**
	 * Enter a parse tree produced by {@link CSSParser#ruleSet}.
	 * @param ctx the parse tree
	 */
	void enterRuleSet(CSSParser.RuleSetContext ctx);
	/**
	 * Exit a parse tree produced by {@link CSSParser#ruleSet}.
	 * @param ctx the parse tree
	 */
	void exitRuleSet(CSSParser.RuleSetContext ctx);
	/**
	 * Enter a parse tree produced by {@link CSSParser#selectorGroup}.
	 * @param ctx the parse tree
	 */
	void enterSelectorGroup(CSSParser.SelectorGroupContext ctx);
	/**
	 * Exit a parse tree produced by {@link CSSParser#selectorGroup}.
	 * @param ctx the parse tree
	 */
	void exitSelectorGroup(CSSParser.SelectorGroupContext ctx);
	/**
	 * Enter a parse tree produced by {@link CSSParser#selector}.
	 * @param ctx the parse tree
	 */
	void enterSelector(CSSParser.SelectorContext ctx);
	/**
	 * Exit a parse tree produced by {@link CSSParser#selector}.
	 * @param ctx the parse tree
	 */
	void exitSelector(CSSParser.SelectorContext ctx);
	/**
	 * Enter a parse tree produced by {@link CSSParser#combinator}.
	 * @param ctx the parse tree
	 */
	void enterCombinator(CSSParser.CombinatorContext ctx);
	/**
	 * Exit a parse tree produced by {@link CSSParser#combinator}.
	 * @param ctx the parse tree
	 */
	void exitCombinator(CSSParser.CombinatorContext ctx);
	/**
	 * Enter a parse tree produced by the {@code universalNamepaceUniversalSelector}
	 * labeled alternative in {@link CSSParser#selectorType}.
	 * @param ctx the parse tree
	 */
	void enterUniversalNamepaceUniversalSelector(CSSParser.UniversalNamepaceUniversalSelectorContext ctx);
	/**
	 * Exit a parse tree produced by the {@code universalNamepaceUniversalSelector}
	 * labeled alternative in {@link CSSParser#selectorType}.
	 * @param ctx the parse tree
	 */
	void exitUniversalNamepaceUniversalSelector(CSSParser.UniversalNamepaceUniversalSelectorContext ctx);
	/**
	 * Enter a parse tree produced by the {@code identNamespaceUniversalSelector}
	 * labeled alternative in {@link CSSParser#selectorType}.
	 * @param ctx the parse tree
	 */
	void enterIdentNamespaceUniversalSelector(CSSParser.IdentNamespaceUniversalSelectorContext ctx);
	/**
	 * Exit a parse tree produced by the {@code identNamespaceUniversalSelector}
	 * labeled alternative in {@link CSSParser#selectorType}.
	 * @param ctx the parse tree
	 */
	void exitIdentNamespaceUniversalSelector(CSSParser.IdentNamespaceUniversalSelectorContext ctx);
	/**
	 * Enter a parse tree produced by the {@code nonamespaceUniversalSelector}
	 * labeled alternative in {@link CSSParser#selectorType}.
	 * @param ctx the parse tree
	 */
	void enterNonamespaceUniversalSelector(CSSParser.NonamespaceUniversalSelectorContext ctx);
	/**
	 * Exit a parse tree produced by the {@code nonamespaceUniversalSelector}
	 * labeled alternative in {@link CSSParser#selectorType}.
	 * @param ctx the parse tree
	 */
	void exitNonamespaceUniversalSelector(CSSParser.NonamespaceUniversalSelectorContext ctx);
	/**
	 * Enter a parse tree produced by the {@code univesalNamespaceTypeSelector}
	 * labeled alternative in {@link CSSParser#selectorType}.
	 * @param ctx the parse tree
	 */
	void enterUnivesalNamespaceTypeSelector(CSSParser.UnivesalNamespaceTypeSelectorContext ctx);
	/**
	 * Exit a parse tree produced by the {@code univesalNamespaceTypeSelector}
	 * labeled alternative in {@link CSSParser#selectorType}.
	 * @param ctx the parse tree
	 */
	void exitUnivesalNamespaceTypeSelector(CSSParser.UnivesalNamespaceTypeSelectorContext ctx);
	/**
	 * Enter a parse tree produced by the {@code identNamespaceTypeSelector}
	 * labeled alternative in {@link CSSParser#selectorType}.
	 * @param ctx the parse tree
	 */
	void enterIdentNamespaceTypeSelector(CSSParser.IdentNamespaceTypeSelectorContext ctx);
	/**
	 * Exit a parse tree produced by the {@code identNamespaceTypeSelector}
	 * labeled alternative in {@link CSSParser#selectorType}.
	 * @param ctx the parse tree
	 */
	void exitIdentNamespaceTypeSelector(CSSParser.IdentNamespaceTypeSelectorContext ctx);
	/**
	 * Enter a parse tree produced by the {@code nonamespaceTypeSelector}
	 * labeled alternative in {@link CSSParser#selectorType}.
	 * @param ctx the parse tree
	 */
	void enterNonamespaceTypeSelector(CSSParser.NonamespaceTypeSelectorContext ctx);
	/**
	 * Exit a parse tree produced by the {@code nonamespaceTypeSelector}
	 * labeled alternative in {@link CSSParser#selectorType}.
	 * @param ctx the parse tree
	 */
	void exitNonamespaceTypeSelector(CSSParser.NonamespaceTypeSelectorContext ctx);
	/**
	 * Enter a parse tree produced by the {@code universalSelector}
	 * labeled alternative in {@link CSSParser#selectorType}.
	 * @param ctx the parse tree
	 */
	void enterUniversalSelector(CSSParser.UniversalSelectorContext ctx);
	/**
	 * Exit a parse tree produced by the {@code universalSelector}
	 * labeled alternative in {@link CSSParser#selectorType}.
	 * @param ctx the parse tree
	 */
	void exitUniversalSelector(CSSParser.UniversalSelectorContext ctx);
	/**
	 * Enter a parse tree produced by the {@code typeSelector}
	 * labeled alternative in {@link CSSParser#selectorType}.
	 * @param ctx the parse tree
	 */
	void enterTypeSelector(CSSParser.TypeSelectorContext ctx);
	/**
	 * Exit a parse tree produced by the {@code typeSelector}
	 * labeled alternative in {@link CSSParser#selectorType}.
	 * @param ctx the parse tree
	 */
	void exitTypeSelector(CSSParser.TypeSelectorContext ctx);
	/**
	 * Enter a parse tree produced by the {@code idSelector}
	 * labeled alternative in {@link CSSParser#selectorType}.
	 * @param ctx the parse tree
	 */
	void enterIdSelector(CSSParser.IdSelectorContext ctx);
	/**
	 * Exit a parse tree produced by the {@code idSelector}
	 * labeled alternative in {@link CSSParser#selectorType}.
	 * @param ctx the parse tree
	 */
	void exitIdSelector(CSSParser.IdSelectorContext ctx);
	/**
	 * Enter a parse tree produced by the {@code classSelector}
	 * labeled alternative in {@link CSSParser#selectorType}.
	 * @param ctx the parse tree
	 */
	void enterClassSelector(CSSParser.ClassSelectorContext ctx);
	/**
	 * Exit a parse tree produced by the {@code classSelector}
	 * labeled alternative in {@link CSSParser#selectorType}.
	 * @param ctx the parse tree
	 */
	void exitClassSelector(CSSParser.ClassSelectorContext ctx);
	/**
	 * Enter a parse tree produced by the {@code attributeSelector}
	 * labeled alternative in {@link CSSParser#selectorType}.
	 * @param ctx the parse tree
	 */
	void enterAttributeSelector(CSSParser.AttributeSelectorContext ctx);
	/**
	 * Exit a parse tree produced by the {@code attributeSelector}
	 * labeled alternative in {@link CSSParser#selectorType}.
	 * @param ctx the parse tree
	 */
	void exitAttributeSelector(CSSParser.AttributeSelectorContext ctx);
	/**
	 * Enter a parse tree produced by the {@code pseudoSelector}
	 * labeled alternative in {@link CSSParser#selectorType}.
	 * @param ctx the parse tree
	 */
	void enterPseudoSelector(CSSParser.PseudoSelectorContext ctx);
	/**
	 * Exit a parse tree produced by the {@code pseudoSelector}
	 * labeled alternative in {@link CSSParser#selectorType}.
	 * @param ctx the parse tree
	 */
	void exitPseudoSelector(CSSParser.PseudoSelectorContext ctx);
	/**
	 * Enter a parse tree produced by the {@code notSelector}
	 * labeled alternative in {@link CSSParser#selectorType}.
	 * @param ctx the parse tree
	 */
	void enterNotSelector(CSSParser.NotSelectorContext ctx);
	/**
	 * Exit a parse tree produced by the {@code notSelector}
	 * labeled alternative in {@link CSSParser#selectorType}.
	 * @param ctx the parse tree
	 */
	void exitNotSelector(CSSParser.NotSelectorContext ctx);
	/**
	 * Enter a parse tree produced by {@link CSSParser#attribute}.
	 * @param ctx the parse tree
	 */
	void enterAttribute(CSSParser.AttributeContext ctx);
	/**
	 * Exit a parse tree produced by {@link CSSParser#attribute}.
	 * @param ctx the parse tree
	 */
	void exitAttribute(CSSParser.AttributeContext ctx);
	/**
	 * Enter a parse tree produced by {@link CSSParser#pseudo}.
	 * @param ctx the parse tree
	 */
	void enterPseudo(CSSParser.PseudoContext ctx);
	/**
	 * Exit a parse tree produced by {@link CSSParser#pseudo}.
	 * @param ctx the parse tree
	 */
	void exitPseudo(CSSParser.PseudoContext ctx);
	/**
	 * Enter a parse tree produced by {@link CSSParser#functionalPseudo}.
	 * @param ctx the parse tree
	 */
	void enterFunctionalPseudo(CSSParser.FunctionalPseudoContext ctx);
	/**
	 * Exit a parse tree produced by {@link CSSParser#functionalPseudo}.
	 * @param ctx the parse tree
	 */
	void exitFunctionalPseudo(CSSParser.FunctionalPseudoContext ctx);
	/**
	 * Enter a parse tree produced by {@link CSSParser#negation}.
	 * @param ctx the parse tree
	 */
	void enterNegation(CSSParser.NegationContext ctx);
	/**
	 * Exit a parse tree produced by {@link CSSParser#negation}.
	 * @param ctx the parse tree
	 */
	void exitNegation(CSSParser.NegationContext ctx);
	/**
	 * Enter a parse tree produced by {@link CSSParser#block}.
	 * @param ctx the parse tree
	 */
	void enterBlock(CSSParser.BlockContext ctx);
	/**
	 * Exit a parse tree produced by {@link CSSParser#block}.
	 * @param ctx the parse tree
	 */
	void exitBlock(CSSParser.BlockContext ctx);
	/**
	 * Enter a parse tree produced by {@link CSSParser#declaration}.
	 * @param ctx the parse tree
	 */
	void enterDeclaration(CSSParser.DeclarationContext ctx);
	/**
	 * Exit a parse tree produced by {@link CSSParser#declaration}.
	 * @param ctx the parse tree
	 */
	void exitDeclaration(CSSParser.DeclarationContext ctx);
	/**
	 * Enter a parse tree produced by {@link CSSParser#priority}.
	 * @param ctx the parse tree
	 */
	void enterPriority(CSSParser.PriorityContext ctx);
	/**
	 * Exit a parse tree produced by {@link CSSParser#priority}.
	 * @param ctx the parse tree
	 */
	void exitPriority(CSSParser.PriorityContext ctx);
	/**
	 * Enter a parse tree produced by {@link CSSParser#expression}.
	 * @param ctx the parse tree
	 */
	void enterExpression(CSSParser.ExpressionContext ctx);
	/**
	 * Exit a parse tree produced by {@link CSSParser#expression}.
	 * @param ctx the parse tree
	 */
	void exitExpression(CSSParser.ExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code numberExpr}
	 * labeled alternative in {@link CSSParser#term}.
	 * @param ctx the parse tree
	 */
	void enterNumberExpr(CSSParser.NumberExprContext ctx);
	/**
	 * Exit a parse tree produced by the {@code numberExpr}
	 * labeled alternative in {@link CSSParser#term}.
	 * @param ctx the parse tree
	 */
	void exitNumberExpr(CSSParser.NumberExprContext ctx);
	/**
	 * Enter a parse tree produced by the {@code stringExpr}
	 * labeled alternative in {@link CSSParser#term}.
	 * @param ctx the parse tree
	 */
	void enterStringExpr(CSSParser.StringExprContext ctx);
	/**
	 * Exit a parse tree produced by the {@code stringExpr}
	 * labeled alternative in {@link CSSParser#term}.
	 * @param ctx the parse tree
	 */
	void exitStringExpr(CSSParser.StringExprContext ctx);
	/**
	 * Enter a parse tree produced by the {@code idExpr}
	 * labeled alternative in {@link CSSParser#term}.
	 * @param ctx the parse tree
	 */
	void enterIdExpr(CSSParser.IdExprContext ctx);
	/**
	 * Exit a parse tree produced by the {@code idExpr}
	 * labeled alternative in {@link CSSParser#term}.
	 * @param ctx the parse tree
	 */
	void exitIdExpr(CSSParser.IdExprContext ctx);
	/**
	 * Enter a parse tree produced by the {@code urlExpr}
	 * labeled alternative in {@link CSSParser#term}.
	 * @param ctx the parse tree
	 */
	void enterUrlExpr(CSSParser.UrlExprContext ctx);
	/**
	 * Exit a parse tree produced by the {@code urlExpr}
	 * labeled alternative in {@link CSSParser#term}.
	 * @param ctx the parse tree
	 */
	void exitUrlExpr(CSSParser.UrlExprContext ctx);
	/**
	 * Enter a parse tree produced by the {@code hexColorExpr}
	 * labeled alternative in {@link CSSParser#term}.
	 * @param ctx the parse tree
	 */
	void enterHexColorExpr(CSSParser.HexColorExprContext ctx);
	/**
	 * Exit a parse tree produced by the {@code hexColorExpr}
	 * labeled alternative in {@link CSSParser#term}.
	 * @param ctx the parse tree
	 */
	void exitHexColorExpr(CSSParser.HexColorExprContext ctx);
	/**
	 * Enter a parse tree produced by the {@code calcExpr}
	 * labeled alternative in {@link CSSParser#term}.
	 * @param ctx the parse tree
	 */
	void enterCalcExpr(CSSParser.CalcExprContext ctx);
	/**
	 * Exit a parse tree produced by the {@code calcExpr}
	 * labeled alternative in {@link CSSParser#term}.
	 * @param ctx the parse tree
	 */
	void exitCalcExpr(CSSParser.CalcExprContext ctx);
	/**
	 * Enter a parse tree produced by the {@code functionExpr}
	 * labeled alternative in {@link CSSParser#term}.
	 * @param ctx the parse tree
	 */
	void enterFunctionExpr(CSSParser.FunctionExprContext ctx);
	/**
	 * Exit a parse tree produced by the {@code functionExpr}
	 * labeled alternative in {@link CSSParser#term}.
	 * @param ctx the parse tree
	 */
	void exitFunctionExpr(CSSParser.FunctionExprContext ctx);
	/**
	 * Enter a parse tree produced by {@link CSSParser#calc}.
	 * @param ctx the parse tree
	 */
	void enterCalc(CSSParser.CalcContext ctx);
	/**
	 * Exit a parse tree produced by {@link CSSParser#calc}.
	 * @param ctx the parse tree
	 */
	void exitCalc(CSSParser.CalcContext ctx);
	/**
	 * Enter a parse tree produced by {@link CSSParser#sum}.
	 * @param ctx the parse tree
	 */
	void enterSum(CSSParser.SumContext ctx);
	/**
	 * Exit a parse tree produced by {@link CSSParser#sum}.
	 * @param ctx the parse tree
	 */
	void exitSum(CSSParser.SumContext ctx);
	/**
	 * Enter a parse tree produced by {@link CSSParser#product}.
	 * @param ctx the parse tree
	 */
	void enterProduct(CSSParser.ProductContext ctx);
	/**
	 * Exit a parse tree produced by {@link CSSParser#product}.
	 * @param ctx the parse tree
	 */
	void exitProduct(CSSParser.ProductContext ctx);
	/**
	 * Enter a parse tree produced by {@link CSSParser#attributeReference}.
	 * @param ctx the parse tree
	 */
	void enterAttributeReference(CSSParser.AttributeReferenceContext ctx);
	/**
	 * Exit a parse tree produced by {@link CSSParser#attributeReference}.
	 * @param ctx the parse tree
	 */
	void exitAttributeReference(CSSParser.AttributeReferenceContext ctx);
	/**
	 * Enter a parse tree produced by the {@code calcNumberDecl}
	 * labeled alternative in {@link CSSParser#unit}.
	 * @param ctx the parse tree
	 */
	void enterCalcNumberDecl(CSSParser.CalcNumberDeclContext ctx);
	/**
	 * Exit a parse tree produced by the {@code calcNumberDecl}
	 * labeled alternative in {@link CSSParser#unit}.
	 * @param ctx the parse tree
	 */
	void exitCalcNumberDecl(CSSParser.CalcNumberDeclContext ctx);
	/**
	 * Enter a parse tree produced by the {@code calcSumDecl}
	 * labeled alternative in {@link CSSParser#unit}.
	 * @param ctx the parse tree
	 */
	void enterCalcSumDecl(CSSParser.CalcSumDeclContext ctx);
	/**
	 * Exit a parse tree produced by the {@code calcSumDecl}
	 * labeled alternative in {@link CSSParser#unit}.
	 * @param ctx the parse tree
	 */
	void exitCalcSumDecl(CSSParser.CalcSumDeclContext ctx);
	/**
	 * Enter a parse tree produced by the {@code calcDecl}
	 * labeled alternative in {@link CSSParser#unit}.
	 * @param ctx the parse tree
	 */
	void enterCalcDecl(CSSParser.CalcDeclContext ctx);
	/**
	 * Exit a parse tree produced by the {@code calcDecl}
	 * labeled alternative in {@link CSSParser#unit}.
	 * @param ctx the parse tree
	 */
	void exitCalcDecl(CSSParser.CalcDeclContext ctx);
	/**
	 * Enter a parse tree produced by the {@code attributeReferenceDecl}
	 * labeled alternative in {@link CSSParser#unit}.
	 * @param ctx the parse tree
	 */
	void enterAttributeReferenceDecl(CSSParser.AttributeReferenceDeclContext ctx);
	/**
	 * Exit a parse tree produced by the {@code attributeReferenceDecl}
	 * labeled alternative in {@link CSSParser#unit}.
	 * @param ctx the parse tree
	 */
	void exitAttributeReferenceDecl(CSSParser.AttributeReferenceDeclContext ctx);
	/**
	 * Enter a parse tree produced by {@link CSSParser#function}.
	 * @param ctx the parse tree
	 */
	void enterFunction(CSSParser.FunctionContext ctx);
	/**
	 * Exit a parse tree produced by {@link CSSParser#function}.
	 * @param ctx the parse tree
	 */
	void exitFunction(CSSParser.FunctionContext ctx);
	/**
	 * Enter a parse tree produced by {@link CSSParser#number}.
	 * @param ctx the parse tree
	 */
	void enterNumber(CSSParser.NumberContext ctx);
	/**
	 * Exit a parse tree produced by {@link CSSParser#number}.
	 * @param ctx the parse tree
	 */
	void exitNumber(CSSParser.NumberContext ctx);
}