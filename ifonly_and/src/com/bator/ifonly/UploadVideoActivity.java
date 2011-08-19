package com.bator.ifonly;

import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;

import android.app.AlertDialog;
import android.app.Dialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.os.Handler;
import android.os.Handler.Callback;
import android.os.Message;
import android.util.Log;
import android.view.View;
import android.view.animation.Animation;
import android.view.animation.LinearInterpolator;
import android.view.animation.RotateAnimation;
import android.widget.EditText;
import android.widget.MediaController;
import android.widget.VideoView;

import com.bator.ifonly.util.DebatingServiceException;
import com.bator.ifonly.util.YoutubeService;
import com.bator.ifonly.util.YoutubeService.Video;

public class UploadVideoActivity extends ActivityBase implements Callback {
	public static final int TEXT_INPUT_DIALOG_NOTES = 1;
	public static final int TEXT_INPUT_DIALOG_TAGS = 2;
	public static final int UPLOAD_VIDEO_MSG_WHAT = 1;
	public static final int UPLOAD_VIDEO_SUCCESS_MSG_WHAT = 2;
	public static final int UPLOAD_VIDEO_ERROR_MSG_WHAT = 3;
	private MediaController mediaController;
	private String tagsStr = "";
	private String notesStr = "";
	private Handler handler = null;

	// private ProgressDialog progressDialog;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.upload_vid);
		backButtonListenerSetup();
		VideoView vv = (VideoView) findViewById(R.id.preview_vid_view);
		mediaController = new MediaController(this, false);
		mediaController.setAnchorView(vv);
		Uri video = getIntent().getData();
		vv.setMediaController(mediaController);
		vv.setVideoURI(video);
		vv.start();
		setUpListeners();
	}

	private void setUpListeners() {
		handler = new Handler() {
			@Override
			public void handleMessage(Message msg) {
				UploadVideoActivity.this.handleMessage(msg);
			}
		};
		findViewById(R.id.upload_vid_about_id).setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				Intent intent = new Intent(UploadVideoActivity.this, SplashActivity.class);
				intent.setData(Uri.parse("fake://foo.bar"));
				startActivity(intent);
			}
		});
		findViewById(R.id.upload_vid_add_note_id).setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				showDialog(TEXT_INPUT_DIALOG_NOTES);
			}
		});
		findViewById(R.id.upload_vid_add_tags_id).setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				showDialog(TEXT_INPUT_DIALOG_TAGS);
			}
		});
		findViewById(R.id.upload_vid_submit_id).setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				startAnimatingIndicator();
				handler.sendEmptyMessage(UPLOAD_VIDEO_MSG_WHAT);
			}
		});
	}

	public void startAnimatingIndicator() {
		RotateAnimation rotation = new RotateAnimation(0f, 360f, Animation.RELATIVE_TO_SELF, 0.5f, Animation.RELATIVE_TO_SELF, 0.5f);
		rotation.setDuration(1200);
		rotation.setInterpolator(new LinearInterpolator());
		rotation.setRepeatMode(Animation.RESTART);
		rotation.setRepeatCount(Animation.INFINITE);
		findViewById(R.id.upload_progress).setVisibility(View.VISIBLE);
		findViewById(R.id.upload_progress).startAnimation(rotation);
	}

	private void uploadVideo() {
		Runnable r = new Runnable() {
			public void run() {
				Uri uri = getIntent().getData();
				String title = "[" + uri.getQueryParameter("category") + "] - " + notesStr;
				String videoFilename = uri.getPath();
				YoutubeService service = new YoutubeService();
				service.apiKey = "key=" + getString(R.string.ytDevKey);
				service.appName = "ifonly-1.0";
				service.youtubeUser = getString(R.string.ytAccountName);
				service.youtubePassword = getString(R.string.ytAccountPass);
				try {
					Video video = service.uploadVideo(handler, new FileInputStream(new File(videoFilename)), videoFilename, title, notesStr, "Education", tagsStr);
					Log.d("uploadVideo", "Video: " + video.toString());
				} catch (FileNotFoundException e) {
					Log.e("uploadVideo", e.getMessage(), e);
				} catch (DebatingServiceException e) {
					Log.e("uploadVideo", e.getMessage(), e);
				}
			}
		};
		new Thread(r).start();
	}

	@Override
	protected Dialog onCreateDialog(final int id) {
		final AlertDialog.Builder alert = new AlertDialog.Builder(this);
		final EditText input = new EditText(this);
		input.setLines(4);
		if (id == TEXT_INPUT_DIALOG_NOTES) {
			alert.setTitle(getString(R.string.upload_vid_add_note));
		}
		if (id == TEXT_INPUT_DIALOG_TAGS) {
			alert.setTitle(getString(R.string.upload_vid_add_tags));
			input.setHint(getString(R.string.upload_vid_add_tags_tooltip));
		}
		alert.setView(input);
		alert.setPositiveButton("Ok", new DialogInterface.OnClickListener() {
			public void onClick(DialogInterface dialog, int whichButton) {
				String value = input.getText().toString().trim();
				switch (id) {
				case TEXT_INPUT_DIALOG_NOTES:
					notesStr = value;
					break;
				case TEXT_INPUT_DIALOG_TAGS:
					tagsStr = value;
					break;
				}
			}
		});
		alert.setNegativeButton("Cancel", new DialogInterface.OnClickListener() {
			public void onClick(DialogInterface dialog, int whichButton) {
				dialog.cancel();
			}
		});
		return alert.create();
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
	public boolean handleMessage(Message msg) {
		findViewById(R.id.upload_progress).setVisibility(View.INVISIBLE);
		if (msg.what == UPLOAD_VIDEO_MSG_WHAT) {
			uploadVideo();
		}
		if (msg.what == UPLOAD_VIDEO_SUCCESS_MSG_WHAT) {
			AlertDialog.Builder builder = new AlertDialog.Builder(this);
			builder.setMessage(getString(R.string.upload_vid_successful)).setCancelable(false).setPositiveButton("Ok", new DialogInterface.OnClickListener() {
				public void onClick(DialogInterface dialog, int id) {
					startActivity(new Intent("ifonly.mainmenu"));
				}
			});
			AlertDialog alert = builder.create();
			alert.setOwnerActivity(this);
			alert.show();
		}
		if (msg.what == UPLOAD_VIDEO_ERROR_MSG_WHAT) {
			AlertDialog.Builder builder = new AlertDialog.Builder(this);
			builder.setMessage((String) msg.obj).setTitle(R.string.upload_vid_error).setCancelable(false).setPositiveButton("Ok", new DialogInterface.OnClickListener() {
				public void onClick(DialogInterface dialog, int id) {
					dialog.dismiss();
				}
			});
			AlertDialog alert = builder.create();
			alert.setOwnerActivity(this);
			alert.show();
		}
		return false;
	}
}
