package com.bator.nhsc.util;

import org.w3c.dom.Node;

public class XmlUtil {
	public static String getTextFromNode(Node linkChild) {
		String result = "";
		for (int i = 0; i < linkChild.getChildNodes().getLength(); i++) {
			Node node = linkChild.getChildNodes().item(i);
			result += node.getNodeValue() != null ? node.getNodeValue().trim() : "";
		}
		return result;
	}
}
