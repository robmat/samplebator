package com.bcm;

import java.util.Enumeration;
import java.util.Hashtable;

import net.rim.device.api.system.Bitmap;
import net.rim.device.api.system.DeviceInfo;
import net.rim.device.api.ui.DrawStyle;
import net.rim.device.api.ui.Field;
import net.rim.device.api.ui.Manager;
import net.rim.device.api.ui.MenuItem;
import net.rim.device.api.ui.component.ButtonField;
import net.rim.device.api.ui.component.Dialog;
import net.rim.device.api.ui.component.LabelField;

public class ScenarioDetailScreen extends CommonsForScreen implements IWaitableScreen {
	public Hashtable details = null;
	private Dialog dialog;

	public ScenarioDetailScreen(Hashtable hashtable) {
		details = hashtable;
		if (details != null) {
			setTitle(new LabelField(I18n.bundle.getString(BcmResource.processDetailTitle) + ": " + details.get("Name"), DrawStyle.HCENTER | Field.USE_ALL_WIDTH));
			add(new CellularLabelfield(new String[] { I18n.bundle.getString(BcmResource.nameLbl), I18n.bundle.getString(BcmResource.valueLbl) }, new int[] { 60, 40 }, Field.NON_FOCUSABLE, true));
			Enumeration en = details.keys();
			while (en.hasMoreElements()) {
				String name = (String) en.nextElement();
				String value = (String) details.get(name);
				try {
					if (name.equals("ImpactType")) {
						value = Dictionary.getDictionaryValue("SCENARIO_IMPACT_TYPE", value, value);
					}
					if (name.equals("Status")) {
						value = Dictionary.getDictionaryValue("SCENARIO_STATUS", value, value);
					}
				} catch (Exception e) {
					ScenarioDetailScreen.this.errorDialog(e.getClass().getName() + " " + e.getMessage());
					e.printStackTrace();
				}
				add(new CellularLabelfield(new String[] { name + ": ", value }, new int[] { 60, 40 }, Field.FOCUSABLE, false));
			}
		}
		addMenuItem(new MenuItem(I18n.bundle.getString(BcmResource.activateScenarioLbl), 0, 1) {
			public void run() {
				try {
					
					Dialog d = new Dialog(Dialog.D_YES_NO, I18n.bundle.getString(BcmResource.reallyActivateScenarioLbl), 1, Bitmap.getPredefinedBitmap(Bitmap.QUESTION), Dialog.FIELD_HCENTER);
					int result = d.doModal();
					if (result == 4) {
						DataReceiver dr = new DataReceiver();
						String id = (String) details.get("Id");
						dr.getAllData(EntryPoint.authUser, EntryPoint.authPass, DeviceInfo.getDeviceId(), ScenarioDetailScreen.this, "activateScenario&id=" + id, null);
					}
				} catch (Exception e) {
					ScenarioDetailScreen.this.errorDialog(e.getClass().getName() + " " + e.getMessage());
					e.printStackTrace();
				}
			}
		});
	}
	public void log(String str) {
	}
	public boolean onSavePrompt() {
		return true;
	}

	public void startWaiting() {
		dialog = new Dialog(Dialog.D_OK, I18n.bundle.getString(BcmResource.activatingScenarioLbl) + "...", 0, Bitmap.getPredefinedBitmap(Bitmap.HOURGLASS), Field.FIELD_HCENTER);
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

	public int callback(String msg) {
		if (msg != null && msg.indexOf("ok") != -1) {
			dialog = new Dialog(Dialog.D_OK, I18n.bundle.getString(BcmResource.activatingScenarioLbl), 0, Bitmap.getPredefinedBitmap(Bitmap.INFORMATION), Field.FIELD_HCENTER);
			dialog.show();
		} else {
			if (msg.indexOf("isError") != -1 && msg.indexOf("<") != -1 && msg.indexOf(">") != -1) {
				msg = msg.substring(msg.indexOf(">") + 1, msg.indexOf("<", msg.indexOf(">")));
			}
			dialog = new Dialog(Dialog.D_OK, I18n.bundle.getString(BcmResource.activatingScenarioFailedLbl) + ": " + msg, 0, Bitmap.getPredefinedBitmap(Bitmap.EXCLAMATION), Field.FIELD_HCENTER);
			dialog.show();
		}
		return 0;
	}
}
