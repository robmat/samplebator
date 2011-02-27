package com.bcm;

import java.util.Date;
import java.util.Hashtable;

import net.rim.device.api.system.DeviceInfo;

public class DataCache implements IWaitableScreen {
	private static final int DOWN_PROC = 1;
	private static final int DOWN_SCEN = 2;
	private static final int DOWN_ASSE = 3;
	private int state = -1;
	public Hashtable[] processes;
	public Hashtable[] scenarios;
	public Hashtable[] assets;
	public Date processesDate;
	public Date scenariosDate;
	public Date assetsDate;
	public IDataCacheAware dataCacheAware;
	
	
	public DataCache(IDataCacheAware dataCacheAware) {
		super();
		this.dataCacheAware = dataCacheAware;
	}

	public boolean fillInCache() {
		try {
			DataReceiver dr = new DataReceiver();
			state = DOWN_PROC;
			dr.getAllData(EntryPoint.authUser, EntryPoint.authPass, DeviceInfo.getDeviceId(), DataCache.this, "getAllProcesses", null);
			state = DOWN_ASSE;
			dr.getAllData(EntryPoint.authUser, EntryPoint.authPass, DeviceInfo.getDeviceId(), DataCache.this, "getAllAssets", null);
			state = DOWN_SCEN;
			dr.getAllData(EntryPoint.authUser, EntryPoint.authPass, DeviceInfo.getDeviceId(), DataCache.this, "getAllScenarios", null);
		} catch (Exception e) {
			log(e.getClass() + " " + e.getMessage());
			return false;
		}
		return true;
	}

	public int callback(String msg) {
		switch (state) {
			case DOWN_PROC: {
				processes = XMLUtils.getArrayItems(XMLUtils.parseXML(msg));
				processesDate = new Date();
			}
			case DOWN_ASSE: {
				assets = XMLUtils.getArrayItems(XMLUtils.parseXML(msg));
				assetsDate = new Date();
			}
			case DOWN_SCEN: {
				scenarios = XMLUtils.getArrayItems(XMLUtils.parseXML(msg));
				scenariosDate = new Date();
			}	
		}
		return 0;
	}
	public void startWaiting() {}
	public void stopWaiting() {}
	public void log(String s) {
		System.out.println(s);
	}
}
