(function($) {
	window.fnames = new Array();
	 window.ftypes = new Array();
	 fnames[0]='EMAIL';
	ftypes[0]='email';
	fnames[1]='FNAME';
	ftypes[1]='text';
	fnames[2]='LNAME';
	ftypes[2]='text';
	$('#mc-embedded-subscribe-form').removeAttr('novalidate')
}(jQuery));

/* @see http://tympanus.net/codrops/2015/09/15/styling-customizing-file-inputs-smart-way */
var featuredImageUploader = document.getElementsByClassName('ninja-forms-field-featured-image-wrap')[0];
if (featuredImageUploader) {
	featuredImageUploader.addEventListener('change', function (e) {
		var label = e.target.parentElement.querySelector('label');
		label.textContent = 'Image File Loaded';
	});
}