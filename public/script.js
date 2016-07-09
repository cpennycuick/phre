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
	var hashPath = window.location.hash.substr(1).split('/');
	var tabID = (hashPath[0] || 'Home');

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
