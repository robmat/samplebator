package sample.model;

public class Osoba {
	private int id;
	private String nazw;
	private String imie;
	
	public Osoba(int id, String imie, String nazw) {
		super();
		this.id = id;
		this.nazw = nazw;
		this.imie = imie;
	}
	public int getId() {
		return id;
	}
	public void setId(int id) {
		this.id = id;
	}
	public String getNazw() {
		return nazw;
	}
	public void setNazw(String nazw) {
		this.nazw = nazw;
	}
	public String getImie() {
		return imie;
	}
	public void setImie(String imie) {
		this.imie = imie;
	}
}
