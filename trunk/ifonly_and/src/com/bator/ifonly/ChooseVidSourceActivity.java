package com.bator.ifonly;

import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.view.View;
import android.widget.Toast;

import com.bator.ifonly.util.Utils;

public class ChooseVidSourceActivity extends ActivityBase {
	protected static final int REQUEST_VIDEO_CAPTURED = 1;
	protected static final int ACTIVITY_SELECT_IMAGE = 2;
	public static Uri tempVid;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.choose_vid_source);
		backButtonListenerSetup();
		setTopBarTitle(getString(R.string.choose_vid_title_lbl));
		findViewById(R.id.choose_vid_camera_id).setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				playPlak();
				Intent intent = new Intent(android.provider.MediaStore.ACTION_VIDEO_CAPTURE);
				intent.putExtra("android.intent.extra.durationLimit", 60);
				//intent.putExtra(MediaStore.EXTRA_VIDEO_QUALITY, 0);
//				if (Environment.MEDIA_MOUNTED.equals(Environment.getExternalStorageState())) {
//					Boolean tempDirCreated = new File(Environment.getExternalStorageDirectory() , "ifonly/").mkdirs();
//					Uri tempFile = Uri.fromFile(new File(Environment.getExternalStorageDirectory() , "tempVid"));
//					intent.putExtra(MediaStore.EXTRA_OUTPUT, tempFile);
//					Log.i("tempDirCreated", tempDirCreated.toString());
//				}
				//Uri tempFile = Uri.fromFile(new File(getFilesDir(), "tempVid.3gp"));
				//intent.putExtra(MediaStore.EXTRA_OUTPUT, tempFile);
				startActivityForResult(intent, REQUEST_VIDEO_CAPTURED);
			}
		});
		findViewById(R.id.choose_vid_library_id).setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				playPlak();
				Intent i = new Intent(Intent.ACTION_GET_CONTENT);
				i.setType("video/*");
				startActivityForResult(i, ACTIVITY_SELECT_IMAGE);
			}
		});
		findViewById(R.id.choose_vid_cancel_id).setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				playPlak();
				finish();
			}
		});
	}

	@Override
	protected void onActivityResult(int requestCode, int resultCode, Intent data) {
		if (resultCode == RESULT_OK) {
			/*Uri selectedImage = data.getData();
			String[] filePathColumn = { MediaStore.Video.Media.DATA };
			Cursor cursor = getContentResolver().query(selectedImage, filePathColumn, null, null, null);
			cursor.moveToFirst();
			int columnIndex = cursor.getColumnIndex(filePathColumn[0]);
			String filePath = cursor.getString(columnIndex);
			cursor.close();
			Uri fileUri = Uri.parse("file://" + filePath);*/
			tempVid = data.getData();
			Intent i = new Intent(Utils.CHOOSE_CATEGORY_ACTION);
			startActivity(i);
		} else if (resultCode == RESULT_CANCELED) {
			Toast.makeText(ChooseVidSourceActivity.this, getString(R.string.cancelled), Toast.LENGTH_SHORT).show();
		}
	}
}
