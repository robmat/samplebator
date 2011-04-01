package com.bcm;

import java.util.Enumeration;
import java.util.Hashtable;

import net.rim.blackberry.api.browser.Browser;
import net.rim.device.api.ui.DrawStyle;
import net.rim.device.api.ui.Field;
import net.rim.device.api.ui.MenuItem;
import net.rim.device.api.ui.UiApplication;
import net.rim.device.api.ui.component.LabelField;

public class TaskDetailScreen extends CommonsForScreen {

	public Hashtable details = null;

	public TaskDetailScreen(Hashtable hashtable, boolean updateOption) {
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
						value = Dictionary.getDictionaryValue("TASK_TASK_STATUS", value, value);
					}
					if (name.equals("Type")) {
						value = Dictionary.getDictionaryValue("TASK_TASK_TYPE", value, value);
					}
				} catch (Exception e) {
					TaskDetailScreen.this.errorDialog(e.getClass().getName() + " " + e.getMessage());
					e.printStackTrace();
				}
				CellularLabelfield clf = new CellularLabelfield(new String[] { name + ": ", value }, new int[] { 50, 50 }, Field.FOCUSABLE, false);
				add(clf);
				if (value.indexOf(DataReceiver.API_ASPX) > -1) { //api url
					clf.setHasUrl(true);
					clf.setUrl(value);
				}
			}
			if (updateOption) {
				addMenuItem(new MenuItem(I18n.bundle.getString(BcmResource.showInfrastructureLbl), 0, 1) {
					public void run() {
						UiApplication.getUiApplication().pushScreen(new InfrastructuresScreen((String) details.get("Id"), (String) details.get("Name"), true));
					}
				});
			}
		}
	}
	protected boolean navigationClick(int status, int time) {
		DataReceiver dr = new DataReceiver();
		String baseUrl = dr.BASE_URL;
		for (int i = 0; i < getFieldCount(); i++ ) {
			if (getField(i).isFocus() && getField(i) instanceof CellularLabelfield && ((CellularLabelfield)getField(i)).isHasUrl()) {
				String url = baseUrl.substring(0, baseUrl.indexOf(DataReceiver.API_ASPX));
				url += ((CellularLabelfield)getField(i)).getUrl() + "&password=" + EntryPoint.authPass + "&user=" + EntryPoint.authUser;
				Browser.getDefaultSession().displayPage(url);
			}
		}
		return true;
	}
	public boolean onSavePrompt() {
		return true;
	}

}
