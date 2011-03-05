package com.bcm;

import net.rim.blackberry.api.browser.Browser;
import net.rim.device.api.system.ApplicationDescriptor;
import net.rim.device.api.system.EncodedImage;
import net.rim.device.api.ui.DrawStyle;
import net.rim.device.api.ui.Field;
import net.rim.device.api.ui.FieldChangeListener;
import net.rim.device.api.ui.Manager;
import net.rim.device.api.ui.UiApplication;
import net.rim.device.api.ui.component.BitmapField;
import net.rim.device.api.ui.component.ButtonField;
import net.rim.device.api.ui.component.Dialog;
import net.rim.device.api.ui.component.LabelField;

public class MainMenuScreen extends CommonsForScreen implements IDataCacheAware {
	public LongButtonField processesBtn = new LongButtonField(I18n.bundle.getString(BcmResource.processesLbl), ButtonField.CONSUME_CLICK);
	public LongButtonField scenariosBtn = new LongButtonField(I18n.bundle.getString(BcmResource.scenariosLbl), ButtonField.CONSUME_CLICK);
	public LongButtonField assetsBtn = new LongButtonField(I18n.bundle.getString(BcmResource.assetsLbl), ButtonField.CONSUME_CLICK);
	public LongButtonField incidentsBtn = new LongButtonField(I18n.bundle.getString(BcmResource.incidentsLbl), ButtonField.CONSUME_CLICK);
	public LongButtonField recoveryBtn = new LongButtonField(I18n.bundle.getString(BcmResource.recoveryLbl), ButtonField.CONSUME_CLICK);
	public LongButtonField notifyBtn = new LongButtonField(I18n.bundle.getString(BcmResource.notifyLbl), ButtonField.CONSUME_CLICK);
	public LongButtonField logoutBtn = new LongButtonField(I18n.bundle.getString(BcmResource.logoutLbl), ButtonField.CONSUME_CLICK);
	public LongButtonField supportBtn = new LongButtonField(I18n.bundle.getString(BcmResource.supportLbl), ButtonField.CONSUME_CLICK);
	public BitmapField logo = new BitmapField(EncodedImage.getEncodedImageResource("logo.jpg").getBitmap(), BitmapField.FIELD_HCENTER | BitmapField.USE_ALL_WIDTH);
	
	public MainMenuScreen() {
		setTitle(new LabelField(I18n.bundle.getString(BcmResource.mainFormTitle), DrawStyle.HCENTER | Field.USE_ALL_WIDTH));
		GridFieldManager mcm = new GridFieldManager(2, Manager.VERTICAL_SCROLL | Manager.USE_ALL_HEIGHT | Manager.USE_ALL_WIDTH);
		add(logo);
		mcm.add(processesBtn);
		mcm.add(scenariosBtn);
		mcm.add(assetsBtn);
		mcm.add(incidentsBtn);
		mcm.add(recoveryBtn);
		mcm.add(notifyBtn);
		mcm.add(logoutBtn);
		mcm.add(supportBtn);
		add(new LabelField("ver " + ApplicationDescriptor.currentApplicationDescriptor().getVersion(), DrawStyle.HCENTER | Field.USE_ALL_WIDTH));
		add(mcm);
		processesBtn.setChangeListener(new FieldChangeListener() {
			public void fieldChanged(Field arg0, int arg1) {
				UiApplication.getUiApplication().pushScreen(new ProcessesScreen(true));
			}
		});
		scenariosBtn.setChangeListener(new FieldChangeListener() {
			public void fieldChanged(Field arg0, int arg1) {
				UiApplication.getUiApplication().pushScreen(new ScenariosScreen());
			}
		});
		assetsBtn.setChangeListener(new FieldChangeListener() {
			public void fieldChanged(Field arg0, int arg1) {
				UiApplication.getUiApplication().pushScreen(new AssetsScreen(null, null));
			}
		});
		incidentsBtn.setChangeListener(new FieldChangeListener() {
			public void fieldChanged(Field arg0, int arg1) {
				UiApplication.getUiApplication().pushScreen(new IncidentsScreen());
			}
		});
		recoveryBtn.setChangeListener(new FieldChangeListener() {
			public void fieldChanged(Field arg0, int arg1) {
				new TaskMenuDialog().show();
			}
		});
		notifyBtn.setChangeListener(new FieldChangeListener() {
			public void fieldChanged(Field arg0, int arg1) {
				UiApplication.getUiApplication().pushScreen(new NotifyMenuScreen());
			}
		});
		logoutBtn.setChangeListener(new FieldChangeListener() {
			public void fieldChanged(Field arg0, int arg1) {
				try {
					PersistentStore.writeTextFile(PersistentStore.FILE_NAME, "not-logged-in");
				} catch (Exception e) {
					MainMenuScreen.this.errorDialog(e.getClass().getName() + " " + e.getMessage());
					e.printStackTrace();
				}
				UiApplication.getUiApplication().popScreen(MainMenuScreen.this);
				UiApplication.getUiApplication().pushScreen(new LoginFormScreen());
			}
		});
		supportBtn.setChangeListener(new FieldChangeListener() {
			public void fieldChanged(Field arg0, int arg1) {
				Browser.getDefaultSession().displayPage("http://support.bcmlogic.com/");
			}
		});
		fillInCaches();
	}

	private void fillInCaches() {
		new Thread(new Runnable() {
			public void run() {
				if (EntryPoint.caches == null) {
					EntryPoint.caches = new DataCache[] { new DataCache(MainMenuScreen.this, "getAllProcesses"), new DataCache(MainMenuScreen.this, "getAllScenarios"), new DataCache(MainMenuScreen.this, "getAllAssets") };
					for (int i = 0; i < EntryPoint.caches.length; i++) {
						if (!EntryPoint.caches[i].fillInCache()) {
							errorDialog("Error filling " + EntryPoint.caches[i].command + " cache.");
						}
					}
				}
			}
		}).start();
	}

	public boolean onSavePrompt() {
		return true;
	}

	public void showDialogWithMsg(final String msg) {
		UiApplication.getUiApplication().invokeLater(new Runnable() {
			public void run() {
				Dialog.alert(msg);
			}
		});
	}
}
