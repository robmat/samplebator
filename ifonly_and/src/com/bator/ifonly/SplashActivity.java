package com.bator.ifonly;

import java.util.concurrent.ScheduledThreadPoolExecutor;
import java.util.concurrent.TimeUnit;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.view.Window;
import android.widget.ImageView;

public class SplashActivity extends Activity {
    private ScheduledThreadPoolExecutor scheduler = new ScheduledThreadPoolExecutor(1);
    private Runnable runnable;
    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        requestWindowFeature(Window.FEATURE_NO_TITLE);
        setContentView(R.layout.splash);
        if (getIntent().getData() != null) {
        	ImageView iv = (ImageView) findViewById(R.id.splash_image);
        	iv.setBackgroundResource(R.drawable.splash);
        } else {
        	runnable = new Runnable() {
    			@Override
    			public void run() {
    				startActivity(new Intent("ifonly.mainmenu"));
    			}
    		};
			scheduler.schedule(runnable, 15, TimeUnit.SECONDS);
        }
        findViewById(R.id.splash_start_btn_id).setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View view) {
				scheduler.remove(runnable);
				startActivity(new Intent("ifonly.mainmenu"));
			}
		});
    }
}