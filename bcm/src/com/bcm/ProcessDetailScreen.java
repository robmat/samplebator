package com.bcm;

import java.util.Enumeration;
import java.util.Hashtable;

import net.rim.device.api.ui.DrawStyle;
import net.rim.device.api.ui.Field;
import net.rim.device.api.ui.MenuItem;
import net.rim.device.api.ui.UiApplication;
import net.rim.device.api.ui.component.LabelField;

public class ProcessDetailScreen extends CommonsForScreen {
	public Hashtable details = null;

	public ProcessDetailScreen(Hashtable hashtable) {
		details = hashtable;
		if (details != null) {
			setTitle(new LabelField(I18n.bundle.getString(BcmResource.processDetailTitle) + ": " + details.get("Name"), DrawStyle.HCENTER | Field.USE_ALL_WIDTH));
			add(new CellularLabelfield(new String[] { I18n.bundle.getString(BcmResource.nameLbl), I18n.bundle.getString(BcmResource.valueLbl) }, new int[] { 60, 40 }, Field.NON_FOCUSABLE, true));
			Enumeration en = details.keys();
			while (en.hasMoreElements()) {
				String name = (String) en.nextElement();
				String value = (String) details.get(name);
				try {
					if (name.equals("Criticality")) {
						value = Dictionary.getDictionaryValue("BP_CRITICALITY", value, value);
					}
					if (name.equals("Status")) {
						value = Dictionary.getDictionaryValue("BP_STATUS", value, value);
					}
					if (name.equals("Periodicity")) {
						value = Dictionary.getDictionaryValue("BP_PERIODICITY", value, value);
					}
					if (name.equals("Rto")) {
						value = Dictionary.getDictionaryValue("BP_RTO", value, value);
					}
					if (name.equals("Type")) {
						value = Dictionary.getDictionaryValue("BP_TYPE", value, value);
					}
				} catch (Exception e) {
					ProcessDetailScreen.this.errorDialog(e.getClass().getName() + " " + e.getMessage());
					e.printStackTrace();
				}
				add(new CellularLabelfield(new String[] { name + ": ", value }, new int[] { 60, 40 }, Field.FOCUSABLE, false));
			}

			addMenuItem(new MenuItem(I18n.bundle.getString(BcmResource.showAssetsLbl), 0, 1) {
				public void run() {
					UiApplication.getUiApplication().pushScreen(new AssetsScreen((String) details.get("Id"), ((String) details.get("Name")), true));
				}
			});
		}
	}

	public boolean onSavePrompt() {
		return true;
	}
}
