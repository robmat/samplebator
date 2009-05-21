package sample.nav;

import java.util.List;

public interface IObiektNawigacyjny {
	String getId();
	String getStrona();
	void setStrona(String strona);
	String getStronaURL();
	List<IParametr> getParametry();
	void dodajParametr(IParametr param);
	void usunParametr(IParametr param);
	boolean equals(Object obj);
}
