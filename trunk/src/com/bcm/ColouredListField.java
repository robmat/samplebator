package com.bcm;

import java.util.Vector;

import net.rim.device.api.ui.Color;
import net.rim.device.api.ui.Graphics;
import net.rim.device.api.ui.XYRect;
import net.rim.device.api.ui.component.ListField;
import net.rim.device.api.ui.component.ListFieldCallback;

public class ColouredListField extends ListField implements ListFieldCallback {

        // private final int[] cols_blue = new int[] { Color.GRAY, Color.GRAY,
        // Color.WHITE, Color.GRAY, Color.GRAY, Color.WHITE };
        // private final int[] cols_sel_blue = new int[] { Color.GRAY, Color.GRAY,
        // Color.WHITE, Color.GRAY, Color.GRAY, Color.WHITE };
        // private final int[] cols_yel = new int[] { Color.GRAY, Color.GRAY,
        // Color.WHITE, Color.GRAY, Color.GRAY, Color.WHITE };
        // private final int[] cols_sel_yel = new int[] { Color.GRAY, Color.GRAY,
        // Color.WHITE, Color.GRAY, Color.GRAY, Color.WHITE };
        private boolean hasFocus; // =false
        private Vector listElements = null;
        private int[] widthPercent = null;
        private String[][] texts = null;
        private int[] rowsToIndicate;

        public ColouredListField(int size, int[] widthPercent, String[][] texts) {
                super(size);
                this.widthPercent = widthPercent;
                this.texts = texts;
        }

        public ColouredListField(int numRows, long style) {
                super(numRows, style);
        }

        // Handles moving the focus within this field.
        public int moveFocus(int amount, int status, int time) {
                invalidate(getSelectedIndex());
                return super.moveFocus(amount, status, time);
        }

        // Invoked when this field receives the focus.
        public void onFocus(int direction) {
                hasFocus = true;
                super.onFocus(direction);
        }

        // Invoked when a field loses the focus.
        public void onUnfocus() {
                hasFocus = false;
                super.onUnfocus();
                invalidate();
        }

        // Over ride paint to produce the alternating colours.
        public void paint(Graphics graphics) {
                // Get the current clipping region as it will be the only part that
                // requires repainting
                XYRect redrawRect = graphics.getClippingRect();
                if (redrawRect.y < 0) {
                        throw new IllegalStateException("Clipping rectangle is wrong.");
                }

                // Determine the start location of the clipping region and end.
                int rowHeight = getRowHeight();

                int curSelected;

                // If the ListeField has focus determine the selected row.
                if (hasFocus) {
                        curSelected = getSelectedIndex();
                } else {
                        curSelected = -1;
                }

                int startLine = redrawRect.y / rowHeight;
                int endLine = (redrawRect.y + redrawRect.height - 1) / rowHeight;
                endLine = Math.min(endLine, getSize() - 1);
                int y = startLine * rowHeight;

                // Setup the data used for drawing.
                int[] yInds = new int[] { y, y, y + rowHeight / 2, y + rowHeight, y + rowHeight, y + rowHeight / 2 };
                // int[] xInds = new int[] { 0, getPreferredWidth(),
                // getPreferredWidth(), getPreferredWidth(), 0, 0 };

                // Get the ListFieldCallback.
                // This sample assumes that the object returned by the get
                // method of the callback is a String or has a toString method.
                // If this is not the case you will need to add the required logic
                // for your implementation.
                // ListFieldCallback callBack = this.getCallback();
                // Calculate columns widths
                int screenWidth = redrawRect.width;
                float[] colWidths = new float[widthPercent.length];
                for (int i = 0; i < colWidths.length; i++) {
                        colWidths[i] = (int) (((float) widthPercent[i] / 100) * screenWidth);
                        for (int j = 0; j < texts.length; j++) {
                                String text = texts[j][i];
                                int textWidth = graphics.getFont().getAdvance(text);
                                boolean wide = false;
                                while (textWidth > colWidths[i]) {
                                        wide = true;
                                        text = text.substring(0, text.length() - 1);
                                        textWidth = graphics.getFont().getAdvance(text + "...");
                                }
                                texts[j][i] = text + (wide ? "..." : "");
                        }
                }
                // Prepare widths for drawing
                for (int i = colWidths.length - 1; i > 0; i--) {
                        colWidths[i] = colWidths[i - 1];
                }
                colWidths[0] = 0;
                for (int i = 2; i < colWidths.length; i++) {
                        if (i < colWidths.length) {
                                colWidths[i] += colWidths[i - 1];
                        }
                }
                // Draw each row
                for (; startLine <= endLine; ++startLine) {
                        // Draw the even and non selected rows.
                        // graphics.setColor(LIGHT_TEXT);
                        // int[] colours = null;
                        // if (texts[startLine].length > 1 && texts[startLine][1] != null &&
                        // texts[startLine][1].equals("Active")) {
                        // colours = startLine == curSelected ? cols_sel_blue : cols_blue;
                        // }
                        // if (texts[startLine].length > 1 && texts[startLine][1] != null &&
                        // texts[startLine][1].equals("Inactive")) {
                        // colours = startLine == curSelected ? cols_sel_yel : cols_yel;
                        // }
                        // graphics.setColor(startLine == curSelected ? Color.WHITE :
                        // Color.BLACK);
                        // graphics.drawShadedFilledPath(xInds, yInds, null, colours, null);
                        for (int i = 0; i < colWidths.length; i++) {
                                // if (i != 1) {
                                // graphics.setColor(startLine == curSelected ? Color.BLACK :
                                // Color.WHITE);
                                // } else {
                                // graphics.setColor(Color.WHITE);
                                // }
                                // graphics.drawText(texts[startLine][i], (int) colWidths[i] +
                                // 1, yInds[0] + 1);
                                graphics.setColor(startLine == curSelected ? Color.WHITE : Color.BLACK);
                                if (i == 1) {
                                        if (rowsToIndicate != null) {
                                                graphics.setColor(Color.GREEN);
                                                if (rowsToIndicate[startLine] == 1) {
                                                        graphics.setColor(Color.RED);
                                                }
                                        }
                                }
                                graphics.drawText(texts[startLine][i], (int) colWidths[i], yInds[0]);
                        }
                        // graphics.setColor(DARK_TEXT);

                        // Assign new values to the y axis moving one row down.
                        y += rowHeight;
                        yInds = new int[] { y, y, y + rowHeight / 2, y + rowHeight, y + rowHeight, y + rowHeight / 2 };
                }
        }

        public void set(String[] itemStrArr) {
                listElements = new Vector();
                if (itemStrArr != null) {
                        for (int i = 0; i < itemStrArr.length; i++) {
                                listElements.addElement(itemStrArr[i] == null ? "" : itemStrArr[i]);
                        }
                }
        }

        // Draws the list row.
        public void drawListRow(ListField list, Graphics g, int index, int y, int w) {
                // We don't need to draw anything here because it is handled
                // by the paint method of our custom ColouredListField.
        }

        // Returns the object at the specified index.
        public Object get(ListField list, int index) {
                return listElements.elementAt(index);
        }

        // Returns the first occurence of the given String, bbeginning the search at
        // index,
        // and testing for equality using the equals method.
        public int indexOfList(ListField list, String p, int s) {
                // return listElements.getSelectedIndex();
                return listElements.indexOf(p, s);
        }

        // Returns the screen width so the list uses the entire screen width.
        public int getPreferredWidth(ListField list) {
                return getWidth();
        }

        public void setRowsToIndicate(int[] rowsToIndicate) {
                this.rowsToIndicate = rowsToIndicate;
        }

}
