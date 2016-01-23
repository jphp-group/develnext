package org.develnext.jphp.ext.game.support.figure;

import org.develnext.jphp.ext.game.support.Vec2d;

public class GamePolygonFigure extends GameFigure {
    protected Vec2d[] points;

    public GamePolygonFigure(Vec2d[] points) {
        this.points = points;
    }

    @Override
    public double getWidth() {
        return 0;
    }

    @Override
    public double getHeight() {
        return 0;
    }

    @Override
    public boolean testCollision(double x1, double y1, double rotation1, double x2, double y2, double rotation2, GameFigure figure) {

        return false;
    }

    public Vec2d[] getPoints() {
        return points;
    }
}
