package com.bator.nfz.dlsl.util;

import java.util.HashMap;
import java.util.Map;
import java.util.Map.Entry;

public class Windows1250Encoding {
	public static Map<String, String> map = new HashMap<String, String>();
	static {
		map.put("�", "%B9");
		map.put("�", "%EA");
		map.put("�", "%B3");
		map.put("�", "%F1");
		map.put("�", "%F3");
		map.put("�", "%9C");
		map.put("�", "%BF");
		map.put("�", "%A5");
		map.put("�", "%CA");
		map.put("�", "%A3");
		map.put("�", "%D1");
		map.put("�", "%D3");
		map.put("�", "%8C");
		map.put("�", "%AF");
		map.put(" ", "%20");
	}
	public static String encode(String str) {
		for (Entry<String, String> entry : map.entrySet()) {
			str = str.replaceAll(entry.getKey(), entry.getValue());
		}
		return str;
	}
}
