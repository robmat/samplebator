package com.bcm;

import java.util.Hashtable;

import net.rim.device.api.system.Bitmap;
import net.rim.device.api.system.DeviceInfo;
import net.rim.device.api.ui.DrawStyle;
import net.rim.device.api.ui.Field;
import net.rim.device.api.ui.Manager;
import net.rim.device.api.ui.MenuItem;
import net.rim.device.api.ui.UiApplication;
import net.rim.device.api.ui.component.ButtonField;
import net.rim.device.api.ui.component.Dialog;
import net.rim.device.api.ui.component.LabelField;

import org.w3c.dom.Document;

public class NotifyTemplatesScreen extends CommonsForScreen implements IWaitableScreen {
	private Hashtable[] items;
	private ColouredListField clf;
	private Dialog dialog;

	public NotifyTemplatesScreen() {
		setTitle(new LabelField(I18n.bundle.getString(BcmResource.notifyTemplatesLbl), DrawStyle.HCENTER | Field.USE_ALL_WIDTH));
		init();
		addMenuItem(new MenuItem(I18n.bundle.getString(BcmResource.refreshLbl), 0, 1) {
			public void run() {
				UiApplication.getUiApplication().popScreen(NotifyTemplatesScreen.this);
				UiApplication.getUiApplication().pushScreen(new NotifyTemplatesScreen());
			}
		});
	}

	private void init() {
		final DataReceiver dr = new DataReceiver();
		new Thread(new Runnable() {
			public void run() {
				try {
					dr.getAllData(EntryPoint.authUser, EntryPoint.authPass, DeviceInfo.getDeviceId(), NotifyTemplatesScreen.this, "getAllNotifyTemplates", null);
				} catch (Exception e) {
					NotifyTemplatesScreen.this.errorDialog(e.getClass().getName() + " " + e.getMessage());
					e.printStackTrace();
				}
			}
		}).start();
	}
	public void log(String str) {
	}
	protected boolean navigationClick(int arg0, int arg1) {
		if (clf != null) {
			int i = clf.getSelectedIndex();
			UiApplication.getUiApplication().pushScreen(new NewNotificationScreen(items[i]));
		}
		return true;
	}

	public void startWaiting() {
		dialog = new Dialog(Dialog.D_OK, I18n.bundle.getString(BcmResource.loadDataLbl) + "...", 0, Bitmap.getPredefinedBitmap(Bitmap.HOURGLASS), Field.FIELD_HCENTER);
		try {
			ButtonField b = (ButtonField) ((Manager) ((Manager) dialog.getField(1)).getField(1)).getField(0);
			b.setLabel(I18n.bundle.getString(BcmResource.cancelLbl));
		} catch (Exception e) {
			NotifyTemplatesScreen.this.errorDialog(e.getClass().getName() + " " + e.getMessage());
			e.printStackTrace();
		}
		dialog.show();
	}

	public void stopWaiting() {
		if (dialog != null && dialog.isDisplayed()) {
			dialog.close();
		}
	}

	public int callback(String msg) {
		if (msg != null && msg instanceof String) {
			Document doc = null;
			doc = XMLUtils.parseXML(msg);
			items = XMLUtils.getArrayItems(doc);
			final String[][] itemStrArr = new String[items.length][];
			for (int i = 0; i < items.length; i++) {
				String name = (String) items[i].get("Name");
				String desc = (String) items[i].get("Desc");
				itemStrArr[i] = new String[] { name, desc };
			}
			CellularLabelfield lf = new CellularLabelfield(new String[] { I18n.bundle.getString(BcmResource.nameLbl), I18n.bundle.getString(BcmResource.statusLbl) }, new int[] { 55, 45 }, 0, true);
			add(lf);
			clf = new ColouredListField(itemStrArr.length, new int[] { 55, 45 }, itemStrArr);
			clf.set(new String[itemStrArr.length]);
			add(clf);
		}
		return 0;
	}
}
