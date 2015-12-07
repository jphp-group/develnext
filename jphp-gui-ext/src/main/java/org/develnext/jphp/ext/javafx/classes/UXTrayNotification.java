package org.develnext.jphp.ext.javafx.classes;

import javafx.scene.image.Image;
import javafx.util.Duration;
import org.develnext.jphp.ext.javafx.JavaFXExtension;
import org.develnext.jphp.ext.javafx.support.tray.animations.AnimationType;
import org.develnext.jphp.ext.javafx.support.tray.notification.NotificationLocation;
import org.develnext.jphp.ext.javafx.support.tray.notification.NotificationType;
import org.develnext.jphp.ext.javafx.support.tray.notification.TrayNotification;
import php.runtime.annotation.Reflection.Name;
import php.runtime.annotation.Reflection.Nullable;
import php.runtime.annotation.Reflection.Property;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.lang.BaseWrapper;
import php.runtime.reflection.ClassEntity;

@Name(JavaFXExtension.NS + "UXTrayNotification")
public class UXTrayNotification extends BaseWrapper<TrayNotification> {
    interface WrappedInterface {
        @Property String title();
        @Property String message();
        @Property @Nullable Image image();
        @Property @Nullable Image trayIcon();
        @Property NotificationType notificationType();
        @Property NotificationLocation location();
        @Property AnimationType animationType();

        void showAndWait();
    }

    public UXTrayNotification(Environment env, TrayNotification wrappedObject) {
        super(env, wrappedObject);
    }

    public UXTrayNotification(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct() {
        __wrappedObject = new TrayNotification();
        getWrappedObject().setAnimationType(AnimationType.FADE);
    }

    @Signature
    public void __construct(String title) {
        __construct(title, "");
    }

    @Signature
    public void __construct(String title, String message) {
        __construct(title, message, NotificationType.NOTICE);
    }

    @Signature
    public void __construct(String title, String message, NotificationType type) {
        __wrappedObject = new TrayNotification(title, message, type);
        getWrappedObject().setAnimationType(AnimationType.FADE);
    }

    @Signature
    public void show() {
        getWrappedObject().showAndDismiss(Duration.seconds(5));
    }

    @Signature
    public void hide() {
        getWrappedObject().dismiss();
    }

    @Signature
    public void show(long millis) {
        getWrappedObject().showAndDismiss(Duration.millis(millis));
    }
}
