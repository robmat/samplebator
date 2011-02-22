package com.bcm;

import net.rim.device.api.ui.component.LabelField;
import net.rim.device.api.ui.component.TextField;
import net.rim.device.api.ui.container.HorizontalFieldManager;

public class LabeledTextField extends HorizontalFieldManager {
        private LabelField labelField = new LabelField("", LabelField.NON_FOCUSABLE | LabelField.FIELD_LEFT);
        private TextField textField = new TextField(TextField.USE_ALL_WIDTH);

        public LabeledTextField(String label) {
                super();
                init(label);
        }

        public LabeledTextField(String label, long style) {
                super(style);
                init(label);
        }

        private void init(String label) {
                labelField.setText(label);
                add(labelField);
                add(textField);
        }

        public void setText(String text) {
                textField.setText(text);
        }
}
