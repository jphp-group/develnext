<?php
namespace ide\bundle\std;

use action\Animation;
use action\Collision;
use action\Element;
use action\Geometry;
use action\Media;
use action\Score;
use game\Jumping;
use ide\bundle\AbstractBundle;
use ide\bundle\AbstractJarBundle;
use ide\project\behaviours\GradleProjectBehaviour;
use php\game\event\UXCollisionEvent;
use php\gui\animation\UXAnimationTimer;
use php\gui\animation\UXKeyFrame;
use php\gui\event\UXContextMenuEvent;
use php\gui\event\UXDragEvent;
use php\gui\event\UXEvent;
use php\gui\event\UXKeyEvent;
use php\gui\event\UXMouseEvent;
use php\gui\event\UXWebEvent;
use php\gui\event\UXWindowEvent;
use php\gui\framework\AbstractForm;
use php\gui\framework\AbstractModule;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXFlowPane;
use php\gui\layout\UXHBox;
use php\gui\layout\UXPane;
use php\gui\layout\UXPanel;
use php\gui\layout\UXScrollPane;
use php\gui\layout\UXStackPane;
use php\gui\layout\UXVBox;
use php\gui\paint\UXColor;
use php\gui\text\UXFont;
use php\gui\UXApplication;
use php\gui\UXButton;
use php\gui\UXCanvas;
use php\gui\UXCell;
use php\gui\UXChoiceBox;
use php\gui\UXClipboard;
use php\gui\UXColorPicker;
use php\gui\UXComboBox;
use php\gui\UXContextMenu;
use php\gui\UXControl;
use php\gui\UXDialog;
use php\gui\UXDirectoryChooser;
use php\gui\UXFileChooser;
use php\gui\UXFlatButton;
use php\gui\UXForm;
use php\gui\UXGeometry;
use php\gui\UXHyperlink;
use php\gui\UXImage;
use php\gui\UXImageArea;
use php\gui\UXImageView;
use php\gui\UXLabel;
use php\gui\UXLabeled;
use php\gui\UXLabelEx;
use php\gui\UXList;
use php\gui\UXListCell;
use php\gui\UXListView;
use php\gui\UXMedia;
use php\gui\UXMediaPlayer;
use php\gui\UXMenu;
use php\gui\UXMenuItem;
use php\gui\UXNode;
use php\gui\UXParent;
use php\gui\UXPasswordField;
use php\gui\UXPopupWindow;
use php\gui\UXProgressBar;
use php\gui\UXProgressIndicator;
use php\gui\UXSlider;
use php\gui\UXSpinner;
use php\gui\UXTab;
use php\gui\UXTabPane;
use php\gui\UXTitledPane;
use php\gui\UXToggleButton;
use php\gui\UXToggleGroup;
use php\gui\UXTooltip;
use php\gui\UXTrayNotification;
use php\gui\UXTreeView;
use php\gui\UXWebEngine;
use php\gui\UXWebView;
use php\gui\UXWindow;

class JPHPGuiDesktopBundle extends AbstractJarBundle
{
    function getName()
    {
        return "JPHP UI Desktop";
    }

    public function getDependencies()
    {
        return [
            JPHPCoreBundle::class,
            JPHPJsonBundle::class,
            JPHPXmlBundle::class,
        ];
    }

    public function applyForGradle(GradleProjectBehaviour $gradle)
    {
        parent::applyForGradle($gradle);

        // legacy.
        $gradle->removeDependency('jphp-gui-desktop-ext');
    }

    /**
     * @return array
     */
    function getJarDependencies()
    {
        return [
            'jphp-gui-ext', 'jphp-desktop-ext', 'jphp-game-ext', 'dyn4j', 'jphp-zend-ext', 'jphp-gui-framework',
        ];
    }

    public function getUseImports()
    {
        return [
            UXNode::class,
            UXEvent::class,
            UXApplication::class,
            UXAnimationTimer::class,
            UXHBox::class,
            UXAnchorPane::class,
            UXClipboard::class,
            UXColor::class,
            UXContextMenuEvent::class,
            UXDialog::class,
            UXFont::class,
            UXGeometry::class,
            UXImage::class,
            UXMedia::class,
            UXMenu::class,
            UXMenuItem::class,
            UXButton::class,
            UXTooltip::class,
            UXToggleButton::class,
            UXToggleGroup::class,
            UXImageView::class,
            UXImageArea::class,
            UXSlider::class,
            UXSpinner::class,
            UXVBox::class,
            UXTitledPane::class,
            UXPanel::class,
            UXFlowPane::class,
            UXForm::class,
            UXWindow::class,
            UXAlert::class,
            UXContextMenu::class,
            UXControl::class,
            UXDirectoryChooser::class,
            UXFileChooser::class,
            UXFlatButton::class,
            UXHyperlink::class,
            UXList::class,
            UXListView::class,
            UXComboBox::class,
            UXChoiceBox::class,
            UXLabel::class,
            UXLabelEx::class,
            UXLabeled::class,
            UXListCell::class,
            UXMediaPlayer::class,
            UXParent::class,
            UXPopupWindow::class,
            UXPasswordField::class,
            UXProgressIndicator::class,
            UXProgressBar::class,
            UXTab::class,
            UXTabPane::class,
            UXTreeView::class,
            UXTrayNotification::class,
            UXWebEngine::class,
            UXWebView::class,
            UXCell::class,
            UXColorPicker::class,
            UXCanvas::class,
            UXStackPane::class,
            UXPane::class,
            UXScrollPane::class,

            UXCollisionEvent::class,
            UXKeyEvent::class,
            UXDragEvent::class,
            UXMouseEvent::class,
            UXWebEvent::class,
            UXWindowEvent::class,

            AbstractForm::class,
            AbstractModule::class,

            Animation::class,
            Collision::class,
            Jumping::class,
            Element::class,
            Geometry::class,
            Media::class,
            Score::class,
        ];
    }
}