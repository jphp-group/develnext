package org.develnext.jphp.ext.javafx;

import com.sun.javafx.scene.control.skin.CustomColorDialog;
import javafx.animation.*;
import javafx.application.Application;
import javafx.beans.value.ObservableValue;
import javafx.collections.ObservableList;
import javafx.event.ActionEvent;
import javafx.event.Event;
import javafx.fxml.FXMLLoader;
import javafx.scene.Node;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.canvas.*;
import javafx.scene.canvas.Canvas;
import javafx.scene.control.*;
import javafx.scene.control.Button;
import javafx.scene.control.Label;
import javafx.scene.control.Menu;
import javafx.scene.control.MenuBar;
import javafx.scene.control.MenuItem;
import javafx.scene.control.ScrollPane;
import javafx.scene.control.TextArea;
import javafx.scene.control.TextField;
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.scene.input.*;
import javafx.scene.layout.*;
import javafx.scene.paint.Color;
import javafx.scene.shape.*;
import javafx.scene.shape.Rectangle;
import javafx.scene.shape.Shape;
import javafx.scene.text.Font;
import javafx.scene.web.*;
import javafx.stage.*;
import javafx.stage.Window;
import netscape.javascript.JSException;
import org.develnext.jphp.ext.javafx.bind.CursorMemoryOperation;
import org.develnext.jphp.ext.javafx.bind.DurationMemoryOperation;
import org.develnext.jphp.ext.javafx.bind.InsetsMemoryOperation;
import org.develnext.jphp.ext.javafx.bind.KeyCombinationMemoryOperation;
import org.develnext.jphp.ext.javafx.classes.*;
import org.develnext.jphp.ext.javafx.classes.animation.*;
import org.develnext.jphp.ext.javafx.classes.data.Data;
import org.develnext.jphp.ext.javafx.classes.event.*;
import org.develnext.jphp.ext.javafx.classes.layout.*;
import org.develnext.jphp.ext.javafx.classes.paint.UXColor;
import org.develnext.jphp.ext.javafx.classes.shape.*;
import org.develnext.jphp.ext.javafx.classes.text.UXFont;
import org.develnext.jphp.ext.javafx.support.EventProvider;
import org.develnext.jphp.ext.javafx.support.ImageViewEx;
import org.develnext.jphp.ext.javafx.support.control.LabelEx;
import org.develnext.jphp.ext.javafx.support.event.*;
import php.runtime.env.CompileScope;
import php.runtime.ext.support.Extension;
import php.runtime.memory.support.MemoryOperation;

import java.awt.*;
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
        registerMemoryOperation(DurationMemoryOperation.class);

        registerClass(scope, UXGeometry.class);

        registerWrapperClass(scope, ObservableValue.class, UXValue.class);
        registerWrapperClass(scope, ObservableList.class, UXList.class);
        registerWrapperClass(scope, Application.class, UXApplication.class);

        registerWrapperClass(scope, Font.class, UXFont.class);
        registerWrapperClass(scope, Color.class, UXColor.class);
        registerWrapperClass(scope, Image.class, UXImage.class);
        registerWrapperClass(scope, GraphicsContext.class, UXGraphicsContext.class);

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
        registerWrapperClass(scope, FlowPane.class, UXFlowPane.class);

        registerWrapperClass(scope, Labeled.class, UXLabeled.class);
        registerWrapperClass(scope, ButtonBase.class, UXButtonBase.class);
        registerWrapperClass(scope, Button.class, UXButton.class);
        registerWrapperClass(scope, ToggleGroup.class, UXToggleGroup.class);
        registerWrapperClass(scope, ToggleButton.class, UXToggleButton.class);
        registerWrapperClass(scope, CheckBox.class, UXCheckbox.class);
        registerWrapperClass(scope, ImageView.class, UXImageView.class);
        registerWrapperClass(scope, ImageViewEx.class, UXImageArea.class);
        registerWrapperClass(scope, MenuBar.class, UXMenuBar.class);
        registerWrapperClass(scope, TextInputControl.class, UXTextInputControl.class);
        registerWrapperClass(scope, TextArea.class, UXTextArea.class);
        registerWrapperClass(scope, TextField.class, UXTextField.class);
        registerWrapperClass(scope, PasswordField.class, UXPasswordField.class);
        registerWrapperClass(scope, Label.class, UXLabel.class);
        registerWrapperClass(scope, LabelEx.class, UXLabelEx.class);
        registerWrapperClass(scope, Hyperlink.class, UXHyperlink.class);
        registerWrapperClass(scope, ComboBoxBase.class, UXComboBoxBase.class);
        registerWrapperClass(scope, ComboBox.class, UXComboBox.class);
        registerWrapperClass(scope, ChoiceBox.class, UXChoiceBox.class);
        registerWrapperClass(scope, ColorPicker.class, UXColorPicker.class);
        registerWrapperClass(scope, CustomColorDialog.class, UXColorChooser.class);
        registerWrapperClass(scope, ProgressIndicator.class, UXProgressIndicator.class);
        registerWrapperClass(scope, ProgressBar.class, UXProgressBar.class);
        registerWrapperClass(scope, HTMLEditor.class, UXHtmlEditor.class);
        registerWrapperClass(scope, WebHistory.class, UXWebHistory.class);
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
        registerWrapperClass(scope, Slider.class, UXSlider.class);
        registerWrapperClass(scope, DatePicker.class, UXDatePicker.class);
        registerWrapperClass(scope, Canvas.class, UXCanvas.class);

        registerWrapperClass(scope, Cell.class, UXCell.class);
        registerWrapperClass(scope, TableView.class, UXTableView.class);
        registerWrapperClass(scope, TableColumnBase.class, UXTableColumn.class);
        registerWrapperClass(scope, TableCell.class, UXTableCell.class);
        registerWrapperClass(scope, ListCell.class, UXListCell.class);
        MemoryOperation.registerWrapper(TableColumn.class, UXTableColumn.class);
        MemoryOperation.registerWrapper(IndexedCell.class, UXCell.class);

        registerWrapperClass(scope, Dragboard.class, UXDragboard.class);

        registerWrapperClass(scope, Shape.class, UXShape.class);
        registerWrapperClass(scope, Circle.class, UXCircle.class);
        registerWrapperClass(scope, Ellipse.class, UXEllipse.class);
        registerWrapperClass(scope, javafx.scene.shape.Polygon.class, UXPolygon.class);
        registerWrapperClass(scope, Rectangle.class, UXRectangle.class);

        MemoryOperation.registerWrapper(InputEvent.class, UXEvent.class);
        MemoryOperation.registerWrapper(ActionEvent.class, UXEvent.class);
        MemoryOperation.registerWrapper(InputMethodEvent.class, UXEvent.class);
        MemoryOperation.registerWrapper(TreeView.EditEvent.class, UXEvent.class);
        MemoryOperation.registerWrapper(ListView.EditEvent.class, UXEvent.class);
        MemoryOperation.registerWrapper(TreeTableView.EditEvent.class, UXEvent.class);
        registerWrapperClass(scope, Event.class, UXEvent.class);

        registerWrapperClass(scope, MouseEvent.class, UXMouseEvent.class);
        registerWrapperClass(scope, MouseDragEvent.class, UXMouseEvent.class);
        registerWrapperClass(scope, KeyEvent.class, UXKeyEvent.class);
        registerWrapperClass(scope, WindowEvent.class, UXWindowEvent.class);
        registerWrapperClass(scope, ContextMenuEvent.class, UXContextMenuEvent.class);
        registerWrapperClass(scope, DragEvent.class, UXDragEvent.class);
        registerWrapperClass(scope, WebEvent.class, UXWebEvent.class);
        registerWrapperClass(scope, WebErrorEvent.class, UXWebErrorEvent.class);
        registerWrapperClass(scope, ScrollEvent.class, UXScrollEvent.class);

        registerWrapperClass(scope, FXMLLoader.class, UXLoader.class);
        registerWrapperClass(scope, Data.class, UXData.class);
        registerWrapperClass(scope, Alert.class, UXAlert.class);

        registerWrapperClass(scope, Desktop.class, UXDesktop.class);

        registerClass(scope, UXDialog.class);
        registerClass(scope, UXClipboard.class);

        registerJavaException(scope, WrapJSException.class, JSException.class);

        registerAnimationPackage(scope);
        registerEvents();
    }

    protected void registerAnimationPackage(CompileScope scope) {
        registerWrapperClass(scope, KeyFrame.class, UXKeyFrame.class);
        registerWrapperClass(scope, Animation.class, UXAnimation.class);
        registerWrapperClass(scope, Timeline.class, UXTimeline.class);
        registerWrapperClass(scope, FadeTransition.class, UXFadeAnimation.class);
        registerWrapperClass(scope, PathTransition.class, UXPathAnimation.class);
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

        registerEventProvider(new AnimationEventProvider());
    }

    protected void registerEventProvider(EventProvider eventProvider) {
        EventProvider.register(eventProvider);
    }
}
