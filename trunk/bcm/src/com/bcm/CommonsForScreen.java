package com.bcm;

import net.rim.device.api.ui.UiApplication;
import net.rim.device.api.ui.component.Dialog;
import net.rim.device.api.ui.container.MainScreen;

public class CommonsForScreen extends MainScreen {
	public void errorDialog(final String msg) {
		UiApplication.getUiApplication().invokeLater(new Runnable() {
			public void run() {
				Dialog.alert(msg);
			}
		});
	}
}
