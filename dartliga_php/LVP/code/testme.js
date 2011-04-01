function testsec(){
	$('#debug').html('<h2>Running Test</h2>');
	$.post('lsdb/testsec.php',{action:'test'},function(data){$('#debug').html(data)});
};
function testinfo(){
	$('#debug').html('<h2>Running Info</h2>');
	$.post('lsdb/testsec.php',{action:'info'},function(data){$('#debug').html(data)});
};