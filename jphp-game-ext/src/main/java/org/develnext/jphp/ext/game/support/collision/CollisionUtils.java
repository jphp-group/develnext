package org.develnext.jphp.ext.game.support.collision;

import org.develnext.jphp.ext.game.support.Vec2d;
import org.develnext.jphp.ext.game.support.figure.GameFigure;
import org.develnext.jphp.ext.game.support.figure.GamePolygonFigure;

final public class CollisionUtils {
    private CollisionUtils() {
    }

    public static boolean testAsRectangles(double x1, double y1, GameFigure figure1, double x2, double y2, GameFigure figure2) {
        double w1 = figure1.getWidth();
        double h1 = figure1.getHeight();
        double w2 = figure2.getWidth();
        double h2 = figure2.getHeight();

        return x1 + w1 / 2 >= x2 - w2 / 2
                && x1 - w1 / 2 <= x2 + w2 / 2
                && y1 + h1 / 2 >= y2 - h2 / 2
                && y1 - h1 / 2 <= y2 + h2 / 2;
    }

    public static boolean testPolygons(GamePolygonFigure a, GamePolygonFigure b) {
        for (int x = 0; x < 2; x++) {
            GamePolygonFigure polygon = (x == 0) ? a : b;

            for (int i1 = 0; i1 < polygon.getPoints().length; i1++) {
                int i2 = (i1 + 1) % polygon.getPoints().length;
                Vec2d p1 = polygon.getPoints()[i1];
                Vec2d p2 = polygon.getPoints()[i2];

                Vec2d normal = new Vec2d(p2.y - p1.y, p1.x - p2.x);

                double minA = Double.MAX_VALUE;
                double maxA = Double.MIN_VALUE;

                for (Vec2d p : a.getPoints()) {
                    double projected = normal.x * p.x + normal.y * p.y;

                    if (projected < minA)
                        minA = projected;
                    if (projected > maxA)
                        maxA = projected;
                }

                double minB = Double.MAX_VALUE;
                double maxB = Double.MIN_VALUE;

                for (Vec2d p : b.getPoints()) {
                    double projected = normal.x * p.x + normal.y * p.y;

                    if (projected < minB)
                        minB = projected;
                    if (projected > maxB)
                        maxB = projected;
                }

                if (maxA < minB || maxB < minA)
                    return false;
            }
        }

        return true;
    }
}
