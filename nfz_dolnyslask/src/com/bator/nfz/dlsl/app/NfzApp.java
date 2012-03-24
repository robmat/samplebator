package com.bator.nfz.dlsl.app;

import android.app.Application;

public class NfzApp extends Application {
	
	public static NfzApp nfzApp;
	
	@Override
	public void onCreate() {
		super.onCreate();
		nfzApp = this;
	}
}
