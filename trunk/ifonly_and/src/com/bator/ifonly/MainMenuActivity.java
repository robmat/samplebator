package com.bator.ifonly;

import android.net.Uri;
import android.os.Bundle;

import com.bator.ifonly.util.Utils;

public class MainMenuActivity extends ActivityBase {
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.main_menu);
		findViewById(R.id.main_menu_make_vid_btn_id).setOnClickListener(new Utils.LaunchActivityListener(Utils.CHOOSE_VIDEO_SOURCE_ACTION, this, null));
		findViewById(R.id.main_menu_electrical_id).setOnClickListener(new Utils.LaunchActivityListener(Utils.VIDEO_LIST_ACTION, this, Uri.parse("category://" + Utils.VID_CATEGORY.ELECTRICAL_GOODS.toString())));
		findViewById(R.id.main_menu_garden_id).setOnClickListener(new Utils.LaunchActivityListener(Utils.VIDEO_LIST_ACTION, this, Uri.parse("category://" + Utils.VID_CATEGORY.GARDEN_TOOLS.toString())));
		findViewById(R.id.main_menu_household_id).setOnClickListener(new Utils.LaunchActivityListener(Utils.VIDEO_LIST_ACTION, this, Uri.parse("category://" + Utils.VID_CATEGORY.HOUSEHOLD.toString())));
		findViewById(R.id.main_menu_misc_id).setOnClickListener(new Utils.LaunchActivityListener(Utils.VIDEO_LIST_ACTION, this, Uri.parse("category://" + Utils.VID_CATEGORY.MISC.toString())));
		findViewById(R.id.main_menu_personal_id).setOnClickListener(new Utils.LaunchActivityListener(Utils.VIDEO_LIST_ACTION, this, Uri.parse("category://" + Utils.VID_CATEGORY.PERSONAL_PRODUCTS.toString())));
		findViewById(R.id.main_menu_tools_id).setOnClickListener(new Utils.LaunchActivityListener(Utils.VIDEO_LIST_ACTION, this, Uri.parse("category://" + Utils.VID_CATEGORY.TOOLS_MACHINERY.toString())));
	}
}
