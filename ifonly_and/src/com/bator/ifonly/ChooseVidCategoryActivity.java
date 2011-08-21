package com.bator.ifonly;

import java.net.URL;

import android.app.AlertDialog;
import android.app.Dialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.widget.MediaController;
import android.widget.VideoView;

import com.bator.ifonly.util.Utils;

public class ChooseVidCategoryActivity extends ActivityBase {
	
	private static final int CHOOSE_CATEGORY_DIALOG = 1;
	private MediaController mediaController;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.choose_vid_category);
		backButtonListenerSetup();
		VideoView vv = (VideoView) findViewById(R.id.preview_vid_view);
		mediaController = new MediaController(this, false);
		mediaController.setAnchorView(vv);
		//mediaController.set
		Uri video = getIntent().getData();
		vv.setMediaController(mediaController);
		vv.setVideoURI(video);
		vv.start();
		showDialog(CHOOSE_CATEGORY_DIALOG);
		setTopBarTitle(getString(R.string.app_name));
	}
	@Override
	protected void onResume() {
		super.onResume();
		VideoView vv = (VideoView) findViewById(R.id.preview_vid_view);
		if (!vv.isPlaying()) {
			vv.start();
		}
	}
	@Override
	protected Dialog onCreateDialog(int id) {
		if (id == CHOOSE_CATEGORY_DIALOG) {
			AlertDialog.Builder builder = new AlertDialog.Builder(this);
			builder.setTitle(getString(R.string.choose_category_dialog_title_lbl));
			builder.setItems(Utils.VID_CATEGORY.getStrArr(this), new DialogInterface.OnClickListener() {
			    public void onClick(DialogInterface dialog, int item) {
			    	try {
			    		playPlak();
						String category = Utils.VID_CATEGORY.getStrArr(ChooseVidCategoryActivity.this)[item];
						URL url = new URL(getIntent().getData().toString() + "?category=" + category);
						Intent i = new Intent(Utils.UPLOAD_VIDEO_ACTION, Uri.parse(url.toString()));
						startActivity(i);
					} catch (Exception e) {
						Log.e("ChooseVidCategoryActivity", "ChooseVidCategoryActivity.onCreateDialog", e);
					}
			    }
			});
			return builder.create();
		}
		return null;
	}
	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		menu.add(R.string.choose_category_dialog_title_lbl).setOnMenuItemClickListener(new MenuItem.OnMenuItemClickListener() {
			@Override
			public boolean onMenuItemClick(MenuItem item) {
				showDialog(CHOOSE_CATEGORY_DIALOG);
				return true;
			}
		});
		return true;
	}
}
