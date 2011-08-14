package com.bator.ifonly;

import android.app.Activity;
import android.os.Bundle;
import android.view.View;
import android.widget.TextView;

public class ActivityBase extends Activity {
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		View backBtn = findViewById(R.id.top_bar_back_btn_id);
		if (backBtn != null) { //if back button exists
			backBtn.setOnClickListener(new View.OnClickListener() {
				@Override
				public void onClick(View v) {
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
}