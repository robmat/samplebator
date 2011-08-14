package com.bator.ifonly.util;

import android.app.Activity;
import android.content.Intent;
import android.view.View;

public class Utils {
	public static final String MAIN_MENU_ACTION = "ifonly.mainmenu";
	public static final String CHOOSE_VIDEO_SOURCE_ACTION = "ifonly.video.source";
	
	public static class LaunchActivityListener implements View.OnClickListener {
		private String action;
		private Activity activity;
		public LaunchActivityListener(String action, Activity activity) {
			this.action = action;
			this.activity = activity;
		}
		public void onClick(View v) {
			activity.startActivity(new Intent(action));
		}
	}
}
