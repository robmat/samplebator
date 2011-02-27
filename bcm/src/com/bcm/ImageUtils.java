package com.bcm;

import net.rim.device.api.math.Fixed32;
import net.rim.device.api.system.EncodedImage;

public class ImageUtils {
	public static EncodedImage resize(EncodedImage img, int width, int height) {
		int divisorX = Fixed32.toFP(width);
		int multiplierX = Fixed32.toFP(img.getWidth());
		int fixedX = Fixed32.toFP(1);
		fixedX = Fixed32.div(fixedX, divisorX);
		fixedX = Fixed32.mul(fixedX, multiplierX);

		int divisorY = Fixed32.toFP(height);
		int multiplierY = Fixed32.toFP(img.getHeight());
		int fixedY = Fixed32.toFP(1);
		fixedY = Fixed32.div(fixedY, divisorY);
		fixedY = Fixed32.mul(fixedY, multiplierY);
		return img.scaleImage32(fixedX, fixedY);
	}
}
