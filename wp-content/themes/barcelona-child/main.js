(function($) {
	window.fnames = new Array();
	 window.ftypes = new Array();
	 fnames[0]='EMAIL';
	ftypes[0]='email';
	fnames[1]='FNAME';
	ftypes[1]='text';
	fnames[2]='LNAME';
	ftypes[2]='text';
	$('#mc-embedded-subscribe-form').removeAttr('novalidate');

	/* @see http://tympanus.net/codrops/2015/09/15/styling-customizing-file-inputs-smart-way */
	var featuredImageUploader = document.getElementsByClassName('ninja-forms-field-featured-image-wrap')[0];
	if (featuredImageUploader) {
		featuredImageUploader.addEventListener('change', function (e) {
			var label = e.target.parentElement.querySelector('label');
			label.textContent = 'Image File Loaded';
		});
	}

	window.twttr = (function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0],
	    t = window.twttr || {};
	  if (d.getElementById(id)) return t;
	  js = d.createElement(s);
	  js.id = id;
	  js.src = "https://platform.twitter.com/widgets.js";
	  fjs.parentNode.insertBefore(js, fjs);
	 
	  t._e = [];
	  t.ready = function(f) {
	    t._e.push(f);
	  };
	 
	  return t;
	}(document, "script", "twitter-wjs"));
	
	twttr.widgets.load(
	  document.getElementById("backfeed-twitter-widget")
	);
}(jQuery));

