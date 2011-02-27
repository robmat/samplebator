package com.bcm;

import java.util.Enumeration;
import java.util.Hashtable;

import net.rim.device.api.system.Bitmap;
import net.rim.device.api.system.DeviceInfo;
import net.rim.device.api.ui.DrawStyle;
import net.rim.device.api.ui.Field;
import net.rim.device.api.ui.Manager;
import net.rim.device.api.ui.UiApplication;
import net.rim.device.api.ui.component.ButtonField;
import net.rim.device.api.ui.component.CheckboxField;
import net.rim.device.api.ui.component.Dialog;
import net.rim.device.api.ui.component.LabelField;
import net.rim.device.api.ui.component.ObjectChoiceField;
import net.rim.device.api.ui.component.SeparatorField;

import org.w3c.dom.Document;

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
	private Hashtable[] templateItems;
	private Dialog dialog;

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
	}

	private boolean parseBoolean(String s) {
		if (s != null && (s.equals("true") || s.equals("TRUE"))) {
			return true;
		}
		return false;
	}

	private void init() {
		new Thread() {
			public void run() {
				try {
					DataReceiver dr = new DataReceiver();
					dr.getAllData(EntryPoint.authUser, EntryPoint.authPass, DeviceInfo.getDeviceId(), NewNotificationScreen.this, "getAllNotifyTemplates", "");
					Document doc = XMLUtils.parseXML(dr.getResult());
					templateItems = XMLUtils.getArrayItems(doc);
					final String[] choices = new String[templateItems.length];
					for (int i = 0; i < templateItems.length; i++) {
						choices[i] = (String) templateItems[i].get("Name");
						choices[i] = choices[i] == null ? "" : choices[i];
					}
					UiApplication.getUiApplication().invokeLater(new Runnable() {
						public void run() {
							templateChoice.setChoices(choices);
							if (choices.length > 0) {
								templateChoice.setSelectedIndex(0);
							}
						}
					});
				} catch (Exception e) {
					NewNotificationScreen.this.errorDialog(e.getClass().getName() + " " + e.getMessage());
					e.printStackTrace();
				}
			};
		};//.start();
	}

	public int callback(String msg) {
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
}
