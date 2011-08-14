package com.bator.ifonly;

import java.util.concurrent.ScheduledThreadPoolExecutor;
import java.util.concurrent.TimeUnit;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;

public class SplashActivity extends Activity {
    private ScheduledThreadPoolExecutor scheduler = new ScheduledThreadPoolExecutor(1);
    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.splash);
        scheduler.schedule(new Runnable() {
			@Override
			public void run() {
				startActivity(new Intent("ifonly.mainmenu"));
			}
		}, 1, TimeUnit.SECONDS);
    }
}