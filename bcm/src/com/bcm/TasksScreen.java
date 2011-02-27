package com.bcm;

import java.util.Hashtable;

import net.rim.device.api.system.Bitmap;
import net.rim.device.api.system.DeviceInfo;
import net.rim.device.api.ui.DrawStyle;
import net.rim.device.api.ui.Field;
import net.rim.device.api.ui.Manager;
import net.rim.device.api.ui.UiApplication;
import net.rim.device.api.ui.component.ButtonField;
import net.rim.device.api.ui.component.Dialog;
import net.rim.device.api.ui.component.LabelField;

import org.w3c.dom.Document;

public class TasksScreen extends CommonsForScreen implements IWaitableScreen {

	public static final int All_TASKS = 1;
	public static final int INCIDENT_TASKS = 2;
	protected static final int MY_TASKS = 3;
	private String id;
	private int mode;
	private Dialog dialog;
	private Hashtable[] items;
	private ColouredListField clf;

	public TasksScreen(int mode, String id, String name) {
		this.id = id;
		this.mode = mode;
		String title = (name == null ? I18n.bundle.getString(BcmResource.allLbl) + " " : "") + (name == null ? I18n.bundle.getString(BcmResource.tasksLbl).toLowerCase() : I18n.bundle.getString(BcmResource.tasksLbl)) + (name != null ? " " + "(" + name + ")" : "");
		setTitle(new LabelField(title, DrawStyle.HCENTER | Field.USE_ALL_WIDTH));
		init();
	}
	public void log(String str) {
	}
	private void init() {
		new Thread(new Runnable() {
			public void run() {
				try {
					DataReceiver dr = new DataReceiver();
					switch (mode) {
					case INCIDENT_TASKS:
						dr.getAllData(EntryPoint.authUser, EntryPoint.authPass, DeviceInfo.getDeviceId(), TasksScreen.this, "getTasksByIncident", "&id=" + id);
						break;
					case All_TASKS:
						dr.getAllData(EntryPoint.authUser, EntryPoint.authPass, DeviceInfo.getDeviceId(), TasksScreen.this, "getAllTasks", "");
						break;
					case MY_TASKS:
						dr.getAllData(EntryPoint.authUser, EntryPoint.authPass, DeviceInfo.getDeviceId(), TasksScreen.this, "getTasksByUser", "");
						break;
					default:
						throw new IllegalStateException("Bad mode set!");
					}
				} catch (Exception e) {
					TasksScreen.this.errorDialog(e.getClass().getName() + " " + e.getMessage());
					e.printStackTrace();
				}
			}
		}).start();
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

	protected boolean onSavePrompt() {
		return true;
	}

	public void stopWaiting() {
		if (dialog != null && dialog.isDisplayed()) {
			dialog.close();
		}
	}

	public int callback(String msg) {
		if (msg != null) {
			Document doc = XMLUtils.parseXML(msg);
			items = XMLUtils.getArrayItems(doc.getElementsByTagName("ArrayOfRecoveryTask").item(0));
			final String[][] itemStrArr = new String[items.length][];
			for (int i = 0; i < items.length; i++) {
				String name = (String) items[i].get("Name");
				String status = (String) items[i].get("Status");
				String type = (String) items[i].get("Type");
				try {
					status = Dictionary.getDictionaryValue("TASK_TASK_STATUS", status, status);
					type = Dictionary.getDictionaryValue("TASK_TASK_TYPE", type, type);
				} catch (Exception e) {
					TasksScreen.this.errorDialog(e.getClass().getName() + " " + e.getMessage());
					e.printStackTrace();
				}
				itemStrArr[i] = new String[] { name, status, type };
			}
			String[] labels = new String[] { I18n.bundle.getString(BcmResource.nameLbl), I18n.bundle.getString(BcmResource.statusLbl), I18n.bundle.getString(BcmResource.typeLbl) };
			CellularLabelfield lf = new CellularLabelfield(labels, new int[] { 55, 20, 25 }, 0, true);
			add(lf);
			clf = new ColouredListField(itemStrArr.length, new int[] { 55, 20, 25 }, itemStrArr);
			clf.set(new String[itemStrArr.length]);
			add(clf);
		}
		return 0;
	}

	protected boolean navigationClick(int arg0, int arg1) {
		if (clf != null) {
			int i = clf.getSelectedIndex();
			UiApplication.getUiApplication().pushScreen(new TaskDetailScreen(items[i], mode == TasksScreen.MY_TASKS));
		}
		return true;
	}
}
