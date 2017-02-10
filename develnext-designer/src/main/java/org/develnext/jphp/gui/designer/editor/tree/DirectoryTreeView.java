package org.develnext.jphp.gui.designer.editor.tree;

import javafx.application.Platform;
import javafx.beans.property.SimpleObjectProperty;
import javafx.beans.value.ChangeListener;
import javafx.scene.control.TreeItem;
import javafx.scene.control.TreeView;
import javafx.util.Callback;

import java.util.ArrayList;
import java.util.HashSet;
import java.util.List;
import java.util.Set;

public class DirectoryTreeView extends TreeView<DirectoryTreeValue> {
    private SimpleObjectProperty<AbstractDirectoryTreeSource> treeSource = new SimpleObjectProperty<>();
    private Set<String> selectedPathPool = new HashSet<>();

    public DirectoryTreeView() {
        setCellFactory(param -> new DirectoryTreeCell());

        treeSourceProperty().addListener((observable, oldValue, newValue) -> {
            TreeItem<DirectoryTreeValue> value = new TreeItem<>(getTreeSource().createValue(""));
            setRoot(value);

            refreshItem(value, "");
        });

        setEditable(false);

        setOnEditCommit(event -> {
            DirectoryTreeValue newValue = event.getNewValue();

            String rename = getTreeSource().rename(newValue.getPath(), newValue.getText());

            if (rename != null) {
                selectedPathPool.add(rename);
            }
        });

        setOnEditCancel(event -> {
            refresh("");
        });
    }

    public DirectoryTreeView(AbstractDirectoryTreeSource treeSource) {
        this();
        setTreeSource(treeSource);
    }


    public AbstractDirectoryTreeSource getTreeSource() {
        return treeSource.get();
    }

    public SimpleObjectProperty<AbstractDirectoryTreeSource> treeSourceProperty() {
        return treeSource;
    }

    public void setTreeSource(AbstractDirectoryTreeSource treeSource) {
        this.treeSource.set(treeSource);
    }

    public void refresh(String path) {
        if (path.isEmpty() || path.equals("/")) {
            refreshItem(getRoot(), "");
        } else {
            String[] strings = path.split("//");
        }
    }

    private Set<String> selectedPaths = new HashSet<>();
    private Set<String> expandedPaths = new HashSet<>();

    private void eachChild(TreeItem<DirectoryTreeValue> owner, Callback<TreeItem<DirectoryTreeValue>, Void> callback) {
        for (TreeItem<DirectoryTreeValue> item : owner.getChildren()) {
            callback.call(item);
            eachChild(item, callback);
        }
    }

    protected void refreshItem(TreeItem<DirectoryTreeValue> item, String path) {
        refreshItem(item, path, true);
    }

    protected void refreshItem(TreeItem<DirectoryTreeValue> item, String path, boolean saveState) {
        boolean empty = getTreeSource().isEmpty(path);

        if (saveState) {
            expandedPaths.clear();
            selectedPaths.clear();

            eachChild(item, treeItem -> {
                if (treeItem.isExpanded()) {
                    expandedPaths.add(treeItem.getValue().getPath());
                }

                return null;
            });

            selectedPaths.addAll(selectedPathPool);
            selectedPathPool.clear();

            for (TreeItem<DirectoryTreeValue> treeItem : getSelectionModel().getSelectedItems()) {
                if (treeItem != null && treeItem.getValue() != null && treeItem.getValue().getPath() != null) {
                    String p = treeItem.getValue().getPath();
                    selectedPaths.add(p);
                }
            }
        }

        item.getChildren().clear();
        TreeItem<DirectoryTreeValue> sub = new TreeItem<>(new DirectoryTreeValue("", ".", ".", null, null, false));

        if (!item.getValue().isAlreadyLoaded()) {
            item.getValue().setAlreadyLoaded(true);
            ChangeListener<Boolean> expandListener = (observable, oldValue, newValue) -> {
                if (newValue) {
                    refreshItem(item, path, false);
                } else {
                    eachChild(item, param -> {
                        if (param.getValue().isFolder()) {
                            DirectoryTreeListener listener = getTreeSource().listener(param.getValue().getPath());

                            if (listener != null) {
                                listener.shutdown();
                            }
                        }
                        return null;
                    });

                    item.getChildren().clear();
                    item.getChildren().add(sub);
                }
            };
            item.expandedProperty().addListener(expandListener);
        }

        DirectoryTreeListener listener = getTreeSource().listener(path);

        if (listener != null) {
            listener.bind(() -> {
                Platform.runLater(() -> refreshItem(item, path));
            });
        }

        if (!empty && item.isExpanded()) {
            List<DirectoryTreeValue> list = getTreeSource().list(path);

            List<TreeItem<DirectoryTreeValue>> selectedItems = new ArrayList<>();

            for (DirectoryTreeValue value : list) {
                TreeItem<DirectoryTreeValue> treeItem = new TreeItem<>(value);
                item.getChildren().add(treeItem);

                if (value.isFolder()) {
                    refreshItem(treeItem, value.getPath(), false);

                    if (expandedPaths.contains(value.getPath())) {
                        treeItem.setExpanded(true);
                    }
                }

                if (selectedPaths.contains(value.getPath())) {
                    selectedItems.add(treeItem);
                }
            }

            getSelectionModel().clearSelection(0);
            for (TreeItem<DirectoryTreeValue> selectedItem : selectedItems) {
                getSelectionModel().select(selectedItem);
            }

            if (saveState) {
                selectedPaths.clear();
                expandedPaths.clear();
            }
        } else {
            if (!empty) {
                item.getChildren().add(sub);
            }
        }
    }
}
