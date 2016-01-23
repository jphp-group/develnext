package org.develnext.jphp.ext.game.support.figure;

public class GameCircleFigure extends GameFigure {
    protected double radius;

    public GameCircleFigure(double radius) {
        this.radius = radius;

        this.type = Type.CIRCLE;
    }

    public double getRadius() {
        return radius;
    }

    @Override
    public double getWidth() {
        return radius * 2;
    }

    @Override
    public double getHeight() {
        return radius * 2;
    }

    @Override
    public boolean testCollision(double x1, double y1, double rotation1, double x2, double y2, double rotation2, GameFigure figure) {
        switch (figure.type) {
            case CIRCLE:
                x1 += getCenterX();
                x2 += figure.getCenterX();

                y1 += getCenterY();
                y2 += figure.getCenterY();

                double dx = x1 - x2;
                double dy = y1 - y2;

                double distance = Math.sqrt(dx * dx + dy * dy);

                return distance < radius + figure.getCenterX(); // figure.getCenterX() is radius

            case RECT:
            case UNKNOWN:
                return testRectangleToCircle(
                        figure.getWidth(), figure.getHeight(), rotation2, figure.getCenterX(), figure.getCenterY(),
                        radius, radius, radius
                );
        }

        return false;
    }

}
