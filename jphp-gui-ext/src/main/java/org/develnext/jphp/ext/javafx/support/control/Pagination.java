package org.develnext.jphp.ext.javafx.support.control;

import javafx.application.Platform;
import javafx.beans.property.SimpleBooleanProperty;
import javafx.beans.property.SimpleIntegerProperty;
import javafx.beans.property.SimpleStringProperty;
import javafx.beans.value.ChangeListener;
import javafx.beans.value.ObservableValue;
import javafx.collections.ObservableList;
import javafx.event.ActionEvent;
import javafx.event.EventHandler;
import javafx.geometry.Insets;
import javafx.scene.Node;
import javafx.scene.control.Button;
import javafx.scene.control.Label;
import javafx.scene.layout.FlowPane;

import java.util.ArrayList;
import java.util.List;

public class Pagination extends FlowPane {
    protected SimpleIntegerProperty total = new SimpleIntegerProperty(0);
    protected SimpleIntegerProperty pageSize = new SimpleIntegerProperty(20);
    protected SimpleIntegerProperty selectedPage = new SimpleIntegerProperty(0);
    protected SimpleIntegerProperty maxPageCount = new SimpleIntegerProperty(9);

    protected Button previousButton = new Button("<");
    protected Button nextButton = new Button(">");
    protected List<Button> pageButtons = new ArrayList<>();

    protected SimpleStringProperty hintText = new SimpleStringProperty("");
    protected SimpleBooleanProperty showTotal = new SimpleBooleanProperty(false);

    public Pagination() {
        getStyleClass().addAll("nav");
        previousButton.getStyleClass().addAll("nav-item", "nav-item-prev");
        nextButton.getStyleClass().addAll("nav-item", "nav-item-next");

        total.addListener(new ChangeListener<Number>() {
            @Override
            public void changed(ObservableValue<? extends Number> observable, Number oldValue, Number newValue) {
                updateUi();
            }
        });

        pageSize.addListener(new ChangeListener<Number>() {
            @Override
            public void changed(ObservableValue<? extends Number> observable, Number oldValue, Number newValue) {
                updateUi();
            }
        });

        maxPageCount.addListener(new ChangeListener<Number>() {
            @Override
            public void changed(ObservableValue<? extends Number> observable, Number oldValue, Number newValue) {
                updateUi();
            }
        });

        selectedPage.addListener(new ChangeListener<Number>() {
            @Override
            public void changed(ObservableValue<? extends Number> observable, Number oldValue, Number newValue) {
                updateSelected();
            }
        });

        hintText.addListener(new ChangeListener<String>() {
            @Override
            public void changed(ObservableValue<? extends String> observable, String oldValue, String newValue) {
                updateUi();
            }
        });

        previousButton.setOnAction(new EventHandler<ActionEvent>() {
            @Override
            public void handle(ActionEvent event) {
                if (selectedPage.get() > 0) {
                    selectedPage.set(selectedPage.get() - 1);
                }
            }
        });

        nextButton.setOnAction(new EventHandler<ActionEvent>() {
            @Override
            public void handle(ActionEvent event) {
                if (selectedPage.get() < getPageCount() - 1) {
                    selectedPage.set(selectedPage.get() + 1);
                }
            }
        });
    }

    public int getTotal() {
        return total.get();
    }

    public SimpleIntegerProperty totalProperty() {
        return total;
    }

    public void setTotal(int total) {
        this.total.set(total);
    }

    public int getPageSize() {
        return pageSize.get();
    }

    public SimpleIntegerProperty pageSizeProperty() {
        return pageSize;
    }

    public void setPageSize(int pageSize) {
        this.pageSize.set(pageSize);
    }

    public int getPageCount() {
        int pages = (int) Math.ceil(total.get() / (float)pageSize.get());
        return pages;
    }

    public int getSelectedPage() {
        return selectedPage.get();
    }

    public SimpleIntegerProperty selectedPageProperty() {
        return selectedPage;
    }

    public void setSelectedPage(int selectedPage) {
        this.selectedPage.set(selectedPage);
    }

    public int getMaxPageCount() {
        return maxPageCount.get();
    }

    public SimpleIntegerProperty maxPageCountProperty() {
        return maxPageCount;
    }

    public void setMaxPageCount(int maxPageCount) {
        this.maxPageCount.set(maxPageCount);
    }

    public Button getPreviousButton() {
        return previousButton;
    }

    public Button getNextButton() {
        return nextButton;
    }

    public String getHintText() {
        return hintText.get();
    }

    public SimpleStringProperty hintTextProperty() {
        return hintText;
    }

    public void setHintText(String hintText) {
        this.hintText.set(hintText);
    }

    public boolean isShowTotal() {
        return showTotal.get();
    }

    public SimpleBooleanProperty showTotalProperty() {
        return showTotal;
    }

    public void setShowTotal(boolean showTotal) {
        this.showTotal.set(showTotal);
    }

    protected void updateSelected() {
        updateUi();
    }

    protected void updateUi() {
        ObservableList<Node> children = this.getChildren();

        children.clear();
        pageButtons.clear();

        int selectedPage = getSelectedPage();
        final int pages = getPageCount();

        children.add(previousButton);

        boolean firstSkip = selectedPage > maxPageCount.get() / 2;
        boolean lastSkip = pages - selectedPage > maxPageCount.get() / 2;

        if (firstSkip) {
            Button button = new Button(String.valueOf(1));
            button.getStyleClass().addAll("nav-item", "nav-item-first");

            button.setOnAction(new EventHandler<ActionEvent>() {
                @Override
                public void handle(ActionEvent event) {
                    setSelectedPage(0);
                }
            });

            pageButtons.add(button);
            children.add(button);

            Label label = new Label("...");
            label.getStyleClass().addAll("nav-item-skip");
            children.add(label);
        }

        int from = selectedPage - maxPageCount.get() / 2;
        if (from < 0) from = 0;

        int to   = from + maxPageCount.get();
        if (to > pages - 1) to = pages;

        if (to - from < maxPageCount.get()) {
            from -= maxPageCount.get() - (to - from);

            if (from < 0) from = 0;
        }

        for (int i = from; i < to; i++) {
            Button button = new Button(String.valueOf(i + 1));
            button.getStyleClass().addAll("nav-item");

            if (selectedPage == i) {
                button.getStyleClass().addAll("selected");
            }

            final int page = i;
            button.setOnAction(new EventHandler<ActionEvent>() {
                @Override
                public void handle(ActionEvent event) {
                    setSelectedPage(page);
                }
            });

            pageButtons.add(button);
            children.addAll(button);
        }

        if (lastSkip) {
            Label label = new Label("...");
            label.getStyleClass().addAll("nav-item-skip");
            children.add(label);

            Button button = new Button(String.valueOf(pages));
            button.getStyleClass().addAll("nav-item", "nav-item-last");

            button.setOnAction(new EventHandler<ActionEvent>() {
                @Override
                public void handle(ActionEvent event) {
                    setSelectedPage(pages - 1);
                }
            });

            pageButtons.add(button);
            children.add(button);
        }

        children.add(nextButton);

        previousButton.setDisable(selectedPage == 0);
        nextButton.setDisable(selectedPage == getPageCount() - 1);

        if (hintText.get() != null && !hintText.get().isEmpty()) {
            Label label = new Label(hintText.get());
            label.getStyleClass().add("nav-hint-text");

            children.add(label);
        }

        if (showTotal.get()) {
            Label label = new Label(String.valueOf(total.get()));
            label.getStyleClass().addAll("nav-hint-total");

            children.add(label);
        }
    }
}
