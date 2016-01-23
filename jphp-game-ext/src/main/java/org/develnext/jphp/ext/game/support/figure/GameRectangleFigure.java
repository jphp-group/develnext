package org.develnext.jphp.ext.game.support.figure;

public class GameRectangleFigure extends GameFigure {
    protected final double width;
    protected final double height;

    public GameRectangleFigure(double width, double height) {
        this.width = width;
        this.height = height;

        this.type = Type.RECT;
    }

    @Override
    public double getWidth() {
        return width;
    }

    @Override
    public double getHeight() {
        return height;
    }

    @Override
    public boolean testCollision(double x1, double y1, double rotation1, double x2, double y2, double rotation2, GameFigure figure) {
        switch (type) {
            case CIRCLE:
                return figure.testCollision(x2, y2, rotation2, x1, y1, rotation1, this);

            case RECT:
            case UNKNOWN:
                double w1 = getWidth();
                double h1 = getHeight();
                double w2 = figure.getWidth();
                double h2 = figure.getHeight();

                return x1 + w1 / 2 >= x2 - w2 / 2
                        && x1 - w1 / 2 <= x2 + w2 / 2
                        && y1 + h1 / 2 >= y2 - h2 / 2
                        && y1 - h1 / 2 <= y2 + h2 / 2;
        }

        return false;
    }
}
