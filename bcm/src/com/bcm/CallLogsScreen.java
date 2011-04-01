package com.bcm;

import net.rim.device.api.system.Bitmap;
import net.rim.device.api.system.DeviceInfo;
import net.rim.device.api.ui.DrawStyle;
import net.rim.device.api.ui.Field;
import net.rim.device.api.ui.Manager;
import net.rim.device.api.ui.MenuItem;
import net.rim.device.api.ui.UiApplication;
import net.rim.device.api.ui.component.ButtonField;
import net.rim.device.api.ui.component.Dialog;
import net.rim.device.api.ui.component.LabelField;

import org.w3c.dom.Document;

public class CallLogsScreen extends CommonsForScreen implements IWaitableScreen {

        private Dialog dialog;
        private ColouredListField clf;

        public CallLogsScreen() {
                setTitle(new LabelField(I18n.bundle.getString(BcmResource.callLogsLbl), DrawStyle.HCENTER | Field.USE_ALL_WIDTH));
                init();
                addMenuItem(new MenuItem(I18n.bundle.getString(BcmResource.refreshLbl), 0, 1) {
                        public void run() {
                                UiApplication.getUiApplication().popScreen(CallLogsScreen.this);
                                UiApplication.getUiApplication().pushScreen(new CallLogsScreen());
                        }
                });
        }

        public void log(String str) {
        }

        private void init() {
                final DataReceiver dr = new DataReceiver();
                new Thread(new Runnable() {
                        public void run() {
                                try {
                                        dr.getAllData(EntryPoint.authUser, EntryPoint.authPass, DeviceInfo.getDeviceId(), CallLogsScreen.this, "getAllCallLogs", null);
                                } catch (Exception e) {
                                        CallLogsScreen.this.errorDialog(e.getClass().getName() + " " + e.getMessage());
                                        e.printStackTrace();
                                }
                        }
                }).start();
        }

        public void startWaiting() {
                dialog = new Dialog(Dialog.D_OK, I18n.bundle.getString(BcmResource.loadDataLbl) + "...", 0, Bitmap.getPredefinedBitmap(Bitmap.HOURGLASS), Field.FIELD_HCENTER);
                try {
                        ButtonField b = (ButtonField) ((Manager) ((Manager) dialog.getField(1)).getField(1)).getField(0);
                        b.setLabel(I18n.bundle.getString(BcmResource.cancelLbl));
                } catch (Exception e) {
                        CallLogsScreen.this.errorDialog(e.getClass().getName() + " " + e.getMessage());
                        e.printStackTrace();
                }
                dialog.show();
        }

        public void stopWaiting() {
                if (dialog != null && dialog.isDisplayed()) {
                        dialog.close();
                }
        }

        protected boolean onSavePrompt() {
                return true;
        }

        public int callback(String msg) {
                if (msg != null && msg instanceof String) {
                        Document doc = null;
                        try {
                                doc = XMLUtils.parseXML(msg);
                        } catch (Exception e) {
                                CallLogsScreen.this.errorDialog(e.getClass().getName() + " " + e.getMessage());
                                e.printStackTrace();
                        }
                        items = XMLUtils.getArrayItems(doc);
                        if (areAnyItemsThere()) {
                                final String[][] itemStrArr = new String[items.length][];
                                for (int i = 0; i < items.length; i++) {
                                        String name = (String) items[i].get("PhoneNumber");
                                        String status = (String) items[i].get("Status");
                                        String time = (String) items[i].get("CallTime");
                                        try {
                                                status = Dictionary.getDictionaryValue("CALL_STATUS", status, status);
                                        } catch (Exception e) {
                                                CallLogsScreen.this.errorDialog(e.getClass().getName() + " " + e.getMessage());
                                                e.printStackTrace();
                                        }
                                        itemStrArr[i] = new String[] { name, status, time };
                                }
                                CellularLabelfield lf = new CellularLabelfield(new String[] { I18n.bundle.getString(BcmResource.numberLbl), I18n.bundle.getString(BcmResource.statusLbl), I18n.bundle.getString(BcmResource.timeLbl) }, new int[] { 30, 20, 50 }, 0, true);
                                add(lf);
                                clf = new ColouredListField(itemStrArr.length, new int[] { 30, 20, 50 }, itemStrArr);
                                clf.set(new String[itemStrArr.length]);
                                add(clf);
                        }
                }
                return 0;
        }
}
