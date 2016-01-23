package org.develnext.jphp.ext.game.support.figure;

import org.develnext.jphp.ext.game.support.Vec2d;

abstract public class GameFigure {
    protected enum Type { UNKNOWN, RECT, CIRCLE, ELLIPSE, POLYGON }

    protected Type type;

    abstract public double getWidth();
    abstract public double getHeight();

    abstract public boolean testCollision(double x1, double y1, double rotation1, double x2, double y2, double rotation2, GameFigure figure);

    public double getCenterX() {
        return getWidth() / 2;
    }

    public double getCenterY() {
        return getHeight() / 2;
    }

    public Vec2d getCenter() {
        return new Vec2d(getCenterX(), getCenterY());
    }

    static boolean testRectangleToPoint(double rectWidth, double rectHeight, double rectRotation, double rectCenterX, double rectCenterY, double pointX, double pointY) {
        if(rectRotation == 0)   // Higher Efficiency for Rectangles with 0 rotation.
            return Math.abs(rectCenterX-pointX) < rectWidth/2 && Math.abs(rectCenterY-pointY) < rectHeight/2;

        double tx = Math.cos(rectRotation) * pointX - Math.sin(rectRotation)*pointY;
        double ty = Math.cos(rectRotation) * pointY + Math.sin(rectRotation)*pointX;

        double cx = Math.cos(rectRotation) * rectCenterX - Math.sin(rectRotation)*rectCenterY;
        double cy = Math.cos(rectRotation) * rectCenterY + Math.sin(rectRotation)*rectCenterX;

        return Math.abs(cx-tx) < rectWidth/2 && Math.abs(cy-ty) < rectHeight/2;
    }

    /** Circle To Segment. */
    boolean testCircleToSegment(double circleCenterX, double circleCenterY, double circleRadius, double lineAX, double lineAY, double lineBX, double lineBY) {
        double lineSize = Math.sqrt(Math.pow(lineAX-lineBX, 2) + Math.pow(lineAY-lineBY, 2));
        double distance;

        if (lineSize == 0) {
            distance = Math.sqrt(Math.pow(circleCenterX-lineAX, 2) + Math.pow(circleCenterY-lineAY, 2));
            return distance < circleRadius;
        }

        double u = ((circleCenterX - lineAX) * (lineBX - lineAX) + (circleCenterY - lineAY) * (lineBY - lineAY)) / (lineSize * lineSize);

        if (u < 0) {
            distance = Math.sqrt(Math.pow(circleCenterX-lineAX, 2) + Math.pow(circleCenterY-lineAY, 2));
        } else if (u > 1) {
            distance = Math.sqrt(Math.pow(circleCenterX-lineBX, 2) + Math.pow(circleCenterY-lineBY, 2));
        } else {
            double ix = lineAX + u * (lineBX - lineAX);
            double iy = lineAY + u * (lineBY - lineAY);
            distance = Math.sqrt(Math.pow(circleCenterX-ix, 2) + Math.pow(circleCenterY-iy, 2));
        }

        return distance < circleRadius;
    }

    /** Rectangle To Circle. */
    boolean testRectangleToCircle(double rectWidth, double rectHeight, double rectRotation, double rectCenterX, double rectCenterY, double circleCenterX, double circleCenterY, double circleRadius) {
        double tx, ty, cx, cy;

        if(rectRotation == 0) { // Higher Efficiency for Rectangles with 0 rotation.
            tx = circleCenterX;
            ty = circleCenterY;

            cx = rectCenterX;
            cy = rectCenterY;
        } else {
            tx = Math.cos(rectRotation)*circleCenterX - Math.sin(rectRotation)*circleCenterY;
            ty = Math.cos(rectRotation)*circleCenterY + Math.sin(rectRotation)*circleCenterX;

            cx = Math.cos(rectRotation)*rectCenterX - Math.sin(rectRotation)*rectCenterY;
            cy = Math.cos(rectRotation)*rectCenterY + Math.sin(rectRotation)*rectCenterX;
        }

        return testRectangleToPoint(rectWidth, rectHeight, rectRotation, rectCenterX, rectCenterY, circleCenterX, circleCenterY) ||
                testCircleToSegment(tx, ty, circleRadius, cx-rectWidth/2, cy+rectHeight/2, cx+rectWidth/2, cy+rectHeight/2) ||
                testCircleToSegment(tx, ty, circleRadius, cx+rectWidth/2, cy+rectHeight/2, cx+rectWidth/2, cy-rectHeight/2) ||
                testCircleToSegment(tx, ty, circleRadius, cx+rectWidth/2, cy-rectHeight/2, cx-rectWidth/2, cy-rectHeight/2) ||
                testCircleToSegment(tx, ty, circleRadius, cx-rectWidth/2, cy-rectHeight/2, cx-rectWidth/2, cy+rectHeight/2);
    }
}
