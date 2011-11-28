package com.bator.ifonly;

import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;

import android.app.AlertDialog;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.database.Cursor;
import android.net.Uri;
import android.os.Bundle;
import android.os.Handler;
import android.os.Handler.Callback;
import android.os.Message;
import android.provider.MediaStore;
import android.util.Log;
import android.view.View;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.MediaController;
import android.widget.VideoView;

import com.bator.ifonly.util.DebatingServiceException;
import com.bator.ifonly.util.Utils;
import com.bator.ifonly.util.YoutubeService;
import com.bator.ifonly.util.YoutubeService.Video;
import com.google.api.client.http.AbstractInputStreamContent;
import com.google.api.client.http.AbstractInputStreamContent.UploadListener;

public class UploadVideoActivity extends ActivityBase implements Callback, UploadListener {
	public static final int TEXT_INPUT_DIALOG_NOTES = 1;
	public static final int TEXT_INPUT_DIALOG_TAGS = 2;
	public static final int UPLOAD_VIDEO_MSG_WHAT = 1;
	public static final int UPLOAD_VIDEO_SUCCESS_MSG_WHAT = 2;
	public static final int UPLOAD_VIDEO_ERROR_MSG_WHAT = 3;
	public static final int UPLOAD_VIDEO_PROGRESS = 4;
	private MediaController mediaController;
	private String tagsStr = "";
	private String notesStr = "";
	private Handler handler = new Handler(this);
	private Dialog dialog;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.upload_vid);
		setTopBarTitle(getString(R.string.upload_vid_top_bar_title));
		backButtonListenerSetup();
		VideoView vv = (VideoView) findViewById(R.id.preview_vid_view);
		mediaController = new MediaController(this, false);
		mediaController.setAnchorView(vv);
		Uri video = ChooseVidCategoryActivity.tempVid;
		vv.setMediaController(mediaController);
		vv.setVideoURI(video);
		vv.start();
		setUpListeners();
		if (!Utils.isNetworkAvailable(this)) {
			AlertDialog.Builder builder = new AlertDialog.Builder(this);
			builder.setTitle(getString(R.string.error));
			builder.setMessage(getString(R.string.network_unreachable)).setCancelable(false).setPositiveButton("Ok", new DialogInterface.OnClickListener() {
				public void onClick(DialogInterface dialog, int id) {
					playPlak();
					startActivity(new Intent("ifonly.mainmenu"));
					finish();
				}
			});
			AlertDialog alert = builder.create();
			alert.setOwnerActivity(this);
			alert.show();
		}
	}

	private void setUpListeners() {
		findViewById(R.id.upload_vid_about_id).setOnClickListener(new Utils.LaunchActivityListener(Utils.ABOUT_ACTION, this, null));
		findViewById(R.id.upload_vid_add_note_id).setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				playPlak();
				showDialog(TEXT_INPUT_DIALOG_NOTES);
				((ImageView) v).setImageResource(R.drawable.upload_vid_add_note_visited_btn);
			}
		});
		findViewById(R.id.upload_vid_add_tags_id).setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				playPlak();
				showDialog(TEXT_INPUT_DIALOG_TAGS);
				((ImageView) v).setImageResource(R.drawable.upload_vid_add_tags_visited_btn);
			}
		});
		findViewById(R.id.upload_vid_submit_id).setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				playPlak();
				handler.sendEmptyMessage(UPLOAD_VIDEO_MSG_WHAT);
			}
		});
	}

	private void uploadVideo() {
		//dialog = ProgressDialog.show(UploadVideoActivity.this, "", getString(R.string.video_list_progress_dialog_title), false, false);
		dialog = new ProgressDialog(UploadVideoActivity.this);
		((ProgressDialog) dialog).setMessage(getString(R.string.video_list_progress_dialog_title));
		((ProgressDialog) dialog).setIndeterminate(false);
		((ProgressDialog) dialog).setCancelable(false);
		((ProgressDialog) dialog).setProgressStyle(ProgressDialog.STYLE_HORIZONTAL);
		((ProgressDialog) dialog).show();
		Runnable r = new Runnable() {
			public void run() {
				Uri uri = ChooseVidCategoryActivity.tempVid;
				String[] filePathColumn = { MediaStore.Video.Media.DATA };
				Cursor cursor = getContentResolver().query(uri, filePathColumn, null, null, null);
				cursor.moveToFirst();
				int columnIndex = cursor.getColumnIndex(filePathColumn[0]);
				String filePath = cursor.getString(columnIndex);
				cursor.close();
				Uri fileUri = Uri.parse("file://" + filePath);
				String title = "[" + uri.getQueryParameter("category") + "] - " + notesStr;
				String videoFilename = fileUri.getPath();
				int length = (int) new File(filePath).length();
				((ProgressDialog) dialog).setMax(length);
				YoutubeService service = new YoutubeService();
				service.apiKey = "key=" + getString(R.string.ytDevKey);
				service.appName = "ifonly-1.0";
				service.youtubeUser = getString(R.string.ytAccountName);
				service.youtubePassword = getString(R.string.ytAccountPass);
				AbstractInputStreamContent.uploadListener = UploadVideoActivity.this;
				try {
					Video video = service.uploadVideo(handler, new FileInputStream(new File(videoFilename)), videoFilename, title, notesStr, "Education", tagsStr);
					Log.d("uploadVideo", "Video: " + video.toString());
				} catch (FileNotFoundException e) {
					Log.e("uploadVideo", e.getMessage(), e);
				} catch (DebatingServiceException e) {
					Log.e("uploadVideo", e.getMessage(), e);
				} catch (Exception e) {
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
				playPlak();
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
				playPlak();
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
		if (msg.what == UPLOAD_VIDEO_MSG_WHAT) {
			uploadVideo();
		}
		if (msg.what == UPLOAD_VIDEO_SUCCESS_MSG_WHAT) {
			AlertDialog.Builder builder = new AlertDialog.Builder(this);
			builder.setMessage(getString(R.string.upload_vid_successful)).setCancelable(false).setPositiveButton("Ok", new DialogInterface.OnClickListener() {
				public void onClick(DialogInterface dialog, int id) {
					playPlak();
					startActivity(new Intent("ifonly.mainmenu"));
					finish();
				}
			});
			AlertDialog alert = builder.create();
			alert.setOwnerActivity(this);
			alert.show();
			if (dialog != null) {
				dialog.dismiss();
			}
		}
		if (msg.what == UPLOAD_VIDEO_ERROR_MSG_WHAT) {
			AlertDialog.Builder builder = new AlertDialog.Builder(this);
			builder.setMessage((String) msg.obj).setTitle(R.string.upload_vid_error).setCancelable(false).setPositiveButton("Ok", new DialogInterface.OnClickListener() {
				public void onClick(DialogInterface dialog, int id) {
					playPlak();
					dialog.dismiss();
				}
			});
			AlertDialog alert = builder.create();
			alert.setOwnerActivity(this);
			alert.show();
			if (dialog != null) {
				dialog.dismiss();
			}
		}
		if (msg.what == UPLOAD_VIDEO_PROGRESS) {
			ProgressDialog progDial = (ProgressDialog) dialog;
			progDial.setProgress(msg.arg1);
		}
		return false;
	}

	@Override
	public void uploadedBytes(long uploadedBytes, long contentLength) {
		if (dialog != null && dialog instanceof ProgressDialog) {
			Message msg = new Message();
			msg.what = UPLOAD_VIDEO_PROGRESS;
			msg.arg1 = (int) uploadedBytes;
			handler.sendMessage(msg);
		}
	}
}
