package com.bator.nhsc;

import android.app.Activity;
import android.os.Bundle;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.bator.nhsc.ResultItemizedOverlay.Entry;
import com.google.gson.Gson;

public class SiteDetailsActivity extends Activity {
	public static final String EXTRA_ENTRY_GSON_KEY = "EXTRA_ENTRY_GSON_KEY";
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.site_details);
		Entry entry = new Gson().fromJson(getIntent().getStringExtra(EXTRA_ENTRY_GSON_KEY), Entry.class);
		((TextView) findViewById(R.id.site_details_name)).setText(entry.name);
		((TextView) findViewById(R.id.site_details_email_text)).setText(entry.email);
		((TextView) findViewById(R.id.site_details_telephone_text)).setText(entry.telephone);
		((TextView) findViewById(R.id.site_details_postcode_text)).setText(entry.postcode);
		for (String addressComponent : entry.addressLines) {
			LinearLayout addressLayout = (LinearLayout) findViewById(R.id.site_details_address_lines_layout);
			TextView textView = new TextView(this);
			textView.setLayoutParams(new LinearLayout.LayoutParams(LinearLayout.LayoutParams.FILL_PARENT, LinearLayout.LayoutParams.WRAP_CONTENT));
			textView.setText(addressComponent);
			addressLayout.addView(textView, 0);
		}
	}
}
