package com.bcm;

import java.util.Hashtable;

import net.rim.device.api.ui.UiApplication;
import net.rim.device.api.ui.component.Dialog;
import net.rim.device.api.ui.component.TextField;
import net.rim.device.api.ui.container.MainScreen;

public class CommonsForScreen extends MainScreen {
	protected Hashtable[] items;
	public void errorDialog(final String msg) {
		UiApplication.getUiApplication().invokeLater(new Runnable() {
			public void run() {
				Dialog.alert(msg);
			}
		});
	}
	protected boolean areAnyItemsThere() {
		if (items == null || items.length == 0) {
			TextField lbl = new TextField();
			lbl.setText(I18n.bundle.getString(BcmResource.noDataLbl));
			add(lbl);
			return false;
		}
		return true;
	}
}
