package com.bcm;

import net.rim.device.api.system.Display;
import net.rim.device.api.ui.UiApplication;

public class EntryPoint extends UiApplication {
	public static final I18n i18n = new I18n();
	public static String authUser = null;
	public static String authPass = null;
	public static int sw = -1;
	public static int sh = -1;
	public static String SITE_NAME = "site1";
	public static void main(String args[]) {
		EntryPoint ep = new EntryPoint();
		sw = Display.getWidth();
		sh = Display.getHeight();
		ep.pushScreen(new LoginFormScreen());
		ep.enterEventDispatcher();
		if (args.length > 0) {
			SITE_NAME = args[0];
		}
	}
}
