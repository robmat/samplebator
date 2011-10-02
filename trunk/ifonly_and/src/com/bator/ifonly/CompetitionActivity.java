package com.bator.ifonly;

import java.io.InputStream;

import org.apache.commons.io.IOUtils;

import android.os.Bundle;
import android.util.Log;
import android.webkit.WebView;

import com.bator.ifonly.util.Utils;

public class CompetitionActivity extends ActivityBase {
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.competition);
		backButtonListenerSetup();
		setTopBarTitle(getString(R.string.competition_title));
		WebView wv = (WebView) findViewById(R.id.competition_web_view_id);
		InputStream is = getResources().openRawResource(R.raw.competitors);
		try {
			wv.loadData(IOUtils.toString(is), "text/html", "utf-8");
		} catch (Exception e) {
			Log.e("CompetitionActivity", "onCreate", e);
		}
		wv.setWebViewClient(new Utils.LinkEnabledWebViewClient());
	}
}
