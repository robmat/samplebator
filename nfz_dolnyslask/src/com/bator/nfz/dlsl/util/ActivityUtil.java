package com.bator.nfz.dlsl.util;

import android.app.Activity;
import android.app.AlertDialog.Builder;
import android.app.Dialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.DialogInterface.OnClickListener;
import android.net.ConnectivityManager;

import com.bator.nfz.dlsl.R;
import com.bator.nfz.dlsl.app.NfzApp;

public class ActivityUtil {
	public static boolean isOnline(Activity activity) {
	    ConnectivityManager cm = (ConnectivityManager) activity.getSystemService(Context.CONNECTIVITY_SERVICE);
	    return cm.getActiveNetworkInfo().isConnectedOrConnecting();
	}
	public static Dialog showDialog(Activity activity, int title, int msg) {
		Builder builder = new Builder(activity).setTitle(title);
		builder.setMessage(msg).setPositiveButton("Ok", new OnClickListener() {
			public void onClick(DialogInterface dialog, int which) {
				dialog.dismiss();
			}
		});
		return builder.create();
	}
	public static Dialog showErrDialog(Activity activity, Exception e) {
		String errMsg = NfzApp.nfzApp.getString(R.string.err_msg) + " " + e.getClass() + ": " + e.getMessage();
		Builder builder = new Builder(activity).setTitle(R.string.err);
		builder.setMessage(errMsg).setPositiveButton("Ok", new OnClickListener() {
			public void onClick(DialogInterface dialog, int which) {
				dialog.dismiss();
			}
		});
		return builder.create();
	}
}
