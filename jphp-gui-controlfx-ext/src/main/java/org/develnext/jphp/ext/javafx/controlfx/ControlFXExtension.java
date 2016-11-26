package org.develnext.jphp.ext.javafx.controlfx;

import org.controlsfx.control.PlusMinusSlider;
import org.controlsfx.control.Rating;
import org.controlsfx.control.ToggleSwitch;
import org.develnext.jphp.ext.javafx.controlfx.classes.UXPlusMinusSlider;
import org.develnext.jphp.ext.javafx.controlfx.classes.UXRating;
import org.develnext.jphp.ext.javafx.controlfx.classes.UXToggleSwitch;
import php.runtime.env.CompileScope;
import php.runtime.ext.support.Extension;

public class ControlFXExtension extends Extension {
    public final static String NS = "php\\gui";

    @Override
    public Status getStatus() {
        return Status.EXPERIMENTAL;
    }

    @Override
    public void onRegister(CompileScope scope) {
        registerWrapperClass(scope, Rating.class, UXRating.class);
        registerWrapperClass(scope, ToggleSwitch.class, UXToggleSwitch.class);
        registerWrapperClass(scope, PlusMinusSlider.class, UXPlusMinusSlider.class);
    }
}
