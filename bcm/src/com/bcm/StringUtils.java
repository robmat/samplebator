package com.bcm;

public class StringUtils {
	public static String[] createEvenRows(int[] colWidths, String[][] texts) {
		String[] result = new String[texts.length];
		for (int rowCount = 0; rowCount < result.length; rowCount++) {
			result[rowCount] = "";
			for (int colCount = 0; colCount < texts[rowCount].length; colCount++) {
				String text = texts[rowCount][colCount];
				int width = colWidths[colCount];
				result[rowCount] += text.length() > width - 1 ? text.substring(0, width - 3) + "..." : text;
				result[rowCount] += space(width - result[rowCount].length());
			}
		}
		return result;
	}
	private static String space(int i) {
		String result = "";
		for (int j =0; j < i; j++) {
			result += " ";
		}
		return result;
	}
}
