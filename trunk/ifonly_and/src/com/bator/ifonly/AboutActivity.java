package com.bator.ifonly;

import java.io.InputStream;

import org.apache.commons.io.IOUtils;

import android.os.Bundle;
import android.util.Log;
import android.webkit.WebView;

import com.bator.ifonly.util.Utils;

public class AboutActivity extends ActivityBase {
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.about);
		setTopBarTitle(getString(R.string.about_title));
		backButtonListenerSetup();
		WebView wv = (WebView) findViewById(R.id.about_web_view_id);
		InputStream is = getResources().openRawResource(R.raw.about);
		try {
			wv.loadData(IOUtils.toString(is), "text/html", "utf-8");
		} catch (Exception e) {
			Log.e("CompetitionActivity", "onCreate", e);
		}
		wv.setWebViewClient(new Utils.LinkEnabledWebViewClient());
	}
}
