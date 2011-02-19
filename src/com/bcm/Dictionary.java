package com.bcm;

import java.io.IOException;
import java.util.Hashtable;

import net.rim.device.api.i18n.Locale;
import net.rim.device.api.system.DeviceInfo;

public class Dictionary {
	public static Hashtable dictionary = null;

	public static String getDictionaryValue(String dictionaryKey, String key, String originalValue) throws IOException {
		if (Dictionary.dictionary == null) {
			DataReceiver dr = new DataReceiver();
			dr.getAllData(EntryPoint.authUser, EntryPoint.authPass, DeviceInfo.getDeviceId(), null, "getAllDictionaries", null);
			String msg = dr.getResult();
			Dictionary.dictionary = XMLUtils.getDictionary(msg);
		}
		if (Dictionary.dictionary != null) {
			Hashtable dict = (Hashtable) Dictionary.dictionary.get(dictionaryKey);
			if (dict != null) {
				String lang = Locale.getDefault().getLanguage();
				String value = (String) dict.get(key + "_" + lang);
				return value == null ? originalValue : value;
			}
		}
		return originalValue;
	}
}
