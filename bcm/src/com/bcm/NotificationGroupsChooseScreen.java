package com.bcm;

import java.util.Hashtable;
import java.util.Vector;

import net.rim.device.api.system.Bitmap;
import net.rim.device.api.system.DeviceInfo;
import net.rim.device.api.ui.DrawStyle;
import net.rim.device.api.ui.Field;
import net.rim.device.api.ui.FieldChangeListener;
import net.rim.device.api.ui.Manager;
import net.rim.device.api.ui.MenuItem;
import net.rim.device.api.ui.UiApplication;
import net.rim.device.api.ui.component.ButtonField;
import net.rim.device.api.ui.component.CheckboxField;
import net.rim.device.api.ui.component.Dialog;
import net.rim.device.api.ui.component.LabelField;

import org.w3c.dom.Document;

public class NotificationGroupsChooseScreen extends CommonsForScreen implements IWaitableScreen {

	private Dialog dialog;
	private Vector selectedIds = null;
	public NotificationGroupsChooseScreen(Vector groups) {
		super();
		selectedIds = groups == null ? new Vector() : groups;
		init();
	}

	private void init() {
		setTitle(new LabelField(I18n.bundle.getString(BcmResource.notificationGroupsTitle), DrawStyle.HCENTER | Field.USE_ALL_WIDTH));
		new Thread() {
			public void run() {
				try {
					DataReceiver dr = new DataReceiver();
					dr.getAllData(EntryPoint.authUser, EntryPoint.authPass, DeviceInfo.getDeviceId(), NotificationGroupsChooseScreen.this, "getAllGroups", "");
				} catch (Exception e) {
					log(e.getMessage());
				}

			};
		}.start();
		addMenuItem(new MenuItem(I18n.bundle.getString(BcmResource.refreshLbl), 0, 1) {
			public void run() {
				UiApplication.getUiApplication().popScreen(NotificationGroupsChooseScreen.this);
				UiApplication.getUiApplication().pushScreen(new NotificationGroupsChooseScreen(selectedIds));
			}
		});
	}

	public int callback(String msg) {
		if (msg != null) {
			Document doc = XMLUtils.parseXML(msg);
			Hashtable[] templateItems = XMLUtils.getArrayItems(doc);
			for (int i = 0; i < templateItems.length; i++) {
				String groupName = (String) templateItems[i].get("Name");
				String groupId = (String) templateItems[i].get("Id");
				CheckboxField chkBx = new CheckboxField(groupName, false);
				add(chkBx);
				chkBx.setCookie(groupId);
				chkBx.setChangeListener(new FieldChangeListener() {
					public void fieldChanged(Field field, int ctx) {
						CheckboxField chkBx = (CheckboxField) field;
						if (chkBx.getChecked()) {
							selectedIds.addElement(chkBx.getCookie());
						} else {
							selectedIds.removeElement(chkBx.getCookie());
						}
					}
				});
				if (selectedIds.contains(groupId)) {
					chkBx.setChecked(true);
				}
			}

		}
		return 0;
	}
	public boolean onSavePrompt() {
		return true;
	}
	public void startWaiting() {
		dialog = new Dialog(Dialog.D_OK, I18n.bundle.getString(BcmResource.loadDataLbl) + "...", 0, Bitmap.getPredefinedBitmap(Bitmap.HOURGLASS), Field.FIELD_HCENTER);
		try {
			ButtonField b = (ButtonField) ((Manager) ((Manager) dialog.getField(1)).getField(1)).getField(0);
			b.setLabel(I18n.bundle.getString(BcmResource.cancelLbl));
		} catch (Exception e) {
			e.printStackTrace();
		}
		dialog.show();
	}

	public void stopWaiting() {
		if (dialog != null && dialog.isDisplayed()) {
			dialog.close();
		}
	}

	public void log(String s) {
		System.out.println(s);
	}

	public boolean onClose() {
		
		return super.onClose();
	}

}
