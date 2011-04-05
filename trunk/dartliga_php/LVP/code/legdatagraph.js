function writeGraph(resp, G) {
    records = resp.split('<br>');
    var gxw = 620;
    var xw = 2 * Math.floor(((gxw - 80) / records.length) / 2);
    if (xw < 4) {
        xw = 4
    };
    var gyw = 280;
    var gx = 10;
    G.setColor('#bbbbbb');
    for (i = 5; i < 21; i = i + 5) {
        G.drawLine(gx, gyw - (i * 10), gxw, gyw - (i * 10));
        G.drawString((i * 3) + ' Darts', gx, gyw - (i * 10))
    }
    for (i in records) {
        res = records[i].split(';');
        try {
            if (res[1] > 0) makeDataPoint(i, res, G, xw)
        } finally {}
    }
    G.setColor('#888888');
    G.drawRect(0, 0, gxw + 10, gyw + 10);
    G.drawRect(5, 5, gxw, gyw);
    G.setColor('#7777ff');
    G.fillRect((gxw + 20), 230, 20, 10);
    G.drawString('Score < 159', (gxw + 45), 230);
    G.setColor('#77ee77');
    G.fillRect((gxw + 20), 250, 20, 10);
    G.drawString('Check zum Sieg', (gxw + 45), 250);
    G.setColor('#ff8888');
    G.fillRect((gxw + 20), 270, 20, 10);
    G.drawString('Check verloren', (gxw + 45), 270);
    G.paint()
};

function makeDartsHist(resp, G, statcode) {
    records = resp.split('<br>');
    var gxw = 620;
    var gyw = 480;
    var gx = 10;
    var gy = 10;
    var xw = 10;
    var yfact = 15;
    G.setColor('#888888');
    G.drawRect(0, 0, gxw + 10, gyw + 10);
    for (i = 6; i < 61; i = i + 3) {
        G.drawString(i, gx + (xw * i), gyw - gy)
    }
    for (i = 2; i < 31; i = i + 2) {
        G.drawLine(gx + 5, gyw - gy - i * yfact, gx + 90, gyw - gy - i * yfact);
        G.drawString(i + ' Legs', gx + 5, gyw - gy - i * yfact)
    }
    G.setColor('#444444');
    G.drawLine(gx, gyw - gy, gxw, gyw - gy);
    G.drawLine(gx, gyw - gy, gx, gy);
    G.paint();
    G.setColor('#AABB00');
    for (i in records) {
        res = records[i].split(';');
        da = res[0];
        ct = res[1];
        if ((statcode == 3) || (statcode == 5)) da = da * 3;
        if (da > 0) G.fillRect(gx + (xw * da), gyw - gy - (yfact * ct), xw, (yfact * ct))
    }
    G.paint()
};

function makeDataPoint(cnt, datarow, G, xw) {
    var xoff = 80;
    G.setColor("#7777ff");
    G.fillRect(xoff + (xw * (cnt)), 280 - datarow[1] * 10, (xw - 1), datarow[1] * 10);
    if (datarow[2] == 501) {
        G.setColor("#77ee77")
    } else {
        G.setColor("#ff8888")
    }
    G.fillRect(xoff + (xw * (cnt)), 280 - datarow[3] * 10, (xw - 1), (datarow[3] * 10 - datarow[1] * 10));
    G.paint()
};

function perfgraph(p_id, stat_id, event_id, dstart, dend) {
    var dataurl = 'snippets/listLegPerformanceData.php';
    $('#JG').html('');
    var jg = new jsGraphics('JG');
    $.get(dataurl, {
        pid: p_id,
        statcode: stat_id,
        eventcode: event_id,
        startdate: dstart,
        enddate: dend
    }, function (data) {
        writeGraph(data, jg)
    })
};

function playerhist(eventid, scorecomp, pid) {
    if (pid == 0) return;
    if (eventid == 0) return;
    var dataurl = 'snippets/retEventHistogram.php';
    $('#JG').html('');
    var jg = new jsGraphics('JG');
    $.get(dataurl, {
        pid: escape(pid),
        eventid: escape(eventid),
        scorecomp: escape(scorecomp)
    }, function (data) {
        makeDartsHist(data, jg)
    })
};

function teamperf(eventid, statcode, scorecomp) {
    var oSel = document.getElementById('tid');
    var IDx = oSel.options.selectedIndex;
    var teamid = oSel.options[IDx].value;
    var dataurl = 'snippets/retEventHistogram.php';
    $('#JG').html('');
    var jg = new jsGraphics('JG');
    if (teamid == 0) return;
    $.get(dataurl, {
        statcode: escape(statcode),
        eventid: escape(eventid),
        tid: escape(teamid),
        scorecomp: escape(scorecomp)
    }, function (data) {
        makeDartsHist(data, jg, statcode)
    });
    getlineupperf(teamid);
    getlineup(teamid)
};