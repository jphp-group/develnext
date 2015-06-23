package org.develnext.jphp.ext.javafx.classes.data;

import com.sun.javafx.geom.BaseBounds;
import com.sun.javafx.geom.transform.BaseTransform;
import com.sun.javafx.jmx.MXNodeAlgorithm;
import com.sun.javafx.jmx.MXNodeAlgorithmContext;
import com.sun.javafx.sg.prism.NGNode;
import javafx.geometry.Bounds;
import javafx.geometry.Point2D;
import javafx.scene.Node;
import javafx.scene.SnapshotParameters;
import javafx.scene.SnapshotResult;
import javafx.scene.image.WritableImage;
import javafx.util.Callback;

@SuppressWarnings("deprecation")
public abstract class MetaNode extends Node {
    @Override
    public final WritableImage snapshot(SnapshotParameters params, WritableImage image) {
        return image;
    }

    @Override
    public final void snapshot(Callback<SnapshotResult, Void> callback, SnapshotParameters params, WritableImage image) { }

    @Override
    public final double maxHeight(double width) {
        return 0;
    }

    @Override
    public final double maxWidth(double height) {
        return 0;
    }

    @Override
    public final double prefHeight(double width) {
        return 0;
    }

    @Override
    public final double prefWidth(double height) {
        return 0;
    }

    @Override
    public final double minHeight(double width) {
        return 0;
    }

    @Override
    public final double minWidth(double height) {
        return super.minWidth(height);
    }

    @Override
    public final boolean isResizable() {
        return false;
    }

    @Override
    public final boolean contains(double localX, double localY) {
        return false;
    }

    @Override
    protected final boolean containsBounds(double localX, double localY) {
        return false;
    }

    @Override
    public final boolean contains(Point2D localPoint) {
        return false;
    }

    @Override
    public final boolean intersects(double localX, double localY, double localWidth, double localHeight) {
        return false;
    }

    @Override
    public final boolean intersects(Bounds localBounds) {
        return false;
    }

    @Override
    public final void resize(double width, double height) {}

    @Override
    public final void resizeRelocate(double x, double y, double width, double height) {}

    @Override
    public final double computeAreaInScreen() {
        return 0;
    }

    @Override
    public final void relocate(double x, double y) { }

    @Override
    public final void toBack() { }

    @Override
    public final void toFront() { }

    /* DEPRECATED, NO-OP */
    @Override
    public final Object impl_processMXNode(MXNodeAlgorithm alg, MXNodeAlgorithmContext ctx) {
        return null;
    }

    @Override
    public final BaseBounds impl_computeGeomBounds(BaseBounds bounds, BaseTransform tx) {
        return new com.sun.javafx.geom.RectBounds(0,0,0,0);
    }

    @Override
    protected final boolean impl_computeContains(double localX, double localY) {
        return false;
    }

    @Override
    protected final NGNode impl_createPeer() {
        return new NullPeerNGNode();
    }
}
