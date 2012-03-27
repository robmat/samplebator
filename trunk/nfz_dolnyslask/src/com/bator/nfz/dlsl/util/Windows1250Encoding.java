package com.bator.nfz.dlsl.util;

import java.util.HashMap;
import java.util.Map;
import java.util.Map.Entry;

public class Windows1250Encoding {
	public static Map<String, String> map = new HashMap<String, String>();
	static {
		map.put("¹", "%B9");
		map.put("ê", "%EA");
		map.put("³", "%B3");
		map.put("ñ", "%F1");
		map.put("ó", "%F3");
		map.put("œ", "%9C");
		map.put("¿", "%BF");
		map.put("¥", "%A5");
		map.put("Ê", "%CA");
		map.put("£", "%A3");
		map.put("Ñ", "%D1");
		map.put("Ó", "%D3");
		map.put("Œ", "%8C");
		map.put("¯", "%AF");
	}
	public static String encode(String str) {
		for (Entry<String, String> entry : map.entrySet()) {
			str = str.replaceAll(entry.getKey(), entry.getValue());
		}
		return str;
	}
}
