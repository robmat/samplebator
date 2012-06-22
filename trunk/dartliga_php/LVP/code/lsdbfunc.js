$(document).ready(function () {
    $(".lsdbbutton").hover(function () {
        mover(this)
    }, function () {
        mout(this)
    })
});

function mover(item) {
    item.style.color = "white";
    item.style.backgroundColor = "#202088";
    item.style.cursor = "pointer"
};

function mout(item) {
    item.style.color = "black";
    item.style.backgroundColor = "#f8f8f8";
    item.style.cursor = "default"
};

function datarowover(item) {
    item.style.color = "#000000";
    item.style.backgroundColor = "#ccffcc";
    item.style.cursor = "pointer"
};

function datarowout(item) {
    item.style.color = "#444444";
    item.style.backgroundColor = "white";
    item.style.cursor = "default"
};

function clearTable(tbl) {
    var c = tbl.rows.length;
    for (i = 0; i < c; i++) {
        tbl.deleteRow(0)
    }
};

function writeHTML(element, sMsg) {
    document.getElementById(element).innerHTML = '';
    document.getElementById(element).innerHTML = sMsg
};

function selLigaChange() {
    var ev = $('#eventid').val();
    $('#mainpane').html('<div style=\'height:5px\'>&nbsp;</div><h3 id=\'pagetitle\'></h3><div id=maincontent><div id=mg></div><div id=mt></div></div>');
    $('#foot').html('');
    $.post('snippets/retEventPerfPerRound.php', {
        eventid: ev
    }, function (data) {
        $('#mg').html(data)
    });
    $.post('snippets/listEventTopWinners.php', {
        eventid: ev,
        limit: 10
    }, function (data) {
        $('#mt').html(data)
    })
};

function setmdate(obj) {
    var ty = obj.value;
    var y = new Date;
    var s = '';
    var e = '';
    var sy = y.getFullYear();
    switch (ty) {
    case '2':
        if (y.getMonth() < 6) {
            s = (sy - 1) + '-08-01';
            e = sy + '-07-31'
        } else {
            s = sy + '-08-01';
            e = (sy + 1) + '-07-31'
        }
        break;
    default:
        s = sy + '-01-01';
        e = sy + '-12-31';
        break
    }
    $('#vmstart').val(s);
    $('#vmend').val(e)
};

function genautopass() {
    checkempty('mtype');
    $.post('lsdb/AutoPass.php', {
        vid: $('#vid').val(),
        gdr: $('#vgender').val(),
        mtype: $('#mtype').val()
    }, function (data) {
        $('#vpassnr').val(data)
    })
};

function vereinstatcodeplayer(v) {
    var sc = $('#statcode').val();
    $('#qry').html('fetching ...');
    $.post('snippets/retEventTeamPlayer.php', {
        vereinid: v,
        eventstat: sc,
        paction: escape('rankingdetails')
    }, function (data) {
        $('#qry').html(data)
    })
};

function listmemberr() {
    $('#maincontent').html('<p>hole daten ...</p>');
    var r = $('#vrealm').val();
    var t = $('#mtyper').val();
    var c = $('#mcurrent').attr('checked');
    $.post('lsdb/Membership.php', {
        action: 'list',
        mrealm: r,
        mtype: t,
        mactive: c
    }, function (data) {
        $('#maincontent').html(data)
    })
};

function listmemberv(t, paction) {
    var v = $('#vid').val();
    $.post('lsdb/Membership.php', {
        action: 'list',
        vid: v,
        mtype: t,
        mactive: 'true'
    }, function (data) {
        $('#memberv').html(data)
    })
};

function listmemberp(pid) {
    $.post('lsdb/Membership.php', {
        pid: pid,
        action: 'listp'
    }, function (data) {
        $('#lstMember').html(data)
    })
};

function rankingdetails(oBtn, evid) {
    window.location = 'ls_stats.php?func=statlistdetail&eventid=' + escape(evid) + '&vindexdate=&pid=' + oBtn.id
};

function showmap(locid) {
    FL = window.open('snippets/retLocationMap.php?locid=' + locid, "DartsMap", "resizable=no,scrollbars=no,menubar=no,location=no,width=500,height=350,left=50,top=50")
};

function historymatch() {
    {
        $.post('snippets/showMatchChangeHistory.php', {
            matchkey: $('#vmkey').val()
        }, function (data) {
            $("#hmatch").html(data)
        })
    }
};

function chkplayerteam(pid) {
    $.post('snippets/retLineUpPlayer.php', {
        pid: pid,
        lactive: 1,
        wfid: $('#id').val()
    }, function (data) {
        $("#check").html(data)
    })
};

function chkplayermember(pid) {
    $.post('snippets/listMembership.php', {
        pid: pid,
        vid: 0,
        mactive: 0
    }, function (data) {
        $("#check").html(data)
    })
};

function chkplayerstat(pid, sid) {
    $.post('snippets/retStaticStatVal.php', {
        pid: pid,
        statid: sid,
        limit: 10
    }, function (data) {
        $("#check").html(data)
    })
};

function memberdel(mid, pid) {
    alert('Deleting Member:' + mid + ' for Player ' + pid);
    $.post('lsdb/Membership.php', {
        pid: pid,
        mid: mid,
        action: 'delete'
    }, function (data) {
        $("#frmMember").html(data);
        listmemberp(pid)
    })
};

function membersave(mid, pid) {
    checkempty('mtype');
    $.post('lsdb/Membership.php', {
        pid: pid,
        mid: mid,
        vid: $("#vid").val(),
        vpassnr: $("#vpassnr").val(),
        vmend: $("#vmend").val(),
        vmstart: $("#vmstart").val(),
        mtype: $("#mtype").val(),
        action: 'save'
    }, function (data) {
        $("#savemsg").html(data);
        listmemberp(pid)
    })
};

function memberedit(mid, pid) {
    $.post('lsdb/Membership.php', {
        pid: pid,
        mid: mid,
        action: 'edit'
    }, function (data) {
        $("#frmMember").html(data)
    })
};

function playeredit(pid) {
    window.location = 'dso_player.php?func=edit&vpid=' + pid
};

function vereinedit(vid) {
    window.location = 'dso_verein.php?func=edit&vvid=' + vid
};

function regbtnclick(btn) {
    var r = $('#realmid').val();
    switch (btn) {
    case 1:
        window.location = 'dso_player.php?func=list&realmid=' + r;
        break;
    case 2:
        window.location = 'dso_verein.php?func=list&realmid=' + r;
        break;
    case 3:
        window.location = 'dso_player.php?func=new&realmid=' + r;
        break;
    case 4:
        window.location = 'dso_verein.php?func=new&realmid=' + r;
        break
    }
};

function lsdbbtnclick(btn) {
    var ev = $('#eventid').val();
    switch (btn) {
    case 1:
        window.location = 'lsdbTeam.php?func=browse&eventid=' + ev;
        break;
    case 2:
        window.location = 'ls_system.php?func=newmatch&eventid=' + ev;
        break;
    case 3:
        window.location = 'ls_system.php?func=schedule&eventid=' + ev;
        break;
    case 4:
        window.location = 'ls_stats.php?eventid=' + ev;
        break;
    case 5:
        window.location = 'ls_tabelle.php?eventid=' + ev;
        break;
    case 6:
        $('#pagetitle').html('Alle Spielst\xe4tten');
        $.post('snippets/retLocationTable.php', {
            eventid: ev,
            extend: 'yes'
        }, function (data) {
            $('#maincontent').html(data)
        });
        break;
    case 7:
        window.location = 'ls_debug.php?eventid=' + ev;
        break;
    case 8:
        window.location = 'lsdbSys.php?func=teamperf&eventid=' + ev;
        break;
    case 9:
        window.location = 'lsdbTeam.php?func=newteam&eventid=' + ev;
        break;
    default:
        alert(btn + ' is not a valid choice');
        break
    }
};

function loadContent(file) {
    var head = document.getElementsByTagName('head').item(0);
    var scriptTag = document.getElementById('loadScript');
    if (scriptTag) head.removeChild(scriptTag);
    script = document.createElement('script');
    script.src = file;
    script.type = 'text/javascript';
    script.id = 'loadScript';
    head.appendChild(script)
};

function getlineup(teamID) {
    try {
        $('#lineUp').html('');
        $.post('snippets/retLineUpTable.php', {
            teamid: teamID
        }, function (data) {
            $("#lineUp").html(data)
        })
    } catch (e) {
        alert('Cannot show LineUp::unsupported browser ...')
    }
};

function getlineupperf(teamID) {
    try {
        $('#lineUpPerf').html('');
        $.post('snippets/retLineUpPerfTable.php', {
            teamid: teamID
        }, function (data) {
            $("#lineUpPerf").html(data)
        })
    } catch (e) {
        alert('Cannot show LineUpTeam Performance')
    }
};

function OpenSysWin(thisurl) {
    F1 = window.open(thisurl, "SysWin", "resizable=yes,scrollbars=yes,menubar=no,location=no,width=400,height=400,left=0,top=0")
};

function checkempty(objID) {
    var chkval = $('#' + objID).attr('value');
    try {
        if (chkval.length < 1) {
            throw new Error('LSDB')
        }
        if (chkval == 0) {
            throw new Error('LSDB')
        } else {
            $('#' + objID).css('background-color', 'white');
            return 0
        }
    } catch (e) {
        $('#' + objID).css('background-color', '#ffbbbb');
        return 1
    }
};