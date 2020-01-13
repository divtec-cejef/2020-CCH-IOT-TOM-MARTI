/**
 * @author Tom Marti
 * @version 1.0
 */
(function (document) {
	let temp;

	function getTemp () {
	    $.ajax({
	       url : 'https://martitom.ch/temp/data.json',
	       type : 'GET',
	       dataType : 'json',
	       success : function(json, statut){
	           temp = json;
	           console.log(temp);
	           showLiveTemp();
	       }
	    });
	}

	function showLiveTemp () {
		$(".live .temp span").text(temp[temp.length - 1].temperature);
		$(".live .hum span").text(temp[temp.length - 1].humidity);
	}

	getTemp();
}(document));