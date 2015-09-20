package org.develnext.jphp.ext.javafx.classes.paint;

import javafx.scene.paint.Color;
import org.develnext.jphp.ext.javafx.JavaFXExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Property;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.lang.BaseWrapper;
import php.runtime.reflection.ClassEntity;

@Reflection.Name(JavaFXExtension.NS + "paint\\UXColor")
public class UXColor extends BaseWrapper<Color> {
    interface WrappedInterface {
        @Property double red();
        @Property double blue();
        @Property double green();
        @Property double opacity();
        @Property double brightness();
        @Property double hue();
        @Property double saturation();

        Color grayscale();
        Color invert();
        Color saturate();
        Color desaturate();
        Color interpolate(Color endValue, double t);
    }

    public UXColor(Environment env, Color wrappedObject) {
        super(env, wrappedObject);
    }

    public UXColor(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct(double red, double blue, double green) {
        __wrappedObject = new Color(red, blue, green, 1.0);
    }

    @Signature
    public void __construct(double red, double blue, double green, double opacity) {
        __wrappedObject = new Color(red, blue, green, opacity);
    }

    @Signature
    public String getWebValue() {
        int r = (int)Math.round(getWrappedObject().getRed() * 255.0);
        int g = (int)Math.round(getWrappedObject().getGreen() * 255.0);
        int b = (int)Math.round(getWrappedObject().getBlue() * 255.0);

        int a = (int) Math.round(getWrappedObject().getOpacity() * 255.0);

        if (a == 255) {
            return String.format("#%02x%02x%02x", r, g, b);
        } else {
            return String.format("#%02x%02x%02x%02x", r, g, b, a);
        }
    }

    @Signature
    public static Color of(String colorString) {
        try {
            return Color.web(colorString);
        } catch (IllegalArgumentException e) {
            return null;
        }
    }

    @Signature
    public static Color of(String colorString, double opacity) {
        return Color.web(colorString, opacity);
    }

    @Signature
    public static Color rgb(int red, int blue, int green) {
        return Color.rgb(red, blue, green);
    }

    @Signature
    public static Color rgb(int red, int blue, int green, double opacity) {
        return Color.rgb(red, blue, green, opacity);
    }
}
