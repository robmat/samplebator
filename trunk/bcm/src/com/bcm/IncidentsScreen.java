package com.bcm;

import java.util.Hashtable;

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

public class IncidentsScreen extends CommonsForScreen implements IWaitableScreen, INavListener {
        public Dialog dialog;

        public IncidentsScreen() {
                setTitle(new LabelField(I18n.bundle.getString(BcmResource.incidentsLbl), DrawStyle.HCENTER | Field.USE_ALL_WIDTH));
                init();
                addMenuItem(new MenuItem(I18n.bundle.getString(BcmResource.refreshLbl), 0, 1) {
                        public void run() {
                                UiApplication.getUiApplication().popScreen(IncidentsScreen.this);
                                UiApplication.getUiApplication().pushScreen(new IncidentsScreen());
                        }
                });
        }
        public void log(String str) {
        }
        public void init() {
                final DataReceiver dr = new DataReceiver();
                new Thread(new Runnable() {
                        public void run() {
                                try {
                                        dr.getAllData(EntryPoint.authUser, EntryPoint.authPass, DeviceInfo.getDeviceId(), IncidentsScreen.this, "getAllIncidents", null);
                                } catch (Exception e) {
                                        IncidentsScreen.this.errorDialog(e.getClass().getName() + " " + e.getMessage());
                                        e.printStackTrace();
                                }
                        }
                }).start();
        }

        public int callback(String msg) {
                if (msg != null && msg instanceof String) {
                        Document doc = null;
                        doc = XMLUtils.parseXML(msg);
                        items = XMLUtils.getArrayItems(doc);
                        if (areAnyItemsThere()) {
                                for (int i = 0; i < items.length; i++) {
                                        String name = (String) items[i].get("SequenceName");
                                        String time = (String) items[i].get("IncidentTime");
                                        MultiRowLabelField mrlf = new MultiRowLabelField(new String[] { name, time }, Field.FOCUSABLE | Field.USE_ALL_WIDTH);
                                        mrlf.setArbitraryData(items[i]);
                                        mrlf.setNavListener(this);
                                        add(mrlf);
                                }
                        }
                }
                return 0;
        }

        public boolean onSavePrompt() {
                return true;
        }

        public void startWaiting() {
                dialog = new Dialog(Dialog.D_OK, I18n.bundle.getString(BcmResource.loadDataLbl) + "...", 0, Bitmap.getPredefinedBitmap(Bitmap.HOURGLASS), Field.FIELD_HCENTER);
                try {
                        ButtonField b = (ButtonField) ((Manager) ((Manager) dialog.getField(1)).getField(1)).getField(0);
                        b.setLabel(I18n.bundle.getString(BcmResource.cancelLbl));
                } catch (Exception e) {
                        IncidentsScreen.this.errorDialog(e.getClass().getName() + " " + e.getMessage());
                        e.printStackTrace();
                }
                dialog.show();
        }

        public void stopWaiting() {
                if (dialog != null && dialog.isDisplayed()) {
                        dialog.close();
                }
        }

        public void navClcik(Field f) {
                MultiRowLabelField mrlf = (MultiRowLabelField) f;
                Hashtable item = (Hashtable) mrlf.getArbitraryData();
                UiApplication.getUiApplication().pushScreen(new TasksScreen(TasksScreen.INCIDENT_TASKS, (String) item.get("Id"), (String) item.get("SequenceName")));
        }
}
