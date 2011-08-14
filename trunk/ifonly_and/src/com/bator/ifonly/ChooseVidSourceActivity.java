package com.bator.ifonly;

import java.io.File;

import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.os.Environment;
import android.provider.MediaStore;
import android.view.View;
import android.widget.Toast;

public class ChooseVidSourceActivity extends ActivityBase {
	protected static final int REQUEST_VIDEO_CAPTURED = 1;
	private Uri uriVideo;
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.choose_vid_source);
		setTopBarTitle(getString(R.string.choose_vid_title_lbl));
		findViewById(R.id.choose_vid_camera_id).setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				String state = android.os.Environment.getExternalStorageState();
			    if(!state.equals(android.os.Environment.MEDIA_MOUNTED))  {
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
	}

	@Override
	protected void onActivityResult(int requestCode, int resultCode, Intent data) {
		if (resultCode == RESULT_OK) {
			if (requestCode == REQUEST_VIDEO_CAPTURED) {
				uriVideo = data.getData();
				//TODOToast.makeText(ChooseVidSourceActivity.this, uriVideo.getPath(), Toast.LENGTH_LONG).show();
			}
		} else if (resultCode == RESULT_CANCELED) {
			uriVideo = null;
			Toast.makeText(ChooseVidSourceActivity.this, "Cancelled!", Toast.LENGTH_LONG).show();
		}
	}
}
