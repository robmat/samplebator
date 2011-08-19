package com.bator.ifonly;

import java.io.File;

import android.content.Intent;
import android.database.Cursor;
import android.net.Uri;
import android.os.Bundle;
import android.os.Environment;
import android.provider.MediaStore;
import android.view.View;
import android.widget.Toast;

import com.bator.ifonly.util.Utils;

public class ChooseVidSourceActivity extends ActivityBase {
	protected static final int REQUEST_VIDEO_CAPTURED = 1;
	protected static final int ACTIVITY_SELECT_IMAGE = 2;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.choose_vid_source);
		backButtonListenerSetup();
		setTopBarTitle(getString(R.string.choose_vid_title_lbl));
		findViewById(R.id.choose_vid_camera_id).setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				String state = android.os.Environment.getExternalStorageState();
				if (!state.equals(android.os.Environment.MEDIA_MOUNTED)) {
					Toast.makeText(ChooseVidSourceActivity.this, "SD Card is not mounted. It is " + state + ".", Toast.LENGTH_LONG).show();
				}
				File tempFile = new File(Environment.getExternalStorageDirectory().getAbsolutePath() + "/ifonlytmp/temp.3gp");
				File directory = tempFile.getParentFile();
				if (!directory.exists() && !directory.mkdirs()) {
					Toast.makeText(ChooseVidSourceActivity.this, "Path to file could not be created.", Toast.LENGTH_LONG).show();
				}
				Intent intent = new Intent(android.provider.MediaStore.ACTION_VIDEO_CAPTURE);
				intent.putExtra("android.intent.extra.durationLimit", 60);
				intent.putExtra(MediaStore.EXTRA_OUTPUT, Uri.fromFile(tempFile));
				startActivityForResult(intent, REQUEST_VIDEO_CAPTURED);
			}
		});
		findViewById(R.id.choose_vid_library_id).setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				Intent i = new Intent(Intent.ACTION_GET_CONTENT);
				i.setType("video/*");
				startActivityForResult(i, ACTIVITY_SELECT_IMAGE);
			}
		});
		findViewById(R.id.choose_vid_cancel_id).setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				finish();
			}
		});
	}

	@Override
	protected void onActivityResult(int requestCode, int resultCode, Intent data) {
		if (resultCode == RESULT_OK) {
			if (requestCode == REQUEST_VIDEO_CAPTURED) {
				Uri uriVideo = data.getData();
				Intent i = new Intent(Utils.CHOOSE_CATEGORY_ACTION, uriVideo);
				startActivity(i);
			}
			if (requestCode == ACTIVITY_SELECT_IMAGE) {
				Uri selectedImage = data.getData();
				String[] filePathColumn = { MediaStore.Video.Media.DATA };
				Cursor cursor = getContentResolver().query(selectedImage, filePathColumn, null, null, null);
				cursor.moveToFirst();
				int columnIndex = cursor.getColumnIndex(filePathColumn[0]);
				String filePath = cursor.getString(columnIndex);
				cursor.close();
				Uri fileUri = Uri.parse("file://" + filePath);
				Intent i = new Intent(Utils.CHOOSE_CATEGORY_ACTION, fileUri);
				startActivity(i);
			}
		} else if (resultCode == RESULT_CANCELED) {
			Toast.makeText(ChooseVidSourceActivity.this, getString(R.string.cancelled), Toast.LENGTH_SHORT).show();
		}
	}
}
