package com.bator.ifonly;

import org.w3c.dom.Document;
import org.w3c.dom.NodeList;

import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.os.Handler;
import android.os.Handler.Callback;
import android.os.Message;
import android.util.Log;
import android.view.View;

import com.bator.ifonly.util.Utils;

public class MainMenuActivity extends ActivityBase implements Callback {
	private static final int VIDEO_LIST_ERROR = 1;
	private static final int SHOW_PROGRESS_DIALOG = 2;
	private Handler handler = new Handler(this);
	private ProgressDialog progressDialog;
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.main_menu);
		findViewById(R.id.main_menu_make_vid_btn_id).setOnClickListener(new Utils.LaunchActivityListener(Utils.CHOOSE_VIDEO_SOURCE_ACTION, this, null));
		findViewById(R.id.main_menu_view_vids_id).setOnClickListener(new Utils.LaunchActivityListener(Utils.VIDEO_LIST_ACTION, this, Uri.parse("category://")));
		findViewById(R.id.main_menu_electrical_id).setOnClickListener(new Utils.LaunchActivityListener(Utils.VIDEO_LIST_ACTION, this, Uri.parse("category://" + Utils.VID_CATEGORY.ELECTRICAL_GOODS.toString())));
		findViewById(R.id.main_menu_garden_id).setOnClickListener(new Utils.LaunchActivityListener(Utils.VIDEO_LIST_ACTION, this, Uri.parse("category://" + Utils.VID_CATEGORY.GARDEN_TOOLS.toString())));
		findViewById(R.id.main_menu_household_id).setOnClickListener(new Utils.LaunchActivityListener(Utils.VIDEO_LIST_ACTION, this, Uri.parse("category://" + Utils.VID_CATEGORY.HOUSEHOLD.toString())));
		findViewById(R.id.main_menu_misc_id).setOnClickListener(new Utils.LaunchActivityListener(Utils.VIDEO_LIST_ACTION, this, Uri.parse("category://" + Utils.VID_CATEGORY.MISC.toString())));
		findViewById(R.id.main_menu_personal_id).setOnClickListener(new Utils.LaunchActivityListener(Utils.VIDEO_LIST_ACTION, this, Uri.parse("category://" + Utils.VID_CATEGORY.PERSONAL_PRODUCTS.toString())));
		findViewById(R.id.main_menu_tools_id).setOnClickListener(new Utils.LaunchActivityListener(Utils.VIDEO_LIST_ACTION, this, Uri.parse("category://" + Utils.VID_CATEGORY.TOOLS_MACHINERY.toString())));
		findViewById(R.id.main_menu_competition_id).setOnClickListener(new Utils.LaunchActivityListener(Utils.COMPETITION_ACTION, this, null));
		findViewById(R.id.main_menu_demo_id).setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				new Thread(new Runnable() {
					public void run() {
						handler.sendEmptyMessage(SHOW_PROGRESS_DIALOG);
					}
				}).start();
				new Thread(new Runnable() {
					public void run() {
					try {
						Document doc = Utils.getVideosDOM("viewCount", "filming_tutorial_video");
						NodeList mediaContentNodes = doc.getElementsByTagName("media:content");
						if (mediaContentNodes.getLength() > 0) {
							String url = mediaContentNodes.item(0).getAttributes().getNamedItem("url").getNodeValue();
							Intent i = new Intent(Intent.ACTION_VIEW, Uri.parse(url));
							startActivity(i);
						}
					} catch (Exception e) {
						Log.e("VideosListActivity", "onCreate", e);
						Message m = new Message();
						m.what = VIDEO_LIST_ERROR;
						m.obj = e.getClass().toString() + ": " + e.getMessage();
						handler.sendMessage(m);
					}
					}
				}).start();
			}
		});
	}
	@Override
	public boolean handleMessage(Message msg) {
		if (msg.what == SHOW_PROGRESS_DIALOG) {
			progressDialog = ProgressDialog.show(MainMenuActivity.this, "", getString(R.string.video_list_progress_dialog_title), true, false);
		}
		if (msg.what == VIDEO_LIST_ERROR) {
			AlertDialog.Builder builder = new AlertDialog.Builder(this);
			builder.setTitle(R.string.video_list_error);
			builder.setMessage(msg.obj.toString());
			builder.setNegativeButton("Ok", new DialogInterface.OnClickListener() {
				@Override
				public void onClick(DialogInterface dialog, int which) {
					playPlak();
					dialog.dismiss();
				}
			});
		}
		return false;
	}
	@Override
	protected void onPause() {
		super.onPause();
		if (progressDialog != null) {
			progressDialog.dismiss();
		}
	}
}
