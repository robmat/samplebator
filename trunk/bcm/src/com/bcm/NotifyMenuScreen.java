package com.bcm;

import net.rim.device.api.system.EncodedImage;
import net.rim.device.api.ui.DrawStyle;
import net.rim.device.api.ui.Field;
import net.rim.device.api.ui.FieldChangeListener;
import net.rim.device.api.ui.UiApplication;
import net.rim.device.api.ui.component.BitmapField;
import net.rim.device.api.ui.component.ButtonField;
import net.rim.device.api.ui.component.LabelField;
import net.rim.device.api.ui.container.MainScreen;

public class NotifyMenuScreen extends MainScreen {
	public BitmapField logo = new BitmapField(EncodedImage.getEncodedImageResource("logo.jpg").getBitmap(), BitmapField.FIELD_HCENTER | BitmapField.USE_ALL_WIDTH);
	public ButtonField fromTemplateBtn = new ButtonField(I18n.bundle.getString(BcmResource.fromTemplateLbl), ButtonField.FIELD_HCENTER | ButtonField.CONSUME_CLICK | ButtonField.USE_ALL_WIDTH);
	public ButtonField newBtn = new ButtonField(I18n.bundle.getString(BcmResource.newBtn), ButtonField.FIELD_HCENTER | ButtonField.CONSUME_CLICK | ButtonField.USE_ALL_WIDTH);
	public ButtonField monitorBtn = new ButtonField(I18n.bundle.getString(BcmResource.monitorLbl), ButtonField.FIELD_HCENTER | ButtonField.CONSUME_CLICK | ButtonField.USE_ALL_WIDTH);

	public NotifyMenuScreen() {
		setTitle(new LabelField(I18n.bundle.getString(BcmResource.notifyLbl), DrawStyle.HCENTER | Field.USE_ALL_WIDTH));
		add(logo);
		add(fromTemplateBtn);
		// add(newBtn);
		add(monitorBtn);
		fromTemplateBtn.setChangeListener(new FieldChangeListener() {
			public void fieldChanged(Field arg0, int arg1) {
				UiApplication.getUiApplication().pushScreen(new NotifyTemplatesScreen());
			}
		});
		// newBtn.setChangeListener(new FieldChangeListener() {
		// public void fieldChanged(Field arg0, int arg1) {
		// UiApplication.getUiApplication().pushScreen(new
		// NewNotificationScreen());
		// }
		// });
		monitorBtn.setChangeListener(new FieldChangeListener() {
			public void fieldChanged(Field arg0, int arg1) {
				UiApplication.getUiApplication().pushScreen(new CallLogsScreen());
			}
		});
	}

	protected boolean onSavePrompt() {
		return true;
	}
}
