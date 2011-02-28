package com.bcm;

import java.util.Date;
import java.util.Hashtable;

import net.rim.device.api.system.DeviceInfo;

public class DataCache implements IWaitableScreen {
	public Hashtable[] cache;
	public Date cacheDate;
	public IDataCacheAware dataCacheAware;
	public String command;
	
	public DataCache(IDataCacheAware dataCacheAware, String command) {
		super();
		this.dataCacheAware = dataCacheAware;
		this.command = command;
	}

	public boolean fillInCache() {
		try {
			DataReceiver dr = new DataReceiver();
			dr.getAllData(EntryPoint.authUser, EntryPoint.authPass, DeviceInfo.getDeviceId(), DataCache.this, command, null);
		} catch (Exception e) {
			log(e.getClass() + " " + e.getMessage());
			return false;
		}
		return true;
	}

	public int callback(String msg) {
		cache = XMLUtils.getArrayItems(XMLUtils.parseXML(msg));
		cacheDate = new Date();
		return 0;
	}
	public void startWaiting() {}
	public void stopWaiting() {}
	public void log(String s) {
		System.out.println(s);
	}
}
