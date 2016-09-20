package org.develnext.jphp.ext.javafx.support.control;

import javafx.beans.value.ChangeListener;
import javafx.beans.value.ObservableValue;
import javafx.geometry.Insets;
import javafx.scene.layout.*;
import javafx.scene.paint.Color;
import javafx.scene.paint.Paint;

public class Panel extends AnchorPane {
    protected Paint borderColor = Color.SILVER;
    protected BorderStrokeStyle borderStyle = BorderStrokeStyle.SOLID;
    protected CornerRadii borderRadius = new CornerRadii(0);
    protected BorderWidths borderWidths = new BorderWidths(1);
    protected String _borderStyle = "SOLID";

    protected LabelEx titleLabel;

    public Panel() {
        this.titleLabel = new LabelEx();
        this.titleLabel.setAutoSize(true);

        updateBorder();

        backgroundProperty().addListener(new ChangeListener<Background>() {
            @Override
            public void changed(ObservableValue<? extends Background> observable, Background oldValue, Background newValue) {
                updateBorder();
            }
        });
    }

    protected void updateBorder() {
        setBorder(new Border(new BorderStroke(borderColor, borderStyle, borderRadius, borderWidths)));

        Background background = getBackground();

        if (background != null && background.getFills().size() == 1) {
            boolean update = false;

            for (BackgroundFill backgroundFill : background.getFills()) {
                if (!backgroundFill.getRadii().equals(borderRadius)) {
                    update = true;
                    break;
                }
            }

            if (update) {
                BackgroundFill[] fills = new BackgroundFill[background.getFills().size()];

                int i = 0;

                for (BackgroundFill backgroundFill : background.getFills()) {
                    fills[i++] = new BackgroundFill(backgroundFill.getFill(), borderRadius, backgroundFill.getInsets());
                }

                setBackground(new Background(fills));
            }
        }
    }

    public void setBackgroundColor(Color color) {
        if (color == null) {
            setBackground(null);
        } else {
            setBackground(new Background(new BackgroundFill(color, CornerRadii.EMPTY, Insets.EMPTY)));
        }
    }

    public Color getBackgroundColor() {
        Background background = getBackground();

        if (background != null && background.getFills().size() > 0) {
            Paint fill = background.getFills().get(0).getFill();
            if (fill instanceof Color) {
                return (Color) fill;
            }
        }

        return null;
    }

    public Color getBorderColor() {
        return borderColor instanceof Color ? (Color) borderColor : null;
    }

    public void setBorderColor(Color borderColor) {
        this.borderColor = borderColor;
        updateBorder();
    }

    public String getBorderStyle() {
        return _borderStyle;
    }

    public void setBorderStyle(String borderStyle) {
        try {
            this.borderStyle = (BorderStrokeStyle) BorderStrokeStyle.class.getField(borderStyle.toUpperCase()).get(null);
            this._borderStyle = borderStyle;
        } catch (IllegalAccessException | NoSuchFieldException e) {
            throw new IllegalArgumentException("Invalid borderStyle - " + borderStyle);
        }
        updateBorder();
    }

    public void setBorderRadius(double radius) {
        if (radius < 0) {
            radius = 0;
        }

        this.borderRadius = new CornerRadii(radius);
        updateBorder();
    }

    public double getBorderRadius() {
        return borderRadius.getTopLeftHorizontalRadius();
    }

    public double getBorderWidth() {
        return borderWidths.getTop();
    }

    public void setBorderWidth(double value) {
        if (value < 0) {
            value = 0;
        }

        borderWidths = new BorderWidths(value);
        updateBorder();
    }
}
