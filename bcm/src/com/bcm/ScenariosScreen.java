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

public class ScenariosScreen extends CommonsForScreen implements IWaitableScreen {
	public Dialog dialog;
	private ColouredListField clf;
	private boolean useCache;
	public ScenariosScreen(boolean _useCache) {
		useCache = _useCache;
		setTitle(new LabelField(I18n.bundle.getString(BcmResource.scenariosLbl), DrawStyle.HCENTER | Field.USE_ALL_WIDTH));
		init();
		addMenuItem(new MenuItem(I18n.bundle.getString(BcmResource.refreshLbl), 0, 1) {
			public void run() {
				UiApplication.getUiApplication().popScreen(ScenariosScreen.this);
				UiApplication.getUiApplication().pushScreen(new ScenariosScreen(false));
			}
		});
	}

	public void init() {
		Hashtable[] itemsTemp = CacheManager.getCache(CacheManager.GET_ALL_SCENARIOS, null, null);
		if (itemsTemp != null && useCache) {
			items = itemsTemp;
			callback(null);
		} else {
			final DataReceiver dr = new DataReceiver();
			new Thread(new Runnable() {
				public void run() {
					try {
						dr.getAllData(EntryPoint.authUser, EntryPoint.authPass, DeviceInfo.getDeviceId(), ScenariosScreen.this, "getAllScenarios", null);
					} catch (Exception e) {
						ScenariosScreen.this.errorDialog(e.getClass().getName() + " " + e.getMessage());
						e.printStackTrace();
					}
				}
			}).start();
		}
	}

	public void log(String str) {
	}

	public int callback(String msg) {
		if (msg != null && msg instanceof String) {
			System.out.println("ScenariosScreen.callback(): " + msg);
			Document doc = XMLUtils.parseXML(msg);
			items = XMLUtils.getArrayItems(doc);
		}
		if (areAnyItemsThere()) {
			int[] rowsToIndicate = new int[items.length];
			final String[][] itemStrArr = new String[items.length][];
			for (int i = 0; i < items.length; i++) {
				String name = (String) items[i].get("Name");
				String status = (String) items[i].get("Status");
				if (status.equals("1")) {
					rowsToIndicate[i] = 1;
				}
				try {
					status = Dictionary.getDictionaryValue("SCENARIO_STATUS", status, status);
				} catch (Exception e) {
					ScenariosScreen.this.errorDialog(e.getClass().getName() + " " + e.getMessage());
					e.printStackTrace();
				}
				itemStrArr[i] = new String[] { name, status };
			}
			CellularLabelfield lf = new CellularLabelfield(new String[] { I18n.bundle.getString(BcmResource.nameLbl), I18n.bundle.getString(BcmResource.statusLbl) }, new int[] { 70, 30 }, 0, true);
			add(lf);
			clf = new ColouredListField(itemStrArr.length, new int[] { 70, 30 }, itemStrArr);
			clf.set(new String[itemStrArr.length]);
			clf.setRowsToIndicate(rowsToIndicate);
			add(clf);
		}
		return 0;
	}

	protected boolean navigationClick(int status, int time) {
		if (clf != null) {
			int i = clf.getSelectedIndex();
			UiApplication.getUiApplication().pushScreen(new ScenarioDetailScreen(items[i]));
		}
		return true;
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
}
