package com.bator.nfz.dlsl.prefs;

import android.content.Context;
import android.content.SharedPreferences;
import android.content.SharedPreferences.Editor;

import com.bator.nfz.dlsl.app.NfzApp;

public class PrefsUtils {
	private static final String PREFS_NAME = "prefs";
	public static final String PREFS_KEY_FIRST_LIST_DIALOG = "PREFS_KEY_FIRST_LIST_DIALOG";
	
	public static boolean getBoolean(String key) {
		SharedPreferences preferences = NfzApp.nfzApp.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE);
		return preferences.getBoolean(key, false);
	}
	public static void setBoolean(String key, boolean val) {
		SharedPreferences preferences = NfzApp.nfzApp.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE);
		Editor editor = preferences.edit();
		editor.putBoolean(key, val);
		editor.commit();
	}
}
