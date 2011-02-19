package com.bcm;

public interface IWaitableScreen extends IUICallback {
	public void startWaiting();

	public void stopWaiting();
	
	public void log(String s);
}
