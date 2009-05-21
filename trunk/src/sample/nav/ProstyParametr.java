package sample.nav;

public class ProstyParametr implements IParametr {
	private String nazwa;
	private String wartosc;
	
	public ProstyParametr(String nazwa, String wartosc) {
		super();
		if (nazwa == null || "".equals(nazwa)) {
			throw new IllegalArgumentException("Nazwa nie moze byæ pusta lun null!");
		}
		this.nazwa = nazwa;
		this.wartosc = wartosc;
	}
	public String getNazwa() {
		return nazwa;
	}
	public String getWartosc() {
		return wartosc == null ? "" : wartosc;
	}
	public void setNazwa(String nazwa) {
		this.nazwa = nazwa;
	}
	public void setWartosc(String wartosc) {
		this.wartosc = wartosc;
	}
}
