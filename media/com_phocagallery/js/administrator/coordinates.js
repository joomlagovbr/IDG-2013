
function setPMGPSLongitude(inputValue) {
	longitudeValue = convertPMGPS(inputValue, 'longitude');
	window.top.document.forms.adminForm.elements.gpslongitude.value = longitudeValue;
}
function setPMGPSLatitude(inputValue) {
	latitudeValue = convertPMGPS(inputValue, 'latitude');
	window.top.document.forms.adminForm.elements.gpslatitude.value = latitudeValue;
}

function setPMGPSLongitudeJForm(inputValue) {
	longitudeValue = convertPMGPS(inputValue, 'longitude');
	if (window.parent) window.parent.phocaSelectMap_jform_gpslongitude(longitudeValue);
}

function setPMGPSLatitudeJForm(inputValue) {
	latitudeValue = convertPMGPS(inputValue, 'latitude');
	if (window.parent) window.parent.phocaSelectMap_jform_gpslatitude(latitudeValue);
}

function convertPMGPS(inputValue, type) {
	var status		= 1;
	var cAbs		= 0;
	var vAbs		= 1000000000;
	var degree		= 0;
	var minute		= 0;
	var second		= 0;
	var gpsValue	= '';
	var degrees		= 180;
	var potc		= '';
	var ms			= 60;
	
	if (type == 'longitude') {
		degrees	= 180;
	}
	
	if (type == 'latitude') {
		degrees	= 90;
	}

	if(isNaN(inputValue)) {
		return '';
	}
	
	if(inputValue < 0) {
		status = -1;
	}
	
	cAbs = Math.abs(Math.round(vAbs * inputValue));

	if(cAbs > (vAbs * degrees)) {
		return '';
	}

	degree = status * Math.floor(cAbs/vAbs);
	minute = Math.floor(ms * ((cAbs/vAbs) - Math.floor(cAbs/vAbs)));
	second = ms * Math.floor(vAbs * ((ms * ((cAbs/vAbs) - Math.floor(cAbs/vAbs))) - Math.floor(ms * ((cAbs/vAbs) - Math.floor(cAbs/vAbs)))) ) / vAbs;
	
	second = Math.round(second * 1000)/1000;
	
	if (degree < 0) {
		if (type == 'longitude') {
			potc = 'W';
		}
		if (type == 'latitude') {
			potc = 'S';
		}
	} 
	if (degree > 0) {
		if (type == 'longitude') {
			potc = 'E';
		}
		if (type == 'latitude') {
			potc = 'N';
		}
	}
	gpsValue = Math.abs(degree) + '\u00b0' + '\u0020' + minute + '\u0027' + '\u0020' + second + '\u0022' + potc;

	//status = 1;

	return gpsValue;
}
/* utf-8 test ěščřžýáíé */