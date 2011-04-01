/* 
 * This is the main lsdb class which is responsible for
 * loading the api files and initializing the objects
 */
var AppRoot="http://www.dartligaverwaltung.de/LVP"
// make sure our supporter lib is loaded
var f=document.createElement('script');
f.setAttribute("type","text/javascript");
f.setAttribute("src", AppRoot+"/api/jquery-1.3.1.js");
document.getElementsByTagName("head")[0].appendChild(f);

function lsdb() {
  this.tabelle = new tabelle();
  this.rangliste = new rangliste();
};

/*
 * Tabelle object, defaults set to 
 * mode=minimum
 * sequence=points
 * we use private vars and public setter methods
 */
function tabelle() {
    var event_id=0;
    var displayfull=0;
    var rankingsets=0;
    var target='debug';
    var tabelleurl=AppRoot+'/snippets/retTabelleById.php?jsonp_callback=?';

    this.settarget=function(val){
      (typeof(val) == 'string' ? target = val : target = 'debug');
    };
    this.seteventid=function(val){
      (typeof(val) == 'number' && val >= 0 ? event_id = val : event_id = 0);
    };
    this.setdisplaymode=function(val){
      (typeof(val) == 'number' && val < 2 ? displayfull = val : displayfull = 0);
    };
    this.setrankingmode=function(val){
      (typeof(val) == 'number' && val < 2 ? rankingsets = val : rankingsets = 0);
    };
    this.fetch=function(){
        //fetch stuff using JSONP and pass back result.
        var con='';
        $(document).ready(function(){
            $('#'+target).html('Loading Data ...');
            $.getJSON(tabelleurl,
                {eventid:event_id,fullmode:displayfull,setmode:rankingsets,format:"jsonp"},
                function(data){
                    $('#'+target).html('');
                    $('<table id="lsdb_tl'+event_id+'"></table>').appendTo('#'+target);
                    con='<tr style="background-color:black;color:white;font-bold:true;"><td>Team Name</td><td>Punkte</td></tr>';
                    $.each(data.tabelle, function(i,row){
                        con=con+'<tr><td>'+row.Team+'</td><td>'+row.Points+'</td></tr>';
                        });
                    //alert(con);
                    $(con).appendTo('#lsdb_tl'+event_id);
                    //$('debug').html(con);
                }
            );
        });
    };
    this.toString=function(){
        return('Tabelle Event:'+event_id);
    };
    
};


function rangliste() {
    var gender='H';
    var modus='E';
    var verband='OEDV';
    this.setgender=function(val){
      if( typeof(val) == 'string' && val.length <2 ) {
        gender = val;
      } else {
        gender='H';
      }
    };
    this.setmodus=function(val){
      if( typeof(val) == 'string' && val.length <2 ) {
        modus = val;
      } else {
        modus='H';
      }
    };
    this.setverband=function(val){
      if( typeof(val) == 'string' && val.length <6 ) {
        verband = val;
      } else {
        verband='OEDV';
      }
    };
    this.toString=function(){
        return('Rangliste: '+gender+' '+modus);
    };
    this.fetch=function(target){
        //fetch stuff using ax and pass back result.
        var rankingurl=AppRoot+'/lsdb/Rangliste.php';
        AjaxRequest.post(
		{
			'url':rankingurl
            ,'parameters':{'action':'fetch', 'rlgender':gender, 'rlmode':modus, 'rlverband':verband }
            ,'onLoading':function() { target.innerHTML = "Loading Ranking List ..."; }
			,'onSuccess':function(req){target.innerHTML = req.responseText;}
			,'groupName':'locbrowse'
			,'onError':function(req){ alert('Error\n'+req.statusText); }
            ,'timeout':5000
            ,'onTimeout':function(req){ alert('Request timed Out!'); }
            ,'onGroupBegin':function(){}
            ,'onGroupEnd':function(){}
		}
        );

    };
};

/*
 * some generic helpers
 */

function lsdb_appendDataRow(tblobj,dataset) {
	ridx=tblobj.rows.length;
	var r = tblobj.insertRow(ridx);		// FF needs the idx - cant use ()
	for(i in dataset)
	{
		c = r.insertCell(i);
		c.innerHTML=dataset[i];
	}
};