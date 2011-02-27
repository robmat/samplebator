package com.bcm;

import java.util.Enumeration;
import java.util.Hashtable;

import net.rim.device.api.ui.DrawStyle;
import net.rim.device.api.ui.Field;
import net.rim.device.api.ui.MenuItem;
import net.rim.device.api.ui.UiApplication;
import net.rim.device.api.ui.component.LabelField;

public class AssetDetailScreen extends CommonsForScreen {
	public Hashtable details = null;

	public AssetDetailScreen(Hashtable hashtable) {
		details = hashtable;
		if (details != null) {
			setTitle(new LabelField(I18n.bundle.getString(BcmResource.processDetailTitle) + ": " + details.get("Name"), DrawStyle.HCENTER | Field.USE_ALL_WIDTH));
			add(new CellularLabelfield(new String[] { I18n.bundle.getString(BcmResource.nameLbl), I18n.bundle.getString(BcmResource.valueLbl) }, new int[] { 50, 50 }, Field.NON_FOCUSABLE, true));
			Enumeration en = details.keys();

			while (en.hasMoreElements()) {
				String name = (String) en.nextElement();
				String value = (String) details.get(name);
				try {
					if (name.equals("Status")) {
						value = Dictionary.getDictionaryValue("ASSET_STATUS", value, value);
					}
					if (name.equals("AssetType")) {
						value = Dictionary.getDictionaryValue("ASSET_TYPE", value, value);
					}
					if (name.equals("StatusProbe")) {
						value = Dictionary.getDictionaryValue("ASSET_STATUS_PROBE", value, value);
					}
				} catch (Exception e) {
					AssetDetailScreen.this.errorDialog(e.getClass().getName() + " " + e.getMessage());
					e.printStackTrace();
				}
				add(new CellularLabelfield(new String[] { name + ": ", value }, new int[] { 50, 50 }, Field.FOCUSABLE, false));
			}

			if (details.get("AssetType").equals("1")) {
				addMenuItem(new MenuItem(I18n.bundle.getString(BcmResource.showInfrastructureLbl), 0, 1) {
					public void run() {
						String type = (String) details.get("AssetType");
						if ("1".equals(type)) {
							UiApplication.getUiApplication().pushScreen(new InfrastructuresScreen((String) details.get("Id"), (String) details.get("Name")));
						}
					}
				});
			}
		}
	}

	public boolean onSavePrompt() {
		return true;
	}
}
