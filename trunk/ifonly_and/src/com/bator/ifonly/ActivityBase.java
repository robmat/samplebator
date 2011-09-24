package com.bator.ifonly;

import android.app.Activity;
import android.media.MediaPlayer;
import android.os.Bundle;
import android.view.View;
import android.view.Window;
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
				@Override
				public void onClick(View v) {
					playPlak();
					ActivityBase.this.finish();
				}
			});
		}
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
		mp.start();
	}
	@Override
	protected void onDestroy() {
		if (mp != null) {
			mp.release();
		}
		super.onDestroy();
	}
}