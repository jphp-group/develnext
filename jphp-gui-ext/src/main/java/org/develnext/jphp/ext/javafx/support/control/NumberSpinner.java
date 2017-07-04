package org.develnext.jphp.ext.javafx.support.control;

import javafx.geometry.Pos;
import javafx.scene.control.Spinner;
import javafx.scene.control.SpinnerValueFactory;

public class NumberSpinner extends Spinner<Integer> {
    private int min = Integer.MIN_VALUE;
    private int max = Integer.MAX_VALUE;
    private int step = 1;
    private int initial = 0;

    public NumberSpinner() {
        super();
        setMinSize(0, 0);
        setValueFactory(new SpinnerValueFactory.IntegerSpinnerValueFactory(min, max, initial, step));
    }

    public void setAlignment(Pos value) {
        getEditor().setAlignment(value);
    }

    public Pos getAlignment() {
        return getEditor().getAlignment();
    }

    public int getMin() {
        return min;
    }

    public void setMin(int min) {
        this.min = min;

        setValueFactory(new SpinnerValueFactory.IntegerSpinnerValueFactory(min, max, initial, step));
    }

    public int getMax() {
        return max;
    }

    public void setMax(int max) {
        this.max = max;

        setValueFactory(new SpinnerValueFactory.IntegerSpinnerValueFactory(min, max, initial, step));
    }

    public int getStep() {
        return step;
    }

    public void setStep(int step) {
        this.step = step;

        setValueFactory(new SpinnerValueFactory.IntegerSpinnerValueFactory(min, max, initial, step));
    }

    public int getInitial() {
        return initial;
    }

    public void setInitial(int initial) {
        this.initial = initial;

        setValueFactory(new SpinnerValueFactory.IntegerSpinnerValueFactory(min, max, initial, step));
    }
}
