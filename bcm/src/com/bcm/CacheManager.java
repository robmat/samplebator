package com.bcm;

import java.util.Hashtable;
import java.util.Vector;

public class CacheManager {
	public static final String GET_ALL_PROCESSES = "getAllProcesses";
	public static final String GET_ALL_SCENARIOS = "getAllScenarios";
	public static final String GET_ALL_ASSETS = "getAllAssets";
	public static final String GET_ALL_PROCESS_ASSETS = "getAllProcessAsset";
	public static final String GET_ALL_ASSETS_IT_INFRASTRUCTURE = "getAllAssetItInfrastructure";
	public static final String GET_ALL_IT_INFRASTRUCTURES = "getAllItInfrastructures";
	public static DataCache[] caches = null;

	public static void fillInCaches(final IDataCacheAware cacheAware) {
		new Thread(new Runnable() {
			public void run() {
				if (caches == null) {
					caches = new DataCache[] { 
							new DataCache(cacheAware, GET_ALL_PROCESSES), 
							new DataCache(cacheAware, GET_ALL_SCENARIOS), 
							new DataCache(cacheAware, GET_ALL_ASSETS), 
							new DataCache(cacheAware, GET_ALL_ASSETS_IT_INFRASTRUCTURE), 
							new DataCache(cacheAware, GET_ALL_PROCESS_ASSETS),
							new DataCache(cacheAware, GET_ALL_IT_INFRASTRUCTURES) };
					for (int i = 0; i < caches.length; i++) {
						if (!caches[i].fillInCache() && cacheAware != null) {
							cacheAware.showDialogWithMsg("Error filling " + caches[i].command + " cache.");
						}
					}
				}
			}
		}).start();
	}

	public static Hashtable[] getCache(String cacheKey, String itemId, final IDataCacheAware cacheAware) {
		DataCache resultCache = null;
		for (int i = 0; i < caches.length; i++) {
			if (caches[i].command.equals(cacheKey)) {
				resultCache = caches[i];
			}
		}
		if (resultCache == null) {
			return null;
		}
		if (resultCache.cache == null) {
			if (cacheAware != null) {
				cacheAware.showDialogWithMsg("Cache with name " + resultCache.command + " empty, refilling.");
			}
			final DataCache resultCacheTemp = resultCache;
			new Thread(new Runnable() {
				public void run() {
					resultCacheTemp.fillInCache();
				}
			}).start();
			return null;
		}
		if (itemId != null) {
			for (int i = 0; i < resultCache.cache.length; i++) {
				Hashtable item = resultCache.cache[i];
				if (item.containsKey("Id") && item.get("Id").equals(itemId)) {
					return new Hashtable[] { item };
				}
			}
		}
		return resultCache.cache;
	}

	public static Hashtable[] getAssetsByProcessIdCache(String processId, final IDataCacheAware cacheAware) {
		Hashtable[] assetProcessMaps = getCache(GET_ALL_PROCESS_ASSETS, null, cacheAware);
		Vector assetIds = new Vector();
		if (assetProcessMaps != null) {
			for (int i = 0; i < assetProcessMaps.length; i++) {
				Hashtable assetProcessMap = assetProcessMaps[i];
				if (assetProcessMap.containsKey("BusinessProcessId") && assetProcessMap.get("BusinessProcessId").equals(processId)) {
					assetIds.addElement(assetProcessMap.get("AssetId"));
				}
			}
		}
		Hashtable[] assets = getCache(GET_ALL_ASSETS, null, cacheAware);
		Vector tempResult = new Vector();
		if (assets != null) {
			for (int j = 0; j < assetIds.size(); j++) {
				for (int i = 0; i < assets.length; i++) {
					if (assets[i].containsKey("Id") && assets[i].get("Id").equals(assetIds.elementAt(j))) {
						tempResult.addElement(assets[i]);
					}
				}
			}
		}
		Hashtable[] result = new Hashtable[tempResult.size()];
		tempResult.copyInto(result);
		return tempResult.size() == 0 ?  null : result;
	}
	public static Hashtable[] getItInfraByAssetIdCache(String assetId, final IDataCacheAware cacheAware) {
		Hashtable[] itAssetMaps = getCache(GET_ALL_ASSETS_IT_INFRASTRUCTURE, null, cacheAware);
		Vector itInfraIds = new Vector();
		if (itAssetMaps != null) {
			for (int i = 0; i < itAssetMaps.length; i++) {
				Hashtable itAssetMap = itAssetMaps[i];
				if (itAssetMap.containsKey("AssetId") && itAssetMap.get("AssetId").equals(assetId)) {
					itInfraIds.addElement(itAssetMap.get("ItInfrastructureId"));
				}
			}
		}
		Hashtable[] infras = getCache(GET_ALL_IT_INFRASTRUCTURES, null, cacheAware);
		Vector tempResult = new Vector();
		if (infras != null) {
			for (int j = 0; j < itInfraIds.size(); j++) {
				for (int i = 0; i < infras.length; i++) {
					if (infras[i].containsKey("Id") && infras[i].get("Id").equals(itInfraIds.elementAt(j))) {
						tempResult.addElement(infras[i]);
					}
				}
			}
		}
		Hashtable[] result = new Hashtable[tempResult.size()];
		tempResult.copyInto(result);
		return tempResult.size() == 0 ?  null : result;
	}
	
}
