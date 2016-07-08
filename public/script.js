function setupQueue () {
	var queue = [];

	var queueFn = function() {
		for (var i in queue) {
			queue[i]();
		}
	};

	queueFn.add = function (fn) {
		queue.push(fn);
	};

	return queueFn;
}

window.onload = setupQueue();

window.onload.add(function() {
	document.getElementById('NavOpen').onclick = function () {
		toggleClass(document.querySelector('nav'), 'open');
	};

	if (!hasHashChangeSupport()) {
		showAllTabs();
		return;
	}

	switchTab();
	window.onhashchange = function () {
		switchTab();
	};
});

function hasHashChangeSupport () {
//	return false;
	return ('onhashchange' in window);
}

function switchTab() {
	var tabID = (window.location.hash.substr(1) || 'Home');

	removeClass(document.querySelector('nav'), 'open');

	var href, hash;
	var allNavLinks = document.querySelectorAll('nav a');
	for (var i = 0; i < allNavLinks.length; i++) {
		href = allNavLinks[i].href;
		hash = href.substr(href.indexOf('#')+1);
		if (hash === tabID) {
			addClass(allNavLinks[i], 'selected');
		} else {
			removeClass(allNavLinks[i], 'selected');
		}
	}

	var selected = false;
	var allTabs = document.querySelectorAll('.tab');
	for (var i = 0; i < allTabs.length; i++) {
		if (allTabs[i].id === 'Tab-'+tabID) {
			selected = true;
			addClass(allTabs[i], 'selected');
		} else {
			removeClass(allTabs[i], 'selected');
		}
	}

	if (!selected) {
		addClass(document.getElementById('Tab-Unknown'), 'selected');
	}
}

function showAllTabs() {
	var allTabs = document.querySelectorAll('.tab');
	for (var i = 0; i < allTabs.length; i++) {
		addClass(allTabs[i], 'selected');
	}
}

function addClass(element, className) {
	removeClass(element, className);
	element.className = (element.className + ' ' + className).trim();
}
function removeClass(element, className) {
	var regex = new RegExp('\\b'+className+'\\b\\s*', 'g');
	element.className = (element.className || '').replace(regex, '').trim();
}
function toggleClass(element, className) {
	if ((element.className || '').match('\\b' + className + '\\b')) {
		removeClass(element, className);
	} else {
		addClass(element, className);
	}
}

function renderExamples (data) {
	var reference = [];
	var content = [];

	var examplesRoot = 'examples';
	var hashRoot = (window.location.hash || '').substr(1);

	var entry, hash, url;
	for (var i = 0; i < data.length; i++) {
		var entry = data[i];

		hash = hashRoot+'/'+entry.Path;
		url = examplesRoot+'/'+entry.Path+'/example.';

		reference.push('<li><a href="#'+hash+'">'+entry.Name+'</a></li>')
		content = content.concat([
			'<h2><a name="'+hash+'"></a>'+entry.Name+'</h2>',
			'<div>',
				([
					'<a href="https://github.com/cpennycuick/phre/tree/gh-pages/examples/'+entry.Path+'/" target=_blank">GitHub</a>',
					'<a href="'+url+'html" target="_blank">HTML</a>',
					'<a href="'+url+'pdf" target="_blank">PDF</a>'
				]).join(' / '),
			'</div>',
			'<p>'+entry.Description+'</p>'
		]);
	}

	window.onload.add(function() {
		document.getElementById('ExamplesList').innerHTML = '<ul>'+reference.join('')+'</ul>'+content.join('');
	});
}
