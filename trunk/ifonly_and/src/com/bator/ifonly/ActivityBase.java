package com.bator.ifonly;

import android.app.Activity;
import android.media.MediaPlayer;
import android.os.Bundle;
import android.view.View;
import android.view.ViewGroup.LayoutParams;
import android.view.Window;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

public class ActivityBase extends Activity {
	private MediaPlayer mp;
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		super.onCreate(savedInstanceState);
	}
	protected void backButtonListenerSetup() {
		View backBtn = findViewById(R.id.top_bar_back_btn_id);
		if (backBtn != null) { //if back button exists
			backBtn.setOnClickListener(new View.OnClickListener() {
				public void onClick(View v) {
					playPlak();
					ActivityBase.this.finish();
				}
			});
		}
	}
	protected void setTopBarRightView(View view) {
		LinearLayout container = (LinearLayout) findViewById(R.id.top_bar_right_container_id);
		if (container != null) {
			container.addView(view);
		}
	}
	protected void setTopBarRightImage(int imgResId) {
		ImageView iv = new ImageView(this);
		iv.setImageResource(imgResId);
		iv.setLayoutParams(new LayoutParams(LayoutParams.WRAP_CONTENT, LayoutParams.WRAP_CONTENT));
		setTopBarRightView(iv);
	}
	protected void setTopBarTitle(String title) {
		TextView titleView = (TextView) findViewById(R.id.top_bar_title_id);
		if (titleView != null) {
			titleView.setText(title);
		}
	}
	public void playPlak() {
		if (mp == null) {
			mp = MediaPlayer.create(this, R.raw.plak);
		}
		mp.setOnCompletionListener(new MediaPlayer.OnCompletionListener() {
			public void onCompletion(MediaPlayer mp) {
				ActivityBase.this.releaseMediaPlayer();
			}
		});
		mp.start();
	}
	@Override
	protected void onDestroy() {
		releaseMediaPlayer();
		super.onDestroy();
	}
	public void releaseMediaPlayer() {
		if (mp != null) {
			mp.release();
			mp = null;
		}
	}
}