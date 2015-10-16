package org.develnext.jphp.ext.game.support;

import javafx.beans.value.ChangeListener;
import javafx.beans.value.ObservableValue;
import javafx.scene.canvas.Canvas;
import javafx.scene.canvas.GraphicsContext;
import javafx.scene.image.Image;

import java.util.HashMap;
import java.util.Map;

public class Sprite extends Canvas {
    public static class Animation {
        protected int[] indexes;
        protected int speed = -1;

        public int currentIndex;

        public Animation(int... indexes) {
            this.indexes = indexes;
        }

        public void setIndexes(int... indexes) {
            this.indexes = indexes;
        }

        public void setRange(int from, int to) {
            int len = to - from;

            indexes = new int[len];

            for (int i = 0; i < len; i++) {
                indexes[i] = i + from;
            }
        }

        public int getMax() {
            return indexes.length - 1;
        }

        public int getSpeed() {
            return speed;
        }

        public void setSpeed(int speed) {
            this.speed = speed;
        }
    }

    public final Map<String, Animation> animations = new HashMap<>();

    protected Image image;
    protected Double frameWidth;
    protected Double frameHeight;

    private int rows = 0;
    private int cols = 0;

    private int maxIndex = -1;

    private int speed = 12;
    private int currentIndex = -1;
    private Animation currentAnimation = null;
    private boolean cycledAnimation = true;

    private boolean freeze = false;

    public Sprite() {
        super();

        widthProperty().addListener(new ChangeListener<Number>() {
            @Override
            public void changed(ObservableValue<? extends Number> observableValue, Number number, Number t1) {
                setImage(getImage());
            }
        });

        heightProperty().addListener(new ChangeListener<Number>() {
            @Override
            public void changed(ObservableValue<? extends Number> observableValue, Number number, Number t1) {
                setImage(getImage());
            }
        });
    }

    public void freeze() {
        this.freeze = true;
    }

    public void unfreeze() {
        this.freeze = false;
    }

    public boolean isFreeze() {
        return freeze;
    }

    public double getFrameWidth() {
        return frameWidth;
    }

    public void setFrameSize(double width, double height) {
        this.frameWidth = width;
        this.frameHeight = height;

        setWidth(frameWidth);
        setHeight(frameHeight);
    }

    public double[] getFrameSize() {
        return new double[] { frameWidth, frameHeight };
    }

    public Image getImage() {
        return image;
    }

    public void setImage(Image image) {
        this.image = image;

        if (image != null) {
            double width = image.getWidth();
            double height = image.getHeight();

            if (frameWidth == null || frameHeight == null) {
                maxIndex = 1;
                frameWidth = width;
                frameHeight = height;
                cols = 1;
                rows = 1;
            } else {
                cols = (int) Math.floor(width / frameWidth);
                rows = (int) Math.floor(height / frameHeight);

                maxIndex = (cols * rows) - 1;
            }
        } else {
            rows = cols = maxIndex = -1;
        }
    }

    public void setAnimation(String name, int... indexes) {
        synchronized (animations) {
            animations.put(name, new Animation(indexes));
        }
    }

    public void setAnimation(String name, int from, int length) {
        synchronized (animations) {
            Animation value = new Animation();
            value.setRange(from, from + length + 1);
            animations.put(name, value);
        }
    }

    synchronized public void setAnimationSpeed(String name, int speed) {
        synchronized (animations) {
            if (!animations.containsKey(name)) {
                setAnimation(name);
            }

            animations.get(name).setSpeed(speed);
        }
    }

    public void drawNext() {
        currentIndex++;

        if (currentIndex > maxIndex) {
            currentIndex = 0;
        }

        draw(currentIndex);
    }

    public boolean isCycledAnimation() {
        return cycledAnimation;
    }

    public void setCycledAnimation(boolean cycledAnimation) {
        this.cycledAnimation = cycledAnimation;
    }

    public void setCurrentAnimation(String name) {
        this.currentAnimation = animations.get(name);
    }

    public int getSpeed() {
        return speed;
    }

    public void setSpeed(int speed) {
        this.speed = speed;
    }

    public void drawByTime(long now) {
        if (freeze || maxIndex < 1) {
            return;
        }

        int drawIndex = -1;

        if (currentAnimation != null) {
            int speed = currentAnimation.getSpeed() > -1 ? currentAnimation.getSpeed() : this.speed;

            if (speed <= 0) {
                drawIndex = currentAnimation.currentIndex;
            } else {
                int newIndex = (int) Math.floor((now - currentAnimation.currentIndex) / (1000000000 / speed)); //Determine how many frames we need to advance to maintain frame rate independence

                newIndex = newIndex % currentAnimation.getMax();
                drawIndex = currentAnimation.indexes[newIndex];
            }
        } else {
            if (speed <= 0) {
                drawIndex = currentIndex;
            } else {
                int newIndex = (int) Math.floor((now - currentIndex) / (1000000000 / speed)); //Determine how many frames we need to advance to maintain frame rate independence

                drawIndex = newIndex % maxIndex;
            }
        }

        if (cycledAnimation || (currentIndex == -1 || currentIndex < drawIndex)) {
            draw(drawIndex);
        }
    }

    public void draw(int index) {
        if (freeze) {
            return;
        }

        currentIndex = index;
        GraphicsContext gc = getGraphicsContext2D();

        gc.clearRect(0, 0, getWidth(), getHeight());

        if (this.image == null || this.frameHeight < 0.1 || this.frameWidth < 0.1 || index > maxIndex || index < 0) {
            return;
        }

        //
        int row = (int) Math.ceil(index / cols);
        int col = index % cols;

        double x = col * frameWidth;
        double y = row * frameHeight;

        gc.drawImage(image, x, y, frameWidth, frameHeight, 0, 0, frameWidth, frameHeight);
    }
}
