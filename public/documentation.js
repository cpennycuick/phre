function renderDocumentation (data) {
	var referenceParts = [];
	var contentParts = [];

	var hashRoot = 'Documentation';

	var entry, hash;
	for (var i in data) {
		entry = data[i];

		hash = hashRoot+'/'+entry.ClassName.split('\\').join('/');

		referenceParts.push('<li><a href="#'+hash+'">'+entry.ClassName+'</a></li>');
		contentParts.push('<h2><a name="'+hash+'"></a>'+entry.ClassName+'</h2>'+(entry.Description || '<i>Undocumented</i>'));

		if (Object.keys(entry.Methods).length) {
			for (var method in entry.Methods) {
				contentParts.push('<h3>'+method+'</h3>'+(entry.Methods[method] || '<i>Undocumented</i>'));
			}
		}
	}

	var content = '<ul>'+referenceParts.join('')+'</ul>'+contentParts.join('');

	window.onload.add(function() {
		document.getElementById('DocumentationContent').innerHTML = content;
	});
};
