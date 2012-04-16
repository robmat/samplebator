package com.bator.nhsc.util;

import java.util.ArrayList;
import java.util.List;

import org.w3c.dom.Node;
import org.w3c.dom.NodeList;

public class XmlUtil {
	public static String getTextFromNode(Node linkChild) {
		String result = "";
		if (linkChild != null) {
			for (int i = 0; i < linkChild.getChildNodes().getLength(); i++) {
				Node node = linkChild.getChildNodes().item(i);
				result += node.getNodeValue() != null ? node.getNodeValue().trim() : "";
			}
		}
		return result;
	}

	public static Node getChildElementByName(Node parent, String name) {
		if (parent != null) {
			NodeList children = parent.getChildNodes();
			for (int i = 0; i < children.getLength(); i++) {
				Node child = children.item(i);
				if (child.getNodeType() == Node.ELEMENT_NODE && child.getNodeName().equals(name)) {
					return child;
				}
			}
		}
		return null;
	}

	public static Node[] getChildElementsByName(Node parent, String name) {
		List<Node> result = new ArrayList<Node>();
		if (parent != null) {
			NodeList children = parent.getChildNodes();
			for (int i = 0; i < children.getLength(); i++) {
				Node child = children.item(i);
				if (child.getNodeType() == Node.ELEMENT_NODE && child.getNodeName().equals(name)) {
					result.add(child);
				}
			}
		}
		return result.toArray(new Node[result.size()]);
	}

	public static boolean hasAttributeWithGivenValue(Node node, String attrName, String attrValue) {
		if (node.getNodeType() == Node.ELEMENT_NODE) {
			return node.getAttributes().getNamedItem(attrName) != null && node.getAttributes().getNamedItem(attrName).getNodeValue().equals(attrValue);
		}
		return false;
	}

	public Node[] getElementChildNodes(Node parent) {
		NodeList children = parent.getChildNodes();
		List<Node> result = new ArrayList<Node>();
		for (int i = 0; i < children.getLength(); i++) {
			Node child = children.item(i);
			if (child.getNodeType() == Node.ELEMENT_NODE) {
				result.add(child);
			}
		}
		return result.toArray(new Node[result.size()]);
	}
	public static String getChildText(Node parent, String name) {
		Node child = getChildElementByName(parent, name);
		return getTextFromNode(child);
	}

	public static String getAttributeValue(Node node, String attrName) {
		return node.getAttributes() != null && node.getAttributes().getNamedItem(attrName) != null ? node.getAttributes().getNamedItem(attrName).getNodeValue() : "";
	}
}
