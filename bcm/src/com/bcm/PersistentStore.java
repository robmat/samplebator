package com.bcm;

import java.io.IOException;

import net.rim.device.api.system.PersistentObject;

public class PersistentStore {
	public final static String FILE_NAME = "file:///store/home/user/documents/store.txt";
//	public static Boolean set(String key, String value) {
//		try {
//			String prev = get(key);
//			if (prev != null) {
//				Document doc = XMLUtils.parseXML(readTextFile(FILE_NAME));
//				Node root = doc.getFirstChild();
//				for (int i = 0; i < root.getChildNodes().getLength(); i++) {
//					Node propNode = root.getChildNodes().item(i);
//					if (propNode.getNodeType() != Node.ELEMENT_NODE && !propNode.hasAttributes()) {
//						continue;
//					}
//					NamedNodeMap attrs = propNode.getAttributes();
//					Node attr = attrs.getNamedItem("key");
//					String attrVal = attr.getNodeValue();
//					if (attrVal.equals(key)) {
//						for (int j = 0; j < propNode.getChildNodes().getLength(); j++) {
//							propNode.removeChild(propNode.getChildNodes().item(j));
//						}
//						propNode.appendChild(doc.createTextNode(value));
//						writeTextFile(FILE_NAME, XMLUtils.getPrettyXml(doc));
//						return Boolean.TRUE;
//					}
//				}
//			} else {
//				createPropsFile();
//				Document doc = XMLUtils.parseXML(readTextFile(FILE_NAME));
//				Node root = doc.getFirstChild();
//				Element prop = doc.createElement("prop");
//				root.appendChild(prop);
//				prop.setAttribute("key", key);
//				prop.appendChild(doc.createTextNode(value));
//				String xml = XMLUtils.getPrettyXml(doc);
//				writeTextFile(FILE_NAME, xml);
//				return Boolean.FALSE;
//			}
//		} catch (Exception e) {
//			e.printStackTrace();
//		} catch (ParserConfigurationException e) {
//			e.printStackTrace();
//		} catch (SAXException e) {
//			e.printStackTrace();
//		}
//		return null;
//	}
//	private static boolean createPropsFile() throws IOException {
//		FileConnection fc = (FileConnection) Connector.open(FILE_NAME, Connector.READ_WRITE, true);
//		if (!fc.exists()) {
//			fc.create();
//			fc.close();
//			writeTextFile(FILE_NAME, XMLUtils.getNewPropXml());
//			return true;
//		} else {
//			fc.close();
//		}
//		return false;
//	}
//	/**
//	 * DOEST'N WORK
//	 * @param key
//	 * @return
//	 */
//	public static String get(String key) {
//		try {
//			createPropsFile();
//			Document doc = XMLUtils.parseXML(readTextFile(FILE_NAME));
//			Node root = doc.getFirstChild();
//			for (int i = 0; i < root.getChildNodes().getLength(); i++) {
//				Node propNode = root.getChildNodes().item(i);
//				if (propNode.getNodeType() != Node.ELEMENT_NODE && !propNode.hasAttributes()) {
//					continue;
//				}
//				NamedNodeMap attrs = propNode.getAttributes();
//				Node attr = attrs.getNamedItem("key");
//				String attrVal = attr.getNodeValue();
//				if (attrVal.equals(key)) {
//					return XMLUtils.getTextNodeValue(propNode);
//				}
//			}
//		} catch (Exception e) {
//			e.printStackTrace();
//		}
//		return null;
//	}
//	public static boolean del(String key) {
//		try {
//			if (!createPropsFile()) {
//				String txt = readTextFile(FILE_NAME);
//				Document doc = XMLUtils.parseXML(txt);
//				Node root = doc.getFirstChild();
//				for (int i = 0; i < root.getChildNodes().getLength(); i++) {
//					Node propNode = root.getChildNodes().item(i);
//					if (propNode.getNodeType() != Node.ELEMENT_NODE && !propNode.hasAttributes()) {
//						continue;
//					}
//					NamedNodeMap attrs = propNode.getAttributes();
//					Node attr = attrs.getNamedItem("key");
//					String attrVal = attr.getNodeValue();
//					if (attrVal.equals(key)) {
//						for (int j = 0; j < propNode.getChildNodes().getLength(); j++) {
//							propNode.removeChild(propNode.getChildNodes().item(j));
//						}
//						root.removeChild(propNode);
//						writeTextFile(FILE_NAME, XMLUtils.getPrettyXml(doc));
//						return true;
//					}
//				}
//			}
//		} catch (Exception e) {
//			e.printStackTrace();
//		}
//		return false;
//	}

	public static String readTextFile(String fName) throws IOException {
		PersistentObject po = net.rim.device.api.system.PersistentStore.getPersistentObject(1L);
		String str = (String) po.getContents();
		return str;
//		String result = null;
//		FileConnection fconn = null;
//		DataInputStream is = null;
//		try {
//			fconn = (FileConnection) Connector.open(fName, Connector.READ_WRITE);
//			is = fconn.openDataInputStream();
//			byte[] data = IOUtilities.streamToBytes(is);
//			result = new String(data);
//			//System.out.println("PersistentStore.writeTextFile(): " + result);
//		} finally {
//			try {
//				if (null != is) {
//					is.close();
//				}
//				if (null != fconn) {
//					fconn.close();
//				}
//			} catch (Exception e) {
//				System.out.println(e.getMessage());
//			}
//		}
//		return result;
	}

	public static void writeTextFile(String fName, String text) throws IOException {
		PersistentObject po = net.rim.device.api.system.PersistentStore.getPersistentObject(1L);
		po.setContents(text);
		po.commit();
		//System.out.println("PersistentStore.writeTextFile(): " + text);
//		DataOutputStream os = null;
//		FileConnection fconn = null;
//		try {
//			fconn = (FileConnection) Connector.open(fName, Connector.READ_WRITE);
//			if (fconn.exists() && fconn.canWrite()) {
//				fconn.delete();
//			}
//			fconn.create();
//			os = fconn.openDataOutputStream();
//			os.write(text.getBytes());
//		} finally {
//			try {
//				if (null != os) {
//					os.close();
//				}
//				if (null != fconn) {
//					fconn.close();
//				}
//			} catch (Exception e) {
//				System.out.println(e.getMessage());
//			}
//		}
	}

}