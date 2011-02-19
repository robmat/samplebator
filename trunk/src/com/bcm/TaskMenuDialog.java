package com.bcm;

import net.rim.device.api.system.Bitmap;
import net.rim.device.api.ui.Field;
import net.rim.device.api.ui.FieldChangeListener;
import net.rim.device.api.ui.UiApplication;
import net.rim.device.api.ui.component.ButtonField;
import net.rim.device.api.ui.component.Dialog;

public class TaskMenuDialog extends Dialog {
	public ButtonField closeBtn = new ButtonField(I18n.bundle.getString(BcmResource.cancelLbl), ButtonField.FIELD_HCENTER | ButtonField.CONSUME_CLICK | ButtonField.USE_ALL_WIDTH);
	public ButtonField myTasksBtn = new ButtonField(I18n.bundle.getString(BcmResource.myTasksLbl), ButtonField.FIELD_HCENTER | ButtonField.CONSUME_CLICK | ButtonField.USE_ALL_WIDTH);
	public ButtonField allTasksBtn = new ButtonField(I18n.bundle.getString(BcmResource.allTasksLbl), ButtonField.FIELD_HCENTER | ButtonField.CONSUME_CLICK | ButtonField.USE_ALL_WIDTH);

	public TaskMenuDialog() {
		super(I18n.bundle.getString(BcmResource.recoveryLbl), new String[] {}, new int[] {}, 0, Bitmap.getPredefinedBitmap(Bitmap.INFORMATION));
		add(myTasksBtn);
		add(allTasksBtn);
		add(closeBtn);
		myTasksBtn.setChangeListener(new FieldChangeListener() {
			public void fieldChanged(Field arg0, int arg1) {
				TaskMenuDialog.this.close();
				UiApplication.getUiApplication().pushScreen(new TasksScreen(TasksScreen.MY_TASKS, null, null));
			}
		});
		allTasksBtn.setChangeListener(new FieldChangeListener() {
			public void fieldChanged(Field arg0, int arg1) {
				TaskMenuDialog.this.close();
				UiApplication.getUiApplication().pushScreen(new TasksScreen(TasksScreen.All_TASKS, null, null));
			}
		});
		closeBtn.setChangeListener(new FieldChangeListener() {
			public void fieldChanged(Field arg0, int arg1) {
				TaskMenuDialog.this.close();
			}
		});
	}

	protected boolean onSavePrompt() {
		return true;
	}
}
