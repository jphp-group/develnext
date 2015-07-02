package org.develnext.jphp.ext.javafx;

import javafx.application.Application;
import javafx.collections.ObservableList;
import javafx.event.ActionEvent;
import javafx.event.Event;
import javafx.fxml.FXMLLoader;
import javafx.scene.Node;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.control.*;
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.scene.input.*;
import javafx.scene.layout.*;
import javafx.scene.paint.Color;
import javafx.scene.text.Font;
import javafx.scene.web.*;
import javafx.stage.*;
import org.develnext.jphp.ext.javafx.bind.CursorMemoryOperation;
import org.develnext.jphp.ext.javafx.bind.InsetsMemoryOperation;
import org.develnext.jphp.ext.javafx.bind.KeyCombinationMemoryOperation;
import org.develnext.jphp.ext.javafx.classes.*;
import org.develnext.jphp.ext.javafx.classes.data.Data;
import org.develnext.jphp.ext.javafx.classes.event.*;
import org.develnext.jphp.ext.javafx.classes.layout.*;
import org.develnext.jphp.ext.javafx.classes.paint.UXColor;
import org.develnext.jphp.ext.javafx.classes.text.UXFont;
import org.develnext.jphp.ext.javafx.support.EventProvider;
import org.develnext.jphp.ext.javafx.support.event.*;
import php.runtime.env.CompileScope;
import php.runtime.ext.support.Extension;
import php.runtime.memory.support.MemoryOperation;

import java.awt.event.InputEvent;

public class JavaFXExtension extends Extension {
    public final static String NS = "php\\gui\\";

    @Override
    public Status getStatus() {
        return Status.EXPERIMENTAL;
    }

    @Override
    public String getVersion() {
        return "1.0";
    }

    @Override
    public void onRegister(CompileScope scope) {
        registerMemoryOperation(KeyCombinationMemoryOperation.class);
        registerMemoryOperation(CursorMemoryOperation.class);
        registerMemoryOperation(InsetsMemoryOperation.class);

        registerWrapperClass(scope, ObservableList.class, UXList.class);
        registerWrapperClass(scope, Application.class, UXApplication.class);

        registerWrapperClass(scope, Font.class, UXFont.class);
        registerWrapperClass(scope, Color.class, UXColor.class);
        registerWrapperClass(scope, Image.class, UXImage.class);

        registerWrapperClass(scope, Window.class, UXWindow.class);
        registerWrapperClass(scope, Stage.class, UXForm.class);
        registerWrapperClass(scope, PopupWindow.class, UXPopupWindow.class);
        registerWrapperClass(scope, Tooltip.class, UXTooltip.class);
        registerWrapperClass(scope, ContextMenu.class, UXContextMenu.class);
        registerWrapperClass(scope, MenuItem.class, UXMenuItem.class);
        registerWrapperClass(scope, Menu.class, UXMenu.class);
       // MemoryOperation.registerWrapper(SeparatorMenuItem.class, UXMenuItem.class);
        registerWrapperClass(scope, Scene.class, UXScene.class);

        registerWrapperClass(scope, Node.class, UXNode.class);
        registerWrapperClass(scope, Parent.class, UXParent.class);
        registerWrapperClass(scope, Region.class, UXRegion.class);
        registerWrapperClass(scope, Control.class, UXControl.class);

        registerWrapperClass(scope, Pane.class, UXPane.class);
        registerWrapperClass(scope, AnchorPane.class, UXAnchorPane.class);
        registerWrapperClass(scope, StackPane.class, UXStackPane.class);
        registerWrapperClass(scope, VBox.class, UXVBox.class);
        registerWrapperClass(scope, HBox.class, UXHBox.class);

        registerWrapperClass(scope, Labeled.class, UXLabeled.class);
        registerWrapperClass(scope, ButtonBase.class, UXButtonBase.class);
        registerWrapperClass(scope, Button.class, UXButton.class);
        registerWrapperClass(scope, ToggleGroup.class, UXToggleGroup.class);
        registerWrapperClass(scope, ToggleButton.class, UXToggleButton.class);
        registerWrapperClass(scope, CheckBox.class, UXCheckbox.class);
        registerWrapperClass(scope, ImageView.class, UXImageView.class);
        registerWrapperClass(scope, MenuBar.class, UXMenuBar.class);
        registerWrapperClass(scope, TextInputControl.class, UXTextInputControl.class);
        registerWrapperClass(scope, TextArea.class, UXTextArea.class);
        registerWrapperClass(scope, TextField.class, UXTextField.class);
        registerWrapperClass(scope, PasswordField.class, UXPasswordField.class);
        registerWrapperClass(scope, Label.class, UXLabel.class);
        registerWrapperClass(scope, Hyperlink.class, UXHyperlink.class);
        registerWrapperClass(scope, ComboBoxBase.class, UXComboBoxBase.class);
        registerWrapperClass(scope, ComboBox.class, UXComboBox.class);
        registerWrapperClass(scope, ChoiceBox.class, UXChoiceBox.class);
        registerWrapperClass(scope, ColorPicker.class, UXColorPicker.class);
        registerWrapperClass(scope, ProgressIndicator.class, UXProgressIndicator.class);
        registerWrapperClass(scope, ProgressBar.class, UXProgressBar.class);
        registerWrapperClass(scope, HTMLEditor.class, UXHtmlEditor.class);
        registerWrapperClass(scope, WebEngine.class, UXWebEngine.class);
        registerWrapperClass(scope, WebView.class, UXWebView.class);
        registerWrapperClass(scope, Tab.class, UXTab.class);
        registerWrapperClass(scope, TabPane.class, UXTabPane.class);
        registerWrapperClass(scope, ScrollPane.class, UXScrollPane.class);
        registerWrapperClass(scope, TitledPane.class, UXTitledPane.class);
        registerWrapperClass(scope, SplitPane.class, UXSplitPane.class);
        registerWrapperClass(scope, TreeItem.class, UXTreeItem.class);
        registerWrapperClass(scope, TreeView.class, UXTreeView.class);
        registerWrapperClass(scope, Separator.class, UXSeparator.class);
        registerWrapperClass(scope, ListView.class, UXListView.class);
        registerWrapperClass(scope, FileChooser.class, UXFileChooser.class);
        registerWrapperClass(scope, DirectoryChooser.class, UXDirectoryChooser.class);

        registerWrapperClass(scope, Cell.class, UXCell.class);
        registerWrapperClass(scope, TableView.class, UXTableView.class);
        registerWrapperClass(scope, TableColumnBase.class, UXTableColumn.class);
        registerWrapperClass(scope, TableCell.class, UXTableCell.class);
        registerWrapperClass(scope, ListCell.class, UXListCell.class);
        MemoryOperation.registerWrapper(TableColumn.class, UXTableColumn.class);
        MemoryOperation.registerWrapper(IndexedCell.class, UXCell.class);

        MemoryOperation.registerWrapper(InputEvent.class, UXEvent.class);
        MemoryOperation.registerWrapper(ActionEvent.class, UXEvent.class);
        MemoryOperation.registerWrapper(InputMethodEvent.class, UXEvent.class);
        MemoryOperation.registerWrapper(TreeView.EditEvent.class, UXEvent.class);
        MemoryOperation.registerWrapper(ListView.EditEvent.class, UXEvent.class);
        MemoryOperation.registerWrapper(TreeTableView.EditEvent.class, UXEvent.class);
        registerWrapperClass(scope, Event.class, UXEvent.class);

        registerWrapperClass(scope, MouseEvent.class, UXMouseEvent.class);
        registerWrapperClass(scope, KeyEvent.class, UXKeyEvent.class);
        registerWrapperClass(scope, WindowEvent.class, UXWindowEvent.class);
        registerWrapperClass(scope, ContextMenuEvent.class, UXContextMenuEvent.class);
        registerWrapperClass(scope, DragEvent.class, UXDragEvent.class);
        registerWrapperClass(scope, WebEvent.class, UXWebEvent.class);
        registerWrapperClass(scope, WebErrorEvent.class, UXWebErrorEvent.class);

        registerWrapperClass(scope, FXMLLoader.class, UXLoader.class);
        registerWrapperClass(scope, Data.class, UXData.class);

        registerClass(scope, UXDialog.class);
        registerClass(scope, UXClipboard.class);

        registerEvents();
    }

    protected void registerEvents() {
        registerEventProvider(new NodeEventProvider());
        registerEventProvider(new WindowEventProvider());
        registerEventProvider(new ContextMenuEventProvider());
        registerEventProvider(new MenuItemEventProvider());
        registerEventProvider(new MenuEventProvider());
        registerEventProvider(new ButtonBaseEventProvider());
        registerEventProvider(new ComboBoxBaseEventProvider());
        registerEventProvider(new ChoiceBoxEventProvider());
        registerEventProvider(new WebEngineEventProvider());
        registerEventProvider(new TreeViewEventProvider());
        registerEventProvider(new TabEventProvider());
    }

    protected void registerEventProvider(EventProvider eventProvider) {
        EventProvider.register(eventProvider);
    }
}
