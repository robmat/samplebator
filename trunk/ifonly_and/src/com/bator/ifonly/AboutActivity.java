package com.bator.ifonly;

import android.os.Bundle;

public class AboutActivity extends ActivityBase {
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.about);
		setTopBarTitle(getString(R.string.about_title));
		backButtonListenerSetup();
	}
}
