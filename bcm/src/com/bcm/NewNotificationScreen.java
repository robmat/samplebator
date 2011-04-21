package com.bcm;

import java.io.IOException;
import java.util.Enumeration;
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
import net.rim.device.api.ui.component.ObjectChoiceField;
import net.rim.device.api.ui.component.SeparatorField;

public class NewNotificationScreen extends CommonsForScreen implements IWaitableScreen {
	public ObjectChoiceField templateChoice = new ObjectChoiceField(I18n.bundle.getString(BcmResource.templatesLbl), new String[] {});
	public ButtonField loadFromTemplateBtn = new ButtonField(I18n.bundle.getString(BcmResource.loadFromTemplateLbl), ButtonField.CONSUME_CLICK);
	public LabeledTextField incidentName = new LabeledTextField(I18n.bundle.getString(BcmResource.incidentNameLbl) + ":");
	public LabeledTextField messagecontent = new LabeledTextField(I18n.bundle.getString(BcmResource.messageContentLbl) + ":");
	public LabeledTextField voiceIntro = new LabeledTextField(I18n.bundle.getString(BcmResource.voiceIntroLbl) + ":");
	public LabelField deliverViaLbl = new LabelField(I18n.bundle.getString(BcmResource.deliverViaLbl));
	public CheckboxField voiceChk = new CheckboxField(I18n.bundle.getString(BcmResource.voiceLbl), false, CheckboxField.FIELD_LEFT);
	public CheckboxField smsChk = new CheckboxField("SMS", false, CheckboxField.FIELD_LEFT);
	public CheckboxField emailChk = new CheckboxField("Email", false, CheckboxField.FIELD_LEFT);
	public LabeledTextField callOpt1 = new LabeledTextField(I18n.bundle.getString(BcmResource.callOptLbl) + " 1 :");
	public LabeledTextField callOpt2 = new LabeledTextField(I18n.bundle.getString(BcmResource.callOptLbl) + " 2 :");
	public LabeledTextField callOpt3 = new LabeledTextField(I18n.bundle.getString(BcmResource.callOptLbl) + " 3 :");
	public LabeledTextField callOpt4 = new LabeledTextField(I18n.bundle.getString(BcmResource.callOptLbl) + " 4 :");
	public LabeledTextField callOpt5 = new LabeledTextField(I18n.bundle.getString(BcmResource.callOptLbl) + " 5 :");
	public CheckboxField isPersonalizedChk = new CheckboxField(I18n.bundle.getString(BcmResource.isPersonalizedLbl), false, CheckboxField.FIELD_LEFT);
	public CheckboxField requiresPinChk = new CheckboxField(I18n.bundle.getString(BcmResource.requiresPinLbl), false, CheckboxField.FIELD_LEFT);
	public LabeledTextField free1 = new LabeledTextField("Free1" + " 1 :");
	public LabeledTextField free2 = new LabeledTextField("Free1" + " 2 :");
	public ObjectChoiceField groupChoice = new ObjectChoiceField(I18n.bundle.getString(BcmResource.groupsLbl), new String[] { "Group 1", "Group 2", "Group 3", "Group 4" });
	public ButtonField createBtn = new ButtonField(I18n.bundle.getString(BcmResource.createLbl), ButtonField.FIELD_HCENTER | ButtonField.CONSUME_CLICK);
	private Dialog dialog;
	public Vector selectedGroudIds = new Vector();
	public void log(String str) {
	}

	public NewNotificationScreen(Hashtable template) {
		setTitle(new LabelField(I18n.bundle.getString(BcmResource.newNotificationTitleLbl), DrawStyle.HCENTER | Field.USE_ALL_WIDTH));
		// add(templateChoice);
		// add(loadFromTemplateBtn);
		// add(new SeparatorField());
		add(incidentName);
		add(messagecontent);
		add(voiceIntro);
		add(new SeparatorField());
		add(deliverViaLbl);
		add(voiceChk);
		add(smsChk);
		add(emailChk);
		add(new SeparatorField());
		add(callOpt1);
		add(callOpt2);
		add(callOpt3);
		add(callOpt4);
		add(callOpt5);
		add(new SeparatorField());
		add(isPersonalizedChk);
		add(requiresPinChk);
		add(new SeparatorField());
		add(free1);
		add(free2);
		add(new SeparatorField());
		add(createBtn);
		init();
		Enumeration keys = template.keys();
		while (keys.hasMoreElements()) {
			String key = (String) keys.nextElement();
			if (key.equals("TemplateMessageContent")) {
				messagecontent.setText((String) template.get(key));
			}
			if (key.equals("VoiceIntro")) {
				voiceIntro.setText((String) template.get(key));
			}
			if (key.equals("Call")) {
				voiceChk.setChecked(parseBoolean((String) template.get(key)));
			}
			if (key.equals("Sms")) {
				smsChk.setChecked(parseBoolean((String) template.get(key)));
			}
			if (key.equals("Email")) {
				emailChk.setChecked(parseBoolean((String) template.get(key)));
			}
			if (key.equals("IsPersonalized")) {
				isPersonalizedChk.setChecked(parseBoolean((String) template.get(key)));
			}
			if (key.equals("IsPinRequired")) {
				requiresPinChk.setChecked(parseBoolean((String) template.get(key)));
			}
		}
		addMenuItem(new MenuItem(I18n.bundle.getString(BcmResource.addGroupsOfReceipentsLbl), 0, 6) {
			public void run() {
				UiApplication.getUiApplication().pushScreen(new NotificationGroupsChooseScreen(selectedGroudIds));
			}
		});
		createBtn.setChangeListener(new NewNotificationFieldChangeListener(this));
	}

	private boolean parseBoolean(String s) {
		if (s != null && (s.equals("true") || s.equals("TRUE"))) {
			return true;
		}
		return false;
	}

	private void init() {
		
	}

	public int callback(String msg) {
		if (msg != null && msg.indexOf("isError") > -1 && msg.indexOf("<") != -1 && msg.indexOf(">") != -1) {
			msg = msg.substring(msg.indexOf(">") + 1, msg.indexOf("<", msg.indexOf(">")));
			dialog = new Dialog(Dialog.D_OK, I18n.bundle.getString(BcmResource.notificationCreationFailedLbl) + ": " + msg, 0, Bitmap.getPredefinedBitmap(Bitmap.EXCLAMATION), Field.FIELD_HCENTER);
			dialog.show();
		} else {
			dialog = new Dialog(Dialog.D_OK, I18n.bundle.getString(BcmResource.notificationCreationSuccesLbl), 0, Bitmap.getPredefinedBitmap(Bitmap.INFORMATION), Field.FIELD_HCENTER);
			dialog.show();
		}
		return 0;
	}

	public void startWaiting() {
		dialog = new Dialog(Dialog.D_OK, I18n.bundle.getString(BcmResource.loadDataLbl) + "...", 0, Bitmap.getPredefinedBitmap(Bitmap.HOURGLASS), Field.FIELD_HCENTER);
		try {
			ButtonField b = (ButtonField) ((Manager) ((Manager) dialog.getField(1)).getField(1)).getField(0);
			b.setLabel(I18n.bundle.getString(BcmResource.cancelLbl));
		} catch (Exception e) {
			NewNotificationScreen.this.errorDialog(e.getClass().getName() + " " + e.getMessage());
			e.printStackTrace();
		}
		dialog.show();
	}

	public void stopWaiting() {
		if (dialog != null && dialog.isDisplayed()) {
			dialog.close();
		}
	}

	protected boolean onSavePrompt() {
		return true;
	}

	class NewNotificationFieldChangeListener implements FieldChangeListener {
		private IWaitableScreen waitable;
		public NewNotificationFieldChangeListener(IWaitableScreen waitable) {
			super();
			this.waitable = waitable;
		}
		public void fieldChanged(Field arg0, int arg1) {
			try {
				final DataReceiver dr = new DataReceiver();
				String params = "";
				params = addParam(params, "notify", "action");
		        params = addParam(params, requiresPinChk.getChecked() ? "true" : "false", "isPinRequired");
		        params = addParam(params, voiceChk.getChecked() ? "true" : "false", "isVoiceType");
		        params = addParam(params, isPersonalizedChk.getChecked() ? "true" : "false", "isPersonalized");
		        params = addParam(params, messagecontent.getText(), "message");
		        params = addParam(params, callOpt1.getText(), "callOpt1");
		        params = addParam(params, callOpt2.getText(), "callOpt2");
		        params = addParam(params, callOpt3.getText(), "callOpt3");
		        params = addParam(params, callOpt4.getText(), "callOpt4");
		        params = addParam(params, callOpt5.getText(), "callOpt5");
		        params = addParam(params, smsChk.getChecked() ? "true" : "false", "isSmsType");
		        params = addParam(params, emailChk.getChecked() ? "true" : "false", "isEmailType");
		        params = addParam(params, "true", "isBbPin"); //BB pin?
		        params = addParam(params, "true", "isImType"); // im type ?
		        params = addParam(params, voiceIntro.getText(), "voiceIntro");
		        for (int i = 0; i < selectedGroudIds.size(); i++) {
		        	 params = addParam(params, (String) selectedGroudIds.elementAt(i), "group" + (i + 1));
		        }
		        final String paramsFinal = params;
				new Thread(new Runnable() {
					public void run() {
						try {
							dr.getAllData(EntryPoint.authUser, EntryPoint.authPass, DeviceInfo.getDeviceId(), waitable, "notify", paramsFinal);
						} catch (IOException e) {
							errorDialog(e.getClass() + ": " + e.getMessage());
						}
					}
				}).start();
			} catch (Exception e) {
				errorDialog(e.getClass() + ": " + e.getMessage());
			}
		}
		private String addParam(String params, String value, String key) {
			params += "&" + key + "=" + value;
			return params;
		}
	}
}