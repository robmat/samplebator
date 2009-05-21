

import java.io.BufferedInputStream;
import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.net.URL;
import java.net.URLConnection;

public class MyTeleport {
	public static void main(String[] args) throws IOException, InterruptedException {
		for (int i = 512; i < 1725; i++) {
			String fileStr = "c:/temp/" + i + ".jpg";
			FileOutputStream fos = new FileOutputStream(new File(fileStr));
			String urlStr = "http://images.celebscentral.net/images/celebrities/maria-menounos/maria-menounos_29" + i + ".jpg";
			URL url = new URL(urlStr);
			URLConnection conn = url.openConnection();
			InputStream is = conn.getInputStream();
			BufferedInputStream bis = new BufferedInputStream(is);
			byte[] b = new byte[256];
			while (bis.available() > 256) {
				bis.read(b);
				fos.write(b);
			}
			if (bis.available() > 0) {
				b = new byte[bis.available()];
				bis.read(b);
				fos.write(b);
			}
			fos.close();
		}
	}
}
