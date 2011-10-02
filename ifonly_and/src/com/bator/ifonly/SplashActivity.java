package com.bator.ifonly;

import java.util.concurrent.ScheduledThreadPoolExecutor;
import java.util.concurrent.TimeUnit;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.Window;
import android.widget.ImageView;

public class SplashActivity extends Activity {
    private ScheduledThreadPoolExecutor scheduler = new ScheduledThreadPoolExecutor(1);
    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        requestWindowFeature(Window.FEATURE_NO_TITLE);
        setContentView(R.layout.splash);
        if (getIntent().getData() != null) {
        	ImageView iv = (ImageView) findViewById(R.id.splash_image);
        	iv.setBackgroundResource(R.drawable.splash);
        } else {
        	scheduler.schedule(new Runnable() {
    			@Override
    			public void run() {
    				startActivity(new Intent("ifonly.mainmenu"));
    			}
    		}, 1, TimeUnit.SECONDS);
        }
    }
}