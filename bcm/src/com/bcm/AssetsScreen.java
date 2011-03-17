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

public class AssetsScreen extends CommonsForScreen implements IWaitableScreen {
        private Dialog dialog;
        private ColouredListField clf;
        private String id;

        public AssetsScreen(final String id, final String name) {
                this.id = id;
                String title = (name == null ? I18n.bundle.getString(BcmResource.allLbl) + " " : "") + (name == null ? I18n.bundle.getString(BcmResource.assetsLbl).toLowerCase() : I18n.bundle.getString(BcmResource.assetsLbl)) + (name != null ? " " + "(" + name + ")" : "");
                setTitle(new LabelField(title, DrawStyle.HCENTER | Field.USE_ALL_WIDTH));
                addMenuItem(new MenuItem(I18n.bundle.getString(BcmResource.refreshLbl), 0, 1) {
                        public void run() {
                                UiApplication.getUiApplication().popScreen(AssetsScreen.this);
                                UiApplication.getUiApplication().pushScreen(new AssetsScreen(id, name));
                        }
                });
                init();
        }
        public void log(String str) {
        }
        private void init() {
                new Thread(new Runnable() {
                        public void run() {
                                try {
                                        if (id != null) {
                                                DataReceiver dr = new DataReceiver();
                                                dr.getAllData(EntryPoint.authUser, EntryPoint.authPass, DeviceInfo.getDeviceId(), AssetsScreen.this, "getAssetsByProcess", "&processId=" + id);
                                        } else {
                                                DataReceiver dr = new DataReceiver();
                                                dr.getAllData(EntryPoint.authUser, EntryPoint.authPass, DeviceInfo.getDeviceId(), AssetsScreen.this, "getAllAssets", "");
                                        }
                                } catch (Exception e) {
                                        AssetsScreen.this.errorDialog(e.getClass().getName() + " " + e.getMessage());
                                        e.printStackTrace();
                                }
                        }
                }).start();
        }

        protected boolean navigationClick(int status, int time) {
                if (clf != null) {
                        int i = clf.getSelectedIndex();
                        UiApplication.getUiApplication().pushScreen(new AssetDetailScreen(items[i]));
                }
                return true;
        }

        public boolean onSavePrompt() {
                return true;
        }

        public int callback(String msg) {
                if (msg != null) {
                        Document doc = XMLUtils.parseXML(msg);
                        items = XMLUtils.getArrayItems(doc);
                        if (areAnyItemsThere()) {
                                final String[][] itemStrArr = new String[items.length][];
                                int[] rowsToIndicate = new int[items.length];
                                for (int i = 0; i < items.length; i++) {
                                        String name = (String) items[i].get("Name");
                                        String status = (String) items[i].get("Status");
                                        String assetType = (String) items[i].get("AssetType");
                                        if (status.equals("1")) {
                                                rowsToIndicate[i] = 1;
                                        }
                                        try {
                                                status = Dictionary.getDictionaryValue("ASSET_STATUS", status, status);
                                                assetType = Dictionary.getDictionaryValue("ASSET_TYPE", assetType, assetType);
                                        } catch (Exception e) {
                                                AssetsScreen.this.errorDialog(e.getClass().getName() + " " + e.getMessage());
                                                e.printStackTrace();
                                        }
                                        itemStrArr[i] = new String[] { name, status, assetType };
                                }
                                String[] labels = new String[] { I18n.bundle.getString(BcmResource.nameLbl), I18n.bundle.getString(BcmResource.statusLbl), I18n.bundle.getString(BcmResource.typeLbl) };
                                CellularLabelfield lf = new CellularLabelfield(labels, new int[] { 55, 20, 25 }, 0, true);
                                add(lf);
                                clf = new ColouredListField(itemStrArr.length, new int[] { 55, 20, 25 }, itemStrArr);
                                clf.set(new String[itemStrArr.length]);
                                clf.setRowsToIndicate(rowsToIndicate);
                                add(clf);
                        }
                }
                return 0;
        }

        public void startWaiting() {
                dialog = new Dialog(Dialog.D_OK, I18n.bundle.getString(BcmResource.loadDataLbl) + "...", 0, Bitmap.getPredefinedBitmap(Bitmap.HOURGLASS), Field.FIELD_HCENTER);
                try {
                        ButtonField b = (ButtonField) ((Manager) ((Manager) dialog.getField(1)).getField(1)).getField(0);
                        b.setLabel(I18n.bundle.getString(BcmResource.cancelLbl));
                } catch (Exception e) {
                        AssetsScreen.this.errorDialog(e.getClass().getName() + " " + e.getMessage());
                        e.printStackTrace();
                }
                dialog.show();
        }

        public void stopWaiting() {
                if (dialog != null && dialog.isDisplayed()) {
                        dialog.close();
                }
        }
}
