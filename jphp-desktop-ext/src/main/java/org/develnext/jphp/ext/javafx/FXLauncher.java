package org.develnext.jphp.ext.javafx;

import javafx.application.Platform;
import javafx.embed.swing.JFXPanel;
import javafx.scene.Scene;
import javafx.scene.image.Image;
import javafx.scene.layout.VBox;
import javafx.stage.Stage;
import javafx.stage.StageStyle;
import org.develnext.jphp.ext.javafx.support.ImageViewEx;
import php.runtime.Memory;
import php.runtime.launcher.Launcher;

import javax.swing.*;
import java.io.InputStream;

public class FXLauncher extends Launcher {

    private Stage splashStage;

    public FXLauncher() {
        super();
    }

    public FXLauncher(String[] args) {
        super(args);
    }

    public FXLauncher(ClassLoader classLoader) {
        super(classLoader);
    }

    @Override
    protected void readConfig() {
        super.readConfig();

        Memory fxSplash = getConfigValue("fx.splash");

        if (fxSplash.toBoolean()) {
            InputStream fxSplashStream = getClass().getResourceAsStream(fxSplash.toString());

            if (fxSplashStream == null) {
                System.err.println("Failed to load 'fx.splash': " + fxSplash.toString());
                return;
            }

            new JFXPanel();

            Platform.runLater(() -> {
                Image image = new Image(fxSplashStream);

                ImageViewEx imageView = new ImageViewEx();
                imageView.setAutoSize(true);
                imageView.setImage(image);

                splashStage = new Stage(StageStyle.TRANSPARENT);
                VBox root = new VBox(imageView);
                root.setBackground(null);
                
                Scene scene = new Scene(root);
                scene.setFill(null);
                splashStage.setScene(scene);

                Memory opacity = getConfigValue("fx.splash.opacity");
                if (opacity.isNotNull()) {
                    splashStage.setOpacity(opacity.toDouble());
                }

                Memory alwaysOnTop = getConfigValue("fx.splash.alwaysOnTop");
                if (alwaysOnTop.isNotNull()) {
                    splashStage.setAlwaysOnTop(alwaysOnTop.toBoolean());
                }

                splashStage.centerOnScreen();
                splashStage.show();
                splashStage.centerOnScreen();
            });
        }
    }

    @Override
    public void run(boolean mustBootstrap, boolean disableExtensions) throws Throwable {
        try {
            super.run(mustBootstrap, disableExtensions);
        } catch (Throwable e) {
            e.printStackTrace();

            SwingUtilities.invokeLater(() -> {
                ExceptionDialog ld = new ExceptionDialog("Unexpected System Error!", e.getMessage(), e);
                ld.setDefaultCloseOperation(WindowConstants.DISPOSE_ON_CLOSE);

                ld.setAlwaysOnTop(true);
                ld.expand();
                ld.setLocationRelativeTo(null);
                ld.setVisible(true);
            });

            if (splashStage != null) {
                Platform.runLater(new Runnable() {
                    @Override
                    public void run() {
                        splashStage.hide();
                    }
                });
            }
        }
    }

    public Stage getSplashStage() {
        return splashStage;
    }

    public static FXLauncher current() {
        Launcher current = Launcher.current();

        if (current instanceof FXLauncher) {
            return (FXLauncher) current;
        } else {
            return null;
        }
    }

    public static void main(String[] args) throws Throwable {
        System.setProperty("org.slf4j.simpleLogger.logFile", "System.out");
        
        FXLauncher launcher = new FXLauncher(args);
        launcher.run();
    }
}
