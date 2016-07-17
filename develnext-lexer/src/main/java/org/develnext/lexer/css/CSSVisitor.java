// Generated from org\develnext\lexer\css\CSS.g4 by ANTLR 4.5.3
package org.develnext.lexer.css;
import org.antlr.v4.runtime.tree.ParseTreeVisitor;

/**
 * This interface defines a complete generic visitor for a parse tree produced
 * by {@link CSSParser}.
 *
 * @param <T> The return type of the visit operation. Use {@link Void} for
 * operations with no return type.
 */
public interface CSSVisitor<T> extends ParseTreeVisitor<T> {
	/**
	 * Visit a parse tree produced by {@link CSSParser#styleSheet}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitStyleSheet(CSSParser.StyleSheetContext ctx);
	/**
	 * Visit a parse tree produced by {@link CSSParser#charSet}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitCharSet(CSSParser.CharSetContext ctx);
	/**
	 * Visit a parse tree produced by {@link CSSParser#importDecl}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitImportDecl(CSSParser.ImportDeclContext ctx);
	/**
	 * Visit a parse tree produced by {@link CSSParser#namespace}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitNamespace(CSSParser.NamespaceContext ctx);
	/**
	 * Visit a parse tree produced by the {@code ruleDecl}
	 * labeled alternative in {@link CSSParser#statement}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitRuleDecl(CSSParser.RuleDeclContext ctx);
	/**
	 * Visit a parse tree produced by the {@code mediaDecl}
	 * labeled alternative in {@link CSSParser#statement}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitMediaDecl(CSSParser.MediaDeclContext ctx);
	/**
	 * Visit a parse tree produced by the {@code pageDecl}
	 * labeled alternative in {@link CSSParser#statement}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitPageDecl(CSSParser.PageDeclContext ctx);
	/**
	 * Visit a parse tree produced by the {@code fontFaceDecl}
	 * labeled alternative in {@link CSSParser#statement}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitFontFaceDecl(CSSParser.FontFaceDeclContext ctx);
	/**
	 * Visit a parse tree produced by {@link CSSParser#fontFace}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitFontFace(CSSParser.FontFaceContext ctx);
	/**
	 * Visit a parse tree produced by {@link CSSParser#media}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitMedia(CSSParser.MediaContext ctx);
	/**
	 * Visit a parse tree produced by {@link CSSParser#mediaQueryList}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitMediaQueryList(CSSParser.MediaQueryListContext ctx);
	/**
	 * Visit a parse tree produced by {@link CSSParser#mediaQuery}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitMediaQuery(CSSParser.MediaQueryContext ctx);
	/**
	 * Visit a parse tree produced by {@link CSSParser#mediaExpression}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitMediaExpression(CSSParser.MediaExpressionContext ctx);
	/**
	 * Visit a parse tree produced by {@link CSSParser#page}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitPage(CSSParser.PageContext ctx);
	/**
	 * Visit a parse tree produced by {@link CSSParser#pseudoPage}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitPseudoPage(CSSParser.PseudoPageContext ctx);
	/**
	 * Visit a parse tree produced by {@link CSSParser#ruleSet}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitRuleSet(CSSParser.RuleSetContext ctx);
	/**
	 * Visit a parse tree produced by {@link CSSParser#selectorGroup}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitSelectorGroup(CSSParser.SelectorGroupContext ctx);
	/**
	 * Visit a parse tree produced by {@link CSSParser#selector}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitSelector(CSSParser.SelectorContext ctx);
	/**
	 * Visit a parse tree produced by {@link CSSParser#combinator}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitCombinator(CSSParser.CombinatorContext ctx);
	/**
	 * Visit a parse tree produced by the {@code universalNamepaceUniversalSelector}
	 * labeled alternative in {@link CSSParser#selectorType}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitUniversalNamepaceUniversalSelector(CSSParser.UniversalNamepaceUniversalSelectorContext ctx);
	/**
	 * Visit a parse tree produced by the {@code identNamespaceUniversalSelector}
	 * labeled alternative in {@link CSSParser#selectorType}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitIdentNamespaceUniversalSelector(CSSParser.IdentNamespaceUniversalSelectorContext ctx);
	/**
	 * Visit a parse tree produced by the {@code nonamespaceUniversalSelector}
	 * labeled alternative in {@link CSSParser#selectorType}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitNonamespaceUniversalSelector(CSSParser.NonamespaceUniversalSelectorContext ctx);
	/**
	 * Visit a parse tree produced by the {@code univesalNamespaceTypeSelector}
	 * labeled alternative in {@link CSSParser#selectorType}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitUnivesalNamespaceTypeSelector(CSSParser.UnivesalNamespaceTypeSelectorContext ctx);
	/**
	 * Visit a parse tree produced by the {@code identNamespaceTypeSelector}
	 * labeled alternative in {@link CSSParser#selectorType}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitIdentNamespaceTypeSelector(CSSParser.IdentNamespaceTypeSelectorContext ctx);
	/**
	 * Visit a parse tree produced by the {@code nonamespaceTypeSelector}
	 * labeled alternative in {@link CSSParser#selectorType}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitNonamespaceTypeSelector(CSSParser.NonamespaceTypeSelectorContext ctx);
	/**
	 * Visit a parse tree produced by the {@code universalSelector}
	 * labeled alternative in {@link CSSParser#selectorType}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitUniversalSelector(CSSParser.UniversalSelectorContext ctx);
	/**
	 * Visit a parse tree produced by the {@code typeSelector}
	 * labeled alternative in {@link CSSParser#selectorType}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitTypeSelector(CSSParser.TypeSelectorContext ctx);
	/**
	 * Visit a parse tree produced by the {@code idSelector}
	 * labeled alternative in {@link CSSParser#selectorType}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitIdSelector(CSSParser.IdSelectorContext ctx);
	/**
	 * Visit a parse tree produced by the {@code classSelector}
	 * labeled alternative in {@link CSSParser#selectorType}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitClassSelector(CSSParser.ClassSelectorContext ctx);
	/**
	 * Visit a parse tree produced by the {@code attributeSelector}
	 * labeled alternative in {@link CSSParser#selectorType}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitAttributeSelector(CSSParser.AttributeSelectorContext ctx);
	/**
	 * Visit a parse tree produced by the {@code pseudoSelector}
	 * labeled alternative in {@link CSSParser#selectorType}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitPseudoSelector(CSSParser.PseudoSelectorContext ctx);
	/**
	 * Visit a parse tree produced by the {@code notSelector}
	 * labeled alternative in {@link CSSParser#selectorType}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitNotSelector(CSSParser.NotSelectorContext ctx);
	/**
	 * Visit a parse tree produced by {@link CSSParser#attribute}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitAttribute(CSSParser.AttributeContext ctx);
	/**
	 * Visit a parse tree produced by {@link CSSParser#pseudo}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitPseudo(CSSParser.PseudoContext ctx);
	/**
	 * Visit a parse tree produced by {@link CSSParser#functionalPseudo}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitFunctionalPseudo(CSSParser.FunctionalPseudoContext ctx);
	/**
	 * Visit a parse tree produced by {@link CSSParser#negation}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitNegation(CSSParser.NegationContext ctx);
	/**
	 * Visit a parse tree produced by {@link CSSParser#block}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitBlock(CSSParser.BlockContext ctx);
	/**
	 * Visit a parse tree produced by {@link CSSParser#declaration}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitDeclaration(CSSParser.DeclarationContext ctx);
	/**
	 * Visit a parse tree produced by {@link CSSParser#priority}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitPriority(CSSParser.PriorityContext ctx);
	/**
	 * Visit a parse tree produced by {@link CSSParser#expression}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitExpression(CSSParser.ExpressionContext ctx);
	/**
	 * Visit a parse tree produced by the {@code numberExpr}
	 * labeled alternative in {@link CSSParser#term}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitNumberExpr(CSSParser.NumberExprContext ctx);
	/**
	 * Visit a parse tree produced by the {@code stringExpr}
	 * labeled alternative in {@link CSSParser#term}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitStringExpr(CSSParser.StringExprContext ctx);
	/**
	 * Visit a parse tree produced by the {@code idExpr}
	 * labeled alternative in {@link CSSParser#term}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitIdExpr(CSSParser.IdExprContext ctx);
	/**
	 * Visit a parse tree produced by the {@code urlExpr}
	 * labeled alternative in {@link CSSParser#term}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitUrlExpr(CSSParser.UrlExprContext ctx);
	/**
	 * Visit a parse tree produced by the {@code hexColorExpr}
	 * labeled alternative in {@link CSSParser#term}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitHexColorExpr(CSSParser.HexColorExprContext ctx);
	/**
	 * Visit a parse tree produced by the {@code calcExpr}
	 * labeled alternative in {@link CSSParser#term}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitCalcExpr(CSSParser.CalcExprContext ctx);
	/**
	 * Visit a parse tree produced by the {@code functionExpr}
	 * labeled alternative in {@link CSSParser#term}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitFunctionExpr(CSSParser.FunctionExprContext ctx);
	/**
	 * Visit a parse tree produced by {@link CSSParser#calc}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitCalc(CSSParser.CalcContext ctx);
	/**
	 * Visit a parse tree produced by {@link CSSParser#sum}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitSum(CSSParser.SumContext ctx);
	/**
	 * Visit a parse tree produced by {@link CSSParser#product}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitProduct(CSSParser.ProductContext ctx);
	/**
	 * Visit a parse tree produced by {@link CSSParser#attributeReference}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitAttributeReference(CSSParser.AttributeReferenceContext ctx);
	/**
	 * Visit a parse tree produced by the {@code calcNumberDecl}
	 * labeled alternative in {@link CSSParser#unit}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitCalcNumberDecl(CSSParser.CalcNumberDeclContext ctx);
	/**
	 * Visit a parse tree produced by the {@code calcSumDecl}
	 * labeled alternative in {@link CSSParser#unit}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitCalcSumDecl(CSSParser.CalcSumDeclContext ctx);
	/**
	 * Visit a parse tree produced by the {@code calcDecl}
	 * labeled alternative in {@link CSSParser#unit}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitCalcDecl(CSSParser.CalcDeclContext ctx);
	/**
	 * Visit a parse tree produced by the {@code attributeReferenceDecl}
	 * labeled alternative in {@link CSSParser#unit}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitAttributeReferenceDecl(CSSParser.AttributeReferenceDeclContext ctx);
	/**
	 * Visit a parse tree produced by {@link CSSParser#function}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitFunction(CSSParser.FunctionContext ctx);
	/**
	 * Visit a parse tree produced by {@link CSSParser#number}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitNumber(CSSParser.NumberContext ctx);
}