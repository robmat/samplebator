package com.bator.ifonly;

import android.os.Bundle;

import com.bator.ifonly.util.Utils;

public class MainMenuActivity extends ActivityBase {
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.main_menu);
		findViewById(R.id.main_menu_make_vid_btn_id).setOnClickListener(new Utils.LaunchActivityListener(Utils.CHOOSE_VIDEO_SOURCE_ACTION, this));
	}
}
