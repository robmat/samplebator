package com.bcm;

import java.io.ByteArrayInputStream;
import java.util.Hashtable;
import java.util.Vector;

import net.rim.device.api.xml.parsers.DocumentBuilder;
import net.rim.device.api.xml.parsers.DocumentBuilderFactory;
import net.rim.device.api.xml.parsers.ParserConfigurationException;

import org.w3c.dom.Document;
import org.w3c.dom.Node;
import org.xml.sax.SAXException;

public class XMLUtils {
	public static Document parseXML(String xmlStr) {
		Document doc = null;
		try {
			DocumentBuilderFactory docBuilderFactory = DocumentBuilderFactory.newInstance();
			DocumentBuilder docBuilder = docBuilderFactory.newDocumentBuilder();
			doc = docBuilder.parse(new ByteArrayInputStream((xmlStr).getBytes()));
			doc.getDocumentElement().normalize();
		} catch (ParserConfigurationException e) {
			System.out.println("XMLUtils.parseXML(): " + e.getMessage());
			e.printStackTrace();
		} catch (SAXException e) {
			System.out.println("XMLUtils.parseXML(): " + e.getMessage());
			e.printStackTrace();
		} catch (Exception e) {
			System.out.println("XMLUtils.parseXML(): " + e.getMessage());
			e.printStackTrace();
		}
		return doc;
	}

	public static Hashtable[] getArrayItems(Node root) {
		if (root != null) {
			Hashtable[] result = null;
			Vector v = new Vector();
			for (int i = 0; i < root.getChildNodes().getLength(); i++) {
				Node processNode = root.getChildNodes().item(i);
				if (processNode.getNodeType() != Node.ELEMENT_NODE) {
					continue;
				}
				v.addElement(new Hashtable());
				for (int j = 0; j < processNode.getChildNodes().getLength(); j++) {
					Node processPropertyNode = processNode.getChildNodes().item(j);
					String nodeName = processPropertyNode.getNodeName();
					String nodeValue = XMLUtils.getTextNodeValue(processPropertyNode);
					if (nodeName != null && nodeValue != null && !nodeValue.equals("")) {
						((Hashtable) v.elementAt(v.size() - 1)).put(nodeName, nodeValue.trim());
					}
				}
			}
			result = new Hashtable[v.size()];
			for (int i = 0; i < v.size(); i++) {
				result[i] = (Hashtable) v.elementAt(i);
			}
			return result;
		}
		return new Hashtable[0];
	}

	public static Hashtable[] getArrayItems(Document doc) {
		return doc == null ? new Hashtable[0] : XMLUtils.getArrayItems(doc.getFirstChild());
	}

	public static String getTextNodeValue(Node node) {
		String nodeValue = "";
		for (int k = 0; k < node.getChildNodes().getLength(); k++) {
			Node textNode = node.getChildNodes().item(k);
			if (textNode.getNodeType() == Node.TEXT_NODE) {
				nodeValue += textNode.getNodeValue() == null ? "" : textNode.getNodeValue();
			}
		}
		return XMLUtils.formatFloat(nodeValue).trim();
	}

	public static String formatFloat(String input) {
		try {
			if (input.indexOf(".") > -1) {
				float f = Float.parseFloat(input);
				f = (float) ((int) ((f * 100))) / 100;
				input = Float.toString(f);
			}
		} catch (Exception e) {
			return input;
		}
		return input;
	}

	public static Hashtable getDictionary(String msg) {
		Document doc = XMLUtils.parseXML(msg);
		Hashtable dict = new Hashtable();
		try {
			if (doc != null) {
				Node root = doc.getFirstChild();
				for (int i = 0; i < root.getChildNodes().getLength(); i++) {
					Node dictNode = root.getChildNodes().item(i);
					if (dictNode == null || dictNode.getNodeType() != Node.ELEMENT_NODE) {
						continue;
					}
					String dictKey = null;
					String key = null;
					for (int j = 0; j < dictNode.getChildNodes().getLength(); j++) {
						Node dictChildNode = dictNode.getChildNodes().item(j);
						if (dictChildNode == null || dictChildNode.getNodeType() != Node.ELEMENT_NODE) {
							continue;
						}
						if ("Type".equals(dictChildNode.getNodeName())) {
							dictKey = XMLUtils.getTextNodeValue(dictChildNode);
							if (!dict.containsKey(dictKey)) {
								dict.put(dictKey, new Hashtable());
							}
						}
						if ("Key".equals(dictChildNode.getNodeName()) && dict.get(dictKey) != null) {
							key = XMLUtils.getTextNodeValue(dictChildNode);
						}
						if ("ValuePL".equals(dictChildNode.getNodeName()) && dict.get(dictKey) != null && key != null) {
							Hashtable typedict = (Hashtable) dict.get(dictKey);
							typedict.put(key + "_pl", XMLUtils.getTextNodeValue(dictChildNode));
						}
						if ("ValueEN".equals(dictChildNode.getNodeName()) && dict.get(dictKey) != null && key != null) {
							Hashtable typedict = (Hashtable) dict.get(dictKey);
							typedict.put(key + "_en", XMLUtils.getTextNodeValue(dictChildNode));
						}
					}
				}
				return dict;
			}
		} catch (Exception e) {
			System.out.println("XMLUtils.getDictionary(): " + e.getMessage());
			e.printStackTrace();
		}
		return null;
	}

//	public static String getPrettyXml(Document doc) throws ParserConfigurationException, SAXException {
//		// configure the handler with the outputstream
//		ByteArrayOutputStream ba = new ByteArrayOutputStream();
//		XMLWriter writer = new XMLWriter(ba);
//		writer.setPrettyPrint();
//		// "parse" the document
//		DOMInternalRepresentation.parse(doc, writer);
//		// craete the string
//		byte bXml[] = ba.toByteArray();
//		String s = new String(bXml);
//		s = s.trim();
//		return s;
//	}
//	public static String getNewPropXml() {
//		return "<?xml version=\"1.0\" encoding=\"UTF-8\"?><props/>";
//	}
}
