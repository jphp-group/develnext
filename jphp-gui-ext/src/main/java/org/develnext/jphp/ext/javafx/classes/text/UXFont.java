package org.develnext.jphp.ext.javafx.classes.text;

import javafx.scene.text.Font;
import javafx.scene.text.FontPosture;
import javafx.scene.text.FontWeight;
import javafx.scene.text.Text;
import org.develnext.jphp.ext.javafx.JavaFXExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Getter;
import php.runtime.annotation.Reflection.Property;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.lang.BaseWrapper;
import php.runtime.reflection.ClassEntity;

import java.io.InputStream;
import java.util.List;

@Reflection.Name(JavaFXExtension.NS + "text\\UXFont")
public class UXFont extends BaseWrapper<Font> {
    interface WrappedInterface {
        @Property String name();
        @Property String family();
        @Property String size();
        @Property String style();
    }

    public UXFont(Environment env, Font wrappedObject) {
        super(env, wrappedObject);
    }

    public UXFont(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct(double size) {
        __wrappedObject = new Font(size);
    }

    @Signature
    public void __construct(double size, String name) {
        __wrappedObject = new Font(name, size);
    }

    @Signature
    public static List<String> getFamilies() {
        return Font.getFamilies();
    }

    @Signature
    public static List<String> getFontNames() {
        return Font.getFontNames();
    }

    @Signature
    public static List<String> getFontNames(String family) {
        return Font.getFontNames(family);
    }

    @Signature
    public static Font getDefault() {
        return Font.getDefault();
    }

    @Signature
    public Font withItalic() {
        return withNameAndSize(getWrappedObject().getName(), getWrappedObject().getSize(), null, true);
    }

    @Signature
    public Font withRegular() {
        return withNameAndSize(getWrappedObject().getName(), getWrappedObject().getSize(), null, false);
    }

    @Signature
    public Font withBold() {
        return withNameAndSize(getWrappedObject().getName(), getWrappedObject().getSize(), true, null);
    }

    @Signature
    public Font withThin() {
        return withNameAndSize(getWrappedObject().getName(), getWrappedObject().getSize(), false, null);
    }

    @Signature
    public Font withName(String name) {
        return withNameAndSize(name, getWrappedObject().getSize());
    }

    @Signature
    public Font withSize(int size) {
        return withNameAndSize(getWrappedObject().getName(), size);
    }

    @Signature
    public Font withNameAndSize(String name, double size) {
        return withNameAndSize(name, size, null, null);
    }

    protected Font withNameAndSize(String name, double size, Boolean bold, Boolean italic) {
        Font font = getWrappedObject();

        FontWeight weight = bold != null && bold ? FontWeight.BOLD : FontWeight.THIN;
        FontPosture posture = italic != null && italic ? FontPosture.ITALIC : FontPosture.REGULAR;

        if (font.getStyle().toUpperCase().contains("BOLD")) {
            weight = FontWeight.BOLD;
        }

        if (font.getStyle().toUpperCase().contains("ITALIC")) {
            posture = FontPosture.ITALIC;
        }

        return Font.font(name, weight, posture, size);
    }

    @Signature
    public static Font of(String family, int size) {
        return Font.font(family, size);
    }

    @Signature
    public static Font of(String family, int size, FontWeight fontWeight) {
        return Font.font(family, fontWeight, size);
    }

    @Signature
    public static Font of(String family, int size, FontWeight fontWeight, boolean italic) {
        return Font.font(family, fontWeight, italic ? FontPosture.ITALIC : FontPosture.REGULAR, size);
    }

    @Signature
    public static Font load(InputStream stream, double size) {
        return Font.loadFont(stream, size);
    }

    @Getter
    public float getLineHeight() {
        return com.sun.javafx.tk.Toolkit.getToolkit().getFontLoader().getFontMetrics(getWrappedObject()).getLineHeight();
    }

    public static double getLineHeight(Font font) {
        Text text = new Text("A");
        text.setFont(font);
        return text.getLayoutBounds().getHeight();
    }

    @Signature
    public double calculateTextWidth(String atext) {
        Text text = new Text(atext);
        text.setFont(this.getWrappedObject());
        return text.getLayoutBounds().getWidth();
    }

    public static double calculateTextWidth(String atext, Font font) {
        Text text = new Text(atext);
        text.setFont(font);
        return text.getLayoutBounds().getWidth();
    }
}
