package com.bcm;

import net.rim.device.api.system.Bitmap;
import net.rim.device.api.system.DeviceInfo;
import net.rim.device.api.system.EncodedImage;
import net.rim.device.api.ui.DrawStyle;
import net.rim.device.api.ui.Field;
import net.rim.device.api.ui.FieldChangeListener;
import net.rim.device.api.ui.Manager;
import net.rim.device.api.ui.UiApplication;
import net.rim.device.api.ui.component.BasicEditField;
import net.rim.device.api.ui.component.BitmapField;
import net.rim.device.api.ui.component.ButtonField;
import net.rim.device.api.ui.component.Dialog;
import net.rim.device.api.ui.component.LabelField;
import net.rim.device.api.ui.component.PasswordEditField;
import net.rim.device.api.ui.container.HorizontalFieldManager;
import net.rim.device.api.ui.container.VerticalFieldManager;

public class LoginFormScreen extends CommonsForScreen implements IWaitableScreen {
	public BitmapField logo = new BitmapField(EncodedImage.getEncodedImageResource("logo.jpg").getBitmap(), BitmapField.FIELD_HCENTER | BitmapField.USE_ALL_WIDTH);
	public BasicEditField login = new BasicEditField();
	public BasicEditField site = new BasicEditField();
	public PasswordEditField passw = new PasswordEditField();
	public LongButtonField okBtn = new LongButtonField(I18n.bundle.getString(BcmResource.logInLbl), ButtonField.FIELD_HCENTER | ButtonField.CONSUME_CLICK);
	private Dialog dialog = null;
	private VerticalFieldManager vfm = new VerticalFieldManager(Field.USE_ALL_HEIGHT);
	private static final String SEPARATOR = "][";
	public static final String AUTH_TOKEN = "AUTH_TOKEN";

	public LoginFormScreen() {
		log("LoginFormScreen constructor");
		setTitle(new LabelField(I18n.bundle.getString(BcmResource.loginFormTitle), DrawStyle.HCENTER | Field.USE_ALL_WIDTH));
		add(logo);
		add(vfm);
		HorizontalFieldManager hfm = new HorizontalFieldManager(Field.FIELD_HCENTER);
		hfm.add(new LabelField(I18n.bundle.getString(BcmResource.loginLbl) + ": "));
		hfm.add(login);
		vfm.add(hfm);
		hfm = new HorizontalFieldManager(Field.FIELD_HCENTER | Field.USE_ALL_WIDTH);
		hfm.add(new LabelField(I18n.bundle.getString(BcmResource.passwordLbl) + ": "));
		hfm.add(passw);
		vfm.add(hfm);
		hfm = new HorizontalFieldManager(Field.FIELD_HCENTER | Field.USE_ALL_WIDTH);
		hfm.add(new LabelField(I18n.bundle.getString(BcmResource.clientNameLbl) + ": "));
		hfm.add(site);
		vfm.add(hfm);
		vfm.add(okBtn);
		log("LoginFormScreen layout done");
		okBtn.setChangeListener(new FieldChangeListener() {
			public void fieldChanged(Field f, int i) {
				login();
			}
		});
		log("LoginFormScreen listener done");
		try {
			checkIfLoggedIn();
		} catch (Exception e) {
			LoginFormScreen.this.errorDialog(e.getClass().getName() + " " + e.getMessage());
			e.printStackTrace();
		}
	}

	public void log(final String str) {
		System.out.println("log(): " + str);
	}

	private void login() {
		log("login() start");
		EntryPoint.SITE_NAME = site.getText();
		final DataReceiver dr = new DataReceiver();
		log("login() start new DataReceiver();");
		log("login() authorize thread starting");
		new Thread(new Runnable() {
			public void run() {
				try {
					log("login() dr.authorize(...) invoked");
					dr.authorize(login.getText(), passw.getText(), DeviceInfo.getDeviceId(), LoginFormScreen.this);
					log("login() dr.getAllData(...) invoked");
					dr.getAllData(login.getText(), passw.getText(), DeviceInfo.getDeviceId(), LoginFormScreen.this, "getAllDictionaries", null);
				} catch (Exception e) {
					LoginFormScreen.this.errorDialog(e.getClass().getName() + " " + e.getMessage());
					e.printStackTrace();
				}
			}
		}).start();
	}

	private void checkIfLoggedIn() throws Exception {
		log("checkIfLoggedIn() start");
		log("checkIfLoggedIn() reading login data");
		String s = PersistentStore.readTextFile(PersistentStore.FILE_NAME);
		log("checkIfLoggedIn() read login data: " + s);
		if (s != null && s.indexOf(SEPARATOR) > -1) {
			log("checkIfLoggedIn() auto login start");
			String[] arr = StrUtils.splitString(s, SEPARATOR, false);
			login.setText(arr[0]);
			passw.setText(arr[1]);
			site.setText(arr[2]);
			login();
		}
	}

	public void startWaiting() {
		log("startWaiting() start");
		dialog = new Dialog(Dialog.D_OK, I18n.bundle.getString(BcmResource.authorizingLbl), 0, Bitmap.getPredefinedBitmap(Bitmap.HOURGLASS), Field.FIELD_HCENTER);
		try {
			ButtonField b = (ButtonField) ((Manager) ((Manager) dialog.getField(1)).getField(1)).getField(0);
			b.setLabel(I18n.bundle.getString(BcmResource.cancelLbl));
			dialog.show();
		} catch (Exception e) {
			LoginFormScreen.this.errorDialog(e.getClass().getName() + " " + e.getMessage());
			e.printStackTrace();
			log("exception: + " + e.getClass().getName() + " msg: " + e.getMessage());
		}
		log("startWaiting() end");
	}

	public void stopWaiting() {
		log("stopWaiting() start");
		if (dialog != null && dialog.isDisplayed()) {
			dialog.close();
		}
		log("stopWaiting() end");
	}

	public int callback(String msg) {
		log("callback() start, msg: " + (msg != null && msg.length() > 150 ? msg.substring(0, 150) : msg));
		if (msg != null && msg.indexOf(AUTH_TOKEN) > -1) {
			if (msg != null && (msg).indexOf("ok") != -1) {
				EntryPoint.authUser = login.getText();
				EntryPoint.authPass = passw.getText();
				EntryPoint.SITE_NAME = site.getText();
				log("user: " + EntryPoint.authUser + " pass: " + EntryPoint.authPass);
				try {
					PersistentStore.writeTextFile(PersistentStore.FILE_NAME, login.getText() + SEPARATOR + passw.getText() + SEPARATOR + site.getText());
				} catch (Exception e) {
					LoginFormScreen.this.errorDialog(e.getClass().getName() + " " + e.getMessage());
					e.printStackTrace();
					log("exception: + " + e.getClass().getName() + " msg: " + e.getMessage());
				}
				UiApplication.getUiApplication().popScreen(this);
				UiApplication.getUiApplication().pushScreen(new MainMenuScreen());
			} else {
				errorDialog(I18n.bundle.getString(BcmResource.authorizingFaultLbl));
			}
		} else if (msg != null) {
			Dictionary.dictionary = XMLUtils.getDictionary(msg);
			log("Dictionary.dictionary = " + Dictionary.dictionary.getClass().getName());
		}
		log("callback() end");
		return 0;
	}

	public boolean onSavePrompt() {
		return true;
	}
}
